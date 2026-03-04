<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyQuestion;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private $botToken;
    private $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send message to user
     */
    public function sendMessage($chatId, $text, $keyboard = null)
    {
        try {
            $params = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if ($keyboard) {
                $params['reply_markup'] = json_encode($keyboard);
            }

            $response = Http::post("{$this->apiUrl}/sendMessage", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Telegram send message failed', [
                'chat_id' => $chatId,
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram send message exception', [
                'message' => $e->getMessage(),
                'chat_id' => $chatId,
            ]);

            return null;
        }
    }

    /**
     * Answer callback query (remove loading state)
     */
    public function answerCallbackQuery($callbackId, $text = null)
    {
        try {
            $params = ['callback_query_id' => $callbackId];

            if ($text) {
                $params['text'] = $text;
            }

            $response = Http::post("{$this->apiUrl}/answerCallbackQuery", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Telegram answer callback failed', [
                'callback_id' => $callbackId,
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram answer callback exception', [
                'message' => $e->getMessage(),
                'callback_id' => $callbackId,
            ]);

            return null;
        }
    }

    /**
     * Send document to user
     */
    public function sendDocument($chatId, $filePath, $caption = null)
    {
        try {
            if (!$filePath || !file_exists($filePath)) {
                Log::error('Telegram send document failed', [
                    'chat_id' => $chatId,
                    'error' => 'File not found',
                    'file_path' => $filePath,
                ]);
                return null;
            }

            $params = [
                'chat_id' => $chatId,
            ];

            if ($caption) {
                $params['caption'] = $caption;
                $params['parse_mode'] = 'HTML';
            }

            $response = Http::attach(
                'document',
                fopen($filePath, 'r'),
                basename($filePath)
            )->post("{$this->apiUrl}/sendDocument", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Telegram send document failed', [
                'chat_id' => $chatId,
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram send document exception', [
                'message' => $e->getMessage(),
                'chat_id' => $chatId,
            ]);

            return null;
        }
    }

    /**
     * Send daily quiz prompt
     */
    public function sendDailyQuizPrompt($user, $course, $lesson, int $questionCount)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>Daily Quiz Ready</b>\n\n";
        $text .= "Course: {$course->title}\n";
        $text .= "Lesson: {$lesson->title}\n";
        $text .= "Questions: {$questionCount}\n\n";
        $text .= "Press <b>Quiz</b> to start.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Quiz', 'callback_data' => 'daily_quiz_start'],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
    }

    /**
     * Send daily question to user (inline keyboard)
     */
    public function sendDailyQuestionWithKeyboard(DailyQuestion $dailyQuestion)
    {
        $user = $dailyQuestion->user;

        if (!$user->is_telegram_linked) {
            return false;
        }

        $question = $dailyQuestion->question;

        $text = "<b>Daily Quiz Question</b>\n\n";
        $text .= "{$question->question_text}\n\n";
        $text .= "A) {$question->option_a}\n";
        $text .= "B) {$question->option_b}\n";

        $buttons = [
            [
                ['text' => 'A', 'callback_data' => "dq:{$dailyQuestion->id}:A"],
                ['text' => 'B', 'callback_data' => "dq:{$dailyQuestion->id}:B"],
            ],
        ];

        if ($question->option_c) {
            $text .= "C) {$question->option_c}\n";
        }

        if ($question->option_d) {
            $text .= "D) {$question->option_d}\n";
        }

        if ($question->option_c || $question->option_d) {
            $row = [];
            if ($question->option_c) {
                $row[] = ['text' => 'C', 'callback_data' => "dq:{$dailyQuestion->id}:C"];
            }
            if ($question->option_d) {
                $row[] = ['text' => 'D', 'callback_data' => "dq:{$dailyQuestion->id}:D"];
            }
            if ($row) {
                $buttons[] = $row;
            }
        }

        $text .= "\nChoose the correct option.";

        $keyboard = ['inline_keyboard' => $buttons];

        $result = $this->sendMessage($user->telegram_chat_id, $text, $keyboard);

        if ($result) {
            $dailyQuestion->markAsSent($result['result']['message_id'] ?? null);
            return true;
        }

        return false;
    }

    /**
     * Backward-compatible wrapper
     */
    public function sendDailyQuestion(DailyQuestion $dailyQuestion)
    {
        return $this->sendDailyQuestionWithKeyboard($dailyQuestion);
    }

    /**
     * Send notification to user
     */
    public function sendNotification(Notification $notification)
    {
        $user = $notification->user;

        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>{$notification->title}</b>

";
        $text .= "{$notification->message}
";

        if ($notification->action_url) {
            $text .= "
<a href='{$notification->action_url}'>View Details</a>";
        }

        $result = $this->sendMessage($user->telegram_chat_id, $text);

        if ($result) {
            $notification->markAsSentToTelegram($result['result']['message_id'] ?? null);
            return true;
        }

        return false;
    }

    /**
     * Send quiz result
     */
    public function sendQuizResult($user, $quiz, $attempt)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $status = $attempt->passed ? 'PASSED' : 'FAILED';

        $text = "<b>Quiz Result</b>\n\n";
        $text .= "Course: {$quiz->course->title}\n";
        $text .= "Quiz: {$quiz->title}\n";
        $text .= "Score: {$attempt->score}%\n";
        $text .= "Correct: {$attempt->correct_answers}/{$attempt->total_questions}\n";
        $text .= "Time: {$attempt->formatted_time}\n";
        $text .= "Status: <b>{$status}</b>\n\n";

        if ($attempt->passed) {
            $text .= "Great job! Keep it up.";
        } else {
            $text .= "Try again to improve your score.";
        }

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    /**
     * Send course purchase confirmation
     */
    public function sendCoursePurchaseConfirmation($user, $course, $enrollment)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>Purchase Successful!</b>

";
        $text .= "You've enrolled in:
";
        $text .= "<b>{$course->title}</b>

";
        $text .= "Total Lessons: {$enrollment->total_lessons}
";
        $text .= "Amount Paid: \${$enrollment->price_paid}
";

        if ($enrollment->discount_amount > 0) {
            $text .= "Discount: \${$enrollment->discount_amount}
";
        }

        $text .= "
Start learning now!
";
        $text .= "I'll send you daily questions to help you practice.
";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    /**
     * Send certificate notification
     */
    public function sendCertificateNotification($user, $course, $certificate)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>Congratulations!</b>

";
        $text .= "You've completed:
";
        $text .= "<b>{$course->title}</b>

";
        $text .= "Final Score: {$certificate->final_score}%
";
        $text .= "Grade: {$certificate->grade}
";
        $text .= "Certificate ID: {$certificate->certificate_id}

";
        $text .= "Your certificate is ready!
";
        $text .= "<a href='{$certificate->verification_url}'>Download Certificate</a>";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    /**
     * Send referral success notification
     */
    public function sendReferralSuccessNotification($referrer, $referee)
    {
        if (!$referrer->is_telegram_linked) {
            return false;
        }

        $percentage = config('app.referral_discount_percentage', 10);

        $text = "<b>Referral Used!</b>

";
        $text .= "{$referee->name} used your referral code and got the discount.

";
        $text .= "You earned a <b>{$percentage}% discount</b> on your next course.
";
        $text .= "Valid for 30 days

";
        $text .= "Keep sharing to earn more discounts!";

        return $this->sendMessage($referrer->telegram_chat_id, $text);
    }

    /**
     * Send study reminder
     */
    public function sendStudyReminder($user, $daysSinceLastActivity)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>Study Reminder</b>\n\n";
        $text .= "Hey {$user->name}!\n\n";
        $text .= "It's been {$daysSinceLastActivity} days since your last activity.\n";
        $text .= "Don't lose your streak!\n\n";
        $text .= "Continue your learning journey today!";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    /**
     * Link user account with telegram
     */
    public function linkUserAccount($chatId, $phone)
    {
        // Normalize: remove spaces, dashes, parentheses
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);

        // Build possible formats to search
        $possibleNumbers = [$cleaned, $phone];

        // If starts with +20, also try without country code (e.g. 01203628493)
        if (str_starts_with($cleaned, '+20')) {
            $possibleNumbers[] = '0' . substr($cleaned, 3);
            $possibleNumbers[] = substr($cleaned, 3); // without leading 0
        }
        // If starts with 20 (without +), also try with 0
        elseif (str_starts_with($cleaned, '20') && strlen($cleaned) >= 12) {
            $possibleNumbers[] = '0' . substr($cleaned, 2);
            $possibleNumbers[] = '+' . $cleaned;
        }
        // If starts with 0 (local format), also try with +20
        elseif (str_starts_with($cleaned, '0')) {
            $possibleNumbers[] = '+2' . $cleaned;
            $possibleNumbers[] = '2' . $cleaned;
            $possibleNumbers[] = substr($cleaned, 1); // without leading 0
        }

        $possibleNumbers = array_unique($possibleNumbers);

        $user = User::whereIn('phone', $possibleNumbers)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'No account found with this phone number.',
            ];
        }

        // If this user is already linked to THIS chat, just confirm
        if ($user->telegram_chat_id == $chatId) {
            return [
                'success' => true,
                'user' => $user,
                'message' => "Your account is already linked!\nWelcome back {$user->name}!",
            ];
        }

        // Unlink any OTHER user that currently has this chat_id
        User::where('telegram_chat_id', $chatId)
            ->where('id', '!=', $user->id)
            ->update([
                'telegram_chat_id' => null,
                'telegram_linked_at' => null,
            ]);

        $user->update([
            'telegram_chat_id' => $chatId,
            'telegram_linked_at' => now(),
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => "Account linked successfully!\nWelcome {$user->name}!",
        ];
    }

    /**
     * Get user progress
     */
    public function getUserProgress($chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            return null;
        }

        $enrollments = $user->enrollments()->with('course')->get();

        $text = "<b>Your Progress</b>\n\n";

        if ($enrollments->isEmpty()) {
            $text .= "You haven't enrolled in any courses yet.\n";
            $text .= "Visit the website to browse available courses!";
        } else {
            foreach ($enrollments as $enrollment) {
                $progress = round($enrollment->progress_percentage);
                $progressBar = $this->generateProgressBar($progress);

                $text .= "<b>{$enrollment->course->title}</b>\n";
                $text .= "{$progressBar} {$progress}%\n";
                $text .= "{$enrollment->completed_lessons}/{$enrollment->total_lessons} lessons\n\n";
            }

            $text .= "Total Points: {$user->total_points}\n";
            $text .= "Rank: #{$user->getRank()}\n";
            $text .= "Streak: {$user->current_streak} days";
        }

        return $text;
    }

    /**
     * Generate progress bar
     */
    private function generateProgressBar($percentage)
    {
        $percentage = max(0, min(100, (int) round($percentage)));
        $filled = (int) floor($percentage / 10);
        $empty = 10 - $filled;

        return str_repeat('#', $filled) . str_repeat('-', $empty);
    }

    /**
     * Set webhook
     */
    public function setWebhook($url)
    {
        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", [
                'url' => $url,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to set telegram webhook', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Remove webhook
     */
    public function deleteWebhook()
    {
        try {
            $response = Http::post("{$this->apiUrl}/deleteWebhook");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to delete telegram webhook', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get bot info
     */
    public function getBotInfo()
    {
        try {
            $response = Http::get("{$this->apiUrl}/getMe");

            if ($response->successful()) {
                return $response->json()['result'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get bot info', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
