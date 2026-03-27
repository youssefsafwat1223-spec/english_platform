<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function callback(Payment $payment, Request $request)
    {
        if (auth()->check() && auth()->id() !== $payment->user_id) {
            abort(403, 'Unauthorized');
        }

        $ignoredQuery = array_values(array_diff(array_keys($request->query()), ['signature', 'expires']));

        if (!auth()->check() && !$request->hasValidSignatureWhileIgnoring($ignoredQuery)) {
            Log::warning('Rejected unsigned payment callback', [
                'payment_id' => $payment->id,
                'ip' => $request->ip(),
                'query' => $request->query(),
            ]);

            abort(403, 'Unauthorized');
        }

        Log::info('StreamPay payment callback received', [
            'payment_id' => $payment->id,
            'request' => $request->all(),
        ]);

        $result = $this->paymentService->handleCallback(
            $payment->id,
            $request->string('payment_id')->toString() ?: null,
            $request->string('invoice_id')->toString() ?: null,
            $request->string('payment_link_id')->toString() ?: null
        );

        if ($result['success']) {
            if (($result['message'] ?? null) === 'Payment successful') {
                return redirect()->route('student.courses.learn', $payment->course)
                    ->with('success', __('تم الدفع بنجاح! مرحبًا بك في الكورس.'));
            }

            return redirect()->route('student.courses.show', $payment->course)
                ->with('info', __('جارٍ التحقق من عملية الدفع. سيصلك تحديث قريبًا.'));
        }

        if ($payment->fresh()->payment_status === 'failed') {
            return redirect()->route('student.courses.show', $payment->course)
                ->with('error', __('فشلت عملية الدفع. تأكد من بيانات البطاقة أو جرّب وسيلة أخرى.'));
        }

        return redirect()->route('student.courses.show', $payment->course)
            ->with('error', __('حدث خطأ أثناء معالجة الدفع. حاول مرة أخرى.'));
    }
}
