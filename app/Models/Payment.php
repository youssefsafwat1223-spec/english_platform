<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentInvoiceMail;
use App\Mail\NewEnrollmentAlert;
use App\Models\PromoCode;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'promo_code_id',
        'installment_plan_id',
        'installment_number',
        'transaction_id',
        'amount',
        'currency',
        'discount_amount',
        'discount_type',
        'discount_code',
        'final_amount',
        'payment_method',
        'payment_status',
        'gateway_payment_id',
        'gateway_response',
        'error_message',
        'paid_at',
        'benefits_processed_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'benefits_processed_at' => 'datetime',
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

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
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
    public function markAsCompleted($gatewayPaymentId = null, $gatewayResponse = null)
    {
        $gatewayResponse = $this->mergeGatewayResponse($gatewayResponse, $gatewayPaymentId);

        if ($this->payment_status === 'completed') {
            $updates = [];

            if ($gatewayPaymentId && $this->gateway_payment_id !== $gatewayPaymentId) {
                $updates['gateway_payment_id'] = $gatewayPaymentId;
            }

            if ($gatewayResponse) {
                $updates['gateway_response'] = $gatewayResponse;
            }

            if (!$this->paid_at) {
                $updates['paid_at'] = now();
            }

            if (!empty($updates)) {
                $this->update($updates);
            }

            return $this->refresh();
        }

        $this->update([
            'payment_status'     => 'completed',
            'paid_at'            => now(),
            'gateway_payment_id' => $gatewayPaymentId ?? $this->gateway_payment_id,
            'gateway_response'   => $gatewayResponse ?: $this->gateway_response,
        ]);

        // Regular (full) payment — create enrollment immediately
        // Note: installment payments are created directly in the webhook handler
        // (InstallmentService::handleSubscriptionInvoicePaid) and never go through here.
        $this->createEnrollment();

        // Send invoice email to student
        try {
            Mail::to($this->user->email)->send(new PaymentInvoiceMail($this));
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email', [
                'payment_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $this->refresh();
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
    public function refund(?array $gatewayResponse = null)
    {
        if ($this->payment_status === 'refunded') {
            return $this->refresh();
        }

        $gatewayResponse = $this->mergeGatewayResponse($gatewayResponse, $this->gateway_payment_id);
        $refundedAt = data_get($gatewayResponse, 'refunded_at');

        $this->update([
            'payment_status' => 'refunded',
            'refunded_at' => $refundedAt ? Carbon::parse($refundedAt) : now(),
            'gateway_response' => $gatewayResponse ?: $this->gateway_response,
        ]);

        // Cancel enrollment if exists
        if ($this->enrollment) {
            $this->enrollment->delete();
            $this->course?->decrementStudents();
        }

        return $this->refresh();
    }

    /**
     * Create enrollment after successful payment
     */
    public function createEnrollment()
    {
        if ($this->enrollment) {
            return $this->enrollment;
        }

        $totalLessons = (int) $this->course->lessons()
            ->whereNotNull('title')
            ->whereRaw("TRIM(title) <> ''")
            ->reorder()
            ->selectRaw("COUNT(DISTINCT LOWER(TRIM(title))) as aggregate")
            ->value('aggregate');

        if ($totalLessons <= 0) {
            $totalLessons = (int) $this->course->lessons()->count();
        }

        $expiresAt = null;
        if ($this->course?->estimated_duration_weeks) {
            $expiresAt = now()->addDays($this->course->estimated_duration_weeks * 7);
        }

        $enrollment = Enrollment::create([
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'payment_id' => $this->id,
            'price_paid' => $this->final_amount,
            'discount_amount' => $this->discount_amount,
            'discount_type' => $this->getEnrollmentDiscountType(),
            'discount_code' => $this->discount_code ?? $this->promoCode?->code,
            'total_lessons' => $totalLessons,
            'started_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        // Notify admin of new enrollment
        try {
            Mail::to('baraashhri@gmail.com')->send(new NewEnrollmentAlert($enrollment));
        } catch (\Throwable $e) {
            \Log::error('NewEnrollmentAlert failed: ' . $e->getMessage());
        }

        // Increment course student count
        $this->course->incrementStudents();

        // Create notification
        Notification::create([
            'user_id' => $this->user_id,
            'notification_type' => 'course_purchased',
            'title' => __('Course Purchased Successfully'),
            'message' => __('You have successfully enrolled in :course', [
                'course' => $this->course->title,
            ]),
            'action_url' => route('student.courses.show', $this->course),
        ]);

        return $enrollment;
    }

    /**
     * Determine discount type from transaction
     */
    private function getEnrollmentDiscountType()
    {
        if ($this->discount_type) {
            return match ($this->discount_type) {
                'promo' => 'coupon',
                'referee_referral', 'referrer_referral', 'referral_free' => 'referral',
                default => 'promotion',
            };
        }

        if ($this->discount_amount <= 0) {
            return null;
        }

        if ($this->promo_code_id) {
            return 'coupon';
        }

        if ($this->user->referred_by && !$this->user->referral_discount_used) {
            return 'referral';
        }

        return 'promotion';
    }

    public function getStreamPaymentId(): ?string
    {
        return data_get($this->gateway_response, 'payment_id')
            ?? data_get($this->gateway_response, 'payment.id')
            ?? ($this->payment_status !== 'pending' ? $this->gateway_payment_id : null);
    }

    public function getPaymentLinkId(): ?string
    {
        return data_get($this->gateway_response, 'payment_link_id')
            ?? data_get($this->gateway_response, 'payment_link.id')
            ?? ($this->payment_status === 'pending' ? $this->gateway_payment_id : null);
    }

    private function mergeGatewayResponse(?array $gatewayResponse, ?string $gatewayPaymentId): ?array
    {
        $existing = is_array($this->gateway_response) ? $this->gateway_response : [];

        if (!$gatewayResponse && !$existing) {
            return null;
        }

        $merged = array_replace_recursive($existing, $gatewayResponse ?? []);

        if ($gatewayPaymentId) {
            $merged['payment_id'] ??= $gatewayPaymentId;
        }

        if (!isset($merged['payment_link_id']) && $this->payment_status === 'pending' && $this->gateway_payment_id) {
            $merged['payment_link_id'] = $this->gateway_payment_id;
        }

        return $merged;
    }
}
