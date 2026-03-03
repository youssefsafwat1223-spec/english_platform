<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\GameSession;
use App\Models\GameTeam;
use App\Models\GameParticipant;
use App\Models\GameQuestion;
use App\Models\GameAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GameSessionController extends Controller
{
    /**
     * List all game sessions
     */
    public function index()
    {
        $sessions = GameSession::with(['course', 'teams'])
            ->withCount('teams')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.games.index', compact('sessions'));
    }

    /**
     * Show the create game form
     */
    public function create()
    {
        $courses = Course::with('lessons')->get();
        return view('admin.games.create', compact('courses'));
    }

    /**
     * Get eligible students count (AJAX)
     */
    public function getEligibleStudents(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'min_lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $query = Enrollment::where('course_id', $request->course_id);

        if ($request->min_lesson_id) {
            // Get lesson order
            $lesson = Lesson::find($request->min_lesson_id);
            if ($lesson) {
                // Get enrollments where the student has completed lessons up to this one
                $query->whereHas('user', function ($q) use ($request, $lesson) {
                    $q->whereHas('lessonProgress', function ($lq) use ($request, $lesson) {
                        $lq->where('course_id', $request->course_id)
                           ->where('lesson_id', $lesson->id)
                           ->where('is_completed', true);
                    });
                });
            }
        }

        $count = $query->count();
        $students = $query->with('user:id,name,email')->get()->map(function ($e) {
            return [
                'id' => $e->user->id,
                'name' => $e->user->name,
                'email' => $e->user->email,
            ];
        });

        return response()->json([
            'count' => $count,
            'students' => $students,
        ]);
    }

    /**
     * Store a new game session, create teams, distribute students, add questions
     */
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

        DB::beginTransaction();

        try {
            // 1. Create the game session
            $session = GameSession::create([
                'title' => $request->title,
                'description' => $request->description,
                'course_id' => $request->course_id,
                'min_lesson_id' => $request->min_lesson_id,
                'start_time' => $request->start_time,
                'status' => 'scheduled',
            ]);

            // 2. Create teams
            $teams = [];
            for ($i = 0; $i < $request->team_count; $i++) {
                $teams[] = GameTeam::create([
                    'game_session_id' => $session->id,
                    'name' => $request->team_names[$i] ?? "Team " . ($i + 1),
                    'color_hex' => $request->team_colors[$i] ?? '#3b82f6',
                ]);
            }

            // 3. Get eligible students and distribute
            $enrollmentQuery = Enrollment::where('course_id', $request->course_id);
            if ($request->min_lesson_id) {
                $lesson = Lesson::find($request->min_lesson_id);
                if ($lesson) {
                    $enrollmentQuery->whereHas('user', function ($q) use ($request, $lesson) {
                        $q->whereHas('lessonProgress', function ($lq) use ($request, $lesson) {
                            $lq->where('course_id', $request->course_id)
                               ->where('lesson_id', $lesson->id)
                               ->where('is_completed', true);
                        });
                    });
                }
            }

            $studentIds = $enrollmentQuery->pluck('user_id')->shuffle();

            // Round-robin distribution
            foreach ($studentIds as $index => $userId) {
                $teamIndex = $index % count($teams);
                GameParticipant::create([
                    'game_team_id' => $teams[$teamIndex]->id,
                    'user_id' => $userId,
                    'is_captain' => false,
                ]);
            }

            // Set first captain for each team (random)
            foreach ($teams as $team) {
                $firstParticipant = $team->participants()->inRandomOrder()->first();
                if ($firstParticipant) {
                    $firstParticipant->update(['is_captain' => true]);
                }
            }

            // 4. Add questions
            foreach ($request->questions as $index => $q) {
                GameQuestion::create([
                    'game_session_id' => $session->id,
                    'question_text' => $q['text'],
                    'options' => $q['options'],
                    'correct_answer' => $q['correct'],
                    'time_limit_seconds' => $q['time_limit'],
                    'points' => $q['points'],
                    'order' => $index,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.games.show', $session)
                ->with('success', 'تم إنشاء اللعبة بنجاح! تم توزيع ' . $studentIds->count() . ' طالب على ' . count($teams) . ' فرق.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حصل خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Show game session details / Control Panel
     */
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

    /**
     * Start the game (set status to active, show first question)
     */
    public function start(GameSession $game)
    {
        $game->update([
            'status' => 'active',
            'current_question_index' => 0,
            'current_question_start_time' => now(),
        ]);

        return back()->with('success', 'اللعبة بدأت! 🎮');
    }

    /**
     * Move to next question + rotate captains
     */
    public function nextQuestion(GameSession $game)
    {
        $nextIndex = $game->current_question_index + 1;
        $totalQuestions = $game->questions()->count();

        if ($nextIndex >= $totalQuestions) {
            // Game over
            $game->update(['status' => 'completed']);
            return back()->with('success', 'اللعبة خلصت! 🏆');
        }

        // Rotate captains for all teams
        foreach ($game->teams as $team) {
            // Remove current captain
            $team->participants()->update(['is_captain' => false]);
            // Pick new random captain
            $newCaptain = $team->participants()->inRandomOrder()->first();
            if ($newCaptain) {
                $newCaptain->update(['is_captain' => true]);
            }
        }

        $game->update([
            'current_question_index' => $nextIndex,
            'current_question_start_time' => now(),
        ]);

        return back()->with('success', 'السؤال التالي! الكابتن اتغير عشوائياً 🔄');
    }

    /**
     * End the game
     */
    public function end(GameSession $game)
    {
        $game->update(['status' => 'completed']);
        return back()->with('success', 'اللعبة انتهت! 🏆');
    }

    /**
     * Send notifications to all participants
     */
    public function notify(GameSession $game)
    {
        $game->load('teams.participants.user');

        $count = 0;
        foreach ($game->teams as $team) {
            foreach ($team->participants as $participant) {
                try {
                    Mail::raw(
                        "مرحباً {$participant->user->name}!\n\n" .
                        "تم اختيارك للمشاركة في مسابقة: {$game->title}\n" .
                        "الفريق: {$team->name}\n" .
                        "الموعد: {$game->start_time->format('Y-m-d H:i')}\n\n" .
                        "استعد! 🎮",
                        function ($message) use ($participant, $game) {
                            $message->to($participant->user->email)
                                    ->subject("🎮 دعوة للمشاركة في: {$game->title}");
                        }
                    );
                    $count++;
                } catch (\Exception $e) {
                    \Log::error("Failed to send game notification: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', "تم إرسال $count إشعار بنجاح! 📧");
    }

    /**
     * Delete a game session
     */
    public function destroy(GameSession $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'تم حذف اللعبة.');
    }
    /**
     * Poll for game state (Admin)
     */
    public function poll(GameSession $game)
    {
        $currentQuestion = null;
        if ($game->status === 'active' && $game->current_question_index >= 0) {
            $currentQuestion = $game->questions->skip($game->current_question_index)->first();
        }

        // Get all answers for current question if active
        $answers = [];
        if ($currentQuestion) {
            $answers = GameAnswer::where('game_question_id', $currentQuestion->id)
                ->with('team')
                ->get()
                ->map(fn($a) => [
                    'team_id' => $a->game_team_id,
                    'team_name' => $a->team->name,
                    'selected_option' => $a->selected_option,
                    'is_correct' => $a->is_correct,
                    'points' => $a->points_awarded,
                ]);
        }

        $leaderboard = $game->teams()->orderByDesc('score')->get()->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'color' => $t->color_hex,
            'score' => $t->score,
            'participants_count' => $t->participants->count(),
        ]);

        return response()->json([
            'status' => $game->status,
            'current_question_index' => $game->current_question_index, // Added this
            'current_question' => $currentQuestion,
            'answers' => $answers,
            'leaderboard' => $leaderboard,
            'time_remaining' => $currentQuestion && $game->current_question_start_time
                ? max(0, $currentQuestion->time_limit_seconds - now()->diffInSeconds($game->current_question_start_time))
                : 0,
        ]);
    }
}
