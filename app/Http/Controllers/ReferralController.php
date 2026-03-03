<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    private $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function track($referralCode, Request $request)
    {
        $referrer = $this->referralService->trackClick($referralCode, $request->ip());

        if (!$referrer) {
            return redirect()->route('home');
        }

        // Show referral landing page or redirect to register
        return redirect()->route('register')
            ->with('referral_info', [
                'referrer_name' => $referrer->name,
                'discount' => config('app.referral_discount_percentage', 10),
            ]);
    }
}