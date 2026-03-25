<?php

/**
 * Script to import questions for Lesson ID 1026 (Past Continuous Practice)
 * php import_lesson_1026_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1026;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1026 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'My brother and sister _____ playing tennis at 11am yesterday.', 'options' => ['was', 'were', 'is', 'are'], 'correct' => 1],
        ['text' => '_____ you still working at 7pm last night?', 'options' => ['Is', 'Are', 'Was', 'were'], 'correct' => 3],
        ['text' => 'At 8.30am today I _____ driving to work.', 'options' => ['is', 'am', 'are', 'was'], 'correct' => 3],
        ['text' => 'Was he _____ his homework?', 'options' => ['do', 'doing', 'done', 'does'], 'correct' => 1],
        ['text' => 'They ________ TV when I arrived.', 'options' => ['was watching', 'were watching', 'watched', 'watched'], 'correct' => 1],
        ['text' => 'We ------ sleeping when the police came.', 'options' => ['was', 'were', 'is', 'are'], 'correct' => 1],
        ['text' => '------ they swimming when the phone rang?', 'options' => ['Did', 'Was', 'Were', 'Are'], 'correct' => 2],
        ['text' => 'Mrs. Adam was--------dinner at 6 o’clock yesterday morning.', 'options' => ['hasing', 'having', 'have', 'had'], 'correct' => 1],
        ['text' => 'Yesterday, at six my mother--------dinner.', 'options' => ['was making', 'was made', 'was make', 'Did make'], 'correct' => 0],
        ['text' => 'My baby brother-------so I couldn’t do my homework.', 'options' => ['was cried', 'was crying', 'cried', 'has cried'], 'correct' => 1],
        ['text' => 'The school gardener------in the garden all yesterday evening.', 'options' => ['were digging', 'was digging', 'is digging', 'are digging'], 'correct' => 1],
        ['text' => 'She was-------- when I entered the kitchen.', 'options' => ['cooks', 'cooked', 'cooking', 'cook'], 'correct' => 2],
        ['text' => 'Hafith didn’t go out yesterday because it-------all day.', 'options' => ['rained', 'is raining', 'rains', 'was raining'], 'correct' => 3],
        ['text' => 'Athletes -------very hard for the sports all last week.', 'options' => ['are practising', 'is practising', 'was practising', 'were practising'], 'correct' => 3],
        ['text' => 'Susan------playing the piano when someone knocked on the door.', 'options' => ['are not', 'is not', 'were not', 'was not'], 'correct' => 3],
        ['text' => 'Ahmed and his friend -------to the teacher.', 'options' => ['are talk', 'were talking', 'am talking', 'talks'], 'correct' => 1],
        ['text' => 'Was Seba drinking milk yesterday morning?', 'options' => ['Yes, she is', 'Yes, she was', 'No, she was', 'No, she isn’t'], 'correct' => 1],
        ['text' => 'Aisha -------at 5 pm yesterday.', 'options' => ['were working', 'was working', 'working', 'was'], 'correct' => 1],
        ['text' => 'He -------to the radio.', 'options' => ['was watching', 'was reading', 'was listening', 'was writing'], 'correct' => 2],
        ['text' => '------------going home?', 'options' => ['Was you', 'Were you', 'Was', 'Were'], 'correct' => 1],
        ['text' => 'We ----- for our lost friend.', 'options' => ['were looking', 'was looking', 'is looking', 'looking'], 'correct' => 0],
        ['text' => 'My grandfather-----reading a newspaper.', 'options' => ['are’t', 'wasn’t', 'have’t', 'Hasn’t'], 'correct' => 1],
        ['text' => 'Sara------chess yesterday at 7 pm.', 'options' => ['was playing', 'played', 'was played', 'has played'], 'correct' => 0],
        ['text' => 'The ducks were-------- .', 'options' => ['swimming', 'was swimming', 'were swims', 'was swim'], 'correct' => 0],
        ['text' => '---------my mother cooking fish?', 'options' => ['Was', 'Were', 'Has', 'Have'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي المستمر (Past Continuous Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1026.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
