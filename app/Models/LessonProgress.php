<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'enrollment_id',
        'is_completed',
        'completed_at',
        'time_spent',
        'last_position',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // ==================== METHODS ====================

    /**
     * Mark lesson as completed
     */
    public function markAsCompleted(): bool
    {
        if ($this->is_completed) {
            return false;
        }

        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Award points to user
        $this->user->addPoints(
            config('app.points_per_lesson', 10),
            'lesson_complete',
            $this->lesson_id,
            "Completed: {$this->lesson->title}"
        );

        // Update enrollment progress
        $this->enrollment->updateProgress();

        return true;
    }

    /**
     * Update video position
     */
    public function updatePosition($position)
    {
        $this->update(['last_position' => $position]);
    }

    /**
     * Add time spent
     */
    public function addTimeSpent($seconds)
    {
        $this->increment('time_spent', $seconds);
    }

    /**
     * Get formatted time spent
     */
    public function getFormattedTimeSpentAttribute()
    {
        if (!$this->time_spent) {
            return '0 min';
        }

        $minutes = floor($this->time_spent / 60);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$remainingMinutes}m";
        }

        return "{$minutes} min";
    }
}
