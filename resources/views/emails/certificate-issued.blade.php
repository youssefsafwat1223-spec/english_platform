<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Tahoma, Arial, sans-serif; line-height: 1.8; color: #1f2937; margin: 0; padding: 0; background: #f3f4f6; direction: rtl; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bb5; color: white; padding: 28px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #007bb5; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: bold; }
        .stats { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    @php
        $studentName = $certificate->user?->name ?? 'الطالب';
        $courseTitle = $certificate->course?->title ?? 'الكورس';
    @endphp

    <div class="container">
        <div class="header">
            <h1>مبروك، شهادتك جاهزة</h1>
        </div>
        <div class="content">
            <h2>مرحباً {{ $studentName }}</h2>

            <p>مبروك عليك إتمام <strong>{{ $courseTitle }}</strong>.</p>
            <p>شهادتك جاهزة للتحميل الآن من حسابك على المنصة.</p>

            <div class="stats">
                <h3>بيانات الشهادة</h3>
                <ul>
                    <li><strong>الدرجة النهائية:</strong> {{ $certificate->final_score }}%</li>
                    <li><strong>التقدير:</strong> {{ $certificate->grade }}</li>
                    <li><strong>مستوى الأداء:</strong> {{ $certificate->performance_level }}</li>
                    <li><strong>رقم الشهادة:</strong> {{ $certificate->certificate_id }}</li>
                </ul>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('student.certificates.show', $certificate) }}" class="button">عرض الشهادة</a>
            </p>

            <p>يمكن التحقق من الشهادة في أي وقت باستخدام رقم الشهادة.</p>
            <p>فريق {{ config('app.name', 'Simple English') }}</p>
        </div>
    </div>
</body>
</html>
