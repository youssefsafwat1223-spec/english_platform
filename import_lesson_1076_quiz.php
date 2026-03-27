<?php

/**
 * Script to import questions for Lesson ID 1076 (Future Perfect Translation)
 * php import_lesson_1076_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1076;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1076 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'The fund will have reached a million Riyals by tomorrow. اختر الترجمة الصحيحة للجملة:', 'options' => ['الصندوق سوف يكون قد وصل لألف ريال بحلول غدا.', 'الصندوق سوف يكون قد وصل لمليون ريال بحلول غدا.', 'البنك سوف يكون قد وصل لمليون ريال بحلول غدا.', 'الصندوق قد وصل لمليون ريال بحلول غدا.'], 'correct' => 1],
        ['text' => 'The ferry will have left when you get there. اختر الترجمة الصحيحة للجملة:', 'options' => ['العبارة سوف تكون قد غادرت عندما (انت) تصل هناك.', 'العبارة سوف تكون قد غادرت عندما (انت) تصل هناك.', 'العبارة سوف تكون قد غادرت عندما (انت) تصل هناك.', 'العبارة سوف تكون قد غادرت عندما (انت) تصل هناك.'], 'correct' => 0], // All same in prompt, I'll take the first.
        ['text' => 'By this time, I will have listened to this podcast for the 30th time. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول هذا الوقت، انا استمعت الى هذا التسجيل للمرة الثلاثون.', 'بحلول هذا الوقت، انا استمع الى هذا التسجيل للمرة الثلاثون.', 'بحلول هذا الوقت، انا سوف أكون قد استمعت الى هذا التسجيل للمرة الثلاثون.', 'بحلول هذا الوقت، انا سوف أكون قد استمعت الى هذا التسجيل للمرة الثالثة.'], 'correct' => 2],
        ['text' => 'By next year, I will have travelled most of France. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول العام القادم، انا سوف أكون قد سافرت اغلب فرنسا.', 'العام الفائت، انا سوف أكون قد سافرت اغلب فرنسا.', 'بحلول العام القادم، انا سوف أكون قد سافرت كل فرنسا.', 'بحلول العام القادم، انا سوف أكون قد سافرت اغلب ايطاليا.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (خلال ستة شهور من الان، الكاتب سوف يكون قد نشر كتابه الجديد.):', 'options' => ['In sixteen months from now, the author will have published his new book.', 'In six months from now, the author will have published his new story.', 'In six months from now, the author will have published his new book.', 'In six months from now, the author will publish his new book.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة (منصور سوف يكون قد أنهى بناء بيته هذا الصيف.):', 'options' => ['Mansour will have finished building his house this summer.', 'Mansour will have finished building his club this summer.', 'Mansour will have finished building his house this winter.', 'Mansour will finish building his house this summer.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (خلال 24 ساعة، المحقق سوف يكون قد حقق في الجريمة.):', 'options' => ['In twenty-four hours, the detective will have investigated the crime.', 'In forty-two hours, the detective will have investigated the crime.', 'In twenty-four hours, the lawyer will have investigated the crime.', 'In twenty-four hours, the detective will be investigate the crime.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (ان شاء الله، انت سوف تكون قد حققت اهدافك بحلول السنة القادمة.):', 'options' => ['Insha’Allah, you will have achieved your goals by next year.', 'Insha’Allah, you will have achieved your goals by next month.', 'Insha’Allah, you will achieve your goals by next year.', 'Insha’Allah, she will have achieved your goals by next year.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (بحلول الأسبوع القادم، انت ما راح تكون قد ازلت جبيرة الكسر.):', 'options' => ['By next week, you will not have been removing the cast.', 'By next week, you will not have removed the cast.', 'By next week, you will not have put the cast.', 'By next week, you will not removed the cast.'], 'correct' => 1],
        ['text' => '(The bank won’t have deducted your monthly fees from your next salary) اختر الترجمة الصحيحة للجملة:', 'options' => ['البنك سوف لن يكون قد خصم رسومك الشهرية من راتبك القادم.', 'البنك سوف يكون قد خصم رسومك الشهرية من راتبك القادم.', 'البنك سوف لن يكون قد خصم رسومك الشهرية من راتبك القادم.', 'المؤسسة سوف لن تكون قد خصمت رسومك الشهرية من راتبك القادم.'], 'correct' => 0],
        ['text' => 'Within five seconds, your laptop will not have restarted. اختر الترجمة الصحيحة للجملة:', 'options' => ['خلال خمس دقائق، حاسوبك المحمول سوف لن يكون قد أعاد التشغيل.', 'خلال خمس ثواني، حاسوبك المحمول سوف لن يكون قد أعاد التشغيل.', 'خلال خمس ساعات، حاسوبك المحمول سوف لن يكون قد أعاد التشغيل.', 'خلال خمس دقائق، حاسوبك المحمول سوف لن يكون قد أنهى التشغيل.'], 'correct' => 1],
        ['text' => 'Will the system have restored information by tomorrow? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف يكون النظام قد خزن معلومات بحلول غدا؟', 'هل سوف يكون النظام قد حذف معلومات بحلول غدا؟', 'هل سوف يكون النظام قد خزن معلومات بحلول أمس؟', 'هل سوف يكون النظام قد خزن ملفات بحلول غدا؟'], 'correct' => 0],
        ['text' => 'Will the cleaner have cleaned the rug by tonight? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف يكون المنظف قد نظف الستارة بحلول الليلة؟', 'هل سوف يكون المنظف قد نظف السجادة بحلول الليلة؟', 'هل سوف يكون المنظف قد اشترى السجادة بحلول الليلة؟', 'هل سوف المنظف قد نظف السجادة بحلول الليلة؟'], 'correct' => 1],
        ['text' => 'Will you have modified the purchasing order when you come to work tomorrow? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف تكون قد عدلت طلب الشراء عندما اتيت (أنت) الى العمل غدا؟', 'هل سوف تكون قد عدلت طلب الشراء عندما تأتي (أنت) الى العمل غدا؟', 'هل سوف تكون قد عدلت طلب الشراء عندما يأتي (هو) الى العمل غدا؟', 'هل سوف تكون قد الغيت طلب الشراء عندما تأتي (أنت) الى العمل غدا؟'], 'correct' => 1],
        ['text' => '(By- the- two- grown- will- enormously- months - , -plants –have) اختر الترتيب الصحيح للجملة:', 'options' => ['By two months, the plants will have grown enormously.', 'By two plants, the months will have grown enormously.', 'Enormously, the plants will have grown By two months.', 'By two months the plants will have ,grown enormously.'], 'correct' => 0],
        ['text' => '( I -Have- semester -my- By –next- , - will- chosen –major) اختر الترتيب الصحيح للجملة:', 'options' => ['By next major, I will have chosen my semester.', 'By next semester, I will have chosen my major.', 'By next semester I will have , chosen my major.', 'I will have chosen by next semester, my major.'], 'correct' => 1],
        ['text' => 'By the time the party starts, I am going to have prepared all the food. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول الوقت الذي تبدأ فيه الحفلة راح أكون قد أعدت كل الطعام.', 'بحلول الوقت الذي تبدأ فيه الحفلة سوف أكون قد أعدت كل الطعام.', 'بحلول الوقت الذي تنتهي فيه الحفلة راح أكون قد أعدت كل الطعام.', 'بحلول الوقت الذي تبدأ فيه الحفلة ما راح أكون قد أعدت كل الطعام.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( بحلول الغد، راح نكون قد انهينا تفاصيل المشروع):', 'options' => ['By tomorrow, we are going to have finalized the details of the project.', 'By yesterday, we are going to have finalized the details of the project.', 'By tomorrow, we will finalize the details of the project.', 'By tomorrow, we are will have finalized the details of the project.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هي ما راح تكون قد افتتحت مشروعها الخاص في غضون العامين المقبلين.):', 'options' => ['She is going to have opened her own business within the next two years.', 'She isn’t going to have opened her own business within the next two years.', 'She will open her own business within the next two years.', 'She won’t open her own business within the next two years.'], 'correct' => 1],
        ['text' => 'Are they going to have moved to a new city by the time their father starts his job? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف يكونوا قد انتقلوا الى مدينة جديدة بحلول الوقت الذي يبدأ والدهم وظيفته؟', 'هل راح يكونوا قد انتقلوا الى مدينة جديدة بحلول الوقت الذي يبدأ والدهم وظيفته؟', 'هل راح يكونوا قد انتقلوا الى مدينة جديدة بحلول الوقت الذي يبدأ والدهم وظيفته.', 'هل راح يكونوا قد ينتقلوا الى مدينة جديدة بحلول الوقت الذي يبدأ والدهم وظيفته؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( في غضون شهرين، البراء راح يكون قد تعلم لغة جديدة.):', 'options' => ['In a couple of months, Albaraa is going to have learned a new language.', 'In a couple of months, Albaraa will learn a new language.', 'In a couple of months, Albaraa is going to be learning a new language.', 'In a couple of months, Albaraa will have learned a new language.'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة المستقبل التام (Future Perfect Translation)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 30,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    $quiz->questions()->detach();
    $letterMap = ['A', 'B', 'C', 'D'];
    foreach ($questionsData as $idx => $qData) {
        $props = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'points' => 1,
            'correct_answer' => 'A', // Default
        ];

        $props['option_a'] = $qData['options'][0] ?? null;
        $props['option_b'] = $qData['options'][1] ?? null;
        $props['option_c'] = $qData['options'][2] ?? null;
        $props['option_d'] = $qData['options'][3] ?? null;
        $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1076.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
