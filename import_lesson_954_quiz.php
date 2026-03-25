<?php

/**
 * Script to import questions for Lesson ID 954
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_954_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    // 1. Find the lesson
    $lessonId = 954;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 954 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'ما معنى (أسماء الإشارة )؟',
            'options' => ['Relative pronouns', 'Demonstrative pronouns', 'Reflexive pronouns', 'Possessive pronouns'],
            'correct' => 1, // Demonstrative pronouns
        ],
        [
            'text' => 'ما هو استخدام أسماء الإشارة؟',
            'options' => ['تحدد الفاعل', 'تصف الفعل', 'الإشارة للشخص او للشيء', 'تربط بين الجمل'],
            'correct' => 2, // الإشارة للشخص او للشيء
        ],
        [
            'text' => 'لا تتأثر أسماء الإشارة بعدد الشخص او الشي المشار اليه.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        [
            'text' => 'ما الشروط التي تحكم تحديد أسماء الإشارة المناسبة؟',
            'options' => ['ا- العدد (المفرد والجمع)', 'ب -الموقع من الجملة (فاعل \ مفعول به)', 'ج- القريب والبعيد', 'د- ا + ج'],
            'correct' => 3, // ا + ج
        ],
        [
            'text' => 'ما اسم الإشارة الذي يستخدم للمفرد القريب؟',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 0, // This
        ],
        [
            'text' => 'ما اسم الإشارة الذي يستخدم للمفرد البعيد؟',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 1, // That
        ],
        [
            'text' => 'ما اسم الإشارة الذي يستخدم للجمع القريب؟',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 2, // These
        ],
        [
            'text' => 'ما اسم الإشارة الذي يستخدم للجمع البعيد؟',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 3, // Those
        ],
        [
            'text' => 'صل كل اسم من أسماء الإشارة بمعناه؟',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "this", "right" => "هذا \ هذه"],
                ["left" => "that", "right" => "ذلك \ تلك"],
                ["left" => "these", "right" => "هؤلاء"],
                ["left" => "those", "right" => "أولئك"]
            ],
            'points' => 4,
        ],
        [
            'text' => 'ما هو اللفظ الصحيح لأسماء الإشارة (These\ those)؟',
            'options' => ['ذس \ذوس', 'ذيز \ ذوز', 'ذيس \ ذوس', 'لا شيء مما سبق'],
            'correct' => 1, // ذيز \ ذوز
        ],
        [
            'text' => 'اخر حرف في اسم الإشارة (This) ينطق ( ز)؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        [
            'text' => 'ما هو (verb to be) الذي يأتي بعد أسماء الإشارة (This \ that) في زمن المضارع؟',
            'options' => ['Am', 'Was', 'Is', 'Are'],
            'correct' => 2, // Is
        ],
        [
            'text' => 'ما هو (verb to be) الذي يأتي بعد أسماء الإشارة (This \ that) في زمن الماضي؟',
            'options' => ['were', 'Was', 'Is', 'Are'],
            'correct' => 1, // Was
        ],
        [
            'text' => 'ما هو (verb to be) الذي يأتي بعد أسماء الإشارة (Those \ these) في زمن الماضي؟',
            'options' => ['were', 'Was', 'Is', 'Are'],
            'correct' => 0, // were
        ],
        [
            'text' => 'ما هو (verb to be) الذي يأتي بعد أسماء الإشارة (Those \ these) في زمن المضارع؟',
            'options' => ['were', 'Was', 'Is', 'Are'],
            'correct' => 3, // Are
        ],
        [
            'text' => 'عند تكوين سؤال يحتوي على اسم إشارة فإننا نكتب الفعل المساعد أولا ثم اسم الإشارة؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // صح
        ],
        [
            'text' => 'اختر تكوين السؤال الصحيح لجملة (This is a table):',
            'options' => ['Is a table this?', 'This is a table?', 'Is this a table?', 'A table this is?'],
            'correct' => 2, // Is this a table?
        ],
        [
            'text' => 'اختر تكوين السؤال الصحيح لجملة (These are birds):',
            'options' => ['These are birds?', 'Are these birds?', 'Birds are these?', 'Are birds these?'],
            'correct' => 1, // Are these birds?
        ],
        [
            'text' => 'اختر تكوين السؤال الصحيح لجملة (These were students):',
            'options' => ['Were these students?', 'These were students?', 'Students were these?', 'These students were?'],
            'correct' => 0, // Were these students?
        ],
        [
            'text' => 'اختر تكوين السؤال الصحيح لجملة (This was clear):',
            'options' => ['Was this clear?', 'clear was this?', 'This was clear?', 'This clear was this?'],
            'correct' => 0, // Was this clear?
        ],
        [
            'text' => 'عندما نسال ( هل هذه تكون نوره Is this Nourah?) فاننا نجيب ب (Yes, Nourah is. \ No, Nourah isn’t) ولا نستبدل اسم الفاعل نوره بضمير فاعل (She)؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        [
            'text' => 'عندما يكون الكتاب قريب مني فإنني أقول (That is a book)؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        [
            'text' => 'عندما تكون الكتب قريبة مني فإنني أقول؟',
            'options' => ['Those are books', 'These are books', 'This is a book', 'That is a book'],
            'correct' => 1, // These are books
        ],
        [
            'text' => 'عندما يكون الصندوق بعيد عني فإنني أقول؟',
            'options' => ['This is a box', 'That is a box', 'These are boxes', 'Those are boxes'],
            'correct' => 1, // That is a box
        ],
        [
            'text' => 'عندما تكون الصناديق بعيدة عني فإنني أقول؟',
            'options' => ['This is a box', 'That is a box', 'These are boxes', 'Those are boxes'],
            'correct' => 3, // Those are boxes
        ],
        [
            'text' => 'نستخدم اسم الإشارة (This) للمفرد العاقل فقط؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار أسماء الإشارة (Demonstrative Pronouns)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 30,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'points' => $qData['points'] ?? 1,
        ];

        if ($attrs['question_type'] === 'drag_drop') {
            $attrs['matching_pairs'] = json_encode($qData['matching_pairs']);
            $attrs['correct_answer'] = 'X'; 
        } else {
            $attrs['option_a'] = $qData['options'][0] ?? null;
            $attrs['option_b'] = $qData['options'][1] ?? null;
            $attrs['option_c'] = $qData['options'][2] ?? null;
            $attrs['option_d'] = $qData['options'][3] ?? null;
            
            $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 954 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
