<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Referral;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    private $secretKey;
    private $apiUrl;

    public function __construct()
    {
        $this->secretKey = config('services.tap.secret_key');
        $this->apiUrl = 'https://api.tap.company/v2';
    }

    /**
     * Create payment charge
     */
    public function createCharge(User $user, Course $course, $discountAmount = 0)
    {
        try {
            // Calculate final amount
            $amount = $course->price;
            $finalAmount = $amount - $discountAmount;

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                'amount' => $amount,
                'currency' => 'USD',
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'payment_status' => 'pending',
            ]);

            // Create Tap charge
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/charges", [
                'amount' => $finalAmount,
                'currency' => 'USD',
                'customer' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => [
                        'country_code' => '20',
                        'number' => $user->phone,
                    ],
                ],
                'source' => [
                    'id' => 'src_all',
                ],
                'redirect' => [
                    'url' => route('payment.callback', $payment->id),
                ],
                'reference' => [
                    'transaction' => $payment->transaction_id,
                    'order' => "ORDER-{$payment->id}",
                ],
                'description' => "Enrollment in {$course->title}",
                'metadata' => [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'payment_id' => $payment->id,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $payment->update([
                    'tap_charge_id' => $data['id'],
                    'tap_response' => $data,
                ]);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'redirect_url' => $data['transaction']['url'],
                ];
            }

            Log::error('Tap charge creation failed', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'response' => $response->body(),
            ]);

            $payment->markAsFailed('Charge creation failed');

            return [
                'success' => false,
                'message' => 'Failed to create payment. Please try again.',
            ];

        } catch (\Exception $e) {
            Log::error('Payment creation exception', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ];
        }
    }

    /**
     * Handle payment callback
     */
    public function handleCallback($chargeId)
    {
        try {
            // Retrieve charge from Tap
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
            ])->get("{$this->apiUrl}/charges/{$chargeId}");

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to verify payment',
                ];
            }

            $data = $response->json();
            $status = $data['status'];

            // Find payment record
            $payment = Payment::where('tap_charge_id', $chargeId)->first();

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment record not found',
                ];
            }

            if ($status === 'CAPTURED') {
                // Payment successful
                $payment->markAsCompleted($chargeId, $data);

                // Handle referral discount usage
                $this->handleReferralDiscountUsage($payment);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'message' => 'Payment successful',
                ];
            } elseif ($status === 'FAILED') {
                $payment->markAsFailed($data['response']['message'] ?? 'Payment failed');

                return [
                    'success' => false,
                    'message' => 'Payment failed',
                ];
            }

            return [
                'success' => false,
                'message' => "Payment status: {$status}",
            ];

        } catch (\Exception $e) {
            Log::error('Payment callback exception', [
                'charge_id' => $chargeId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred processing your payment',
            ];
        }
    }

    /**
     * Calculate discount for user, considering referrals and promo codes
     */
    public function calculateDiscount(User $user, Course $course, ?PromoCode $promoCode = null)
    {
        $discountAmount = 0;
        $discountType = null;

        // Check if a valid promo code was provided (promo codes override referral discounts)
        if ($promoCode && $promoCode->isValid()) {
            $discountAmount = $promoCode->calculateDiscount($course->price);
            $discountType = 'promo_' . $promoCode->id;
        }
        // Fallback to check if user has referral discount
        elseif ($user->has_referral_discount) {
            $discountPercentage = config('app.referral_discount_percentage', 10);
            $discountAmount = ($course->price * $discountPercentage) / 100;
            $discountType = 'referral';
        }

        // Prevent negative final amount
        $finalAmount = max(0, $course->price - $discountAmount);

        return [
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'final_amount' => $finalAmount,
        ];
    }

    /**
     * Handle referral discount usage
     */
    private function handleReferralDiscountUsage(Payment $payment)
    {
        $user = $payment->user;

        // Mark referee discount as used
        if ($user->referred_by && !$user->referral_discount_used) {
            $referral = Referral::where('referee_id', $user->id)->first();

            if ($referral) {
                $referral->markRefereeDiscountUsed();

                // If this is first purchase, mark referral as successful
                if ($referral->status !== 'purchased') {
                    $referral->markAsPurchased($payment->final_amount);

                    // Send notification to referrer
                    app(TelegramService::class)->sendReferralSuccessNotification(
                        $referral->referrer,
                        $user
                    );
                }
            }
        }

        // Or mark referrer discount as used
        $referral = Referral::where('referrer_id', $user->id)
            ->where('referrer_discount_earned', true)
            ->where('referrer_discount_used', false)
            ->first();

        if ($referral && $payment->discount_type === 'referral') {
            $referral->markReferrerDiscountUsed();
        }
    }

    /**
     * Process refund
     */
    public function refund(Payment $payment)
    {
        try {
            if (!$payment->is_completed) {
                return [
                    'success' => false,
                    'message' => 'Cannot refund incomplete payment',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/refunds", [
                'charge_id' => $payment->tap_charge_id,
                'amount' => $payment->final_amount,
                'currency' => $payment->currency,
                'reason' => 'requested_by_customer',
            ]);

            if ($response->successful()) {
                $payment->refund();

                return [
                    'success' => true,
                    'message' => 'Refund processed successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Refund request failed',
            ];

        } catch (\Exception $e) {
            Log::error('Refund exception', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred processing refund',
            ];
        }
    }
}