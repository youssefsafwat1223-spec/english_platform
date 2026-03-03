<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order_index',
        'is_active',
        'total_topics',
        'total_posts',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function topics()
    {
        return $this->hasMany(ForumTopic::class, 'category_id');
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

    /**
     * Get category URL
     */
    public function getUrlAttribute()
    {
        return route('forum.category', $this->slug);
    }

    /**
     * Get latest topic
     */
    public function getLatestTopicAttribute()
    {
        return $this->topics()->latest()->first();
    }

    // ==================== METHODS ====================

    /**
     * Increment topic count
     */
    public function incrementTopics()
    {
        $this->increment('total_topics');
    }

    /**
     * Decrement topic count
     */
    public function decrementTopics()
    {
        $this->decrement('total_topics');
    }

    /**
     * Increment post count
     */
    public function incrementPosts()
    {
        $this->increment('total_posts');
    }

    /**
     * Update counts
     */
    public function updateCounts()
    {
        $this->total_topics = $this->topics()->count();
        $this->total_posts = $this->topics()->sum('reply_count') + $this->total_topics;
        $this->save();
    }
}