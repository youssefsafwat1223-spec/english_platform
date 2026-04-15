<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StreamPayWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
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
