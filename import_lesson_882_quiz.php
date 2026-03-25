<?php

/**
 * Script to import questions for Lesson ID 882
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_882_quiz.php
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
    $lessonId = 882;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 882 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'ما ورد في درس prefix suffix: اللاحقة دائما تأتي ( قبل) الجذر في الكلمة',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // index 1 = خطأ (B)
            'points' => 1,
        ],
        [
            'text' => 'اختر البادئة التي تعني كبير (حجم) او اعلى ( قوة )',
            'type' => 'multiple_choice',
            'options' => ['Super', 'Un', 'Ir', 'Pre'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما هي البادئة التي ممكن ان نضيفها لكلمة (satisfactory مرضي) ليصبح معناها (غير مرضي)',
            'type' => 'multiple_choice',
            'options' => ['Im', 'Un', 'Ly', 'Ir'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما معنى كلمة Repaint؟',
            'type' => 'multiple_choice',
            'options' => ['يبدأ الرسم', 'ينهي الرسم', 'يعيد الرسم', 'يتوقف عن الرسم'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'كلمة soldier تعني جندي اما كلمة Ex-soldier تعني',
            'type' => 'multiple_choice',
            'options' => ['جندي جديد', 'جندي صغير', 'جندي سابق', 'جندي مميز'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'كلمة carelessly تحتوي على لاحقة واحدة Suffix',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما هو الجذر في كلمة unsuccessful؟',
            'type' => 'multiple_choice',
            'options' => ['Unsuccess', 'Successful', 'Success', 'ful'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس punctuation: نستخدم full stop في نهاية الجملة و Question mark في نهاية السؤال.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما هو ضمير الفاعل الذي يكتب دائما بحرف (كبير Capital) مهما كان موقعه في الجملة؟',
            'type' => 'multiple_choice',
            'options' => ['We', 'You', 'I', 'She'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'اختر علامة الترقيم المناسبة للفراغين في الجملة التالية: (Blue _ red _ white and black are colors.)',
            'type' => 'multiple_choice',
            'options' => ['فاصلة (,)', 'نقطة (.)', 'علامة تعجب (!)', 'علامة استفهام (?)'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'اختر الجملة المكتوبة بالطريقة الصحيحة من حيث علامات الترقيم كاملة: hassan give me the glass',
            'type' => 'multiple_choice',
            'options' => ['Hassan give me the glass.', 'Hassan, give me the glass.', 'Hassan give me the glass', 'Hassan give me the glass?'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'في الجملة (She doesn’t like milk.) وضعنا فاصلة علوية:',
            'type' => 'multiple_choice',
            'options' => ['اختصار للأفعال المساعدة', 'يوجد ملكية هنا وهو انها تمتلك الحليب', 'استخدامها هنا خطأ من الأساس', 'لأنه يوجد نفي في الجملة واختصرنا حرف (o)'],
            'correct' => 3, // D
            'points' => 1,
        ],
        [
            'text' => 'اختر علامة الترقيم الأنسب لجملة "انا نسيت ان اعزم اخت صديقتي __ هي لم تأتي": I forgot to invite my friend’s sister__ she didn’t come.',
            'type' => 'multiple_choice',
            'options' => ['نقطة (.)', 'فاصلة (,)', 'نقطتان فوق بعض (:)', 'لا شيء مما سبق'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما الذي نستخدمه في نهاية الجملة المركبة (الجملة التي لم تنتهي).',
            'type' => 'multiple_choice',
            'options' => ['النقطة (.)', 'الفاصلة (,)', 'علامة استفهام (?)', 'علامة تعجب (!)'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما الذي نستخدمه اذا انتهت الجملة بالكامل (جملة منفصلة وليست مركبة).',
            'type' => 'multiple_choice',
            'options' => ['النقطة (.)', 'الفاصلة (,)', 'علامة استفهام (?)', 'علامة تعجب (!)'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الجمع والمفرد: نستطيع ان نجمع الفعل (نحوله لصيغة جمع)',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما الجمع الصحيح لكلمة Sandwich؟',
            'type' => 'multiple_choice',
            'options' => ['sandwichs', 'sandwiches', 'sandwichies', 'تبقى كما هي في الجمع والمفرد'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'عند جمعها Stomach على كلمة es نضيف',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر الجمع الصحيح لكلمة City',
            'type' => 'multiple_choice',
            'options' => ['Citys', 'Cities', 'Cityes', 'Cityies'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'جمع كلمة chief هو Chiefs',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما مفرد كلمة Feet؟',
            'type' => 'multiple_choice',
            'options' => ['Feets', 'Foot', 'تبقى كما هي', 'fiit'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس Article: نضع أداة التعريف a قبل الاسم المفرد المبدوء بحرف ساكن والأداة an للاسم المفرد المبدوء بحرف علة',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'إذا جاء اول حرف من الكلمة التي بعدها حرف ساكن فإننا نلفظ الأداة The:',
            'type' => 'multiple_choice',
            'options' => ['ذ بكسر الذال', 'ذ بفتح الذال', 'ث بفتح الثاء', 'ث بكسر الثاء'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'أدوات التعريف تأتي قبل الأسماء او ايضا قبل:',
            'type' => 'multiple_choice',
            'options' => ['الحال', 'الفعل', 'الصفة', 'حروف الجر'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'صل بين كل أداة والشروط الواجب توفرها في الاسم لاستخدامها:',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "اسم مفرد نكرة معدود يبدأ بصوت علة", "right" => "an"],
                ["left" => "للأشياء الغير مكررة مثل الشمس", "right" => "the"],
                ["left" => "اسم مفرد نكرة معدود يبدأ بحرف ساكن", "right" => "a"]
            ],
            'points' => 3,
        ],
        [
            'text' => 'أي الجمل التالية كتبت فيها الأداة بالشكل الصحيح: (انا اشتريت شقة جديدة الشهر الفائت)',
            'type' => 'multiple_choice',
            'options' => [
                'I bought an new flat last month.',
                'I bought a new flat last month.',
                'I bought the new flat last month.',
                'I bought new flat last month.'
            ],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر أدوات التعريف المناسبة: (I read ____ novel yesterday. ____ novel was interesting)',
            'type' => 'multiple_choice',
            'options' => ['a / an', 'a / the', 'an / an', 'an / the'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الفاعل: أي ضمير من الضمائر التالية ممكن ان يحل محل اسم الفاعل (عائشة)؟',
            'type' => 'multiple_choice',
            'options' => ['He', 'She', 'I', 'We'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر ضمائر الفاعل التي يتحدث الفاعل فيها عن نفسه (الحاضر):',
            'type' => 'multiple_choice',
            'options' => ['He / she', 'We / I', 'You / they', 'It / he'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ماذا نستخدم لتفادي تكرار اسم الفاعل في الجملة؟',
            'type' => 'multiple_choice',
            'options' => ['المفعول به', 'ضمير الفاعل', 'حرف الجر', 'تكملة جملة'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر الجملة الصحيحة التي يكون فيها (ترتيب اسم الفاعل و ضمير الفاعل) بالطريقة الصحيحة علما بأن الفاعل هو (أنا) و انا اسمي (سناء):',
            'type' => 'multiple_choice',
            'options' => [
                'Sanaa went to a restaurant then she went to the park.',
                'Sanaa went to a restaurant then he went to the park.',
                'She went to a restaurant then Sanaa went to the park.',
                'I went to a restaurant then I went to the park.'
            ],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => '(Ahmed and I) met at the park. استبدل ما بين القوسين بضمير فاعل مناسب',
            'type' => 'multiple_choice',
            'options' => ['He', 'She', 'I', 'We'],
            'correct' => 3, // D
            'points' => 1,
        ],
        [
            'text' => 'صل كل ضمير من ضمائر الفاعل بمعناه:',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "هي", "right" => "She"],
                ["left" => "نحن", "right" => "We"],
                ["left" => "هو/هي لغير العاقل", "right" => "It"],
                ["left" => "انا", "right" => "I"],
                ["left" => "انت/ انتم/ انتن/ أنتي", "right" => "You"],
                ["left" => "هم/هن للعاقل وغير عاقل", "right" => "They"],
                ["left" => "هو", "right" => "He"]
            ],
            'points' => 7,
        ],
        [
            'text' => 'ما ورد في درس تصريف الفعل: نستخدم V1 في زمن المضارع البسيط ونستخدم V2 في زمن الماضي البسيط.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما هو التصريف الثالث من الفعل Cut؟',
            'type' => 'multiple_choice',
            'options' => ['Cuted', 'Cutted', 'Cut', 'Cet'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'ما هو الفعل الذي تتغير فيه الكلمة تماما عن الفعل الأساسي ولا نقدر على إضافة (ed) مباشرة؟',
            'type' => 'multiple_choice',
            'options' => ['Regular verb', 'Irregular verb', 'Modal verb', 'Helping verb'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما هو اللفظ الصحيح للفعل barked؟',
            'type' => 'multiple_choice',
            'options' => ['باركت', 'باركد', 'بارك ايد', 'باركييد'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'اختر التصريف الثاني الصحيح للفعل Hope:',
            'type' => 'multiple_choice',
            'options' => ['Hoped', 'Hopeed', 'Hopied', 'تبقى كما هي Hope'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'اذا انتهي الفعل في التصريف الثاني ب Ed فانه غالبا في التصريف الثالث ينتهي بـ:',
            'type' => 'multiple_choice',
            'options' => ['S', 'Ing', 'Ed', 'en'],
            'correct' => 2, // C
            'points' => 1,
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار شامل للدرس',
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
    
    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'],
            'points' => $qData['points'],
        ];

        if ($qData['type'] === 'drag_drop') {
            $attrs['matching_pairs'] = $qData['matching_pairs'];
            $attrs['correct_answer'] = 'X'; // Dummy for drag_drop
        } else {
            $attrs['option_a'] = $qData['options'][0] ?? null;
            $attrs['option_b'] = $qData['options'][1] ?? null;
            $attrs['option_c'] = $qData['options'][2] ?? null;
            $attrs['option_d'] = $qData['options'][3] ?? null;
            
            // Map index to letter
            $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($attrs);
        
        // Attach to quiz
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 882 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
