<?php

/**
 * Script to import questions for Lesson ID 1142 (There is/are Practice)
 * php import_lesson_1142_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1142;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1142 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Help yourself ---------- coffee, tea and juice to drink.', 'options' => ['There is', 'There are', 'There aren’t', 'There isn’t'], 'correct' => 0],
        ['text' => '------ many people standing outside the stage,', 'options' => ['There is', 'There are', 'There', 'There was'], 'correct' => 1],
        ['text' => 'There ----- a small cat playing with some toys.', 'options' => ['Are', 'Have', 'Is', 'Were'], 'correct' => 2],
        ['text' => 'How many cups -----? There are two cups.', 'options' => ['Are there', 'There are', 'There have', 'There has'], 'correct' => 0],
        ['text' => 'Are there two birds in the tree? Yes, -------', 'options' => ['There is', 'Are there', 'There are', 'Is there'], 'correct' => 2],
        ['text' => '------ a taxi waiting for us? Yes, ------.', 'options' => ['Is there/is there', 'Is there/there is', 'There is/there is', 'Are there/there is'], 'correct' => 1],
        ['text' => '------- four chairs and one table in the dining room.', 'options' => ['There is', 'There are', 'Are there', 'There were'], 'correct' => 1],
        ['text' => 'Is there anything I can do to help? Yes, ------', 'options' => ['There are', 'Is there', 'There is', 'Are there'], 'correct' => 2],
        ['text' => 'Why ------- so many cars parked near the library?', 'options' => ['Are there', 'There', 'Is there', 'There is'], 'correct' => 0],
        ['text' => '-------- a great action movie playing at the theater. Do you want to see it?', 'options' => ['Is there', 'There is', 'There are', 'Are there'], 'correct' => 1],
        ['text' => 'I don’t see any buses. Why ------ any buses?', 'options' => ['Aren’t there', 'Are there', 'There aren’t', 'Is there'], 'correct' => 0],
        ['text' => '------- a good reason why he is late? Yes, ------', 'options' => ['Is there/there is', 'There are/there is', 'Is there/there are', 'There are/there are'], 'correct' => 0],
        ['text' => 'Please wait here for a moment ------- something I have to get in my car.', 'options' => ['Are there', 'There is', 'There are', 'Is there'], 'correct' => 1],
        ['text' => 'There is -------- on the desk.', 'options' => ['A computer', 'Some computers', 'Any computers', 'Computers'], 'correct' => 0],
        ['text' => 'There are ------- on the table.', 'options' => ['Some apples', 'Any apples', 'An apple', 'Apple'], 'correct' => 0],
        ['text' => 'There aren’t ------ on the table.', 'options' => ['Some pens', 'Any pens', 'A pen', 'Pens'], 'correct' => 1],
        ['text' => 'There ----- a dog in my garden.', 'options' => ['Is', 'Are', 'Were', 'Has'], 'correct' => 0],
        ['text' => 'There ------ a goldfish in our class.', 'options' => ['Is', 'Are', 'Have', 'Were'], 'correct' => 0],
        ['text' => 'There ----- some sandwiches in my bag.', 'options' => ['Is', 'Are', 'Aren’t', 'Isn’t'], 'correct' => 1],
        ['text' => 'Is there a carpet in your bedroom?', 'options' => ['Yes, there is', 'Yes, there are', 'No, there aren’t', 'No there isn’t'], 'correct' => 0],
        ['text' => '------- a kitchen (positive).', 'options' => ['There is', 'There are', 'There aren’t', 'There isn’t'], 'correct' => 0],
        ['text' => '------- a dining room (negative).', 'options' => ['There is', 'There are', 'There isn’t', 'There aren’t'], 'correct' => 2],
        ['text' => '------- a dishwasher (interrogative).', 'options' => ['Is there', 'There is', 'There are', 'There aren’t'], 'correct' => 0],
        ['text' => '------ big windows (negative).', 'options' => ['There is', 'There are', 'There isn’t', 'There aren’t'], 'correct' => 3],
        ['text' => '------- four bedrooms (interrogative).', 'options' => ['There is', 'Is there', 'Are there', 'There aren’t'], 'correct' => 2],
        ['text' => 'There ------- a red car parked in our driveway.', 'options' => ['Is', 'Are', 'Aren’t', 'Has'], 'correct' => 0],
        ['text' => 'There ----- many options to pick from.', 'options' => ['Is', 'Are', 'Isn’t', 'Has'], 'correct' => 1],
        ['text' => 'There ------- lots of errors in this page.', 'options' => ['Is', 'Was', 'Are', 'Has'], 'correct' => 2],
        ['text' => 'There ----- tomato paste in the kitchen.', 'options' => ['Is no', 'Are no', 'No is', 'No are'], 'correct' => 0],
        ['text' => 'There ------- vases on show this week.', 'options' => ['Is no', 'Are no', 'No is', 'No are'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة هناك (There is/are Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1142.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
