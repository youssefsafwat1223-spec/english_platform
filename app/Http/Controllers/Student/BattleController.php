<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BattleRoom;
use App\Models\BattleParticipant;
use App\Models\BattleRound;
use App\Models\BattleAnswer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Notification;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BattleController extends Controller
{
    /**
     * List courses the student is enrolled in — with "Join Battle" button
     */
    public function index()
    {
        $user = auth()->user();

        $minQuestions = (int) SystemSetting::get('battle_min_questions', 1);

        $enrolledCourses = Course::whereHas('enrollments', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->withCount('questions')
        ->where('is_active', true)
        ->get()
        ->filter(function ($course) use ($minQuestions) {
            return $course->questions_count >= $minQuestions;
        });

        // Check if user is already in a waiting/playing room
        $activeRoom = BattleRoom::whereIn('status', ['waiting', 'playing'])
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        return view('student.battle.index', compact('enrolledCourses', 'activeRoom'));
    }

    /**
     * Join or create a battle room for a course
     */
    public function join(Course $course)
    {
        $user = auth()->user();

        // Check user is enrolled
        $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
        if (!$isEnrolled) {
            return back()->with('error', __('يجب أن تكون مسجلًا في هذا الكورس للانضمام إلى التحدي.'));
        }

        // Check enough questions
        $questionCount = Question::where('course_id', $course->id)->count();
        if ($questionCount < 5) {
            return back()->with('error', __('هذا الكورس لا يحتوي على أسئلة كافية للتحدي بعد.'));
        }

        // Check if already in a room
        $existingRoom = BattleRoom::whereIn('status', ['waiting', 'playing'])
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        if ($existingRoom) {
            return redirect()->route('student.battle.lobby', $existingRoom);
        }

        // Find an existing waiting room for this course with available slots
        // Fetching all waiting rooms and filtering in PHP to avoid subquery issues
        $room = BattleRoom::waiting()
            ->forCourse($course->id)
            ->get()
            ->filter(function ($r) {
                return $r->playerCount() < $r->max_players;
            })
            ->sortBy('created_at') // Join oldest room first
            ->first();

        DB::beginTransaction();
        try {
            if (!$room) {
                // Create a new room with admin-configured settings
                $lobbyTimer = (int) (SystemSetting::get('battle_lobby_timer') ?: 120);
                
                $room = BattleRoom::create([
                    'course_id'               => $course->id,
                    'status'                  => 'waiting',
                    'max_players'             => (int) (SystemSetting::get('battle_max_players') ?: 10),
                    'lobby_timer_seconds'     => $lobbyTimer,
                    'lobby_ends_at'           => now()->addSeconds($lobbyTimer),
                    'question_timer_seconds'  => (int) (SystemSetting::get('battle_question_timer') ?: 30),
                ]);

                // Notify all enrolled students (except the creator) about the new battle
                $enrolledStudentIds = $course->enrollments()
                    ->where('user_id', '!=', $user->id)
                    ->pluck('user_id');

                $notifications = $enrolledStudentIds->map(function ($studentId) use ($course, $room, $user) {
                    return [
                        'user_id' => $studentId,
                        'notification_type' => 'battle_started',
                        'title' => __('⚔️ Battle Started in :course!', ['course' => $course->title]),
                        'message' => __(':user started a battle. Join now before the lobby closes!', [
                            'user' => $user->name,
                        ]),
                        'action_url' => route('student.battle.lobby', $room),
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                if (!empty($notifications)) {
                    Notification::insert($notifications);
                }
            }

            // Add player
            BattleParticipant::create([
                'battle_room_id' => $room->id,
                'user_id' => $user->id,
            ]);

            // If room is full → start immediately
            if ($room->playerCount() >= $room->max_players) {
                $this->startBattle($room);
            }

            DB::commit();
            return redirect()->route('student.battle.lobby', $room);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('حدث خطأ: ') . $e->getMessage());
        }
    }

    /**
     * Lobby / waiting room
     */
    public function lobby(BattleRoom $room)
    {
        $user = auth()->user();

        // Check player is in this room
        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            // Auto-join if room is still waiting and has space (e.g. came from notification)
            if ($room->status === 'waiting' && $room->playerCount() < $room->max_players) {
                $participant = BattleParticipant::create([
                    'battle_room_id' => $room->id,
                    'user_id' => $user->id,
                ]);
            } else {
                return redirect()->route('student.battle.index')
                    ->with('error', __('غرفة التحدي هذه لم تعد متاحة.'));
            }
        }

        // If game already started, redirect to play
        if ($room->status === 'playing') {
            return redirect()->route('student.battle.play', $room);
        }

        // If game finished
        if ($room->status === 'finished') {
            return redirect()->route('student.battle.results', $room);
        }

        $room->load('participants.user', 'course');
        $lobbyTimeRemaining = $room->lobbyTimeRemaining();

        return view('student.battle.lobby', compact('room', 'participant', 'lobbyTimeRemaining'));
    }

    /**
     * Play screen
     */
    public function play(BattleRoom $room)
    {
        $user = auth()->user();

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return redirect()->route('student.battle.index')
                ->with('error', __('أنت لست في غرفة التحدي هذه.'));
        }

        if ($room->status === 'waiting') {
            return redirect()->route('student.battle.lobby', $room);
        }

        if ($room->status === 'finished') {
            return redirect()->route('student.battle.results', $room);
        }

        $room->load('participants.user', 'course', 'rounds.question');

        return view('student.battle.play', compact('room', 'participant'));
    }

    /**
     * AJAX: Poll for room state
     */
    public function poll(BattleRoom $room)
    {
        $user = auth()->user();
        $room->refresh();

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        // If waiting and lobby expired → handle auto-start or rejection
        if ($room->status === 'waiting' && $room->isLobbyExpired()) {
            $this->handleLobbyExpiry($room);
            $room->refresh();
        }

        // If playing, check if current round timer expired → auto-advance
        if ($room->status === 'playing') {
            $currentRound = $room->currentRound();
            if ($currentRound && ($currentRound->isTimerExpired() || $currentRound->isAllAnswered())) {
                $this->advanceToNextQuestion($room);
                $room->refresh();
            }
        }

        // Build response
        $data = [
            'status' => $room->status,
            'player_count' => $room->playerCount(),
            'max_players' => $room->max_players,
            'lobby_time_remaining' => $room->status === 'waiting' ? $room->lobbyTimeRemaining() : 0,
            'team_a_score' => $room->team_a_score,
            'team_b_score' => $room->team_b_score,
            'team_a_name' => $room->team_a_name,
            'team_b_name' => $room->team_b_name,
            'my_team' => $participant->team,
            'my_score' => $participant->individual_score,
            'winner_team' => $room->winner_team,
        ];

        // Players list
        $data['players'] = $room->participants()->with('user:id,name')->get()->map(fn($p) => [
            'id' => $p->user_id,
            'name' => $p->user->name,
            'team' => $p->team,
            'score' => $p->individual_score,
            'is_me' => $p->user_id === $user->id,
        ]);

        // Current question if playing
        if ($room->status === 'playing') {
            $currentRound = $room->currentRound();
            if ($currentRound) {
                $question = $currentRound->question;
                $data['current_round'] = [
                    'round_id' => $currentRound->id,
                    'round_number' => $currentRound->round_number,
                    'total_rounds' => $room->question_count,
                    'question_text' => $question->question_text,
                    'option_a' => $question->option_a,
                    'option_b' => $question->option_b,
                    'option_c' => $question->option_c,
                    'option_d' => $question->option_d,
                    'points' => $currentRound->points,
                    'time_remaining' => $currentRound->timeRemaining(),
                ];

                // Has the user already answered this round?
                $myAnswer = BattleAnswer::where('battle_round_id', $currentRound->id)
                    ->where('battle_participant_id', $participant->id)
                    ->first();

                $data['current_round']['my_answer'] = $myAnswer ? [
                    'selected' => $myAnswer->selected_option,
                    'is_correct' => $myAnswer->is_correct,
                    'points' => $myAnswer->points_awarded,
                ] : null;

                // How many have answered
                $data['current_round']['answers_count'] = $currentRound->answers()->count();
                $data['current_round']['total_players'] = $room->participants()->whereNotNull('team')->count();
            }
        }

        return response()->json($data);
    }

    /**
     * AJAX: Submit answer
     */
    public function answer(Request $request, BattleRoom $room)
    {
        $request->validate([
            'round_id' => 'required|integer',
            'selected_option' => 'required|string|size:1|in:A,B,C,D',
        ]);

        $user = auth()->user();
        $participant = $room->participants()->where('user_id', $user->id)->first();

        if (!$participant || !$participant->team) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        if ($room->status !== 'playing') {
            return response()->json(['error' => 'Game is not active'], 400);
        }

        $round = BattleRound::where('id', $request->round_id)
            ->where('battle_room_id', $room->id)
            ->first();

        if (!$round) {
            return response()->json(['error' => 'Invalid round'], 400);
        }

        // Check if already answered
        $existing = BattleAnswer::where('battle_round_id', $round->id)
            ->where('battle_participant_id', $participant->id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Already answered this question'], 400);
        }

        // Check timer
        if ($round->isTimerExpired()) {
            return response()->json(['error' => 'Time is up!'], 400);
        }

        // Check answer
        $question = $round->question;
        $isCorrect = strtoupper($request->selected_option) === strtoupper($question->correct_answer);
        $pointsAwarded = $isCorrect ? $round->points : 0;

        DB::beginTransaction();
        try {
            // Save answer
            BattleAnswer::create([
                'battle_round_id' => $round->id,
                'battle_participant_id' => $participant->id,
                'selected_option' => strtoupper($request->selected_option),
                'is_correct' => $isCorrect,
                'points_awarded' => $pointsAwarded,
                'answered_at' => now(),
            ]);

            // Update scores
            if ($isCorrect) {
                $participant->increment('individual_score', $pointsAwarded);
                if ($participant->team === 'a') {
                    $room->increment('team_a_score', $pointsAwarded);
                } else {
                    $room->increment('team_b_score', $pointsAwarded);
                }
            }

            // Check if all answered → advance
            $round->refresh();
            if ($round->isAllAnswered()) {
                $this->advanceToNextQuestion($room);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'correct_answer' => $question->correct_answer,
                'points_awarded' => $pointsAwarded,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Leave a waiting room
     */
    public function leave(BattleRoom $room)
    {
        $user = auth()->user();

        if ($room->status !== 'waiting') {
            return back()->with('error', __('لا يمكنك المغادرة أثناء سير التحدي.'));
        }

        $room->participants()->where('user_id', $user->id)->delete();

        // If room is now empty, delete it
        if ($room->playerCount() === 0) {
            $room->delete();
        }

        return redirect()->route('student.battle.index')
            ->with('success', __('لقد غادرت غرفة التحدي.'));
    }

    /**
     * Results screen
     */
    public function results(BattleRoom $room)
    {
        $user = auth()->user();

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return redirect()->route('student.battle.index');
        }

        if ($room->status !== 'finished') {
            return redirect()->route('student.battle.lobby', $room);
        }

        $room->load('participants.user', 'course', 'rounds.question', 'rounds.answers');

        $teamAPlayers = $room->teamA()->with('user')->orderByDesc('individual_score')->get();
        $teamBPlayers = $room->teamB()->with('user')->orderByDesc('individual_score')->get();

        return view('student.battle.results', compact(
            'room', 'participant', 'teamAPlayers', 'teamBPlayers'
        ));
    }

    // ==================== PRIVATE HELPERS ====================

    /**
     * Handle lobby expiry — divide teams and start, or reject if solo
     */
    private function handleLobbyExpiry(BattleRoom $room)
    {
        $playerCount = $room->participants()->count();
        $minPlayers = (int) (SystemSetting::get('battle_min_players') ?: 2);

        if ($playerCount < $minPlayers) {
            // Not enough players → cancel
            $room->update([
                'status' => 'finished',
                'winner_team' => null,
                'finished_at' => now(),
            ]);
            return;
        }

        // Enough players → start!
        $this->startBattle($room);
    }

    /**
     * Start the battle: divide teams, pick random questions, begin
     */
    private function startBattle(BattleRoom $room)
    {
        $participants = $room->participants()->inRandomOrder()->get();

        // Divide into teams
        foreach ($participants as $index => $participant) {
            $participant->update([
                'team' => $index % 2 === 0 ? 'a' : 'b',
            ]);
        }

        // Pick random questions using admin-configured count range
        $minQ = (int) SystemSetting::get('battle_min_questions', 5);
        $maxQ = (int) SystemSetting::get('battle_max_questions', 15);
        $availableCount = Question::where('course_id', $room->course_id)->count();
        $questionCount = rand($minQ, min($maxQ, $availableCount));

        $questions = Question::where('course_id', $room->course_id)
            ->inRandomOrder()
            ->take($questionCount)
            ->get();

        // Create rounds
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
    }

    /**
     * Advance to the next question (or end the game)
     */
    private function advanceToNextQuestion(BattleRoom $room)
    {
        // Mark current round as finished
        $currentRound = $room->currentRound();
        if ($currentRound && !$currentRound->finished_at) {
            $currentRound->update(['finished_at' => now()]);
        }

        $nextIndex = $room->current_question_index + 1;

        if ($nextIndex >= $room->question_count) {
            // Game over
            $winner = null;
            if ($room->team_a_score > $room->team_b_score) {
                $winner = 'a';
            } elseif ($room->team_b_score > $room->team_a_score) {
                $winner = 'b';
            } else {
                $winner = 'draw';
            }

            $room->update([
                'status' => 'finished',
                'winner_team' => $winner,
                'finished_at' => now(),
            ]);

            // Award points to all participants
            foreach ($room->participants as $p) {
                if ($p->user) {
                    $p->user->increment('total_points', $p->individual_score);
                }
            }

            return;
        }

        // Start next round
        $nextRound = $room->rounds()->where('round_number', $nextIndex + 1)->first();
        if ($nextRound) {
            $nextRound->update(['started_at' => now()]);
        }

        $room->update([
            'current_question_index' => $nextIndex,
            'current_question_started_at' => now(),
        ]);
    }
}
