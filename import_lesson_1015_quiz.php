<?php

/**
 * Script to import questions for Lesson ID 1015 (Past Simple Practice)
 * php import_lesson_1015_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1015;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1015 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => '--------you work in a hospital last year?', 'options' => ['Do', 'Did', 'Does', 'Done'], 'correct' => 1],
        ['text' => 'He --------the Nobel Prize last year.', 'options' => ['win', 'wins', 'won', 'winned'], 'correct' => 2],
        ['text' => 'She -------to visit her grandmother yesterday.', 'options' => ['go', 'went', 'goes', 'gone'], 'correct' => 1],
        ['text' => 'Columbus ------America in 1492.', 'options' => ['discovered', 'discover', 'discovers', 'discovering'], 'correct' => 0],
        ['text' => 'My friend didn’t ------very hard in this presentation.', 'options' => ['worked', 'works', 'work', 'working'], 'correct' => 2],
        ['text' => 'My sisters---------fixing the car last week.', 'options' => ['finish', 'finishing', 'finishes', 'finished'], 'correct' => 3],
        ['text' => 'It --------raining two hours ago.', 'options' => ['Stops', 'Stopped', 'Stopping', 'Stop'], 'correct' => 1],
        ['text' => '----------your friend open the door and see the thief?', 'options' => ['Do', 'Did', 'Does', 'Is'], 'correct' => 1],
        ['text' => 'Why did she---------you in the middle of the night?', 'options' => ['called', 'calls', 'call', 'calling'], 'correct' => 2],
        ['text' => 'Did you see Saleem-------?', 'options' => ['now', 'everyday', 'last summer', 'already'], 'correct' => 2],
        ['text' => 'Mona didn’t -------- a story yesterday?', 'options' => ['Write', 'Wrote', 'Written', 'Writes'], 'correct' => 0],
        ['text' => 'Ameer------ a new pair of shoes last month.', 'options' => ['bought', 'buys', 'buy', 'buyed'], 'correct' => 0],
        ['text' => 'I--------at the bank two hours ago.', 'options' => ['was', 'were', 'be', 'did'], 'correct' => 0],
        ['text' => '-------- the visitor enjoy the tour last week?', 'options' => ['Do', 'Does', 'Is', 'Did'], 'correct' => 3],
        ['text' => '--------Hadeel-------a cake?', 'options' => ['Does/bake', 'Do/bake', 'Did/bake', 'Did/baked'], 'correct' => 2],
        ['text' => 'They ------ --- to Haifa last year.', 'options' => ['did move', 'does move', 'didn’t moved', 'didn’t move'], 'correct' => 3],
        ['text' => 'Ameer didn’t -------well yesterday.', 'options' => ['fell', 'feels', 'feel', 'feeled'], 'correct' => 2],
        ['text' => 'Kareem and I -------tired two hours ago.', 'options' => ['didn’t', 'were', 'was', 'don’t'], 'correct' => 1],
        ['text' => 'Deema ---------- the teacher yesterday.', 'options' => ['hear', 'hears', 'didn’t hear', 'don’t hear'], 'correct' => 2],
        ['text' => 'Amal ------the car carefully last night.', 'options' => ['drive', 'drove', 'drives', 'drived'], 'correct' => 1],
        ['text' => 'Moneer and his friend -------emails last night.', 'options' => ['doesn’t send', 'didn’t sent', 'didn’t send', 'don’t send'], 'correct' => 2],
        ['text' => 'Salem------learn at this school in 2008.', 'options' => ['didn’t', 'don’t', 'doesn’t', 'haven’t'], 'correct' => 0],
        ['text' => 'Last night we------- to the cinema.', 'options' => ['walk', 'walked', 'walks', 'walking'], 'correct' => 1],
        ['text' => 'I didn’t --------to the swimming pool yesterday.', 'options' => ['go', 'went', 'goes', 'gone'], 'correct' => 0],
        ['text' => 'Did soha studied at this school last year?', 'options' => ['Yes, she do', 'Yes, she did', 'No, she did', 'Yes, she didn’t'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الماضي البسيط (Past Simple Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1015.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
