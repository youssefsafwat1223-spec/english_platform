<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'parent_id',
        'comment_text',
        'is_admin_reply',
        'like_count',
    ];

    protected function casts(): array
    {
        return [
            'is_admin_reply' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent comment (for nested replies)
     */
    public function parent()
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    /**
     * Child replies
     */
    public function replies()
    {
        return $this->hasMany(LessonComment::class, 'parent_id')->latest();
    }

    // ==================== SCOPES ====================

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeAdminReplies($query)
    {
        return $query->where('is_admin_reply', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if this is a reply
     */
    public function getIsReplyAttribute()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get total replies count
     */
    public function getTotalRepliesAttribute()
    {
        return $this->replies()->count();
    }

    // ==================== METHODS ====================

    /**
     * Increment like count
     */
    public function incrementLikes()
    {
        $this->increment('like_count');
    }

    /**
     * Send notification when admin replies
     */
    public function notifyUser()
    {
        if ($this->is_admin_reply && $this->parent) {
            Notification::create([
                'user_id' => $this->parent->user_id,
                'notification_type' => 'comment_reply',
                'title' => __('New Reply on Your Comment'),
                'message' => __('Admin replied to your comment on :lesson', [
                    'lesson' => $this->lesson->title,
                ]),
                'action_url' => route('student.lessons.show', [
                    'course' => $this->lesson->course_id,
                    'lesson' => $this->lesson_id,
                ]) . '#comment-' . $this->id,
            ]);
        }
    }
}
