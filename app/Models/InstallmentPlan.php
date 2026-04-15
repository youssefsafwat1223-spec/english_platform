<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPlan extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_id',
        'total_amount',
        'installment_amount',
        'installments_count',
        'installments_paid',
        'next_due_at',
        'status',
        'streampay_subscription_id',
        'streampay_product_id',
    ];

    protected function casts(): array
    {
        return [
            'total_amount'       => 'decimal:2',
            'installment_amount' => 'decimal:2',
            'next_due_at'        => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ==================== ACCESSORS ====================

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsSuspendedAttribute(): bool
    {
        return $this->status === 'suspended';
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getRemainingInstallmentsAttribute(): int
    {
        return max(0, $this->installments_count - $this->installments_paid);
    }

    public function getNextInstallmentNumberAttribute(): int
    {
        return $this->installments_paid + 1;
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeDueForReminder($query)
    {
        // Plans where next_due_at is within the next 3 days and still active
        return $query->whereIn('status', ['active', 'suspended'])
            ->whereNotNull('next_due_at')
            ->where('next_due_at', '<=', now()->addDays(3))
            ->where('next_due_at', '>', now());
    }

    public function scopeOverdue($query)
    {
        // Plans where next_due_at has passed by more than the grace period (7 days)
        return $query->whereIn('status', ['active'])
            ->whereNotNull('next_due_at')
            ->where('next_due_at', '<', now()->subDays(7));
    }

    // ==================== METHODS ====================

    public function markInstallmentPaid(): void
    {
        $this->increment('installments_paid');
        $this->refresh();

        if ($this->installments_paid >= $this->installments_count) {
            $this->update([
                'status'      => 'completed',
                'next_due_at' => null,
            ]);
        } else {
            $this->update([
                'status'      => 'active',
                'next_due_at' => now()->addDays(30),
            ]);
        }
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);

        if ($this->enrollment) {
            $this->enrollment->update(['access_suspended_at' => now()]);
        }
    }

    public function restore(): void
    {
        $this->update(['status' => 'active']);

        if ($this->enrollment) {
            $this->enrollment->update(['access_suspended_at' => null]);
        }
    }

    public function isOverdue(): bool
    {
        return $this->next_due_at && $this->next_due_at->isPast()
            && $this->next_due_at->diffInDays(now()) > 7;
    }

    public function isDueForReminder(): bool
    {
        return $this->next_due_at
            && $this->next_due_at->isFuture()
            && $this->next_due_at->diffInDays(now()) <= 3;
    }
}
