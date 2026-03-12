<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CourseLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'order_index',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($level) {
            if (empty($level->slug)) {
                $level->slug = Str::slug($level->title);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order_index');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    // ==================== ACCESSORS ====================

    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    // ==================== METHODS ====================

    /**
     * Check if this level is unlocked for a given user.
     * Level is unlocked if:
     *  - It's the first level (lowest order_index) in the course, OR
     *  - The user has completed ALL lessons in the previous level.
     */
    public function isUnlockedFor(User $user): bool
    {
        // Get the previous level (the one with the closest lower order_index)
        $previousLevel = self::where('course_id', $this->course_id)
            ->where('is_active', true)
            ->where('order_index', '<', $this->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        // If there's no previous level, this is the first level — always unlocked
        if (!$previousLevel) {
            return true;
        }

        // Check if the user completed ALL lessons in the previous level
        $previousLessons = $previousLevel->lessons;

        if ($previousLessons->isEmpty()) {
            // Previous level has no lessons, consider it completed
            return true;
        }

        foreach ($previousLessons as $lesson) {
            if (!$lesson->isCompletedBy($user)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the completion percentage for a user in this level
     */
    public function getCompletionPercentageFor(User $user): int
    {
        $lessons = $this->lessons;

        if ($lessons->isEmpty()) {
            return 100;
        }

        $completedCount = 0;
        foreach ($lessons as $lesson) {
            if ($lesson->isCompletedBy($user)) {
                $completedCount++;
            }
        }

        return (int) round(($completedCount / $lessons->count()) * 100);
    }
}
