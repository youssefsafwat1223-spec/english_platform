<?php

/**
 * Script to import questions for Lesson ID 1089 (Imperative Sentences Translation)
 * php import_lesson_1089_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1089;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1089 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Boil the milk for at least two to three minutes. اختر الترجمة الصحيحة للجملة:', 'options' => ['اغلي الحليب على الأقل دقيقتان او ثلاثة دقائق.', 'اغلي الحليب على الأقل ثانيتان او ثلاثة ثواني.', 'اشرب الحليب على الأقل دقيقتان او ثلاثة دقائق.', 'اغلي الحليب دقيقتان او ثلاثة دقائق.'], 'correct' => 0],
        ['text' => 'Finish the assignment by tomorrow. اختر الترجمة الصحيحة للجملة:', 'options' => ['انهي الاختبار بحلول غدا.', 'انهي الواجب بحلول غدا.', 'انهي الواجب بحلول امس.', 'ينهي الواجب بحلول غدا.'], 'correct' => 1],
        ['text' => 'Please don’t litter. اختر الترجمة الصحيحة للجملة:', 'options' => ['من فضلك لا ترمي القمامة.', 'من فضلك لا تجري.', 'من فضلك ارمي القمامة.', 'اسف لا ترمي القمامة.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( لا تنسى الشخص الذي يحبك.):', 'options' => ['Never forget the person which loves you', 'Never forgets the person who loves you', 'Never forget the person who loves you', 'Doesn’t forget the person who loves you'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( لا تمزح على بؤس الناس.):', 'options' => ['Don’t joke about people’s misery.', 'joke about people’s misery.', 'Please joke about people’s misery.', 'Don’t joke about people’s happiness.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( لا تسرع.):', 'options' => ['Speed up', 'Doesn’t speed.', 'Don’t speed up.', 'Don’t speed down.'], 'correct' => 2],
        ['text' => 'Don’t ever call me a loser. اختر الترجمة الصحيحة للجملة:', 'options' => ['لا تسميني خاسر ابدا.', 'تسميني خاسر دائما.', 'سميني رابح ابدا.', 'لا تكتبلي خاسر ابدا.'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة للجملة (لا تتحرك!):', 'options' => ['Move!', 'doesn’t move!', 'Don’t move!', 'Don’t stop!'], 'correct' => 2],
        ['text' => 'Open the blue box and connect the two wires. اختر الترجمة الصحيحة للجملة:', 'options' => ['لا تفتح الصندوق الأزرق واربط السلكين.', 'افتح الصندوق الأزرق واربط السلكين.', 'افتح الصندوق الأخضر واربط السلكين.', 'افتح الصندوق الأزرق وفك السلكين.'], 'correct' => 1],
        ['text' => 'Please be waiting when we arrive. اختر الترجمة الصحيحة للجملة:', 'options' => ['من فضلك كن منتظرا عندما يصلوا (هم).', 'من فضلك كن متحمسا عندما نصل (نحن).', 'من فضلك كن منتظرا عندما نصل (نحن).', 'من فضلك لا تكن منتظرا عندما نصل (نحن).'], 'correct' => 2],
        ['text' => 'الترجمة الصحيحة للجملة ( احد ما يرد على الهاتف من فضلكم.):', 'options' => ['Somebody answer the phone, please.', 'Somebody open the phone, please.', 'Somebody close the phone, please.', 'Somebody open the door, please.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( خذوا مقاعدكم) ولكن هنا فيه تأكيد شديد( إصرار) على فعل الامر:', 'options' => ['Does take your seats.', 'take your seats.', 'Do take your seats.', 'Do take their seats.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (سامحني) ولكن الامر فيه تأكيد شديد(إصرار) على فعل الامر:', 'options' => ['Do forgive her', 'Forgive me', 'Don’t forgive me', 'Do forgive him'], 'correct' => 0], // Matching user prompt's options
        ['text' => 'Do try to keep the noise down, gentlemen. اختر الترجمة الصحيحة للجملة:', 'options' => ['حاولوا ان تخفضوا الضوضاء أيها السادة. (امر عادي)', 'حاولوا ان تخفضوا الضوضاء أيها السادة. ( تأكيد شديد ( إصرار) على فعل الامر)', 'حاولوا ان ترفعوا الضوضاء أيها السادة.', 'لا تخفضوا الضوضاء أيها السادة.'], 'correct' => 1],
        ['text' => 'Call me when you get there. اختر الترجمة الصحيحة للجملة:', 'options' => ['اتصل به عندما تكون هناك (تصل).', 'اتصل بي عندما تكون هناك (تصل).', 'اتصل بي عندما يكون هناك (يصل).', 'اتصل بها عندما نكون هناك (نصل).'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( من فضلك مرر الملح والفلفل الأسود.):', 'options' => ['Please pass the salt and pepper.', 'Please pass the milk and pepper.', 'Please pass the sugar and pepper.', 'Please pass the salt and spice.'], 'correct' => 0],
        ['text' => 'Please check your phone. اختر الترجمة الصحيحة للجملة:', 'options' => ['من فضلك تأكد من تلفونك.', 'من فضلك تأكد من بريدك الالكتروني.', 'من فضلك أصلح تلفونك.', 'من فضلك احضر تلفونك.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( املأ النموذج وأرسله قبل الاثنين.):', 'options' => ['املأ النموذج وأرسله بعد الاثنين.', 'املأ النموذج وأرسله قبل الاثنين.', 'املأ النموذج واطبعه قبل الاثنين.', 'املأ الاختبار وأرسله قبل الاثنين.'], 'correct' => 1],
        ['text' => '(Window- leave- Don’t -the- open) اختر الترتيب الصحيح للجملة:', 'options' => ['Don’t the window leave open.', 'leave Don’t the window open.', 'Don’t leave the window open.', 'Don’t open the window leave.'], 'correct' => 2],
        ['text' => '(home – before- Come -the –sunset) اختر الترتيب الصحيح للجملة:', 'options' => ['Come home before the sunset.', 'Come sunset before the home.', 'Home come before the sunset.', 'Before Come home the sunset.'], 'correct' => 0],
        ['text' => '(starts -off –your- Switch- mobile-prayer- before- the- meeting.) اختر الترتيب الصحيح للجملة:', 'options' => ['Starts your mobile before the prayer switch off.', 'Switch off your mobile before the prayer starts.', 'Switch your mobile before the prayer starts off.', 'Before Switch off your mobile the prayer starts.'], 'correct' => 1],
        ['text' => '(us – lunch- after- join- for- work) اختر الترتيب الصحيح للجملة:', 'options' => ['After join us for lunch work.', 'Join for lunch us after work.', 'Join us for lunch after work.', 'Work us for lunch after join.'], 'correct' => 2],
        ['text' => 'Don’t overthink your life. اختر الترجمة الصحيحة للجملة:', 'options' => ['لا تبالغ التفكير في حياتك.', 'لا تكره حياتك.', 'لا تنهي حياتك.', 'من فضلك بالغ التفكير في حياتك.'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة جمل الأمر (Imperative Sentences Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1089.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
