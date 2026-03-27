<?php

/**
 * Script to import questions for Lesson ID 1068 (Future Continuous Translation)
 * php import_lesson_1068_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1068;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1068 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'They will be using the cottage this weekend. اختر الترجمة الصحيحة للجملة:', 'options' => ['هم كانوا يستخدموا الكوخ (البيت الريفي) في عطلة نهاية الاسبوع.', 'هم سوف يكونوا يستخدموا الكوخ (البيت الريفي) في عطلة نهاية الاسبوع.', 'هم سوف يكونوا يستخدموا الكوخ (البيت الريفي) هذا الشهر.', 'هم يستخدموا الكوخ (البيت الريفي) هذا الأسبوع.'], 'correct' => 1],
        ['text' => 'At noon tomorrow, I’ll be relaxing on a beach somewhere. اختر الترجمة الصحيحة للجملة:', 'options' => ['وقت الظهيرة غدا، انا سوف أكون مسترخيا على الشاطئ في مكان ما.', 'وقت المساء غدا، انا سوف أكون مسترخيا على الشاطئ في مكان ما.', 'وقت الظهيرة غدا، انا استرخيت على الشاطئ في مكان ما.', 'وقت الظهيرة بعد غدا، انا سوف أكون مسترخيا على الشاطئ في مكان ما.'], 'correct' => 0],
        ['text' => 'I will be taking my daughter to school at that time. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا سوف اخذ ابني الى المدرسة في ذلك الوقت.', 'انا سوف اخذ ابنتي الى النادي في ذلك الوقت.', 'انا سوف اخذ ابنتي الى المدرسة في ذلك الوقت.', 'انا سوف احضر ابنتي من المدرسة في ذلك الوقت.'], 'correct' => 2],
        ['text' => 'Dhafir will be arriving late. He’s stuck in traffic. اختر الترجمة الصحيحة للجملة:', 'options' => ['ظافر سوف يصل متأخرا. هو يكون عالق في الزحمة.', 'ظافر سوف يصل مبكرا. هو يكون عالق في الزحمة.', 'ظافر يصل متأخرا. هو يكون عالق في الزحمة.', 'ظافر وصل متأخرا. هو يكون عالق في الزحمة.'], 'correct' => 0],
        ['text' => 'By the time you arrive, we will be having dinner. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول الوقت الذي تذهب فيه(انت) سوف نكون نتناول العشاء.', 'بحلول الوقت الذي تصل فيه(انت) سوف نكون نتناول العشاء.', 'بحلول الوقت الذي تصل فيه(انت) سوف نكون نتناول الغداء.', 'بحلول الوقت الذي تصل فيه(انت) سوف نكون نتناول الفطور.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( عندما انت تصل انا سأكون اعمل في الكراج.):', 'options' => ['When you arrive, I’ll be working in the garage.', 'When you arrive, I’ll be work in the garage.', 'When you arrive, I’ll be working in the garden.', 'When you leave, I’ll be working in the garage.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( بحلول عام 2030 العلماء سوف يكونوا يخترعون تكنولوجيات جديدة.):', 'options' => ['By the year 2030, teachers will be creating new technologies.', 'By the year 2030, scientists will be creating new technologies.', 'By the year 2030, scientists will be creating new computers.', 'By the year 2030, scientists will be create new technologies.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( الأطفال سوف يكونون يذهبون للحديقة للعب.):', 'options' => ['The kids will be going to the garden to play.', 'The kids will be going to the garden to swim.', 'Parents will be going to the garden to play.', 'The kids will be going to the beach to play.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (اذا انت تحتاج ان تتصل بي انا سوف أكون في الفندق حتى الجمعة.):', 'options' => ['If you need to contact me, I will be staying at the hotel until Sunday.', 'If you need to contact me, I will be staying at the hotel until Friday.', 'If you will need to contact me, I will be staying at the hotel until Friday.', 'If you need to contact me, I will be stay at the hotel until Friday.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( بحلول عام 2050، العلماء سوف يكونوا يجربوا ليجدوا طريقة ليعيشوا خارج كوكب الأرض.):', 'options' => ['By the year 2050, scientists will be trying to find a way to live inside the earth.', 'By the year 2050, doctors will be trying to find a way to live outside the earth.', 'By the year 2050, scientists will be trying to find a way to live outside the earth.', 'By the year 2050, scientists will try to find a way to live outside the earth.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هي سوف لن تكون تؤدي نفس الوظيفة بعد خمس سنوات من الان ):', 'options' => ['She will not be doing the same job after five years from now.', 'She will not do the same job after five years from now.', 'She will not be doing a different job after five years from now.', 'She not will be doing the same job after five years from now.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (انا سوف لن اكون اضيع وقتي بينما انت سوف تكون تضيع حقك (وقتك).):', 'options' => ['I won’t be wasting my time while you are wasting you.', 'I won’t be wasting my time while you will be wasting yours.', 'I won’t be spending my time while you are wasting yours.', 'I will be wasting my time while you are wasting yours.'], 'correct' => 1],
        ['text' => 'I won’t be watching while you will performing. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا سوف لن أكون اشاهد بينما انت سوف تكون تؤدي التمثل.', 'انا سوف أكون اشاهد بينما انت سوف تؤدي التمثل.', 'انا ما شاهدت بينما انت سوف تؤدي التمثل.', 'انا سوف لن أكون اشاهد بينما انت أديت التمثيل.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (هم سوف لن يكونوا يتساءلون اين انت تكون.):', 'options' => ['They will not be wondering where you are.', 'They will not be wonder where you are.', 'They will not be wondering who you are.', 'They not will be wondering where you are.'], 'correct' => 0],
        ['text' => 'The employee will not be calling you. اختر الترجمة الصحيحة للجملة:', 'options' => ['الموظف سوف يكون يتصل بك.', 'الموظف سوف لن يكون يتصل بك.', 'هل سوف لن يكون يتصل بك؟', 'الموظف لا يتصل بك.'], 'correct' => 1],
        ['text' => 'Will you be taking work’s car tomorrow for loading? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف تكون تأخذ سيارة العمل غدا للتحميل؟', 'هل أخذت سيارة العمل غدا للتحميل؟', 'هل تأخذ سيارة العمل كل يوم للتحميل؟', 'هل سوف يأخذوا سيارة العمل غدا للتحميل؟'], 'correct' => 0],
        ['text' => 'Will you be discussing that with chief? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف تكون تناقش هذا مع الموظف؟', 'هل سوف تكون تناقش هذا مع المسؤول؟', 'هل سوف تكون تناقش هذا مع العامل؟', 'هل سوف تكون تقرأ هذا مع المسؤول؟'], 'correct' => 1],
        ['text' => 'Will She be translating for the ambassador? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف يكون هو يترجم للسفير؟', 'هل سوف تكون هي تترجم للسفير؟', 'هل سوف تكون هي تنقل للسفير؟', 'هل سوف تكون هي تترجم للمعلم؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هل نحن سوف نكون نجازف بوظائفنا الجديدة؟):', 'options' => ['Will we be risking their new jobs?', 'Will we be risking our new jobs?', 'Are will we be risking their new jobs?', 'Will we be risking their new jobs.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل سوف تكون تبحث عن مرآة جانبية لسيارتك؟):', 'options' => ['Will you be looking for a side mirror for your car?', 'Will you be looking for a seat for your car?', 'Will you be looking for a car?', 'Will you look for a side mirror for your car?'], 'correct' => 0],
        ['text' => '(Boat- filling -You- will- gasoline- be -the- with) اختر الترتيب الصحيح للجملة:', 'options' => ['You will be filling the gasoline with boat .', 'You will be filling the boat with gasoline .', 'You will filling be the boat with gasoline .', 'You will be filling with the boat gasoline .'], 'correct' => 1],
        ['text' => '(Using- be- The- the- firefighter -will -hydrant.) اختر الترتيب الصحيح للجملة:', 'options' => ['Using the firefighter will be the hydrant. ', 'The hydrant will be using the firefighter. ', 'The firefighter be will using the hydrant. ', 'The firefighter will be using the hydrant.'], 'correct' => 3],
        ['text' => '(me- visiting- will- this –my- friends- be - weekend) اختر الترتيب الصحيح للجملة:', 'options' => ['Me will be visiting my friends this weekend.', 'My friends will be visiting me this weekend.', 'My friends will visiting be me this weekend.', 'My will friends be visiting me this weekend.'], 'correct' => 1],
        ['text' => '(polishing –The- will –carpenter- be -wood.) اختر الترتيب الصحيح للجملة:', 'options' => ['The carpenter will be polishing wood.', 'The wood will be polishing carpenter.', 'The carpenter be will polishing wood.', 'Be carpenter will the polishing wood.'], 'correct' => 0],
        ['text' => '(closing -be -the- I- will -gate.) اختر الترتيب الصحيح للجملة:', 'options' => ['The will be closing I.', 'I will be closing the gate.', 'I be will closing the gate.', 'I will be the closing gate.'], 'correct' => 1],
        ['text' => 'They are going to be organizing community events throughout the year. اختر الترجمة الصحيحة للجملة:', 'options' => ['هم راح يكونوا ينظمون احداث مجتمعية على مدار (طوال) العام.', 'هم سوف يكونوا ينظمون احداث مجتمعية على مدار (طوال) العام.', 'هم راح يكونوا قد نظموا احداث مجتمعية على مدار (طوال) العام.', 'هم سوف يكونوا قد نظموا احداث مجتمعية على مدار (طوال) العام.'], 'correct' => 0],
        ['text' => 'He is going to be working late tonight on a special project. اختر الترجمة الصحيحة للجملة:', 'options' => ['هو سوف يكون يعمل الليلة في مشروع مميز.', 'هو راح يكون يعمل الليلة في مشروع مميز.', 'هو راح يكون قد عمل الليلة في مشروع مميز.', 'هو سوف يكون قد عمل الليلة في مشروع مميز.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( نحن راح نكون نجدد مطبخنا خلال الصيف.):', 'options' => ['We are going to be renovating our kitchen through the summer.', 'We will renovate our kitchen through the summer.', 'We are going to have renovated our kitchen through the summer.', 'We will have renovated our kitchen through the summer.'], 'correct' => 0],
        ['text' => 'By the end of the month, they aren’t going to be launching a new product. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول نهاية الشهر، هم سوف لن يكونوا يطلقون منتج جديد.', 'بحلول نهاية الشهر، هم ما راح يكونوا يطلقون منتج جديد.', 'بحلول الشهر القادم، هم ما راح يكونوا يطلقون منتج جديد.', 'بحلول نهاية الشهر، هم راح يكونوا يطلقون منتج جديد.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل راح يكون يقود مجموعة من الموظفين الجدد في الشركة؟):', 'options' => ['Will he be leading a group of new employees at the company?', 'Will he lead a group of new employees at the company?', 'Is he going to be leading a group of new employees at the company?', 'Is he going to be lead a group of new employees at the company.'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة المستقبل المستمر (Future Continuous Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1068.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
