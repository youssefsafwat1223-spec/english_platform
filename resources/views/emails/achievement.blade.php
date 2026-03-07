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
        .achievement-box { background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 2px solid #86efac; border-radius: 12px; padding: 24px; margin: 20px 0; text-align: center; }
        .achievement-icon { font-size: 48px; margin-bottom: 12px; }
        .achievement-title { font-size: 20px; font-weight: bold; color: #166534; margin-bottom: 8px; }
        .achievement-detail { color: #15803d; font-size: 16px; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #10b981, #059669); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($achievementType === 'course_completed')
                <h1>🎉 تم إكمال الكورس!</h1>
                <p>وصلت لإنجاز رائع</p>
            @elseif($achievementType === 'high_score')
                <h1>🌟 درجة ممتازة!</h1>
                <p>مجهودك يؤتي ثماره</p>
            @elseif($achievementType === 'certificate_earned')
                <h1>🎓 حصلت على شهادة!</h1>
                <p>إنجازك أصبح رسمياً الآن</p>
            @else
                <h1>🏆 مبروك!</h1>
                <p>حققت إنجاز رائع</p>
            @endif
        </div>
        <div class="content">
            <div class="greeting">أحسنت يا {{ $user->name }}!</div>

            <div class="achievement-box">
                @if($achievementType === 'course_completed')
                    <div class="achievement-icon">🎉</div>
                    <div class="achievement-title">تم إكمال الكورس!</div>
                    <div class="achievement-detail">
                        <strong>{{ $achievementData['course_title'] ?? '' }}</strong>
                    </div>
                @elseif($achievementType === 'high_score')
                    <div class="achievement-icon">🌟</div>
                    <div class="achievement-title">الدرجة: {{ $achievementData['score'] ?? '' }}%</div>
                    <div class="achievement-detail">
                        الاختبار: <strong>{{ $achievementData['quiz_title'] ?? '' }}</strong><br>
                        الكورس: {{ $achievementData['course_title'] ?? '' }}
                    </div>
                @elseif($achievementType === 'certificate_earned')
                    <div class="achievement-icon">🎓</div>
                    <div class="achievement-title">الشهادة جاهزة!</div>
                    <div class="achievement-detail">
                        <strong>{{ $achievementData['course_title'] ?? '' }}</strong><br>
                        الدرجة النهائية: {{ $achievementData['score'] ?? '' }}% &mdash; التقدير: {{ $achievementData['grade'] ?? '' }}
                    </div>
                @endif
            </div>

            <p style="text-align: center;">
                <a href="{{ $achievementData['action_url'] ?? route('student.dashboard') }}" class="button">
                    @if($achievementType === 'certificate_earned')
                        عرض الشهادة ←
                    @else
                        عرض لوحة التحكم ←
                    @endif
                </a>
            </p>

            <p>استمر في التميز! كل خطوة تقربك أكتر من الطلاقة.</p>

            <p>بفخر،<br><strong>فريق Simple English</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Simple English. جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
