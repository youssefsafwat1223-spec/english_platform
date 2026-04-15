<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكير بموعد القسط</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; direction: rtl; }
        .container { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #f59e0b, #d97706); padding: 28px 32px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 800; }
        .header p  { margin: 6px 0 0; font-size: 14px; opacity: 0.9; }
        .body { padding: 28px 32px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .row:last-child { border-bottom: none; }
        .label { color: #6b7280; font-size: 13px; }
        .value { color: #111827; font-size: 14px; font-weight: 700; }
        .btn { display: block; text-align: center; background: #6366f1; color: #fff; text-decoration: none; padding: 14px 24px; border-radius: 8px; font-size: 16px; font-weight: 800; margin: 24px 0 0; }
        .warning { background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400e; margin-top: 16px; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>⏰ تذكير: موعد القسط {{ $plan->next_installment_number }}</h1>
        <p>موعد الدفع: {{ $plan->next_due_at?->format('Y/m/d') }}</p>
    </div>

    <div class="body">
        <div class="row">
            <span class="label">👤 الاسم</span>
            <span class="value">{{ $plan->user->name }}</span>
        </div>
        <div class="row">
            <span class="label">📚 الكورس</span>
            <span class="value">{{ $plan->course->title }}</span>
        </div>
        <div class="row">
            <span class="label">📦 رقم القسط</span>
            <span class="value">{{ $plan->next_installment_number }} من {{ $plan->installments_count }}</span>
        </div>
        <div class="row">
            <span class="label">💰 المبلغ المطلوب</span>
            <span class="value">{{ number_format($plan->installment_amount, 0) }} ريال</span>
        </div>
        <div class="row">
            <span class="label">📅 آخر موعد للدفع</span>
            <span class="value">{{ $plan->next_due_at?->addDays(7)->format('Y/m/d') }}</span>
        </div>

        @if($paymentUrl)
            <a href="{{ $paymentUrl }}" class="btn">💳 ادفع الآن</a>
        @endif

        <div class="warning">
            ⚠️ في حال عدم الدفع خلال 7 أيام من الموعد، سيتم إيقاف وصولك للكورس مؤقتاً حتى يتم السداد.
        </div>
    </div>

    <div class="footer">
        Simple English Platform — إشعار تلقائي
    </div>
</div>
</body>
</html>
