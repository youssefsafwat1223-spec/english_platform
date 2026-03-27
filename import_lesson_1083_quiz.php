<?php

/**
 * Script to import questions for Lesson ID 1083 (Future Perfect Continuous Practice)
 * php import_lesson_1083_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1083;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1083 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'She _______________ the play for four months by the end of October.', 'options' => ['Won’t have been writing', 'Won’t have writing', 'Won’t been writing', 'Won’t have written'], 'correct' => 0],
        ['text' => 'My father and I will have been __ sheep for 20 years tomorrow.', 'options' => ['Breed', 'Breeding', 'Bred', 'Breeds'], 'correct' => 1],
        ['text' => '___ you have been explaining the problem for more an hour.', 'options' => ['Willnt', 'Shall', 'Are', 'Do'], 'correct' => 1],
        ['text' => 'When the movie stars, I will have ___ lots of popcorns.', 'options' => ['Been eating', 'Be eating', 'Eat', 'Eats'], 'correct' => 0],
        ['text' => 'By the time the show starts, the conductor will have ___ selling lots of tickets.', 'options' => ['Be', 'Been', 'Shall', 'Gone'], 'correct' => 1],
        ['text' => 'I will have ____ waiting here for three hours.', 'options' => ['Be', 'Been', 'Being', 'Am'], 'correct' => 1],
        ['text' => 'Next year, Khalid and I will __ been joining this football club for four years.', 'options' => ['Has', 'Have', 'Had', 'Do'], 'correct' => 1],
        ['text' => 'When you reach the courtroom, the lawyer will have been ____ the documents since morning.', 'options' => ['Check', 'Checked', 'Checking', 'Checks'], 'correct' => 2],
        ['text' => 'By the time Ahmed leaves the city, he will have been ____ another place for 2 weeks.', 'options' => ['Settle in', 'Settling in', 'Settled in', 'Go'], 'correct' => 1],
        ['text' => 'Abdullah and Faten ____ Jareesh for their children for 20 munites.', 'options' => ['Will prepare', 'Will have been preparing', 'Will be prepared', 'prepare'], 'correct' => 1],
        ['text' => 'When you retire, you ______ money for 20 years.', 'options' => ['Will have been saving', 'Will be saved', 'Will been saving', 'Will have saving'], 'correct' => 0],
        ['text' => 'You_______ for over 11 hours.', 'options' => ['’ll have been driving.', 'Shall drive', 'Will be driven', 'Will have driven'], 'correct' => 0],
        ['text' => 'He\'ll have been _ Mahshi for two hours.', 'options' => ['Prepared', 'Preparing', 'Eaten', 'Cooked'], 'correct' => 1],
        ['text' => 'You _ have been watching TV.(negative)', 'options' => ['Willn’t', 'Shalln’t', 'Wolln’t', 'Shan’t'], 'correct' => 3],
        ['text' => '___ you have been driving fast?', 'options' => ['Will', 'Do', 'Shell', 'Are'], 'correct' => 0],
        ['text' => 'Will she _ been travelling to New York?', 'options' => ['Have', 'Has', 'Had', 'Does'], 'correct' => 0],
        ['text' => 'By the end of June, I _ in this flat for three years.', 'options' => ['Will been living', 'Will have been living', 'Will had been living', 'Will be lived'], 'correct' => 1],
        ['text' => 'Will she _ crying for an hour when I calm her down?', 'options' => ['Have being', 'Had been', 'Has been', 'Have been'], 'correct' => 3],
        ['text' => 'At the end of this term, I_____ English for months.', 'options' => ['Will have been studying', 'Will have studied', 'Have study', 'Had been studied'], 'correct' => 0],
        ['text' => 'The company’s profits will have been __ steadily by the end of the year.', 'options' => ['Increasing', 'Increase', 'Increases', 'Increased'], 'correct' => 0],
        ['text' => 'By the time of his wedding, he __ have been losing weight.', 'options' => ['Willn’t', 'Shall', 'Is', 'Does'], 'correct' => 0],
        ['text' => 'By the end of the year, she will have ____ the art exhibitions.', 'options' => ['Been managing', 'Be managed', 'Be manage', 'Been manage'], 'correct' => 0],
        ['text' => 'By next year, Sarah ______ cookies for her friends for fifteen years.', 'options' => ['Will be bake', 'Will bake', 'Will have been baking', 'Will have baked'], 'correct' => 2],
        ['text' => 'By the time he graduates, he will have been __ medicine for seven years.', 'options' => ['Studies', 'Studying', 'Studied', 'Done'], 'correct' => 1],
        ['text' => 'By next week, the team will have ____ their routines for the competition.', 'options' => ['Been practicing', 'Be practice', 'Been practice', 'Practice'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المستقبل التام المستمر (Future Perfect Continuous Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1083.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
