<?php

/**
 * Script to import questions for Lesson ID 1082 (Future Perfect Continuous Translation)
 * php import_lesson_1082_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1082;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1082 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'By next quarter, Saleh will have been investing in stocks for seventeen years. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول الربع القادم، صالح راح يكون صارله يستثمر في الاسهم لمدة 17 سنة.', 'بحلول الربع القادم، صالح راح يكون صارله يستثمر في الاسهم لمدة 70 سنة.', 'بحلول الربع القادم، صالح استثمر في الاسهم لمدة 17 سنة.', 'بحلول الربع الفائت، صالح سوف يستثمر في الاسهم لمدة 17 سنة.'], 'correct' => 0],
        ['text' => 'By the time the plane departs, the maintenance team will have been checking the plane for half an hour. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول الوقت الذي تغادر فيه الطائرة، فريق الصيانة سوف يتفحص الطائرة لمدة نصف ساعة.', 'بحلول الوقت الذي تغادر فيه الطائرة، فريق الصيانة راح يكون صارله يتفحص الطائرة لمدة نصف ساعة.', 'بحلول الوقت الذي تغادر فيه السيارة، فريق الصيانة راح كان صارله يتفحص الطائرة لمدة نصف ساعة.', 'بحلول الوقت الذي تغادر فيه الطائرة، فريق الصيانة راح يكون صارله يتفحص الطائرة لمدة ساعة.'], 'correct' => 1],
        ['text' => 'By the time we leave school the teacher will have been checking the exam since morning. اختر الترجمة الصحيحة للجملة:', 'options' => ['بحلول الوقت الذي نغادر(نحن) المدرسة، السكرتيرة سوف تصحح الامتحان منذ الصباح.', 'بحلول الوقت الذي غادروا(هم) المدرسة، المعلمة سوف تكون تصحح الامتحان منذ الصباح.', 'بحلول الوقت الذي نغادر(نحن) المدرسة، المعلمة راح تكون صارلها تصحح الامتحان منذ الصباح.', 'بحلول الوقت الذي نغادر(نحن) المدرسة، المعلمة صححت الامتحان منذ الصباح.'], 'correct' => 2],
        ['text' => 'The flight attendant will have been helping passengers for several hours by the time we arrive. اختر الترجمة الصحيحة للجملة:', 'options' => ['مضيفة الطيران راح تكون صارلها تساعد الركاب لمدة عدة ساعات بحلول الوقت الذي نصل (نحن).', 'كابتن الطيارة سوف تساعد الركاب لمدة عدة ساعات بحلول الوقت الذي نصل (نحن).', 'مضيفة الطيران ساعدت الركاب لمدة عدة ساعات بحلول الوقت الذي نصل (نحن).', 'مضيفة الطيران سوف تساعد الركاب لمدة عدة أيام بحلول الوقت الذي نصل (نحن).'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (انا ما راح يكون صارلي عايش في مونتينيغرو بحلول العام القادم.):', 'options' => ['I will not have been living in Montenegro by next month.', 'I will not have been living in Montenegro by next year.', 'I will have been living in Montenegro by next year.', 'I will not live in Montenegro by next year.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (عند الساعة الثالثة مساء هو راح يكون صارله يعمل لمدة ساعات.):', 'options' => ['At 3:00 pm, he will not have been working for hours.', 'At 3:00 am, he will not have been working for hours.', 'At 3:00 pm, he will not have been working since hours.', 'At 3:00 pm, she will not have been working for hours.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (فاطمة راح يكون صارلها تتسوق لمدة نصف ساعة بحلول الوقت الذي تصل انت.):', 'options' => ['Fatima was shopping for half an hour by the time you arrive.', 'Fatima will have been shopping for half an hour by the time you arrive.', 'Fatima will have been shopping for half an hour by the time you leave.', 'Fatima will have shopping for half an hour by the time you arrive.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (بحلول الليلة، آلة الغسل ما راح يكون صارلها تعمل طول اليوم.):', 'options' => ['By tonight, the washing machine will have been running all day.', 'By tonight, the washing machine won’t have been running all day.', 'By tonight, the washing machine willn’t have been running all day.', 'By tonight, the washing machine will not run all day.'], 'correct' => 1],
        ['text' => 'Will the businessmen have been conducting a meeting for 3 hours? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل رجال الاعمال سوف يكونوا ينهوا الاجتماع لمدة 3 ساعات؟', 'هل رجال الاعمال راح يكون صارلهم يعقدوا الاجتماع لمدة 3 ساعات؟', 'هل رجال الاعمال سوف يعقدوا الدورة لمدة 3 ساعات؟', 'هل رجال الاعمال عقدوا الاجتماع لمدة 3 ساعات؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (بحلول غدا هل المحاسب راح يكون صارله يدقق السجلات لمدة يومين؟):', 'options' => ['By tomorrow, will the accountant have been auditing the books for 2 days.', 'By tomorrow, will the accountant have been auditing the books for 2 days?', 'By tomorrow, will the accountant has been auditing the books for 2 days?', 'By tomorrow, will the accountant audit the books for 2 days?'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل سائق الشاحنة راح يكون صارله يسوق لمدة 8 ساعات بحلول الـ 4 مساء؟):', 'options' => ['Will the truck driver have been driving for 8 hours by 4:00 pm?', 'Will the truck driver have been driving for 8 hours by 4:00 am?', 'Will the truck driver have been driving for 8 minutes by 4:00 pm?', 'Will the truck driver been driving for 8 hours by 4:00 pm?'], 'correct' => 0],
        ['text' => 'By midnight, will the fox have been waiting for several hours to come out? اختر الترجمة الصحيحة للسؤال:', 'options' => ['بحلول منتصف النهار، هل سوف يكون الثعلب ينتظر لمدة عدة ساعات ليخرج؟', 'بحلول منتصف الليل، هل راح يكون الثعلب صارله ينتظر لمدة عدة ساعات ليخرج؟', 'بحلول منتصف الليل، هل سوف يكون الذئب ينتظر لمدة عدة ساعات ليخرج؟', 'بحلول منتصف الليل، هل سوف كان الثعلب ينتظر لمدة عدة أيام ليخرج؟'], 'correct' => 1],
        ['text' => '(month - company - establishing - , - been - Next - I - since - will - have - my - 2020) اختر الترتيب الصحيح للجملة:', 'options' => ['Next, 2020 I will have been establishing my company since month.', 'Next month, I will have been establishing my company since 2020.', 'Next month, I have will been establishing my company since 2020.', 'Next month I will, my company have been establishing since 2020.'], 'correct' => 1],
        ['text' => '(have - specialist –The- drone- will- for - hours – drone- been –flying- his -2) اختر الترتيب الصحيح للجملة:', 'options' => ['The specialist drone will have been flying his drone for 2 hours.', 'The drone specialist will have been flying his drone for 2 hours.', 'The drone specialist will have been flying drone his for 2 hours.', 'The drone specialist will have been flying his drone for hours 2.'], 'correct' => 1],
        ['text' => '(By - the - cashier - end -of -work- , - the -have- 5 - standing - will - been -hours) اختر الترتيب الصحيح للجملة:', 'options' => ['By the end of work the cashier will, been have standing for 5 hours.', 'By the end, of cashier the work will have been standing for 5 hours.', 'By the end of work, the cashier will have been standing for 5 hours.', 'By the end of work the cashier will have been, for standing 5 hours.'], 'correct' => 2],
        ['text' => '(the- visiting- Will -end principle- classes- have- day- been -all -students’ -?- by- the -of -the) اختر الترتيب الصحيح للسؤال:', 'options' => ['Will the principle have been visiting all students’ classes by the end of the day?', 'Will the principle have been visiting all classes students’ by the end of the day?', 'Will the students’ have been visiting all principle classes by the end of the day?', 'The principle will have been visiting all students’ classes by the end of the day?'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة المستقبل التام المستمر (Future Perfect Continuous Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1082.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
