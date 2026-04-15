<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الاشتراك بالتقسيط</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; direction: rtl; }
        .container { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 28px 32px; color: #fff; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 800; }
        .header p  { margin: 6px 0 0; font-size: 14px; opacity: 0.85; }
        .body { padding: 28px 32px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .row:last-child { border-bottom: none; }
        .label { color: #6b7280; font-size: 13px; }
        .value { color: #111827; font-size: 14px; font-weight: 700; }
        .schedule { margin-top: 20px; }
        .schedule-title { font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 12px; }
        .installment-row { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 8px; margin-bottom: 8px; }
        .installment-row.paid { background: #d1fae5; }
        .installment-row.upcoming { background: #f3f4f6; }
        .installment-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; color: #fff; background: #6366f1; flex-shrink: 0; }
        .installment-num.paid { background: #059669; }
        .installment-info { flex: 1; }
        .installment-label { font-size: 13px; font-weight: 700; color: #111827; }
        .installment-date { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .installment-amount { font-size: 14px; font-weight: 800; color: #6366f1; }
        .badge-paid { background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 تم تفعيل اشتراكك بالتقسيط!</h1>
        <p>يمكنك الآن الوصول إلى الكورس</p>
    </div>

    <div class="body">
        <div class="row">
            <span class="label">📚 الكورس</span>
            <span class="value">{{ $plan->course->title }}</span>
        </div>
        <div class="row">
            <span class="label">💰 إجمالي السعر</span>
            <span class="value">{{ number_format($plan->total_amount, 0) }} ريال</span>
        </div>
        <div class="row">
            <span class="label">📦 قيمة كل قسط</span>
            <span class="value">{{ number_format($plan->installment_amount, 0) }} ريال</span>
        </div>

        <div class="schedule">
            <div class="schedule-title">📅 جدول الأقساط</div>

            @php
                $dueDate = $plan->created_at;
            @endphp

            @for ($i = 1; $i <= $plan->installments_count; $i++)
                @php
                    $isPaid = $i <= $plan->installments_paid;
                    $date = $i === 1 ? $plan->created_at : $plan->created_at->addDays(30 * ($i - 1));
                @endphp
                <div class="installment-row {{ $isPaid ? 'paid' : 'upcoming' }}">
                    <div class="installment-num {{ $isPaid ? 'paid' : '' }}">{{ $i }}</div>
                    <div class="installment-info">
                        <div class="installment-label">القسط {{ $i }}</div>
                        <div class="installment-date">{{ $date->format('Y/m/d') }}</div>
                    </div>
                    <div>
                        @if($isPaid)
                            <span class="badge-paid">✓ مدفوع</span>
                        @else
                            <span class="installment-amount">{{ number_format($plan->installment_amount, 0) }} ريال</span>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="footer">
        Simple English Platform — سيتم إرسال رابط الدفع قبل 3 أيام من موعد كل قسط
    </div>
</div>
</body>
</html>
