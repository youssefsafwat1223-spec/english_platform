<?php

/**
 * Script to import questions for Lesson ID 988 (Present Continuous Translation/Ordering)
 * php import_lesson_988_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 988;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 988 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'He is studying for his driver\'s license test. اختر الترجمة الصحيحة بالجملة:', 'options' => ['هو قاعد يدرس لاختبار رخصة قيادته.', 'هي مو قاعد يدرس لاختبار رخصة قيادته.', 'هو درس لاختبار رخصة قيادته.', 'هو قد درس لاختبار رخصة قيادته.'], 'correct' => 0],
        ['text' => 'We are watching a movie while eating popcorn. اختر الترجمة الصحيحة للجملة:', 'options' => ['أنا نشاهد فيلم بينما نتناول الفشار.', 'نحن نشاهد فيلم بينما نتناول الفشار.', 'نحن شاهدنا فيلم بينما تناولنا الفشار.', 'نحن قد شاهدنا فيلم بينما تناولنا الفشار.'], 'correct' => 1],
        ['text' => 'Nourah is taking care of her child at the moment. اختر الترجمة الصحيحة للجملة:', 'options' => ['نورا قاعدة تعتني بطفلها في هذه اللحظة.', 'نورا اعتنت بطفلها.', 'نورا قد اعتنت بطفلها بالأمس.', 'نورا سوف تعتني بطفلها غدا.'], 'correct' => 0],
        ['text' => 'Alaa is playing board games with his friends. اختر الترجمة الصحيحة للجملة:', 'options' => ['الاء قاعدة لعبت لعبه اللوحة مع اصدقائها', 'علاء قاعد يلعب لعبة اللوحة مع اصدقاؤه.', 'علاء لعب لعبة اللوحة مع اصدقاؤه', 'علاء سوف يلعب لعبة اللوحة مع اصدقاؤه.'], 'correct' => 1],
        ['text' => 'Abdullah is fixing his bicycle in the garage. اختر الترجمة الصحيحة للجملة:', 'options' => ['عبدالله صلح دراجته في الكراج.', 'عبدالله قد صلح دراجته في الكراج.', 'عبدالله قاعد يصلح دراجته في الكراج.', 'عبدالله سوف يصلح دراجته في الكراج.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (فاطمة قاعدة تتسوق لشراء الشموع في المركز التجاري.)', 'options' => ['Fatima is shopping for candles at the mall.', 'Fatima are shopping for candles at the mall.', 'Fatima has shopped for candles at the mall.', 'Fatima has been shopping for candles at the mall.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة (إسماعيل قاعد يصطاد في البحيرة الآن.)', 'options' => ['Ismael is fishing at the lake.', 'Ismael is fishing at the lake now.', 'Ismael is fishing at the lake yesterday.', 'Ismael has fished at the lake recently.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (هذه الأيام معظم الناس قاعدين يستخدمون البريد الالكتروني بدلا من كتابة الرسائل.)', 'options' => ['most people is using...', 'most people used...', 'These days most people are using email instead of writing letters.', 'These days most people are using writing letters instead of email.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (فيروز قاعدة تنمو بسرعة)', 'options' => ['Fairouz are growing up quickly.', 'Fairouz is growing up quickly.', 'Fairouz is growing up quick.', 'Fairouz grows up quickly.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (التغير المناخي قاعد يتغير بوتيرة سريعة)', 'options' => ['The climate is changing rapid.', 'The climate are changing rapidly.', 'The climate is changing rapidly.', 'The climate is changing slowly.'], 'correct' => 2],
        ['text' => 'It\'s not always raining in Kuwait. اختر الترجمة الصحيحة للجملة:', 'options' => ['انها لا تمطر دائما في الكويت.', 'انها تمطر دائما في الكويت.', 'انها ما امطرت في الكويت.', 'انها سوف لن تمطر في الكويت.'], 'correct' => 0],
        ['text' => 'They aren’t laughing at my jokes. اختر الترجمة الصحيحة للجملة:', 'options' => ['هم سوف لا يضحكون على نكاتي.', 'هم لا (مو قاعدين) يضحكون على نكاتي.', 'هم ما ضحكوا على نكاتي.', 'هم ضحكوا على نكاتي.'], 'correct' => 1],
        ['text' => 'The politicians are not discussing the latest news now. اختر الترجمة الصحيحة للجملة:', 'options' => ['السياسيون مو جالسين يناقشون اخر الاخبار الان.', 'السياسيون يناقشون اخر الاخبار الان.', 'السياسيون ما ناقشوا اخر الاخبار الان.', 'السياسيون سوف لن يناقشوا اخر الاخبار الان.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة (نبيل لا يحل اللغز في هذه اللحظة)', 'options' => ['Nabeel are not solving...', 'Nabeel is solving...', 'Nabeel is not solving the puzzle at the moment.', 'Nabeel solves the puzzle at the moment.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة (بدر سوف لن يجري اجتماع عمل غدا)', 'options' => ['Bader is conducting...', 'Bader is not conducting a business meeting tomorrow.', 'Bader are not conducting...', 'Bader will conducting...'], 'correct' => 1],
        ['text' => 'Is the doctor using his statoscope? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قاعد المعلم يستخدم سماعته؟', 'هل قاعد الدكتور يستخدم جهاز الضغط؟', 'هل قاعد الدكتور يستخدم سماعته الطبية؟', 'هل الدكتور لا يستخدم سماعته الطبية؟'], 'correct' => 2],
        ['text' => 'Is the nurse helping the doctor? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل الطبيبة تساعد الطبيب؟', 'هل قاعدة الممرضة تساعد الطبيب؟', 'هل قاعدة الممرضة تتصل بالطبيب؟', 'هل الممرضة تعالج المريض؟'], 'correct' => 1],
        ['text' => 'Is the butcher opening his shop these days? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل الجزار يفتح دكانه هذه الأيام؟', 'هل الخباز يفتح دكانه هذه الأيام؟', 'هل الحداد يفتح دكانه هذه الأيام؟', 'هل قاعد الجزار يغلق دكانه هذه الأيام؟'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل الممرضة قاعدة تحضر للعملية الجراحية؟)', 'options' => ['Is the doctor preparing...', 'Is the nurse preparing for the surgery?', 'Is the nurse preparing for the workshop?', 'Are the nurse preparing...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل قاعد رجل الإطفاء يطفئ النار الآن؟)', 'options' => ['Does the fire fighter...', 'Is the fire fighter putting out the fire now?', 'Are the fire fighter...', 'Does the paramedic...'], 'correct' => 1],
        ['text' => 'Farmer – goods – selling – is – at- his –the - market – the اعد ترتيب الجملة:', 'options' => ['The farmer is his selling...', 'The farmer selling is his...', 'The farmer is selling his goods at the market.', 'Farmer is selling the his goods...'], 'correct' => 2],
        ['text' => '(New – the – scientist -always– is – learning – methods -? ) اعد ترتيب السؤال:', 'options' => ['Is the scientist always learning new methods?', 'Is scientist always the learning new methods?', 'Is the scientist learning always new methods?', 'Is the scientist always learning methods new?'], 'correct' => 0],
        ['text' => 'Not – are – you – solving – assignment – this. اعد ترتيب الجملة المنفية:', 'options' => ['You not are solving this assignment.', 'You are not solving this assignment.', 'You are not this solving assignment.', 'Not you are solving this assignment.'], 'correct' => 1],
        ['text' => 'The – preparing – student – is – his –for – exam. اعد ترتيب الجملة:', 'options' => ['Student is preparing...', 'The exam is preparing for his student.', 'The student is preparing for his exam.', 'The student is preparing his for exam.'], 'correct' => 2],
        ['text' => 'Now – not – the – acting – actor – is. اختر الترتيب الصحيح للجملة المنفية:', 'options' => ['Not The actor is acting now.', 'The actor not is acting now.', 'The actor acting is not now.', 'The actor is not acting now.'], 'correct' => 3],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ترجمة وترتيب (Present Continuous)',
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
        $question = Question::create([
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => 'multiple_choice',
            'option_a' => $qData['options'][0],
            'option_b' => $qData['options'][1],
            'option_c' => $qData['options'][2],
            'option_d' => $qData['options'][3],
            'correct_answer' => $letterMap[$qData['correct']],
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 988.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
