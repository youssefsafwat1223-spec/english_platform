<?php

/**
 * Script to import questions for Lesson ID 1090 (Imperative Sentences Practice)
 * php import_lesson_1090_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1090;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1090 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Which sentence is correct?', 'options' => ['please to hold the line.', 'Please you hold the line.', 'Please, hold the line.', 'Please, not to hold the line.'], 'correct' => 2],
        ['text' => '---------talk to Ahmed, he’s busy.', 'options' => ['Not', 'Don’t', 'Doesn’t', 'No'], 'correct' => 1],
        ['text' => 'Please ------- in, you don’t have to knock.', 'options' => ['come', 'came', 'comes', 'coming'], 'correct' => 0],
        ['text' => 'Hey, ------me your keys.', 'options' => ['gave', 'giving', 'give', 'gives'], 'correct' => 2],
        ['text' => '--------run in the corridors.', 'options' => ['Not', 'Don’t', 'No', 'Doesn’t'], 'correct' => 1],
        ['text' => '----------on the bed.', 'options' => ['Jump', 'To jump', 'Don’t jump', 'Not jump'], 'correct' => 2],
        ['text' => '----------------- .', 'options' => ['Stand up', 'No stand up', 'Don’t stands up', 'Don’t sits down'], 'correct' => 0],
        ['text' => '---------your hand.', 'options' => ['Raised', 'Raise', 'Raises', 'Not raise'], 'correct' => 1],
        ['text' => '-----------the door.', 'options' => ['You open', 'Open', 'Don’t you', 'Not open'], 'correct' => 1],
        ['text' => '-----------the window.', 'options' => ['Not close', 'Don’t close', 'Not closed', 'Don’t closed'], 'correct' => 1],
        ['text' => '------ smoke here .', 'options' => ['Not', 'Do', 'Don’t', 'Did'], 'correct' => 2],
        ['text' => '------- with matches.', 'options' => ['Don’t plays', 'Don’t play', 'Doesn’t play', 'Do plays'], 'correct' => 1],
        ['text' => 'Which sentence is correct?', 'options' => ['Your room clean .', 'Clean your room .', 'Your clean yoy room .', 'Clean room your .'], 'correct' => 1],
        ['text' => '-------- healthy food.', 'options' => ['Eat', 'Drink', 'Eats', 'Drinks'], 'correct' => 0],
        ['text' => 'Which sentence is correct?', 'options' => ['Your lunch forget don’t .', 'Don’t forget your lunch .', 'Forget lunch your .', 'Not forget your lunch .'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة جمل الأمر (Imperative Sentences Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1090.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
