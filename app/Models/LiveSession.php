<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LiveSession extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_LIVE = 'live';
    public const STATUS_ENDED = 'ended';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'zoom_join_url',
        'starts_at',
        'ends_at',
        'status',
        'banner_enabled',
        'notifications_enabled',
        'recording_url',
        'published_notification_sent_at',
        'notified_24h_at',
        'notified_1h_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'banner_enabled' => 'boolean',
            'notifications_enabled' => 'boolean',
            'published_notification_sent_at' => 'datetime',
            'notified_24h_at' => 'datetime',
            'notified_1h_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (LiveSession $liveSession) {
            if (blank($liveSession->slug)) {
                $liveSession->slug = static::generateUniqueSlug($liveSession->title);
            }
        });

        static::updating(function (LiveSession $liveSession) {
            if ($liveSession->isDirty('title') && !$liveSession->isDirty('slug')) {
                $liveSession->slug = static::generateUniqueSlug($liveSession->title, $liveSession->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_live_session')->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeVisibleToStudent($query, User $user)
    {
        return $query
            ->whereNotIn('status', [self::STATUS_DRAFT, self::STATUS_CANCELLED])
            ->whereHas('courses.enrollments', function ($enrollmentQuery) use ($user) {
                $enrollmentQuery->where('user_id', $user->id);
            });
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return self::STATUS_CANCELLED;
        }

        if ($this->status === self::STATUS_DRAFT) {
            return self::STATUS_DRAFT;
        }

        $now = now();

        if ($this->ends_at && $this->ends_at->isPast()) {
            return self::STATUS_ENDED;
        }

        if ($this->starts_at && $this->starts_at->isPast() && $this->ends_at && $this->ends_at->isFuture()) {
            return self::STATUS_LIVE;
        }

        return self::STATUS_SCHEDULED;
    }

    public function getPrimaryCourseAttribute(): ?Course
    {
        if ($this->relationLoaded('courses')) {
            return $this->courses->first();
        }

        return $this->courses()->first();
    }

    public function canBeViewedBy(User $user): bool
    {
        return $this->courses()->whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }

    public function shouldShowJoinButton(): bool
    {
        return in_array($this->display_status, [self::STATUS_SCHEDULED, self::STATUS_LIVE], true)
            && filled($this->zoom_join_url);
    }

    public function shouldShowRecording(): bool
    {
        return $this->display_status === self::STATUS_ENDED && filled($this->recording_url);
    }

    public function startsWithinHours(int $hours): bool
    {
        $now = now();

        return $this->starts_at->between($now, (clone $now)->addHours($hours));
    }

    private static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug !== '' ? $baseSlug : 'live-session';
        $original = $slug;
        $counter = 2;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
