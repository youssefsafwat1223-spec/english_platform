<?php

/**
 * Script to import questions for Lesson ID 1119 (Comparison Practice)
 * php import_lesson_1119_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1119;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1119 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Ahmed is --------- (young) than Abdullah.', 'options' => ['Younger', 'More young', 'Youngger', 'More younger'], 'correct' => 0],
        ['text' => 'Ice –cream is ------- (sweet) than biscuits.', 'options' => ['Sweetter', 'Sweeter', 'More sweet', 'Sweetier'], 'correct' => 1],
        ['text' => 'Pizza is ------ (delicious) than vegetables.', 'options' => ['Much delicious', 'More delicious', 'Deliciouser', 'Deliciousest'], 'correct' => 1],
        ['text' => 'AbdulMajeed is ------- (strong) than Fahad.', 'options' => ['More strong', 'Stronger', 'Strongger', 'More stronger'], 'correct' => 1],
        ['text' => 'Florida’s weather is -------- (warm) than Alaska.', 'options' => ['More warm', 'More warmer', 'Warmer', 'Warmmer'], 'correct' => 2],
        ['text' => 'A rock is ------- (heavy) than a feather.', 'options' => ['Heavyer', 'Heavier', 'More heavy', 'More heavier'], 'correct' => 1],
        ['text' => 'A car’s speed is ------- (slow) than an airplane’s.', 'options' => ['More slower', 'Slower', 'More slow', 'Slowwer'], 'correct' => 1],
        ['text' => 'Opera music is ------ (boring) than rock music.', 'options' => ['Bored', 'More boring', 'Border', 'More boringer'], 'correct' => 1],
        ['text' => 'Love stories are ------- (romantic) than comedies.', 'options' => ['More romantic', 'Romancer', 'Romancier', 'More romancier'], 'correct' => 0],
        ['text' => 'Basil is ------ (smart) than Sami.', 'options' => ['More smart', 'Smarter', 'Smartter', 'More smarter'], 'correct' => 1],
        ['text' => 'Do you think that math and science are ------- (difficult) than English?', 'options' => ['More difficult', 'Difficulter', 'More difficullter', 'Are difficulter'], 'correct' => 0],
        ['text' => 'Fridays --------- (nice) than Saturdays because I don’t have to go to work.', 'options' => ['Nicer', 'Are nicer', 'Is nicer', 'More nice'], 'correct' => 1],
        ['text' => 'Sleeping is good for health. It’s ------ (good) than playing games.', 'options' => ['Is better', 'Better', 'Are better', 'Gooder'], 'correct' => 1],
        ['text' => 'New York is ------- (crowded) than London.', 'options' => ['Crowdeder', 'Crowdedest', 'More crowded', 'Most crowded'], 'correct' => 2],
        ['text' => 'The Mona Lisa is one of the ------- paintings in the world.', 'options' => ['Beautifuler', 'Beautifulest', 'More beautiful', 'Most beautiful'], 'correct' => 3],
        ['text' => 'This is the ----- movie I’ve ever seen.', 'options' => ['Boringer', 'Boringest', 'More boring', 'Most boring'], 'correct' => 3],
        ['text' => 'Who is the ------ singer in your country?', 'options' => ['Famous', 'More famous', 'Most famous', 'Famouser'], 'correct' => 2],
        ['text' => 'Who is the --------- person in your family?', 'options' => ['Tallest', 'Taller', 'More taller', 'Most tallest'], 'correct' => 0],
        ['text' => 'My mum is the ------ (good) in the world?', 'options' => ['Gooder', 'Goodest', 'Better', 'Best'], 'correct' => 3],
        ['text' => 'What’s the ------ dangerous animal in the world?', 'options' => ['More', 'Most', 'Than', 'The'], 'correct' => 1],
        ['text' => '------ man in the world is 120 years old.', 'options' => ['The old', 'The oldest', 'Older', 'Older than'], 'correct' => 1],
        ['text' => 'Who is ----- tennis player in the class?', 'options' => ['Are better', 'The best', 'The good', 'Better than'], 'correct' => 1],
        ['text' => 'Your watch is ------- than mine.', 'options' => ['The most cheap', 'More cheap', 'Cheaper', 'The more cheaper'], 'correct' => 2],
        ['text' => 'A car is ----- than a bike.', 'options' => ['Most expensive', 'More expensive', 'expensiver', 'Expensiviest'], 'correct' => 1],
        ['text' => 'What is the comparative of “big”?', 'options' => ['Biger', 'Bigger', 'Biggest', 'Biggest'], 'correct' => 1],
        ['text' => 'What do you do when a short adjective ends in ”y’?', 'options' => ['Add er', 'Add more', 'Change “y” into “i” and add “er”', 'Add most'], 'correct' => 2],
        ['text' => 'It’s better ----- I thought.', 'options' => ['As', 'Then', 'Than', 'like'], 'correct' => 2],
        ['text' => 'Hatim is the ------ runner.', 'options' => ['Fast', 'Faster', 'Fastest', 'Fastly'], 'correct' => 2],
        ['text' => 'Brazil is ----- England.', 'options' => ['Biger than', 'Bigger as', 'Bigger than', 'Biger as'], 'correct' => 2],
        ['text' => 'I am not as successful ------ she is.', 'options' => ['As', 'Like', 'Than', 'The'], 'correct' => 0],
        ['text' => 'It’s ------- art collection in Europe.', 'options' => ['Finer', 'Finest', 'The finer', 'The finest'], 'correct' => 3],
        ['text' => 'This is the ------ kitchen I have ever seen.', 'options' => ['Dirtyst', 'Dirty', 'Dirtiest', 'Dirtier'], 'correct' => 2],
        ['text' => 'Ahmed is older than Sami.', 'options' => ['Sami is not as older than Ahmed.', 'Sami is an old as Ahmed.', 'Sami is not as old as Ahmed.', 'Sami is as old as Ahmed.'], 'correct' => 2],
        ['text' => 'What is the comparative of “thin”?', 'options' => ['Thiner than', 'Thinner than', 'The Thinnest', 'Most thin'], 'correct' => 1],
        ['text' => 'What is the superlative of “expensive”?', 'options' => ['Expensiver', 'More expensivest', 'The most expensive', 'The expensiviest'], 'correct' => 2],
        ['text' => 'What is the superlative of “quick”?', 'options' => ['Quicker than', 'The quickest', 'More quicker', 'Most quickest'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المقارنة (Comparison Practice)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 45,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1119.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
