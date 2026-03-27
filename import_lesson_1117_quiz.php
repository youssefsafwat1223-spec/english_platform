<?php

/**
 * Script to import questions for Lesson ID 1117 (Comparison Grammar)
 * php import_lesson_1117_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1117;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1117 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى (Comparison)؟', 'options' => ['أهمية', 'مقارنة', 'تعريف', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'ما الذي يحدد نوع الإضافة في المقارنة او التفضيل سواء( more – er)؟', 'options' => ['موقعها (صفة قبلية – صفة بعدية )', 'عدد مقاطعها اللفظية', 'نوعها ( صفة فاعل – صفة مفعول به)', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'عند مقارنة شخصيين او شيئين ببعض (وكانت الصفة أحادية المقطع، تتكون من مقطع واحد) فإننا:', 'options' => ['نضيف er على الصفة ثم than بعدها', 'نضيف more قبل الصفة ثم than بعدها', 'نضيف the most قبلها', 'نضيف the قبلها ثم نضيف est على الصفة'], 'correct' => 0],
        ['text' => 'عند مقارنة شخص او شيء بمجموعة باستخدام صفة أحادية المقطع ( تتكون من مقطع واحد) فإننا:', 'options' => ['نضيف er على الصفة ثم than بعدها', 'نضيف more قبل الصفة ثم than بعدها', 'نضيف the most قبلها', 'نضيف the قبلها ثم نضيف est على الصفة'], 'correct' => 3],
        ['text' => 'عند مقارنة شخصين او شيئين باستخدام صفة متعددة المقاطع ( تتكون من مقطعين او اكثر) فإننا:', 'options' => ['نضيف er على الصفة ثم than بعدها', 'نضيف more قبل الصفة ثم than بعدها', 'نضيف the most قبلها', 'نضيف the قبلها ثم نضيف est على الصفة'], 'correct' => 1],
        ['text' => 'عند مقارنة شخص او شيء بمجموعة باستخدام صفة متعددة المقاطع ( تتكون من مقطعين او اكثر) فإننا:', 'options' => ['نضيف er على الصفة ثم than بعدها', 'نضيف more قبل الصفة ثم than بعدها', 'نضيف the most قبلها', 'نضيف the قبلها ثم نضيف est على الصفة'], 'correct' => 2],
        ['text' => 'ما الترجمة الصحيحة لجملة ( خالد يكون انحف من احمد)؟', 'options' => ['Khalid is more thinner than Ahmed.', 'Khalid is thinner than Ahmed.', 'Ahmed is thinner than Khalid.', 'Khalid is thinnest than Ahmed.'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة لجملة ( هبة تكون جدية)؟', 'options' => ['Heba is more serious.', 'Heba is serious.', 'Heba is seriouser.', 'Heba is most serious.'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة لجملة (السيارة السوداء تكون أغلى من السيارة البيضاء):', 'options' => ['The black car is expensiver than the white car.', 'The black car is more expensive than the white car.', 'The black car is most expensive than the white car.', 'The black car is the expensivest the white car.'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة لجملة ( هذا افضل غروب شمس رايته على الاطلاق):', 'options' => ['This is the most beautiful sunset I’ve ever seen.', 'This is the more beautiful sunset I’ve ever seen.', 'This is most beautiful than sunset I’ve ever seen.', 'This is the beautifulest sunset I’ve ever seen.'], 'correct' => 0],
        ['text' => 'في جملة (This is the tallest building in the city.) اضفنا the قبل الصفة ثم اضفنا est على الصفة لأن؟', 'options' => ['ا - الصفة أحادية المقطع ( تتكون من مقطع واحد)', 'ب – لان يقارن بين شيء ومجموعة', 'ج – لان الصفة متعددة المقاطع ( تتكون من مقطعين او اكثر)', 'د – ا + ب'], 'correct' => 3],
        ['text' => 'في جملة (The most important part of the exam was the essay question) اضفنا the most قبل الصفة ولم نضيف عليها est لأن؟', 'options' => ['لأنها صفة متعددة المقاطع وإذا اضفنا عليها أي مقطع اخر تصبح صعبة القراءة', 'لأنها تقارن بين اثنين', 'لأنها تقارن بين مجموعة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'كيف نضيف المقطع (Er) او (est) على الصفات التي تنتهي بحرف ساكن مسبوق بحرف علة؟', 'options' => ['نضيف المقطع ولا نفعل أي شيء بالكلمة', 'ندبل الحرف الأخير (نكتبه مرتان) ثم نضيف المقطع', 'نقلب الحرف الأخير الى i ثم نضيف المقطع', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'كيف نضيف المقطع (Er) او (est) على الصفات التي تنتهي بحرف ساكن (غير) مسبوق بحرف علة؟', 'options' => ['نضيف المقطع ولا نفعل أي شيء بالكلمة', 'ندبل الحرف الأخير ثم نضيف المقطع', 'نقلب الحرف الأخير الى i ثم نضيف المقطع', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'كيف نضيف المقطع (Er) او (est) على الصفة التي تنتهي بحرف y مسبوق بحرف علة؟', 'options' => ['نضيف المقطع ولا نفعل أي شيء بالكلمة', 'ندبل الحرف الأخير ثم نضيف المقطع', 'نقلب حرف y الى i ثم نضيف المقطع', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'عند إضافة er الى الصفة (Big) تصبح:', 'options' => ['Biger', 'Bigger', 'Bigier', 'Biggier'], 'correct' => 1],
        ['text' => 'لا نضيف (est) للصفة (ثقيل heavy) عند المقارنة لأنها صفة تتكون من مقطعين.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'نضيف (Er) للصفة (جميل Beautiful) عند المقارنة لأننا نعتبرها صفة تتكون من مقطع واحد.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'المعنى الصحيح لـ (More interesting than) هو:', 'options' => ['المثير للاهتمام', 'اكثر اثارة للاهتمام من', 'الأكثر اثارة للاهتمام', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'المعنى الصحيح لـ (Smaller than) هو:', 'options' => ['صغير', 'أصغر من', 'الأصغر', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'المعنى الصحيح لـ (The greatest) هو:', 'options' => ['عظيم', 'اعظم من', 'الأعظم', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'المعنى الصحيح لـ (The most boring) هو:', 'options' => ['ممل', 'الأكثر مللا', 'الأكثر مللا من', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'المعنى الصحيح ل (أبطئ من ) هو:', 'options' => ['Slow', 'Slower than', 'The slowest', 'More slow than'], 'correct' => 1],
        ['text' => 'المعنى الصحيح ل ( الأفقر) هو:', 'options' => ['Poor', 'Poorer than', 'The poorest', 'The most poor'], 'correct' => 2],
        ['text' => 'المعنى الصحيح ل ( الأكثر جاذبية) هو:', 'options' => ['Attractive', 'Attractiver', 'More attractive than', 'The most attractive'], 'correct' => 3],
        ['text' => 'المعنى الصحيح ل (اكثر نجاحا من ) هو:', 'options' => ['Successful', 'More successful than', 'The most successful', 'Successfullest'], 'correct' => 1],
        ['text' => '(Boxing is _ dangerous than karate) اختر الكلمة المناسبة:', 'options' => ['The', 'More', 'Most', 'Than'], 'correct' => 1],
        ['text' => '(she is smarter _ her sister) اختر الكلمة المناسبة:', 'options' => ['The', 'More', 'Most', 'Than'], 'correct' => 3],
        ['text' => '(This project is _ difficult than the previous one) اختر الكلمة المناسبة:', 'options' => ['The', 'More', 'Most', 'Than'], 'correct' => 1],
        ['text' => '(The blue whale is _ largest animal on Earth) اختر الكلمة المناسبة:', 'options' => ['The', 'More', 'Most', 'Than'], 'correct' => 0],
        ['text' => 'عند مقارنة شيئين او شخصين يحملون نفس الصفة( نفس المستوى) فإننا نستخدم:', 'options' => ['A – an', 'As – as', 'The – than', 'More – most'], 'correct' => 1],
        ['text' => 'نضع بين ( As – as):', 'options' => ['صفة بدون إضافات المقارنة مثل er \more', 'صفة اخرها er او صفة قبلها more', 'صفة اخرها est او صفة قبلها most', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'عندما نقول بان (أحمد يكون طويل مثل فارس) فان انسب ترجمة لها:', 'options' => ['Ahmed is taller than Faris.', 'Ahmed is as tall as Faris.', 'Ahmed is the tallest Faris.', 'Ahmed is more tall than Faris.'], 'correct' => 1],
        ['text' => '(This is ____ day of my life) اختر المناسب لـ (هذا يكون اسعد يوم في حياتي):', 'options' => ['Happier than', 'More happy than', 'The happiest', 'The most happy'], 'correct' => 2],
        ['text' => '(This is ____ ice cream I ’ve ever tasted) اختر المناسب لـ (هذه تكون الذ مثلجات قد تذوقتها):', 'options' => ['The most delicious', 'More delicious than', 'Deliciouser than', 'The deliciousest'], 'correct' => 0],
        ['text' => '(coffee is ____ tea in the United States) اختر المناسب لـ (القهوة تكون اكثر شهرة من الشاي):', 'options' => ['The most popular', 'More popular than', 'Popularer', 'Populariest'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المقارنة (Comparison Grammar)',
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

        $props['option_a'] = $qData['options'][0] ?? null;
        $props['option_b'] = $qData['options'][1] ?? null;
        $props['option_c'] = $qData['options'][2] ?? null;
        $props['option_d'] = $qData['options'][3] ?? null;
        $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1117.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
