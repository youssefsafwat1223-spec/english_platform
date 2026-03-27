<?php

/**
 * Script to import questions for Lesson ID 1151 (Time Practice)
 * php import_lesson_1151_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1151;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1151 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'We go to work ____ 8:00.', 'options' => ['In', 'On', 'At', 'For'], 'correct' => 2],
        ['text' => 'Elizabeth likes to take a nap _____ noon.', 'options' => ['At', 'In', 'On', 'For'], 'correct' => 0],
        ['text' => 'How can we read this (11:45)?', 'options' => ['It’s eleven o’clock', 'It’s twelve o’clock', 'It’s quarter to twelve', 'It’s a quarter to eleven'], 'correct' => 2],
        ['text' => 'How can we read this (2:30)?', 'options' => ['It’s two to quarter two', 'It’s a quarter to two', 'It’s fifteen minutes past two', 'It’s half past two'], 'correct' => 3],
        ['text' => 'How can we read this (2:15)?', 'options' => ['It’s half past two', 'It’s two past half', 'It’s quarter past two', 'It’s two past quarter'], 'correct' => 2],
        ['text' => 'It’s quarter to eight means ------', 'options' => ['7:15', '8:45', '7:45', '8:15'], 'correct' => 2],
        ['text' => '(7:34) What time is it?', 'options' => ['It’s seven to eight', 'Twenty six to eight', 'It’s eight past seven', 'It’s seven'], 'correct' => 1],
        ['text' => 'What time is it? (12:00)', 'options' => ['Half past twelve', 'Twelve o’clock', 'A quarter past twelve', 'A quarter to twelve'], 'correct' => 1],
        ['text' => 'What is the "long hand" on the clock called?', 'options' => ['Hour hand', 'Right hand', 'Short hand', 'Minute hand'], 'correct' => 3],
        ['text' => 'What is the "short hand" on the clock called?', 'options' => ['Hour hand', 'Left hand', 'Short hand', 'Minute hand'], 'correct' => 0],
        ['text' => 'What is (11:50pm) on a military time?', 'options' => ['23:50', '11:50', '24:00', '23:05'], 'correct' => 0],
        ['text' => 'What is (1:40pm) on a military time?', 'options' => ['13:40', '01:40', '20:00', '01:04'], 'correct' => 0],
        ['text' => 'What is (11:59am) on a military time?', 'options' => ['23:59', '11:59', '24:00', '23:59'], 'correct' => 1],
        ['text' => 'What is 12 am on a military clock?', 'options' => ['23', '00', '11', '12'], 'correct' => 1],
        ['text' => 'What is the time 1 minute past mid night military time?', 'options' => ['0100', '0001', '0040', '2401'], 'correct' => 1],
        ['text' => 'What is 1210 in military time?', 'options' => ['12:10 pm', '13:20 pm', '12:30pm', '12:10am'], 'correct' => 0],
        ['text' => 'What is 1800 in military time?', 'options' => ['12 pm', '6am', '6pm', '12am'], 'correct' => 2],
        ['text' => '(2:40pm) in military time:', 'options' => ['1440', '0340', '2200', '1404'], 'correct' => 0],
        ['text' => 'What is 2310 in military time?', 'options' => ['12:10am', '11:30pm', '13:20pm', '11:10pm'], 'correct' => 3],
        ['text' => 'What time is it? (Image 1)', 'options' => ['5:06', '5:30', '6:25', '6:05'], 'correct' => 3],
        ['text' => 'What time is it? (Image 2)', 'options' => ['7:07', '7:05', '1:34', '1:35'], 'correct' => 0],
        ['text' => 'What time is it? (Image 3)', 'options' => ['A quarter to two', 'A quarter past two', 'Half past five', 'Ten to six'], 'correct' => 2],
        ['text' => 'I get up at ------- (Practice):', 'options' => ['Half past eight', 'Seven o’clock', 'Half past seven', 'Six o’clock'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الوقت (Time Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1151.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
