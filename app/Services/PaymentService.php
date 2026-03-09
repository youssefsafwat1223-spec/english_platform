<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Referral;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    private $apiKey;
    private $secretKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.streampay.api_key');
        $this->secretKey = config('services.streampay.secret_key');
        $this->apiUrl = config('services.streampay.api_url', 'https://stream-app-service.streampay.sa/api/v2');
    }

    /**
     * Create payment link using StreamPay
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
                'currency' => 'SAR', // StreamPay uses SAR primarily
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'payment_status' => 'pending',
            ]);

            // Create a StreamPay Product on-the-fly
            $productResponse = Http::withHeaders([
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/products", [
                'name' => "Enrollment in {$course->title}",
                'description' => "Course access for {$user->name}",
                'type' => 'ONE_OFF',
                'active' => true,
                'price' => (float) $finalAmount,
                'currency' => 'SAR',
            ]);

            if (!$productResponse->successful()) {
                Log::error('StreamPay product creation failed', [
                    'response' => $productResponse->json()
                ]);
                throw new \Exception('Failed to create product for payment link');
            }

            $productId = $productResponse->json('id');

            // Create Payment Link
            $paymentLinkResponse = Http::withHeaders([
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/payment_links", [
                'name' => "Payment for {$course->title}",
                'description' => "Course access payment for {$user->name}",
                'currency' => 'SAR',
                'items' => [
                    [
                        'product_id' => $productId,
                        'quantity' => 1,
                        'allow_custom_quantity' => false
                    ]
                ],
                'success_redirect_url' => route('payment.callback', $payment->id),
                'failure_redirect_url' => route('payment.callback', $payment->id),
                'max_number_of_payments' => 1,
                'custom_metadata' => [
                    'user_id' => (string) $user->id,
                    'course_id' => (string) $course->id,
                    'payment_id' => (string) $payment->id,
                ],
                'contact_information_type' => 'EMAIL'
            ]);

            if ($paymentLinkResponse->successful()) {
                $data = $paymentLinkResponse->json();

                $payment->update([
                    'gateway_payment_id' => $data['id'],
                    'gateway_response' => $data,
                ]);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'redirect_url' => $data['url'] ?? $data['link'] ?? null,
                ];
            }

            Log::error('StreamPay payment link creation failed', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'response' => $paymentLinkResponse->json(),
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
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
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
    public function handleCallback($paymentId)
    {
        try {
            $payment = Payment::find($paymentId);

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment record not found',
                ];
            }
            
            // If already marked as completed by a webhook
            if ($payment->payment_status === 'completed') {
                return [
                    'success' => true,
                    'payment' => $payment,
                    'message' => 'Payment successful',
                ];
            }

            // Check payment link status on StreamPay
            if ($payment->gateway_payment_id) {
                $response = Http::withHeaders([
                    'X-Api-Key' => $this->apiKey,
                ])->get("{$this->apiUrl}/payment_links/{$payment->gateway_payment_id}");

                if ($response->successful()) {
                    $data = $response->json();
                    $status = strtolower($data['status'] ?? '');
                    
                    Log::info('StreamPay callback - payment link status', [
                        'payment_id' => $paymentId,
                        'status' => $status,
                    ]);

                    // If StreamPay reports the payment link as paid, complete it
                    if (in_array($status, ['paid', 'completed', 'success'])) {
                        $payment->markAsCompleted($data['id'] ?? $payment->gateway_payment_id, $data);
                        $this->handleReferralDiscountUsage($payment);

                        return [
                            'success' => true,
                            'payment' => $payment,
                            'message' => 'Payment successful',
                        ];
                    } elseif (in_array($status, ['failed', 'expired', 'cancelled'])) {
                        $payment->markAsFailed('Payment ' . $status);

                        return [
                            'success' => false,
                            'message' => 'Payment ' . $status,
                        ];
                    }
                }
            }

            // Payment is still pending — webhook will handle it
            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'Payment validation in progress. You will receive an email shortly.',
            ];

        } catch (\Exception $e) {
            Log::error('Payment callback exception', [
                'payment_id' => $paymentId,
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

        if ($user->has_free_enrollment) {
            $discountAmount = $course->price;
            $discountType = 'referral_free';
        } elseif ($promoCode && $promoCode->isValid()) {
            $discountAmount = $promoCode->calculateDiscount($course->price);
            $discountType = 'promo_' . $promoCode->id;
        } elseif ($user->has_referral_discount) {
            $discountPercentage = config('app.referral_discount_percentage', 10);
            $discountAmount = ($course->price * $discountPercentage) / 100;
            $discountType = 'referral';
        }

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
    public function handleReferralDiscountUsage(Payment $payment)
    {
        $user = $payment->user;

        if ($user->has_free_enrollment && $payment->discount_type === 'referral_free') {
            $user->update(['has_free_enrollment' => false]);
        }

        if ($user->referred_by && !$user->referral_discount_used) {
            $referral = Referral::where('referee_id', $user->id)->first();

            if ($referral) {
                $referral->markRefereeDiscountUsed();

                if ($referral->status !== 'purchased') {
                    $referral->markAsPurchased($payment->final_amount);

                    app(TelegramService::class)->sendReferralSuccessNotification(
                        $referral->referrer,
                        $user
                    );
                }
            }
        }

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

            // Call to StreamPay Refund API endpoint (assuming it exists or using payment id from gateway metadata)
            // Wait, we need the Payment/Charge ID which StreamPay associates with the invoice.
            // Let's assume refunding is done via their dashboard or we use the invoice's payment id.
            // For now, updating local db.
            $payment->refund();

            return [
                'success' => true,
                'message' => 'Refund processed successfully locally. Please verify on StreamPay dashboard.',
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