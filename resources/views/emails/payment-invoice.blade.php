<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 0; background: #f3f4f6; direction: rtl; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .invoice-box { background: #f9fafb; border-radius: 12px; padding: 24px; margin: 20px 0; border: 1px solid #e5e7eb; }
        .invoice-title { font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #10b981; padding-bottom: 10px; }
        .invoice-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
        .invoice-label { color: #6b7280; font-size: 14px; }
        .invoice-value { font-weight: bold; color: #1f2937; font-size: 14px; }
        .invoice-total { display: flex; justify-content: space-between; padding: 14px 0; margin-top: 8px; border-top: 2px solid #10b981; }
        .invoice-total .invoice-label { font-weight: bold; color: #1f2937; font-size: 16px; }
        .invoice-total .invoice-value { font-weight: bold; color: #10b981; font-size: 18px; }
        .status-badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #10b981, #059669); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
        .info-text { color: #6b7280; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 10px 0; }
        table td.label { color: #6b7280; font-size: 14px; text-align: right; width: 40%; }
        table td.value { font-weight: bold; color: #1f2937; font-size: 14px; text-align: left; }
        table tr.total-row td { border-top: 2px solid #10b981; padding-top: 14px; }
        table tr.total-row td.label { font-weight: bold; color: #1f2937; font-size: 16px; }
        table tr.total-row td.value { font-weight: bold; color: #10b981; font-size: 18px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧾 {{ __('فاتورة الدفع') }}</h1>
            <p>{{ __('شكراً لاشتراكك في Simple English') }}</p>
        </div>
        <div class="content">
            <div class="greeting">{{ __('مرحباً') }} {{ $payment->user->name }}!</div>
            
            <p>{{ __('تم اشتراكك بنجاح! إليك تفاصيل الفاتورة الخاصة بك:') }}</p>

            <div class="invoice-box">
                <div class="invoice-title">{{ __('تفاصيل الفاتورة') }}</div>
                
                <table>
                    <tr>
                        <td class="label">{{ __('رقم المعاملة') }}</td>
                        <td class="value" style="font-family: monospace; font-size: 12px;">{{ $payment->transaction_id }}</td>
                    </tr>
                    <tr>
                        <td class="label">{{ __('تاريخ الدفع') }}</td>
                        <td class="value">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d - h:i A') : $payment->created_at->format('Y/m/d - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">{{ __('الكورس') }}</td>
                        <td class="value">{{ $payment->course->title }}</td>
                    </tr>
                    <tr>
                        <td class="label">{{ __('السعر الأصلي') }}</td>
                        <td class="value">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @if($payment->discount_amount > 0)
                    <tr>
                        <td class="label">{{ __('الخصم') }}</td>
                        <td class="value" style="color: #10b981;">- {{ $payment->currency }} {{ number_format($payment->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label">{{ __('المبلغ المدفوع') }}</td>
                        <td class="value">{{ $payment->currency }} {{ number_format($payment->final_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <p style="text-align: center;">
                <span class="status-badge">✅ {{ __('تم الدفع بنجاح') }}</span>
            </p>

            <p style="text-align: center;">
                <a href="{{ route('student.courses.learn', $payment->course_id) }}" class="button">
                    {{ __('ابدأ التعلم الآن') }} &larr;
                </a>
            </p>

            <p class="info-text">{{ __('يمكنك الوصول لكورسك في أي وقت من خلال لوحة التحكم الخاصة بك. إذا كان لديك أي استفسار، تواصل معنا عبر واتساب.') }}</p>

            <p>{{ __('تعلم ممتع!') }}<br><strong>{{ __('فريق Simple English') }}</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Simple English. {{ __('جميع الحقوق محفوظة.') }}<br>
            <span style="font-size: 11px; color: #d1d5db;">{{ __('هذه الفاتورة تم إنشاؤها تلقائياً عبر بوابة StreamPay.') }}</span>
        </div>
    </div>
</body>
</html>
