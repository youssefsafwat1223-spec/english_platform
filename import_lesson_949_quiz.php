<?php

/**
 * Script to import questions for Lesson ID 949
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_949_quiz.php
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
    $lessonId = 949;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 949 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'I’ll arrive sometime ------ 8 and 9 am.',
            'options' => ['In', 'Next to', 'Between', 'On'],
            'correct' => 2, // Between
        ],
        [
            'text' => 'Shops here are open ------ 9 am until 5 pm.',
            'options' => ['From', 'At', 'On', 'By'],
            'correct' => 0, // From
        ],
        [
            'text' => 'They should be ready to go ------ 20 minutes.',
            'options' => ['On', 'By', 'In', 'To'],
            'correct' => 2, // In
        ],
        [
            'text' => 'She wants to stay ------ home tonight.',
            'options' => ['To', 'Of', 'At', 'In'],
            'correct' => 2, // At
        ],
        [
            'text' => 'Did you watch the football ----- TV last night?',
            'options' => ['By', 'On', 'In', 'To'],
            'correct' => 1, // On
        ],
        [
            'text' => 'I will be in the office ------ 5 pm.',
            'options' => ['In', 'For', 'Until', 'At'],
            'correct' => 2, // Until
        ],
        [
            'text' => 'You must have this report finished ----- Monday.',
            'options' => ['While', 'By', 'At', 'Since'],
            'correct' => 1, // By
        ],
        [
            'text' => 'I haven’t had a call from him ------ last Wednesday.',
            'options' => ['Since', 'On', 'In', 'For'],
            'correct' => 0, // Since
        ],
        [
            'text' => 'Identify the preposition in the following sentence: "Seba sat between Faten and Mona."',
            'options' => ['Seba', 'Sat', 'Between', 'Faten'],
            'correct' => 2, // Between
        ],
        [
            'text' => '-------- Monday, students will leave school early.',
            'options' => ['Before', 'On', 'After', 'During'],
            'correct' => 1, // On
        ],
        [
            'text' => 'My mother lives ----- New York.',
            'options' => ['At', 'In', 'Over', 'On'],
            'correct' => 1, // In
        ],
        [
            'text' => 'Select the word that is a preposition:',
            'options' => ['Laugh', 'Teacher', 'Under', 'Desk'],
            'correct' => 2, // Under
        ],
        [
            'text' => 'Which of the following sentences includes a prepositional phrase?',
            'options' => [
                'Ahmed is a player.',
                'Ahmed kicked the football.',
                'Ahmed’s team made three points.',
                'Ahmed kicked the football between the goalposts.'
            ],
            'correct' => 3, 
        ],
        [
            'text' => 'Which of the following sentences does not include a prepositional phrase?',
            'options' => [
                'Seba walked all the way home.',
                'Seba walked over the hill.',
                'Seba walked through the woods.',
                'Seba walked next to the river.'
            ],
            'correct' => 0, 
        ],
        [
            'text' => 'Find the preposition in this sentence: "Faisal walked through the door."',
            'options' => ['Through', 'Walked', 'Door', 'The'],
            'correct' => 0, 
        ],
        [
            'text' => 'Abdulrazzak sat ----- his new chair.',
            'options' => ['In', 'On', 'For', 'At'],
            'correct' => 1, // On generic chair, but "In" works for armchairs.
        ],
        [
            'text' => 'Basil stayed focused ----- his reading.',
            'options' => ['In', 'At', 'During', 'On'],
            'correct' => 2, // During
        ],
        [
            'text' => 'The dog sat ----- Abdullah.',
            'options' => ['On', 'In', 'Near', 'At'],
            'correct' => 2, // Near
        ],
        [
            'text' => 'What is the preposition in the sentence: “My little brother enjoys working in the street.”',
            'options' => ['In', 'Working', 'The', 'street'],
            'correct' => 0, // In
        ],
        [
            'text' => 'We rode our bikes ----- the road.',
            'options' => ['During', 'In', 'Along', 'At'],
            'correct' => 2, // Along
        ],
        [
            'text' => 'The sky is ----- the earth.',
            'options' => ['At', 'On', 'Above', 'Below'],
            'correct' => 2, // Above
        ],
        [
            'text' => 'Amy fell ------ the stairs.',
            'options' => ['In', 'At', 'Down', 'On'],
            'correct' => 2, // Down
        ],
        [
            'text' => 'Which word is a preposition?',
            'options' => ['Between', 'Behave', 'Believe', 'Being'],
            'correct' => 0, // Between
        ],
        [
            'text' => 'Which is not a preposition?',
            'options' => ['Belong', 'Inside', 'Outside', 'Off'],
            'correct' => 0, // Belong
        ],
        [
            'text' => 'Did you watch the show ---- endangered dolphins?',
            'options' => ['About', 'In', 'On', 'At'],
            'correct' => 0, // About
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة حروف الجر (Prepositions Practice)',
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

    echo "🎉 Successfully added " . $count . " questions to Lesson 949 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
