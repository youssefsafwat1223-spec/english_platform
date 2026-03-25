<?php

/**
 * Script to import questions for Lesson ID 955
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
            'text' => 'Help yourself ---------- coffee, tea and juice to drink.',
            'options' => ['There is', 'There are', 'There aren’t', 'There isn’t'],
            'correct' => 0, // There is (coffee is uncountable/singular)
        ],
        [
            'text' => '------ many people standing outside the stage,',
            'options' => ['There is', 'There are', 'There', 'There was'],
            'correct' => 1, // There are
        ],
        [
            'text' => 'There ----- a small cat playing with some toys.',
            'options' => ['Are', 'Have', 'Is', 'Were'],
            'correct' => 2, // Is
        ],
        [
            'text' => 'How many cups -----? There are two cups.',
            'options' => ['Are there', 'There are', 'There have', 'There has'],
            'correct' => 0, // Are there
        ],
        [
            'text' => 'Are there two birds in the tree? Yes, -------',
            'options' => ['There is', 'Are there', 'There are', 'Is there'],
            'correct' => 2, // There are
        ],
        [
            'text' => '------ a taxi waiting for us? Yes, ------.',
            'options' => ['Is there/is there', 'Is there/there is', 'There is/there is', 'Are there/there is'],
            'correct' => 1, // Is there/there is
        ],
        [
            'text' => '------- four chairs and one table in the dining room.',
            'options' => ['There is', 'There are', 'Are there', 'There were'],
            'correct' => 1, // There are
        ],
        [
            'text' => 'Is there anything I can do to help? Yes, ------',
            'options' => ['There are', 'Is there', 'There is', 'Are there'],
            'correct' => 2, // There is
        ],
        [
            'text' => 'Why ------- so many cars parked near the library?',
            'options' => ['Are there', 'There', 'Is there', 'There is'],
            'correct' => 0, // Are there
        ],
        [
            'text' => '-------- a great action movie playing at the theater. Do you want to see it?',
            'options' => ['Is there', 'There is', 'There are', 'Are there'],
            'correct' => 1, // There is
        ],
        [
            'text' => 'I don’t see any buses. Why ------ any buses?',
            'options' => ['Aren’t there', 'Are there', 'There aren’t', 'Is there'],
            'correct' => 0, // Aren’t there
        ],
        [
            'text' => '------- a good reason why he is late? Yes, ------',
            'options' => ['Is there/there is', 'There are/there is', 'Is there/there are', 'There are/there are'],
            'correct' => 0, // Is there/there is
        ],
        [
            'text' => 'Please wait here for a moment ------- something I have to get in my car.',
            'options' => ['Are there', 'There is', 'There are', 'Is there'],
            'correct' => 1, // There is
        ],
        [
            'text' => 'There is -------- on the desk.',
            'options' => ['A computer', 'Some computers', 'Any computers', 'Computers'],
            'correct' => 0, // A computer
        ],
        [
            'text' => 'There are ------- on the table.',
            'options' => ['Some apples', 'Any apples', 'An apple', 'Apple'],
            'correct' => 0, // Some apples
        ],
        [
            'text' => 'There aren’t ------ on the table.',
            'options' => ['Some pens', 'Any pens', 'A pen', 'Pens'],
            'correct' => 1, // Any pens
        ],
        [
            'text' => 'There ----- a dog in my garden.',
            'options' => ['Is', 'Are', 'Were', 'Has'],
            'correct' => 0, // Is
        ],
        [
            'text' => 'There ------ a goldfish in our class.',
            'options' => ['Is', 'Are', 'Have', 'Were'],
            'correct' => 0, // Is
        ],
        [
            'text' => 'There ----- some sandwiches in my bag.',
            'options' => ['Is', 'Are', 'Aren’t', 'Isn’t'],
            'correct' => 1, // Are
        ],
        [
            'text' => 'Is there a carpet in your bedroom?',
            'options' => ['Yes, there is', 'Yes, there are', 'No, there aren’t', 'No there isn’t'],
            'correct' => 0, // Yes, there is
        ],
        [
            'text' => '------- a kitchen (positive).',
            'options' => ['There is', 'There are', 'There aren’t', 'There isn’t'],
            'correct' => 0, // There is
        ],
        [
            'text' => '------- a dining room (negative).',
            'options' => ['There is', 'There are', 'There isn’t', 'There aren’t'],
            'correct' => 2, // There isn’t
        ],
        [
            'text' => '------- a dishwasher (interrogative).',
            'options' => ['Is there', 'There is', 'There are', 'There aren’t'],
            'correct' => 0, // Is there
        ],
        [
            'text' => '------ big windows (negative).',
            'options' => ['There is', 'There are', 'There isn’t', 'There aren’t'],
            'correct' => 3, // There aren’t
        ],
        [
            'text' => '------- four bedrooms (interrogative).',
            'options' => ['There is', 'Is there', 'Are there', 'There aren’t'],
            'correct' => 2, // Are there
        ],
        [
            'text' => 'There ------- a red car parked in our driveway.',
            'options' => ['Is', 'Are', 'Aren’t', 'Has'],
            'correct' => 0, // Is
        ],
        [
            'text' => 'There ----- many options to pick from.',
            'options' => ['Is', 'Are', 'Isn’t', 'Has'],
            'correct' => 1, // Are
        ],
        [
            'text' => 'There ------- lots of errors in this page.',
            'options' => ['Is', 'Was', 'Are', 'Has'],
            'correct' => 2, // Are
        ],
        [
            'text' => 'There ----- tomato paste in the kitchen.',
            'options' => ['Is no', 'Are no', 'No is', 'No are'],
            'correct' => 0, // Is no
        ],
        [
            'text' => 'There ------- vases on show this week.',
            'options' => ['Is no', 'Are no', 'No is', 'No are'],
            'correct' => 1, // Are no
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ممارسة There is & There are',
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
