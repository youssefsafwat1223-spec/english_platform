<?php

namespace App\Models;

use App\Models\Concerns\RepairsMojibakeAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes, RepairsMojibakeAttributes;

    protected array $repairableTextAttributes = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'quiz_type',
        'description',
        'total_questions',
        'duration_minutes',
        'passing_score',
        'is_active',
        'allow_retake',
        'show_results_immediately',
        'enable_audio',
        'audio_auto_play',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allow_retake' => 'boolean',
            'show_results_immediately' => 'boolean',
            'enable_audio' => 'boolean',
            'audio_auto_play' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
            ->withTimestamps()
            ->withPivot('order_index')
            ->orderBy('quiz_questions.order_index');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLessonQuizzes($query)
    {
        return $query->where('quiz_type', 'lesson');
    }

    public function scopeFinalExams($query)
    {
        return $query->where('quiz_type', 'final_exam');
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if quiz is final exam
     */
    public function getIsFinalExamAttribute()
    {
        return $this->quiz_type === 'final_exam';
    }

    /**
     * Get duration in seconds
     */
    public function getDurationSecondsAttribute()
    {
        return $this->duration_minutes * 60;
    }

    // ==================== METHODS ====================

    /**
     * Get user's best attempt
     */
    public function getBestAttempt(User $user)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('score', 'desc')
            ->first();
    }

    /**
     * Get user's latest attempt
     */
    public function getLatestAttempt(User $user)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    /**
     * Get user's attempt count
     */
    public function getAttemptCount(User $user)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->count();
    }

    /**
     * Check if user can take quiz
     */
    public function canUserTake(User $user)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->allow_retake) {
            return true;
        }

        return $this->getAttemptCount($user) === 0;
    }

    /**
     * Check if user passed
     */
    public function hasUserPassed(User $user)
    {
        $bestAttempt = $this->getBestAttempt($user);

        if (!$bestAttempt) {
            return false;
        }

        return $bestAttempt->passed;
    }

    /**
     * Calculate average score
     */
    public function getAverageScore()
    {
        return $this->attempts()
            ->where('passed', true)
            ->avg('score') ?? 0;
    }

    /**
     * Get pass rate
     */
    public function getPassRate()
    {
        $totalAttempts = $this->attempts()->count();

        if ($totalAttempts === 0) {
            return 0;
        }

        $passedAttempts = $this->attempts()->where('passed', true)->count();

        return ($passedAttempts / $totalAttempts) * 100;
    }
}
