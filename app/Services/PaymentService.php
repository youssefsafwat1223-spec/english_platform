<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PaymentService
{
    private string $apiKey;
    private string $secretKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = trim((string) config('services.streampay.api_key'));
        $this->secretKey = trim((string) config('services.streampay.secret_key'));
        $this->apiUrl = rtrim((string) config('services.streampay.api_url', 'https://stream-app-service.streampay.sa/api/v2'), '/');
    }

    /**
     * Create payment link using StreamPay.
     */
    public function createCharge(User $user, Course $course, array $discountData, ?PromoCode $promoCode = null, ?string $discountCode = null): array
    {
        $payment = null;

        try {
            $amount = (float) $course->price;
            $discountAmount = (float) ($discountData['discount_amount'] ?? 0);
            $finalAmount = max(1, (float) ($discountData['final_amount'] ?? ($amount - $discountAmount)));

            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'promo_code_id' => $promoCode?->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                'amount' => $amount,
                'currency' => 'SAR',
                'discount_amount' => $discountAmount,
                'discount_type' => $discountData['discount_type'] ?? null,
                'discount_code' => $discountCode ?? $promoCode?->code,
                'final_amount' => $finalAmount,
                'payment_status' => 'pending',
            ]);

            $productResponse = $this->streamPayRequest('post', '/products', [
                'name' => "Enrollment in {$course->title}",
                'description' => "Course access for {$user->name}",
                'type' => 'ONE_OFF',
                'active' => true,
                'price' => $finalAmount,
                'currency' => 'SAR',
            ]);

            if (!$productResponse->successful()) {
                $message = $this->extractGatewayErrorMessage(
                    $productResponse,
                    'Payment gateway rejected the course product request. Please try again.'
                );

                Log::error('StreamPay product creation failed', [
                    'payment_id' => $payment->id,
                    'status' => $productResponse->status(),
                    'response' => $productResponse->json(),
                    'body' => $productResponse->body(),
                ]);

                $payment->markAsFailed($message);

                return [
                    'success' => false,
                    'message' => $message,
                ];
            }

            $productId = $productResponse->json('id');
            $callbackUrl = $this->buildCallbackUrl($payment);

            $paymentLinkResponse = $this->streamPayRequest('post', '/payment_links', [
                'name' => "Payment for {$course->title}",
                'description' => "Course access payment for {$user->name}",
                'currency' => 'SAR',
                'items' => [
                    [
                        'product_id' => $productId,
                        'quantity' => 1,
                        'allow_custom_quantity' => false,
                    ],
                ],
                'success_redirect_url' => $callbackUrl,
                'failure_redirect_url' => $callbackUrl,
                'max_number_of_payments' => 1,
                'custom_metadata' => [
                    'user_id' => (string) $user->id,
                    'course_id' => (string) $course->id,
                    'payment_id' => (string) $payment->id,
                ],
                'contact_information_type' => 'EMAIL',
            ]);

            if (!$paymentLinkResponse->successful()) {
                $message = $this->extractGatewayErrorMessage(
                    $paymentLinkResponse,
                    'Payment gateway rejected the checkout link request. Please try again.'
                );

                Log::error('StreamPay payment link creation failed', [
                    'payment_id' => $payment->id,
                    'status' => $paymentLinkResponse->status(),
                    'response' => $paymentLinkResponse->json(),
                    'body' => $paymentLinkResponse->body(),
                ]);

                $payment->markAsFailed($message);

                return [
                    'success' => false,
                    'message' => $message,
                ];
            }

            $data = $paymentLinkResponse->json();
            $paymentLinkId = $data['id'] ?? null;

            $payment->update([
                'gateway_payment_id' => $paymentLinkId,
                'gateway_response' => array_merge([
                    'payment_link_id' => $paymentLinkId,
                ], $data),
            ]);

            $redirectUrl = $data['url'] ?? $data['link'] ?? $data['checkout_url'] ?? $data['payment_url'] ?? $data['redirect_url'] ?? null;

            if (!$redirectUrl) {
                Log::error('StreamPay payment link response did not include a redirect URL', [
                    'payment_id' => $payment->id,
                    'payment_link_id' => $paymentLinkId,
                    'response' => $data,
                ]);

                $payment->markAsFailed('StreamPay did not return a checkout URL');

                return [
                    'success' => false,
                    'message' => 'Payment gateway did not return a checkout link. Please try again.',
                ];
            }

            Log::info('StreamPay payment link created', [
                'payment_id' => $payment->id,
                'payment_link_id' => $paymentLinkId,
                'redirect_url' => $redirectUrl,
            ]);

            return [
                'success' => true,
                'payment' => $payment->fresh(),
                'redirect_url' => $redirectUrl,
            ];
        } catch (ConnectionException $e) {
            $message = $this->paymentGatewayConnectionMessage($e);

            if ($payment?->is_pending) {
                $payment->markAsFailed($message);
            }

            Log::error('Payment gateway connection failed', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_id' => $payment?->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $message,
            ];
        } catch (\Throwable $e) {
            $message = 'An unexpected payment error occurred. Please try again.';

            if ($payment?->is_pending) {
                $payment->markAsFailed($message);
            }

            Log::error('Payment creation exception', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_id' => $payment?->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $message,
            ];
        }
    }

    /**
     * Handle payment callback after Stream redirects back to the site.
     */
    public function handleCallback(
        int $paymentId,
        ?string $streamPaymentId = null,
        ?string $invoiceId = null,
        ?string $paymentLinkId = null
    ): array {
        try {
            $payment = Payment::find($paymentId);

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment record not found',
                ];
            }

            if ($payment->payment_status === 'completed') {
                return [
                    'success' => true,
                    'payment' => $payment,
                    'message' => 'Payment successful',
                ];
            }

            $streamPaymentId ??= $payment->getStreamPaymentId();
            $paymentLinkId ??= $payment->getPaymentLinkId();

            if ($streamPaymentId) {
                $paymentResponse = $this->streamPayRequest('get', "/payments/{$streamPaymentId}");

                if ($paymentResponse->successful()) {
                    $gatewayPayment = $paymentResponse->json();
                    $status = $this->normalizeGatewayStatus($gatewayPayment);

                    Log::info('StreamPay callback payment status received', [
                        'payment_id' => $payment->id,
                        'stream_payment_id' => $streamPaymentId,
                        'status' => $status,
                    ]);

                    if (!$this->paymentMatchesExpectedOrder($payment, $gatewayPayment)) {
                        return [
                            'success' => false,
                            'message' => 'Payment verification failed',
                        ];
                    }

                    $gatewayPayload = $this->enrichGatewayPayload($payment, $gatewayPayment, [
                        'payment_id' => $streamPaymentId,
                        'invoice_id' => $invoiceId,
                        'payment_link_id' => $paymentLinkId,
                    ]);

                    if ($this->isSuccessfulGatewayStatus($status)) {
                        $payment = $this->finalizeSuccessfulPayment($payment, $streamPaymentId, $gatewayPayload);

                        return [
                            'success' => true,
                            'payment' => $payment,
                            'message' => 'Payment successful',
                        ];
                    }

                    if ($this->isRefundedGatewayStatus($status)) {
                        $payment = $this->syncRefundedPayment($payment, $gatewayPayload);

                        return [
                            'success' => false,
                            'payment' => $payment,
                            'message' => 'Payment refunded',
                        ];
                    }

                    if ($this->isFailedGatewayStatus($status)) {
                        $payment->markAsFailed('Payment ' . strtolower($status));

                        return [
                            'success' => false,
                            'payment' => $payment->fresh(),
                            'message' => 'Payment ' . strtolower($status),
                        ];
                    }
                } else {
                    Log::warning('StreamPay payment status lookup failed', [
                        'payment_id' => $payment->id,
                        'stream_payment_id' => $streamPaymentId,
                        'response' => $paymentResponse->json(),
                    ]);
                }
            }

            if ($paymentLinkId) {
                $paymentLinkResponse = $this->streamPayRequest('get', "/payment_links/{$paymentLinkId}");

                if ($paymentLinkResponse->successful()) {
                    $paymentLinkData = $paymentLinkResponse->json();
                    $paymentLinkStatus = strtoupper((string) ($paymentLinkData['status'] ?? ''));

                    Log::info('StreamPay callback payment link status received', [
                        'payment_id' => $payment->id,
                        'payment_link_id' => $paymentLinkId,
                        'status' => $paymentLinkStatus,
                    ]);

                    if (in_array($paymentLinkStatus, ['INACTIVE', 'ACTIVE'], true)) {
                        return [
                            'success' => true,
                            'payment' => $payment,
                            'message' => 'Payment validation in progress. You will receive an email shortly.',
                        ];
                    }
                }
            }

            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'Payment validation in progress. You will receive an email shortly.',
            ];
        } catch (\Throwable $e) {
            Log::error('Payment callback exception', [
                'payment_id' => $paymentId,
                'stream_payment_id' => $streamPaymentId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred processing your payment',
            ];
        }
    }

    /**
     * Calculate discount for user, considering referrals and promo codes.
     */
    public function calculateDiscount(User $user, Course $course, ?PromoCode $promoCode = null): array
    {
        $discountAmount = 0;
        $discountType = null;

        if ($user->has_free_enrollment) {
            $discountAmount = $course->price;
            $discountType = 'referral_free';
        } elseif ($promoCode && $promoCode->isValid()) {
            $discountAmount = $promoCode->calculateDiscount($course->price);
            $discountType = 'promo';
        } elseif ($referralDiscountType = $this->resolveReferralDiscountType($user)) {
            $discountPercentage = config('app.referral_discount_percentage', 10);
            $discountAmount = ($course->price * $discountPercentage) / 100;
            $discountType = $referralDiscountType;
        }

        return [
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'final_amount' => max(0, $course->price - $discountAmount),
        ];
    }

    public function finalizeSuccessfulPayment(Payment $payment, ?string $gatewayPaymentId = null, ?array $gatewayResponse = null): Payment
    {
        return DB::transaction(function () use ($payment, $gatewayPaymentId, $gatewayResponse) {
            $payment = Payment::query()
                ->with(['user', 'course', 'promoCode'])
                ->lockForUpdate()
                ->findOrFail($payment->id);

            $payment->markAsCompleted($gatewayPaymentId, $gatewayResponse);
            $this->applySuccessfulPaymentEffects($payment);

            return $payment->fresh(['user', 'course', 'promoCode', 'enrollment']);
        });
    }

    public function syncRefundedPayment(Payment $payment, ?array $gatewayResponse = null): Payment
    {
        return DB::transaction(function () use ($payment, $gatewayResponse) {
            $payment = Payment::query()
                ->with(['course', 'enrollment'])
                ->lockForUpdate()
                ->findOrFail($payment->id);

            return $payment->refund($gatewayResponse);
        });
    }

    public function applySuccessfulPaymentEffects(Payment $payment): void
    {
        if ($payment->benefits_processed_at) {
            return;
        }

        $payment->loadMissing(['user', 'promoCode']);

        if ($payment->discount_type === 'promo' && $payment->promoCode) {
            $payment->promoCode->increment('used_count');
        }

        $this->handleReferralDiscountUsage($payment);

        $payment->forceFill([
            'benefits_processed_at' => now(),
        ])->save();
    }

    public function handleReferralDiscountUsage(Payment $payment): void
    {
        $user = $payment->user;

        if ($payment->discount_type === 'referral_free' && $user->has_free_enrollment) {
            $user->update(['has_free_enrollment' => false]);

            return;
        }

        if ($payment->discount_type === 'referee_referral' && $user->referred_by && !$user->referral_discount_used) {
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

        if ($referral && $payment->discount_type === 'referrer_referral') {
            $referral->markReferrerDiscountUsed();
        }
    }

    public function refund(
        Payment $payment,
        string $reason = 'REQUESTED_BY_CUSTOMER',
        ?string $note = null,
        bool $allowRefundMultipleRelatedPayments = false
    ): array {
        if (!$payment->is_completed) {
            return [
                'success' => false,
                'message' => 'Cannot refund incomplete payment',
            ];
        }

        if ($payment->is_refunded) {
            return [
                'success' => false,
                'message' => 'Payment is already refunded',
            ];
        }

        $streamPaymentId = $payment->getStreamPaymentId();

        if (!$streamPaymentId) {
            return [
                'success' => false,
                'message' => 'Cannot refund this payment because the Stream payment ID is missing.',
            ];
        }

        $response = $this->streamPayRequest('post', "/payments/{$streamPaymentId}/refund", [
            'refund_reason' => $reason,
            'refund_note' => $note,
            'allow_refund_multiple_related_payments' => $allowRefundMultipleRelatedPayments,
        ]);

        if (!$response->successful()) {
            Log::error('StreamPay refund failed', [
                'payment_id' => $payment->id,
                'stream_payment_id' => $streamPaymentId,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $this->extractGatewayErrorMessage($response),
            ];
        }

        $gatewayResponse = $this->enrichGatewayPayload($payment, $response->json(), [
            'payment_id' => $streamPaymentId,
            'payment_link_id' => $payment->getPaymentLinkId(),
        ]);

        $this->syncRefundedPayment($payment, $gatewayResponse);

        return [
            'success' => true,
            'message' => 'Refund processed successfully.',
        ];
    }

    public function buildCallbackUrl(Payment $payment): string
    {
        return URL::temporarySignedRoute(
            'payment.callback',
            now()->addDays(30),
            ['payment' => $payment->id]
        );
    }

    private function resolveReferralDiscountType(User $user): ?string
    {
        if (!$user->has_referral_discount) {
            return null;
        }

        $hasUnusedRefereeDiscount = Referral::where('referee_id', $user->id)
            ->where('referee_discount_used', false)
            ->exists();

        if ($hasUnusedRefereeDiscount) {
            return 'referee_referral';
        }

        $hasUnusedReferrerDiscount = Referral::where('referrer_id', $user->id)
            ->where('referrer_discount_earned', true)
            ->where('referrer_discount_used', false)
            ->exists();

        if ($hasUnusedReferrerDiscount) {
            return 'referrer_referral';
        }

        if ($user->referred_by) {
            return 'referee_referral';
        }

        return null;
    }

    private function streamPayRequest(string $method, string $path, array $payload = []): Response
    {
        $request = Http::withHeaders($this->streamPayHeaders())
            ->acceptJson()
            ->connectTimeout(10)
            ->timeout(25)
            ->retry(2, 500, function (\Throwable $exception) {
                return $exception instanceof ConnectionException;
            }, throw: false);

        return match (strtolower($method)) {
            'get' => $request->get($this->apiUrl . $path),
            'post' => $request->post($this->apiUrl . $path, $payload),
            'patch' => $request->patch($this->apiUrl . $path, $payload),
            default => throw new \InvalidArgumentException("Unsupported StreamPay method [{$method}]"),
        };
    }

    private function streamPayHeaders(): array
    {
        return [
            'X-Api-Key' => $this->getStreamPayAuthToken(),
            'Content-Type' => 'application/json',
        ];
    }

    private function getStreamPayAuthToken(): string
    {
        if ($this->looksLikeEncodedApiToken($this->apiKey)) {
            return $this->apiKey;
        }

        if ($this->apiKey !== '' && $this->secretKey !== '') {
            return base64_encode($this->apiKey . ':' . $this->secretKey);
        }

        return $this->apiKey;
    }

    private function looksLikeEncodedApiToken(string $value): bool
    {
        $decoded = base64_decode($value, true);

        return $decoded !== false && str_contains($decoded, ':');
    }

    private function normalizeGatewayStatus(array $payload): string
    {
        return strtoupper((string) ($payload['current_status'] ?? $payload['status'] ?? ''));
    }

    private function isSuccessfulGatewayStatus(string $status): bool
    {
        return in_array($status, ['SUCCEEDED', 'SETTLED'], true);
    }

    private function isFailedGatewayStatus(string $status): bool
    {
        return in_array($status, ['FAILED', 'FAILED_INITIATION', 'CANCELED', 'EXPIRED'], true);
    }

    private function isRefundedGatewayStatus(string $status): bool
    {
        return $status === 'REFUNDED';
    }

    private function paymentMatchesExpectedOrder(Payment $payment, array $gatewayPayment): bool
    {
        $gatewayAmount = (float) ($gatewayPayment['amount'] ?? 0);
        $gatewayCurrency = strtoupper((string) ($gatewayPayment['currency'] ?? ''));

        $amountMatches = round($gatewayAmount, 2) === round((float) $payment->final_amount, 2);
        $currencyMatches = $gatewayCurrency === '' || $gatewayCurrency === strtoupper($payment->currency);

        if ($amountMatches && $currencyMatches) {
            return true;
        }

        Log::warning('StreamPay payment verification mismatch', [
            'payment_id' => $payment->id,
            'expected_amount' => (float) $payment->final_amount,
            'gateway_amount' => $gatewayAmount,
            'expected_currency' => strtoupper($payment->currency),
            'gateway_currency' => $gatewayCurrency,
        ]);

        return false;
    }

    private function enrichGatewayPayload(Payment $payment, array $gatewayPayload, array $extra = []): array
    {
        return array_replace_recursive(
            is_array($payment->gateway_response) ? $payment->gateway_response : [],
            $gatewayPayload,
            array_filter($extra, static fn ($value) => filled($value))
        );
    }

    private function extractGatewayErrorMessage(Response $response, string $fallback = 'StreamPay rejected the request.'): string
    {
        $json = $response->json();

        if (is_string($json['message'] ?? null) && $json['message'] !== '') {
            return $json['message'];
        }

        if (is_string($json['detail'] ?? null) && $json['detail'] !== '') {
            return $json['detail'];
        }

        if (is_array($json['detail'] ?? null) && !empty($json['detail'][0]['msg'])) {
            return $json['detail'][0]['msg'];
        }

        return $fallback;
    }

    private function paymentGatewayConnectionMessage(ConnectionException $exception): string
    {
        $message = strtolower($exception->getMessage());

        if (str_contains($message, 'timed out') || str_contains($message, 'timeout')) {
            return 'The payment gateway took too long to respond. Please try again in a moment.';
        }

        return 'Unable to reach the payment gateway right now. Please try again in a moment.';
    }
}
