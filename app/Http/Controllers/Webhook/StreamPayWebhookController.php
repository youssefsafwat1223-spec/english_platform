<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StreamPayWebhookController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(Request $request)
    {
        // ── Verify webhook signature ──
        $signature = $request->header('X-Signature') 
                  ?? $request->header('X-StreamPay-Signature')
                  ?? $request->header('Signature');
        $payload = $request->getContent();
        $secret = config('services.streampay.secret_key');

        if ($secret && $signature) {
            $expected = hash_hmac('sha256', $payload, $secret);
            if (!hash_equals($expected, $signature)) {
                Log::warning('StreamPay webhook: Invalid signature', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        Log::info('StreamPay webhook received', $request->all());

        try {
            $eventType = $request->input('event') ?? $request->input('type');
            $data = $request->input('data') ?? $request->all();
            
            // Extract payment ID from metadata if passed by StreamPay
            $paymentId = null;
            if (isset($data['custom_metadata']['payment_id'])) {
                $paymentId = $data['custom_metadata']['payment_id'];
            } elseif (isset($data['metadata']['payment_id'])) {
                $paymentId = $data['metadata']['payment_id'];
            } elseif (isset($data['payment_link_id'])) {
                // Find payment by gateway_payment_id if no metadata provided
                $payment = Payment::where('gateway_payment_id', $data['payment_link_id'])->first();
                if ($payment) {
                    $paymentId = $payment->id;
                }
            } else {
                $payment = Payment::where('gateway_payment_id', $data['id'] ?? null)->first();
                if ($payment) {
                    $paymentId = $payment->id;
                }
            }

            if ($paymentId) {
                $payment = Payment::find($paymentId);
                
                if ($payment && $payment->payment_status !== 'completed') {
                    // Check if it's a success event for a payment link or invoice
                    $status = strtolower($data['status'] ?? '');
                    $isSuccessEvent = in_array(strtolower($eventType), [
                        'payment_link.paid', 'payment.success', 'invoice.paid', 'payment.completed'
                    ]) || in_array($status, ['paid', 'completed', 'success']);

                    if ($isSuccessEvent) {
                        $payment->markAsCompleted($data['id'] ?? $data['payment_link_id'] ?? null, $data);
                        
                        // Handle referrals
                        $this->paymentService->handleReferralDiscountUsage($payment);
                    } elseif (in_array(strtolower($eventType), ['payment.failed', 'invoice.voided']) || $status === 'failed') {
                        $payment->markAsFailed($data['failure_reason'] ?? 'Webhook reported payment failure');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('StreamPay webhook error: ' . $e->getMessage());
        }

        return response()->json(['received' => true]);
    }
}
