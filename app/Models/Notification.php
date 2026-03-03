<?php

namespace App\Models;

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

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

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

    // ==================== METHODS ====================

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark as sent to telegram
     */
    public function markAsSentToTelegram($messageId = null)
    {
        $this->update([
            'sent_to_telegram' => true,
            'telegram_sent_at' => now(),
            'telegram_message_id' => $messageId,
        ]);
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        return match($this->notification_type) {
            'course_purchased' => 'shopping-cart',
            'quiz_result' => 'clipboard-check',
            'comment_reply' => 'comment',
            'referral_success' => 'gift',
            'certificate_issued' => 'award',
            'daily_question' => 'help-circle',
            'achievement_earned' => 'award',
            'reply_marked_solution' => 'check-circle',
            'battle_started' => 'zap',
            default => 'bell',
        };
    }

    /**
     * Get color based on notification type
     */
    public function getColorAttribute()
    {
        return match($this->notification_type) {
            'course_purchased' => 'success',
            'quiz_result' => 'info',
            'comment_reply' => 'primary',
            'referral_success' => 'warning',
            'certificate_issued' => 'success',
            'achievement_earned' => 'success',
            'reply_marked_solution' => 'info',
            'battle_started' => 'warning',
            default => 'secondary',
        };
    }
}
