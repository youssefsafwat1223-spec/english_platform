<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'course_id',
        'course_level_id',
        'title',
        'slug',
        'description',
        'video_url',
        'video_duration',
        'text_content',
        'order_index',
        'is_free',
        'has_quiz',
        'has_pronunciation_exercise',
        'vdocipher_video_id',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'has_quiz' => 'boolean',
            'has_pronunciation_exercise' => 'boolean',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Course this lesson belongs to
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Level this lesson belongs to
     */
    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

    /**
     * Attachments for this lesson
     */
    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class)->orderBy('order_index');
    }

    /**
     * Audio version of this lesson
     */
    public function audio()
    {
        return $this->hasOne(LessonAudio::class);
    }

    /**
     * Questions for this lesson
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Quiz for this lesson
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Pronunciation exercise for this lesson
     */
    public function pronunciationExercise()
    {
        return $this->hasOne(PronunciationExercise::class);
    }

    /**
     * Progress records for this lesson
     */
    public function progressRecords()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Comments on this lesson
     */
    public function comments()
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id')->latest();
    }

    /**
     * Notes for this lesson
     */
    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    // ==================== SCOPES ====================

    /**
     * Free lessons only
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Lessons with quiz
     */
    public function scopeWithQuiz($query)
    {
        return $query->where('has_quiz', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get lesson URL
     */
    public function getUrlAttribute()
    {
        return route('lessons.show', [$this->course->slug, $this->slug]);
    }

    /**
     * Get formatted video duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->video_duration) {
            return null;
        }

        $minutes = floor($this->video_duration / 60);
        $seconds = $this->video_duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get embed URL for supported video providers (YouTube/Vimeo)
     */
    public function getVideoEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        $url = $this->video_url;

        // YouTube (watch, short, embed, shorts)
        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([A-Za-z0-9_-]{6,})~i', $url, $matches)) {
            return "https://www.youtube.com/embed/{$matches[1]}";
        }

        // Vimeo
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}";
        }

        // Google Drive (file/d/ID/view, open?id=ID, uc?id=ID)
        if (preg_match('~drive\.google\.com/file/d/([A-Za-z0-9_-]+)~i', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }
        if (preg_match('~drive\.google\.com/(?:open|uc)\?.*id=([A-Za-z0-9_-]+)~i', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        return null;
    }

    /**
     * Get next lesson
     */
    public function getNextLessonAttribute()
    {
        return $this->course->lessons()
            ->where('order_index', '>', $this->order_index)
            ->orderBy('order_index')
            ->first();
    }

    /**
     * Get previous lesson
     */
    public function getPreviousLessonAttribute()
    {
        return $this->course->lessons()
            ->where('order_index', '<', $this->order_index)
            ->orderBy('order_index', 'desc')
            ->first();
    }

    // ==================== METHODS ====================

    /**
     * Check if this lesson uses VdoCipher for video
     */
    public function isVdoCipherVideo(): bool
    {
        return !empty($this->vdocipher_video_id);
    }

    /**
     * Check if user has completed this lesson
     */
    public function isCompletedBy(User $user)
    {
        return $this->progressRecords()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Get user's progress for this lesson
     */
    public function getProgressFor(User $user)
    {
        return $this->progressRecords()
            ->where('user_id', $user->id)
            ->first();
    }
}
