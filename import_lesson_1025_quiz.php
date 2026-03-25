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
        ['text' => 'I was calling 911 while he was performing first aid. اختر الترجمة الصحيحة:', 'options' => ['سوف اتصل', 'أدى الإسعافات', 'كنت اتصل بـ 911 بينما هو كان يؤدي الإسعافات الأولية.', 'اتصلت بينما كان يؤدي'], 'correct' => 2],
        ['text' => 'The dog was barking all day yesterday. اختر الترجمة الصحيحة:', 'options' => ['ينبح طول اليوم الليلة الفائتة', 'الكلب كان ينبح طول اليوم بالأمس.', 'ينبح طول اليوم غداً', 'سوف ينبح'], 'correct' => 1],
        ['text' => 'It was raining last night. اختر الترجمة الصحيحة:', 'options' => ['انها كانت تمطر الليلة الفائتة.', 'سوف تمطر', 'امطرت', 'تمطر كل يوم'], 'correct' => 0],
        ['text' => 'Abdullah was doing a great job. اختر الترجمة الصحيحة:', 'options' => ['عمل عمل عظيم', 'عبدالله كان يعمل عمل عظيم.', 'سوف يعمل', 'يعمل عمل عظيم'], 'correct' => 1],
        ['text' => 'The teacher was encouraging us. الترجمة الصحيحة هي:', 'options' => ['المعلم كان يشجعنا.', 'يشجعهم', 'اعطانا مكافأة', 'يشجعنا'], 'correct' => 0],
        ['text' => 'ترجمة: (الشرطة كانت تطارد الرجل بينما انت كنت تتكلم على الهاتف):', 'options' => ['police were chasing... while you talked', 'police were chasing the man while you were talking over the phone.', 'police chased... while you were talking', 'are chasing...'], 'correct' => 1],
        ['text' => 'ترجمة: (انا كنت ابكي بينما اخي كان يضحك):', 'options' => ['I was crying while my brother was laughing.', 'when my brother...', 'sister...', 'I cried while brother laughed'], 'correct' => 0],
        ['text' => 'ترجمة: (الطلبة الجدد كانوا يعطوا انتباه بينما انا كنت القي محاضرة):', 'options' => ['New students were paying attention while I was lecturing.', 'New teachers...', 'when I was...', 'are paying...'], 'correct' => 0],
        ['text' => 'ترجمة: (الممثلون كانوا يمثلون بينما المصور كان يصور):', 'options' => ['Actors acted', 'Nurses were...', 'Actors were acting while the cameraman was filming.', 'cameraman were acting'], 'correct' => 2],
        ['text' => 'ترجمة: (عبد الكريم كان يستمع بينما اخي كان يضايقني):', 'options' => ['AbdulKareem was listening while my brother was bothering me.', 'My brother was listening...', 'listened while...', 'was listening while bothered'], 'correct' => 0],
        ['text' => 'ترجمة: (امي ما كانت تغسل الاطباق بينما التلفون كان يرن):', 'options' => ['My mum wasn’t washing the dishes while the telephone was ringing.', 'ترتب الاطباق', 'كانت تغسل', 'ما كان يرن'], 'correct' => 0],
        ['text' => 'ترجمة: (فهد ما كان يتحدث باستمرار عن ألعاب الفيديو):', 'options' => ['كان يتحدث', 'Fahad wasn’t constantly talking about video games.', 'ما تحدث', 'عن الأفلام'], 'correct' => 1],
        ['text' => 'ترجمة: (جدي ما كان دائماً يقرأ الجرائد):', 'options' => ['My grandpa wasn’t always reading newspapers.', 'constantly reading', 'grandma', 'reading magazines'], 'correct' => 0],
        ['text' => 'ترجمة: (هبة ما كانت دائماً تحصل على درجات عالية في الاختبارات):', 'options' => ['always got', 'Heba wasn’t always getting high grades in the exams.', 'weren’t always', 'getting high grades in games'], 'correct' => 1],
        ['text' => 'ترجمة: (المستأجرين ما كانوا دائماً يشكون عن الازعاج):', 'options' => ['complain about noise', 'كانوا دائماً', 'The residents weren’t always complaining about the noise.', 'عن المهملات'], 'correct' => 2],
        ['text' => 'ترجمة: Was chef cooking while assistant was cutting vegetables? ', 'options' => ['هل الطباخ كان يطبخ بينما المساعدة كانت تقطع الخضار؟', 'هل الطباخ طبخ', 'عندما المساعدة', 'بينما المعلمة'], 'correct' => 0],
        ['text' => 'ترجمة: Was detective searching my room while colleague searching other? ', 'options' => ['القاضي يفتش', 'زميله يفتش', 'هل كان المحقق يفتش غرفتي بينما زميله كان يفتش الغرفة الأخرى؟', 'اللص يفتش'], 'correct' => 2],
        ['text' => 'ترجمة: (هل كانت زوجتك دائماً تشرب الكثير من القهوة؟):', 'options' => ['Was your wife always drinking too much coffee?', 'wife always driking (duplicate check)', 'wife always drink', 'wife always (no ?)'], 'correct' => 0],
        ['text' => 'Was Seba wondering if she could get help? الترجمة هي:', 'options' => ['اذا بإمكانها (هو)', 'هل كانت صبا تتساءل اذا بإمكانها (هي) أن تحصل على مساعدة؟', 'تتطلب هي', 'تحصل على المال'], 'correct' => 1],
        ['text' => 'ترجمة: (هل كانوا البحارة يتسابقون في البحر؟):', 'options' => ['Were the sailors racing in the sea?', 'Was the sailors', 'race in', 'racing in ocean'], 'correct' => 0],
        ['text' => '(Announcing – the – departure – was – pilot – the) اعد الترتيب:', 'options' => ['pilot announcing was', 'The pilot was announcing the departure.', 'Departure was announcing', 'was the announcing'], 'correct' => 1],
        ['text' => '(Good – reading – always – he – was –at – charts) اعد الترتيب:', 'options' => ['Charts was always...', 'He was always good reading...', 'He was always good at reading charts.', 'He always was good at'], 'correct' => 2],
        ['text' => '(The - ? – was – pharmacist – the – preparing – medicine) اعد الترتيب:', 'options' => ['pharmacist preparing? medicine', 'pharmacist medicine preparing', 'medicine preparing pharmacist', 'Was the pharmacist preparing the medicine?'], 'correct' => 3],
        ['text' => '(Was – I – to – adapting – since – town – got – here – I) اعد الترتيب:', 'options' => ['new town was adapting...', 'I was adapting to the new town since I got here.', 'adapting since... to new town', 'to adapting the new town'], 'correct' => 1],
        ['text' => '(Studying – loud – when – I – was – music – played – you) اعد الترتيب:', 'options' => ['I was studying when you played loud music.', 'Loud music was studying...', 'I played when you was...', 'I when was studying'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة وترتيب الماضي المستمر (Past Continuous Translation)',
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
