<?php

namespace App\Services;

use App\Models\BattleRoom;
use App\Models\Course;
use App\Models\DailyQuestion;
use App\Models\Notification;
use App\Models\TelegramBotSetting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private ?string $botToken;
    private string $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

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
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

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
                'callback_id' => $callbackId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

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

            $params = ['chat_id' => $chatId];

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
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function sendDailyQuizPrompt(User $user, Course $course, string $scopeTitle, int $questionCount): bool
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>تم تجهيز أسئلة اليوم</b>\n\n";
        $text .= "الدورة: {$this->escapeHtml($course->title)}\n";
        $text .= "النطاق الحالي: {$this->escapeHtml($scopeTitle)}\n";
        $text .= "عدد الأسئلة: {$questionCount}\n\n";
        $text .= "اضغط على الزر التالي لبدء الإجابة.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'ابدأ أسئلة اليوم', 'callback_data' => 'daily_quiz_start'],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
    }

    public function sendDailyQuestionWithKeyboard(DailyQuestion $dailyQuestion): bool
    {
        $user = $dailyQuestion->user;

        if (!$user->is_telegram_linked) {
            return false;
        }

        $question = $dailyQuestion->question;

        $text = "<b>سؤال اليوم</b>\n\n";
        $text .= $this->escapeHtml($question->question_text) . "\n\n";
        $text .= "A) " . $this->escapeHtml((string) $question->option_a) . "\n";
        $text .= "B) " . $this->escapeHtml((string) $question->option_b) . "\n";

        $buttons = [
            [
                ['text' => 'A', 'callback_data' => "dq:{$dailyQuestion->id}:A"],
                ['text' => 'B', 'callback_data' => "dq:{$dailyQuestion->id}:B"],
            ],
        ];

        if ($question->option_c) {
            $text .= "C) " . $this->escapeHtml((string) $question->option_c) . "\n";
        }

        if ($question->option_d) {
            $text .= "D) " . $this->escapeHtml((string) $question->option_d) . "\n";
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

        $text .= "\nاختر الإجابة الصحيحة من الأزرار التالية.";

        $result = $this->sendMessage($user->telegram_chat_id, $text, ['inline_keyboard' => $buttons]);

        if ($result) {
            $dailyQuestion->markAsSent($result['result']['message_id'] ?? null);

            return true;
        }

        return false;
    }

    public function sendDailyQuestion(DailyQuestion $dailyQuestion): bool
    {
        return $this->sendDailyQuestionWithKeyboard($dailyQuestion);
    }

    public function sendNotification(Notification $notification): bool
    {
        $user = $notification->user;

        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = '<b>' . $this->escapeHtml($notification->title) . "</b>\n\n";
        $text .= $this->escapeHtml($notification->message);

        if ($notification->action_url) {
            $text .= "\n\n<a href='" . e($notification->action_url) . "'>عرض التفاصيل</a>";
        }

        $result = $this->sendMessage($user->telegram_chat_id, $text);

        if ($result) {
            $notification->markAsSentToTelegram($result['result']['message_id'] ?? null);

            return true;
        }

        return false;
    }

    public function sendQuizResult($user, $quiz, $attempt)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $status = $attempt->passed ? 'ناجح' : 'غير ناجح';

        $text = "<b>نتيجة الاختبار</b>\n\n";
        $text .= "الدورة: {$this->escapeHtml($quiz->course->title)}\n";
        $text .= "الاختبار: {$this->escapeHtml($quiz->title)}\n";
        $text .= "النتيجة: {$attempt->score}%\n";
        $text .= "الإجابات الصحيحة: {$attempt->correct_answers}/{$attempt->total_questions}\n";
        $text .= "الوقت المستغرق: {$attempt->formatted_time}\n";
        $text .= "الحالة: <b>{$status}</b>\n\n";
        $text .= $attempt->passed
            ? 'أحسنت. استمر على هذا الأداء.'
            : 'يمكنك إعادة المحاولة لتحسين نتيجتك.';

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    public function sendCoursePurchaseConfirmation($user, $course, $enrollment)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>تم تأكيد اشتراكك بنجاح</b>\n\n";
        $text .= "الدورة: {$this->escapeHtml($course->title)}\n";
        $text .= "عدد الدروس: {$enrollment->total_lessons}\n";
        $text .= "المبلغ المدفوع: " . number_format((float) $enrollment->price_paid, 2) . " ر.س\n";

        if ((float) $enrollment->discount_amount > 0) {
            $text .= "الخصم: " . number_format((float) $enrollment->discount_amount, 2) . " ر.س\n";
        }

        $text .= "\nسيصلك عبر تيليجرام أسئلة يومية مرتبطة بتقدمك داخل الدورة.";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    public function sendCertificateNotification($user, $course, $certificate)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>مبروك، لقد أتممت الدورة بنجاح</b>\n\n";
        $text .= "الدورة: {$this->escapeHtml($course->title)}\n";
        $text .= "الدرجة النهائية: {$certificate->final_score}%\n";
        $text .= "التقدير: {$this->escapeHtml($certificate->grade)}\n";
        $text .= "رقم الشهادة: {$this->escapeHtml($certificate->certificate_id)}\n\n";
        $text .= "<a href='{$certificate->verification_url}'>عرض الشهادة</a>";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    public function sendReferralSuccessNotification($referrer, $referee)
    {
        if (!$referrer->is_telegram_linked) {
            return false;
        }

        $percentage = config('app.referral_discount_percentage', 10);

        $text = "<b>تم استخدام رابط الإحالة الخاص بك</b>\n\n";
        $text .= $this->escapeHtml($referee->name) . " استخدم رابط الإحالة الخاص بك.\n";
        $text .= "تمت إضافة خصم {$percentage}% إلى حسابك لاستخدامه في عملية شراء قادمة.";

        return $this->sendMessage($referrer->telegram_chat_id, $text);
    }

    public function sendStudyReminder($user, $daysSinceLastActivity)
    {
        if (!$user->is_telegram_linked) {
            return false;
        }

        $text = "<b>تذكير بالدراسة</b>\n\n";
        $text .= "مرحبًا {$this->escapeHtml($user->name)}.\n";
        $text .= "لم تسجل نشاطًا دراسيًا منذ {$daysSinceLastActivity} يومًا.\n";
        $text .= "حافظ على استمراريتك وارجع لإكمال رحلتك التعليمية اليوم.";

        return $this->sendMessage($user->telegram_chat_id, $text);
    }

    public function canSendAutomatedNotifications(): bool
    {
        return filled($this->botToken)
            && (bool) TelegramBotSetting::get('enable_notifications', true);
    }

    public function sendBattleInvite(User $user, Course $course, BattleRoom $room, string $creatorName): bool
    {
        if (!$user->is_telegram_linked || !$this->canSendAutomatedNotifications()) {
            return false;
        }

        $text = "<b>دعوة إلى باتل جديدة</b>\n\n";
        $text .= "تم فتح باتل جديدة داخل دورة {$this->escapeHtml($course->title)} بواسطة {$this->escapeHtml($creatorName)}.\n";
        $text .= "بما أنك مشترك في هذه الدورة، يمكنك الانضمام مباشرة من الزر التالي.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'انضم إلى الباتل', 'url' => route('student.battle.lobby', $room)],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
    }

    public function sendBattleMarketing(User $user, Course $course, string $creatorName): bool
    {
        if (!$user->is_telegram_linked || !$this->canSendAutomatedNotifications()) {
            return false;
        }

        $text = "<b>هناك باتل نشطة الآن</b>\n\n";
        $text .= "{$this->escapeHtml($creatorName)} بدأ باتل جديدة داخل دورة {$this->escapeHtml($course->title)}.\n";
        $text .= "اشترك في الدورة لتتمكن من دخول الباتلات القادمة والمنافسة مع الطلاب.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'اشترك في الدورة', 'url' => route('student.courses.enroll', $course)],
                ],
            ],
        ];

        return (bool) $this->sendMessage($user->telegram_chat_id, $text, $keyboard);
    }

    public function normalizePhoneNumber(?string $phone): ?string
    {
        return app(PhoneNumberService::class)->normalize($phone);
    }

    public function linkUserAccount($chatId, $phone): array
    {
        $normalized = $this->normalizePhoneNumber($phone);

        if (!$normalized) {
            return [
                'success' => false,
                'message' => 'تعذر التعرّف على رقم الهاتف. أعد الإرسال مع كود الدولة، مثل +9665XXXXXXXX أو +2010XXXXXXX.',
            ];
        }

        $possibleNumbers = $this->buildPhoneLookupCandidates($normalized);

        $user = User::whereIn('phone', $possibleNumbers)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'لم نعثر على حساب بهذا الرقم. تأكد من كتابة الرقم بنفس الصيغة المسجلة في المنصة مع كود الدولة.',
            ];
        }

        if ($user->telegram_chat_id == $chatId) {
            return [
                'success' => true,
                'user' => $user,
                'message' => "حسابك مرتبط بالفعل. أهلاً بك من جديد يا {$this->escapeHtml($user->name)}.",
            ];
        }

        User::where('telegram_chat_id', $chatId)
            ->where('id', '!=', $user->id)
            ->update([
                'telegram_chat_id' => null,
                'telegram_linked_at' => null,
            ]);

        $user->update([
            'phone' => $normalized,
            'telegram_chat_id' => $chatId,
            'telegram_linked_at' => now(),
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => "تم ربط حسابك بنجاح. أهلاً بك يا {$this->escapeHtml($user->name)}.",
        ];
    }

    public function getUserProgress($chatId): ?string
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            return null;
        }

        $enrollments = $user->enrollments()->with('course')->get();

        $text = "<b>ملخص تقدمك</b>\n\n";

        if ($enrollments->isEmpty()) {
            $text .= "لا توجد لديك دورات مسجلة حاليًا.\n";
            $text .= "يمكنك تصفح الدورات من خلال الموقع والاشتراك في الدورة المناسبة لك.";

            return $text;
        }

        foreach ($enrollments as $enrollment) {
            $progress = round($enrollment->progress_percentage);

            $text .= "<b>{$this->escapeHtml($enrollment->course->title)}</b>\n";
            $text .= $this->generateProgressBar($progress) . " {$progress}%\n";
            $text .= "{$enrollment->completed_lessons}/{$enrollment->total_lessons} دروس مكتملة\n\n";
        }

        $text .= "إجمالي النقاط: {$user->total_points}\n";
        $text .= "الترتيب الحالي: #{$user->getRank()}\n";
        $text .= "سلسلة النشاط: {$user->current_streak} يوم";

        return $text;
    }

    public function setWebhook($url): bool
    {
        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", [
                'url' => $url,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to set telegram webhook', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function deleteWebhook(): bool
    {
        try {
            $response = Http::post("{$this->apiUrl}/deleteWebhook");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to delete telegram webhook', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function getBotInfo()
    {
        try {
            $response = Http::get("{$this->apiUrl}/getMe");

            if ($response->successful()) {
                return $response->json()['result'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get bot info', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function buildPhoneLookupCandidates(string $normalized): array
    {
        $digits = ltrim($normalized, '+');
        $candidates = [$normalized, $digits];

        if (str_starts_with($digits, '20') && strlen($digits) >= 12) {
            $candidates[] = '0' . substr($digits, 2);
        }

        return array_values(array_unique(array_filter($candidates)));
    }

    private function generateProgressBar($percentage): string
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
}
