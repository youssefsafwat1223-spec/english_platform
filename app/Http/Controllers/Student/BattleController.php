<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BattleParticipant;
use App\Models\BattleRoom;
use App\Models\BattleRound;
use App\Models\Course;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Services\BattleRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BattleController extends Controller
{
    public function __construct(
        private readonly BattleRoomService $battleRoomService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $minQuestions = $this->battleRoomService->getMinimumQuestions();

        $enrolledCourses = Course::whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->withCount('questions')
            ->where('is_active', true)
            ->get()
            ->filter(fn (Course $course) => $course->questions_count >= $minQuestions)
            ->values();

        $activeRoom = $this->getActiveRoomForUser($user->id);

        if ($activeRoom) {
            $activeRoom = $this->battleRoomService->syncRoomState($activeRoom);

            if ($activeRoom->status === 'finished' || !$activeRoom->hasPlayer($user->id)) {
                $activeRoom = null;
            }
        }

        return view('student.battle.index', compact('enrolledCourses', 'activeRoom'));
    }

    public function join(Course $course): RedirectResponse
    {
        $user = auth()->user();

        if (!$course->enrollments()->where('user_id', $user->id)->exists()) {
            return back()->with('error', $this->text(
                'يجب أن تكون مشتركًا في هذا الكورس قبل دخول الباتل.',
                'You must be enrolled in this course before joining a battle.'
            ));
        }

        $minimumQuestions = $this->battleRoomService->getMinimumQuestions();
        if ($course->questions()->count() < $minimumQuestions) {
            return back()->with('error', $this->text(
                'هذا الكورس لا يحتوي على عدد كافٍ من الأسئلة لبدء الباتل الآن.',
                'This course does not have enough questions to start a battle yet.'
            ));
        }

        $existingRoom = $this->getActiveRoomForUser($user->id);
        if ($existingRoom) {
            $existingRoom = $this->battleRoomService->syncRoomState($existingRoom);

            if ($existingRoom->status !== 'finished') {
                return redirect()->route(
                    $existingRoom->status === 'playing' ? 'student.battle.play' : 'student.battle.lobby',
                    $existingRoom
                )->with('info', $this->text(
                    'أنت بالفعل داخل باتل آخر. أكمل نفس الغرفة أولًا.',
                    'You are already in another active battle. Finish that room first.'
                ));
            }
        }

        $room = DB::transaction(function () use ($course, $user) {
            $room = BattleRoom::waiting()
                ->forCourse($course->id)
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get()
                ->first(fn (BattleRoom $battleRoom) => $battleRoom->playerCount() < $battleRoom->max_players);

            if (!$room) {
                $lobbyTimer = (int) SystemSetting::get('battle_lobby_timer', 120);
                $room = BattleRoom::create([
                    'course_id' => $course->id,
                    'status' => 'waiting',
                    'max_players' => (int) SystemSetting::get('battle_max_players', 10),
                    'lobby_timer_seconds' => $lobbyTimer,
                    'lobby_ends_at' => now()->addSeconds($lobbyTimer),
                    'question_timer_seconds' => (int) SystemSetting::get('battle_question_timer', 30),
                ]);

                $this->notifyCourseStudentsAboutBattle($course, $room, $user->id, $user->name);
            }

            BattleParticipant::firstOrCreate([
                'battle_room_id' => $room->id,
                'user_id' => $user->id,
            ]);

            $room = $room->fresh();

            if ($room->playerCount() >= $room->max_players) {
                $this->battleRoomService->startBattle($room);
            }

            return $room;
        });

        $room = $this->battleRoomService->syncRoomState($room);

        if ($room->status === 'playing') {
            return redirect()->route('student.battle.play', $room);
        }

        return redirect()->route('student.battle.lobby', $room);
    }

    public function lobby(BattleRoom $room): RedirectResponse|View
    {
        $user = auth()->user();
        $room = $this->battleRoomService->syncRoomState($room);

        $participant = $room->participants()->where('user_id', $user->id)->first();

        if (!$participant) {
            if ($room->status !== 'waiting' || $room->isFull()) {
                return redirect()->route('student.battle.index')
                    ->with('error', $this->text(
                        'هذه الغرفة لم تعد متاحة.',
                        'This battle room is no longer available.'
                    ));
            }

            if (!$room->course->enrollments()->where('user_id', $user->id)->exists()) {
                return redirect()->route('student.battle.index')
                    ->with('error', $this->text(
                        'لا يمكنك دخول هذا الباتل لأنك غير مشترك في الكورس.',
                        'You cannot join this battle because you are not enrolled in the course.'
                    ));
            }

            $otherRoom = $this->getActiveRoomForUser($user->id, $room->id);
            if ($otherRoom) {
                $otherRoom = $this->battleRoomService->syncRoomState($otherRoom);

                if ($otherRoom->status !== 'finished') {
                    return redirect()->route(
                        $otherRoom->status === 'playing' ? 'student.battle.play' : 'student.battle.lobby',
                        $otherRoom
                    )->with('error', $this->text(
                        'أنت بالفعل داخل باتل آخر.',
                        'You are already in another active battle.'
                    ));
                }
            }

            BattleParticipant::firstOrCreate([
                'battle_room_id' => $room->id,
                'user_id' => $user->id,
            ]);

            $room = $this->battleRoomService->syncRoomState($room->fresh());
            $participant = $room->participants()->where('user_id', $user->id)->first();
        }

        if ($room->status === 'playing') {
            return redirect()->route('student.battle.play', $room);
        }

        if ($room->status === 'finished') {
            return redirect()->route('student.battle.results', $room);
        }

        $room->load('participants.user', 'course');
        $lobbyTimeRemaining = $room->lobbyTimeRemaining();

        return view('student.battle.lobby', compact('room', 'participant', 'lobbyTimeRemaining'));
    }

    public function play(BattleRoom $room): RedirectResponse|View
    {
        $user = auth()->user();
        $room = $this->battleRoomService->syncRoomState($room);

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return redirect()->route('student.battle.index')
                ->with('error', $this->text(
                    'أنت لست ضمن هذه الغرفة.',
                    'You are not a participant in this room.'
                ));
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

    public function poll(BattleRoom $room): JsonResponse
    {
        $user = auth()->user();
        $room = $this->battleRoomService->syncRoomState($room);

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        $data = [
            'status' => $room->status,
            'finished_reason' => $this->getFinishedReason($room),
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
            'players' => $room->participants()->with('user:id,name')->get()->map(fn (BattleParticipant $battleParticipant) => [
                'id' => $battleParticipant->user_id,
                'name' => $battleParticipant->user->name,
                'team' => $battleParticipant->team,
                'score' => $battleParticipant->individual_score,
                'is_me' => $battleParticipant->user_id === $user->id,
            ]),
        ];

        if ($room->status === 'playing') {
            $currentRound = $room->currentRound();

            if ($currentRound) {
                $question = $currentRound->question;
                $myAnswer = $currentRound->answers()
                    ->where('battle_participant_id', $participant->id)
                    ->first();

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
                    'answers_count' => $currentRound->answers()->count(),
                    'total_players' => $room->participants()->whereNotNull('team')->count(),
                    'my_answer' => $myAnswer ? [
                        'selected' => $myAnswer->selected_option,
                        'is_correct' => $myAnswer->is_correct,
                        'points' => $myAnswer->points_awarded,
                    ] : null,
                ];
            }
        }

        return response()->json($data);
    }

    public function answer(Request $request, BattleRoom $room): JsonResponse
    {
        $validated = $request->validate([
            'round_id' => 'required|integer',
            'selected_option' => 'required|string|size:1|in:A,B,C,D,a,b,c,d',
        ]);

        $user = auth()->user();
        $room = $this->battleRoomService->syncRoomState($room);

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant || !$participant->team) {
            return response()->json(['error' => 'Not a participant'], 403);
        }

        $round = BattleRound::where('id', $validated['round_id'])
            ->where('battle_room_id', $room->id)
            ->first();

        if (!$round) {
            return response()->json(['error' => 'Invalid round'], 400);
        }

        $result = $this->battleRoomService->recordAnswer(
            $room,
            $participant,
            $round,
            strtoupper($validated['selected_option'])
        );

        $status = $result['status'] ?? 200;
        unset($result['status']);

        return response()->json($result, $status);
    }

    public function leave(BattleRoom $room): RedirectResponse
    {
        $user = auth()->user();
        $room = $this->battleRoomService->syncRoomState($room);

        $participant = $room->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return redirect()->route('student.battle.index')
                ->with('error', $this->text(
                    'أنت لست ضمن هذه الغرفة.',
                    'You are not a participant in this room.'
                ));
        }

        if ($room->status === 'finished') {
            return redirect()->route('student.battle.index')
                ->with('info', $this->text(
                    'هذه الغرفة انتهت بالفعل.',
                    'This battle room has already finished.'
                ));
        }

        if ($room->status === 'playing') {
            $this->battleRoomService->closeRoomBecausePlayerLeft($room);

            return redirect()->route('student.battle.index')
                ->with('success', $this->text(
                    'تم إنهاء الباتل لأنك غادرت الجولة.',
                    'The battle was closed because you left the match.'
                ));
        }

        if ($room->status !== 'waiting') {
            return back()->with('error', $this->text(
                'لا يمكنك مغادرة الغرفة بعد بدء الباتل.',
                'You cannot leave the room after the battle has started.'
            ));
        }

        $room->participants()->where('user_id', $user->id)->delete();

        if ($room->playerCount() === 0) {
            $room->delete();
        }

        return redirect()->route('student.battle.index')
            ->with('success', $this->text(
                'تمت مغادرة الغرفة.',
                'You left the battle room.'
            ));
    }

    public function results(BattleRoom $room): RedirectResponse|View
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

        return view('student.battle.results', compact('room', 'participant', 'teamAPlayers', 'teamBPlayers'));
    }

    private function getActiveRoomForUser(int $userId, ?int $exceptRoomId = null): ?BattleRoom
    {
        return BattleRoom::whereIn('status', ['waiting', 'playing'])
            ->when($exceptRoomId, fn ($query) => $query->where('id', '!=', $exceptRoomId))
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('course')
            ->latest('id')
            ->first();
    }

    private function notifyCourseStudentsAboutBattle(Course $course, BattleRoom $room, int $creatorId, string $creatorName): void
    {
        $studentIds = $course->enrollments()
            ->where('user_id', '!=', $creatorId)
            ->pluck('user_id');

        if ($studentIds->isEmpty()) {
            return;
        }

        $notifications = $studentIds->map(function (int $studentId) use ($course, $room, $creatorName) {
            return [
                'user_id' => $studentId,
                'notification_type' => 'battle_started',
                'title' => 'Battle Started in ' . $course->title . '!',
                'message' => $creatorName . ' started a battle. Join now before the lobby closes!',
                'action_url' => route('student.battle.lobby', $room),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        Notification::insert($notifications);
    }

    private function getFinishedReason(BattleRoom $room): ?string
    {
        if ($room->status !== 'finished') {
            return null;
        }

        if ($room->winner_team === 'player_left') {
            return 'player_left';
        }

        if ($room->winner_team !== null) {
            return null;
        }

        return $room->started_at ? 'abandoned' : 'cancelled';
    }

    private function text(string $ar, string $en): string
    {
        return app()->getLocale() === 'ar' ? $ar : $en;
    }
}
