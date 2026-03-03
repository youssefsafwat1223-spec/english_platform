<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'scheduled_for',
        'sent_at',
        'answered_at',
        'user_answer',
        'is_correct',
        'points_earned',
        'telegram_message_id',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'date',
            'sent_at' => 'datetime',
            'answered_at' => 'datetime',
            'is_correct' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // ==================== SCOPES ====================

    public function scopeScheduledForToday($query)
    {
        return $query->whereDate('scheduled_for', today());
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('answered_at');
    }

    public function scopeAnswered($query)
    {
        return $query->whereNotNull('answered_at');
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if question was answered
     */
    public function getIsAnsweredAttribute()
    {
        return !is_null($this->answered_at);
    }

    /**
     * Check if sent
     */
    public function getIsSentAttribute()
    {
        return !is_null($this->sent_at);
    }

    // ==================== METHODS ====================

    /**
     * Mark as sent
     */
    public function markAsSent($telegramMessageId = null)
    {
        $this->update([
            'sent_at' => now(),
            'telegram_message_id' => $telegramMessageId,
        ]);
    }

    /**
     * Record answer
     */
    public function recordAnswer($answer)
    {
        $isCorrect = $this->question->isCorrect($answer);

        $this->update([
            'answered_at' => now(),
            'user_answer' => strtoupper($answer),
            'is_correct' => $isCorrect,
            'points_earned' => $isCorrect ? config('app.points_per_daily_question', 5) : 0,
        ]);

        // Award points
        if ($isCorrect) {
            $this->user->addPoints(
                $this->points_earned,
                'daily_question',
                $this->question_id,
                "Daily question answered correctly"
            );

            // Update streak
            $this->updateStreak();
        }

        return $isCorrect;
    }

    /**
     * Update user's streak
     */
    private function updateStreak()
    {
        $yesterday = today()->subDay();

        $answeredYesterday = DailyQuestion::where('user_id', $this->user_id)
            ->whereDate('scheduled_for', $yesterday)
            ->where('is_correct', true)
            ->exists();

        if ($answeredYesterday) {
            $this->user->increment('current_streak');
        } else {
            $this->user->update(['current_streak' => 1]);
        }

        // Update longest streak
        if ($this->user->current_streak > $this->user->longest_streak) {
            $this->user->update(['longest_streak' => $this->user->current_streak]);
        }
    }
}