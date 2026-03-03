<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsHistory extends Model
{
    use HasFactory;

    protected $table = 'points_history';

    protected $fillable = [
        'user_id',
        'points_earned',
        'activity_type',
        'activity_id',
        'description',
    ];

    public $timestamps = true;

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

    public function scopeByActivity($query, $activityType)
    {
        return $query->where('activity_type', $activityType);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get icon based on activity type
     */
    public function getIconAttribute()
    {
        return match($this->activity_type) {
            'lesson_complete' => 'book-open',
            'quiz_pass' => 'award',
            'daily_question' => 'help-circle',
            'pronunciation_practice' => 'mic',
            'course_complete' => 'graduation-cap',
            default => 'star',
        };
    }
}
