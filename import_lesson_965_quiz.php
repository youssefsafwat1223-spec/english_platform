<?php

/**
 * Script to import questions for Lesson ID 965
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_965_quiz.php
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
    $lessonId = 965;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 965 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'ما معنى كلمة (Linking words)؟',
            'options' => ['علامات الترقيم', 'كلمات او أدوات الربط', 'كلمات وصفية', 'لا شيء مما سبق'],
            'correct' => 1, // كلمات او أدوات الربط
        ],
        [
            'text' => 'كم عدد أنواع أدوات الربط المذكورة في الدرس؟',
            'options' => ['ستة أنواع', 'سبعة أنواع', 'ثمانية أنواع', 'تسعة أنواع'],
            'correct' => 2, // ثمانية أنواع
        ],
        [
            'text' => 'صل كل نوع من أنواع أدوات الربط بمعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "Addition words", "right" => "كلمات الاضافة"],
                ["left" => "Contrast words", "right" => "كلمات التناقض"],
                ["left" => "Sequence words", "right" => "كلمات التسلسل"],
                ["left" => "Consequence words", "right" => "كلمات العواقب والنتائج"],
                ["left" => "Reason words", "right" => "كلمات الاسباب"],
                ["left" => "Condition words", "right" => "كلمات الحالة او الشرط"],
                ["left" => "Certainty words", "right" => "كلمات التأكيد"],
                ["left" => "Summary words", "right" => "كلمات التلخيص"]
            ],
            'points' => 8,
        ],
        [
            'text' => 'صل ما بين كل نوع من أنواع كلمات الربط بالكلمات التي تناسبه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "Addition words", "right" => "As well as - besides - in addition"],
                ["left" => "Contrast words", "right" => "However - on the other hand - in spite of"],
                ["left" => "Sequence words", "right" => "Firstly - secondly - initially - after"],
                ["left" => "Consequence words", "right" => "As a result - thus - therefore - so"],
                ["left" => "Reason words", "right" => "Since - as - due to - because"],
                ["left" => "Condition words", "right" => "If - as long as - whether - unless"],
                ["left" => "Certainty words", "right" => "Certainly - obviously - of course - undoubtedly"],
                ["left" => "Summary words", "right" => "Finally - to sum up - in short - to conclude"]
            ],
            'points' => 8,
        ],
        [
            'text' => 'جميع كلمات (Addition) نستخدمها ل:',
            'options' => ['اظهار السبب', 'اظهار النتيجة', 'إضافة شيء اخر للجملة', 'اظهار ملخص للموضوع'],
            'correct' => 2, // إضافة شيء اخر للجملة
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Addition) الإضافة:',
            'options' => [
                'أولا – ثانيا – أخيرا',
                'بالنتيجة لذلك – بسبب ذلك - لأن',
                'بالإضافة الى ذلك – علاوة على ذلك – الى جانب ذلك',
                'وذلك بسبب – لأن  - لكن'
            ],
            'correct' => 2, 
        ],
        [
            'text' => 'اختر كلمة الربط المناسبة لجملة: (The company reduced costs---- it increased its market share) ليصبح معناها (الشركة قللت التكاليف بالإضافة لذلك انها رفعت حصتها السوقية)',
            'options' => ['Because', 'Furthermore', 'First', 'Therefore'],
            'correct' => 1, // Furthermore
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (انا استمتع بالتجول وأيضا انا أحب التخييم في الجبال):',
            'options' => [
                'I enjoy hiking also, I love camping in the mountain.',
                'I enjoy hiking because I love camping in the mountain.',
                'I enjoy hiking as a result I love camping in the mountain.',
                'I enjoy hiking finally I love camping in the mountain.'
            ],
            'correct' => 0, 
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Contrast) التناقض:',
            'options' => [
                'لكن – من ناحية أخرى – بالرغم من – على العكس تماما',
                'بالنتيجة لذلك – بسبب ذلك – لأن - لكن',
                'جميعهم بمعنى علاوة على ذلك – إضافة الى ذلك',
                'أولا – ثانيا – ثالثا - أخيرا'
            ],
            'correct' => 0, 
        ],
        [
            'text' => '(---- he had promised to be on time, he arrived late.) اختر كلمة الربط المناسبة ليصبح معناها (على الرغم من انه وعد بالحضور في الموعد المحدد، مع ذلك وصل متاخر)',
            'options' => ['Moreover', 'Although', 'Firstly', 'As a result'],
            'correct' => 1, // Although
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (هي اشترت الفستان الأحمر بدلا من الفستان الازرق):',
            'options' => [
                'She bought the red dress and the blue one.',
                'She bought the red dress instead of the blue one.',
                'She bought the red dress therefore the blue one.',
                'She bought the red dress because the blue one.'
            ],
            'correct' => 1, 
        ],
        [
            'text' => 'عند ذكر احداث متسلسلة وبالخطوات فان انسب كلمات ربط نستخدمها هي:',
            'options' => ['Contrast', 'Sequence', 'Consequence', 'Reason'],
            'correct' => 1, // Sequence
        ],
        [
            'text' => '(---- I need to finish my homework ---- I can watch TV.) اختر كلمة الربط المناسبة ليصبح معناها (أولا انا يجب انا انهي واجبي ثم انا استطيع اشاهد التلفاز)',
            'options' => ['Then \ next', 'Then \ firstly', 'Firstly \ then', 'Because \ and'],
            'correct' => 2, // Firstly / then
        ],
        [
            'text' => 'لإظهار النتائج او العواقب فان انسب أداة ربط نستخدمها هي:',
            'options' => ['Reason', 'Consequence', 'Contrast', 'Sequence'],
            'correct' => 1, // Consequence
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Consequence) العواقب:',
            'options' => [
                'على الرغم من ذلك – بدلا من ذلك – من ناحية أخرى – لكن',
                'نتيجة ل – من ثم (وبالتالي) – لذلك (بناء على ذلك) – لذلك (لذا)',
                'علاوة على ذلك – إضافة الى ذلك',
                'أولا – ثانيا – أخيرا'
            ],
            'correct' => 1, 
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (هو نسي احضار جواز سفره لذلك، لم يسمح له بالسفر):',
            'options' => [
                'He forgot to bring his passport, therefore, he wasn’t allowed to travel.',
                'He forgot to bring his passport, finally he wasn’t allowed to travel.',
                'He forgot to bring his passport, because he wasn’t allowed to travel.',
                'He forgot to bring his passport, however he wasn’t allowed to travel.'
            ],
            'correct' => 0, 
        ],
        [
            'text' => '(the store was closed ---- we didn’t buy anything.) اختر كلمة الربط المناسبة ليصبح معناها (المتجر كان مغلقا لذلك لم نشتري شيء)',
            'options' => ['First', 'Moreover', 'So', 'Because'],
            'correct' => 2, // So
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Reason) السبب:',
            'options' => [
                'على الرغم من ذلك – بدلا من ذلك – من ناحية أخرى – لكن',
                'بما ان – لأن – بسبب (رسمية) - بسبب',
                'علاوة على ذلك – إضافة الى ذلك',
                'أولا – ثانيا – أخيرا'
            ],
            'correct' => 1, 
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (هي لم تستطع النوم لأنه كان يوجد الكثير من الضوضاء):',
            'options' => [
                'She couldn’t sleep so there was a lot of noise.',
                'She couldn’t sleep therefore there was a lot of noise.',
                'She couldn’t sleep because there was a lot of noise.',
                'She couldn’t sleep however there was a lot of noise.'
            ],
            'correct' => 2, 
        ],
        [
            'text' => '(The match was postponed ---- strong storm.) اختر كلمة الربط المناسبة ليصبح معناها (المباراة الغيت بسبب عاصفة القوية)',
            'options' => ['So', 'Due to', 'Although', 'But'],
            'correct' => 1, // Due to
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Condition):',
            'options' => [
                'اذا – ما دام – سواء – ما لم',
                'بسبب – لان – بالنتيجة لذلك – ما لم',
                'بالنتيجة لذلك – لذلك – بسبب ذلك',
                'أولا – ثانيا – ثالثا - أخيرا'
            ],
            'correct' => 0, 
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (اذا وصلت مبكرا سأتصل بك):',
            'options' => [
                'If I arrive early, I will call you.',
                'as I arrive early, I will call you.',
                'So I arrive early, I will call you.',
                'Although I arrive early, I will call you.'
            ],
            'correct' => 0, 
        ],
        [
            'text' => '(You can use my motorcycle ---- you bring it back before midnight .) اختر كلمة الربط ليصبح معناها (بشرط ان ترجعها)',
            'options' => ['As', 'Since', 'As long as', 'So'],
            'correct' => 2, // As long as
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لمعظم كلمات (Certainty):',
            'options' => [
                'اذا – لو – بشرط – شريطة ان',
                'بالتأكيد – بوضوح – بالطبع – بلا شك',
                'بالنتيجة لذلك – لذلك – بسبب ذلك',
                'أولا – ثانيا – ثالثا - أخيرا'
            ],
            'correct' => 1, 
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (بالتأكيد ، سأساعدك):',
            'options' => [
                'So , I will help you.',
                'Since , I will help you.',
                'certainly, I will help you.',
                'as , I will help you.'
            ],
            'correct' => 2, 
        ],
        [
            'text' => '(----- , it’s important to be honest.) اختر كلمة الربط ليصبح معناها (بالطبع ، انه من المهم ان تكون صادق)',
            'options' => ['As long as', 'Since', 'of course', 'So'],
            'correct' => 2, // of course
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة (أخيرا ، وصلنا البيت):',
            'options' => [
                'First, we reached the home.',
                'Finally, we reached the home.',
                'Recently, we reached the home.',
                'In addition, we reached the home.'
            ],
            'correct' => 1, 
        ],
        [
            'text' => '(----- , Riyadh is a wonderful destination for tourists.) اختر كلمة الربط ليصبح معناها (باختصار، الرياض مقصد رائع للسياح)',
            'options' => ['In short', 'Finally', 'Obviously', 'Certainly'],
            'correct' => 0, // In short
        ],
        [
            'text' => 'I couldn’t attend the meeting _ I was sick. اختر أداة الربط الصحيحة لتصبح (لأنني كنت مريض)',
            'options' => ['Because بسبب\ لأن', 'So لذلك', 'But لكن', 'And و'],
            'correct' => 0, // Because
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار أدوات الربط (Linking Words)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 40,
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

    echo "🎉 Successfully added " . $count . " questions to Lesson 965 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
