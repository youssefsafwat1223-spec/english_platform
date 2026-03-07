<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 0; background: #f3f4f6; direction: rtl; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .course-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin: 12px 0; }
        .course-name { font-weight: bold; color: #1f2937; font-size: 16px; }
        .course-progress { color: #6b7280; font-size: 14px; margin-top: 4px; }
        .progress-bar { height: 8px; background: #e5e7eb; border-radius: 999px; margin-top: 8px; overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #6366f1, #8b5cf6); border-radius: 999px; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #f59e0b, #ef4444); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👋 اشتقنالك!</h1>
            <p>مر {{ $daysSinceActive }} يوم من آخر زيارة لك</p>
        </div>
        <div class="content">
            <div class="greeting">مرحباً {{ $user->name }}!</div>
            
            <p>لاحظنا إنك مش نشط من <strong>{{ $daysSinceActive }} يوم</strong>. رحلتك التعليمية في انتظارك!</p>

            @if($enrolledCourses && count($enrolledCourses) > 0)
                <p style="font-weight: bold; color: #1f2937;">الكورسات بتاعتك لسه موجودة:</p>
                @foreach($enrolledCourses as $enrollment)
                    <div class="course-card">
                        <div class="course-name">📚 {{ $enrollment->course->title ?? 'كورس' }}</div>
                        <div class="course-progress">التقدم: {{ $enrollment->progress_percentage ?? 0 }}%</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            @endif

            <p style="text-align: center;">
                <a href="{{ route('student.dashboard') }}" class="button">
                    استمر في التعلم ←
                </a>
            </p>

            <p style="color: #6b7280; font-size: 14px;">🔥 نصيحة: الممارسة اليومية المستمرة، حتى لو 30 دقيقة بس، هي المفتاح لإتقان الإنجليزية!</p>

            <p>نشوفك قريب!<br><strong>فريق Simple English</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Simple English. جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
