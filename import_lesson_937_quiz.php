<?php

/**
 * Script to import questions for Lesson ID 937
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_937_quiz.php
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
    $lessonId = 937;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 937 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'I appreciate --------- kindness.',
            'type' => 'multiple_choice',
            'options' => ['You', 'Your', 'Yours', 'Me'],
            'correct' => 1, // Your
        ],
        [
            'text' => 'What kind of possessive is “mine”?',
            'type' => 'multiple_choice',
            'options' => ['Possessive pronoun', 'Possessive adjective', 'Possessive noun', 'ownership'],
            'correct' => 0, // Possessive pronoun
        ],
        [
            'text' => 'Have you ever seen ----- dog?',
            'type' => 'multiple_choice',
            'options' => ['Me', 'We', 'Our', 'Ours'],
            'correct' => 2, // Our
        ],
        [
            'text' => 'That car is ------.',
            'type' => 'multiple_choice',
            'options' => ['My', 'Me', 'Mine', 'I'],
            'correct' => 2, // Mine
        ],
        [
            'text' => 'I wish I had ------- luck.',
            'type' => 'multiple_choice',
            'options' => ['You', 'They', 'Their', 'Theirs'],
            'correct' => 2, // Their
        ],
        [
            'text' => 'I wrote ------ name on the paper.',
            'type' => 'multiple_choice',
            'options' => ['My', 'Me', 'I', 'Mine'],
            'correct' => 0, // My
        ],
        [
            'text' => 'The bird lost ----- chick.',
            'type' => 'multiple_choice',
            'options' => ['It’s', 'Its', 'It', 'She'],
            'correct' => 1, // Its
        ],
        [
            'text' => 'Choose a possessive noun to replace the underlined possessive noun: “The woman’s dream was to become a teacher.”',
            'type' => 'multiple_choice',
            'options' => ['Our', 'His', 'Her', 'My'],
            'correct' => 2, // Her
        ],
        [
            'text' => 'Choose a possessive noun to replace the underlined possessive noun: Ahmed’s parrot can say hello.',
            'type' => 'multiple_choice',
            'options' => ['His', 'Her', 'Hers', 'It'],
            'correct' => 0, // His
        ],
        [
            'text' => 'The white watch is -------.',
            'type' => 'multiple_choice',
            'options' => ['My', 'Your', 'His', 'Her'],
            'correct' => 2, // His
        ],
        [
            'text' => 'I think that this package is -------.',
            'type' => 'multiple_choice',
            'options' => ['Her', 'Yours', 'Our', 'Our'],
            'correct' => 1, // Yours
        ],
        [
            'text' => 'I don’t know what I want for ------ birthday.',
            'type' => 'multiple_choice',
            'options' => ['My', 'Mine', 'Me', 'I'],
            'correct' => 0, // My
        ],
        [
            'text' => 'I have a computer on ------- desk.',
            'type' => 'multiple_choice',
            'options' => ['His', 'Her', 'My', 'Mine'],
            'correct' => 2, // My
        ],
        [
            'text' => 'I have ------ horse.',
            'type' => 'multiple_choice',
            'options' => ['Mine', 'My own', 'His', 'Me'],
            'correct' => 1, // My own
        ],
        [
            'text' => '------- mobile is old ------- is new.',
            'type' => 'multiple_choice',
            'options' => ['Hers/mine', 'His/my', 'Her/mine', 'Mine/her'],
            'correct' => 2, // Her/mine
        ],
        [
            'text' => '------- son is lazy ------ is hardworking.',
            'type' => 'multiple_choice',
            'options' => ['Hers/my', 'My/yours', 'My/your', 'His/its'],
            'correct' => 1, // My/yours
        ],
        [
            'text' => '-------- garden is better than ------ .',
            'type' => 'multiple_choice',
            'options' => ['Our/my', 'Our/theirs', 'Ours/theirs', 'Our/their'],
            'correct' => 1, // Our/theirs
        ],
        [
            'text' => 'Did you finish ------ work on time?',
            'type' => 'multiple_choice',
            'options' => ['Your', 'Her', 'His', 'Yours'],
            'correct' => 0, // Your
        ],
        [
            'text' => 'Those tickets are ------.',
            'type' => 'multiple_choice',
            'options' => ['Hers', 'Your', 'My', 'Their'],
            'correct' => 0, // Hers
        ],
        [
            'text' => 'The students left ----- classroom very quickly.',
            'type' => 'multiple_choice',
            'options' => ['Mine', 'Yours', 'Their', 'Theirs'],
            'correct' => 2, // Their
        ],
        [
            'text' => 'Shooq and Abdul Rahim invited me to ------ wedding.',
            'type' => 'multiple_choice',
            'options' => ['Them', 'Their', 'Theirs', 'Our'],
            'correct' => 1, // Their
        ],
        [
            'text' => 'It’s not theirs, it’s ------. We bought it yesterday.',
            'type' => 'multiple_choice',
            'options' => ['Our', 'Mine', 'Ours', 'Theirs'],
            'correct' => 2, // Ours
        ],
        [
            'text' => 'The panda was hungry and tired, so it ate all -----food and fell asleep.',
            'type' => 'multiple_choice',
            'options' => ['Its', 'It', 'It’s', 'Theirs'],
            'correct' => 0, // Its
        ],
        [
            'text' => 'Basim and his family live in -------house.',
            'type' => 'multiple_choice',
            'options' => ['Them', 'Their own', 'They', 'Ours'],
            'correct' => 1, // Their own
        ],
        [
            'text' => 'Fatima has the opportunity to make friends with all ----classmates.',
            'type' => 'multiple_choice',
            'options' => ['Hers', 'Her', 'His', 'she'],
            'correct' => 1, // Her
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ممارسة ضمائر وصفات الملكية',
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
    
    // Clear existing questions
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'],
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

    echo "🎉 Successfully added " . $count . " questions to Lesson 937 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
