<?php

/**
 * Script to import questions for Lesson ID 967
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_967_quiz.php
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
    $lessonId = 967;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 967 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        // Possessive Review
        [
            'text' => 'للتحدث عن ملكية الشيء فإننا نستخدم جميع ما يلي ما عدا:',
            'options' => ['Possessive pronouns', 'Possessive adjective', 'Possessive nouns', 'Subjective pronoun'],
            'correct' => 3, // Subjective pronoun
        ],
        [
            'text' => 'نستخدم كلمة Own قبل جميع ما يلي (ما عدا):',
            'options' => ['Their', 'My', 'Hers', 'his'],
            'correct' => 2, // Hers (Pronoun)
        ],
        [
            'text' => 'كلمة (book) في (Books’ covers) مفرد ام جمع؟',
            'type' => 'multiple_choice',
            'options' => ['مفرد', 'جمع'],
            'correct' => 1, // جمع
        ],
        [
            'text' => 'الطريقة الصحيحة لترجمة (My teeth) هي:',
            'options' => ['اسنان لي', 'اسناني', 'ي اسنان', 'اسنان حقي'],
            'correct' => 1, // اسناني
        ],
        [
            'text' => 'عندما نريد ان نقول بان ( فهد يمتلك شركة ملكية كاملة يعني الشركة له ) فما هي افضل طريقة للتعبير عن ذلك ؟',
            'options' => ['Fahad’s company', 'Fahad has his own company', 'His company', 'جميع ما سبق'],
            'correct' => 1, // owns his own
        ],
        [
            'text' => 'الترجمة الصحيحة ل ( هذا الكرسي يكون ملكي ) هي:',
            'options' => ['This chair is mine.', 'This is my chair', 'This is chair my', 'This mine is chair'],
            'correct' => 0, 
        ],
        // Prepositions Review
        [
            'text' => 'لإضافة معنى للكلمة او لربط كلمة بكلمة فإننا نستخدم:',
            'options' => ['أسماء الأشخاص', 'أدوات الربط', 'حروف الجر', 'حروف التعجب'],
            'correct' => 2, // حروف الجر
        ],
        [
            'text' => 'ما الترجمة الصحيحة ل ( على الرف ):',
            'options' => ['In the shelf', 'On the shelf', 'Under the shelf', 'Next to the shelf'],
            'correct' => 1, // On the shelf
        ],
        [
            'text' => 'ما حرف الجر الذي بإمكاننا استخدامه قبل ( السنوات والشهور والأماكن)؟',
            'options' => ['In', 'On', 'Under', 'at'],
            'correct' => 0, // In
        ],
        [
            'text' => 'اختر حرف الجر المناسب قبل أسماء المواصلات الكبيرة العامة التي يمكن ان نمشي بها ( بها ممر):',
            'options' => ['In', 'At', 'On', 'By'],
            'correct' => 2, // On
        ],
        [
            'text' => 'ما حرف الجر المناسب للصورة؟ (Along / Across / Through / Around)',
            'options' => ['Along) بمحاذاة على طول ( المسافة جانبا', 'Across عبر (عبور)', 'Through خلال', 'Around حول'],
            'correct' => 0, 
        ],
        [
            'text' => 'اختر الصورة المناسبة لكلمة (among):',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        // Demonstrative Review
        [
            'text' => 'this اسم إشارة يستخدم للمفرد البعيد؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        [
            'text' => 'those اسم إشارة يستخدم للجمع البعيد؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // صح
        ],
        [
            'text' => 'كلمة ( ذيز) الكتابة الصحيحة لها هي:',
            'options' => ['Theze', 'These', 'Theese', 'Theeze'],
            'correct' => 1, // These
        ],
        [
            'text' => 'ما هي أسماء الإشارة التي تأخذ Is في زمن المضارع؟',
            'options' => ['This \ that', 'Those \ these', 'This \ these', 'That \ those'],
            'correct' => 0, // This \ that
        ],
        [
            'text' => 'اختر تكوين السؤال الصحيح لجملة (This is an old house):',
            'options' => ['This is an old house?', 'Is this an old house?', 'This an old is house?', 'Is this an old house.'],
            'correct' => 1, 
        ],
        [
            'text' => 'عندما اشير الى مرآه( بعيدة عني) فإنني أقول:',
            'options' => ['This is a mirror.', 'That is a mirror.', 'These is a mirror.', 'Those is a mirror.'],
            'correct' => 1, // That
        ],
        [
            'text' => 'لا يمكننا استخدام أسماء الإشارة This \that للمفرد العاقل؟',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // خطأ
        ],
        // Linking Words Review
        [
            'text' => '__ I need to check my email __ I can send another letter. اختر كلمة الربط المناسبة:',
            'options' => ['Then \ next', 'Then \ firstly', 'Firstly \ then', 'Because \ and'],
            'correct' => 2, 
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لجملة ( الباب كان مقفل لذلك هو ما كان قادر ان يخرج):',
            'options' => [
                'The door was locked, because, he wasn’t able to go out.',
                'The door was locked, finally, he wasn’t able to go out.',
                'The door was locked, however, he wasn’t able to go out.',
                'The door was locked; therefore, he wasn’t able to go out.'
            ],
            'correct' => 3, 
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة ( اذا ذهبت الى الشاطئ، ستشعر بالسعادة):',
            'options' => [
                'If you go to the beach, you will feel happy.',
                'Although you go to the beach, you will feel happy.',
                'So you go to the beach, you will feel happy.',
                'As you go to the beach, you will feel happy.'
            ],
            'correct' => 0, 
        ],
        [
            'text' => 'اختر كلمة الربط المناسبة لجملة (__ I like listening to Qur’an.):',
            'options' => ['Since', 'So', 'Because', 'Of course'],
            'correct' => 3, // of course (based on typical patterns)
        ],
        [
            'text' => 'جميع كلمات الربط التالية هي (أدوات ربط للتأكيد) ما عدا:',
            'options' => ['Certainly', 'Finally', 'Of course', 'Obviously'],
            'correct' => 1, // Finally (Sequence/Summary)
        ],
        [
            'text' => 'ماذا نسمي أدوات الربط التي تستخدم للإضافة في الكلام؟',
            'options' => ['Certainity', 'Summary', 'Addition', 'Reason'],
            'correct' => 2, // Addition
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة شاملة (Grammar Review)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 45,
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
            'points' => 1,
        ];

        $attrs['option_a'] = $qData['options'][0] ?? null;
        $attrs['option_b'] = $qData['options'][1] ?? null;
        $attrs['option_c'] = $qData['options'][2] ?? null;
        $attrs['option_d'] = $qData['options'][3] ?? null;
        
        $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 967 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
