<?php

/**
 * Script to import questions for Lesson ID 1069 (Future Continuous Practice)
 * php import_lesson_1069_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1069;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1069 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I will be _____ at midnight.', 'options' => ['Sleep', 'Sleeping', 'Slept', 'Is sleeping'], 'correct' => 1],
        ['text' => 'I will be _______ at 10 am tomorrow Monday.', 'options' => ['Work', 'Works', 'Working', 'Worked'], 'correct' => 2],
        ['text' => 'She will be ________ the movie tonight.', 'options' => ['Watch', 'Watches', 'Watching', 'Watched'], 'correct' => 2],
        ['text' => 'My cousin ______ to university next year because of his poor result.', 'options' => ['Will not to go', 'Will not be going', 'Is going', 'Going to'], 'correct' => 1],
        ['text' => 'Aishah\'s mother ________ her to her grandparents\' house next Friday afternoon.', 'options' => ['Will be driving', 'Be drive', 'Drove', 'Drives'], 'correct' => 0],
        ['text' => 'Don\'t phone between 7 and 8. We ____ dinner then.', 'options' => ['’ll be having', 'Having', 'Am having', 'Had'], 'correct' => 0],
        ['text' => 'Abdullah ___ to visit us this Sunday. You can ask him then.', 'options' => ['Is going to be coming', 'Will comes', 'Be coming', 'Came'], 'correct' => 0],
        ['text' => 'They ________ the cottage this weekend.', 'options' => ['Using', 'Will be using', 'Are be using', 'use'], 'correct' => 1],
        ['text' => 'He will not _____ taking the bus today.', 'options' => ['Is', 'Be', 'Been', 'Being'], 'correct' => 1],
        ['text' => 'Ahmed _____ be coming to the picnic.', 'options' => ['Won’t', 'Won’t not', 'Willn’t', 'Isn’t'], 'correct' => 0],
        ['text' => '________ sleeping at 9 pm?', 'options' => ['You be', 'Will be', 'Will you be', 'Will you'], 'correct' => 2],
        ['text' => 'Don\'t forget your snow pants. It ________ by the time you get to school.', 'options' => ['Will snowing', 'Is snowing', 'Will be snowing', 'Snows'], 'correct' => 2],
        ['text' => 'At noon tomorrow, I ________ on a beach somewhere.', 'options' => ['’ll be relaxing', 'Relax', 'Will being relax', 'Relax'], 'correct' => 0],
        ['text' => 'Sorry, I can\'t. I ________ my daughter to work at that time.', 'options' => ['Will take', 'Will be taking', 'Won’t be', 'Take'], 'correct' => 1],
        ['text' => 'At three tomorrow, he __ be waiting for the train.', 'options' => ['’s', '’ll', 'Is', 'Does'], 'correct' => 1],
        ['text' => 'Abdulrahman ___ late. He’s stuck in traffic.', 'options' => ['Is going to be arriving', 'Will arrive', 'Is going to', 'Will arrives'], 'correct' => 0],
        ['text' => '____ you get home, we’ll be having dinner.', 'options' => ['By the time', 'If', 'In time', 'By'], 'correct' => 0],
        ['text' => 'Khalid just called. He said he __ to the meeting.', 'options' => ['Won’t be coming', 'Will comes', 'Willn’t come', 'Be come'], 'correct' => 0],
        ['text' => 'Please, come at 8. I ______ my homework and then we can go out.', 'options' => ['’ll finish', '’ll be finishing', '’ll have finished', 'finish'], 'correct' => 1],
        ['text' => 'we __(no, write) her assignment when you arrive.', 'options' => ['Won’t be writing', 'Will’nt write', 'Be written', 'Not written'], 'correct' => 0],
        ['text' => 'She ___ her presentation during the conference call at 10 AM.', 'options' => ['Will give', 'Will be giving', 'Is giving', 'Will be gave'], 'correct' => 1],
        ['text' => 'At this time tomorrow, I ___ my final exam.', 'options' => ['Will be taking', 'Will take', 'Am taking', 'Is going to take'], 'correct' => 0],
        ['text' => 'We __ the new software when you arrive.', 'options' => ['Will test', 'Will be testing', 'Will have test', 'Is going to be testing'], 'correct' => 1],
        ['text' => 'They __ a party when you call them.', 'options' => ['Will have', 'Will be having', 'Are having', 'Is \ going to be having'], 'correct' => 1],
        ['text' => 'At this time tomorrow, I ___ on a plane to Paris.', 'options' => ['Will be sitting', 'Will sit', 'Am sitting', 'Are going to be sitting'], 'correct' => 0],
        ['text' => 'We ___ the final touches on the project when you come in.', 'options' => ['Will put', 'Will be putting', 'Are putting', 'Are going to be putting'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة المستقبل المستمر (Future Continuous Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1069.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
