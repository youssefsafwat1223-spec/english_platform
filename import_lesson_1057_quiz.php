<?php

/**
 * Script to import questions for Lesson ID 1057 (Future Simple Practice)
 * php import_lesson_1057_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1057;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1057 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'He ---------arrive on time.', 'options' => ['will', 'is', 'not', 'has'], 'correct' => 0],
        ['text' => 'Will your parents ----------before Tuesday?', 'options' => ['leaving', 'leave', 'leaves', 'left'], 'correct' => 1],
        ['text' => 'We will ------what your father says.', 'options' => ['see', 'sees', 'seeing', 'saw'], 'correct' => 0],
        ['text' => 'It ------ tomorrow.', 'options' => ['will rains', 'going to rain', 'is going to rain', 'going of rain'], 'correct' => 2],
        ['text' => 'I --------back before Friday.', 'options' => ['\'ll be', 'will', 'am being', 'won\'t'], 'correct' => 0],
        ['text' => 'He is going to the grocery store. I think _____ buy a turkey.', 'options' => ['I\'ve', 'I\'ll', 'I\'d', 'I am'], 'correct' => 1],
        ['text' => 'I am sure that she --------a lot of people in Makah.', 'options' => ['will met', 'will meets', 'will meet', 'is going to meet'], 'correct' => 2],
        ['text' => 'If it rains, I --------take a taxi.', 'options' => ['going to', 'will', 'be', 'have'], 'correct' => 1],
        ['text' => 'He ----------his homework today.', 'options' => ['is doing', 'doesn’t do', 'won\'t do', 'do'], 'correct' => 2],
        ['text' => 'Will you get up early tomorrow morning?', 'options' => ['Yes, I will.', 'No, I will.', 'No, I don\'t', 'Yes, I do.'], 'correct' => 0],
        ['text' => 'Class “A ”students -------a test tomorrow.', 'options' => ['are going to have', 'has', 'had', 'will has'], 'correct' => 0],
        ['text' => 'The doctor -------with you in a moment.', 'options' => ['was', 'be', 'will be', 'is'], 'correct' => 2],
        ['text' => 'I ------visit my cousin next month.', 'options' => ['am going to', 'is going to', 'are going to', 'will'], 'correct' => 0],
        ['text' => '(The mobile phone rings) A: I --------answer.', 'options' => ['will', 'am going to', 'am', 'willn\'t'], 'correct' => 0],
        ['text' => 'The storm-----probably-------in the evening.', 'options' => ['will/to begin', 'will/begin', 'am going to begin', 'will/begins'], 'correct' => 1],
        ['text' => 'I ------- preparing lunch before the children get home from school today.', 'options' => ['am going to start', 'will starting', 'will to start', 'will starts'], 'correct' => 0],
        ['text' => 'Where ------you-----when you go for the conference? I have already booked a hotel room.', 'options' => ['will/to stay', 'are/going to stay', 'will/stay', 'will/stays'], 'correct' => 1],
        ['text' => 'My friend and I ------for dinner at a nearby restaurant. Would you like to join us?', 'options' => ['will meet', 'will meeting', 'are going to meet', 'are going to meeting'], 'correct' => 2],
        ['text' => 'He\'s wearing some old clothes because he------paint the walls.', 'options' => ['is going to', 'will', 'be going to', 'going to'], 'correct' => 0],
        ['text' => 'I am going to the supermarket. I -------you some vegetables for salad.', 'options' => ['going to bring', 'will bring', 'am going brings', 'am going to bring'], 'correct' => 1],
        ['text' => 'A: Your friend left you a message. B: Ok I ------her right away.', 'options' => ['will calling', 'will to call', 'am going to call', 'will call'], 'correct' => 3],
        ['text' => 'She has bought eggs and flour because she -------- a cake.', 'options' => ['is going to make', 'be going to make', 'will make', 'will to make'], 'correct' => 0],
        ['text' => 'The office------ you an email on Monday.', 'options' => ['not will send', 'will not send', 'going to', 'going to send'], 'correct' => 1],
        ['text' => '--------my mum make some cookies later?', 'options' => ['IS', 'Will', 'Had', 'Has'], 'correct' => 1],
        ['text' => 'Our team-------the game.', 'options' => ['is going to win', 'had won', 'won', 'has won'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المستقبل البسيط (Future Simple Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1057.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
