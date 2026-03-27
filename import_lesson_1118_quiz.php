<?php

/**
 * Script to import questions for Lesson ID 1118 (Comparison Translation)
 * php import_lesson_1118_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1118;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1118 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'The blue dress is the most beautiful dress I’ve ever seen. اختر الترجمة الصحيحة بالجملة:', 'options' => ['الفستان الأزرق أكثر فستان يكون جمالا رايته على الاطلاق.', 'الفستان الأزرق يكون أكثر فستان جمالا رايته على الاطلاق.', 'التنورة الزرقاء تكون أكثر تنورة جميلة رايتها على الاطلاق.', 'الفستان الأزرق يكون أقل فستان جمالا رايته على الاطلاق.'], 'correct' => 1],
        ['text' => 'The laptop is lighter than the computer. اختر الترجمة الصحيحة للجملة:', 'options' => ['اللاب توب يكون أخف من الكمبيوتر.', 'اللاب توب يكون أثقل من الكمبيوتر.', 'الكمبيوتر يكون أخف من اللاب توب.', 'الأي باد يكون أخف من الكمبيوتر.'], 'correct' => 0],
        ['text' => 'This exercise is easier than the previous exercise. اختر الترجمة الصحيحة للجملة:', 'options' => ['هذا التمرين يكون أصعب من التمرين السابق.', 'هذا التمرين يكون أسهل من التمرين السابق.', 'التمرين السابق يكون أسهل من هذا التمرين.', 'هذا السباق يكون أسهل من السباق السابق.'], 'correct' => 1],
        ['text' => 'This cake is more delicious than ice cream. اختر الترجمة الصحيحة للجملة:', 'options' => ['هذه المثلجات تكون لذيذة اكثر من الكيكة.', 'هذه الكيكة تكون لذيذة اكثر من المثلجات.', 'هذه الكيكة تكون لذيذة اقل من المثلجات.', 'هذه الكيكة لا تكون لذيذة اكثر من المثلجات.'], 'correct' => 1],
        ['text' => 'Your suitcase is as heavy as mine. اختر الترجمة الصحيحة للجملة:', 'options' => ['حقيبة السفر حقتك تكون مثل ثقل حقيبتي.', 'حقيبة السفر حقي تكون اثقل من حقيبتي.', 'حقيبتي اثقل من حقيبتك.', 'حقيبة السفر حقك تكون ثقيلة مثل حقيبتي.'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الأصدقاء يكونوا اهم من المال.):', 'options' => ['Friends are more important than money.', 'Money is more important than friends.', 'Friends are the most important money.', 'Friends are as important as money.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هذا الممثل يكون اكثر ممثل مشهور في اسبانيا.):', 'options' => ['This actor is the more famous actor in Spain.', 'This actor is the most famous teacher in Spain.', 'This actor is the most famous actor in Spain.', 'This actor the most famous actor in Spain.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هذه السيارة تكون اسرع سيارة قد قدتها (أنا) على الاطلاق.):', 'options' => ['This car is the fastest car I have ever driven.', 'This car is faster than car I have driven.', 'This car is the fast car I have ever driven.', 'This car is the most fast car I have ever driven.'], 'correct' => 0],
        ['text' => 'المقهى يكون اقرب الى بيتي من متجر الكتب. اختر الترجمة الصحيحة للجملة:', 'options' => ['The coffee shop is closer to my school than the bookstore.', 'The coffee shop is closer to my house than the bookstore.', 'The bookstore is closer to my house than the coffee shop.', 'The coffee shop is closest to my house than the bookstore.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (صوت والدي يكون مثل علو "عالي" صوت أخي):', 'options' => ['My dad’s voice is louder than my brother’s voice.', 'My dad’s voice is as loud as my sister’s voice.', 'My dad’s voice is as loud as my brother’s voice.', 'My dad’s voice is as quiet as my brother’s voice.'], 'correct' => 2],
        ['text' => '(stressful - as - Studying - major - for exams - is - as - completing - a - project) اختر الترتيب الصحيح للجملة:', 'options' => ['Studying for exams is as stressful as completing a major project.', 'Studying for exams is as stressful as completing major a project.', 'Studying for exams is as as completing stressful a major project.', 'Studying for project is as stressful as completing a major exams.'], 'correct' => 0],
        ['text' => '(restaurant - The - delicious - most - serves - the - food.) اختر الترتيب الصحيح للجملة:', 'options' => ['The restaurant serves the most food delicious.', 'The restaurant serves the delicious most food.', 'The restaurant serves the most delicious food.', 'The restaurant serves most delicious food the.'], 'correct' => 2],
        ['text' => '(the - Khalid - the - person - is - funniest - in - office.) اختر الترتيب الصحيح للجملة:', 'options' => ['Khalid is the funniest the person in office.', 'Khalid is the funniest person in the office.', 'The Khalid is funniest person in the office.', 'Khalid is person the funniest in the office.'], 'correct' => 1],
        ['text' => '(is - than - more - walking - exhausting - Running.) اختر الترتيب الصحيح للجملة:', 'options' => ['Running is more exhausting than walking.', 'Exhausting is more running than walking.', 'Running is more than exhausting walking.', 'Walking is more exhausting than running.'], 'correct' => 0],
        ['text' => '(hotter - is - Summer - spring - than.) اختر الترتيب الصحيح للجملة:', 'options' => ['Spring is hotter than summer.', 'Summer is than hotter spring.', 'Summer is hotter than spring.', 'Is summer hotter than spring.'], 'correct' => 2],
        ['text' => 'I just had the worst pain. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا قد كان لدي اسوا ألم.', 'انا كان لدي اسوا ألم.', 'انا لدي ألم اسوا.', 'هو قد كان لديه أسوا ألم.'], 'correct' => 0],
        ['text' => 'This trip is the most exciting of all. اختر الترجمة الصحيحة للجملة:', 'options' => ['هذه الرحلة تكون الأكثر متعة من البقية.', 'هذه الرحة تكون ممتعة أكثر من البقية.', 'باقي الرحلات ممتعة أكثر من هذه الرحلة.', 'هذه الرحلة كانت الأكثر متعة من البقية.'], 'correct' => 0],
        ['text' => 'China trains are faster than American’s trains. اختر الترجمة الصحيحة للجملة:', 'options' => ['قطارات الصين تكون أسرع من قطارات أمريكا.', 'قطارات أمريكا تكون أسرع من قطارات الصين.', 'قطارات الصين كانت أسرع من قطارات أمريكا.', 'طائرات أمريكا تكون أسرع من طائرات الصين.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( انا اعتقد ان الأغمق لون يكون الأسود.):', 'options' => ['I think the darkest color is blue', 'I think the darkest color is black', 'I think the lightest color is black', 'I think the darkest color was black'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( نحن لدينا اكثر الزبائن سعادة.):', 'options' => ['We have the happiest customers', 'We are the happiest customers', 'We has the happiest customers', 'We have the saddest customers'], 'correct' => 0],
        ['text' => '(Is- cooler - My - yours - fridge - than) اختر الترتيب الصحيح للجملة:', 'options' => ['My fridge is than cooler yours', 'My fridge is cooler than yours', 'My cooler than fridge is yours', 'My yours is cooler than fridge'], 'correct' => 1],
        ['text' => '(delicious - we – Their - croissant - is - one - more - than - the - make.) اختر الترتيب الصحيح للجملة:', 'options' => ['Their croissant is more delicious than we make the one.', 'Their croissant is delicious more than the one we make.', 'Their croissant is more delicious than the one we make.', 'Their croissant is the one more delicious than we make.'], 'correct' => 2],
        ['text' => '(is - Our - current - than - friendlier - previous - neighbor - the - one) اختر الترتيب الصحيح للجملة:', 'options' => ['Our current neighbor is friendlier than the previous one', 'Our current neighbor is friendlier the previous than one', 'Our current neighbor is than friendlier the previous one', 'Our current neighbor friendlier is than the previous one'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة المقارنة (Comparison Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1118.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
