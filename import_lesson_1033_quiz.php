<?php

/**
 * Script to import questions for Lesson ID 1033 (Past Perfect Practice)
 * php import_lesson_1033_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1033;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1033 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Had the driver--------to you before the police arrived?', 'options' => ['talk', 'talked', 'talking', 'talks'], 'correct' => 1],
        ['text' => 'I was more than happy to see Ahmed again as I -------him for ages.', 'options' => ['hadn’t seen', 'saw', 'didn’t see', 'have seen'], 'correct' => 0],
        ['text' => 'I am sorry but I --------to be at your show.', 'options' => ['intended', 'was intended', 'had really intended', 'has intended'], 'correct' => 2],
        ['text' => '--------to the national museum before we went last month?', 'options' => ['Have you been', 'Had you be', 'Had you been', 'Has you been'], 'correct' => 2],
        ['text' => 'Before she had dinner, she ------in the garden.', 'options' => ['was working', 'had worked', 'worked', 'has worked'], 'correct' => 2],
        ['text' => 'She needed help because someone------her money.', 'options' => ['steal', 'had stolen', 'stole', 'has stolen'], 'correct' => 1],
        ['text' => 'By 1810 Napoleon---------all his battles.', 'options' => ['has won', 'have won', 'won', 'had won'], 'correct' => 3],
        ['text' => 'Before they started the party, they---------some friends.', 'options' => ['invited', 'had invited', 'have invited', 'did invite'], 'correct' => 1],
        ['text' => 'After she--------me with the housework, she went to meet her friends.', 'options' => ['helped', 'has helped', 'have helped', 'had helped'], 'correct' => 3],
        ['text' => 'He fed the cat because no one------it for days.', 'options' => ['had fed', 'fed', 'feed', 'has feed'], 'correct' => 0],
        ['text' => 'She ------to Mohammed before because she was too shy to start a conversation.', 'options' => ['hadn’t spoken', 'hadn’t speak', 'hadn’t speak', 'spoke'], 'correct' => 0],
        ['text' => 'After the family--------------breakfast, they went to the zoo.', 'options' => ['eating', 'eat', 'had eaten', 'ate'], 'correct' => 2],
        ['text' => 'When we arrived at the airport, our flight--------.', 'options' => ['left', 'had left', 'has left', 'leave'], 'correct' => 1],
        ['text' => '-------you finished all your homework before you started watching TV?', 'options' => ['Has', 'Have', 'Had', 'Were'], 'correct' => 2],
        ['text' => 'Had she worked here before 2013? Yes, she -----.', 'options' => ['has', 'had', 'have', 'was'], 'correct' => 1],
        ['text' => 'Had they -----each other before the party?', 'options' => ['meet', 'meeting', 'meets', 'met'], 'correct' => 3],
        ['text' => 'She had ------a beautiful dress at the party.', 'options' => ['wear', 'wore', 'worn', 'wearing'], 'correct' => 2],
        ['text' => 'Had they _____ to her before?', 'options' => ['spoken', 'spoke', 'speak', 'speaking'], 'correct' => 0],
        ['text' => 'We ________ finished eating dinner.', 'options' => ['has not', 'were not', 'had not', 'did not'], 'correct' => 2],
        ['text' => 'I had never _____ her before.', 'options' => ['seen', 'see', 'saw', 'didn’t see'], 'correct' => 0],
        ['text' => 'Nobody explained why the project had ________ on time.', 'options' => ['n\'t been completed', 'n\'t completed', 'n\'t complete', 'complete'], 'correct' => 0],
        ['text' => 'He couldn\'t make a sandwich because he----- forgotten to buy bread.', 'options' => ['\'d', '\'ve', '\'s', '\'re'], 'correct' => 0],
        ['text' => 'When I closed the door, I realized that I ___ my keys inside.', 'options' => ['left', 'had left', 'has left', 'leave'], 'correct' => 1],
        ['text' => 'Samia wasn\'t at home. She had ---- shopping.', 'options' => ['go', 'went', 'gone', 'going'], 'correct' => 2],
        ['text' => 'I couldn\'t get into the house. I ---- my keys.', 'options' => ['lose', 'lost', 'had lost', 'loses'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي التام (Past Perfect Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1033.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
