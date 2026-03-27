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
        ['text' => 'Abdullah had been walking three miles a day before he broke his leg. اختر الترجمة الصحيحة للجملة:', 'options' => ['عبدالله قد كان يجري ثلاثة اميال في يوميا قبل ان كسر رجله.', 'عبدالله قد كان يمشي ثلاثة اميال في يوميا قبل ان كسر رجله.', 'عبدالله مشي ثلاثة اميال في يوميا قبل ان كسر رجله.', 'عبدالله يمشي ثلاثة اميال في يوميا قبل ان كسر رجله.'], 'correct' => 1],
        ['text' => 'The program that was terminated had been working well since 1945. اختر الترجمة الصحيحة للجملة:', 'options' => ['البرنامج الذي تم انهاؤه قد كان يعمل بشكل جيد منذ عام 1945.', 'البرنامج الذي تم انهاؤه قد كان يعمل بشكل جيد لمدة عام 1945.', 'البرنامج الذي قد تم انهاؤه كان يعمل بشكل جيد منذ عام 1945.', 'البرنامج الذي تم انهاؤه قد كان يعمل بشكل سيء منذ عام 1945.'], 'correct' => 0],
        ['text' => 'They had been talking for over an hour before Jabir arrived. اختر الترجمة الصحيحة للجملة:', 'options' => ['هم قد كانوا يتكلمون لمدة أكثر من ساعة قبل ان سافر جابر.', 'هم قد كانوا يتكلمون لمدة أكثر من ساعة بعد ان وصل جابر.', 'هم قد كانوا يتكلمون لمدة أكثر من ساعة قبل ان وصل جابر.', 'نحن قد كنا نتكلم لمدة أكثر من ساعة قبل ان وصل جابر.'], 'correct' => 2],
        ['text' => 'Aisha gained weight because she had been overeating. اختر الترجمة الصحيحة للجملة:', 'options' => ['عائشة اكتسبت وزن لأنها قد كانت تفرط في تناول الطعام.', 'عائشة فقدت وزن لأنها قد كانت تفرط في تناول الطعام.', 'عائشة اكتسبت وزن لأنها قد كانت لا تفرط في تناول الطعام.', 'عائشة ما اكتسبت وزن لأنها قد كانت تفرط في تناول الطعام.'], 'correct' => 0],
        ['text' => 'اختر الإجابة الصحيحة للجملة (هم قد كانوا يلعبون عندما الحكم أنهى المباراة.)', 'options' => ['They had been play when the referee ended the match.', 'They had been playing while the referee ended the match.', 'They had been playing when the referee ended the match.', 'They had been playing when the referee had ended the match.'], 'correct' => 2],
        ['text' => 'اختر الإجابة الصحيحة للجملة (احمد قد كان يعلم في الجامعة لمدة أكثر من عام قبل ان استقال.)', 'options' => ['Ahmed  had been teaching at the university for more than a year before he resigned.', 'Ahmed  had been teaching at the university for more than a month before he resigned.', 'Ahmed  had been teaching at the university for more than a year after he resigned.', 'Ahmed  had been teaching at the university for more than a year before he had resigned.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (صبا قد كانت تعمل في تلك الشركة لمدة ثلاث سنوات عندما توقفت ( الشركة) عن العمل.)', 'options' => ['Seba had been working at that company for three years while it went out of business.', 'Seba had been working at that company for three years when it had gone out of business.', 'Seba had been working at that company for three years when it went out of business.', 'Seba had been working at that company for three months when it went out of business.'], 'correct' => 2],
        ['text' => 'Had Saleh been working at the company for five years when he got the promotion? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قد كان صالح يعمل في المؤسسة لمدة خمس سنوات عندما حصل (هو) على ترقية؟', 'هل قد كان صالح يعمل في الشركة لمدة خمس سنوات عندما حصل ( هو) على ترقية؟', 'هل قد كان صالح يعمل في الشركة لمدة خمس سنوات عندما قد حصل ( هو) على ترقية؟', 'هل قد كان صالح يعمل في الشركة منذ خمس سنوات عندما حصل ( هو) على ترقية؟'], 'correct' => 1],
        ['text' => 'Had He been swimming when the small boy sank? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قد كانت (هي) تسبح عندما الولد الصغير غرق؟', 'هل قد كان (هو) يسبح عندما الولد الصغير غرق؟', 'هل قد(هو) سبح عندما الولد الصغير غرق؟', 'هل قد كان (هو) يسبح عندما الولد الكبيرغرق؟'], 'correct' => 1],
        ['text' => 'Had the engine been leaking oil when the mechanic checked it? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قد كان المحرك يسرب زيت عندما الميكانيكي تفقده؟', 'هل قد كان المحرك يغير زيت عندما الميكانيكي صلحه؟', 'هل قد كان المحرك يسرب مياه عندما الميكانيكي تفقده؟', 'هل قد كان المحرك يسرب زيت بينما الميكانيكي تفقده؟'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل قد كانوا يكتبون دعوات قبل ان وصل عبدالله؟)', 'options' => ['Had they been writing invitations before Abdullah arrived?', 'Had they been writing letters before Abdullah arrived?', 'Had they been writing invitations before Abdullah arrived.', 'Had Abdullah been writing invitations before they arrived?'], 'correct' => 0],
        ['text' => 'He hadn’t been solving math equations. اختر الترجمة الصحيحة للجملة:', 'options' => ['هو ما قد كان يحل معادلات رياضيات.', 'هم ما قد كانوا يحلوا معادلات رياضيات.', 'هو ما قد كان يحل معادلات رياضيات؟', 'هو ما قد كان يحل مسائل معادلات رياضيات.'], 'correct' => 0],
        ['text' => 'She hadn’t been listening to music before you came home. اختر الترجمة الصحيحة للجملة:', 'options' => ['هو ما قد كان يستمع الى موسيقى قبل ان (انت) أتيت البيت.', 'هي ما قد كانت تستمع الى موسيقى قبل ان(انت) أتيت البيت.', 'هي ما قد كانت تستمع الى موسيقى قبل ان قد(انت) أتيت البيت.', 'هي ما قد كانت تقرأ الى موسيقى قبل ان(انت) أتيت البيت.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة ل (سعيد ما قد كان يعيش في أمريكا لمدة 10 سنوات.)', 'options' => ['Saeed had not been living in America for 10 years.', 'Saeed had not been living in America for 10 year.', 'Saeed not had been living in America for 10 years.', 'Had Saeed been living in America for 10 years?'], 'correct' => 0],
        ['text' => 'اختر الإجابة الصحيحة للجملة (انها ما قد كانت تمطر لمدة أسبوع.)', 'options' => ['It hadn’t been snowing for a week.', 'Not it had been snowing for a week.', 'It had been not snowing for a week.', 'It hadn’t been snowing for a month.'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للجملة: (Practicing – she – been – hard – spelling bee – for –the –had.)', 'options' => ['She had been practicing hard for the spelling bee.', 'She had been for the spelling bee practicing hard.', 'The spelling bee had been practicing hard for she.', 'She had hard been practicing for the spelling bee.'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للجملة: (Had – for – been –Khalid – brainstorming – half – ?- an hour.)', 'options' => ['Khalid had been brainstorming for half an hour?', 'Khalid been brainstorming had for half an hour?', 'Had Khalid been brainstorming for half an hour?', 'Had Khalid been? brainstorming for half an hour'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة الماضي التام المستمر (Past Perfect Continuous Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1041.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
