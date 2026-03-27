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
        // ── Verify payment ownership ──
        if (auth()->check() && auth()->id() !== $payment->user_id) {
            abort(403, 'Unauthorized');
        }

        Log::info('StreamPay payment callback received', [
            'payment_id' => $payment->id,
            'request' => $request->all(),
        ]);

        // Check if StreamPay explicitly says the payment failed via redirect params
        $redirectStatus = $request->input('status');

        if ($redirectStatus === 'failed') {
            $payment->markAsFailed($request->input('message', 'Payment failed'));

            return redirect()->route('student.courses.show', $payment->course)
                ->with('error', __('فشلت عملية الدفع. تأكد من صحة بيانات البطاقة وأن البطاقة مدعومة، ثم حاول مرة أخرى.'));
        }

        $result = $this->paymentService->handleCallback($payment->id);

        if ($result['success']) {
            if ($result['message'] === 'Payment successful') {
                return redirect()->route('student.courses.learn', $payment->course)
                    ->with('success', __('تم الدفع بنجاح! مرحباً بك في الكورس 🎉'));
            } else {
                return redirect()->route('student.courses.show', $payment->course)
                    ->with('info', __('جاري التحقق من عملية الدفع. ستصلك رسالة بريد إلكتروني قريباً.'));
            }
        }

        return redirect()->route('student.courses.show', $payment->course)
            ->with('error', __('حدث خطأ أثناء معالجة الدفع. حاول مرة أخرى أو استخدم بطاقة أخرى.'));
    }


}
