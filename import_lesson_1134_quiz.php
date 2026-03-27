<?php

/**
 * Script to import questions for Lesson ID 1134 (Delexical Verbs Grammar)
 * php import_lesson_1134_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1134;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1134 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى (Delexical verbs)؟', 'options' => ['الأفعال اللازمة', 'الأفعال المركبة', 'الأفعال الاصطلاحية', 'الأفعال المتعدية'], 'correct' => 2],
        ['text' => 'الأفعال الاصطلاحية هي الأفعال التي تعبر عن معنى وحدها و(لا) تعتمد على الاسم او الصفة التي بعدها او ملتصقة بها.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر من الأفعال الاصطلاحية (Delexical verbs):', 'options' => ['sing – run – make – travel – go – do – wonder', 'play – take – make – give – go – buy - get', 'Have – take – visit – give – cook – do - get', 'Have – take – make – give – go – do – get'], 'correct' => 3],
        ['text' => '(I __ breakfast at 8 o’clock) اختر انسب فعل لجملة (انا اتناول الفطور):', 'options' => ['Get', 'Have', 'Make', 'Go'], 'correct' => 1],
        ['text' => '(I __ a break) اختر انسب فعل لجملة (انا كان لدي استراحة):', 'options' => ['Went', 'Had', 'Made', 'Did'], 'correct' => 1],
        ['text' => '(I __ a shower every day) اختر انسب فعل لجملة (انا اخذ دوش):', 'options' => ['Have', 'Take', 'Get', 'ا + ب'], 'correct' => 3],
        ['text' => 'اختر مجموعة الأسماء التي تقبل استخدام الفعلين (Have او take) معها وتعتبر صحيحة:', 'options' => ['Holiday – shower – argument – look – wash', 'picture – shower – chance – look – wash', 'Holiday – shower – break – look – wash', 'Holiday – snack – break – look – discussion'], 'correct' => 2],
        ['text' => '(__ me some information) اختر انسب فعل لجملة (اعطني بعض المعلومات):', 'options' => ['Take', 'Give', 'Have', 'Make'], 'correct' => 1],
        ['text' => '(He came and _ a knock at the door) اختر انسب فعل لجملة (هو اتى وطرق على الباب):', 'options' => ['Took', 'Gave', 'Had', 'Made'], 'correct' => 1],
        ['text' => '(I __ noise on yesterday) اختر انسب فعل لجملة (انا عملت ازعاج بالأمس):', 'options' => ['Took', 'Gave', 'Had', 'Made'], 'correct' => 3],
        ['text' => 'ما الترجمة الصحيحة لجملة (انا دائما ابذل جهد)؟', 'options' => ['I always give an effort', 'I always make an effort', 'I always have an effort', 'I always take an effort'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة لجملة (انا اجري مكالمة هاتفية الان)؟', 'options' => ['I am getting a phone call now.', 'I am making a phone call now.', 'I am running a phone call.', 'I am taking a phone call now.'], 'correct' => 1],
        ['text' => 'ما الذي يأتي بعد الفعل (Go) غالبا؟', 'options' => ['Infinitive (فعل مجرد)', 'To + infinitive', 'Gerund (v1 + ing)', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => '(I go _ every week) اختر الكلمة المناسبة:', 'options' => ['Fish', 'Fishing', 'To fish', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(I __ shopping every Thursday) اختر انسب فعل:', 'options' => ['Take', 'Have', 'Go', 'Make'], 'correct' => 2],
        ['text' => 'ما هي الجملة البديلة و التي في نفس الوقت تحمل نفس المعنى لجملة (انا امشط شعري I brush my hair)؟', 'options' => ['I have my hair', 'I go my hair', 'I do my hair', 'I get my hair'], 'correct' => 2],
        ['text' => 'الفعل المناسب قبل كلمة (غلطة A mistake):', 'options' => ['Have', 'Get', 'Make', 'Go'], 'correct' => 2],
        ['text' => 'الفعل (Get) يحمل الكثير من المعاني، فما المعنى الذي يحمله ان اتى قبل اسم مثل (get the key)؟', 'options' => ['يحصل على – يجلب', 'يصبح', 'يكون', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اذا اتى بعد الفعل (Get) صفة مثل (excited متحمس):', 'options' => ['يحصل على – يجلب', 'يصبح', 'يكون', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'متى يصبح الفعل (Get) بمعنى أفعال الكينونة (Be)؟', 'options' => ['عندما يأتي بعدها اسم', 'عندما يأتي بعدها صفة', 'اذا اتى بعدها تصريف ثالث للفعل', 'عندما يأتي بعدها حرف جر'], 'correct' => 2],
        ['text' => 'الترجمة الصحيحة لجملة (كسر يعني قد تم كسره):', 'options' => ['went broken', 'Had broken', 'Got broken', 'Made broken'], 'correct' => 2],
        ['text' => 'لا يمكن ان يأتي حرف جر بعد الفعل (Get).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        
        [
            'text' => 'صل ما بين كل فعل مركب ومعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Get up', 'right' => 'انهض'],
                ['left' => 'Get along', 'right' => 'انسجم'],
                ['left' => 'Get down', 'right' => 'انخفض \ انزل'],
                ['left' => 'Get on', 'right' => 'اركب'],
                ['left' => 'Get over', 'right' => 'تخطى'],
                ['left' => 'Get out', 'right' => 'اخرج'],
            ]
        ],

        ['text' => 'وجود الفعل (get) بعد (Have) لا يؤثر في المعنى، فمثلا (I have got a problem) هي نفسها (I have a problem).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الأفعال الاصطلاحية (Delexical Verbs Grammar)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 45,
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

        if ($props['question_type'] === 'drag_drop') {
            $props['matching_pairs'] = $qData['matching_pairs'];
            $props['correct_answer'] = 'A';
        } else {
            $props['option_a'] = $qData['options'][0] ?? null;
            $props['option_b'] = $qData['options'][1] ?? null;
            $props['option_c'] = $qData['options'][2] ?? null;
            $props['option_d'] = $qData['options'][3] ?? null;
            $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1134.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
