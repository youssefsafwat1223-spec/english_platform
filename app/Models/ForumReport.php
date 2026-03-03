<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the reportable model (topic or reply)
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->whereIn('status', ['reviewed', 'resolved', 'dismissed']);
    }

    // ==================== METHODS ====================

    /**
     * Mark as reviewed
     */
    public function markAsReviewed(User $reviewer)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Mark as resolved
     */
    public function resolve(User $reviewer)
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Dismiss report
     */
    public function dismiss(User $reviewer)
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }
}