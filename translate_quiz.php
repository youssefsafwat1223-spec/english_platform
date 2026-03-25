<?php

$translations = [
    // take.blade.php
    'Take Quiz: ' => 'خوض الاختبار: ',
    'Question' => 'السؤال',
    'of' => 'من',
    'Finish Early' => 'إنهاء مبكر',
    'Please review the following errors:' => 'يرجى مراجعة الأخطاء التالية:',
    'Previous' => 'السابق',
    'Next Question' => 'السؤال التالي',
    'Submit Quiz' => 'تسليم الاختبار',
    'Are you sure?' => 'هل أنت متأكد؟',
    'You answered' => 'لقد أجبت على',
    'out of' => 'من أصل',
    'You have unanswered questions!' => 'لديك أسئلة لم تقم بالإجابة عليها!',
    'Review' => 'مراجعة',
    'Submit' => 'تسليم',
    'Click here to answer' => 'اضغط هنا للإجابة',
    
    // result.blade.php
    'Quiz Result' => 'نتيجة الاختبار',
    'Congratulations!' => 'مبروك! لقد اجتزت الاختبار🎉',
    'Keep Trying!' => 'حاول مرة أخرى!',
    'You have completed the' => 'لقد أكملت اختبار:',
    'Score' => 'الدرجة',
    'Correct' => 'صح',
    'Questions' => 'أسئلة',
    'Time Taken' => 'الوقت المستغرق',
    'Next Lesson' => 'الدرس التالي',
    'Back to Course' => 'العودة للكورس',
    'Retake Quiz' => 'إعادة الاختبار',
    'All Attempts' => 'كل المحاولات',
    'Questions Review' => 'مراجعة الأسئلة',
    'Your Answer' => 'إجابتك',
    'Correct Answer' => 'الإجابة الصحيحة',
    
    // my-attempts.blade.php
    'My Quiz Attempts' => 'محاولاتي في الاختبارات',
    'All Your Attempts' => 'كل محاولاتك',
    'Review your past quiz attempts, scores, and progress.' => 'راجع محاولاتك السابقة، الدرجات، وتقدمك في الاختبارات.',
    'Passed' => 'ناجح',
    'Failed' => 'لم يجتز',
    'Course:' => 'الكورس:',
    'Lesson:' => 'الدرس:',
    'View Details' => 'عرض التفاصيل',
    'No Attempts Yet' => 'لا توجد محاولات بعد',
    'You haven\'t taken any quizzes yet. Start learning and test your knowledge!' => 'لم تقم بإجراء أي اختبارات حتى الآن. ابدأ التعلم واختبر معلوماتك!',
    'Explore Courses' => 'استكشاف الكورسات',
];

$saJsonPath = __DIR__ . '/lang/sa.json';
$arJsonPath = __DIR__ . '/lang/ar.json';

$saJson = file_exists($saJsonPath) ? json_decode(file_get_contents($saJsonPath), true) : [];
$arJson = file_exists($arJsonPath) ? json_decode(file_get_contents($arJsonPath), true) : [];

foreach ($translations as $eng => $ara) {
    if (!isset($saJson[$eng]) || preg_match('/^[a-zA-Z]/', $saJson[$eng]) || $saJson[$eng] === $eng) {
        $saJson[$eng] = $ara;
    }
    if (!isset($arJson[$eng]) || preg_match('/^[a-zA-Z]/', $arJson[$eng]) || $arJson[$eng] === $eng) {
        $arJson[$eng] = $ara;
    }
}

file_put_contents($saJsonPath, json_encode($saJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arJsonPath, json_encode($arJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Added student quiz translations to sa.json and ar.json.\n";
