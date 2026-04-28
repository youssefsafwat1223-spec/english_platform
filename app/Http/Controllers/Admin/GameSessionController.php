<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\GameAnswer;
use App\Models\GameParticipant;
use App\Models\GameQuestion;
use App\Models\GameSession;
use App\Models\GameTeam;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GameSessionController extends Controller
{
    public function index()
    {
        $sessions = GameSession::with(['course', 'teams'])
            ->withCount('teams')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.games.index', compact('sessions'));
    }

    public function create()
    {
        $courses = Course::with('lessons')->get();

        return view('admin.games.create', compact('courses'));
    }

    public function getEligibleStudents(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'min_lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $lesson = $this->validatedLessonForCourse((int) $request->course_id, $request->min_lesson_id);
        if ($request->filled('min_lesson_id') && !$lesson) {
            return response()->json([
                'message' => __('The selected lesson does not belong to the selected course.'),
            ], 422);
        }

        $query = $this->eligibleEnrollmentsQuery((int) $request->course_id, $lesson);
        $count = $query->count();
        $students = $query->with('user:id,name,email')
            ->get()
            ->filter(fn ($enrollment) => $enrollment->user)
            ->map(fn ($enrollment) => [
                'id' => $enrollment->user->id,
                'name' => $enrollment->user->name,
                'email' => $enrollment->user->email,
            ])
            ->values();

        return response()->json([
            'count' => $count,
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'min_lesson_id' => 'nullable|exists:lessons,id',
            'start_time' => 'required|date|after:now',
            'team_count' => 'required|integer|min:2|max:20',
            'team_names' => 'required|array',
            'team_names.*' => 'required|string|max:50',
            'team_colors' => 'required|array',
            'team_colors.*' => 'required|string|max:7',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:6',
            'questions.*.correct' => 'required|string',
            'questions.*.time_limit' => 'required|integer|min:10|max:300',
            'questions.*.points' => 'required|integer|min:10|max:1000',
        ]);

        $lesson = $this->validatedLessonForCourse((int) $request->course_id, $request->min_lesson_id);
        if ($request->filled('min_lesson_id') && !$lesson) {
            return back()->withInput()->with('error', __('The selected lesson does not belong to the selected course.'));
        }

        if (count($request->team_names) < (int) $request->team_count || count($request->team_colors) < (int) $request->team_count) {
            return back()->withInput()->with('error', __('Please provide a name and color for each team.'));
        }

        DB::beginTransaction();

        try {
            $session = GameSession::create([
                'title' => $request->title,
                'description' => $request->description,
                'course_id' => $request->course_id,
                'min_lesson_id' => $request->min_lesson_id,
                'start_time' => $request->start_time,
                'status' => 'scheduled',
            ]);

            $teams = [];
            for ($i = 0; $i < $request->team_count; $i++) {
                $teams[] = GameTeam::create([
                    'game_session_id' => $session->id,
                    'name' => $request->team_names[$i] ?? ('Team ' . ($i + 1)),
                    'color_hex' => $request->team_colors[$i] ?? '#3b82f6',
                ]);
            }

            $studentIds = $this->eligibleEnrollmentsQuery((int) $request->course_id, $lesson)
                ->pluck('user_id')
                ->shuffle();

            if ($studentIds->isEmpty()) {
                DB::rollBack();

                return back()->withInput()->with('error', __('No eligible students were found for the selected course and lesson filter.'));
            }

            foreach ($studentIds as $index => $userId) {
                $teamIndex = $index % count($teams);
                GameParticipant::create([
                    'game_team_id' => $teams[$teamIndex]->id,
                    'user_id' => $userId,
                    'is_captain' => false,
                ]);
            }

            foreach ($teams as $team) {
                $firstParticipant = $team->participants()->inRandomOrder()->first();
                if ($firstParticipant) {
                    $firstParticipant->update(['is_captain' => true]);
                }
            }

            foreach ($request->questions as $index => $question) {
                GameQuestion::create([
                    'game_session_id' => $session->id,
                    'question_text' => $question['text'],
                    'options' => $question['options'],
                    'correct_answer' => $question['correct'],
                    'time_limit_seconds' => $question['time_limit'],
                    'points' => $question['points'],
                    'order' => $index,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.games.show', $session)
                ->with('success', __('Competition created successfully. Distributed :students students across :teams teams.', [
                    'students' => $studentIds->count(),
                    'teams' => count($teams),
                ]));
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', __('An error occurred: :message', [
                'message' => $e->getMessage(),
            ]));
        }
    }

    public function show(GameSession $game)
    {
        $game->load([
            'course',
            'teams.participants.user',
            'teams.captain.user',
            'questions',
        ]);

        $leaderboard = $game->teams()->orderByDesc('score')->get();

        return view('admin.games.show', compact('game', 'leaderboard'));
    }

    public function start(GameSession $game)
    {
        $game->update([
            'status' => 'active',
            'current_question_index' => 0,
            'current_question_start_time' => now(),
        ]);

        return back()->with('success', __('Competition started successfully.'));
    }

    public function nextQuestion(GameSession $game)
    {
        $nextIndex = $game->current_question_index + 1;
        $totalQuestions = $game->questions()->count();

        if ($nextIndex >= $totalQuestions) {
            $game->update(['status' => 'completed']);

            return back()->with('success', __('Competition completed successfully.'));
        }

        $game->loadMissing('teams.participants');
        foreach ($game->teams as $team) {
            $team->participants()->update(['is_captain' => false]);

            $newCaptain = $team->participants()->inRandomOrder()->first();
            if ($newCaptain) {
                $newCaptain->update(['is_captain' => true]);
            }
        }

        $game->update([
            'current_question_index' => $nextIndex,
            'current_question_start_time' => now(),
        ]);

        return back()->with('success', __('Moved to the next question and rotated team captains.'));
    }

    public function end(GameSession $game)
    {
        $game->update(['status' => 'completed']);

        return back()->with('success', __('Competition ended successfully.'));
    }

    public function notify(GameSession $game)
    {
        $game->load('teams.participants.user');

        $count = 0;
        foreach ($game->teams as $team) {
            foreach ($team->participants as $participant) {
                if (!$participant->user || blank($participant->user->email)) {
                    continue;
                }

                try {
                    Mail::raw(
                        "Hello {$participant->user->name},\n\n"
                        . "You have been selected to join the competition: {$game->title}\n"
                        . "Team: {$team->name}\n"
                        . "Start time: {$game->start_time->format('Y-m-d H:i')}\n\n"
                        . "Be ready.",
                        function ($message) use ($participant, $game) {
                            $message->to($participant->user->email)
                                ->subject("Competition Invitation: {$game->title}");
                        }
                    );
                    $count++;
                } catch (\Throwable $e) {
                    \Log::error('Failed to send game notification: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', __('Sent :count invitation(s) successfully.', ['count' => $count]));
    }

    public function destroy(GameSession $game)
    {
        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', __('Competition deleted successfully.'));
    }

    public function poll(GameSession $game)
    {
        $game->loadMissing(['questions', 'teams.participants']);

        $currentQuestion = null;
        if ($game->status === 'active' && $game->current_question_index >= 0) {
            $currentQuestion = $game->questions->skip($game->current_question_index)->first();
        }

        $answers = [];
        if ($currentQuestion) {
            $answers = GameAnswer::where('game_question_id', $currentQuestion->id)
                ->with('team')
                ->get()
                ->map(fn ($answer) => [
                    'team_id' => $answer->game_team_id,
                    'team_name' => $answer->team?->name ?? __('Unknown team'),
                    'selected_option' => $answer->selected_option,
                    'is_correct' => $answer->is_correct,
                    'points' => $answer->points_awarded,
                ]);
        }

        $leaderboard = $game->teams()->orderByDesc('score')->get()->map(fn ($team) => [
            'id' => $team->id,
            'name' => $team->name,
            'color' => $team->color_hex,
            'score' => $team->score,
            'participants_count' => $team->participants->count(),
        ]);

        return response()->json([
            'status' => $game->status,
            'current_question_index' => $game->current_question_index,
            'current_question' => $currentQuestion,
            'answers' => $answers,
            'leaderboard' => $leaderboard,
            'time_remaining' => $currentQuestion && $game->current_question_start_time
                ? max(0, $currentQuestion->time_limit_seconds - now()->diffInSeconds($game->current_question_start_time))
                : 0,
        ]);
    }

    private function validatedLessonForCourse(int $courseId, mixed $lessonId): ?Lesson
    {
        if (!$lessonId) {
            return null;
        }

        $lesson = Lesson::find($lessonId);

        if (!$lesson || (int) $lesson->course_id !== $courseId) {
            return null;
        }

        return $lesson;
    }

    private function eligibleEnrollmentsQuery(int $courseId, ?Lesson $lesson = null)
    {
        $query = Enrollment::where('course_id', $courseId)
            ->whereHas('user');

        if ($lesson) {
            $query->whereHas('user', function ($userQuery) use ($courseId, $lesson) {
                $userQuery->whereHas('lessonProgress', function ($progressQuery) use ($courseId, $lesson) {
                    $progressQuery->where('course_id', $courseId)
                        ->where('lesson_id', $lesson->id)
                        ->where('is_completed', true);
                });
            });
        }

        return $query;
    }
}
