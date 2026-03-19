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
                "I didn't understand that. Use /help to see available commands."
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
                    $message = "Welcome back, {$user->name}!\n\n";
                    $message .= "You are already connected to your account.\n";
                    $message .= "Use /status to check your progress or /help to see available commands.";
                } else {
                    $message = "Welcome to Simple English!\n\n";
                    $message .= "To link your account, please send your registered phone number.\n\n";
                    $message .= "Example: 01012345678";
                }
                break;

            case '/status':
                $message = $this->telegramService->getUserProgress($chatId);
                if (!$message) {
                    $message = "Please link your account first by sending your phone number.";
                }
                break;

            case '/today':
                $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

                if (!$user) {
                    $message = "Please link your account first by sending your phone number.";
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
                        // Start the quiz directly
                        $this->dailyQuestionService->startDailyQuiz($user);
                        return response()->json(['ok' => true]);
                    }

                    $answeredToday = \App\Models\DailyQuestion::where('user_id', $user->id)
                        ->whereDate('scheduled_for', today())
                        ->whereNotNull('answered_at')
                        ->count();

                    if ($answeredToday > 0 && $unanswered == 0) {
                        $message = "✅ خلصت كويز اليوم! استنى الكويز الجاي.";
                    } elseif ($user->enrollments()->count() === 0) {
                        $message = "مش مسجل في أي كورس. سجل في كورس عشان تبدأ تاخد كويزات يومية.";
                    } else {
                        $message = "مفيش كويز متاح دلوقتي. الكويزات بتتبعت الساعة 6 مساءً يوم ويوم.";
                    }
                }
                break;

            case '/help':
                $message = "<b>Available Commands:</b>\n\n";
                $message .= "/status - View your progress\n";
                $message .= "/today - Get today's question\n";
                $message .= "/courses - View your enrolled courses\n";
                $message .= "/leaderboard - Top 10 students\n";
                $message .= "/streak - Your current streak\n";
                $message .= "/certificate - Your certificates\n";
                $message .= "/unlink - Unlink your account\n";
                $message .= "/remind - Toggle daily reminder\n";
                $message .= "/help - Show this message\n\n";
                $message .= "To answer daily questions, reply with A, B, C, or D.";
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
                $message = "Unknown command. Use /help to see available commands.";
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
            $this->telegramService->sendMessage($chatId, "من فضلك اربط حسابك الأول عن طريق إرسال رقم تليفونك.");
            return response()->json(['ok' => true]);
        }

        // Try to start the quiz directly
        if ($this->dailyQuestionService->startDailyQuiz($user)) {
            return response()->json(['ok' => true]);
        }

        // If no questions found, try scheduling first then starting
        $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, false);

        if ($result['scheduled'] > 0) {
            // Now try starting
            if ($this->dailyQuestionService->startDailyQuiz($user)) {
                return response()->json(['ok' => true]);
            }
        }

        $this->telegramService->sendMessage($chatId, "مفيش كويز متاح دلوقتي. جرب تاني بعدين! 🙏");
        return response()->json(['ok' => true]);
    }

    private function handleCoursesCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "Please link your account first by sending your phone number.");
            return response()->json(['ok' => true]);
        }

        $enrollments = $user->enrollments()->with('course')->get();

        if ($enrollments->isEmpty()) {
            $message = "📚 You haven't enrolled in any courses yet.\n\n";
            $message .= "Visit the website to browse available courses!\n";
            $message .= "<a href='" . config('app.url') . "/student/courses'>Browse Courses</a>";
        } else {
            $message = "<b>📚 Your Courses:</b>\n\n";
            foreach ($enrollments as $enrollment) {
                $progress = round($enrollment->progress_percentage);
                $emoji = $progress >= 100 ? '✅' : ($progress > 50 ? '📖' : '📕');
                $message .= "{$emoji} <b>{$enrollment->course->title}</b>\n";
                $message .= "   Progress: {$progress}% ({$enrollment->completed_lessons}/{$enrollment->total_lessons} lessons)\n\n";
            }
            $message .= "<a href='" . config('app.url') . "/student/courses/my-courses'>View on Website</a>";
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

        $message = "<b>🏆 Leaderboard — Top 10</b>\n\n";

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
            $message .= "📍 Your Rank: #{$userRank} ({$user->total_points} XP)";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleStreakCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "Please link your account first by sending your phone number.");
            return response()->json(['ok' => true]);
        }

        $current = $user->current_streak ?? 0;
        $longest = $user->longest_streak ?? 0;

        $fire = str_repeat('🔥', min($current, 10));

        $message = "<b>🔥 Your Streak</b>\n\n";
        $message .= "Current Streak: <b>{$current} days</b> {$fire}\n";
        $message .= "Longest Streak: <b>{$longest} days</b>\n\n";

        if ($current === 0) {
            $message .= "Start studying today to begin your streak! 💪";
        } elseif ($current >= 7) {
            $message .= "Amazing! You're on fire! Keep it up! 🚀";
        } elseif ($current >= 3) {
            $message .= "Great progress! Don't break the chain! 💪";
        } else {
            $message .= "Good start! Keep going every day! ⭐";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleCertificateCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "Please link your account first by sending your phone number.");
            return response()->json(['ok' => true]);
        }

        $certificates = $user->certificates()->with('course')->get();

        if ($certificates->isEmpty()) {
            $message = "🏅 You haven't earned any certificates yet.\n\n";
            $message .= "Complete a course to earn your first certificate!";
        } else {
            $message = "<b>🏅 Your Certificates:</b>\n\n";
            foreach ($certificates as $cert) {
                $courseName = $cert->course->title ?? 'Unknown Course';
                $message .= "📜 <b>{$courseName}</b>\n";
                $message .= "   Grade: {$cert->grade} — Score: {$cert->final_score}%\n";
                $message .= "   ID: <code>{$cert->certificate_id}</code>\n";
                if ($cert->verification_url) {
                    $message .= "   <a href='{$cert->verification_url}'>Download</a>\n";
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
            $this->telegramService->sendMessage($chatId, "Your account is not linked. Send your phone number to link it.");
            return response()->json(['ok' => true]);
        }

        $user->update([
            'telegram_chat_id' => null,
            'telegram_linked_at' => null,
        ]);

        $message = "✅ Your account has been unlinked from Telegram.\n\n";
        $message .= "You can link it again anytime by sending /start and your phone number.";

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }

    private function handleRemindCommand($chatId)
    {
        $user = \App\Models\User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegramService->sendMessage($chatId, "Please link your account first by sending your phone number.");
            return response()->json(['ok' => true]);
        }

        // Toggle the reminder setting
        $currentSetting = $user->telegram_reminders ?? true;
        $newSetting = !$currentSetting;

        $user->update(['telegram_reminders' => $newSetting]);

        if ($newSetting) {
            $message = "🔔 Daily study reminders are now <b>ON</b>.\n\n";
            $message .= "I'll remind you if you haven't studied for a while!";
        } else {
            $message = "🔕 Daily study reminders are now <b>OFF</b>.\n\n";
            $message .= "You can turn them back on anytime with /remind.";
        }

        $this->telegramService->sendMessage($chatId, $message);
        return response()->json(['ok' => true]);
    }
}
