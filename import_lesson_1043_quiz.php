<?php

/**
 * Script to import questions for Lesson ID 1043 (Past Tenses Review)
 * php import_lesson_1043_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1043;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1043 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I _____ (go) to the park yesterday.', 'options' => ['go', 'went', 'gone', 'was going'], 'correct' => 1],
        ['text' => 'They _____ (play) football when it started to rain.', 'options' => ['played', 'were playing', 'have played', 'play'], 'correct' => 1],
        ['text' => 'By the time I arrived, the movie _____ (start).', 'options' => ['started', 'has started', 'had started', 'starts'], 'correct' => 2],
        ['text' => 'She _____ (wait) for over an hour before the bus finally came.', 'options' => ['waited', 'was waiting', 'had been waiting', 'has been waiting'], 'correct' => 2],
        ['text' => 'When I was younger, I _____ (live) in Cairo.', 'options' => ['live', 'lived', 'am living', 'was lived'], 'correct' => 1],
        ['text' => 'He _____ (write) a letter while his sister was reading.', 'options' => ['wrote', 'was writing', 'writes', 'written'], 'correct' => 1],
        ['text' => 'Until last year, I _____ never _____ (see) a whale.', 'options' => ['have see', 'had seen', 'saw', 'was seen'], 'correct' => 1],
        ['text' => 'They _____ (drive) for hours when they ran out of petrol.', 'options' => ['drove', 'had been driving', 'were driving', 'drive'], 'correct' => 1],
        ['text' => 'She _____ (finish) her homework before she went out.', 'options' => ['finished', 'had finished', 'was finishing', 'finishes'], 'correct' => 1],
        ['text' => 'What _____ you _____ (do) at 8 PM last night?', 'options' => ['did do', 'were doing', 'have done', 'do'], 'correct' => 1],
        ['text' => 'I _____ (meet) him two years ago.', 'options' => ['meet', 'met', 'have met', 'was meeting'], 'correct' => 1],
        ['text' => 'While I _____ (wash) the dishes, the phone rang.', 'options' => ['wash', 'was washing', 'washed', 'was washed'], 'correct' => 1],
        ['text' => 'He apologized because he _____ (forget) his notebook.', 'options' => ['forgets', 'forgot', 'had forgotten', 'was forgetting'], 'correct' => 2],
        ['text' => 'We _____ (walk) for ages before we found the shop.', 'options' => ['walked', 'were walking', 'had been walking', 'have walked'], 'correct' => 2],
        ['text' => 'The sun _____ (shine) when I woke up this morning.', 'options' => ['shone', 'was shining', 'shines', 'has shone'], 'correct' => 1],
        ['text' => 'I _____ (buy) this car in 2020.', 'options' => ['buy', 'bought', 'have bought', 'was buying'], 'correct' => 1],
        ['text' => 'She _____ (read) the book before she saw the film.', 'options' => ['reads', 'read (simple)', 'had read', 'was reading'], 'correct' => 2],
        ['text' => 'It _____ (snow) for days before the roads were closed.', 'options' => ['snowed', 'was snowing', 'had been snowing', 'snows'], 'correct' => 2],
        ['text' => 'They _____ (not / hear) the doorbell because the music was too loud.', 'options' => ['haven’t heard', 'didn’t hear', 'not heard', 'none'], 'correct' => 1],
        ['text' => '_____ you _____ (see) that new exhibition last week?', 'options' => ['Did see', 'Have seen', 'Had seen', 'none'], 'correct' => 0],
        ['text' => 'At this time yesterday, we _____ (fly) to Dubai.', 'options' => ['flew', 'were flying', 'have flown', 'fly'], 'correct' => 1],
        ['text' => 'After he _____ (eat) his lunch, he went back to work.', 'options' => ['eats', 'ate', 'had eaten', 'have eaten'], 'correct' => 2],
        ['text' => 'How long _____ they _____ (wait) in the queue?', 'options' => ['did wait', 'were waiting', 'had been waiting', 'none'], 'correct' => 2],
        ['text' => 'I _____ (lose) my umbrella, so I got wet.', 'options' => ['lose', 'lost', 'had lost', 'loosing'], 'correct' => 1],
        ['text' => 'When the teacher entered, the students _____ (talk).', 'options' => ['talked', 'were talking', 'talk', 'have talked'], 'correct' => 1],
        ['text' => 'She failed because she _____ (not / study) enough.', 'options' => ['not studied', 'didn’t study', 'had not studied', 'none'], 'correct' => 2],
        ['text' => 'I _____ (not / understand) what he said.', 'options' => ['haven’t understood', 'didn’t understand', 'not understood', 'none'], 'correct' => 1],
        ['text' => 'They _____ (watch) TV when the power went out.', 'options' => ['watched', 'were watching', 'watch', 'have watched'], 'correct' => 1],
        ['text' => 'Had you _____ (meet) her before?', 'options' => ['meet', 'met', 'meeting', 'none'], 'correct' => 1],
        ['text' => 'It _____ (rain) since 6 o’clock when I left the office.', 'options' => ['rained', 'was raining', 'had been raining', 'rains'], 'correct' => 2],
        ['text' => 'We _____ (not / have) enough money for a taxi.', 'options' => ['haven’t', 'didn’t have', 'had not', 'none'], 'correct' => 1],
        ['text' => 'What _____ he _____ (say) when you told him the news?', 'options' => ['did say', 'was saying', 'had said', 'none'], 'correct' => 0],
        ['text' => 'I _____ (already / finish) my work when the boss asked for it.', 'options' => ['already finished', 'had already finished', 'was already finishing', 'none'], 'correct' => 1],
        ['text' => 'She _____ (practice) the piano for years before her first concert.', 'options' => ['practiced', 'was practicing', 'had been practicing', 'none'], 'correct' => 2],
        ['text' => 'They _____ (be) very happy to see us.', 'options' => ['was', 'were', 'been', 'are'], 'correct' => 1],
        ['text' => 'I _____ (see) a ghost last night!', 'options' => ['see', 'saw', 'seen', 'have seen'], 'correct' => 1],
        ['text' => 'The children _____ (sleep) when we got home.', 'options' => ['slept', 'were sleeping', 'sleep', 'none'], 'correct' => 1],
        ['text' => 'She told me she _____ (lost) her keys.', 'options' => ['lost', 'had lost', 'loses', 'none'], 'correct' => 1],
        ['text' => 'Everything _____ (become) clear after he explained it.', 'options' => ['becomes', 'became', 'had become', 'none'], 'correct' => 1],
        ['text' => 'We _____ (travel) all day, so we were exhausted.', 'options' => ['traveled', 'were traveling', 'had been traveling', 'none'], 'correct' => 2],
        ['text' => 'What time _____ you _____ (arrive) at the party?', 'options' => ['did arrive', 'were arriving', 'have arrived', 'none'], 'correct' => 0],
        ['text' => 'I _____ (not / like) that movie very much.', 'options' => ['didn’t like', 'haven’t liked', 'not liked', 'none'], 'correct' => 0],
        ['text' => '_____ he _____ (work) at that time?', 'options' => ['Did work', 'Was working', 'Had worked', 'none'], 'correct' => 1],
        ['text' => 'They _____ (live) in Cairo for five years before they moved to Giza.', 'options' => ['lived', 'were living', 'had been living', 'none'], 'correct' => 2],
        ['text' => 'I _____ (know) him since we were children.', 'options' => ['know', 'knew', 'have known', 'was knowing'], 'correct' => 1], // Stative verb usually past simple here if fixed time
        ['text' => 'She _____ (study) hard, so she passed the exam.', 'options' => ['studies', 'studied', 'was studying', 'none'], 'correct' => 1],
        ['text' => 'The match _____ (already / start) when we turned on the TV.', 'options' => ['already started', 'had already started', 'was already starting', 'none'], 'correct' => 1],
        ['text' => 'It _____ (get) dark when we finally reached the hotel.', 'options' => ['got', 'was getting', 'had been getting', 'none'], 'correct' => 1],
        ['text' => 'They _____ (wait) for ages before the bus came.', 'options' => ['waited', 'were waiting', 'had been waiting', 'none'], 'correct' => 2],
        ['text' => 'I _____ (have) a wonderful time in Paris last summer.', 'options' => ['have', 'had', 'am having', 'was had'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة أزمنة الماضي (Past Tenses Review)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1043.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
