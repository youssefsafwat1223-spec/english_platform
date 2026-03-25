<?php

/**
 * Script to import questions for Lesson ID 1006 (Present Perfect Continuous MCQ)
 * php import_lesson_1006_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1006;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1006 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'They ------been living here for more than ten years.', 'options' => ['have', 'has', 'had', 'were'], 'correct' => 0],
        ['text' => 'Tom -------been answering my calls recently.', 'options' => ['have', 'haven’t', 'hasn’t', 'were'], 'correct' => 2],
        ['text' => 'My brother has been -------a job for a long time.', 'options' => ['look for', 'looking for', 'looked for', 'looks for'], 'correct' => 1],
        ['text' => 'I have -----feeling very tired recently.', 'options' => ['ben', 'be', 'been', 'am'], 'correct' => 2],
        ['text' => 'Her son ---------since childhood.', 'options' => ['have been playing', 'has been playing', 'has been played', 'have been played'], 'correct' => 1],
        ['text' => 'We have been------our house all day long.', 'options' => ['clean', 'cleaned', 'cleaning', 'cleans'], 'correct' => 2],
        ['text' => 'I --------writing this verse for five minutes.', 'options' => ['have', 'has', 'has been', 'have been'], 'correct' => 3],
        ['text' => '------she------ Arabic since last spring?', 'options' => ['Is/been studying', 'Has/been studying', 'Have/been studying', 'Have/studied'], 'correct' => 1],
        ['text' => 'You -----been playing football since he was 15.', 'options' => ['has', 'had', 'haven’t', 'hasn’t'], 'correct' => 2],
        ['text' => '--------they-------this show for 2 hours?', 'options' => ['Has/watched', 'Has/been watching', 'Have/been watching', 'Had/watched'], 'correct' => 2],
        ['text' => 'I ----- eating seafood since I first tried it.', 'options' => ['has been', 'have been', 'have be', 'has be'], 'correct' => 1],
        ['text' => 'They have been studying here-----2015', 'options' => ['since', 'for', 'yet', 'just'], 'correct' => 0],
        ['text' => 'I have been learning English ------ten years.', 'options' => ['since', 'for', 'yet', 'just'], 'correct' => 1],
        ['text' => 'The baby has been crying-----', 'options' => ['already', 'ever', 'all morning', 'yet'], 'correct' => 2],
        ['text' => 'I feel sick. I have been -------chocolates all day!', 'options' => ['eating', 'eat', 'ate', 'eaten'], 'correct' => 0],
        ['text' => 'It ----- since this morning. It’s terrible.', 'options' => ['rained', 'has rained', 'has been raining', 'has been'], 'correct' => 2],
        ['text' => 'These women-------songs without getting tired.', 'options' => ['has been singing', 'has sung', 'have been singing', 'have sung'], 'correct' => 2],
        ['text' => 'The girl-------learning her lesson for a long time.', 'options' => ['has been', 'have been', 'has', 'have'], 'correct' => 0],
        ['text' => 'I ------been cooking since 1 pm', 'options' => ['having', 'has', 'have', 'hasing'], 'correct' => 2],
        ['text' => '------they been playing?', 'options' => ['Have', 'Has', 'Are', 'Were'], 'correct' => 0],
        ['text' => 'Soha ------been sleeping since 2 am.', 'options' => ['haven’t', 'have', 'has not', 'not has'], 'correct' => 2],
        ['text' => 'It has been-------', 'options' => ['rain', 'rained', 'rains', 'raining'], 'correct' => 3],
        ['text' => '-------your brother and sister been getting along?', 'options' => ['Have', 'Are', 'Has', 'were'], 'correct' => 0],
        ['text' => 'Ibrahim ------been studying hard this semester.', 'options' => ['’ s', '’ ve', '’ re', '’ ll'], 'correct' => 0],
        ['text' => 'We have been watching TV ------we had dinner.', 'options' => ['for', 'yet', 'since', 'already'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المضارع التام المستمر (Present Perfect Continuous Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1006.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
