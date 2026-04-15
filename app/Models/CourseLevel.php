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
        'has_listening_exercise',
        'has_writing_exercise',
        'has_speaking_exercise',
    ];

    protected function casts(): array
    {
        return [
            'is_active'              => 'boolean',
            'has_listening_exercise' => 'boolean',
            'has_writing_exercise'   => 'boolean',
            'has_speaking_exercise'  => 'boolean',
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

    public function listeningExercise()
    {
        return $this->hasOne(ListeningExercise::class);
    }

    public function writingExercise()
    {
        return $this->hasOne(WritingExercise::class);
    }

    public function speakingExercise()
    {
        return $this->hasOne(PronunciationExercise::class);
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
        // All levels are now open — no sequential locking
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
