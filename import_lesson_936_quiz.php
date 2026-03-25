<?php

/**
 * Script to import questions for Lesson ID 936
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_936_quiz.php
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
    $lessonId = 936;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 936 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'جميع ما يلي ضمائر ملكية ما عدا؟',
            'type' => 'multiple_choice',
            'options' => ['Mine', 'Ours', 'Her', 'Yours'],
            'correct' => 2, // Her (Adjective)
            'points' => 1,
        ],
        [
            'text' => 'ضمائر الملكية؟(possessive pronouns)اخترالاجابة الصحيحة لمجموعة الكلمات التي تحتوي على',
            'type' => 'multiple_choice',
            'options' => ['His - mine - her – their', 'Ours – hers – its – yours', 'You – theirs – my – his', 'It – hers – mine - his'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'جميع هذه الكلمات تحت مسمى؟(your-her-his-our-my-their-its)',
            'type' => 'multiple_choice',
            'options' => ['ضمائر ملكيةPossessive pronouns', 'صفات ملكيةPossessive adjectives'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'جميع هذه الكلمات تحت مسمى؟(yours-hers-his-ours-mine-theirs)',
            'type' => 'multiple_choice',
            'options' => ['Possessive pronouns', 'Possessive adjectives'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'الكلمة التي تبقى كما هي في ضمير الملكية وصفة الملكية عند استخدامها هي كلمة؟',
            'type' => 'multiple_choice',
            'options' => ['My', 'Her', 'His', 'Ours'],
            'correct' => 2, // His
            'points' => 1,
        ],
        [
            'text' => 'صفات الملكية؟(possessive adjective)اختر الإجابة الصحيحة لمجموعة الكلمات التي تحتوي على',
            'type' => 'multiple_choice',
            'options' => ['My – her – his – their', 'Ours – hers – its – yours', 'You – theirs – my – his', 'Its – hers – my - his'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'للتحدث عن ملكية الشيء فاننا نستخدم؟',
            'type' => 'multiple_choice',
            'options' => ['Possessive pronouns', 'Possessive adjectives', 'Own', 'جميع ما سبق'],
            'correct' => 3, // All
            'points' => 1,
        ],
        [
            'text' => 'نستخدم قبل كلمة (own):',
            'type' => 'multiple_choice',
            'options' => ['Possessive pronouns', 'Possessive adjectives', '(subject pronouns) ضمائر فاعل', 'ليس مما سبق'],
            'correct' => 1, // Possessive adjectives
            'points' => 1,
        ],
        [
            'text' => 'لو كان الاسم مفرد لا ينتهي ب (s) كيف نضيف له( الملكية s ؟',
            'type' => 'multiple_choice',
            'options' => ['(’s) نضيف اخره', '(s’) نضيف اخره', '(s) نضيف اخره', 'نضيف اخره (’)'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'كلمة (child) في (The child’s nursing bottle) مفرد ام جمع؟',
            'type' => 'multiple_choice',
            'options' => ['مفرد', 'جمع'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'لو كان الاسم جمع و منتهيا بحرف (s) كيف نضيف له( الملكية s ؟',
            'type' => 'multiple_choice',
            'options' => ['(’s) نضيف اخره', '(s’) نضيف اخره', '(s) نضيف اخره', 'نضيف اخره (’)'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'كلمة (girl) في (The girls’ parents) مفرد ام جمع؟',
            'type' => 'multiple_choice',
            'options' => ['مفرد', 'جمع'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'لو كان الاسم جمع وغير منتهي ب s (يعني جمع غير منتظم مثل children) عند إضافة s الملكية فاننا نعامله معاملة المفرد ونضيف ’s',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'الجمع الشاذ (ما نضيف له es او s) نعامله معاملة المفرد لماذا؟',
            'type' => 'multiple_choice',
            'options' => ['بسبب عدم وجود (s) فنقوم باضافتها مع الفاصلة', 'لانه بالاصل كان مفرد', 'لا شي مما سبق'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'نضيف ----- اذا كان الاسم مفرد يعني (ليس منتهي بحرف s)',
            'type' => 'multiple_choice',
            'options' => ['’s', 's’', 's', '’'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'نضيف ----- اذا كان الاسم جمع يعني (منتهي بحرف s)',
            'type' => 'multiple_choice',
            'options' => ['’s', 's’', 's', '’'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'Own مع صفة الملكية تعني ------؟',
            'type' => 'multiple_choice',
            'options' => ['تعني ان الشخص يمتلك الشيء تملك مطلق كامل(يكون باسمه)', 'تعني ان الشخص يمتلك شيء بصورة مؤقتة'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'عند ترجمة صفات الملكية(possessive adjectives) فاننا نقوم ب--------؟',
            'type' => 'multiple_choice',
            'options' => ['فانها تكون منفصلة عن المملوك', 'نلصقها باالمملوك مثل (كتابه-كتابها-كتابهم)', 'لا نترجمها', '1+2'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الطريقة الصحيحة لترجمة جملة (my hat) هي؟',
            'type' => 'multiple_choice',
            'options' => ['قبعة لي', 'قبعتي', 'ي قبعة', 'قبعة تي'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'عند ترجمة جملة تحتوي على اسم الملكية نقوم بترجمة المملوك أولا ثم اسم المالك ثانيا.',
            'type' => 'true_false',
            'options' => ['نعم', 'لا'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ضمائر الملكية غالبا تأتي في نهاية الجملة ويأتي الاسم أوالشيء المملوك قبلها؟',
            'type' => 'true_false',
            'options' => ['نعم', 'لا'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'نستطيع استخدام أسماء الملكية مع ضمائر الفاعل التالية ( I – we – you ) .',
            'type' => 'true_false',
            'options' => ['نعم', 'لا'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اذا اتى اسم المالك(اسم الشخص) منتهي بحرف (s) فمكان الفاصلة العلوية يكون-----',
            'type' => 'multiple_choice',
            'options' => ['S’', '’s', 'Both'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'عند ترجمة ضمائر الملكية ( Possessive pronouns ) فإننا نقوم ب ------؟',
            'type' => 'multiple_choice',
            'options' => ['نفصلها عن المملوك مثلا (لي\حقي له\حقه لها\حقها)', 'نلصقها بالاسم مثل (لعبته-لعبتها-لعبتهم)', 'لا نترجمها', '1+2'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'الطريقة الصحيحة لترجمة جملة (this room is hers) هي؟',
            'type' => 'multiple_choice',
            'options' => ['هذه الغرفة تكون لها', 'هذه الغرفة تكون ملكها', 'هذه تكون غرفتها', 'جميع ما سبق'],
            'correct' => 3, // D
            'points' => 1,
        ],
        [
            'text' => 'عندما نريد ان نقول بان( احمد يمتلك مطعم ملكية مطلقة المطعم له ) فان افضل طريقة للتعبير عن ذلك؟',
            'type' => 'multiple_choice',
            'options' => ['Ahmed’s restaurant', 'Ahmed has his own restaurant', 'His restaurant', 'جميع ما سبق'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'صل بين العمود ا مع ما يناسبه مع العمود ب:',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "اسم ملكية غير منتظم", "right" => "حقوق النساء women’s right"],
                ["left" => "اسم ملكية جمع", "right" => "رف زجاجات Bottles’ shelf"],
                ["left" => "اسم ملكية مفرد", "right" => "قائد الفريق Team’s captain"]
            ],
            'points' => 3,
        ],
        [
            'text' => 'Ahmed and Abdullah are sitting on their desks. These desks are ------',
            'type' => 'multiple_choice',
            'options' => ['his', 'theirs', 'mine'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'We must protect our country because it is___',
            'type' => 'multiple_choice',
            'options' => ['our', 'their', 'Ours'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'I furnished my flat. This flat is ----',
            'type' => 'multiple_choice',
            'options' => ['Her', 'His', 'Mine'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'صل الكلمة بمعناها (صفات الملكية):',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "my", "right" => "ي"],
                ["left" => "her", "right" => "ها"],
                ["left" => "his", "right" => "ه"],
                ["left" => "our", "right" => "نا"],
                ["left" => "your", "right" => "ك"],
                ["left" => "its", "right" => "ه/ها"],
                ["left" => "their", "right" => "هم"]
            ],
            'points' => 7,
        ],
        [
            'text' => 'في اللغة العربية نترجم الاسم المالك ثم المملوك مثل (احمد مكتبة بدل من مكتبة احمد)',
            'type' => 'true_false',
            'options' => ['نعم', 'لا'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الترجمة الصحيحة ل( هذا القلم يكون ملكي) هي-------',
            'type' => 'multiple_choice',
            'options' => ['This pen is mine', 'This is my pen.', 'This is pen my', 'This mine is pen'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'الترجمة الصحيحة ل ( هذه تكون بطانيتها) هي -------',
            'type' => 'multiple_choice',
            'options' => ['This blanket is hers', 'This is her blanket', 'This blanket hers is', 'This her is blanket'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة(هذا العقد يكون خاصتي) هي --------',
            'type' => 'multiple_choice',
            'options' => ['This is my contract', 'This contract is mine', 'This is my own contract', 'لا شيء مما سبق'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'صل الكلمة بمعناها (ضمائر الملكية):',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "mine", "right" => "ملكي"],
                ["left" => "hers", "right" => "ملكها"],
                ["left" => "his", "right" => "ملكه"],
                ["left" => "ours", "right" => "ملكنا"],
                ["left" => "yours", "right" => "ملكك/ملككم"],
                ["left" => "its", "right" => "ملكه لغير العاقل/ملكها"],
                ["left" => "theirs", "right" => "ملكهم"]
            ],
            'points' => 7,
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ضمائر وصفات الملكية',
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
    
    // Clear existing questions for this quiz to avoid duplicates if re-running
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'],
            'points' => $qData['points'] ?? 1,
        ];

        if ($qData['type'] === 'drag_drop') {
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

    echo "🎉 Successfully added " . $count . " questions to Lesson 936 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
