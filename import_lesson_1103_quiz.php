<?php

/**
 * Script to import questions for Lesson ID 1103 (Modal Verbs Practice)
 * php import_lesson_1103_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1103;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1103 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Ahmed ----- swim very well, he is a great swimmer.', 'options' => ['Should', 'Can', 'Mustn’t', 'Can’t'], 'correct' => 1],
        ['text' => 'The doctor told him that he ------ eat less. He is obese.', 'options' => ['Should', 'Shouldn’t', 'Mustn’t', 'Can'], 'correct' => 0],
        ['text' => 'You ----- swim here.it’s dangerous.', 'options' => ['Must', 'Mustn’t', 'Should', 'Shouldn’t'], 'correct' => 1],
        ['text' => 'You ------ spend too much time playing video games. It’s bad for your social health.', 'options' => ['Should', 'Shouldn’t', 'Must', 'Can'], 'correct' => 1],
        ['text' => '------- you help me? This exercise is really hard.', 'options' => ['Should', 'Can', 'Must', 'Mustn’t'], 'correct' => 1],
        ['text' => 'You ------ touch this snake! It can bite you.', 'options' => ['Should', 'Must', 'Mustn’t', 'Can'], 'correct' => 2],
        ['text' => 'You ----- listen to this podcast. It’s really good.', 'options' => ['Must', 'Should', 'Shouldn’t', 'Mustn’t'], 'correct' => 1],
        ['text' => 'The clothes are very dirty. I ------- wash them.', 'options' => ['Must', 'Mustn’t', 'Shouldn’t', 'Can'], 'correct' => 0],
        ['text' => 'You ------- park here.its illegal.', 'options' => ['Must', 'Mustn’t', 'Can', 'Shall'], 'correct' => 1],
        ['text' => 'He ----- come to school today but I am not sure.', 'options' => ['Shall', 'May', 'Will', 'Can'], 'correct' => 1],
        ['text' => '------ you help Seba with her homework yesterday?', 'options' => ['Could', 'Might', 'Must', 'Should'], 'correct' => 0],
        ['text' => 'Abdullah ----- play both the piano and the guitar.', 'options' => ['Can', 'Could', 'Must', 'mustn’t'], 'correct' => 0],
        ['text' => 'You ------ work so hard.', 'options' => ['Should', 'Would', 'Might', 'Should no'], 'correct' => 0],
        ['text' => '------ you help me move this table?', 'options' => ['Could', 'Shall', 'Should', 'Must'], 'correct' => 0],
        ['text' => 'You ------ forget the medicine. It’s urgent.', 'options' => ['Must', 'Had better', 'Mustn’t', 'Can'], 'correct' => 2],
        ['text' => 'She ----- be very pleased with herself. She got the best grades.', 'options' => ['Must', 'Had better', 'May', 'Will'], 'correct' => 0],
        ['text' => '------- I open the window, please?', 'options' => ['Must', 'Should', 'May', 'Will'], 'correct' => 2],
        ['text' => 'You ----- eat too much chocolate. It’s not good for you.', 'options' => ['Mustn’t', 'Shouldn’t', 'Wont', 'Had better not'], 'correct' => 1],
        ['text' => 'Entrance to the museum was free. We ----- pay to get in.', 'options' => ['Didn’t have to', 'Don’t have to', 'Mustn’t', 'Shouldn’t'], 'correct' => 0],
        ['text' => '------ you swim?', 'options' => ['Are you able to', 'Can', 'Should', 'Will'], 'correct' => 1],
        ['text' => 'It’s very important to ----- speak more than one language.', 'options' => ['Can', 'Be able to', 'Shall', 'Will'], 'correct' => 1],
        ['text' => 'Whose is this bag? I don’t know, but it ------ belong to Ahmed.', 'options' => ['Could', 'May', 'Should', 'Would'], 'correct' => 0],
        ['text' => 'I ------ move the table. It was too heavy.', 'options' => ['Couldn’t', 'Mustn’t', 'Shouldn’t', 'Wouldn’t'], 'correct' => 0],
        ['text' => 'I ------ be able to help you, but I am not sure yet.', 'options' => ['Might', 'Would', 'Can', 'Shall'], 'correct' => 0],
        ['text' => 'I didn’t feel very well yesterday. I ----- eat anything.', 'options' => ['Can’t', 'Couldn’t', 'Must', 'Mustn’t'], 'correct' => 1],
        ['text' => 'What do you use for advice?', 'options' => ['Should/had better', 'Must/mustn’t', 'Will/won’t', 'May/might'], 'correct' => 0],
        ['text' => 'What do you use for obligation?', 'options' => ['Must', 'Will', 'May', 'Should'], 'correct' => 0],
        ['text' => 'What do you use for possibility?', 'options' => ['Will', 'May/might', 'Should/ had better', 'Must/mustn’t'], 'correct' => 1],
        ['text' => 'I ------- go to school tomorrow, tomorrow is Friday.', 'options' => ['Have to', 'Must', 'Don’t have to', 'Don’t has to'], 'correct' => 2],
        ['text' => 'Children mustn’t ------ in a dangerous place.', 'options' => ['Swam', 'Swum', 'Swims', 'swim'], 'correct' => 3],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الأفعال الناقصة (Modal Verbs Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1103.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
