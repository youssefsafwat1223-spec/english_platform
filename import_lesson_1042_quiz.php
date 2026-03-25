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
        ['text' => 'It ______ for hours when the sun finally came out.', 'options' => ['is raining', 'had been raining', 'rained', 'rains'], 'correct' => 1],
        ['text' => 'We ______ for over an hour before the bus arrived.', 'options' => ['waited', 'wait', 'had been waiting', 'has been waiting'], 'correct' => 2],
        ['text' => 'They ______ the game since morning.', 'options' => ['play', 'had been playing', 'are playing', 'played'], 'correct' => 1],
        ['text' => 'I ______ English for three years when I passed the exam.', 'options' => ['study', 'was studying', 'had been studying', 'has studied'], 'correct' => 2],
        ['text' => 'She ______ there for a long time when she got promoted.', 'options' => ['works', 'had been working', 'is working', 'worked'], 'correct' => 1],
        ['text' => 'How long ______ you ______ (wait) for me?', 'options' => ['did wait', 'had been waiting', 'have been waiting', 'none'], 'correct' => 1],
        ['text' => 'They ______ (not / practice) enough before the tournament.', 'options' => ['not practice', 'haven’t practicing', 'had not been practicing', 'none'], 'correct' => 2],
        ['text' => 'She was tired because she ______ (work) hard all day.', 'options' => ['is working', 'had been working', 'worked', 'none'], 'correct' => 1],
        ['text' => 'By the time I arrived, he ______ (stand) there for hours.', 'options' => ['stood', 'stands', 'had been standing', 'none'], 'correct' => 2],
        ['text' => 'Everything was white because it ______ (snow) since yesterday.', 'options' => ['snows', 'had been snowing', 'snow', 'none'], 'correct' => 1],
        ['text' => '______ they ______ (live) in that town for years?', 'options' => ['Did live', 'Had been living', 'Have been living', 'none'], 'correct' => 1],
        ['text' => 'He was out of breath because he ______ (run).', 'options' => ['is running', 'runs', 'had been running', 'none'], 'correct' => 2],
        ['text' => 'We ______ (talk) for an hour when the power went out.', 'options' => ['talk', 'talked', 'had been talking', 'none'], 'correct' => 2],
        ['text' => 'How long ______ she ______ (learn) French before she moved?', 'options' => ['did learn', 'had been learning', 'has been learning', 'none'], 'correct' => 1],
        ['text' => 'I ______ (not / feel) well for a few days before I saw the doctor.', 'options' => ['not feel', 'had not been feeling', 'didn’t feel', 'none'], 'correct' => 1],
        ['text' => 'They ______ (wait) for the train for 20 minutes.', 'options' => ['waited', 'had been waiting', 'are waiting', 'wait'], 'correct' => 1],
        ['text' => 'The ground was wet because it ______ (rain) all night.', 'options' => ['rained', 'rains', 'had been raining', 'is raining'], 'correct' => 2],
        ['text' => 'Had he ______ (exercise) before he felt the pain?', 'options' => ['exercises', 'exercised', 'been exercising', 'none'], 'correct' => 2],
        ['text' => 'How long had you ______ (sleep) when the alarm went off?', 'options' => ['sleep', 'slept', 'been sleeping', 'none'], 'correct' => 2],
        ['text' => 'We ______ (not / drive) for long when we ran out of gas.', 'options' => ['not drive', 'didn’t drive', 'hadn’t been driving', 'none'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي التام المستمر (Past Perfect Continuous Practice)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 20,
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
