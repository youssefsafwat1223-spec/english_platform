<?php

namespace App\Models;

use App\Models\Concerns\RepairsMojibakeAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForumTopic extends Model
{
    use HasFactory, SoftDeletes, RepairsMojibakeAttributes;

    protected array $repairableTextAttributes = [
        'title',
        'content',
    ];

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'content',
        'is_pinned',
        'is_locked',
        'view_count',
        'reply_count',
        'last_reply_at',
        'last_reply_by',
        'is_reported',
        'report_count',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_reported' => 'boolean',
            'last_reply_at' => 'datetime',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($topic) {
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->title);
            }
        });

        static::created(function ($topic) {
            $topic->category->incrementTopics();
            $topic->category->incrementPosts();
        });

        static::deleted(function ($topic) {
            $topic->category->decrementTopics();
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastReplyUser()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'topic_id')->latest();
    }

    public function reports()
    {
        return $this->morphMany(ForumReport::class, 'reportable');
    }

    // ==================== SCOPES ====================

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_reply_at', 'desc');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get topic URL
     */
    public function getUrlAttribute()
    {
        return route('forum.topic', [$this->category->slug, $this->slug]);
    }

    /**
     * Get excerpt
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    // ==================== METHODS ====================

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Increment reply count
     */
    public function incrementReplies()
    {
        $this->increment('reply_count');
        $this->category->incrementPosts();
    }

    /**
     * Update last reply info
     */
    public function updateLastReply(ForumReply $reply)
    {
        $this->update([
            'last_reply_at' => $reply->created_at,
            'last_reply_by' => $reply->user_id,
        ]);
    }

    /**
     * Pin topic
     */
    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    /**
     * Unpin topic
     */
    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }

    /**
     * Lock topic
     */
    public function lock()
    {
        $this->update(['is_locked' => true]);
    }

    /**
     * Unlock topic
     */
    public function unlock()
    {
        $this->update(['is_locked' => false]);
    }

    /**
     * Check if user can reply
     */
    public function canUserReply(User $user)
    {
        return !$this->is_locked;
    }
}
