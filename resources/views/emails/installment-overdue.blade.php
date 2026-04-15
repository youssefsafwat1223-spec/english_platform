<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم إيقاف الوصول</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; direction: rtl; }
        .container { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #ef4444, #dc2626); padding: 28px 32px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 800; }
        .header p  { margin: 6px 0 0; font-size: 14px; opacity: 0.9; }
        .body { padding: 28px 32px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .row:last-child { border-bottom: none; }
        .label { color: #6b7280; font-size: 13px; }
        .value { color: #111827; font-size: 14px; font-weight: 700; }
        .info-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; font-size: 14px; color: #991b1b; margin: 20px 0; line-height: 1.6; }
        .restore-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; font-size: 14px; color: #14532d; margin-top: 16px; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔒 تم إيقاف وصولك مؤقتاً</h1>
        <p>بسبب تأخر سداد القسط {{ $plan->next_installment_number }}</p>
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
            <span class="label">📦 القسط المتأخر</span>
            <span class="value">{{ $plan->next_installment_number }} من {{ $plan->installments_count }}</span>
        </div>
        <div class="row">
            <span class="label">💰 المبلغ المطلوب</span>
            <span class="value">{{ number_format($plan->installment_amount, 0) }} ريال</span>
        </div>

        <div class="info-box">
            تم إيقاف وصولك لكورس <strong>{{ $plan->course->title }}</strong> بشكل مؤقت لأنه لم يتم سداد القسط {{ $plan->next_installment_number }} في الموعد المحدد.
        </div>

        <div class="restore-box">
            ✅ <strong>لاستعادة الوصول:</strong><br>
            تواصل معنا أو ادفع القسط المتأخر وسيتم فتح الكورس فوراً تلقائياً.
        </div>
    </div>

    <div class="footer">
        Simple English Platform — إشعار تلقائي
    </div>
</div>
</body>
</html>
