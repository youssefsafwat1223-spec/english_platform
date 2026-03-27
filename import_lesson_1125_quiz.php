<?php

/**
 * Script to import questions for Lesson ID 1125 (Quantifiers Grammar)
 * php import_lesson_1125_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1125;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1125 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى (Quantifiers)؟', 'options' => ['محددات النوع', 'محددات الكمية', 'محددات الكيفية', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'تستخدم محددات الكمية (Quantifiers) قبل اسماء:', 'options' => ['الاشارة', 'المعدودة', 'الغير معدودة', 'المعدودة و غير المعدودة'], 'correct' => 3],
        
        [
            'text' => 'صل كل كلمة من كلمات محددات الكمية بمعناها:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Little - a few', 'right' => 'القليل من'],
                ['left' => 'Many – much – a lot of', 'right' => 'الكثير من – العديد من'],
                ['left' => 'Any', 'right' => 'أي'],
                ['left' => 'some', 'right' => 'بعض'],
            ]
        ],

        ['text' => 'لو كان الاسم غير معدود وأريد ان أقول بانه كثير فاستخدم محدد الكمية التالي؟', 'options' => ['Many', 'Much', 'Some', 'Any'], 'correct' => 1],
        ['text' => 'ما معنى جملة (عصير كثير)؟', 'options' => ['Many juice', 'A lot of juice', 'Much juice', 'A few juice'], 'correct' => 2],
        ['text' => 'ما معنى جملة (الكثير من الملح)؟', 'options' => ['A little salt', 'A few salt', 'A lot of salt', 'Many salt'], 'correct' => 2],
        ['text' => 'لو كان الاسم (معدود) وأريد ان أقول بانه كثير فاستخدم محدد الكمية التالي؟', 'options' => ['Many', 'Much', 'Some', 'Any'], 'correct' => 0],
        ['text' => 'ما معنى جملة (قطع حلوى كثيرة)؟', 'options' => ['Much sweets', 'Many sweets', 'A few sweets', 'Some sweets'], 'correct' => 1],
        ['text' => 'ما هو محدد الكمية الذي يجوز استخدامه مع المعدود وغير المعدود ويعني ( العديد من)؟', 'options' => ['Many', 'Much', 'A lot of', 'Any'], 'correct' => 2],
        ['text' => 'لو كان الاسم (غير معدود) وأريد ان أقول بانه (قليل) فاستخدم محدد الكمية التالي؟', 'options' => ['Many', 'Much', 'A lot of', 'A little'], 'correct' => 3],
        ['text' => 'ما معنى جملة (معجون طماطم قليل - شوية)؟', 'options' => ['A few tomato paste', 'A little tomato paste', 'Some tomato paste', 'Any tomato paste'], 'correct' => 1],
        ['text' => 'ما هو محدد الكمية الذي يجوز استخدامه مع المعدود وغير المعدود ويعني (بعض)؟', 'options' => ['Many', 'some', 'A lot of', 'A little'], 'correct' => 1],
        ['text' => 'ما معنى جملة (بعض من الجزر)؟', 'options' => ['Any carrots', 'Some carrots', 'A little carrots', 'A few carrots'], 'correct' => 1],
        ['text' => 'ما هي الأسماء المعدودة (Countable nouns)؟', 'options' => ['التي لا نجمعها', 'التي نجمعها بإضافة (S) او (Es) او تكون جمع شاذ', 'هي أسماء أساسها جمع ولا تكون مفرد', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اختر من الأسماء المعدودة (التي قابلة للعد):', 'options' => ['Egg – book – information – coffee', 'Egg – book – desk – board – candle', 'Egg – book – sugar – tea – salt', 'Olive oil – juice – wheat - flour'], 'correct' => 1],
        ['text' => 'ما هي الأسماء الغير المعدودة (Uncountable nouns)؟', 'options' => ['التي لا نستطيع جمعها', 'التي نجمعها بإضافة (S) او (Es) او تكون جمع شاذ', 'هي أسماء أساسها جمع ولا تكون مفرد', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر من الأسماء الغيرمعدودة (التي لا تقبل الجمع):', 'options' => ['Egg – book – information – coffee', 'Egg – book – desk – board – candle', 'Egg – book – sugar – tea – salt', 'Olive oil – juice – wheat - flour'], 'correct' => 3],
        ['text' => 'نعتبر كلمة (مياه water) غير معدودة فعند تحديدها لا بد ان نضيف عليها وحدة قياس مثل (A bottle of water).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],

        [
            'text' => 'صل ما بين كل اسم ووحدة القياس المناسبة له (المجموعة الأولى):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Pasta', 'right' => 'A bag of'],
                ['left' => 'Cooking oil', 'right' => 'A can of'],
                ['left' => 'Chocolate', 'right' => 'A bar of'],
                ['left' => 'Milk', 'right' => 'A carton of'],
                ['left' => 'Potatoes', 'right' => 'A kilo of'],
            ]
        ],
        [
            'text' => 'صل ما بين كل اسم ووحدة القياس المناسبة له (المجموعة الثانية):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Juice', 'right' => 'A bottle of'],
                ['left' => 'Apples', 'right' => 'A kilo of'],
                ['left' => 'Gifts', 'right' => 'A bag of'],
            ]
        ],

        ['text' => 'لإظهار درجة محدد الكمية فإننا نضع قبله:', 'options' => ['اسم', 'صفة', 'حال او ظرف مثل Too\very', 'فعل'], 'correct' => 2],
        ['text' => 'اختر من الظروف التي نضيفها قبل محددات (الكمية للمعدود):', 'options' => ['So - more –too', 'So – quickly – fast', 'Too – more – hard', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'للسؤال عن محددات الكمية فإننا نستخدم أداة السؤال؟', 'options' => ['What', 'How', 'Who', 'When'], 'correct' => 1],
        ['text' => 'نستخدم أداة السؤال (How much) للسؤال عن؟', 'options' => ['الكمية', 'السعر', 'العدد', 'ا + ب'], 'correct' => 3],
        ['text' => 'نستخدم أداة السؤال (How many) للسؤال عن؟', 'options' => ['الكمية', 'السعر', 'العدد', 'ا + ب'], 'correct' => 2],
        ['text' => '(I need _ water) اختر محدد الكمية المناسب لـ (انا احتاج بعض من الماء):', 'options' => ['Many', 'A little', 'Some', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => '(Do you have _ sweater?) اختر محدد الكمية المناسب لـ (هل لديك أي بلوزة صوف):', 'options' => ['Many', 'Some', 'Any', 'A little'], 'correct' => 2],
        ['text' => '(we have just _ time) اختر محدد الكمية المناسب لـ (لدينا القليل من الوقت):', 'options' => ['A little', 'A few', 'Much', 'Some'], 'correct' => 0],
        ['text' => '(----- people came to the party) اختر محدد الكمية المناسب لـ (الكثير من الناس أتوا للحفلة):', 'options' => ['Much', 'A little', 'A lot of', 'A few'], 'correct' => 2],
        ['text' => '(The exam was difficult, _ students passed it) اختر محدد الكمية المناسب:', 'options' => ['Much', 'A little', 'A lot of', 'A few'], 'correct' => 3],
        ['text' => 'لا يوجد فرق بين المحدد (a few) والمحدد (few).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'محدد الكمية (A few) يركز على القلة اكثر من محدد الكمية (few).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'جملة (I have few coins) توضح:', 'options' => ['انني امتلك القليل من العملات المعدنية', 'انني لدي القليل جدا (دلالة على الندرة والقلة)', 'انني لدي الكثير من العملات المعدنية', 'انني لدي الكثير جدا منهم'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح لسؤال يبدأ بـ (how much) للسؤال عن السعر:', 'options' => ['How much + auxiliary verb + subject + v', 'How much + subject + v', 'How much +v + auxiliary + subject', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لسؤال يبدأ بـ (how much) للسؤال عن كمية لغير المعدود:', 'options' => ['How much + auxiliary verb + subject + v', 'How much + noun + auxiliary verb + subject + v', 'How much + subject + v', 'How much +v + auxiliary + subject'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح لسؤال يبدأ بـ (how many) للسؤال عن العدد للمعدود:', 'options' => ['How many + auxiliary verb + subject + v', 'How many + noun + auxiliary verb + subject + v', 'How many + subject + v', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح لسؤال يبدأ بـ (how much) للسؤال عن السعر في حال كانت Be هي الفعل الأساسي:', 'options' => ['How much + be + subject \ complement?', 'How much +v + auxiliary + subject?', 'How much + subject + v1?', 'How much + do \does+ subject + v1?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لسؤال يبدأ بـ (how many) للسؤال عن العدد:', 'options' => ['How many + be + subject + v?', 'How many + noun + auxiliary verb + subject + v?', 'How many + subject + v?', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(I need 3 kilos of apples) اختر السؤال الأنسب عن عدد التفاح:', 'options' => ['How much apples do you need?', 'How many apples do you need?', 'How many do you need?', 'How much do you need?'], 'correct' => 1],
        ['text' => '(My coat costs 100 dollars) اختر السؤال الأنسب عن سعر المعطف:', 'options' => ['How much does your coat cost?', 'How many does your coat cost?', 'How much do your coat cost?', 'How much is your coat cost?'], 'correct' => 0],
        ['text' => '(I’d like a little sugar in my tea) اختر السؤال الأنسب عن كمية السكر:', 'options' => ['How many sugar do you need?', 'How much sugar do you need?', 'How many sugar does you need?', 'How many sugar does you need?'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة لجملة (كم كمية المياه التي ينبغي ان اشربها يوميا)؟', 'options' => ['How much water do I need each daily?', 'How much water can I drink each daily?', 'How much water should I drink each daily?', 'How much water does I drink each daily?'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد محددات الكمية (Quantifiers Grammar)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 60,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1125.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
