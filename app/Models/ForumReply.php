<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topic_id',
        'user_id',
        'content',
        'is_solution',
        'like_count',
        'is_reported',
        'report_count',
    ];

    protected function casts(): array
    {
        return [
            'is_solution' => 'boolean',
            'is_reported' => 'boolean',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->topic->incrementReplies();
            $reply->topic->updateLastReply($reply);
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function topic()
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ForumReplyLike::class, 'reply_id');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'forum_reply_likes', 'reply_id', 'user_id')
            ->withTimestamps();
    }

    public function reports()
    {
        return $this->morphMany(ForumReport::class, 'reportable');
    }

    // ==================== METHODS ====================

    /**
     * Check if user liked this reply
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Toggle like
     */
    public function toggleLike(User $user)
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->where('user_id', $user->id)->delete();
            $this->decrement('like_count');
            return false;
        } else {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('like_count');
            return true;
        }
    }

    /**
     * Mark as solution
     */
    public function markAsSolution()
    {
        // Unmark other solutions in same topic
        $this->topic->replies()->update(['is_solution' => false]);

        $this->update(['is_solution' => true]);

        // Notify reply author
        Notification::create([
            'user_id' => $this->user_id,
            'notification_type' => 'reply_marked_solution',
            'title' => 'Your Reply Marked as Solution',
            'message' => "Your reply was marked as the solution in: {$this->topic->title}",
            'action_url' => route('student.forum.topic', [
                'category' => $this->topic->category->slug,
                'topic' => $this->topic->slug,
            ]),
        ]);
    }
}
