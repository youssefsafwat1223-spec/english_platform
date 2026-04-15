<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use App\Services\InstallmentService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StreamPayWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly InstallmentService $installmentService,
    ) {
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $secret  = trim((string) config('services.streampay.secret_key'));

        if ($secret === '') {
            Log::critical('StreamPay webhook rejected because the secret key is not configured.');
            return response()->json(['error' => 'Webhook secret is not configured'], 503);
        }

        if (!$this->hasValidWebhookSignature($request, $payload, $secret)) {
            Log::warning('StreamPay webhook rejected because the signature is invalid', [
                'ip'      => $request->ip(),
                'headers' => [
                    'X-Webhook-Signature'  => $request->header('X-Webhook-Signature'),
                    'X-Signature'          => $request->header('X-Signature'),
                    'X-StreamPay-Signature'=> $request->header('X-StreamPay-Signature'),
                ],
            ]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        Log::info('StreamPay webhook received', [
            'event_type'  => $request->input('event_type') ?? $request->input('event') ?? $request->input('type'),
            'entity_type' => $request->input('entity_type'),
            'entity_id'   => $request->input('entity_id'),
            'status'      => $request->input('status'),
            'ip'          => $request->ip(),
        ]);

        try {
            $eventType = strtoupper((string) (
                $request->input('event_type') ?? $request->input('event') ?? $request->input('type')
            ));
            $data = (array) ($request->input('data') ?? []);

            // ── Subscription invoice payment ─────────────────────────────────
            $subscriptionId = $this->extractSubscriptionId($request->all(), $data);

            if ($subscriptionId) {
                return $this->handleSubscriptionWebhook(
                    $subscriptionId, $eventType, $data, $request->all()
                );
            }

            // ── Regular (one-off) payment ─────────────────────────────────────
            $payment = $this->resolvePaymentFromWebhookPayload($request->all(), $data);

            if (!$payment) {
                Log::warning('StreamPay webhook could not be matched to a payment record', [
                    'event_type'  => $eventType,
                    'entity_type' => $request->input('entity_type'),
                    'entity_id'   => $request->input('entity_id'),
                ]);
                return response()->json(['received' => true]);
            }

            $streamPaymentId = data_get($data, 'payment.id')
                ?? ($request->input('entity_type') === 'PAYMENT' ? $request->input('entity_id') : null)
                ?? $payment->getStreamPaymentId();
            $paymentLinkId = data_get($data, 'payment_link.id')
                ?? ($request->input('entity_type') === 'PAYMENT_LINK' ? $request->input('entity_id') : null)
                ?? $payment->getPaymentLinkId();
            $status = strtoupper((string) (
                $request->input('status')
                ?? data_get($data, 'payment.current_status')
                ?? data_get($data, 'status')
                ?? ''
            ));
            $gatewayPayload = array_replace_recursive($request->all(), array_filter([
                'payment_id'      => $streamPaymentId,
                'payment_link_id' => $paymentLinkId,
            ], static fn ($value) => filled($value)));

            if (
                in_array($eventType, ['PAYMENT_SUCCEEDED', 'PAYMENT_MARKED_AS_PAID'], true)
                || in_array($status, ['SUCCEEDED', 'SETTLED'], true)
            ) {
                $this->paymentService->finalizeSuccessfulPayment($payment, $streamPaymentId, $gatewayPayload);
                return response()->json(['received' => true]);
            }

            if ($eventType === 'PAYMENT_REFUNDED' || $status === 'REFUNDED') {
                $this->paymentService->syncRefundedPayment($payment, $gatewayPayload);
                return response()->json(['received' => true]);
            }

            if (
                in_array($eventType, ['PAYMENT_FAILED', 'PAYMENT_CANCELED', 'PAYMENT_LINK_PAY_ATTEMPT_FAILED'], true)
                || in_array($status, ['FAILED', 'FAILED_INITIATION', 'CANCELED', 'EXPIRED'], true)
            ) {
                if (!in_array($payment->payment_status, ['completed', 'refunded'], true)) {
                    $payment->markAsFailed(
                        data_get($data, 'failure_reason')
                            ?? data_get($request->all(), 'message')
                            ?? ('Webhook reported payment ' . strtolower($status ?: $eventType))
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error('StreamPay webhook error', [
                'message'    => $e->getMessage(),
                'event_type' => $eventType ?? null,
                'payment_id' => $payment?->id ?? null,
            ]);
        }

        return response()->json(['received' => true]);
    }

    // =========================================================================
    // Subscription invoice handling
    // =========================================================================

    private function handleSubscriptionWebhook(
        string $subscriptionId,
        string $eventType,
        array $data,
        array $fullPayload
    ) {
        $plan = InstallmentPlan::where('streampay_subscription_id', $subscriptionId)
            ->with(['user', 'course', 'enrollment'])
            ->first();

        if (!$plan) {
            Log::warning('StreamPay subscription webhook could not be matched to an installment plan', [
                'subscription_id' => $subscriptionId,
                'event_type'      => $eventType,
            ]);
            return response()->json(['received' => true]);
        }

        // Invoice paid → process installment
        $invoiceStatus = strtoupper((string) (
            data_get($data, 'invoice.status')
            ?? data_get($data, 'status')
            ?? ''
        ));

        $isPaymentSuccess = in_array($eventType, ['PAYMENT_SUCCEEDED', 'PAYMENT_MARKED_AS_PAID', 'INVOICE_PAID', 'INVOICE_COMPLETED'], true)
            || in_array($invoiceStatus, ['COMPLETED', 'ACCEPTED'], true)
            || in_array(strtoupper((string) ($fullPayload['status'] ?? '')), ['SUCCEEDED', 'SETTLED'], true);

        if ($isPaymentSuccess) {
            $gatewayPaymentId = data_get($data, 'payment.id')
                ?? data_get($data, 'id')
                ?? ($fullPayload['entity_type'] === 'PAYMENT' ? ($fullPayload['entity_id'] ?? null) : null);

            // Determine cycle number from plan's current state or from invoice
            $cycleNumber = $this->resolveCycleNumber($plan, $data);

            Log::info('StreamPay subscription invoice paid', [
                'plan_id'           => $plan->id,
                'subscription_id'   => $subscriptionId,
                'cycle_number'      => $cycleNumber,
                'gateway_payment_id'=> $gatewayPaymentId,
            ]);

            $this->installmentService->handleSubscriptionInvoicePaid(
                $plan,
                $gatewayPaymentId ?? 'unknown',
                $cycleNumber,
                ['event_type' => $eventType, 'subscription_id' => $subscriptionId]
            );

            return response()->json(['received' => true]);
        }

        // Invoice expired / failed → suspend access
        $isExpiredOrFailed = in_array($eventType, ['INVOICE_EXPIRED', 'INVOICE_CANCELED', 'SUBSCRIPTION_CANCELED'], true)
            || in_array($invoiceStatus, ['EXPIRED', 'CANCELED'], true);

        if ($isExpiredOrFailed && in_array($plan->status, ['active'], true)) {
            Log::info('StreamPay subscription invoice expired — suspending plan', [
                'plan_id'         => $plan->id,
                'subscription_id' => $subscriptionId,
                'event_type'      => $eventType,
            ]);
            $this->installmentService->suspendOverduePlan($plan);
        }

        return response()->json(['received' => true]);
    }

    private function resolveCycleNumber(InstallmentPlan $plan, array $data): int
    {
        // StreamPay may include the cycle number in the subscription data
        $cycleNumber = (int) (
            data_get($data, 'subscription.current_cycle_number')
            ?? data_get($data, 'current_cycle_number')
        );

        if ($cycleNumber >= 1) {
            return $cycleNumber;
        }

        // Fall back to installments_paid + 1
        return $plan->installments_paid + 1;
    }

    private function extractSubscriptionId(array $payload, array $data): ?string
    {
        return data_get($data, 'invoice.subscription_id')
            ?? data_get($data, 'subscription.id')
            ?? data_get($data, 'subscription_id')
            ?? data_get($payload, 'subscription_id')
            ?? (($payload['entity_type'] ?? '') === 'SUBSCRIPTION' ? ($payload['entity_id'] ?? null) : null);
    }

    // =========================================================================
    // One-off payment resolution (unchanged)
    // =========================================================================

    private function resolvePaymentFromWebhookPayload(array $payload, array $data): ?Payment
    {
        $internalPaymentId = data_get($data, 'metadata.payment_id')
            ?? data_get($data, 'custom_metadata.payment_id')
            ?? data_get($payload, 'data.metadata.payment_id')
            ?? data_get($payload, 'data.custom_metadata.payment_id');

        if ($internalPaymentId) {
            return Payment::find($internalPaymentId);
        }

        $entityType = strtoupper((string) ($payload['entity_type'] ?? ''));

        $candidateGatewayIds = array_filter([
            data_get($data, 'payment.id'),
            data_get($data, 'payment_link.id'),
            $entityType === 'PAYMENT'      ? ($payload['entity_id'] ?? null) : null,
            $entityType === 'PAYMENT_LINK' ? ($payload['entity_id'] ?? null) : null,
            $data['payment_id']      ?? null,
            $data['payment_link_id'] ?? null,
        ]);

        foreach ($candidateGatewayIds as $gatewayId) {
            $payment = Payment::where('gateway_payment_id', $gatewayId)->first();
            if ($payment) {
                return $payment;
            }
        }

        return null;
    }

    private function hasValidWebhookSignature(Request $request, string $payload, string $secret): bool
    {
        $signedHeader = $request->header('X-Webhook-Signature');

        if ($signedHeader) {
            $parts = [];
            foreach (explode(',', $signedHeader) as $part) {
                [$key, $value] = array_pad(explode('=', trim($part), 2), 2, null);
                if ($key && $value) {
                    $parts[$key] = $value;
                }
            }

            $timestamp = $parts['t'] ?? $request->header('X-Webhook-Timestamp');
            $signature = $parts['v1'] ?? null;

            if (!$timestamp || !$signature) {
                return false;
            }

            $timestampInt = (int) $timestamp;
            if ($timestampInt > 1_000_000_000_000) {
                $timestampInt = (int) floor($timestampInt / 1000);
            }

            $tolerance = (int) config('services.streampay.webhook_tolerance', 300);
            if ($timestampInt <= 0 || abs(time() - $timestampInt) > $tolerance) {
                return false;
            }

            $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
            return hash_equals($expected, $signature);
        }

        $legacySignature = $request->header('X-Signature')
            ?? $request->header('X-StreamPay-Signature')
            ?? $request->header('Signature');

        if (!$legacySignature) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $legacySignature);
    }
}
