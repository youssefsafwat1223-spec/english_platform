<?php

namespace App\Services;

use App\Models\CourseLevel;
use App\Models\DailyQuestion;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DailyQuestionService
{
    public function __construct(private readonly TelegramService $telegramService)
    {
    }

    public function scheduleQuestionsForToday(): array
    {
        $scheduledCount = 0;
        $sentCount = 0;

        $users = User::active()
            ->students()
            ->telegramLinked()
            ->get();

        foreach ($users as $user) {
            if (!$this->shouldSendQuestionToday($user)) {
                continue;
            }

            foreach ($user->enrollments()->with('course')->get() as $enrollment) {
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

    public function scheduleQuestionsForEnrollment(User $user, Enrollment $enrollment, bool $sendPrompt = true): array
    {
        $course = $enrollment->course;

        if (!$course) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        $todayQuestions = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereHas('question', function ($query) use ($course) {
                $query->whereHas('lesson', function ($lessonQuery) use ($course) {
                    $lessonQuery->where('course_id', $course->id);
                });
            })
            ->count();

        $scope = $this->resolveProgressScope($user, $enrollment);

        if (!$scope) {
            return ['scheduled' => 0, 'prompt_sent' => false];
        }

        if ($todayQuestions > 0) {
            $promptSent = false;

            if ($sendPrompt) {
                $promptSent = $this->telegramService->sendDailyQuizPrompt(
                    $user,
                    $course,
                    $scope['title'],
                    $todayQuestions
                );
            }

            return ['scheduled' => 0, 'prompt_sent' => (bool) $promptSent];
        }

        $questions = $this->selectQuestionsForScope($user, $scope['eligible_lesson_ids'], $scope['priority_lesson_ids']);

        if ($questions->isEmpty()) {
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
                $scope['title'],
                $questions->count()
            );
        }

        Notification::create([
            'user_id' => $user->id,
            'notification_type' => 'daily_question',
            'title' => 'تم تجهيز أسئلة اليوم',
            'message' => "تم تجهيز {$questions->count()} أسئلة من نطاق {$scope['title']} داخل دورة {$course->title}.",
            'action_url' => route('student.courses.learn', $course),
        ]);

        return ['scheduled' => $questions->count(), 'prompt_sent' => (bool) $promptSent];
    }

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
            $anySent = $anySent || $result['prompt_sent'];
        }

        return ['scheduled' => $totalScheduled, 'prompt_sent' => $anySent];
    }

    public function sendDailyQuestion(DailyQuestion $dailyQuestion): bool
    {
        return $this->telegramService->sendDailyQuestionWithKeyboard($dailyQuestion);
    }

    public function startDailyQuiz(User $user): bool
    {
        $dailyQuestion = $this->getUnansweredQuestion($user);

        if (!$dailyQuestion) {
            return false;
        }

        return $this->sendDailyQuestion($dailyQuestion);
    }

    public function processAnswer($chatId, $answer, $dailyQuestionId = null): array
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'لم نعثر على حساب مرتبط بهذا المحادثة.',
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
                'message' => 'لا توجد أسئلة متاحة لك الآن.',
            ];
        }

        return $this->processAnswerForQuestion($user, $dailyQuestion, $answer);
    }

    public function getUserStatistics(User $user): array
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

    public function getUnansweredQuestion(User $user): ?DailyQuestion
    {
        return DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNull('answered_at')
            ->with('question')
            ->orderBy('id')
            ->first();
    }

    public function sendQuizReminders(): int
    {
        $remindedCount = 0;

        $userIds = DailyQuestion::whereDate('scheduled_for', today())
            ->whereNull('answered_at')
            ->pluck('user_id')
            ->unique();

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            if (!$user || !$user->is_telegram_linked) {
                continue;
            }

            $answeredToday = DailyQuestion::where('user_id', $userId)
                ->whereDate('scheduled_for', today())
                ->whereNotNull('answered_at')
                ->exists();

            if ($answeredToday) {
                continue;
            }

            $unansweredCount = DailyQuestion::where('user_id', $userId)
                ->whereDate('scheduled_for', today())
                ->whereNull('answered_at')
                ->count();

            $text = "<b>تذكير بأسئلة اليوم</b>\n\n";
            $text .= "ما زالت لديك {$unansweredCount} أسئلة بانتظار الإجابة.\n";
            $text .= "ابدأ الآن من الزر التالي.";

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'ابدأ أسئلة اليوم', 'callback_data' => 'daily_quiz_start'],
                    ],
                ],
            ];

            $this->telegramService->sendMessage($user->telegram_chat_id, $text, $keyboard);
            $remindedCount++;
        }

        Log::info('Quiz reminders sent', ['reminded' => $remindedCount]);

        return $remindedCount;
    }

    private function shouldSendQuestionToday(User $user): bool
    {
        $sendAlternateDays = config('app.send_daily_questions_alternate_days', true);

        if (!$sendAlternateDays) {
            return true;
        }

        $lastQuestion = DailyQuestion::where('user_id', $user->id)
            ->orderBy('scheduled_for', 'desc')
            ->first();

        if (!$lastQuestion) {
            return true;
        }

        if ($lastQuestion->scheduled_for->isToday()) {
            return true;
        }

        return $lastQuestion->scheduled_for->diffInDays(today()) >= 2;
    }

    private function selectQuestionsForScope(User $user, Collection $eligibleLessonIds, Collection $priorityLessonIds): Collection
    {
        if ($eligibleLessonIds->isEmpty()) {
            return collect();
        }

        $count = min(10, Question::whereIn('lesson_id', $eligibleLessonIds)->count());

        if ($count === 0) {
            return collect();
        }

        $recentlyAskedQuestionIds = DailyQuestion::where('user_id', $user->id)
            ->where('scheduled_for', '>=', today()->subDays(7))
            ->pluck('question_id');

        $selected = collect();

        $primary = Question::whereIn('lesson_id', $priorityLessonIds)
            ->whereNotIn('id', $recentlyAskedQuestionIds)
            ->inRandomOrder()
            ->take($count)
            ->get();

        $selected = $selected->merge($primary);

        if ($selected->count() < $count) {
            $needed = $count - $selected->count();
            $fallback = Question::whereIn('lesson_id', $eligibleLessonIds)
                ->whereNotIn('id', $recentlyAskedQuestionIds)
                ->whereNotIn('id', $selected->pluck('id'))
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $selected = $selected->merge($fallback);
        }

        if ($selected->count() < $count) {
            $needed = $count - $selected->count();
            $fallback = Question::whereIn('lesson_id', $eligibleLessonIds)
                ->whereNotIn('id', $selected->pluck('id'))
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $selected = $selected->merge($fallback);
        }

        return $selected;
    }

    private function resolveProgressScope(User $user, Enrollment $enrollment): ?array
    {
        $course = $enrollment->course;

        if (!$course) {
            return null;
        }

        $engagedProgress = $user->lessonProgress()
            ->where('enrollment_id', $enrollment->id)
            ->where(function ($query) {
                $query->where('is_completed', true)
                    ->orWhere('time_spent', '>', 0)
                    ->orWhere('last_position', '>', 0);
            })
            ->with('lesson.level')
            ->get();

        $reachedLevel = $engagedProgress
            ->pluck('lesson.level')
            ->filter()
            ->sortByDesc('order_index')
            ->first();

        if (!$reachedLevel) {
            $reachedLevel = $course->levels()->orderBy('order_index')->first();
        }

        if ($reachedLevel instanceof CourseLevel) {
            $eligibleLessonIds = $course->lessons()
                ->whereHas('level', function ($query) use ($reachedLevel) {
                    $query->where('order_index', '<=', $reachedLevel->order_index);
                })
                ->pluck('id');

            $priorityLessonIds = $course->lessons()
                ->where('course_level_id', $reachedLevel->id)
                ->pluck('id');

            return [
                'title' => $reachedLevel->title,
                'eligible_lesson_ids' => $eligibleLessonIds,
                'priority_lesson_ids' => $priorityLessonIds,
            ];
        }

        $targetLesson = $engagedProgress
            ->pluck('lesson')
            ->filter()
            ->sortByDesc('order_index')
            ->first();

        if (!$targetLesson) {
            $targetLesson = $course->lessons()->orderBy('order_index')->first();
        }

        if (!$targetLesson) {
            return null;
        }

        $eligibleLessonIds = $course->lessons()
            ->where('order_index', '<=', $targetLesson->order_index)
            ->pluck('id');

        return [
            'title' => $targetLesson->title,
            'eligible_lesson_ids' => $eligibleLessonIds,
            'priority_lesson_ids' => collect([$targetLesson->id]),
        ];
    }

    private function createDailyQuestion(User $user, Question $question): DailyQuestion
    {
        return DailyQuestion::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'scheduled_for' => today(),
        ]);
    }

    private function processAnswerForQuestion(User $user, DailyQuestion $dailyQuestion, $answer): array
    {
        $answer = strtoupper(trim((string) $answer));

        if (!in_array($answer, ['A', 'B', 'C', 'D'], true)) {
            return [
                'success' => false,
                'message' => 'أرسل الإجابة بالحرف A أو B أو C أو D.',
            ];
        }

        if ($dailyQuestion->answered_at) {
            return [
                'success' => false,
                'message' => 'تمت الإجابة عن هذا السؤال مسبقًا.',
            ];
        }

        if ($dailyQuestion->scheduled_for->toDateString() !== today()->toDateString()) {
            return [
                'success' => false,
                'message' => 'انتهى وقت هذه الأسئلة.',
            ];
        }

        $isCorrect = $dailyQuestion->recordAnswer($answer);
        $question = $dailyQuestion->question;

        $response = $isCorrect ? '<b>إجابة صحيحة</b>' : '<b>إجابة غير صحيحة</b>';
        $response .= "\nإجابتك: {$answer}";
        $response .= "\nالإجابة الصحيحة: {$question->correct_answer} - {$question->correct_option_text}";

        if ($question->explanation) {
            $response .= "\n\nالشرح:\n{$question->explanation}";
        }

        $totalCount = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->count();

        $answeredCount = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNotNull('answered_at')
            ->count();

        $response .= "\n\nالتقدم: {$answeredCount}/{$totalCount}";

        $nextQuestion = $this->getUnansweredQuestion($user);

        if (!$nextQuestion) {
            $correctCount = DailyQuestion::where('user_id', $user->id)
                ->whereDate('scheduled_for', today())
                ->where('is_correct', true)
                ->count();

            $response .= "\n\nأنهيت أسئلة اليوم. نتيجتك النهائية: {$correctCount}/{$totalCount}.";

            $course = $question->lesson?->course;

            Notification::create([
                'user_id' => $user->id,
                'notification_type' => 'quiz_result',
                'title' => 'نتيجة أسئلة اليوم',
                'message' => "أجبت إجابة صحيحة عن {$correctCount} من أصل {$totalCount} أسئلة.",
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
}
