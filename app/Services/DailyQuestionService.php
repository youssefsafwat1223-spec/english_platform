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
     * Schedule daily quiz questions for all active users (called by cron at 6 PM)
     */
    public function scheduleQuestionsForToday()
    {
        $scheduledCount = 0;
        $sentCount = 0;

        // Get all active students with telegram linked
        $users = User::active()
            ->students()
            ->telegramLinked()
            ->get();

        foreach ($users as $user) {
            // Check if this user should receive a quiz today (every other day)
            if (!$this->shouldSendQuestionToday($user)) {
                continue;
            }

            // Loop over ALL enrolled courses for this user
            $enrollments = $user->enrollments()->with('course')->get();

            foreach ($enrollments as $enrollment) {
                $result = $this->scheduleQuestionsForEnrollment($user, $enrollment);

                $scheduledCount += $result['scheduled'];

                if ($result['prompt_sent']) {
                    $sentCount++;
                }
            }
        }

        Log::info('Daily questions scheduled', [
            'scheduled' => $scheduledCount,
            'prompts_sent' => $sentCount,
        ]);

        return [
            'scheduled' => $scheduledCount,
            'sent' => $sentCount,
        ];
    }

    /**
     * Schedule 10 questions for a specific user + enrollment (one course)
     */
    public function scheduleQuestionsForEnrollment(User $user, Enrollment $enrollment, bool $sendPrompt = true): array
    {
        $course = $enrollment->course;

        if (!$course) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        // Check if questions already scheduled for this user + course today
        $todayQuestions = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereHas('question', function ($q) use ($course) {
                $q->whereHas('lesson', function ($lq) use ($course) {
                    $lq->where('course_id', $course->id);
                });
            })
            ->count();

        if ($todayQuestions > 0) {
            // Already scheduled — just re-send prompt if needed
            if ($sendPrompt) {
                $lesson = $this->getTargetLesson($user, $enrollment);
                if ($lesson) {
                    $this->telegramService->sendDailyQuizPrompt($user, $course, $lesson, $todayQuestions);
                    return ['scheduled' => 0, 'prompt_sent' => true];
                }
            }
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        // Get the lesson the student has reached
        $lesson = $this->getTargetLesson($user, $enrollment);
        if (!$lesson) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        // Select 10 questions from lesson 1 → current lesson
        $questions = $this->selectQuestionsForEnrollment($user, $enrollment, $lesson);

        if ($questions->isEmpty()) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        // Save the selected questions as DailyQuestion records
        foreach ($questions as $question) {
            $this->createDailyQuestion($user, $question);
        }

        // Send the "Ready?" prompt via Telegram
        $promptSent = false;
        if ($sendPrompt) {
            $promptSent = $this->telegramService->sendDailyQuizPrompt(
                $user,
                $course,
                $lesson,
                $questions->count()
            );
        }

        // Create in-app notification
        Notification::create([
            'user_id' => $user->id,
            'notification_type' => 'daily_question',
            'title' => 'Daily Quiz Ready',
            'message' => "Your daily quiz for {$course->title} is ready ({$questions->count()} questions).",
            'action_url' => route('student.courses.learn', $course),
        ]);

        return ['scheduled' => $questions->count(), 'prompt_sent' => (bool) $promptSent];
    }

    /**
     * Manually trigger quiz for a user (via /today command) — all courses
     */
    public function scheduleQuestionsForUser(User $user, bool $sendPrompt = true, bool $promptIfExisting = false): array
    {
        $totalScheduled = 0;
        $anySent = false;

        $enrollments = $user->enrollments()->with('course')->get();

        if ($enrollments->isEmpty()) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        foreach ($enrollments as $enrollment) {
            $result = $this->scheduleQuestionsForEnrollment($user, $enrollment, $sendPrompt);
            $totalScheduled += $result['scheduled'];
            if ($result['prompt_sent']) {
                $anySent = true;
            }
        }

        return ['scheduled' => $totalScheduled, 'prompt_sent' => $anySent];
    }

    /**
     * Check if user should receive quiz today (every other day)
     */
    private function shouldSendQuestionToday(User $user)
    {
        $sendAlternateDays = config('app.send_daily_questions_alternate_days', true);

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
            return true; // Already scheduled for today, allow re-prompting
        }

        return $lastQuestion->scheduled_for->diffInDays(today()) >= 2;
    }

    /**
     * Select 10 questions for a specific enrollment (lesson 1 → current lesson)
     */
    private function selectQuestionsForEnrollment(User $user, Enrollment $enrollment, Lesson $lesson)
    {
        $count = 10;

        // Get all lesson IDs from lesson 1 up to the student's current lesson
        $lessonIds = $lesson->course->lessons()
            ->where('order_index', '<=', $lesson->order_index)
            ->pluck('id');

        $availableCount = Question::whereIn('lesson_id', $lessonIds)->count();

        // If fewer than 3 questions in the range, expand to ALL course lessons
        if ($availableCount < 3) {
            $lessonIds = $lesson->course->lessons()->pluck('id');
            $availableCount = Question::whereIn('lesson_id', $lessonIds)->count();
            if ($availableCount === 0) {
                return collect();
            }
        }

        // Cap count to available
        $count = min($count, $availableCount);

        // Questions asked to this user in the last 7 days (try to avoid repeats)
        $recentlyAskedQuestionIds = DailyQuestion::where('user_id', $user->id)
            ->where('scheduled_for', '>=', today()->subDays(7))
            ->pluck('question_id');

        $selected = collect();

        // 1. Fresh questions from the current lesson
        $primary = Question::where('lesson_id', $lesson->id)
            ->whereNotIn('id', $recentlyAskedQuestionIds)
            ->inRandomOrder()
            ->take($count)
            ->get();
        $selected = $selected->merge($primary);

        // 2. Fresh questions from previous lessons
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

        // 3. Allow repeats if still not enough
        if ($selected->count() < $count) {
            $needed = $count - $selected->count();
            $fallback = Question::whereIn('lesson_id', $lessonIds)
                ->whereNotIn('id', $selected->pluck('id'))
                ->inRandomOrder()
                ->take($needed)
                ->get();
            $selected = $selected->merge($fallback);
        }

        // 4. Ultimate fallback: any question from the entire course
        if ($selected->count() === 0) {
            $courseLessonIds = $lesson->course->lessons()->pluck('id');
            $selected = Question::whereIn('lesson_id', $courseLessonIds)
                ->inRandomOrder()
                ->take($count)
                ->get();
        }

        return $selected;
    }

    /**
     * Get the last lesson the student accessed in the enrollment
     */
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

        // Default to the first lesson
        return $enrollment->course->lessons()
            ->orderBy('order_index')
            ->first();
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

    /**
     * Send reminders to users who haven't started their quiz (called at 7 PM)
     */
    public function sendQuizReminders()
    {
        $remindedCount = 0;

        // Find users who have questions scheduled for today but none answered
        $userIds = DailyQuestion::whereDate('scheduled_for', today())
            ->whereNull('answered_at')
            ->pluck('user_id')
            ->unique();

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            if (!$user || !$user->is_telegram_linked) {
                continue;
            }

            // Check if user has answered ANY question today
            $answeredToday = DailyQuestion::where('user_id', $userId)
                ->whereDate('scheduled_for', today())
                ->whereNotNull('answered_at')
                ->exists();

            if ($answeredToday) {
                continue; // Already started, skip
            }

            $unansweredCount = DailyQuestion::where('user_id', $userId)
                ->whereDate('scheduled_for', today())
                ->whereNull('answered_at')
                ->count();

            $text = "<b>⏰ تذكير!</b>\n\n";
            $text .= "كويز اليوم لسه مستنيك! عندك {$unansweredCount} سؤال.\n\n";
            $text .= "ابدأ دلوقتي عشان متفوتش اليوم! 💪";

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'ابدأ الكويز 🚀', 'callback_data' => 'daily_quiz_start'],
                    ],
                ],
            ];

            $this->telegramService->sendMessage($user->telegram_chat_id, $text, $keyboard);
            $remindedCount++;
        }

        Log::info('Quiz reminders sent', ['reminded' => $remindedCount]);

        return $remindedCount;
    }
}
