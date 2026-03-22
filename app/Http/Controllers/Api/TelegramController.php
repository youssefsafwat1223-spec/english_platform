<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;
use App\Services\DailyQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    private $telegramService;
    private $dailyQuestionService;

    public function __construct(
        TelegramService $telegramService,
        DailyQuestionService $dailyQuestionService
    ) {
        $this->telegramService = $telegramService;
        $this->dailyQuestionService = $dailyQuestionService;
    }

    public function webhook(Request $request)
    {
        // ── Verify Telegram webhook secret token ──
        $secretToken = config('services.telegram.webhook_secret');
        if ($secretToken) {
            $headerToken = $request->header('X-Telegram-Bot-Api-Secret-Token');
            if (!$headerToken || !hash_equals($secretToken, $headerToken)) {
                Log::warning('Telegram webhook: Invalid secret token', ['ip' => $request->ip()]);
                return response()->json(['ok' => false], 403);
            }
        }

        try {
            $update = $request->all();

            Log::info('Telegram webhook received', $update);

            if (isset($update['callback_query'])) {
                return $this->handleCallbackQuery($update['callback_query']);
            }

            if (!isset($update['message'])) {
                Log::info('Telegram: No message in update');
                return response()->json(['ok' => true]);
            }

            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = trim($message['text'] ?? '');

            Log::info('Telegram processing message', [
                'chat_id' => $chatId,
                'text' => $text,
                'text_length' => strlen($text),
                'text_bytes' => bin2hex($text),
            ]);

            // Handle commands
            if (str_starts_with($text, '/')) {
                Log::info('Telegram: Handling command', ['command' => $text]);
                return $this->handleCommand($chatId, $text);
            }

            // Handle phone number (for linking)
            $cleanPhone = preg_replace('/[^0-9]/', '', $text);
            Log::info('Telegram: Checking phone', [
                'original' => $text,
                'cleaned' => $cleanPhone,
                'cleaned_length' => strlen($cleanPhone),
            ]);

            if (strlen($cleanPhone) >= 10 && strlen($cleanPhone) <= 15) {
                Log::info('Telegram: Phone number detected, linking', ['phone' => $cleanPhone]);
                return $this->handlePhoneLinking($chatId, $cleanPhone);
            }

            // Handle quiz answer (A, B, C, D)
            if (preg_match('/^[A-D]$/i', $text)) {
                return $this->handleQuizAnswer($chatId, $text);
            }

            // Default response
            Log::info('Telegram: No handler matched, sending default response');
            $this->telegramService->sendMessage(
                $chatId,
                "ما فهمت عليك 😅 استخدم /help عشان تشوف الأوامر المتاحة."
            );

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {
            Log::error('Telegram webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    private function handleCommand($chatId, $command)
    {
        $parts = explode(' ', $command);
        $cmd = strtolower($parts[0]);

        switch ($cmd) {
            case '/start':
                $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
                if ($user) {
                    $message = "أهلاً ومرحباً فيك يا {$user->name}! 👋\n\n";
                    $message .= "حسابك مربوط بالفعل ✅\n";
                    $message .= "استخدم /status عشان تشوف تقدمك أو /help عشان تشوف كل الأوامر.";
                } else {
                    $message = "أهلاً فيك في Simple English! 🎓\n\n";
                    $message .= "عشان تربط حسابك، أرسل رقم جوالك المسجل عندنا.\n\n";
                    $message .= "مثال: 01012345678";
                }
                break;

            case '/status':
                $message = $this->telegramService->getUserProgress($chatId);
                if (!$message) {
                    $message = "اربط حسابك أول عن طريق إرسال رقم جوالك 📱";
                }
                break;

            case '/today':
                $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

                if (!$user) {
                    $message = "اربط حسابك أول عن طريق إرسال رقم جوالك 📱";
                } else {
                    $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, true);

                    if ($result['prompt_sent'] || $result['scheduled'] > 0) {
                        return response()->json(['ok' => true]);
                    }

                    // Check if there are unanswered questions from today
                    $unanswered = \App\Models\DailyQuestion::where('user_id', $user->id)
                        ->whereDate('scheduled_for', today())
                        ->whereNull('answered_at')
                        ->count();

                    if ($unanswered > 0) {
                        $this->dailyQuestionService->startDailyQuiz($user);
                        return response()->json(['ok' => true]);
                    }

                    $answeredToday = \App\Models\DailyQuestion::where('user_id', $user->id)
                        ->whereDate('scheduled_for', today())
                        ->whereNotNull('answered_at')
                        ->count();

                    if ($answeredToday > 0 && $unanswered == 0) {
                        $message = "✅ ما شاء الله خلّصت كويز اليوم! انتظر الكويز الجاي.";
                    } elseif ($user->enrollments()->count() === 0) {
                        $message = "ما أنت مسجل بأي كورس. سجل بكورس عشان تبدأ تاخذ كويزات يومية 📚";
                    } else {
                        $message = "ما فيه كويز متاح الحين. الكويزات تنرسل الساعة 6 المسا يوم ويوم 🕕";
                    }
                }
                break;

            case '/help':
                $message = "<b>الأوامر المتاحة:</b>\n\n";
                $message .= "/status - شوف تقدمك 📊\n";
                $message .= "/today - كويز اليوم ❓\n";
                $message .= "/courses - كورساتك المسجلة 📚\n";
                $message .= "/leaderboard - أفضل 10 طلاب 🏆\n";
                $message .= "/streak - سلسلة أيامك 🔥\n";
                $message .= "/certificate - شهاداتك 🏅\n";
                $message .= "/unlink - فك ربط الحساب 🔓\n";
                $message .= "/remind - تفعيل/تعطيل التذكيرات 🔔\n";
                $message .= "/help - عرض هالرسالة ℹ️\n\n";
                $message .= "عشان تجاوب على الكويز، اختار الإجابة A أو B أو C أو D.";
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
                $message = "أمر مو معروف 🤔 استخدم /help عشان تشوف الأوامر المتاحة.";
        }

        $this->telegramService->sendMessage($chatId, $message);

        return response()->json(['ok' => true]);
    }

    private function handlePhoneLinking($chatId, $phone)
    {
        Log::info('Telegram: handlePhoneLinking called', ['chat_id' => $chatId, 'phone' => $phone]);
        
        $result = $this->telegramService->linkUserAccount($chatId, $phone);

        Log::info('Telegram: linkUserAccount result', $result);

        $this->telegramService->sendMessage($chatId, $result['message']);

        return response()->json(['ok' => true]);
    }

    private function handleQuizAnswer($chatId, $answer)
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
            $parts = explode(':', $data);
            $dailyQuestionId = $parts[1] ?? null;
            $answer = $parts[2] ?? null;

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

    private function handleDailyQuizStart($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "اربط حسابك أول عن طريق إرسال رقم جوالك 📱");
            return response()->json(['ok' => true]);
        }

        // Try to start the quiz directly
        if ($this->dailyQuestionService->startDailyQuiz($user)) {
            return response()->json(['ok' => true]);
        }

        // If no questions found, try scheduling first then starting
        $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, false);

        if ($result['scheduled'] > 0) {
            if ($this->dailyQuestionService->startDailyQuiz($user)) {
                return response()->json(['ok' => true]);
            }
        }

        $this->telegramService->sendMessage($chatId, "ما فيه كويز متاح الحين. جرب مرة ثانية بعدين! 🙏");
        return response()->json(['ok' => true]);
    }

    private function handleCoursesCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "اربط حسابك أول عن طريق إرسال رقم جوالك 📱");
            return response()->json(['ok' => true]);
        }

        $enrollments = $user->enrollments()->with('course')->get();

        if ($enrollments->isEmpty()) {
            $message = "📚 ما سجلت بأي كورس لحد الآن.\n\n";
            $message .= "زور الموقع عشان تشوف الكورسات المتاحة!\n";
            $message .= "<a href='" . config('app.url') . "/student/courses'>تصفح الكورسات</a>";
        } else {
            $message = "<b>📚 كورساتك:</b>\n\n";
            foreach ($enrollments as $enrollment) {
                $progress = round($enrollment->progress_percentage);
                $emoji = $progress >= 100 ? '✅' : ($progress > 50 ? '📖' : '📕');
                $message .= "{$emoji} <b>{$enrollment->course->title}</b>\n";
                $message .= "   التقدم: {$progress}% ({$enrollment->completed_lessons}/{$enrollment->total_lessons} دروس)\n\n";
            }
            $message .= "<a href='" . config('app.url') . "/student/courses/my-courses'>شوفها بالموقع</a>";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleLeaderboardCommand($chatId)
    {
        $topStudents = \App\Models\User::where('role', 'student')
            ->where('is_active', true)
            ->orderByDesc('total_points')
            ->limit(10)
            ->get(['name', 'total_points']);

        $message = "<b>🏆 قائمة المتصدرين — أفضل 10</b>\n\n";

        $medals = ['🥇', '🥈', '🥉'];

        foreach ($topStudents as $index => $student) {
            $rank = $index + 1;
            $medal = $medals[$index] ?? "#{$rank}";
            $message .= "{$medal} <b>{$student->name}</b> — {$student->total_points} XP\n";
        }

        // Show current user's rank if linked
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();
        if ($user) {
            $userRank = $user->getRank();
            $message .= "\n—————————————\n";
            $message .= "📍 ترتيبك: #{$userRank} ({$user->total_points} XP)";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleStreakCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "اربط حسابك أول عن طريق إرسال رقم جوالك 📱");
            return response()->json(['ok' => true]);
        }

        $current = $user->current_streak ?? 0;
        $longest = $user->longest_streak ?? 0;

        $fire = str_repeat('🔥', min($current, 10));

        $message = "<b>🔥 سلسلتك</b>\n\n";
        $message .= "السلسلة الحالية: <b>{$current} يوم</b> {$fire}\n";
        $message .= "أطول سلسلة: <b>{$longest} يوم</b>\n\n";

        if ($current === 0) {
            $message .= "ابدأ اليوم عشان تبدأ سلسلتك! 💪";
        } elseif ($current >= 7) {
            $message .= "ما شاء الله عليك! كمّل على كذا! 🚀";
        } elseif ($current >= 3) {
            $message .= "أحسنت! لا توقف واستمر! 💪";
        } else {
            $message .= "بداية حلوة! استمر كل يوم! ⭐";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleCertificateCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "اربط حسابك أول عن طريق إرسال رقم جوالك 📱");
            return response()->json(['ok' => true]);
        }

        $certificates = $user->certificates()->with('course')->get();

        if ($certificates->isEmpty()) {
            $message = "🏅 ما عندك شهادات لحد الآن.\n\n";
            $message .= "خلّص كورس كامل عشان تحصل على شهادتك الأولى! 🎓";
        } else {
            $message = "<b>🏅 شهاداتك:</b>\n\n";
            foreach ($certificates as $cert) {
                $courseName = $cert->course->title ?? 'كورس غير معروف';
                $message .= "📜 <b>{$courseName}</b>\n";
                $message .= "   التقدير: {$cert->grade} — الدرجة: {$cert->final_score}%\n";
                $message .= "   الرقم: <code>{$cert->certificate_id}</code>\n";
                if ($cert->verification_url) {
                    $message .= "   <a href='{$cert->verification_url}'>حمّل الشهادة</a>\n";
                }
                $message .= "\n";
            }
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleUnlinkCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "حسابك مو مربوط. أرسل رقم جوالك عشان تربطه 📱");
            return response()->json(['ok' => true]);
        }

        $user->update([
            'telegram_chat_id' => null,
            'telegram_linked_at' => null,
        ]);

        $message = "✅ تم فك ربط حسابك من تيليجرام.\n\n";
        $message .= "تقدر تربطه مرة ثانية بأي وقت عن طريق /start وإرسال رقم جوالك.";

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleRemindCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "اربط حسابك أول عن طريق إرسال رقم جوالك 📱");
            return response()->json(['ok' => true]);
        }

        // Toggle the reminder setting
        $currentSetting = $user->telegram_reminders ?? true;
        $newSetting = !$currentSetting;

        $user->update(['telegram_reminders' => $newSetting]);

        if ($newSetting) {
            $message = "🔔 التذكيرات اليومية <b>مفعّلة</b> الحين.\n\n";
            $message .= "بنذكرك إذا ما درست من فترة!";
        } else {
            $message = "🔕 التذكيرات اليومية <b>متوقفة</b> الحين.\n\n";
            $message .= "تقدر تفعلها مرة ثانية بـ /remind.";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }
}
