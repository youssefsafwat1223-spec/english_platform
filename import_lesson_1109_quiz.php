<?php

/**
 * Script to import questions for Lesson ID 1109 (Interrogative Pronouns Translation)
 * php import_lesson_1109_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1109;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1109 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'When do they eat lunch? اختر الترجمة الصحيحة للسؤال:', 'options' => ['متى هم يأكلون الغذاء؟', 'متى هم أكلوا الغذاء؟', 'من هم الذين يأكلون الغذاء؟', 'ماذا هم يأكلون للغذاء؟'], 'correct' => 0],
        ['text' => 'Whose signature is on the document? اختر الترجمة الصحيحة للسؤال:', 'options' => ['من كتب هذه الوثيقة؟', 'لمن هذه الوثيقة؟', 'من توقيعه على الوثيقة؟', 'من اين هذه الوثيقة؟'], 'correct' => 2],
        ['text' => 'Where are we going to play soccer? اختر الترجمة الصحيحة للسؤال:', 'options' => ['متى نحن سوف نلعب كرة قدم؟', 'أين نحن راح نلعب كرة قدم؟', 'لماذا نحن سوف نلعب كرة قدم؟', 'اين نحن سوف نلعب كرة قدم؟'], 'correct' => 1],
        ['text' => 'What are they watching at the cinema? اختر الترجمة الصحيحة للسؤال:', 'options' => ['ماذا هم ( وش) قاعدين يشاهدون في السينما؟', 'لماذا هم يذهبون الى السينما؟', 'ما الذي يشاهدون في السينما؟', 'متى هم يشاهدون في السينما؟'], 'correct' => 0],
        ['text' => 'Whom has the committee selected as the winner? اختر الترجمة الصحيحة للسؤال:', 'options' => ['من الذي اختارته اللجنة كفائز؟', 'من الذي اختار اللجنة كفائزة؟', 'لمن اختارته اللجنة كفائز؟', 'كيف اختارته اللجنة كفائز؟'], 'correct' => 0],
        ['text' => 'How many chairs do they need for the party? اختر الترجمة الصحيحة للسؤال:', 'options' => ['كم ثمن الكراسي التي يحتاجوها للحفلة؟', 'كم عدد الكراسي التي يحتاجوها للحفلة؟', 'كيف الكراسي التي يحتاجوها للحفلة؟', 'كم عدد الكراسي التي يحتاجها للحفلة؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (أين قاعدين يلعبون الأطفال؟):', 'options' => ['Who are the children playing?', 'Where are the children playing?', 'When do the children play?', 'What will the children play?'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( متى سوف يغادر القطار؟):', 'options' => ['When will the train leave?', 'How will the train leave?', 'When is the train going to leave?', 'Why will the train leave?'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( من المدير التنفيذي لهذه الشركة؟):', 'options' => ['Who was the CEO of this company?', 'What is the CEO of this company?', 'Who is the CEO of this company?', 'Whom is CEO of this company?'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( كم عدد الاخوة الي تملك)؟', 'options' => ['How many friends do you have?', 'How many siblings do you have?', 'How many siblings did you have?', 'How much siblings do you have?'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (أي خيار هو الأفضل؟):', 'options' => ['Which option is the best?', 'Whose option is the best?', 'Who option is the best?', 'How many option is the best?'], 'correct' => 0],
        ['text' => '(Author – is – book – of - that – ? - who – the) اختر الترتيب الصحيح للسؤال:', 'options' => ['Who is that author of the book?', 'Who is the book of that auther?', 'Who is the author of that book?', 'Who is the author?of that book'], 'correct' => 2],
        ['text' => '(Long – how – Albaha – they – did – stay – in - ?) اختر الترتيب الصحيح للسؤال:', 'options' => ['How long did they stay in Albaha?', 'How did they long stay in Albaha?', 'How stay did they long in Albaha?', 'How long did they stay ?in Albaha'], 'correct' => 0],
        ['text' => '(house - were - Whose - we - noon - ?- visiting - yesterday - at) اختر الترتيب الصحيح للسؤال:', 'options' => ['Noon house were we visiting yesterday at whose?', 'Whose noon were we visiting yesterday at house?', 'Whose house were we visiting yesterday at noon?', 'Whose house were we visiting yesterday at noon?'], 'correct' => 2], // The prompt has identical options 3 and 4? I'll use 2 as correct index if 3/4 are correct.
        ['text' => '(Is -Much – bottle – the – how – water – in ?) اختر الترتيب الصحيح للسؤال:', 'options' => ['How much water is in the bottle?', 'How much bottle in is the water?', 'How is water much in the bottle?', 'How much is?water in the bottle'], 'correct' => 0],
        ['text' => '(Late – were – why – you – the - meeting – for ?) اختر الترتيب الصحيح للسؤال:', 'options' => ['Why were the you late for meeting?', 'Why you were late for the meeting?', 'Why were you late for the meeting?', 'Why were you late for the meeting?'], 'correct' => 2],
        ['text' => 'What’s the name of your grandfather? اختر الترجمة الصحيحة للسؤال:', 'options' => ['ماذا يكون اسم جدك؟', 'ماذا يكون اسم جدتك؟', 'من هو جدك؟', 'كم يكون عمر جدك؟'], 'correct' => 0],
        ['text' => 'How did you hear about us? اختر الترجمة الصحيحة للسؤال:', 'options' => ['كيف تسمع (انت) عننا؟', 'كيف سمعت (انت) عننا؟', 'كيف عرفت (انت) عننا؟', 'متى سمعت (انت) عننا؟'], 'correct' => 1],
        ['text' => 'When was the last time you saw your parents? اختر الترجمة الصحيحة للسؤال:', 'options' => ['متى كانت اخر مرة رأيت فيها والديك؟', 'اين كانت اخر مرة رأيت فيها والديك؟', 'متى تكون اخر مرة رأيت فيها والديك؟', 'كيف كانت اخر مرة رأيت فيها والديك؟'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( كيف قد حققت سارة أهدافها؟):', 'options' => ['How did Sarah accomplished her goals?', 'How has Sarah accomplish her goals?', 'How has Sarah accomplished her goals?', 'How have Sarah accomplished her goals?'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (ماذا ترى على الشاشة؟):', 'options' => ['What do you see on the screen?', 'What did you see on the screen?', 'Who do you see on the screen?', 'Whom do you see on the screen?'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( اين ولد النبي محمد صلى الله عليه وسلم ؟):', 'options' => ['When was the prophet Muhammad (peace be upon him) born?', 'Where was the prophet Muhammad (peace be upon him) born?', 'Where were the prophet Muhammad (peace be upon him) born?', 'who has the prophet Muhammad (peace be upon him) born?'], 'correct' => 1],
        ['text' => '(you - game - Which - did - ? - pick) اختر الترتيب الصحيح للسؤال:', 'options' => ['Which game did you pick?', 'Which did you pick game?', 'Which game you pick did?', 'Did Which game you pick?'], 'correct' => 0],
        ['text' => '(favorite - your - show - Which – is- ?) اختر الترتيب الصحيح للسؤال:', 'options' => ['Which show your is favorite?', 'Which is your show favorite ?', 'Which show is your favorite?', 'Which favorite show is your?'], 'correct' => 2],
        ['text' => '(?- pyramids - are - Where - the) اختر الترتيب الصحيح للسؤال:', 'options' => ['Where are the pyramids?', 'Where the are pyramids?', 'Where pyramids are the?', 'Where? are the pyramids'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة ضمائر الاستفهام (Interrogative Pronouns Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1109.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
