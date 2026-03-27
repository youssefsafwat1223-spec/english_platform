<?php

/**
 * Script to import questions for Lesson ID 1126 (Quantifiers Practice)
 * php import_lesson_1126_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1126;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1126 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'You can’t use much of this perfume, there is ------- in the bottle.', 'options' => ['A few', 'A lot of', 'A little', 'Much'], 'correct' => 2],
        ['text' => 'They have had ------ homework in mathematics recently.', 'options' => ['Lots of', 'A lot', 'Many', 'Few'], 'correct' => 0],
        ['text' => 'How ----- time do you need to finish the work?', 'options' => ['Many', 'Often', 'Much', 'Do'], 'correct' => 2],
        ['text' => 'There are too ----- students in the library.', 'options' => ['Lots', 'Little', 'Much', 'Many'], 'correct' => 3],
        ['text' => 'Have you visited ----- foreign countries?', 'options' => ['Any', 'Lots of', 'A little', 'A lot of'], 'correct' => 0],
        ['text' => 'Although he was very ill, he didn’t take ------ medicine.', 'options' => ['Lots', 'A lot of', 'Many', 'Any'], 'correct' => 3],
        ['text' => 'It was a big party ------ of people came.', 'options' => ['A few', 'Not many', 'A lot', 'A little'], 'correct' => 2],
        ['text' => 'Ali was thirsty, he drank ------ of water.', 'options' => ['Many', 'A little', 'Lots', 'A few'], 'correct' => 2],
        ['text' => 'I can’t buy this book I don’t have ------- money, only 3 dinars.', 'options' => ['A lot', 'much', 'Not many', 'Not a few'], 'correct' => 1],
        ['text' => 'I need just ------ sugar in my tea.', 'options' => ['A lot', 'Few', 'A little', 'Many'], 'correct' => 2],
        ['text' => 'Only ------- students passed the exam, it was really difficult.', 'options' => ['A few', 'A lot', 'Many', 'Much'], 'correct' => 0],
        ['text' => 'I haven’t read ------ stories during the last holiday.', 'options' => ['Lots', 'Many', 'Much', 'Few'], 'correct' => 1],
        ['text' => 'He’s having ----- of trouble passing his driving test.', 'options' => ['A lot', 'A few', 'Much', 'Many'], 'correct' => 0],
        ['text' => 'Have you brought soda to the picnic? I don’t have --------.', 'options' => ['Some', 'Any', 'Many', 'A little'], 'correct' => 1],
        ['text' => 'How do you feel about your new job? Do you have as ------- responsibilities as you used to?', 'options' => ['Much', 'Many', 'A few', 'Little'], 'correct' => 1],
        ['text' => 'This engine has ------ power.', 'options' => ['Little', 'Few', 'Any', 'Some'], 'correct' => 0],
        ['text' => 'I am sorry, but I have ------ time to waste.', 'options' => ['Few', 'Many', 'Much', 'Little'], 'correct' => 3],
        ['text' => 'There’s very ----- communication between them.', 'options' => ['Little', 'Many', 'Few', 'Any'], 'correct' => 0],
        ['text' => '------ Children understood the difference.', 'options' => ['Little', 'Few', 'Any', 'Much'], 'correct' => 1],
        ['text' => 'I have ------ apples in my bag.', 'options' => ['A', 'An', 'Some', 'Any'], 'correct' => 2],
        ['text' => 'There aren’t ----- eggs.', 'options' => ['A', 'An', 'Some', 'many'], 'correct' => 3],
        ['text' => '------- town has a police station.', 'options' => ['Every', 'Some', 'Any', 'All'], 'correct' => 0],
        ['text' => 'Do you have ----- flour? I need ------ for the cake.', 'options' => ['Any/some', 'Some/any', 'A lot of/ not enough', 'Any/a lot of'], 'correct' => 0],
        ['text' => 'These ingredients ------ not ------ to make cakes.', 'options' => ['Are/too much', 'Is/enough', 'Are/ enough', 'Are /some'], 'correct' => 2],
        ['text' => 'There ----- ------ baking powder in this cake.', 'options' => ['Are/too much', 'Is/too many', 'Is/too much', 'Are/too many'], 'correct' => 2],
        ['text' => 'How ------ is your coat?', 'options' => ['Many', 'Much', 'A few', 'A little'], 'correct' => 1],
        ['text' => 'Buy ------ bread and cheese for breakfast tomorrow.', 'options' => ['Some', 'Any', 'Much', 'Many'], 'correct' => 0],
        ['text' => 'You may have coffee without milk because there isn’t ----- at home.', 'options' => ['No', 'Any', 'One', 'Some'], 'correct' => 1],
        ['text' => 'Every day we get ------ magazines and newspapers.', 'options' => ['Lots of', 'Much', 'Few', 'A little'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة محددات الكمية (Quantifiers Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1126.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
