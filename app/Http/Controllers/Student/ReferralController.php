<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ReferralService;

class ReferralController extends Controller
{
    private $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function index()
    {
        $user = auth()->user();

        // Get referral statistics
        $stats = $this->referralService->getStatistics($user);

        // Get referral history
        $referrals = $user->referrals()
            ->with('referee')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Check if user has available discount
        $hasDiscount = $user->has_referral_discount;

        // Progress toward one free course (5 registrations needed)
        $referralProgress = \App\Models\Referral::where('referrer_id', $user->id)
            ->where('status', '!=', 'clicked')
            ->count();
        $hasFreeEnrollment = $user->has_free_enrollment;

        return view('student.referrals.index', compact('user', 'stats', 'referrals', 'hasDiscount', 'referralProgress', 'hasFreeEnrollment'));
    }

    public function howItWorks()
    {
        $discountPercentage = config('app.referral_discount_percentage', 10);

        return view('student.referrals.how-it-works', compact('discountPercentage'));
    }
}
