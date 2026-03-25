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
        ['text' => 'The storm ______ the house before we got there.', 'options' => ['has destroyed', 'had destroyed', 'destroy', 'destroys'], 'correct' => 1],
        ['text' => 'I _____ my key, so I couldn’t open the door.', 'options' => ['lose', 'was losing', 'had lost', 'loosing'], 'correct' => 2],
        ['text' => 'The teacher ______ the results before the meeting.', 'options' => ['announce', 'had announced', 'has announced', 'announcing'], 'correct' => 1],
        ['text' => 'She ______ many countries before she was twenty.', 'options' => ['visit', 'had visited', 'visiting', 'visits'], 'correct' => 1],
        ['text' => 'He ______ the report by 10 PM.', 'options' => ['finishes', 'finish', 'had finished', 'was finishing'], 'correct' => 2],
        ['text' => 'I didn’t recognize him because he _____ so much.', 'options' => ['change', 'changes', 'had changed', 'has changed'], 'correct' => 2],
        ['text' => 'When I arrived at the cinema, the movie ______ .', 'options' => ['starts', 'started', 'had started', 'start'], 'correct' => 2],
        ['text' => 'They told me that they _____ to America twice.', 'options' => ['be', 'been', 'had been', 'had was'], 'correct' => 2],
        ['text' => 'After we _____ breakfast, we went for a walk.', 'options' => ['ate', 'eaten', 'had eaten', 'have eaten'], 'correct' => 2],
        ['text' => 'Until yesterday, I ______ never ______ a whale.', 'options' => ['have see', 'had seen', 'has seen', 'had saw'], 'correct' => 1],
        ['text' => 'She _______ (not / study) before the exam.', 'options' => ['not studied', 'hadn’t study', 'had not studied', 'has not studied'], 'correct' => 2],
        ['text' => 'We _______ (not / meet) him before last night.', 'options' => ['didn’t meet', 'haven’t met', 'had not met', 'has not met'], 'correct' => 2],
        ['text' => 'The match _______ (not / start) when we arrived.', 'options' => ['had not started', 'didn’t start', 'starts not', 'haven’t started'], 'correct' => 0],
        ['text' => 'I _______ (not / see) her for five years.', 'options' => ['didn’t saw', 'had not seen', 'haven’t seen', 'not see'], 'correct' => 1],
        ['text' => 'He told me he _______ (not / sleep) for two days.', 'options' => ['didn’t sleep', 'not slept', 'had not slept', 'haven’t slept'], 'correct' => 2],
        ['text' => '______ you ______ (finish) your work before you left?', 'options' => ['Did finish', 'Had finished', 'Has finished', 'none'], 'correct' => 1],
        ['text' => '______ she ______ (be) there before?', 'options' => ['Had been', 'Has been', 'Did be', 'none'], 'correct' => 0],
        ['text' => '______ they ______ (prepare) everything for the party?', 'options' => ['Had prepared', 'Did prepare', 'Have prepared', 'none'], 'correct' => 0],
        ['text' => '______ he ______ (tell) you the truth?', 'options' => ['Had told', 'Did tell', 'Has told', 'none'], 'correct' => 0],
        ['text' => 'Where ______ you ______ (hide) the book?', 'options' => ['did hide', 'had hidden', 'have hidden', 'none'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي التام (Past Perfect Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1033.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
