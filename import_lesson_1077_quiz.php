<?php

/**
 * Script to import questions for Lesson ID 1077 (Future Perfect Practice)
 * php import_lesson_1077_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1077;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1077 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'By the time you get here, I ___________.', 'options' => ['Will have showered', 'Was showered', 'Was showering', 'Going to show'], 'correct' => 0],
        ['text' => 'Adam will have _________ married for twenty years next month.', 'options' => ['Be', 'Been', 'Gone', 'Being'], 'correct' => 1],
        ['text' => 'He will have _____ the kitchen by this evening.', 'options' => ['Cleaned', 'Cleaning', 'Clean', 'Cleans'], 'correct' => 0],
        ['text' => 'By the time class starts, she will not have _______ her homework.', 'options' => ['Complete', 'Completed', 'Completing', 'Completes'], 'correct' => 1],
        ['text' => '_____ you have made my cake by then?', 'options' => ['Will', 'Did', 'Are', 'Does'], 'correct' => 0],
        ['text' => 'By 2050, I think schools will have _____ .', 'options' => ['Closed', 'Closes', 'Closing', 'Close'], 'correct' => 0],
        ['text' => 'By 5 p.m., I _________ all my chores.', 'options' => ['Will has finished', 'Will have finished', 'Will have finishing', 'Will finish'], 'correct' => 1],
        ['text' => 'She will have _____ the table by the time we arrive.', 'options' => ['Sat', 'Set', 'Seted', 'Setting'], 'correct' => 1],
        ['text' => 'I _______ my diet by next month.', 'options' => ['Will have started', 'Will has started', 'Have starting', 'Start'], 'correct' => 0],
        ['text' => 'Will Nada ________ our class by April?', 'options' => ['Has joined', 'Have joined', 'Have join', 'Have joining'], 'correct' => 1],
        ['text' => 'Nour _____ have ______ on vacation this time next year.', 'options' => ['Will \ gone', 'Will \ goes', 'Will \ going', 'Gone \ will'], 'correct' => 0],
        ['text' => 'I bet the baby will have ______ asleep by the time we get home.', 'options' => ['Falling', 'Fallen', 'Fell', 'Felled'], 'correct' => 1],
        ['text' => 'By the time I arrived at school, the lesson ______have started already!', 'options' => ['Need', 'Is going to', 'Willn’t', 'Going to'], 'correct' => 1], // Following prompt options
        ['text' => 'By next November, I will have ______ my big promotion at work.', 'options' => ['Received', 'Receiving', 'Getting', 'Took'], 'correct' => 0],
        ['text' => 'We will ____ finished building our house by next summer.', 'options' => ['Has', 'Have', 'Had', 'Be'], 'correct' => 1],
        ['text' => 'The meeting will start at 9.00 a.m and _______________ at 11.00 a.m.', 'options' => ['Finished', 'Will have finished', 'Will finished', 'Will be finishing'], 'correct' => 1],
        ['text' => 'By the time I finished the courses, I will have __________ one test.', 'options' => ['Take', 'Took', 'Taken', 'Taking'], 'correct' => 2],
        ['text' => 'You _______________ perfected your English by the time you come back from New York.', 'options' => ['Will has', 'Will have', 'Has been', 'Was'], 'correct' => 1],
        ['text' => 'They _______________ married by next year.', 'options' => ['Will', 'Won’t', 'Won’t have been'], 'correct' => 2],
        ['text' => 'You _______________ your English scores online next week.', 'options' => ['Will have seen', 'Will have saw', 'Will have see', 'Will have been'], 'correct' => 0],
        ['text' => 'Faisal _______________ breakfast by the time his mother wakes up.', 'options' => ['Will have prepared', 'Will have preparing', 'Going to prepared', 'Will has prepared'], 'correct' => 0],
        ['text' => 'You ________ the bill by the time the item arrives.', 'options' => ['’ll have received', 'Will receiving', '’ve received', '’s received'], 'correct' => 0],
        ['text' => 'khalid and Ali will be exhausted. They ________ slept for 24 hours.', 'options' => ['Will not', 'Will not have', 'Will not be', 'Will been'], 'correct' => 1],
        ['text' => 'He will have ________ all about it by Monday.', 'options' => ['Forgotten', 'Forgot', 'Be forgetting', 'Been forgot'], 'correct' => 0],
        ['text' => 'The boss ________ by the time the orders come in.', 'options' => ['Will leave', 'Will left', 'Will have left', 'Will be left'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المستقبل التام (Future Perfect Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1077.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
