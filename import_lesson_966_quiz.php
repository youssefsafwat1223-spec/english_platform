<?php

/**
 * Script to import questions for Lesson ID 966
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_966_quiz.php
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
    $lessonId = 966;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 966 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => '__ you need help, come and see me.',
            'options' => ['if', 'unless', 'however', 'although'],
            'correct' => 0, // if
        ],
        [
            'text' => 'I will visit you ____ I have finished my homework.',
            'options' => ['Whereas', 'So', 'As soon as', 'but'],
            'correct' => 2, // As soon as
        ],
        [
            'text' => '___ all the technical problems he had with the computer, he had managed to send the email.',
            'options' => ['despite', 'because', 'since', 'so'],
            'correct' => 0, // despite
        ],
        [
            'text' => 'Mariam wasn’t feeling so well ___, she went to school.',
            'options' => ['nonetheless', 'Therefore', 'Firstly', 'So'],
            'correct' => 0, // nonetheless
        ],
        [
            'text' => '____ Peter woke up late, he missed his first class on Monday.',
            'options' => ['When', 'Despite', 'Before', 'As'],
            'correct' => 3, // As (meaning because/since)
        ],
        [
            'text' => 'She is an excellent student, she can speak _ Italian _____French.',
            'options' => ['Neither/nor', 'Both/and', 'either/or', 'and/or'],
            'correct' => 1, // Both/and
        ],
        [
            'text' => 'He wasn\'t able to finish his work, ____ he tried as hard as he could.',
            'options' => ['Even though', 'but', 'if', 'Besides'],
            'correct' => 0, // Even though
        ],
        [
            'text' => 'I hate Math _____Physics.',
            'options' => ['Also', 'as well as', 'well as', 'too'],
            'correct' => 1, // as well as
        ],
        [
            'text' => 'Select the linking words that show contrast in a sentence:',
            'options' => ['Although', 'However', 'In spite of', 'All of the above'],
            'correct' => 3, 
        ],
        [
            'text' => 'Which linking word shows cause?',
            'options' => ['In spite of', 'Due to', 'However', 'So'],
            'correct' => 1, // Due to
        ],
        [
            'text' => 'Which linking word shows effect?',
            'options' => ['In spite of', 'Due to', 'However', 'So'],
            'correct' => 3, // So
        ],
        [
            'text' => 'She likes cheesecakes ___ she likes chocolate too.',
            'options' => ['But', 'Or', 'and', 'because'],
            'correct' => 2, // and
        ],
        [
            'text' => 'My favourite subject is English ___ is very interesting and important to speak it nowadays.',
            'options' => ['because', 'So', 'and', 'although'],
            'correct' => 0, // because
        ],
        [
            'text' => 'We use (and) to express_____.',
            'options' => ['A consequence', 'A result', 'As addition (to add)', 'A contrast'],
            'correct' => 2, 
        ],
        [
            'text' => 'I dislike when my mum cooks pasta with cheese ___ mushrooms, ___ I love when she cooks shrimps ___ fried chicken.',
            'options' => ['Because/so/and', 'And/but/or', 'Or/and/or', 'Or/and/and'],
            'correct' => 3, // Generic or/and/and pattern often used in these tests.
        ],
        [
            'text' => 'I turned off the air conditioning_____ I was cold.',
            'options' => ['Therefor', 'because', 'and', 'but'],
            'correct' => 1, // because
        ],
        [
            'text' => 'Firstly, bananas are delicious. _____, they are healthy.',
            'options' => ['next', 'however', 'for example', 'So'],
            'correct' => 0, // next
        ],
        [
            'text' => 'I have a flat. ___, I have a car.',
            'options' => ['Furthermore', 'Firstly', 'However', 'Because'],
            'correct' => 0, // Furthermore
        ],
        [
            'text' => 'Saleh bought a motorcycle, -------- he doesn’t ride it.',
            'options' => ['As', 'However', 'Besides', 'So'],
            'correct' => 1, // However
        ],
        [
            'text' => 'I didn’t buy anything ------ I lost my purse at home.',
            'options' => ['Moreover', 'Then', 'since', 'so'],
            'correct' => 2, // since
        ],
        [
            'text' => '____ she\'s one of the best employees in the company, she didn’t get promoted.',
            'options' => ['Even though', 'So', 'As', 'And'],
            'correct' => 0, // Even though
        ],
        [
            'text' => '____visiting her several times, I still can\'t remember her house.',
            'options' => ['In spite of', 'Moreover', 'Finally', 'Such'],
            'correct' => 0, // In spite of
        ],
        [
            'text' => 'Which word can\'t replace “as a result "?',
            'options' => ['Also', 'Consequently', 'So', 'As a consequence'],
            'correct' => 0, // Also
        ],
        [
            'text' => 'Social media sites have advantages --------- they have a lot of disadvantages.',
            'options' => ['On the other hand', 'As a result', 'In addition', 'And'],
            'correct' => 0, // On the other hand
        ],
        [
            'text' => 'We have worked for hours. ____ we can go home.',
            'options' => ['finally', 'Because', 'Firstly', 'Moreover'],
            'correct' => 0, // finally
        ],
        [
            'text' => 'I set the alarm for 6:30 in the morning ___ I wouldn\'t miss the train.',
            'options' => ['while', 'so that', 'unless', 'because'],
            'correct' => 1, // so that
        ],
        [
            'text' => 'I am ___ better at geography than you are.',
            'options' => ['obviously', 'as well as', 'too', 'just as'],
            'correct' => 0, // obviously
        ],
        [
            'text' => '--------, you can see what is on display. ------- you can buy whatever you want.',
            'options' => ['Firstly/secondly', 'Secondly/finally', 'Or/and', 'But/and'],
            'correct' => 0, // Firstly/secondly
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة أدوات الربط (Linking Words Practice)',
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

    echo "🎉 Successfully added " . $count . " questions to Lesson 966 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
