<?php

/**
 * Script to import questions for Lesson ID 1161 (Date Practice)
 * php import_lesson_1161_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1161;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1161 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'The date is 1st March:', 'options' => ['It’s the first of March', 'It’s the first March', 'Its first the March', 'Its March of first'], 'correct' => 0],
        ['text' => 'The date is 24th December:', 'options' => ['It’s the twenty-fourth of December', 'Its 24th December', 'It’s the 24th December', 'It’s the twenty fourth December'], 'correct' => 0],
        ['text' => 'What\'s the day after Monday?', 'options' => ['Thursday', 'Tuesday', 'Monday', 'Wednesday'], 'correct' => 1],
        ['text' => 'What date is it today? 12th September:', 'options' => ['September twelve', 'The twelve of September', 'September the twelve', 'The twelfth of September'], 'correct' => 3],
        ['text' => 'The second of May:', 'options' => ['May 2', '2nd May', 'May, 6th', '3rd May'], 'correct' => 1],
        ['text' => 'the fourth of August twenty sixteen:', 'options' => ['4/5/2010', '4/08/2010', '4/8/2016', '8/4/2016'], 'correct' => 2],
        ['text' => 'She was born ____ September.', 'options' => ['On', 'In', 'From', 'To'], 'correct' => 1],
        ['text' => 'I was at the cinema ____ Sunday.', 'options' => ['On', 'In', 'From', 'to'], 'correct' => 0],
        ['text' => '25th January:', 'options' => ['Twenty-fifth of January', 'The twenty-fifth of January', 'The twenty-fifth January', 'Twenty-five January'], 'correct' => 1],
        ['text' => 'The day before Monday is -------', 'options' => ['Saturday', 'Sunday', 'Tuesday', 'Wednesday'], 'correct' => 1],
        ['text' => 'The day after Wednesday is -------', 'options' => ['Tuesday', 'Monday', 'Thursday', 'Friday'], 'correct' => 2],
        ['text' => 'Is your birthday on ___________?', 'options' => ['September', 'April', 'Sunday', 'October'], 'correct' => 2],
        ['text' => 'Which date is written correctly?', 'options' => ['january 12,2016', 'February 14 2016', 'March 11, 2015', 'December, 25 2016'], 'correct' => 2],
        ['text' => 'Which sentence is written correctly?', 'options' => ['He was born in June 3', 'He born on June', 'He born June 3', 'He was born on June 03'], 'correct' => 3],
        ['text' => 'Which sentence is written correctly?', 'options' => ['Was he born in 1992?', 'Was he born on 1992?', 'Was he born from 1992?', 'Was he born at 1992?'], 'correct' => 0],
        ['text' => 'Which sentence is written correctly?', 'options' => ['She died in October 23, 1999', 'She died on October 23 1999', 'She died on October 23, 1999', 'She died at October 23, 1999'], 'correct' => 2],
        ['text' => '2019 is -----', 'options' => ['Two thousand ninety', 'Twenty ninety', 'Two thousand nineteen', 'Two hundred nineteen'], 'correct' => 2],
        ['text' => '2007 is --------', 'options' => ['Two thousand oh seven', 'Oh seven', 'Twenty zero seven', 'Two hundred and seven'], 'correct' => 0],
        ['text' => '21/08/1845 is --------', 'options' => ['The twenty first of August eighteen forty five', 'The eighth of December eighteen forty five', 'The eighth of August eighteen forty five', 'The twenty first of December eighteen forty five'], 'correct' => 0],
        ['text' => 'Twenty ten is the same as...--------', 'options' => ['Two oh ten', 'Two thousand oh ten', 'Two thousand and ten', 'Two thousand'], 'correct' => 2],
        ['text' => '0 is...-------', 'options' => ['Zero', 'Oh', 'Of', 'Zero/oh'], 'correct' => 3],
        ['text' => 'December 24th:', 'options' => ['The twenty fourth of December', 'December the twenty fourth', 'Twenty fourth December', 'December twenty fourth'], 'correct' => 1],
        ['text' => 'March 16th:', 'options' => ['The sixteenth of March', 'March the sixteenth', 'Sixteen of March', 'March sixteenth'], 'correct' => 1],
        ['text' => 'January 8th 1920:', 'options' => ['The eighth of January nineteen twenty', 'January the eighth of nineteen twenty', 'January the eighth nineteen twenty', 'Eight January 1929'], 'correct' => 2],
        ['text' => 'April 2nd:', 'options' => ['April the second', 'April second', 'The second of April', 'April two'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة التاريخ (Date Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1161.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
