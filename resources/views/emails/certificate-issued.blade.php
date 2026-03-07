<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 0; background: #f3f4f6; direction: rtl; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: bold; }
        .stats { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 مبروك!</h1>
        </div>
        <div class="content">
            <h2>مرحباً {{ $certificate->user->name }}!</h2>
            
            <p>مبروك عليك إنهاء كورس <strong>{{ $certificate->course->title }}</strong>!</p>
            
            <p>شهادتك جاهزة للتحميل الآن.</p>

            <div class="stats">
                <h3>نتائجك:</h3>
                <ul>
                    <li><strong>الدرجة النهائية:</strong> {{ $certificate->final_score }}%</li>
                    <li><strong>التقدير:</strong> {{ $certificate->grade }}</li>
                    <li><strong>مستوى الأداء:</strong> {{ $certificate->performance_level }}</li>
                    <li><strong>رقم الشهادة:</strong> {{ $certificate->certificate_id }}</li>
                </ul>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('student.certificates.show', $certificate) }}" class="button">
                    تحميل الشهادة
                </a>
            </p>

            <p>يمكنك التحقق من شهادتك في أي وقت باستخدام رقم الشهادة.</p>

            <p>استمر في التميز!<br><strong>فريق Simple English</strong></p>
        </div>
    </div>
</body>
</html>