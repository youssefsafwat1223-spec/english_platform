<?php

/**
 * Script to import questions for Lesson ID 1005 (Present Perfect Continuous Translation)
 * php import_lesson_1005_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1005;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1005 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I have been reading Quran for a month now. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا صارلي اقرأ قرآن لمدة شهر الان.', 'انا اقرأ قرآن', 'انا قرأت قرآن', 'انا كنت اقرأ'], 'correct' => 0],
        ['text' => 'I haven’t been feeling well lately. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا صارلي اشعر بخير', 'انا ما صارلي اشعر بخير مؤخرا.', 'انا ما شعرت بخير', 'انا لا اشعر بخير'], 'correct' => 1],
        ['text' => 'We have been waiting here for two hours. اختر الترجمة الصحيحة للجملة:', 'options' => ['نحن صارلنا ننتظر هنا لمدة ساعتين.', 'منذ ساعتين', 'انتظرنا هنا', 'لا ننتظر هنا'], 'correct' => 0],
        ['text' => 'He has been searching for his lost key since morning. اختر الترجمة الصحيحة:', 'options' => ['المفتاح الضائع', 'ما صارله يبحث', 'هو صارله يبحث عن مفتاحه المفقود منذ الصباح.', 'فقد مفتاحه'], 'correct' => 2],
        ['text' => 'ترجمة: (أنا صارلي ابحث عن جاكيت مصنوع من الجلد لمدة شهر)', 'options' => ['I have been looking...', 'انا أبحث عن جاكيت...', 'انا بحثت...', 'انا صارلي ابحث عن جاكيت مصنوع من الجلد لمدة شهر.'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة للجملة (صبا صارلها تبحث عن معلومات منذ الظهيرة.)', 'options' => ['Seba has been searching... for noon.', 'Seba has been searching for information since noon.', 'Seba have been...', 'Seba has searching...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (بشرتي صارلها تحكني مؤخرا)', 'options' => ['My skin has itching...', 'My skin has been itching me lately.', 'skin has be itching...', 'skin have been...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (سعد صارله يحاول ان ينهض من السرير منذ الصباح.)', 'options' => ['Saad have been...', 'Saad has been trying... for morning.', 'Saad has been trying to get out of bed since morning.', 'Saad has trying...'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (ظافر صارله ينظف الكراج منذ السادسة صباحا.)', 'options' => ['Dhafer has been... for 6:00', 'Dhafer have been...', 'Dhafer has been cleaning the garage since 6:00 am', 'Dhafer cleaned...'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة (فاطمة صارلها تبحث عن نظاراتها الشمسية لمدة ثلاث ساعات.)', 'options' => ['Fatima has been searching for sunglasses for 3 hours.', 'Fatima has searching...', 'has searching since', 'is searching for'], 'correct' => 0],
        ['text' => 'ترجمة: (عبد الرازق ما صارله يبحث عن بنطال جينز في المجمع التجاري لمدة ساعة.)', 'options' => ['Abdulrazaq hasn’t been looking for a pair of jeans at the mall for an hour.', 'صارله يبحث', 'منذ ساعة', 'بحث عن جينز'], 'correct' => 0],
        ['text' => 'ترجمة: (فيصل ما صارله يدرس مادة هندسة ميكانيكية لمدة ثلاث ساعات.)', 'options' => ['لا يدرس', 'Faisal hasn’t been studying mechanical engineering course for 3 hours.', 'صارله يدرس', 'ما درس مادة'], 'correct' => 1],
        ['text' => 'ترجمة: (القطار ما صارله ينتظر في المحطة منذ الأسبوع الفائت.)', 'options' => ['have been waiting', 'The train has not been waiting at the station since last week.', 'not has been', 'has been waited'], 'correct' => 1],
        ['text' => 'ترجمة: (برنامج مكافحة الفيروسات ما صارله يستهدف الفيروسات في النظام منذ أمس.)', 'options' => ['يستهدف الفيروسات', 'لا يستهدف', 'anti-virus software hasn’t been targeting viruses... since yesterday.', 'لمدة أمس'], 'correct' => 2],
        ['text' => 'ترجمة: (منى ما صارلها تحلل معلومات لمدة آخر ثلاث سنوات.)', 'options' => ['Muna has not been analyzing data for the past 3 years.', 'صارلها تحلل', 'حللت معلومات', 'لا تحلل'], 'correct' => 0],
        ['text' => 'Has the lion been looking for the prey since morning? الترجمة الصحيحة هي:', 'options' => ['بحث عن الفريسة', 'هل الأسد صارله يبحث عن الفريسة منذ الصباح؟', 'لمدة الصباح', 'هل النمر'], 'correct' => 1],
        ['text' => 'Has the university has been offering free excellent courses since last month? الترجمة الصحيحة:', 'options' => ['المدرسة', 'هل الجامعة صارلها تقدم دروس مجانية ممتازة منذ الشهر الفائت؟', 'قدمت دروس', 'تقدم دروس'], 'correct' => 1],
        ['text' => 'اختر الترجمة للسؤال: (هل الفيل صارله يبكي على طفله المفقود لمدة يومين؟)', 'options' => ['monkey...', 'Has the elephant been crying on its lost baby for two days?', 'cried...', 'mother...'], 'correct' => 1],
        ['text' => 'Has Faleh been playing table tennis since he was 10 years old? الترجمة الصحيحة:', 'options' => ['هل فالح صارله يلعب تنس منذ ان كان عمره 10 سنوات؟', 'لمدة ان كان', 'كرة السلة', 'لعب تنس'], 'correct' => 0],
        ['text' => 'اختر الترجمة للسؤال: (هل المعلمون صارلهم يصححون معلوماتكم طوال اليوم؟)', 'options' => ['Have the teachers been correcting your information you all day long?', 'Has the teachers...', 'teachers corrected...', 'doctors been...'], 'correct' => 0],
        ['text' => '(CEO – the – company – been –over – years – five – for – the -has – managing) اعد ترتيب الجملة:', 'options' => ['managing been...', 'The CEO has been managing the company for over 3 years.', '3 over years', 'managing the CEO'], 'correct' => 1],
        ['text' => '(Been – have – cave men – the – animals – hunting – centuries – for – many) اعد ترتيبها:', 'options' => ['hunting been...', 'been hunting animals many for...', 'The cave men have been hunting animals for many centuries.', 'hunting animals have been'], 'correct' => 2],
        ['text' => '(You – been – work - ? – imagining – have – away –life – your –from) اعد ترتيبها:', 'options' => ['Have you been imagining your life away from work?', 'work your life away...', '? your life away...', 'Have been you...'], 'correct' => 0],
        ['text' => '(Been – smelling -food– I’ve –good – since – in – the – walked – restaurant.) اعد ترتيبها:', 'options' => ['food good since...', 'I’ve been smelling good food since I walked in the restaurant.', 'I since walked...', 'food I walked since'], 'correct' => 1],
        ['text' => '(She- ’S– attention – her – keep – been – entire – trying – to – lesson – the) اعد ترتيبها:', 'options' => ['her attention the to keep...', 'trying been to keep...', 'She’s been trying to keep her attention the entire lesson.', 'trying to keep She’s'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ترجمة وترتيب (Present Perfect Continuous)',
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
            'option_a' => $qData['options'][0] ?? null,
            'option_b' => $qData['options'][1] ?? null,
            'option_c' => $qData['options'][2] ?? null,
            'option_d' => $qData['options'][3] ?? null,
            'correct_answer' => $letterMap[$qData['correct']] ?? 'A',
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1005.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
