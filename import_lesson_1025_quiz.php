<?php

/**
 * Script to import questions for Lesson ID 1025 (Past Continuous Translation)
 * php import_lesson_1025_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1025;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1025 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I was calling 911 while he was performing first aid. اختر الترجمة الصحيحة للجملة', 'options' => ['انا سوف اتصل ب 911 بينما هو سيطبق (سيؤدي) الإسعافات الأولية.', 'انا كنت اتصل ب 911 بينما هو أدى(طبق) الإسعافات الأولية.', 'انا كنت اتصل ب 911 بينما هو كان يؤدي (يطبق) الإسعافات الأولية.', 'انا اتصلت ب 911 بينما هو كان يؤدي الإسعافات الأولية.'], 'correct' => 2],
        ['text' => 'The dog was barking all day yesterday. اختر الترجمة الصحيحة للجملة', 'options' => ['الكلب ينبح طول اليوم الليلة الفائتة.', 'الكلب كان ينبح طول اليوم بالأمس.', 'الكلب ينبح طول اليوم بالأمس.', 'الكلب سوف ينبح طول اليوم غدا.'], 'correct' => 1],
        ['text' => 'It was raining last night. اختر الترجمة الصحيحة للجملة', 'options' => ['انها كانت تمطر الليلة الفائتة.', 'انها سوف تمطر غدا.', 'انها امطرت الليلة الفائتة.', 'انها تمطر كل يوم.'], 'correct' => 0],
        ['text' => 'Abdullah was doing a great job. اختر الإجابة الصحيحة للجملة', 'options' => ['عبدالله عمل عمل عظيم.', 'عبدالله كان يعمل عمل عظيم.', 'عبدالله سوف يعمل عمل عظيم.', 'عبدالله يعمل عمل عظيم.'], 'correct' => 1],
        ['text' => 'The teacher was encouraging us. اختر الترجمة الصحيحة للجملة', 'options' => ['المعلم كان يشجعنا.', 'المعلم كان يشجعهم.', 'المعلم اعطانا مكافأة.', 'المعلم يشجعنا.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الشرطة كانت تطارد الرجل بينما انت كنت تتكلم على الهاتف.)', 'options' => ['The police were chasing the man while you talked over the phone.', 'The police were chasing the man while you were talking over the phone.', 'The police chased the man while you were talking over the phone.', 'The police are chasing the man while you are talking over the phone.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( انا كنت ابكي بينما اخي كان يضحك.)', 'options' => ['I was crying while my brother was laughing .', 'I was crying when my brother was laughing .', 'I was crying while my sister was laughing .', 'I cried while my brother laughed .'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الطلبة الجدد كانوا يعطوا انتباه بينما انا كنت القي محاضرة.)', 'options' => ['New students were paying attention while I was lecturing.', 'New teachers were paying attention while I was lecturing.', 'New students were paying attention when I was lecturing.', 'New students are paying attention while I am lecturing.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الممثلون كانوا يمثلون بينما المصور كان يصور.)', 'options' => ['Actors acted while the cameraman was filming.', 'Nurses were acting while the cameraman was filming.', 'Actors were acting while the cameraman was filming.', 'The cameraman were acting while the actors was filming.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( عبد الكريم كان يستمع بينما اخي كان يضايقني.)', 'options' => ['AbdulKareem was listening while my brother was bothering me.', ' My brother was listening while Abdulkareem was bothering me.', 'AbdulKareem listened while my brother was bothering me.', 'AbdulKareem was listening while my brother bothered me.'], 'correct' => 0],
        ['text' => 'My mum wasn’t washing the dishes while the telephone was ringing. اختر الترجمة الصحيحة للجملة', 'options' => ['امي ما كانت تغسل الاطباق بينما التلفون كان يرن.', 'امي ما كانت ترتب الاطباق بينما التلفون كان يرن.', 'امي كانت تغسل الاطباق بينما التلفون كان يرن.', 'امي تغسل الاطباق بينما التلفون ما كان يرن.'], 'correct' => 0],
        ['text' => 'Fahad wasn’t constantly talking about video games. اختر الترجمة الصحيحة للجملة', 'options' => ['فهد كان يتحدث باستمرار عن ألعاب الفيديو.', 'فهد ما كان يتحدث باستمرار عن ألعاب الفيديو.', 'فهد ما تحدث باستمرار عن ألعاب الفيديو.', 'فهد ما كان يتحدث باستمرار عن الأفلام.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( جدي ما كان دائما يقرأ جرائد.)', 'options' => ['My grandpa wasn’t always reading newspapers.', 'My grandpa wasn’t constantly reading newspapers.', 'My grandma wasn’t always reading newspapers.', 'My grandpa wasn’t always reading magazines.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هبة ما كانت دائما تحصل على درجات عالية في الاختبارات.)', 'options' => ['Heba always got high grades in the exams.', 'Heba wasn’t always getting high grades in the exams.', 'Heba weren’t always getting high grades in the exams.', 'Heba was always getting high grades in the games.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (المستأجرين ما كانوا دائما يشكون عن الازعاج.)', 'options' => ['The residents weren’t always complaining about the noise.', 'The residents were always complaining about the noise.', 'The residents wasn’t always complaining about the noise.', 'The residents aren’t always complaining about the noise.'], 'correct' => 0],
        ['text' => 'Was the chef cooking while the assistant was cutting the vegetable? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل الطباخ كان يطبخ بينما المساعدة كانت تقطع الخضار؟', 'هل الطباخ طبخ بينما المساعدة كانت تقطع الخضار؟', 'هل الطباخ كان يطبخ عندما المساعدة كانت تقطع الخضار؟', 'هل الطباخ كان يطبخ بينما المعلمة كانت تقطع الخضار؟'], 'correct' => 0],
        ['text' => 'Was the detective searching my room while his colleague was searching the other room? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل كان القاضي يفتش غرفتي بينما زميله كان يفتش الغرفة الأخرى؟', 'هل كان زميله يفتش غرفتي بينما المحقق كان يفتش الغرفة الأخرى؟', 'هل كان المحقق يفتش غرفتي بينما زميله كان يفتش الغرفة الأخرى؟', 'هل كان اللص يفتش غرفتي بينما زميله كان يفتش الغرفة الأخرى؟'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل زوجتك كانت دائما تشرب الكثير من القهوة؟)', 'options' => [' Was your wife always drinking too much coffee?', 'Was your sister always drinking too much coffee?', 'Was your wife always drink too much coffee?', 'Was your wife always drinking too much coffee.'], 'correct' => 0],
        ['text' => 'Was Seba wondering if she could get help? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل كانت صبا تتساءل اذا بإمكانها (هو) ان يحصل على مساعدة؟', 'هل كانت صبا تتساءل اذا بإمكانها (هي) ان تحصل على مساعدة؟', 'هل كانت صبا تتطلب (هي) ان تحصل على مساعدة؟', 'هل كانت صبا تتساءل اذا بإمكانها (هي) ان تحصل على المال؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( هل كانوا البحارة يتسابقون في البحر؟)', 'options' => ['Were the sailors racing in the sea?', 'Was the sailors racing in the sea?', 'Were the sailors race in the sea?', 'Were the sailors racing in the ocean?'], 'correct' => 0],
        ['text' => 'Announcing – the – departure – was – pilot – the. اختر الترتيب الصحيح للجملة', 'options' => ['The pilot announcing was the departure.', 'The pilot was announcing the departure.', 'The departure was announcing the pilot.', 'The pilot was the announcing departure.'], 'correct' => 1],
        ['text' => 'Good – reading – always – he – was –at – charts. اختر الترتيب الصحيح للجملة', 'options' => ['Charts was always good at reading he.', 'He was always good reading at charts.', 'He was always good at reading charts.', 'He always was good at reading charts.'], 'correct' => 2],
        ['text' => 'The - ? – was – pharmacist – the – preparing – medicine اختر الترتيب الصحيح للسؤال', 'options' => ['Was the pharmacist preparing?the medicine', 'Was the pharmacist the medicine preparing ?', 'Was the medicine preparing the pharmacist?', 'Was the pharmacist preparing the medicine?'], 'correct' => 3],
        ['text' => 'اختر الترتيب الصحيح للجملة (Was – I – to – adapting – since – town – the- new – got – here – I)', 'options' => ['The new town was adapting to I since I got here.', 'I was adapting to the new town since I got here.', 'I was adapting since I got here to the new town.', 'I was to adapting the new town since I got here.'], 'correct' => 1],
        ['text' => 'Studying – loud – when – I – was – music – played – you اختر الترتيب الصحيح للجملة', 'options' => ['I was studying when you played loud music.', 'Loud music was studying when you played I.', 'I played when you was studying loud music.', 'I when was studying you played loud music.'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة الماضي المستمر (Past Continuous Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1025.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
