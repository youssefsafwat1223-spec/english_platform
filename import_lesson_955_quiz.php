<?php

/**
 * Script to import questions for Lesson ID 955 (Updated: This/That)
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_955_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    // 1. Find the lesson
    $lessonId = 955;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 955 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'What’s this? ------ is a book.',
            'options' => ['This', 'Those', 'These', 'The'],
            'correct' => 0, // This
        ],
        [
            'text' => 'What are these? ------- are dumbbells.',
            'options' => ['This', 'That', 'These', 'The'],
            'correct' => 2, // These
        ],
        [
            'text' => '-------- cake is delicious. When I finish it, could I please have another slide?',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 0, // This
        ],
        [
            'text' => 'Do you think ------ sixteenth grammar questions are easy or difficult?',
            'options' => ['That', 'Them', 'These', 'This'],
            'correct' => 2, // These
        ],
        [
            'text' => 'How many of ----- cookies would you like? Two?',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 3, // These
        ],
        [
            'text' => '------- was a difficult test we had last week.',
            'options' => ['This', 'That', 'These', 'The'],
            'correct' => 1, // That
        ],
        [
            'text' => 'Maybe we can ask ------ policeman for directions.',
            'options' => ['That', 'Those', 'These', 'This'],
            'correct' => 0, // That
        ],
        [
            'text' => 'Hello, Dr Ahmed. Could you please look at ----- cut on my finger?',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 0, // This
        ],
        [
            'text' => 'Can you see ----- fishing boats on the lake?',
            'options' => ['That', 'Those', 'These', 'The'],
            'correct' => 1, // Those
        ],
        [
            'text' => 'Who was ----- man you talked to yesterday?',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 1, // That
        ],
        [
            'text' => '-------- are my siblings.',
            'options' => ['This', 'These', 'That', 'The'],
            'correct' => 1, // These
        ],
        [
            'text' => '------- is a tall building over there.',
            'options' => ['These', 'Them', 'That', 'Those'],
            'correct' => 2, // That
        ],
        [
            'text' => 'Hello ----- is Ahmed. Can I speak to Ghalib, please?',
            'options' => ['That', 'This', 'These', 'Those'],
            'correct' => 1, // This
        ],
        [
            'text' => 'I am going to Makkah again ------ weekend. Do you want to come?',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 0, // This
        ],
        [
            'text' => 'What’s the name of ----- film that we watched last night?',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 1, // That
        ],
        [
            'text' => 'Here we are. ------ is Where I live.',
            'options' => ['This', 'That', 'Those', 'These'],
            'correct' => 0, // This
        ],
        [
            'text' => 'I am leaving ------ Tuesday.',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 0, // This
        ],
        [
            'text' => 'Can you see what ----- car’s license plate? It is too far away.',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 1, // That
        ],
        [
            'text' => 'The flat we looked at today was better than ------ two we saw last weekend.',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 3, // those
        ],
        [
            'text' => 'Look at ---- birds up there in the tree.',
            'options' => ['This', 'That', 'These', 'Those'],
            'correct' => 3, // those
        ],
        [
            'text' => 'Are ----- your books over there on the table?',
            'options' => ['These', 'Those', 'This', 'That'],
            'correct' => 1, // those
        ],
        [
            'text' => 'Those apples are red, but ----- apples over here are green.',
            'options' => ['That', 'This', 'These', 'Those'],
            'correct' => 2, // these
        ],
        [
            'text' => 'Did you and your family stay at ----- hotel in Paris?',
            'options' => ['That', 'These', 'This', 'Those'],
            'correct' => 0, // That
        ],
        [
            'text' => '----- was such an interesting experience.',
            'options' => ['That', 'These', 'Those', 'Such'],
            'correct' => 0, // That
        ],
        [
            'text' => 'Are ----- your shoes?',
            'options' => ['That', 'Them', 'Those', 'This'],
            'correct' => 2, // Those
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ممارسة أسماء الإشارة (Demonstratives Practice)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 30,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => 'multiple_choice',
            'points' => 1,
        ];

        $attrs['option_a'] = $qData['options'][0] ?? null;
        $attrs['option_b'] = $qData['options'][1] ?? null;
        $attrs['option_c'] = $qData['options'][2] ?? null;
        $attrs['option_d'] = $qData['options'][3] ?? null;
        
        $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 955 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
