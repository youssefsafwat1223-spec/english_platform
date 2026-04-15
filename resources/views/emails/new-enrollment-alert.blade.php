<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اشتراك جديد</title>
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
        .badge { display: inline-block; background: #d1fae5; color: #065f46; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>🎉 اشتراك جديد في الكورس!</h1>
        <p>تم تسجيل طالب جديد الآن</p>
    </div>

    <div class="body">

        <div class="row">
            <span class="label">👤 اسم الطالب</span>
            <span class="value">{{ $enrollment->user->name }}</span>
        </div>

        <div class="row">
            <span class="label">📧 البريد الإلكتروني</span>
            <span class="value">{{ $enrollment->user->email }}</span>
        </div>

        @if($enrollment->user->phone)
        <div class="row">
            <span class="label">📱 رقم الهاتف</span>
            <span class="value">{{ $enrollment->user->phone }}</span>
        </div>
        @endif

        <div class="row">
            <span class="label">📚 الكورس</span>
            <span class="value">{{ $enrollment->course->title }}</span>
        </div>

        <div class="row">
            <span class="label">💰 المبلغ المدفوع</span>
            <span class="value">
                @if($enrollment->price_paid > 0)
                    {{ number_format($enrollment->price_paid, 0) }} ريال
                @else
                    <span class="badge">مجاني</span>
                @endif
            </span>
        </div>

        @if($enrollment->discount_amount > 0)
        <div class="row">
            <span class="label">🏷️ الخصم</span>
            <span class="value">{{ number_format($enrollment->discount_amount, 0) }} ريال
                @if($enrollment->discount_code)
                    ({{ $enrollment->discount_code }})
                @endif
            </span>
        </div>
        @endif

        <div class="row">
            <span class="label">🕐 وقت الاشتراك</span>
            <span class="value">{{ $enrollment->created_at->format('Y/m/d — h:i A') }}</span>
        </div>

    </div>

    <div class="footer">
        Simple English Platform — إشعار تلقائي
    </div>

</div>
</body>
</html>
