<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyQuestion;
use App\Models\User;
use App\Services\DailyQuestionService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly DailyQuestionService $dailyQuestionService,
    ) {
    }

    public function webhook(Request $request)
    {
        $secretToken = config('services.telegram.webhook_secret');

        if ($secretToken) {
            $headerToken = $request->header('X-Telegram-Bot-Api-Secret-Token');

            if (!$headerToken || !hash_equals($secretToken, $headerToken)) {
                Log::warning('Telegram webhook: invalid secret token', ['ip' => $request->ip()]);

                return response()->json(['ok' => false], 403);
            }
        }

        try {
            $update = $request->all();

            if (isset($update['callback_query'])) {
                return $this->handleCallbackQuery($update['callback_query']);
            }

            if (!isset($update['message'])) {
                return response()->json(['ok' => true]);
            }

            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = trim((string) ($message['text'] ?? ''));

            if ($text !== '' && str_starts_with($text, '/')) {
                return $this->handleCommand($chatId, $text);
            }

            if ($this->looksLikePhoneNumber($text)) {
                return $this->handlePhoneLinking($chatId, $text);
            }

            if (preg_match('/^[A-D]$/i', $text)) {
                return $this->handleQuizAnswer($chatId, $text);
            }

            $this->telegramService->sendMessage(
                $chatId,
                'لم أفهم رسالتك. استخدم /help لعرض الأوامر المتاحة.'
            );

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            Log::error('Telegram webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['ok' => false]);
        }
    }

    private function handleCommand(int|string $chatId, string $command)
    {
        $cmd = strtolower(explode(' ', $command)[0]);

        switch ($cmd) {
            case '/start':
                $message = $this->buildStartMessage($chatId);
                break;

            case '/status':
                $message = $this->telegramService->getUserProgress($chatId)
                    ?: 'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.';
                break;

            case '/today':
                return $this->handleTodayCommand($chatId);

            case '/help':
                $message = $this->buildHelpMessage();
                break;

            case '/courses':
                return $this->handleCoursesCommand($chatId);

            case '/leaderboard':
                return $this->handleLeaderboardCommand($chatId);

            case '/streak':
                return $this->handleStreakCommand($chatId);

            case '/certificate':
            case '/certificates':
                return $this->handleCertificateCommand($chatId);

            case '/unlink':
                return $this->handleUnlinkCommand($chatId);

            case '/remind':
                return $this->handleRemindCommand($chatId);

            default:
                $message = 'هذا الأمر غير معروف. استخدم /help لعرض الأوامر المتاحة.';
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handlePhoneLinking(int|string $chatId, string $phone)
    {
        $result = $this->telegramService->linkUserAccount($chatId, $phone);
        $this->telegramService->sendMessage($chatId, $result['message']);

        return response()->json(['ok' => true]);
    }

    private function handleQuizAnswer(int|string $chatId, string $answer)
    {
        $result = $this->dailyQuestionService->processAnswer($chatId, $answer);
        $this->telegramService->sendMessage($chatId, $result['message']);

        if (!empty($result['next_question'])) {
            $this->telegramService->sendDailyQuestionWithKeyboard($result['next_question']);
        }

        return response()->json(['ok' => true]);
    }

    private function handleCallbackQuery(array $callback)
    {
        $callbackId = $callback['id'] ?? null;
        $data = $callback['data'] ?? '';
        $chatId = $callback['message']['chat']['id'] ?? null;

        if ($callbackId) {
            $this->telegramService->answerCallbackQuery($callbackId);
        }

        if (!$chatId) {
            return response()->json(['ok' => true]);
        }

        if ($data === 'daily_quiz_start') {
            return $this->handleDailyQuizStart($chatId);
        }

        if (str_starts_with($data, 'dq:')) {
            [$prefix, $dailyQuestionId, $answer] = array_pad(explode(':', $data), 3, null);

            if ($dailyQuestionId && $answer) {
                $result = $this->dailyQuestionService->processAnswer($chatId, $answer, $dailyQuestionId);
                $this->telegramService->sendMessage($chatId, $result['message']);

                if (!empty($result['next_question'])) {
                    $this->telegramService->sendDailyQuestionWithKeyboard($result['next_question']);
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    private function handleDailyQuizStart(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        if ($this->dailyQuestionService->startDailyQuiz($user)) {
            return response()->json(['ok' => true]);
        }

        $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, false);

        if ($result['scheduled'] > 0 && $this->dailyQuestionService->startDailyQuiz($user)) {
            return response()->json(['ok' => true]);
        }

        $this->telegramService->sendMessage($chatId, 'لا توجد أسئلة متاحة لك الآن. حاول مرة أخرى لاحقًا.');

        return response()->json(['ok' => true]);
    }

    private function handleTodayCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, true);

        if ($result['prompt_sent'] || $result['scheduled'] > 0) {
            return response()->json(['ok' => true]);
        }

        $unanswered = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNull('answered_at')
            ->count();

        if ($unanswered > 0) {
            $this->dailyQuestionService->startDailyQuiz($user);

            return response()->json(['ok' => true]);
        }

        $answeredToday = DailyQuestion::where('user_id', $user->id)
            ->whereDate('scheduled_for', today())
            ->whereNotNull('answered_at')
            ->count();

        if ($answeredToday > 0) {
            $message = 'أنهيت أسئلة اليوم بالفعل. سنرسل لك الأسئلة القادمة في موعدها.';
        } elseif ($user->enrollments()->count() === 0) {
            $message = 'لا توجد لديك دورات مسجلة حاليًا. اشترك في إحدى الدورات لتصلك الأسئلة اليومية.';
        } else {
            $message = 'لا توجد أسئلة يومية متاحة الآن. تُرسل الأسئلة تلقائيًا وفق الإعدادات المعتمدة.';
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handleCoursesCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        $enrollments = $user->enrollments()->with('course')->get();

        if ($enrollments->isEmpty()) {
            $message = "لا توجد لديك دورات مسجلة حاليًا.\n\n";
            $message .= "<a href='" . config('app.url') . "/student/courses'>تصفح الدورات</a>";
        } else {
            $message = "<b>دوراتك الحالية</b>\n\n";

            foreach ($enrollments as $enrollment) {
                $progress = round($enrollment->progress_percentage);
                $message .= "<b>{$enrollment->course->title}</b>\n";
                $message .= "التقدم: {$progress}% ({$enrollment->completed_lessons}/{$enrollment->total_lessons} دروس)\n\n";
            }

            $message .= "<a href='" . config('app.url') . "/student/courses/my-courses'>عرض الدورات داخل المنصة</a>";
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handleLeaderboardCommand(int|string $chatId)
    {
        $topStudents = User::where('role', 'student')
            ->where('is_active', true)
            ->orderByDesc('total_points')
            ->limit(10)
            ->get(['name', 'total_points']);

        $message = "<b>قائمة المتصدرين</b>\n\n";

        foreach ($topStudents as $index => $student) {
            $rank = $index + 1;
            $message .= "#{$rank} <b>{$student->name}</b> - {$student->total_points} نقطة\n";
        }

        $user = User::where('telegram_chat_id', $chatId)->first();

        if ($user) {
            $message .= "\nترتيبك الحالي: #{$user->getRank()} ({$user->total_points} نقطة)";
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handleStreakCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        $message = "<b>سلسلة نشاطك</b>\n\n";
        $message .= "السلسلة الحالية: {$user->current_streak} يوم\n";
        $message .= "أطول سلسلة: {$user->longest_streak} يوم";

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handleCertificateCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        $certificates = $user->certificates()->with('course')->get();

        if ($certificates->isEmpty()) {
            $message = 'لا توجد شهادات متاحة لك حتى الآن.';
        } else {
            $message = "<b>شهاداتك</b>\n\n";

            foreach ($certificates as $certificate) {
                $courseName = $certificate->course->title ?? 'دورة غير معروفة';
                $message .= "<b>{$courseName}</b>\n";
                $message .= "التقدير: {$certificate->grade} - الدرجة: {$certificate->final_score}%\n";
                $message .= "رقم الشهادة: <code>{$certificate->certificate_id}</code>\n";

                if ($certificate->verification_url) {
                    $message .= "<a href='{$certificate->verification_url}'>عرض الشهادة</a>\n";
                }

                $message .= "\n";
            }
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handleUnlinkCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, 'هذا الحساب غير مربوط حاليًا.');

            return response()->json(['ok' => true]);
        }

        $user->update([
            'telegram_chat_id' => null,
            'telegram_linked_at' => null,
        ]);

        $this->telegramService->sendMessage(
            $chatId,
            'تم فك ربط حسابك من تيليجرام. يمكنك إعادة الربط في أي وقت باستخدام /start ثم إرسال رقم هاتفك مع كود الدولة.'
        );

        return response()->json(['ok' => true]);
    }

    private function handleRemindCommand(int|string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage(
                $chatId,
                'لربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة. مثال: +9665XXXXXXXX أو +2010XXXXXXX.'
            );

            return response()->json(['ok' => true]);
        }

        $newSetting = !($user->telegram_reminders ?? true);
        $user->update(['telegram_reminders' => $newSetting]);

        $message = $newSetting
            ? 'تم تفعيل تذكيرات تيليجرام اليومية لحسابك.'
            : 'تم إيقاف تذكيرات تيليجرام اليومية لحسابك.';

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function buildStartMessage(int|string $chatId): string
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if ($user) {
            return "مرحبًا {$user->name}.\n\nحسابك مربوط بالفعل. استخدم /status لمتابعة تقدمك أو /help لعرض الأوامر المتاحة.";
        }

        return "مرحبًا بك في بوت Simple English.\n\nلربط حسابك، أرسل رقم هاتفك المسجل في المنصة مع كود الدولة.\nمثال: +9665XXXXXXXX أو +2010XXXXXXX";
    }

    private function buildHelpMessage(): string
    {
        return "<b>الأوامر المتاحة</b>\n\n"
            . "/status - عرض تقدمك الحالي\n"
            . "/today - بدء أسئلة اليوم\n"
            . "/courses - عرض الدورات المسجلة\n"
            . "/leaderboard - عرض المتصدرين\n"
            . "/streak - عرض سلسلة النشاط\n"
            . "/certificate - عرض الشهادات\n"
            . "/unlink - فك ربط تيليجرام\n"
            . "/remind - تشغيل أو إيقاف التذكيرات\n"
            . "/help - عرض هذه الرسالة";
    }

    private function looksLikePhoneNumber(string $text): bool
    {
        if ($text === '' || !preg_match('/^[\d\+\-\(\)\s]+$/', $text)) {
            return false;
        }

        return $this->telegramService->normalizePhoneNumber($text) !== null;
    }
}
