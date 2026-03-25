<?php

/**
 * Script to import questions for Lesson ID 1041 (Past Perfect Continuous Translation)
 * php import_lesson_1041_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1041;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1041 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I had been studying for three hours when he called. ترجمة:', 'options' => ['درست لثلاث ساعات', 'أنا قد كنت أدرس لمدة ثلاث ساعات عندما اتصل.', 'سوف ادرس', 'أدرس الان'], 'correct' => 1],
        ['text' => 'They had been playing football since morning. ترجمة:', 'options' => ['لعبوا الكرة', 'هم قد كانوا يلعبون كرة القدم منذ الصباح.', 'سوف يلعبون', 'يلعبون دائماً'], 'correct' => 1],
        ['text' => 'She had been working there for ten years. ترجمة:', 'options' => ['عملت هناك', 'هي قد كانت تعمل هناك لمدة عشر سنوات.', 'سوف تعمل', 'تعمل الان'], 'correct' => 1],
        ['text' => 'It had been snowing all night. ترجمة:', 'options' => ['امطرت ثلج', 'انها قد كانت تثلج طوال الليل.', 'سوف تثلج', 'تثلج الان'], 'correct' => 1],
        ['text' => 'We had been waiting for the bus. ترجمة:', 'options' => ['انتظرنا الباص', 'نحن قد كنا ننتظر الحافلة.', 'سوف ننتظر', 'ننتظر دائماً'], 'correct' => 1],
        ['text' => 'ترجمة: (هو لم يكن قد كان يعمل في تلك الشركة لفترة طويلة):', 'options' => ['He had not been working for that company for long.', 'not work', 'not worked', 'none'], 'correct' => 0],
        ['text' => 'ترجمة: (نحن لم نكن قد كنا نعيش في هذا البيت لسنوات):', 'options' => ['not live', 'We had not been living in this house for years.', 'not living', 'none'], 'correct' => 1],
        ['text' => 'ترجمة: (الطفل لم يكن قد كان يبكي طوال الوقت):', 'options' => ['baby not cry', 'The baby had not been crying all the time.', 'not cried', 'none'], 'correct' => 1],
        ['text' => 'ترجمة: (أنا لم أكن قد كنت أتوقع هذا الخبر):', 'options' => ['I had not been expecting this news.', 'not expect', 'not expected', 'none'], 'correct' => 0],
        ['text' => 'ترجمة: (هم لم يكونوا قد كانوا يتدربون قبل المباراة):', 'options' => ['not track', 'They had not been practicing before the match.', 'not practice', 'none'], 'correct' => 1],
        ['text' => 'Had you been waitng for a long time? ترجمة:', 'options' => ['هل انتظرت طويلاً؟', 'هل كنت قد كنت تنتظر لفترة طويلة؟', 'لماذا انتظرت', 'كيف انتظرت'], 'correct' => 1],
        ['text' => 'Had she been studying English before she travelled? ترجمة:', 'options' => ['درست انجليزي', 'هل كانت قد كانت تدرس الإنجليزية قبل أن تسافر؟', 'هل درست', 'سوف تدرس'], 'correct' => 1],
        ['text' => 'Had they been living in London for long? ترجمة:', 'options' => ['هل كانوا قد كانوا يعيشون في لندن لفترة طويلة؟', 'عاشوا في لندن', 'هل سكنتم', 'سيسكنون'], 'correct' => 0],
        ['text' => 'Had he been working out at the gym? ترجمة:', 'options' => ['يتمرن في النادي', 'هل كان قد كان يتمرن في النادي؟', 'سيتمرن', 'هل يتمرن'], 'correct' => 1],
        ['text' => 'Had the phone been ringing for a while? ترجمة:', 'options' => ['هل رن الهاتف؟', 'هل كان قد كان الهاتف يرن لفترة؟', 'سيرن', 'قد يرن'], 'correct' => 1],
        ['text' => '(Before – I – met – her – she – had – been – teaching – for – years) اعد الترتيب:', 'options' => ['She had been teaching for years before I met her.', 'teaching been she had...', 'years for teaching she...', 'none'], 'correct' => 0],
        ['text' => '(They – been – had – talking – for – an – hour – when – we – arrived) اعد الترتيب:', 'options' => ['They had been talking for an hour when we arrived.', 'an hour talking been had...', 'arrived when they...', 'none'], 'correct' => 0],
        ['text' => '(Had – you – been – swimming – all – morning - ?) اعد الترتيب:', 'options' => ['You had been swimming?', 'Had you been swimming all morning?', 'all morning you been?', 'none'], 'correct' => 1],
        ['text' => '(He – been – hadn’t – feeling – well – recently) اعد الترتيب:', 'options' => ['He hadn’t been feeling well recently.', 'well he hadn’t been...', 'feeling well he hadn’t', 'none'], 'correct' => 0],
        ['text' => '(Everything – wet – was – because – it – had – been – raining) اعد الترتيب:', 'options' => ['Everything was wet because it had been raining.', 'raining because everything...', 'it had been raining because...', 'none'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة وترتيب الماضي التام المستمر (Past Perfect Continuous Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1041.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
