<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;

class ReferralService
{
    private const REFERRAL_MAX_USES = 5;
    /**
     * Track referral click
     */
    public function trackClick($referralCode, $ipAddress = null)
    {
        $referrer = User::where('referral_code', $referralCode)->first();

        if (!$referrer) {
            return null;
        }

        // Store referral code in session for later use
        session(['referral_code' => $referralCode]);

        return $referrer;
    }

    /**
     * Create referral on user registration
     */
    public function createReferral(User $referee)
    {
        $referralCode = session('referral_code');

        if (!$referralCode) {
            return null;
        }


        $referrer = User::where('referral_code', $referralCode)->first();

        if (!$referrer || $referrer->id === $referee->id) {
            return null;
        }

        // Create referral record
        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referral_code' => $referralCode,
            'clicked_at' => now(),
            'registered_at' => now(),
            'status' => 'registered',
        ]);

        // Update referee with referrer info
        $referee->update([
            'referred_by' => $referrer->id,
        ]);

        // Give referee a discount (expires in 30 days)
        $this->giveRefereeDiscount($referee);

        // Check if referrer reached 5 referrals → give free enrollment
        $this->checkAndGrantFreeEnrollment($referrer);

        // Clear session
        session()->forget('referral_code');

        return $referral;
    }

    /**
     * Give discount to referee
     */
    private function giveRefereeDiscount(User $referee)
    {
        $referee->update([
            'referral_discount_used' => false,
            'referral_discount_expires_at' => now()->addDays(30),
        ]);
    }

    /**
     * Check if referrer reached 5 referrals and grant free enrollment
     */
    private function checkAndGrantFreeEnrollment(User $referrer)
    {
        $totalRegistrations = Referral::where('referrer_id', $referrer->id)
            ->where('status', '!=', 'clicked')
            ->count();

        // Grant free enrollment at every 5 referrals
        if ($totalRegistrations >= 5 && !$referrer->has_free_enrollment) {
            $referrer->update(['has_free_enrollment' => true]);

            // Send notification
            \App\Models\Notification::create([
                'user_id' => $referrer->id,
                'notification_type' => 'referral_reward',
                'title' => '🎉 مبروك! حصلت على اشتراك مجاني!',
                'message' => 'لقد أحلت 5 أشخاص بنجاح! يمكنك الآن الاشتراك في أي كورس مجاناً.',
                'action_url' => route('student.courses.index'),
            ]);
        }
    }

    /**
     * Apply referral code during checkout
     */
    public function applyReferralCode(User $referee, string $referralCode): array
    {
        $code = strtoupper(trim($referralCode));

        if ($code === '') {
            return [
                'success' => false,
                'message' => 'Referral code is required.',
            ];
        }

        $referrer = User::where('referral_code', $code)->first();

        if (!$referrer) {
            return [
                'success' => false,
                'message' => 'Referral code not found.',
            ];
        }

        if ($referrer->id === $referee->id) {
            return [
                'success' => false,
                'message' => 'You cannot use your own referral code.',
            ];
        }

        if ($referee->referral_discount_used) {
            return [
                'success' => false,
                'message' => 'Referral discount already used.',
            ];
        }

        if ($referee->referred_by && $referee->referred_by !== $referrer->id) {
            return [
                'success' => false,
                'message' => 'You already used a different referral code.',
            ];
        }

        if ($this->hasReachedReferralLimit($referrer->id)) {
            return [
                'success' => false,
                'message' => 'This referral code has reached its usage limit.',
            ];
        }

        $referral = Referral::where('referrer_id', $referrer->id)
            ->where('referee_id', $referee->id)
            ->first();

        if ($referral && $referral->status === 'purchased') {
            return [
                'success' => false,
                'message' => 'Referral discount already used.',
            ];
        }

        if (!$referral) {
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referee_id' => $referee->id,
                'referral_code' => $code,
                'clicked_at' => now(),
                'registered_at' => now(),
                'status' => 'registered',
            ]);
        } elseif ($referral->status === 'clicked') {
            $referral->markAsRegistered();
        }

        $referee->update([
            'referred_by' => $referrer->id,
        ]);

        $this->giveRefereeDiscount($referee);

        return [
            'success' => true,
            'referrer' => $referrer,
            'referral' => $referral,
        ];
    }




    private function hasReachedReferralLimit(int $referrerId): bool
    {
        $maxUses = (int) config('app.referral_max_uses', self::REFERRAL_MAX_USES);

        $used = Referral::where('referrer_id', $referrerId)
            ->where('status', 'purchased')
            ->count();

        return $used >= $maxUses;
    }

    /**
     * Get referral statistics for user
     */
    public function getStatistics(User $user)
    {
        $referrals = Referral::where('referrer_id', $user->id)->get();

        return [
            'total_clicks' => $referrals->count(),
            'total_registrations' => $referrals->where('status', '!=', 'clicked')->count(),
            'total_purchases' => $referrals->where('status', 'purchased')->count(),
            'conversion_rate' => Referral::getConversionRate($user->id),
            'total_earnings' => $referrals->where('status', 'purchased')->sum('first_purchase_amount') * 0.1,
            'available_discounts' => $referrals->where('referrer_discount_earned', true)
                ->where('referrer_discount_used', false)
                ->count(),
        ];
    }

    /**
     * Get top referrers
     */
    public function getTopReferrers($limit = 10)
    {
        return User::withCount([
            'referrals as successful_referrals_count' => function ($query) {
                $query->where('status', 'purchased');
            }
        ])
        ->having('successful_referrals_count', '>', 0)
        ->orderBy('successful_referrals_count', 'desc')
        ->limit($limit)
        ->get();
    }

    /**
     * Check if user can use referral discount
     */
    public function canUseDiscount(User $user)
    {
        if ($user->referral_discount_used) {
            return false;
        }

        if (!$user->referral_discount_expires_at) {
            return false;
        }

        return now()->lessThan($user->referral_discount_expires_at);
    }

    /**
     * Calculate referral discount amount
     */
    public function calculateDiscountAmount($price)
    {
        $percentage = config('app.referral_discount_percentage', 10);
        return ($price * $percentage) / 100;
    }
}
