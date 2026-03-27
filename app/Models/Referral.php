<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referee_id',
        'referral_code',
        'clicked_at',
        'registered_at',
        'first_purchase_at',
        'first_purchase_amount',
        'referrer_discount_earned',
        'referrer_discount_used',
        'referrer_discount_used_at',
        'referee_discount_used',
        'referee_discount_used_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'first_purchase_amount' => 'decimal:2',
            'clicked_at' => 'datetime',
            'registered_at' => 'datetime',
            'first_purchase_at' => 'datetime',
            'referrer_discount_earned' => 'boolean',
            'referrer_discount_used' => 'boolean',
            'referrer_discount_used_at' => 'datetime',
            'referee_discount_used' => 'boolean',
            'referee_discount_used_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User who referred (the one who shared the link)
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * User who was referred (the one who signed up)
     */
    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    // ==================== SCOPES ====================

    public function scopeClicked($query)
    {
        return $query->where('status', 'clicked');
    }

    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    public function scopePurchased($query)
    {
        return $query->where('status', 'purchased');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'purchased');
    }

    // ==================== ACCESSORS ====================

    /**
     * Check if referral resulted in purchase
     */
    public function getIsSuccessfulAttribute()
    {
        return $this->status === 'purchased';
    }

    /**
     * Check if referrer earned discount
     */
    public function getReferrerEarnedDiscountAttribute()
    {
        return $this->referrer_discount_earned && !$this->referrer_discount_used;
    }

    /**
     * Check if referee has available discount
     */
    public function getRefereeHasDiscountAttribute()
    {
        return !$this->referee_discount_used;
    }

    // ==================== METHODS ====================

    /**
     * Mark as clicked
     */
    public function markAsClicked()
    {
        if (is_null($this->clicked_at)) {
            $this->update([
                'clicked_at' => now(),
                'status' => 'clicked',
            ]);
        }
    }

    /**
     * Mark as registered
     */
    public function markAsRegistered()
    {
        $this->update([
            'registered_at' => now(),
            'status' => 'registered',
        ]);
    }

    /**
     * Mark as purchased (referral successful)
     */
    public function markAsPurchased($amount)
    {
        $this->update([
            'first_purchase_at' => now(),
            'first_purchase_amount' => $amount,
            'status' => 'purchased',
            'referrer_discount_earned' => true,
        ]);

        // Give referrer a discount (expires in 30 days)
        $this->referrer->update([
            'referral_discount_expires_at' => now()->addDays(30),
        ]);

        // Send notification to referrer
        Notification::create([
            'user_id' => $this->referrer_id,
            'notification_type' => 'referral_success',
            'title' => __('Referral Successful!'),
            'message' => __(':user purchased a course using your referral code. You earned a discount!', [
                'user' => $this->referee->name,
            ]),
            'action_url' => route('student.referrals.index'),
        ]);
    }

    /**
     * Mark referrer discount as used
     */
    public function markReferrerDiscountUsed()
    {
        $this->update([
            'referrer_discount_used' => true,
            'referrer_discount_used_at' => now(),
        ]);

        $this->referrer->update([
            'referral_discount_used' => true,
        ]);
    }

    /**
     * Mark referee discount as used
     */
    public function markRefereeDiscountUsed()
    {
        $this->update([
            'referee_discount_used' => true,
            'referee_discount_used_at' => now(),
        ]);

        $this->referee->update([
            'referral_discount_used' => true,
        ]);
    }

    /**
     * Calculate conversion rate for referrer
     */
    public static function getConversionRate($referrerId)
    {
        $total = self::where('referrer_id', $referrerId)->count();

        if ($total === 0) {
            return 0;
        }

        $successful = self::where('referrer_id', $referrerId)
            ->where('status', 'purchased')
            ->count();

        return round(($successful / $total) * 100, 2);
    }
}
