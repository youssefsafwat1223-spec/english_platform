<?php

$replacements = [
    'resources/views/admin/courses/create.blade.php' => [
        "Brief overview (max 500 characters)" => "{{ __('Brief overview (max 500 characters)') }}",
        "Estimated Duration (weeks)" => "{{ __('Estimated Duration (weeks)') }}"
    ],
    'resources/views/admin/courses/edit.blade.php' => [
        "Estimated Duration (weeks)" => "{{ __('Estimated Duration (weeks)') }}"
    ],
    'resources/views/admin/email-campaigns/create.blade.php' => [
        "Button Text (Optional)" => "{{ __('Button Text (Optional)') }}",
        "Button URL (Optional)" => "{{ __('Button URL (Optional)') }}",
        "✅ Active Students (last 7 days)" => "✅ {{ __('Active Students (last 7 days)') }}",
        "⏸️ Inactive Students (7+ days)" => "⏸️ {{ __('Inactive Students (7+ days)') }}"
    ],
    'resources/views/admin/forum/index.blade.php' => [
        ">pending<" => ">{{ __('pending') }}<",
        ">topics<" => ">{{ __('topics') }}<",
        ">Order<" => ">{{ __('Order') }}<"
    ],
    'resources/views/admin/forum/reports.blade.php' => [
        ">Reported by<" => ">{{ __('Reported by') }}<"
    ],
    'resources/views/admin/forum/topic-details.blade.php' => [
        " views<" => " {{ __('views') }}<",
        " replies<" => " {{ __('replies') }}<"
    ],
    'resources/views/admin/games/show.blade.php' => [
        "⏱ s | 🏆 pts" => "⏱ {{ __('s') }} | 🏆 {{ __('pts') }}"
    ],
    'resources/views/admin/lessons/create.blade.php' => [
        "Add a new lesson to" => "{{ __('Add a new lesson to') }}",
        "Attachments (PDF, DOC, etc.)" => "{{ __('Attachments (PDF, DOC, etc.)') }}",
        "Duration (minutes)" => "{{ __('Duration (minutes)') }}",
        "Passing Score (%)" => "{{ __('Passing Score (%)') }}",
        "Max Duration (seconds)" => "{{ __('Max Duration (seconds)') }}"
    ],
    'resources/views/admin/lessons/edit.blade.php' => [
        "Duration (minutes)" => "{{ __('Duration (minutes)') }}",
        "Passing Score (%)" => "{{ __('Passing Score (%)') }}",
        "Max Duration (seconds)" => "{{ __('Max Duration (seconds)') }}"
    ],
    'resources/views/admin/payments/show.blade.php' => [
        ">Payment #<" => ">{{ __('Payment') }} #<",
        "Payment was refunded on" => "{{ __('Payment was refunded on') }}"
    ],
    'resources/views/admin/questions/create.blade.php' => [
        "Lesson (Optional)" => "{{ __('Lesson (Optional)') }}",
        "Points (Optional)" => "{{ __('Points (Optional)') }}",
        "Upload Custom Audio (MP3/WAV)" => "{{ __('Upload Custom Audio (MP3/WAV)') }}"
    ],
    'resources/views/admin/questions/edit.blade.php' => [
        "Lesson (Optional)" => "{{ __('Lesson (Optional)') }}",
        "Points (Optional)" => "{{ __('Points (Optional)') }}"
    ],
    'resources/views/admin/quizzes/attempt-details.blade.php' => [
        ">Question<" => ">{{ __('Question') }}<"
    ],
    'resources/views/admin/quizzes/create.blade.php' => [
        "Lesson (Optional)" => "{{ __('Lesson (Optional)') }}",
        "None (Final Exam)" => "{{ __('None (Final Exam)') }}"
    ],
    'resources/views/admin/quizzes/edit.blade.php' => [
        "Lesson (Optional)" => "{{ __('Lesson (Optional)') }}",
        "None (Final Exam)" => "{{ __('None (Final Exam)') }}"
    ],
    'resources/views/admin/settings/payment.blade.php' => [
        "Tax Rate (%)" => "{{ __('Tax Rate (%)') }}"
    ],
    'resources/views/admin/settings/points.blade.php' => [
        "Referral Discount (%)" => "{{ __('Referral Discount (%)') }}"
    ],
    'resources/views/admin/students/index.blade.php' => [
        ">Joined<" => ">{{ __('Joined') }}<"
    ],
    'resources/views/student/certificates/show.blade.php' => [
        ">Certificate<" => ">{{ __('Certificate') }}<",
        "Certificate ID:" => "{{ __('Certificate ID:') }}",
        "Issued:" => "{{ __('Issued:') }}",
        "Final Score:" => "{{ __('Final Score:') }}",
        "Grade:" => "{{ __('Grade:') }}",
        "Certificate PDF is not available yet." => "{{ __('Certificate PDF is not available yet.') }}",
        "Share on LinkedIn" => "{{ __('Share on LinkedIn') }}"
    ],
    'resources/views/student/certificates/verify-failed.blade.php' => [
        "Certificate Verification Failed" => "{{ __('Certificate Verification Failed') }}",
        "Go to Home" => "{{ __('Go to Home') }}",
        "Browse Courses" => "{{ __('Browse Courses') }}"
    ],
    'resources/views/student/certificates/verify.blade.php' => [
        "Certificate Verification" => "{{ __('Certificate Verification') }}",
        "Certificate Verified!" => "{{ __('Certificate Verified!') }}",
        "Visit Platform" => "{{ __('Visit Platform') }}"
    ],
    'resources/views/student/profile/change-password.blade.php' => [
        "Current Password *" => "{{ __('Current Password *') }}",
        "New Password *" => "{{ __('New Password *') }}",
        "Confirm New Password *" => "{{ __('Confirm New Password *') }}",
        "Cancel" => "{{ __('Cancel') }}",
        "Update Password" => "{{ __('Update Password') }}"
    ],
    'resources/views/student/profile/points-history.blade.php' => [
        ">Activity<" => ">{{ __('Activity') }}<",
        ">Points<" => ">{{ __('Points') }}<",
        ">Date<" => ">{{ __('Date') }}<",
        "No points history yet." => "{{ __('No points history yet.') }}"
    ],
    'resources/views/student/pronunciation/my-attempts.blade.php' => [
        "Pronunciation Practice" => "{{ __('Pronunciation Practice') }}"
    ]
];

// Translations to inject into sa.json
$newTranslations = [
    'Brief overview (max 500 characters)' => 'نبذة مختصرة (بحد أقصى 500 حرف)',
    'Estimated Duration (weeks)' => 'المدة المتوقعة (بالأسابيع)',
    'Button Text (Optional)' => 'نص الزر (اختياري)',
    'Button URL (Optional)' => 'رابط الزر (اختياري)',
    'Active Students (last 7 days)' => 'الطلاب النشطين (آخر 7 أيام)',
    'Inactive Students (7+ days)' => 'الطلاب غير النشطين (7 أيام أو أكثر)',
    'pending' => 'قيد الانتظار',
    'topics' => 'المواضيع',
    'Order' => 'الترتيب',
    'Reported by' => 'تم التبليغ من قبل',
    'views' => 'مشاهدات',
    'replies' => 'ردود',
    's' => 'ث',
    'pts' => 'نقطة',
    'Add a new lesson to' => 'إضافة درس جديد لـ',
    'Attachments (PDF, DOC, etc.)' => 'المرفقات (PDF، DOC، إلخ)',
    'Duration (minutes)' => 'المدة (بالدقائق)',
    'Passing Score (%)' => 'درجة النجاح (%)',
    'Max Duration (seconds)' => 'المدة القصوى (بالثواني)',
    'Upload Custom Audio (MP3/WAV)' => 'رفع ملف صوتي مخصص (MP3/WAV)',
    'Payment' => 'الدفعة',
    'Payment was refunded on' => 'تم استرداد الدفعة في',
    'Lesson (Optional)' => 'الدرس (اختياري)',
    'Points (Optional)' => 'النقاط (اختياري)',
    'Question' => 'السؤال',
    'None (Final Exam)' => 'لا يوجد (اختبار نهائي)',
    'Tax Rate (%)' => 'نسبة الضريبة (%)',
    'Referral Discount (%)' => 'خصم الدعوات (%)',
    'Joined' => 'تاريخ الانضمام',
    'Certificate' => 'الشهادة',
    'Certificate ID:' => 'رقم الشهادة:',
    'Issued:' => 'تاريخ الإصدار:',
    'Final Score:' => 'الدرجة النهائية:',
    'Grade:' => 'التقدير:',
    'Certificate PDF is not available yet.' => 'ملف الـ PDF للشهادة غير متاح بعد.',
    'Share on LinkedIn' => 'مشاركة على لينكد إن',
    'Certificate Verification Failed' => 'فشل التحقق من الشهادة',
    'Go to Home' => 'العودة للرئيسية',
    'Browse Courses' => 'تصفح الكورسات',
    'Certificate Verification' => 'التحقق من الشهادة',
    'Certificate Verified!' => 'تم التحقق من الشهادة بنجاح!',
    'Visit Platform' => 'زيارة المنصة',
    'Current Password *' => 'كلمة المرور الحالية *',
    'New Password *' => 'كلمة المرور الجديدة *',
    'Confirm New Password *' => 'تأكيد كلمة المرور الجديدة *',
    'Update Password' => 'تحديث كلمة المرور',
    'Cancel' => 'إلغاء',
    'Activity' => 'النشاط',
    'Points' => 'النقاط',
    'Date' => 'التاريخ',
    'No points history yet.' => 'لا يوجد سجل نقاط حتى الآن.',
    'Pronunciation Practice' => 'تمرين النطق',
    
    // Existing English in sa.json
    'Games' => 'الألعاب',
    'AI Engine' => 'محرك الذكاء الاصطناعي',
    'Tap Public Key' => 'خيارات بوابة الدفع (Tap)',
    'Set this in your .env file as TAP_PUBLIC_KEY.' => 'اضبط هذا بمفتاح TAP_PUBLIC_KEY في ملف .env.',
    'Currency *' => 'العملة *',
    'Points and Rewards Settings' => 'إعدادات النقاط والمكافآت',
    'Points Configuration' => 'تهيئة النقاط',
    'Referral Settings' => 'إعدادات الدعوات',
    'Bot Token' => 'توكن البوت',
    'Set this in your .env file as TELEGRAM_BOT_TOKEN.' => 'اضبط هذا في ملف .env كـ TELEGRAM_BOT_TOKEN.',
    'Webhook URL' => 'رابط الـ Webhook',
    'Send daily questions on alternate days' => 'إرسال أسئلة يومية في أيام متبادلة',
    'Daily Question Time' => 'وقت السؤال اليومي',
    'Enable Telegram notifications' => 'تفعيل إشعارات التيليجرام',
    'Webhook Management' => 'إدارة الـ Webhooks',
    'Set Webhook' => 'تفعيل الـ Webhook',
    'Delete Webhook' => 'حذف الـ Webhook',
    'Bot Info' => 'معلومات البوت'
];

foreach ($replacements as $file => $fileReplacements) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        foreach ($fileReplacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
        file_put_contents($path, $content);
    }
}

// Update JSON
$saJsonPath = __DIR__ . '/lang/sa.json';
$arJsonPath = __DIR__ . '/lang/ar.json';
$enJsonPath = __DIR__ . '/lang/en.json';

$saJson = file_exists($saJsonPath) ? json_decode(file_get_contents($saJsonPath), true) : [];
$arJson = file_exists($arJsonPath) ? json_decode(file_get_contents($arJsonPath), true) : [];
$enJson = file_exists($enJsonPath) ? json_decode(file_get_contents($enJsonPath), true) : [];

foreach ($newTranslations as $eng => $ara) {
    if (!isset($saJson[$eng]) || preg_match('/^[a-zA-Z]/', $saJson[$eng])) {
        $saJson[$eng] = $ara;
    }
    if (!isset($arJson[$eng]) || preg_match('/^[a-zA-Z]/', $arJson[$eng])) {
        $arJson[$eng] = $ara;
    }
    if (!isset($enJson[$eng])) {
        $enJson[$eng] = $eng;
    }
}

file_put_contents($saJsonPath, json_encode($saJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arJsonPath, json_encode($arJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($enJsonPath, json_encode($enJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "English translations applied to Blade views and sa.json.\n";
