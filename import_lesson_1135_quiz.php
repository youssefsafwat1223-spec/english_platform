<?php

/**
 * Script to import questions for Lesson ID 1135 (Delexical Verbs Practice)
 * php import_lesson_1135_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1135;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1135 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'John is _____ a shower now.', 'options' => ['Having', 'Getting', 'Taking', 'Having/taking'], 'correct' => 3],
        ['text' => 'I_____too many coffee last night.', 'options' => ['Got', 'Had', 'Took', 'Went'], 'correct' => 1],
        ['text' => '_____me a shout when you are ready to go.', 'options' => ['Take', 'Give', 'Get', 'Do'], 'correct' => 1],
        ['text' => 'He was prepared to _____ great risks.', 'options' => ['Take', 'Give', 'Get', 'Do'], 'correct' => 0],
        ['text' => 'They _____ a serious argument about their father\'s will yesterday.', 'options' => ['Had', 'Got', 'Made', 'Took'], 'correct' => 0],
        ['text' => 'He _____ the signal, and the race started.', 'options' => ['Took', 'Gave', 'Made', 'Had'], 'correct' => 1],
        ['text' => 'Can you please _____ the washing?', 'options' => ['Have', 'Get', 'Do', 'Go'], 'correct' => 2],
        ['text' => 'I need you to _____ the grocery shopping.', 'options' => ['Do', 'Go', 'Make', 'Have'], 'correct' => 0],
        ['text' => 'I\'m tired. I\'m going to _____ a nap.', 'options' => ['Take', 'Get', 'Have', 'Have/take'], 'correct' => 3],
        ['text' => 'My dad always _____ me good advice.', 'options' => ['Takes', 'Gets', 'Gives', 'Has'], 'correct' => 2],
        ['text' => 'Are you ____fishing next week?', 'options' => ['Going', 'Doing', 'Making', 'Having'], 'correct' => 0],
        ['text' => 'Let me ..... you an example of what I mean.', 'options' => ['Give', 'Take', 'Have', 'Go'], 'correct' => 0],
        ['text' => 'There\'s no hurry, so ..... your time.', 'options' => ['Give', 'Take', 'Have', 'Go'], 'correct' => 1],
        ['text' => 'When do you usually ..... breakfast?', 'options' => ['Give', 'Take', 'Have', 'Go'], 'correct' => 2],
        ['text' => 'We ..... a wonderful holiday last year.', 'options' => ['Gave', 'Took', 'Had', 'Went'], 'correct' => 2],
        ['text' => 'They decided to ...... for a swim in the ocean.', 'options' => ['Give', 'Take', 'Go', 'Make'], 'correct' => 2],
        ['text' => '...... care of yourself!', 'options' => ['Take', 'Give', 'Do', 'Make'], 'correct' => 0],
        ['text' => 'They were ..... an interesting chat about their thesis.', 'options' => ['Giving', 'Taking', 'Having', 'Going'], 'correct' => 2],
        ['text' => 'Most sensible people don\'t like ..... risks.', 'options' => ['Giving', 'Taking', 'Having', 'Going'], 'correct' => 1],
        ['text' => 'I hate to get ....................... early in the morning.', 'options' => ['On', 'Off', 'Up', 'Away'], 'correct' => 2],
        ['text' => 'It didn\'t take long for the rumor to get ........', 'options' => ['Out', 'Over', 'Around', 'Up'], 'correct' => 2],
        ['text' => 'They were married for 20 years, but they got _ _ _ _ _ last year.', 'options' => ['Fit', 'Divorced', 'Together', 'Nervous'], 'correct' => 1],
        ['text' => 'It\'s Diana\'s birthday next week. Shall we ------ her a present?', 'options' => ['Take', 'Have', 'Get', 'Go'], 'correct' => 2],
        ['text' => 'How long does it take you to get _____ after work?', 'options' => ['Home', 'Up', 'Out', 'Back'], 'correct' => 0],
        ['text' => 'We\'ve been to your house before, so we won\'t get______.', 'options' => ['Lazy', 'Tired', 'Lost', 'away'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ممارسة الأفعال الاصطلاحية (Delexical Verbs Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1135.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
