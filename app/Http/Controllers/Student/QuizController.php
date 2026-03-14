<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Http\Requests\SubmitQuizRequest;
use App\Services\TelegramService;
use App\Services\AchievementService;

class QuizController extends Controller
{
    private $telegramService;
    private $achievementService;

    public function __construct(TelegramService $telegramService, AchievementService $achievementService)
    {
        $this->telegramService = $telegramService;
        $this->achievementService = $achievementService;
    }

    public function start(Quiz $quiz)
    {
        $user = auth()->user();

        // Check if enrolled in course
        if (!$user->isEnrolledIn($quiz->course_id)) {
            return redirect()->route('student.courses.show', $quiz->course_id)
                ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
        }

        // Check if can take quiz
        if (!$quiz->canUserTake($user)) {
            return back()->with('error', __('لا يمكنك إجراء هذا الاختبار في الوقت الحالي.'));
        }

        $quiz->load(['questions' => function ($query) {
            $query->orderBy('quiz_questions.order_index');
        }]);

        return view('student.quizzes.take', compact('quiz'));
    }

    public function submit(SubmitQuizRequest $request, Quiz $quiz)
    {
        $user = auth()->user();
        $enrollment = $user->getEnrollment($quiz->course_id);

        if (!$enrollment) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        }

        // Calculate score
        $answers = $request->answers;
        $totalQuestions = count($answers);
        $correctAnswers = 0;

        // Get attempt number
        $attemptNumber = $quiz->getAttemptCount($user) + 1;

        // Calculate time taken
        $startedAt = \Carbon\Carbon::parse($request->started_at);
        $completedAt = \Carbon\Carbon::parse($request->completed_at);
        $timeTaken = $completedAt->diffInSeconds($startedAt);

        // Create attempt
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'enrollment_id' => $enrollment->id,
            'attempt_number' => $attemptNumber,
            'total_questions' => $totalQuestions,
            'time_taken' => $timeTaken,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'score' => 0, // Will update after checking answers
            'correct_answers' => 0,
            'passed' => false,
        ]);

        // Save answers and calculate score
        foreach ($answers as $answer) {
            $question = \App\Models\Question::find($answer['question_id']);
            $isCorrect = $question->isCorrect($answer['user_answer']);

            if ($isCorrect) {
                $correctAnswers++;
            }

            $attempt->answers()->create([
                'question_id' => $answer['question_id'],
                'user_answer' => $answer['user_answer'],
                'is_correct' => $isCorrect,
                'time_taken' => $answer['time_taken'] ?? null,
                'audio_played' => $answer['audio_played'] ?? false,
                'audio_replay_count' => $answer['audio_replay_count'] ?? 0,
            ]);
        }

        // Update attempt score
        $score = round(($correctAnswers / $totalQuestions) * 100);
        $attempt->update([
            'score' => $score,
            'correct_answers' => $correctAnswers,
        ]);

        // Check if passed
        $attempt->checkPassed();

        // Award points if passed
        if ($attempt->passed) {
            $attempt->awardPoints();
            
            // Check for achievements
            $this->achievementService->checkAchievements($user, 'quiz_completed');
        }

        // Send notification via Telegram
        $this->telegramService->sendQuizResult($user, $quiz, $attempt);

        // Create in-app notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'notification_type' => 'quiz_result',
            'title' => 'Quiz Result',
            'message' => "You scored {$score}% on {$quiz->title}.",
            'action_url' => route('student.quizzes.result', $attempt->id),
        ]);

        // Send achievement email for high scores (90%+)
        if ($score >= 90) {
            try {
                \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\AchievementCongrats(
                    $user,
                    'high_score',
                    [
                        'quiz_title' => $quiz->title,
                        'course_title' => $quiz->course->title ?? '',
                        'score' => $score,
                        'action_url' => route('student.quizzes.result', $attempt->id),
                    ]
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Achievement email failed: ' . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'attempt_id' => $attempt->id,
                'score' => $score,
                'passed' => $attempt->passed,
                'redirect_url' => route('student.quizzes.result', $attempt->id),
            ]);
        }

        return redirect()->route('student.quizzes.result', $attempt->id);
    }

    public function result($attemptId)
    {
        $attempt = QuizAttempt::with(['quiz', 'answers.question'])
            ->where('user_id', auth()->id())
            ->findOrFail($attemptId);

        return view('student.quizzes.result', compact('attempt'));
    }

    public function myAttempts()
    {
        $attempts = auth()->user()->quizAttempts()
            ->with(['quiz.course'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.quizzes.my-attempts', compact('attempts'));
    }
}
