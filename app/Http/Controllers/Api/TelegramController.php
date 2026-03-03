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
                    $result = $this->dailyQuestionService->scheduleQuestionsForUser($user, true, true);

                    if ($result['prompt_sent'] || $result['scheduled'] > 0) {
                        return response()->json(['ok' => true]);
                    }

                    $message = "No daily quiz available right now. Quizzes are sent at 6 PM every other day.";
                }
                break;

            case '/help':
                $message = "<b>Available Commands:</b>\n\n";
                $message .= "/status - View your progress\n";
                $message .= "/today - Get today's question\n";
                $message .= "/help - Show this message\n\n";
                $message .= "To answer daily questions, reply with A, B, C, or D.";
                break;

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
            $this->telegramService->sendMessage($chatId, "Please link your account first by sending your phone number.");
            return response()->json(['ok' => true]);
        }

        if (!$this->dailyQuestionService->startDailyQuiz($user)) {
            $this->telegramService->sendMessage($chatId, "No daily quiz available right now. Check back later.");
        }

        return response()->json(['ok' => true]);
    }
}
