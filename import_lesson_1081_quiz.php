<?php

/**
 * Script to import questions for Lesson ID 1081 (Future Perfect Continuous Grammar)
 * php import_lesson_1081_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1081;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1081 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى زمن المستقبل التام المستمر في اللغة الإنجليزية؟', 'options' => ['Future simple', 'Future continuous', 'Future perfect', 'Future perfect continuous'], 'correct' => 3],
        ['text' => 'يستخدم زمن المستقبل التام المستمر للتعبير عن:', 'options' => ['فترة حدوث فعل بدأ في الماضي واستمر حتى وقت محدد في المستقبل.', 'تنبؤ في المستقبل', 'احداث متداخلة في المستقبل', 'يستخدم للتعبير عن حدوث حدثين في المستقبل واحد سينتهي في المستقبل قبل حدث اخر'], 'correct' => 0],
        ['text' => 'يعد زمن المستقبل التام المستمر زمن كثير الاستخدام.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للإثبات في زمن المستقبل التام المستمر:', 'options' => ['Subject + will\shall+ have + been+ v1 + ing + object \ complement.', 'Subject + will\shall+ have + been+ v3 + object \ complement.', 'Subject + will\shall + been+ v1 + ing + object \ complement.', 'Subject + will\shall+ has + been+ v1 + ing + object \ complement.'], 'correct' => 0],
        ['text' => 'لماذا نستخدم Will في زمن المستقبل التام المستمر؟', 'options' => ['لان الزمن مستمر', 'لان الزمن مستقبل واي زمن مستقبل نستخدم معه Will', 'لان الجملة مثبتة واي جملة مثبتة نستخدم معها Will', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'لماذا لا نكتب Has بدل Have في زمن المستقبل التام المستمر؟', 'options' => ['لان Have تاني مع كافة الازمنة', 'لان بعد will يأتي فعل مجرد والمجرد هو have وليس Has', 'لان الزمن مستقبل تام', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'لماذا نضع V1+ing في زمن المستقبل التام المستمر؟', 'options' => ['لان الزمن تام', 'لان أي زمن مستمر لا بد ان نضع معه V1 + ing', 'لان قبلها الكينونة Be ودائما يأتي بعد الكينونة Be نضع V1+ing', 'ب + ج'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة للفعل المساعد (Will\shall have been):', 'options' => ['قد', 'لا', 'سوف اكون صارلي \ راح أكون صارلي', 'فائت'], 'correct' => 2],
        ['text' => 'اختر الجملة التي تعبر عن زمن المستقبل التام المستمر.', 'options' => ['At five o’clock, I will wait for 30 minutes.', 'At five o’clock, I have been waiting for 30 minutes.', 'At five o’clock, I will have been waiting for 30 minutes.', 'At five o’clock, I waited for 30 minutes.'], 'correct' => 2],
        ['text' => 'In November, I shall have been __ at my company for three years. اختر الإجابة الصحيحة لـ:', 'options' => ['Work', 'Working', 'Worked', 'Works'], 'correct' => 1],
        ['text' => 'ما (الفاعل) في الجملة: (In November, I will have been working at my company for three years.)؟', 'options' => ['November', 'I', 'Will', 'My company'], 'correct' => 1],
        ['text' => 'ما الفعل المساعد الدال على المستقبل في الجملة السابقة؟', 'options' => ['will', 'have', 'been', 'Will have been + الفعل الناقص'], 'correct' => 0],
        ['text' => 'ما الفعل المساعد الدال على التام في الجملة السابقة؟', 'options' => ['Will', 'Have', 'Been', 'الفعل الناقص'], 'correct' => 1],
        ['text' => 'ما الفعل المساعد الدال على المستمر في الجملة السابقة؟', 'options' => ['Will', 'Have', 'Been', 'الفعل الناقص'], 'correct' => 2],
        ['text' => 'لماذا كتبت الكلمة (نوفمبر) بحرف كبير في الجملة السابقة؟', 'options' => ['لان كلمة نوفمبر November من أسماء الشهور وأسماء الشهور ( أسماء علم) تبدا بحرف كبير.', 'لأنها تبدأ بحرف ال N', 'لأنها تتكون من ثلاثة مقاطع', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'When I turn thirty, I shall ___ been living in Dammam for twenty-one years. اختر الإجابة الصحيحة للجملة:', 'options' => ['Has', 'Had', 'Have', 'Be'], 'correct' => 2],
        ['text' => 'We will have __ living in this house for 10 years by next month. اختر الإجابة الصحيحة للجملة:', 'options' => ['Be', 'Been', 'Was', 'Were'], 'correct' => 1],
        ['text' => 'by noon, the electrician will have been __ the electricity for 3 hours. اختر الإجابة الصحيحة للجملة:', 'options' => ['fix', 'fixing', 'fixed', 'fixes'], 'correct' => 1],
        ['text' => 'اذا كان الفاعل جمع فاننا نستخدم Will have been عكس لو كان الفاعل مفرد فاننا نستخدم Will has been.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للنفي في زمن المستقبل التام المستمر:', 'options' => ['Subject + will\shall+ not + have + been+ v1+ object \ complement.', 'Subject + will\shall +not+ have + been+ v1 + ing + object \ complement.', 'Subject + will\shall+ have + not + been+ v1 + ing + object \ complement.', 'Subject + will\shall+ have + been+ not +v1 + ing + object \ complement.'], 'correct' => 1],
        ['text' => 'اختر الشكل المختصر الصحيح للنفي (Will not):', 'options' => ['Willnt', 'Won’t', 'Wiit', '’ll'], 'correct' => 1],
        ['text' => 'اختر الجملة المنفية بالشكل الصحيح:', 'options' => ['I shall not have been shopping on Wednesday.', 'I not will have been shopping on Wednesday.', 'I will haven’t been shopping on Wednesday.', 'I will have been not shopping on Wednesday.'], 'correct' => 0],
        ['text' => 'Suad will have been living here for a month next week. اختر الترجمة الصحيحة للجملة:', 'options' => ['سعاد راح يكون صارلها عايشة هنا لمدة شهر الأسبوع القادم.', 'سعاد عاشت هنا لمدة شهر الأسبوع القادم.', 'سعاد قد عاشت هنا لمدة شهر الأسبوع القادم.', 'سعاد تعيش هنا لمدة شهر الأسبوع القادم.'], 'correct' => 0],
        ['text' => 'من الفاعل في الجملة السابقة؟', 'options' => ['Will have been', 'Living', 'Suad', 'لم يذكر الفاعل في الجملة'], 'correct' => 2],
        ['text' => 'اختر ضمير الفاعل الذي يحل محل اسم الفاعل في الجملة السابقة:', 'options' => ['I', 'He', 'She', 'We'], 'correct' => 2],
        ['text' => 'اختر الفعل الأساسي في الجملة السابقة:', 'options' => ['Have', 'Been', 'Living', 'Will'], 'correct' => 2],
        ['text' => 'اختر الافعال المساعدة في الجملة السابقة:', 'options' => ['Will', 'Will have been', 'There', 'لا تحتوي الجملة على فعل مساعد'], 'correct' => 1],
        ['text' => 'اختر الصفة في الجملة التالية ان وجدت: (His father will not have been seeing him because he is angry.)', 'options' => ['لا يوجد صفة في الجملة', 'Father', 'Angry', 'Because'], 'correct' => 2],
        ['text' => 'اختر أداة الربط في الجملة السابقة:', 'options' => ['لا يوجد صفة في الجملة', 'Father', 'Angry', 'Because'], 'correct' => 3],
        ['text' => 'ما اهمية أداة الربط في الجملة السابقة؟', 'options' => ['Addition', 'Contrast', 'Reason', 'Condition'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للسؤال في زمن المستقبل التام المستمر:', 'options' => ['Will\shall+ subject + have + been+ v1+ object \ complement?', 'Will\shall+ subject + have + been+ v1 + ing + object \ complement?', 'Will\shall+ Subject + have + been+ v3+ object \ complement?', 'Will\shall+ Subject + has + be+ v1 + ing + object \ complement?'], 'correct' => 1],
        ['text' => 'You will have been traveling for two days. اختر تكوين السؤال الصحيح للجملة:', 'options' => ['Have you will been traveling for two days?', 'Will you have been traveling for two days?', 'Will you have been travel for two days?', 'Will you has been traveling for two days?'], 'correct' => 1],
        ['text' => 'Kareem will have been teaching at a university for a year. اختر تكوين السؤال الصحيح للجملة:', 'options' => ['Will Kareem have been teaching at a university for a year?', 'Will Kareem have been teach at a university for a year?', 'Will have Kareem been teaching at a university for a year?', 'Will Kareem have been teaching at a university for a year.'], 'correct' => 0],

        [
            'text' => 'صل ما بين السؤال وجوابه الصحيح:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Will you have been painting your room by tomorrow?', 'right' => 'Yes, I will \ No, I won’t.'],
                ['left' => 'Will Abeer have been studying by 7:00?', 'right' => 'Yes, she will\ No, she won’t.'],
                ['left' => 'Will Tariq have been eating vegetables for three months?', 'right' => 'Yes, he will.\ No, he won’t.'],
                ['left' => 'Will passengers have been flying to Jeddah for 4 hours by this time tomorrow?', 'right' => 'Yes, they will.\No, they won’t.'],
            ]
        ],

        ['text' => 'اختر الجملة المكتوبة ( إجابة السؤال ) بالشكل الصحيح:', 'options' => ['Yes, they will!', 'Yes they, will.', 'Yes, they will.', 'Yes, they will?'], 'correct' => 2],
        ['text' => 'اختر الجملة المكتوبة ( إجابة السؤال ) بالشكل الصحيح:', 'options' => ['No, she won’t.', 'No, she wont’.', 'No she wont?', 'No she, wont.'], 'correct' => 0],
        ['text' => 'حدد الخطأ في الجملة وصححه ان وجد: (By noon, the doctor will has been checking patients for 6 hours.)', 'options' => ['نضع have بدل has', 'نضع be بدل been', 'نضع checked بدل checking', 'لا يوجد خطأ في الجملة'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المستقبل التام المستمر (Future Perfect Continuous Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1081.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
