<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function callback(Payment $payment, Request $request)
    {
        Log::info('Payment callback received', [
            'payment_id' => $payment->id,
            'request' => $request->all(),
        ]);

        $chargeId = $request->input('tap_id');

        if (!$chargeId) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Payment verification failed.');
        }

        $result = $this->paymentService->handleCallback($chargeId);

        if ($result['success']) {
            return redirect()->route('student.courses.learn', $payment->course_id)
                ->with('success', 'Payment successful! Welcome to the course!');
        }

        return redirect()->route('student.courses.show', $payment->course_id)
            ->with('error', $result['message']);
    }

    public function webhook(Request $request)
    {
        Log::info('Payment webhook received', $request->all());

        // Verify webhook signature
        // Process webhook event

        return response()->json(['received' => true]);
    }
}