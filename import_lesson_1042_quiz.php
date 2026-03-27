<?php

/**
 * Script to import questions for Lesson ID 1042 (Past Perfect Continuous Practice)
 * php import_lesson_1042_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1042;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1042 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Abdullah ------------for two hours before he arrived.', 'options' => ['was driving', 'had been driven', 'has been driving', 'had been driving'], 'correct' => 3],
        ['text' => 'Ibrahim----------all day, so he had a headache.', 'options' => ['had worked', 'was worked', 'have been working', 'had been working'], 'correct' => 3],
        ['text' => 'Khadija----------------information about the accident for two days, when the thief stole her notes.', 'options' => ['had gathered', 'had been gathered', 'was gathering', 'had been gathering'], 'correct' => 3],
        ['text' => 'Sami--------------networking courses for two years when he was promoted to be a coach.', 'options' => ['had taken', 'had been taking', 'have been taking', 'could have taken'], 'correct' => 1],
        ['text' => 'My scores in high school-----------until I made an effort to study more.', 'options' => ['couldn’t be improved', 'haven’t been improved', 'hadn’t been improving', 'wouldn’t have improved'], 'correct' => 2],
        ['text' => 'The staff------been preparing lunch for the special guests since 8 am.', 'options' => ['was', 'have', 'had', 'would'], 'correct' => 2],
        ['text' => 'Once Sally saw me, I ------------for 3 hours.', 'options' => ['had gardened', 'had been gardening', 'gardened', 'was gardening'], 'correct' => 1],
        ['text' => 'Samia-------------for three hours before we met.', 'options' => ['have waited', 'has been waiting', 'had been waiting', 'was waited'], 'correct' => 2],
        ['text' => 'The house was perfect for him, it was exactly what he ----------for.', 'options' => ['had been looking', 'looks', 'is looking', 'have been looking'], 'correct' => 0],
        ['text' => 'James----------medicine for years when he realized he didn’t want to be a doctor.', 'options' => ['was studying', 'has studied', 'studied', 'had been studying'], 'correct' => 3],
        ['text' => 'We -------to visit our grandmother in Jeddah but there wasn’t enough time.', 'options' => ['had been planning', 'had planned', 'have been planning', 'have planned'], 'correct' => 0],
        ['text' => 'He\'d been --------- all day.', 'options' => ['drink', 'drank', 'drinking', 'drinks'], 'correct' => 2],
        ['text' => 'We hadn\'t---- living there long.', 'options' => ['been', 'Be', 'was', 'were'], 'correct' => 0],
        ['text' => 'They -----been studying very hard.', 'options' => ['has not', 'not have', 'had not', 'not have'], 'correct' => 2],
        ['text' => 'Suddenly, my car broke down. I was not surprised. It -----not -------well for a long time.', 'options' => ['had/run', 'had/ran', 'had/ been running', 'had been/ran'], 'correct' => 2],
        ['text' => '------the pilot been drinking before the crash?', 'options' => ['Has', 'Have', 'Had', 'Were'], 'correct' => 2],
        ['text' => 'I am angry. I have been------ for two hours.', 'options' => ['wait', 'waits', 'waiting', 'waited'], 'correct' => 2],
        ['text' => 'The printer--------working all day.', 'options' => ['has', 'had', 'had been', 'have been'], 'correct' => 2],
        ['text' => 'Had the players---------playing by the rules?', 'options' => ['been', 'have', 'be', 'has'], 'correct' => 0],
        ['text' => 'She ________ expecting the worst.', 'options' => ['Had', '\'d been', 'had being', 'have been'], 'correct' => 1],
        ['text' => 'Had the teachers ________ before the strike?', 'options' => ['been work', 'working', 'been working', 'worked'], 'correct' => 2],
        ['text' => 'Our pool pump ________ running properly before the storm.', 'options' => ['had not have', 'hadn\'t been', 'hadn\'t being', 'haven’t been'], 'correct' => 1],
        ['text' => 'My sister’s roommate was upset. She _____ been waiting for an hour.', 'options' => ['had', 'has', 'have', 'was'], 'correct' => 0],
        ['text' => 'I just heard about the accident. ________ been working all night?', 'options' => ['Had the driver', 'Had the driving', 'Have the driver', 'Were the driver'], 'correct' => 0],
        ['text' => 'We were shocked to see her. ________ been expecting her.', 'options' => ['we are', 'we have', 'we\'d not', 'we were'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي التام المستمر (Past Perfect Continuous Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1042.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
