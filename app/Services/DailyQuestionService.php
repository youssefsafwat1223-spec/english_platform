<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyQuestion;
use App\Models\Question;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class DailyQuestionService
{
    private $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Schedule daily quiz questions for all active users
     */
    public function scheduleQuestionsForToday()
    {
        $scheduledCount = 0;
        $sentCount = 0;

        // Get all active users with telegram linked
        $users = User::active()
            ->students()
            ->telegramLinked()
            ->get();

        foreach ($users as $user) {
            $result = $this->scheduleQuestionsForUser($user, true, false);

            $scheduledCount += $result['scheduled'];

            if ($result['prompt_sent']) {
                $sentCount++;
            }
        }

        Log::info('Daily questions scheduled', [
            'scheduled' => $scheduledCount,
            'sent' => $sentCount,
        ]);

        return [
            'scheduled' => $scheduledCount,
            'sent' => $sentCount,
        ];
    }

    /**
     * Schedule daily quiz questions for a specific user
     */
    public function scheduleQuestionsForUser(User $user, bool $sendPrompt = true, bool $promptIfExisting = false): array
    {
        if (!$this->shouldSendQuestionToday($user)) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        $todayQuestions = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->count();

        if ($todayQuestions > 0) {
            $promptSent = false;
            if ($sendPrompt && $promptIfExisting) {
                $promptSent = $this->sendPromptForUser($user, $todayQuestions);
            }

            return ['scheduled' => 0, 'prompt_sent' => (bool) $promptSent];
        }

        $selection = $this->selectQuestionsForUser($user);
        $questions = $selection['questions'];
        $course = $selection['course'];
        $lesson = $selection['lesson'];

        if ($questions->isEmpty() || !$course || !$lesson) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        foreach ($questions as $question) {
            $this->createDailyQuestion($user, $question);
        }

        $promptSent = false;
        if ($sendPrompt) {
            $promptSent = $this->telegramService->sendDailyQuizPrompt(
                $user,
                $course,
                $lesson,
                $questions->count()
            );
        }

        Notification::create([
            'user_id' => $user->id,
            'notification_type' => 'daily_question',
            'title' => 'Daily Quiz Ready',
            'message' => "Your daily quiz for {$course->title} - {$lesson->title} is ready.",
            'action_url' => route('student.courses.learn', $course),
        ]);

        return ['scheduled' => $questions->count(), 'prompt_sent' => (bool) $promptSent];
    }

    /**
     * Check if user should receive quiz today
     * Now always returns true so users can get quizzes every day
     */
    private function shouldSendQuestionToday(User $user)
    {
        $sendAlternateDays = config('app.send_daily_questions_alternate_days', false);

        if (!$sendAlternateDays) {
            return true; // Send every day
        }

        $lastQuestion = DailyQuestion::where('user_id', $user->id)
            ->orderBy('scheduled_for', 'desc')
            ->first();

        if (!$lastQuestion) {
            return true; // First time
        }

        if ($lastQuestion->scheduled_for->isToday()) {
            return true; // Scheduled for today, allow sending the prompt again
        }

        return $lastQuestion->scheduled_for->diffInDays(today()) >= 2;
    }

    /**
     * Select appropriate questions for user based on current lesson
     */
    private function selectQuestionsForUser(User $user)
    {
        $enrollment = $this->getTargetEnrollment($user);
        if (!$enrollment) {
            return ['questions' => collect(), 'course' => null, 'lesson' => null];
        }

        $lesson = $this->getTargetLesson($user, $enrollment);
        if (!$lesson) {
            return ['questions' => collect(), 'course' => $enrollment->course, 'lesson' => null];
        }

        $lessonIds = $lesson->course->lessons()
            ->where('order_index', '<=', $lesson->order_index)
            ->pluck('id');

        $availableCount = Question::whereIn('lesson_id', $lessonIds)->count();
        if ($availableCount === 0) {
            // Ultimate fallback: check ALL lessons in the course
            $allLessonIds = $lesson->course->lessons()->pluck('id');
            $availableCount = Question::whereIn('lesson_id', $allLessonIds)->count();
            if ($availableCount === 0) {
                return ['questions' => collect(), 'course' => $enrollment->course, 'lesson' => $lesson];
            }
            // Use all lessons instead
            $lessonIds = $allLessonIds;
        }

        $count = $this->getDailyQuizQuestionCount($availableCount);

        $recentlyAskedQuestionIds = DailyQuestion::where('user_id', $user->id)
            ->where('scheduled_for', '>=', today()->subDays(7))
            ->pluck('question_id');

        $selected = collect();

        // Prefer questions from the current lesson
        $primary = Question::where('lesson_id', $lesson->id)
            ->whereNotIn('id', $recentlyAskedQuestionIds)
            ->inRandomOrder()
            ->take($count)
            ->get();

        $selected = $selected->merge($primary);

        // Fallback to previous lessons in the same course
        if ($selected->count() < $count) {
            $needed = $count - $selected->count();

            $fallback = Question::whereIn('lesson_id', $lessonIds)
                ->whereNotIn('id', $recentlyAskedQuestionIds)
                ->whereNotIn('id', $selected->pluck('id'))
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $selected = $selected->merge($fallback);
        }

        // Allow repeats if still not enough (ignore 7-day exclusion)
        if ($selected->count() < $count) {
            $needed = $count - $selected->count();

            $fallback = Question::whereIn('lesson_id', $lessonIds)
                ->whereNotIn('id', $selected->pluck('id'))
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $selected = $selected->merge($fallback);
        }

        // Ultimate Fallback: If STILL empty, grab any question from the ENTIRE course
        if ($selected->count() === 0) {
            $courseLessonIds = $lesson->course->lessons()->pluck('id');
            $selected = Question::whereIn('lesson_id', $courseLessonIds)
                ->inRandomOrder()
                ->take($count)
                ->get();
        }

        return [
            'questions' => $selected,
            'course' => $enrollment->course,
            'lesson' => $lesson,
        ];
    }

    private function getTargetEnrollment(User $user): ?Enrollment
    {
        return $user->enrollments()
            ->with('course')
            ->orderByRaw('COALESCE(last_accessed_at, started_at, created_at) desc')
            ->first();
    }

    private function getTargetLesson(User $user, Enrollment $enrollment): ?Lesson
    {
        $progress = $user->lessonProgress()
            ->where('enrollment_id', $enrollment->id)
            ->orderBy('updated_at', 'desc')
            ->with('lesson')
            ->first();

        if ($progress && $progress->lesson) {
            return $progress->lesson;
        }

        return $enrollment->course->lessons()
            ->orderBy('order_index')
            ->first();
    }

    private function sendPromptForUser(User $user, int $questionCount): bool
    {
        $enrollment = $this->getTargetEnrollment($user);
        if (!$enrollment) {
            return false;
        }

        $lesson = $this->getTargetLesson($user, $enrollment);
        if (!$lesson) {
            return false;
        }

        return (bool) $this->telegramService->sendDailyQuizPrompt(
            $user,
            $enrollment->course,
            $lesson,
            $questionCount
        );
    }

    private function getDailyQuizQuestionCount(int $maxAvailable): int
    {
        $desired = random_int(3, 5);
        return max(1, min($desired, $maxAvailable));
    }

    /**
     * Create daily question record
     */
    private function createDailyQuestion(User $user, Question $question)
    {
        return DailyQuestion::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'scheduled_for' => today(),
        ]);
    }

    /**
     * Send a daily question via Telegram (inline keyboard)
     */
    public function sendDailyQuestion(DailyQuestion $dailyQuestion)
    {
        return $this->telegramService->sendDailyQuestionWithKeyboard($dailyQuestion);
    }

    /**
     * Start today's quiz by sending the next unanswered question
     */
    public function startDailyQuiz(User $user)
    {
        $dailyQuestion = $this->getUnansweredQuestion($user);

        if (!$dailyQuestion) {
            return false;
        }

        return $this->sendDailyQuestion($dailyQuestion);
    }

    /**
     * Process answer from Telegram
     */
    public function processAnswer($chatId, $answer, $dailyQuestionId = null)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.',
            ];
        }

        $dailyQuestion = null;

        if ($dailyQuestionId) {
            $dailyQuestion = DailyQuestion::where('id', $dailyQuestionId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$dailyQuestion) {
            $dailyQuestion = $this->getUnansweredQuestion($user);
        }

        if (!$dailyQuestion) {
            return [
                'success' => false,
                'message' => 'No pending quiz questions right now.',
            ];
        }

        return $this->processAnswerForQuestion($user, $dailyQuestion, $answer);
    }

    private function processAnswerForQuestion(User $user, DailyQuestion $dailyQuestion, $answer)
    {
        $answer = strtoupper(trim($answer));

        if (!in_array($answer, ['A', 'B', 'C', 'D'])) {
            return [
                'success' => false,
                'message' => 'Please answer with A, B, C, or D.',
            ];
        }

        if ($dailyQuestion->answered_at) {
            return [
                'success' => false,
                'message' => 'This question was already answered.',
            ];
        }

        if ($dailyQuestion->scheduled_for->toDateString() !== today()->toDateString()) {
            return [
                'success' => false,
                'message' => 'This quiz is no longer active.',
            ];
        }

        $isCorrect = $dailyQuestion->recordAnswer($answer);
        $question = $dailyQuestion->question;

        $response = $isCorrect
            ? '<b>Correct!</b>'
            : '<b>Incorrect.</b>';

        $response .= "\nYour answer: {$answer}";
        $response .= "\nCorrect answer: {$question->correct_answer} - {$question->correct_option_text}";

        if ($question->explanation) {
            $response .= "\n\nExplanation:\n{$question->explanation}";
        }

        $totalCount = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->count();

        $answeredCount = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNotNull('answered_at')
            ->count();

        $response .= "\n\nProgress: {$answeredCount}/{$totalCount}";

        $nextQuestion = $this->getUnansweredQuestion($user);

        if (!$nextQuestion) {
            $correctCount = DailyQuestion::where('user_id', $user->id)
                ->whereDate('scheduled_for', today())
                ->where('is_correct', true)
                ->count();

            $response .= "\n\nDaily quiz complete! Score: {$correctCount}/{$totalCount}.";

            $lesson = $question->lesson;
            $course = $lesson ? $lesson->course : null;

            Notification::create([
                'user_id' => $user->id,
                'notification_type' => 'quiz_result',
                'title' => 'Daily Quiz Result',
                'message' => "You answered {$correctCount}/{$totalCount} correctly in today's quiz.",
                'action_url' => $course ? route('student.courses.learn', $course) : null,
            ]);
        }

        return [
            'success' => true,
            'is_correct' => $isCorrect,
            'message' => $response,
            'next_question' => $nextQuestion,
        ];
    }

    /**
     * Get user's daily question statistics
     */
    public function getUserStatistics(User $user)
    {
        $totalAnswered = DailyQuestion::where('user_id', $user->id)
            ->whereNotNull('answered_at')
            ->count();

        $totalCorrect = DailyQuestion::where('user_id', $user->id)
            ->where('is_correct', true)
            ->count();

        $accuracy = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100) : 0;

        return [
            'total_answered' => $totalAnswered,
            'total_correct' => $totalCorrect,
            'total_incorrect' => $totalAnswered - $totalCorrect,
            'accuracy' => $accuracy,
        ];
    }

    /**
     * Get unanswered question for user
     */
    public function getUnansweredQuestion(User $user)
    {
        return DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNull('answered_at')
            ->with('question')
            ->orderBy('id')
            ->first();
    }
}
