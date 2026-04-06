<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'payment_id',
        'price_paid',
        'discount_amount',
        'discount_type',
        'discount_code',
        'progress_percentage',
        'completed_lessons',
        'total_lessons',
        'started_at',
        'completed_at',
        'expires_at',
        'certificate_issued_at',
        'certificate_id',
        'last_accessed_at',
    ];

    protected function casts(): array
    {
        return [
            'price_paid' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'progress_percentage' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'expires_at' => 'datetime',
            'certificate_issued_at' => 'datetime',
            'last_accessed_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if enrollment is completed
     */
    public function getIsCompletedAttribute()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Check if certificate is issued
     */
    public function getHasCertificateAttribute()
    {
        return !is_null($this->certificate_id);
    }

    /**
     * Get next lesson to study
     */
    public function getNextLessonAttribute()
    {
        $completedLessonIds = $this->lessonProgress()
            ->where('is_completed', true)
            ->pluck('lesson_id');

        return $this->course->lessons()
            ->whereNotIn('id', $completedLessonIds)
            ->orderBy('order_index')
            ->first();
    }

    // ==================== METHODS ====================

    /**
     * Update progress
     */
    public function updateProgress()
    {
        $totalLessons = (int) $this->course->lessons()
            ->whereNotNull('title')
            ->whereRaw("TRIM(title) <> ''")
            ->selectRaw("COUNT(DISTINCT LOWER(TRIM(title))) as aggregate")
            ->value('aggregate');

        $completedLessons = (int) $this->lessonProgress()
            ->where('is_completed', true)
            ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
            ->where('lessons.course_id', $this->course_id)
            ->whereNotNull('lessons.title')
            ->whereRaw("TRIM(lessons.title) <> ''")
            ->selectRaw("COUNT(DISTINCT LOWER(TRIM(lessons.title))) as aggregate")
            ->value('aggregate');

        if ($totalLessons <= 0) {
            $totalLessons = (int) $this->course->lessons()->count();
            $completedLessons = (int) $this->lessonProgress()
                ->where('is_completed', true)
                ->count();
        }

        $this->completed_lessons = $completedLessons;
        $this->total_lessons = $totalLessons;
        $this->progress_percentage = $totalLessons > 0
            ? ($this->completed_lessons / $totalLessons) * 100
            : 0;

        if ($this->progress_percentage >= 100) {
            if (is_null($this->completed_at)) {
                $this->completed_at = now();
            }
        }

        $this->save();

        if ($this->progress_percentage >= 100 && !$this->certificate_id) {
            app(\App\Services\CertificateService::class)->generateCertificate($this);
        }
    }

    /**
     * Mark as started
     */
    public function markAsStarted()
    {
        if (is_null($this->started_at)) {
            $this->update(['started_at' => now()]);
        }
    }

    /**
     * Update last access time
     */
    public function updateLastAccess()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Get time since last access (in days)
     */
    public function getDaysSinceLastAccess()
    {
        if (!$this->last_accessed_at) {
            return null;
        }

        return now()->diffInDays($this->last_accessed_at);
    }
}
