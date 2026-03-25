<?php

/**
 * Script to import questions for Lesson ID 1032 (Past Perfect Translation)
 * php import_lesson_1032_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1032;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1032 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'The baby had cried before her father came. ترجمة:', 'options' => ['الطفلة بكت قبل قدوم والدها', 'الطفلة كانت تبكي', 'الطفلة قد بكت قبل أن يأتِ والدها.', 'سوف تبكي'], 'correct' => 2],
        ['text' => 'We had already finished the test. ترجمة:', 'options' => ['نحن قد أنهينا الاختبار بالفعل.', 'أنهينا الاختبار', 'سوف ننهي', 'بالتأكيد سننهي'], 'correct' => 0],
        ['text' => 'I had forgotten to feed the cat. ترجمة:', 'options' => ['نسيت القطة', 'أنا قد نسيت إطعام القطة.', 'سوف أنسى', 'قد نسوا'], 'correct' => 1],
        ['text' => 'They had lost the map. ترجمة:', 'options' => ['هم قد أضاعوا الخريطة.', 'وجدوا الخريطة', 'سيضيعون', 'أضاع'], 'correct' => 0],
        ['text' => 'Sarah had lived in London. ترجمة:', 'options' => ['تعيش في لندن', 'سارة قد عاشت في لندن.', 'ستعيش', 'كانت تعيش'], 'correct' => 1],
        ['text' => 'ترجمة: (هو لم يكن قد تخرج بعد):', 'options' => ['He had not graduated yet.', 'not graduate', 'not graduated (no had)', 'not graduating'], 'correct' => 0],
        ['text' => 'ترجمة: (الشمس لم تكن قد غربت):', 'options' => ['sun set', 'The sun had not set.', 'sun setting', 'is not set'], 'correct' => 1],
        ['text' => 'ترجمة: (نحن لم نكن قد أكلنا الغداء بعد):', 'options' => ['We had not eaten lunch yet.', 'not ate', 'not eating', 'not have eaten'], 'correct' => 0],
        ['text' => 'ترجمة: (هم لم يكونوا قد وصلوا المحطة):', 'options' => ['reached station', 'They had not reached the station.', 'not reach', 'not reaching'], 'correct' => 1],
        ['text' => 'ترجمة: (أنا لم أكن قد قرأت هذا الكتاب من قبل):', 'options' => ['not read', 'I had not read this book before.', 'reading this book', 'never read (simple)'], 'correct' => 1],
        ['text' => 'Had you visited Italy before? ترجمة:', 'options' => ['هل زرت ايطاليا؟', 'هل قد زرت إيطاليا من قبل؟', 'متى زرت', 'كيف زرت'], 'correct' => 1],
        ['text' => 'Had she seen the movie before? ترجمة:', 'options' => ['هي رأت الفيلم', 'هل كانت قد رأت الفيلم من قبل؟', 'هل ترى', 'سوف ترى'], 'correct' => 1],
        ['text' => 'Had they finished their work? ترجمة:', 'options' => ['هل كانوا قد أنهوا عملهم؟', 'أنهوا عملهم', 'سيقومون', 'قد أنهيت'], 'correct' => 0],
        ['text' => 'Had he written the letter? ترجمة:', 'options' => ['كتب الرسالة', 'هل كان قد كتب الرسالة؟', 'سوف يكتب', 'هل يكتب'], 'correct' => 1],
        ['text' => 'Had the bus already left? ترجمة:', 'options' => ['هل غادر الباص؟', 'هل كان قد غادر الباص بالفعل؟', 'سيغادر', 'قد يغادر'], 'correct' => 1],
        ['text' => '(Before – she – left – she – had – typed – current – the – file) اعد الترتيب:', 'options' => ['She typed before...', 'She had typed the current file before she left.', 'current file she had...', 'none'], 'correct' => 1],
        ['text' => '(A – large – amount – of – had – work – completed – been) اعد الترتيب:', 'options' => ['A large amount of work had been completed.', 'Work had been a large...', 'completed work had been', 'none'], 'correct' => 0],
        ['text' => '(The – broken – glass – had – replaced – been) اعد الترتيب:', 'options' => ['broken glass replaced', 'The broken glass had been replaced.', 'had been broken glass', 'none'], 'correct' => 1],
        ['text' => '(Had – you – before – ever – seen – him - ?) اعد الترتيب:', 'options' => ['Seen him before you had ever?', 'Had you ever seen him before?', 'before you ever seen?', 'none'], 'correct' => 1],
        ['text' => '(If – I – had – hard – I – would – passed – studied) اعد الترتيب:', 'options' => ['If I had studied hard, I would have passed.', 'studied hard if I had', 'I would have passed if...', 'Both a+c (implied)'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة وترتيب الماضي التام (Past Perfect Translation)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 20,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1032.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
