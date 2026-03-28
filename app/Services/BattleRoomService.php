<?php

namespace App\Services;

use App\Models\BattleAnswer;
use App\Models\BattleParticipant;
use App\Models\BattleRoom;
use App\Models\BattleRound;
use App\Models\Question;
use App\Models\SystemSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BattleRoomService
{
    public function getMinimumQuestions(): int
    {
        return max(1, (int) SystemSetting::get('battle_min_questions', 5));
    }

    public function getMaximumQuestions(): int
    {
        return max($this->getMinimumQuestions(), (int) SystemSetting::get('battle_max_questions', 15));
    }

    public function getMinimumPlayers(): int
    {
        return max(2, (int) SystemSetting::get('battle_min_players', 2));
    }

    public function getInactivityTimeoutSeconds(?BattleRoom $room = null): int
    {
        $configured = (int) SystemSetting::get('battle_inactivity_timeout', 120);
        $questionTimer = $room?->question_timer_seconds ?? (int) SystemSetting::get('battle_question_timer', 30);

        return max($configured, $questionTimer + 30);
    }

    public function syncRoomState(BattleRoom $room): BattleRoom
    {
        return DB::transaction(function () use ($room) {
            /** @var BattleRoom $lockedRoom */
            $lockedRoom = BattleRoom::lockForUpdate()->findOrFail($room->id);

            if ($lockedRoom->status === 'waiting' && $lockedRoom->isLobbyExpired()) {
                $this->handleExpiredLobby($lockedRoom);
            }

            if ($lockedRoom->status === 'playing') {
                $currentRound = $lockedRoom->currentRound();

                if (!$currentRound) {
                    $this->finishRoom($lockedRoom);
                } elseif ($currentRound->isTimerExpired() || $currentRound->isAllAnswered()) {
                    $this->advanceToNextQuestion($lockedRoom);
                }
            }

            return $lockedRoom->fresh(['participants.user', 'course', 'rounds.question']);
        });
    }

    public function cleanupStaleRooms(): array
    {
        $started = 0;
        $cancelled = 0;
        $abandoned = 0;

        BattleRoom::where('status', 'waiting')
            ->orderBy('id')
            ->chunkById(100, function (Collection $rooms) use (&$started, &$cancelled) {
                foreach ($rooms as $room) {
                    if (!$room->isLobbyExpired()) {
                        continue;
                    }

                    $before = $room->status;
                    $updatedRoom = $this->syncRoomState($room);

                    if ($before === 'waiting' && $updatedRoom->status === 'playing') {
                        $started++;
                    }

                    if ($before === 'waiting' && $updatedRoom->status === 'finished' && $updatedRoom->winner_team === null) {
                        $cancelled++;
                    }
                }
            });

        BattleRoom::where('status', 'playing')
            ->orderBy('id')
            ->chunkById(100, function (Collection $rooms) use (&$abandoned) {
                foreach ($rooms as $room) {
                    if (!$this->isAbandoned($room)) {
                        continue;
                    }

                    if ($this->closeAbandonedRoom($room)) {
                        $abandoned++;
                    }
                }
            });

        return [
            'started' => $started,
            'cancelled' => $cancelled,
            'abandoned' => $abandoned,
        ];
    }

    public function closeAbandonedRoom(BattleRoom $room): bool
    {
        return DB::transaction(function () use ($room) {
            /** @var BattleRoom|null $lockedRoom */
            $lockedRoom = BattleRoom::lockForUpdate()->find($room->id);

            if (!$lockedRoom || $lockedRoom->status !== 'playing' || !$this->isAbandoned($lockedRoom)) {
                return false;
            }

            $this->finishRoom($lockedRoom);

            return true;
        });
    }

    public function startBattle(BattleRoom $room): bool
    {
        /** @var BattleRoom $room */
        $room = BattleRoom::lockForUpdate()->findOrFail($room->id);

        if ($room->status !== 'waiting') {
            return false;
        }

        $participants = $room->participants()->inRandomOrder()->get();
        $availableCount = Question::where('course_id', $room->course_id)->count();
        $minQuestions = $this->getMinimumQuestions();
        $maxQuestions = min($this->getMaximumQuestions(), $availableCount);

        if ($participants->count() < $this->getMinimumPlayers() || $availableCount < $minQuestions || $maxQuestions < 1) {
            $this->finishRoom($room);
            return false;
        }

        foreach ($participants as $index => $participant) {
            $participant->update([
                'team' => $index % 2 === 0 ? 'a' : 'b',
            ]);
        }

        $questionCount = rand($minQuestions, $maxQuestions);
        $questions = Question::where('course_id', $room->course_id)
            ->inRandomOrder()
            ->take($questionCount)
            ->get();

        if ($questions->isEmpty()) {
            $this->finishRoom($room);
            return false;
        }

        foreach ($questions as $index => $question) {
            BattleRound::create([
                'battle_room_id' => $room->id,
                'question_id' => $question->id,
                'round_number' => $index + 1,
                'points' => $question->points ?? 10,
                'started_at' => $index === 0 ? now() : null,
            ]);
        }

        $room->update([
            'status' => 'playing',
            'question_count' => $questions->count(),
            'current_question_index' => 0,
            'current_question_started_at' => now(),
            'started_at' => now(),
        ]);

        return true;
    }

    public function isAbandoned(BattleRoom $room): bool
    {
        if ($room->status !== 'playing' || !$room->current_question_started_at) {
            return false;
        }

        return $room->current_question_started_at->lte(now()->subSeconds($this->getInactivityTimeoutSeconds($room)));
    }

    public function isCurrentRound(BattleRoom $room, BattleRound $round): bool
    {
        $currentRound = $room->currentRound();

        return $currentRound
            && (int) $currentRound->id === (int) $round->id
            && $round->started_at !== null
            && $round->finished_at === null;
    }

    public function recordAnswer(BattleRoom $room, BattleParticipant $participant, BattleRound $round, string $selectedOption): array
    {
        return DB::transaction(function () use ($room, $participant, $round, $selectedOption) {
            /** @var BattleRoom $lockedRoom */
            $lockedRoom = BattleRoom::lockForUpdate()->findOrFail($room->id);
            /** @var BattleParticipant|null $lockedParticipant */
            $lockedParticipant = BattleParticipant::lockForUpdate()->find($participant->id);
            /** @var BattleRound|null $lockedRound */
            $lockedRound = BattleRound::lockForUpdate()->find($round->id);

            if (!$lockedParticipant || !$lockedRound || $lockedRoom->status !== 'playing') {
                return ['error' => 'Game is not active', 'status' => 400];
            }

            if (!$this->isCurrentRound($lockedRoom, $lockedRound)) {
                return ['error' => 'Invalid or inactive round', 'status' => 400];
            }

            if ($lockedRound->isTimerExpired()) {
                return ['error' => 'Time is up!', 'status' => 400];
            }

            $existing = BattleAnswer::where('battle_round_id', $lockedRound->id)
                ->where('battle_participant_id', $lockedParticipant->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return ['error' => 'Already answered this question', 'status' => 400];
            }

            $question = $lockedRound->question;
            $normalizedOption = strtoupper($selectedOption);
            $isCorrect = $normalizedOption === strtoupper($question->correct_answer);
            $pointsAwarded = $isCorrect ? $lockedRound->points : 0;

            BattleAnswer::create([
                'battle_round_id' => $lockedRound->id,
                'battle_participant_id' => $lockedParticipant->id,
                'selected_option' => $normalizedOption,
                'is_correct' => $isCorrect,
                'points_awarded' => $pointsAwarded,
                'answered_at' => now(),
            ]);

            if ($isCorrect) {
                $lockedParticipant->increment('individual_score', $pointsAwarded);

                if ($lockedParticipant->team === 'a') {
                    $lockedRoom->increment('team_a_score', $pointsAwarded);
                } else {
                    $lockedRoom->increment('team_b_score', $pointsAwarded);
                }
            } else {
                $lockedRoom->touch();
            }

            $lockedRound->refresh();
            if ($lockedRound->isAllAnswered()) {
                $this->advanceToNextQuestion($lockedRoom);
            }

            return [
                'success' => true,
                'is_correct' => $isCorrect,
                'correct_answer' => $question->correct_answer,
                'points_awarded' => $pointsAwarded,
                'status' => 200,
            ];
        });
    }

    private function handleExpiredLobby(BattleRoom $room): void
    {
        if ($room->participants()->count() < $this->getMinimumPlayers()) {
            $this->finishRoom($room);
            return;
        }

        $this->startBattle($room);
    }

    private function advanceToNextQuestion(BattleRoom $room): void
    {
        if ($room->status !== 'playing') {
            return;
        }

        $currentRound = $room->currentRound();
        if ($currentRound && !$currentRound->finished_at) {
            $currentRound->update(['finished_at' => now()]);
        }

        $nextIndex = $room->current_question_index + 1;

        if ($nextIndex >= (int) $room->question_count) {
            $winner = null;

            if ($room->team_a_score > $room->team_b_score) {
                $winner = 'a';
            } elseif ($room->team_b_score > $room->team_a_score) {
                $winner = 'b';
            } elseif ($room->team_a_score === $room->team_b_score) {
                $winner = 'draw';
            }

            $this->finishRoom($room, $winner);
            return;
        }

        $nextRound = $room->rounds()->where('round_number', $nextIndex + 1)->first();
        if ($nextRound && !$nextRound->started_at) {
            $nextRound->update(['started_at' => now()]);
        }

        $room->update([
            'current_question_index' => $nextIndex,
            'current_question_started_at' => now(),
        ]);
    }

    private function finishRoom(BattleRoom $room, ?string $winnerTeam = null): void
    {
        if ($room->status === 'finished' || $room->finished_at) {
            return;
        }

        $room->update([
            'status' => 'finished',
            'winner_team' => $winnerTeam,
            'finished_at' => now(),
        ]);

        foreach ($room->participants()->with('user')->get() as $participant) {
            if ($participant->user && $participant->individual_score > 0) {
                $participant->user->increment('total_points', $participant->individual_score);
            }
        }
    }
}
