<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id',
        'amount',
        'currency',
        'discount_amount',
        'final_amount',
        'payment_method',
        'payment_status',
        'tap_charge_id',
        'tap_response',
        'error_message',
        'paid_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'tap_response' => 'array',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
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

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    // ==================== SCOPES ====================

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('payment_status', 'refunded');
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if payment is completed
     */
    public function getIsCompletedAttribute()
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function getIsPendingAttribute()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment failed
     */
    public function getIsFailedAttribute()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if payment is refunded
     */
    public function getIsRefundedAttribute()
    {
        return $this->payment_status === 'refunded';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->final_amount, 2);
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->amount <= 0) {
            return 0;
        }

        return round(($this->discount_amount / $this->amount) * 100);
    }

    // ==================== METHODS ====================

    /**
     * Mark payment as completed
     */
    public function markAsCompleted($tapChargeId = null, $tapResponse = null)
    {
        $this->update([
            'payment_status' => 'completed',
            'paid_at' => now(),
            'tap_charge_id' => $tapChargeId,
            'tap_response' => $tapResponse,
        ]);

        // Create enrollment
        $this->createEnrollment();
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'payment_status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Process refund
     */
    public function refund()
    {
        $this->update([
            'payment_status' => 'refunded',
            'refunded_at' => now(),
        ]);

        // Cancel enrollment if exists
        if ($this->enrollment) {
            $this->enrollment->delete();
        }
    }

    /**
     * Create enrollment after successful payment
     */
    public function createEnrollment()
    {
        $totalLessons = $this->course->lessons()->count();

        $enrollment = Enrollment::create([
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'payment_id' => $this->id,
            'price_paid' => $this->final_amount,
            'discount_amount' => $this->discount_amount,
            'discount_type' => $this->getDiscountType(),
            'total_lessons' => $totalLessons,
        ]);

        // Increment course student count
        $this->course->incrementStudents();

        // Create notification
        Notification::create([
            'user_id' => $this->user_id,
            'notification_type' => 'course_purchased',
            'title' => 'Course Purchased Successfully',
            'message' => "You have successfully enrolled in {$this->course->title}",
            'action_url' => route('student.courses.show', $this->course),
        ]);

        return $enrollment;
    }

    /**
     * Determine discount type from transaction
     */
    private function getDiscountType()
    {
        if ($this->discount_amount <= 0) {
            return null;
        }

        // Check if user used referral discount
        if ($this->user->referred_by && !$this->user->referral_discount_used) {
            return 'referral';
        }

        return 'promotion';
    }
}
