<?php

namespace App\Services;

use App\Models\BattleRoom;
use App\Models\Course;
use App\Models\User;
use App\Models\DailyQuestion;
use App\Models\Notification;
use App\Models\TelegramBotSetting;
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
     * Send daily quiz prompt (Arabic)
     */
    public function sendDailyQuizPrompt($user, $course, $lesson, int $questionCount)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>🎯 كويز اليوم جاهز!</b>\n\n";
        $text .= "📚 الكورس: {$course->title}\n";
        $text .= "📖 الدرس: {$lesson->title}\n";
        $text .= "❓ عدد الأسئلة: {$questionCount}\n\n";
        $text .= "مستعد؟ اضغط على الزر وابدأ! 💪";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'ابدأ الكويز 🚀', 'callback_data' => 'daily_quiz_start'],
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

        $text = "<b>❓ سؤال الكويز</b>\n\n";
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

        $text .= "\nاختار الإجابة الصحيحة 👇";

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
<a href='{$notification->action_url}'>شوف التفاصيل</a>";
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

        // status set below in the text block

        $status = $attempt->passed ? 'نجحت ✅' : 'ما نجحت ❌';

        $text = "<b>📝 نتيجة الكويز</b>\n\n";
        $text .= "الكورس: {$quiz->course->title}\n";
        $text .= "الكويز: {$quiz->title}\n";
        $text .= "الدرجة: {$attempt->score}%\n";
        $text .= "الصحيح: {$attempt->correct_answers}/{$attempt->total_questions}\n";
        $text .= "الوقت: {$attempt->formatted_time}\n";
        $text .= "الحالة: <b>{$status}</b>\n\n";

        if ($attempt->passed) {
            $text .= "ما شاء الله عليك! كمّل على كذا 🚀";
        } else {
            $text .= "حاول مرة ثانية عشان تحسن درجتك 💪";
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

        $text = "<b>🎉 تم الشراء بنجاح!</b>\n\n";
        $text .= "سجلت في:\n";
        $text .= "<b>{$course->title}</b>\n\n";
        $text .= "عدد الدروس: {$enrollment->total_lessons}\n";
        $text .= "المبلغ المدفوع: \${$enrollment->price_paid}\n";

        if ($enrollment->discount_amount > 0) {
            $text .= "الخصم: \${$enrollment->discount_amount}\n";
        }

        $text .= "\nابدأ التعلم الحين! 🚀\n";
        $text .= "بنرسلك أسئلة يومية عشان تتدرب.";

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

        $text = "<b>🎉 مبروك!</b>\n\n";
        $text .= "خلّصت كورس:\n";
        $text .= "<b>{$course->title}</b>\n\n";
        $text .= "الدرجة النهائية: {$certificate->final_score}%\n";
        $text .= "التقدير: {$certificate->grade}\n";
        $text .= "رقم الشهادة: {$certificate->certificate_id}\n\n";
        $text .= "شهادتك جاهزة! 🏅\n";
        $text .= "<a href='{$certificate->verification_url}'>حمّل الشهادة</a>";

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

        $text = "<b>🎁 تم استخدام كود الإحالة!</b>\n\n";
        $text .= "{$referee->name} استخدم كود الإحالة حقك وحصل على الخصم.\n\n";
        $text .= "حصلت على <b>خصم {$percentage}%</b> على كورسك الجاي.\n";
        $text .= "صالح لمدة 30 يوم\n\n";
        $text .= "استمر بالمشاركة عشان تحصل خصومات أكثر! 🚀";

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

        $text = "<b>📢 تذكير بالدراسة</b>\n\n";
        $text .= "هلا {$user->name}! 👋\n\n";
        $text .= "صار لك {$daysSinceLastActivity} يوم ما دخلت.\n";
        $text .= "لا تضيع سلسلتك! 🔥\n\n";
        $text .= "كمّل رحلة التعلم اليوم! 💪";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    /**
     * Check whether automated Telegram notifications can be sent.
     */
    public function canSendAutomatedNotifications(): bool
    {
        return filled($this->botToken)
            && (bool) TelegramBotSetting::get('enable_notifications', true);
    }

    /**
     * Send battle invite to a student already enrolled in the course.
     */
    public function sendBattleInvite(User $user, Course $course, BattleRoom $room, string $creatorName): bool
    {
        if (!$user->is_telegram_linked || !$this->canSendAutomatedNotifications()) {
            return false;
        }

        $courseTitle = $this->escapeHtml($course->title);
        $creator = $this->escapeHtml($creatorName);

        $text = "<b>⚔️ باتل جديدة في {$courseTitle}</b>\n\n";
        $text .= "{$creator} فتح روم جديدة الآن.\n";
        $text .= "أنت مشترك في الكورس، فادخل بسرعة قبل ما اللوبي يقفل.\n\n";
        $text .= "اضغط الزر تحت وانضم مباشرة.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'انضم إلى الباتل الآن', 'url' => route('student.battle.lobby', $room)],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
    }

    /**
     * Send marketing message for a battle to a student not enrolled in the course.
     */
    public function sendBattleMarketing(User $user, Course $course, string $creatorName): bool
    {
        if (!$user->is_telegram_linked || !$this->canSendAutomatedNotifications()) {
            return false;
        }

        $courseTitle = $this->escapeHtml($course->title);
        $creator = $this->escapeHtml($creatorName);

        $text = "<b>🔥 في باتل شغالة الآن داخل {$courseTitle}</b>\n\n";
        $text .= "{$creator} بدأ تحدي جديد داخل الكورس.\n";
        $text .= "إذا اشتركت في الكورس، ستقدر تدخل الباتلات القادمة وتنافس الطلاب مباشرة.\n\n";
        $text .= "افتح صفحة الكورس الآن وشاهد التفاصيل.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'اشترك في الكورس', 'url' => route('student.courses.enroll', $course)],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
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
                'message' => 'ما لقينا حساب بهالرقم. تأكد من الرقم وحاول مرة ثانية 📱',
            ];
        }

        // If this user is already linked to THIS chat, just confirm
        if ($user->telegram_chat_id == $chatId) {
            return [
                'success' => true,
                'user' => $user,
                'message' => "حسابك مربوط بالفعل! ✅\nأهلاً فيك مرة ثانية يا {$user->name}! 👋",
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
            'message' => "تم ربط الحساب بنجاح! ✅\nأهلاً فيك يا {$user->name}! 🎉",
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

        $text = "<b>📊 تقدمك</b>\n\n";

        if ($enrollments->isEmpty()) {
            $text .= "ما سجلت بأي كورس لحد الآن.\n";
            $text .= "زور الموقع عشان تشوف الكورسات المتاحة! 📚";
        } else {
            foreach ($enrollments as $enrollment) {
                $progress = round($enrollment->progress_percentage);
                $progressBar = $this->generateProgressBar($progress);

                $text .= "<b>{$enrollment->course->title}</b>\n";
                $text .= "{$progressBar} {$progress}%\n";
                $text .= "{$enrollment->completed_lessons}/{$enrollment->total_lessons} دروس\n\n";
            }

            $text .= "النقاط: {$user->total_points}\n";
            $text .= "الترتيب: #{$user->getRank()}\n";
            $text .= "السلسلة: {$user->current_streak} يوم 🔥";
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

    private function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
