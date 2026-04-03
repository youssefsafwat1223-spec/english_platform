<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_type',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
        'sent_to_telegram',
        'telegram_sent_at',
        'telegram_message_id',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'sent_to_telegram' => 'boolean',
            'telegram_sent_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $this->localizeStoredNotificationTitle($value, $attributes)
        );
    }

    protected function message(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $this->localizeStoredNotificationMessage($value, $attributes)
        );
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsSentToTelegram($messageId = null)
    {
        $this->update([
            'sent_to_telegram' => true,
            'telegram_sent_at' => now(),
            'telegram_message_id' => $messageId,
        ]);
    }

    public function getIconAttribute()
    {
        return match ($this->notification_type) {
            'course_purchased' => 'shopping-cart',
            'quiz_result' => 'clipboard-check',
            'comment_reply' => 'comment',
            'referral_success' => 'gift',
            'certificate_issued' => 'award',
            'daily_question' => 'help-circle',
            'achievement_earned' => 'award',
            'reply_marked_solution' => 'check-circle',
            'battle_started' => 'zap',
            'live_session_scheduled' => 'video',
            'live_session_reminder' => 'clock',
            'promo_announcement' => 'megaphone',
            default => 'bell',
        };
    }

    public function getColorAttribute()
    {
        return match ($this->notification_type) {
            'course_purchased' => 'success',
            'quiz_result' => 'info',
            'comment_reply' => 'primary',
            'referral_success' => 'warning',
            'certificate_issued' => 'success',
            'achievement_earned' => 'success',
            'reply_marked_solution' => 'info',
            'battle_started' => 'warning',
            'live_session_scheduled' => 'primary',
            'live_session_reminder' => 'warning',
            'promo_announcement' => 'warning',
            default => 'secondary',
        };
    }

    private function localizeStoredNotificationTitle(?string $value, array $attributes): ?string
    {
        if (!$value || app()->getLocale() !== 'ar') {
            return $value;
        }

        return match ($attributes['notification_type'] ?? null) {
            'quiz_result' => __('Quiz Result'),
            'referral_reward' => __('ui.notifications.free_course_title'),
            'achievement_earned' => __('Achievement Unlocked!'),
            'course_purchased' => __('Course Purchased Successfully'),
            'certificate_issued' => __('Certificate Issued!'),
            'referral_success' => __('Referral Successful!'),
            'reply_marked_solution' => __('Your Reply Marked as Solution'),
            'comment_reply' => __('New Reply on Your Comment'),
            'live_session_scheduled' => __('live_sessions.scheduled_title'),
            'live_session_reminder' => __('live_sessions.reminder_title'),
            'promo_announcement' => __('live_sessions.promo.special_offer'),
            default => $this->translateExactValue($value),
        };
    }

    private function localizeStoredNotificationMessage(?string $value, array $attributes): ?string
    {
        if (!$value || app()->getLocale() !== 'ar') {
            return $value;
        }

        return match ($attributes['notification_type'] ?? null) {
            'quiz_result' => preg_match('/^You scored (\d+)% on (.+)\.$/u', $value, $matches)
                ? __('You scored :score% on :quiz.', ['score' => $matches[1], 'quiz' => $matches[2]])
                : $this->translateExactValue($value),
            'referral_reward' => __('ui.notifications.free_course_message'),
            'achievement_earned' => $this->translateAchievementMessage($value),
            'course_purchased' => preg_match('/^You have successfully enrolled in (.+)$/u', $value, $matches)
                ? __('You have successfully enrolled in :course', ['course' => $matches[1]])
                : $this->translateExactValue($value),
            'certificate_issued' => preg_match('/^Congratulations! Your certificate for (.+) is ready\.$/u', $value, $matches)
                ? __('Congratulations! Your certificate for :course is ready.', ['course' => $matches[1]])
                : $this->translateExactValue($value),
            'referral_success' => preg_match('/^(.+) purchased a course using your referral code\. You earned a discount!$/u', $value, $matches)
                ? __(':user purchased a course using your referral code. You earned a discount!', ['user' => $matches[1]])
                : $this->translateExactValue($value),
            'reply_marked_solution' => preg_match('/^Your reply was marked as the solution in: (.+)$/u', $value, $matches)
                ? __('Your reply was marked as the solution in: :topic', ['topic' => $matches[1]])
                : $this->translateExactValue($value),
            'comment_reply' => preg_match('/^Admin replied to your comment on (.+)$/u', $value, $matches)
                ? __('Admin replied to your comment on :lesson', ['lesson' => $matches[1]])
                : $this->translateExactValue($value),
            'live_session_scheduled' => $this->translateExactValue($value),
            'live_session_reminder' => $this->translateExactValue($value),
            'promo_announcement' => $this->translateExactValue($value),
            default => $this->translateExactValue($value),
        };
    }

    private function translateAchievementMessage(string $value): string
    {
        if (preg_match("/^You earned '(.+)': (.+)$/u", $value, $matches)) {
            return __("You earned ':achievement': :description", [
                'achievement' => $matches[1],
                'description' => $matches[2],
            ]);
        }

        if (preg_match("/^You earned the '(.+)' achievement!$/u", $value, $matches)) {
            return __("You earned the ':achievement' achievement!", [
                'achievement' => $matches[1],
            ]);
        }

        return $this->translateExactValue($value);
    }

    private function translateExactValue(string $value): string
    {
        $translated = __($value);

        return $translated !== $value ? $translated : $value;
    }
}
