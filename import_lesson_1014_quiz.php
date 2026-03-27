<?php

/**
 * Script to import questions for Lesson ID 1014 (Past Simple Translation)
 * php import_lesson_1014_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1014;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1014 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Renad opened the curtains this morning اختر الترجمة الصحيحة بالجملة', 'options' => ['ريناد فتحت الستائر هذا الصباح.', 'ريناد غسلت الستائر هذا الصباح.', 'ريناد أغلقت الستائر هذا الصباح.', 'ريناد نظفت السجاد هذا الصباح.'], 'correct' => 0],
        ['text' => 'Birds flew by our house yesterday. اختر الترجمة الصحيحة للجملة', 'options' => ['طيور تطير من جانب بيتنا بالأمس', 'طيور طارت من جانب بيتنا غدا', 'طيور طارت من جانب بيتنا بالأمس', 'حشرات طارت من جانب بيتنا بالأمس'], 'correct' => 2],
        ['text' => 'Seba joined an art club last month. اختر الترجمة الصحيحة للجملة', 'options' => ['صبا التحقت بنادي فن الشهر الفائت.', 'صبا التحقت بنادي فن الأسبوع الفائت.', 'صبا ستلتحق بمدرسة فن الأسبوع القادم.', 'صبا ستلتحق بنادي فن الأسبوع القادم.'], 'correct' => 0],
        ['text' => 'Khalid washed his car last night. اختر الترجمة الصحيحة للجملة', 'options' => ['خالد غسل سيارته الليلة الفائتة (البارحة).', 'خالد يغسل سيارته كل يوم.', 'خالد صبغ سيارته الليلة الفائتة (البارحة).', 'خالد يغسل سيارة والده الليلة الفائتة (البارحة).'], 'correct' => 0],
        ['text' => 'Salem had an accident yesterday. اختر الترجمة الصحيحة للجملة', 'options' => ['سالم يتعرض لحادث كل يوم.', 'سالم قد تعرض لحادث بالامس.', 'سالم تعرض لحادث بالأمس.', 'سالم لم يتعرض لحادث غدا.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( شريفة و زوجها عاشوا في اسبانيا عام 1991)', 'options' => ['Shareefah and her husband lived in london in 1991.', 'Shareefah and her husband are living in Spain in 1991.', 'Shareefah and her husband lived in Spain in 1991.', 'Shareefah and her husband have lived in Spain in 1991.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( ابن جارنا سجل في الجيش الأسبوع الماضي)', 'options' => ["Our neighbour's son enlisted in the army last year.", "Our neighbour's son enlists in the army last year.", "Our neighbour's son has enlisted in the army last year.", "Our neighbour's son is enlisting in the army last year."], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( البستاني -الجنايني قص الأشجار من أسبوع مضى)', 'options' => ['The farmer cut the trees a week ago.', 'The gardener cut the trees a week ago.', 'The gardener is cutting the trees a week ago.', 'The gardener has cut the trees a week ago.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( المعلمة ذكرت اسمي الأسبوع الفائت)', 'options' => ['The teacher mentions my name last week.', 'The manager mentioned my name last week.', 'The teacher mentioned my name last week.', 'The teacher mentioned my name last month.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( احمد اشترى كنبة جديدة الشهر الفائت)', 'options' => ['Ahmed bought a new sofa last month.', 'Ahmed bought a new car last month.', 'Ahmed buyed a new sofa last month.', 'Ahmed bought a new sofa last week.'], 'correct' => 0],
        ['text' => 'The gardener didn’t plant flowers yesterday. اختر الترجمة الصحيحة للجملة', 'options' => ['البستاني زرع ورود بالأمس.', 'البستاني لم يزرع ورود بالأمس.', 'البستاني ما قد زرع ورود بالأمس.', 'المزارع لم يزرع ورود بالأمس.'], 'correct' => 1],
        ['text' => 'Abdullah didn’t buy a new yacht last year. اختر الترجمة الصحيحة للجملة', 'options' => ['عبدالله لم يشتري قارب جديد السنة الماضية.', 'عبدالله لم يشتري عربة جديد السنة الماضية.', 'عبدالله اشتري قارب جديد السنة الماضية.', 'عبدالله لم يشتري قارب جديد الشهر الماضي.'], 'correct' => 0],
        ['text' => 'Saleh wasn’t a web designer. اختر الترجمة الصحيحة للجملة', 'options' => ['صالح كان مصمم صور.', 'صالح ما كان مبرمج ويب.', 'صالح كان مصمم ويب.', 'صالح ما كان مصمم ويب.'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( مصعب لم يكن بعيد)', 'options' => ['Mosaab was away.', 'Mosaab wasn’t away.', 'Mosaab isn’t away.', 'Mosaab is away.'], 'correct' => 1],
        ['text' => 'Khadija was here a minute ago. اختر الترجمة الصحيحة للجملة', 'options' => ['خديجة كانت هنا من دقيقة مضت.', 'خديجة لم تكت هنا من دقيقة مضت.', 'خديجة كانت هنا من خمس دقائق مضت.', 'خديجة كانت هناك من دقيقة مضت.'], 'correct' => 0],
        ['text' => 'Were Our neighbours on a vacation? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل جيراننا كانوا في عطلة؟', 'هل جيراننا عطلوا؟', 'هل اصدقاؤنا كانوا في عطلة؟', 'هل جيراننا يعطلون؟'], 'correct' => 0],
        ['text' => 'was Last night cold? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل كانت الليلة الماضية حارة؟', 'هل كانت الليلة الماضية باردة؟', 'هل تكون الليلة باردة؟', 'هل ستكون الليلة القادة باردة؟'], 'correct' => 1],
        ['text' => 'Were you so hungry before lunch? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل كنت جائعا جدا قبل الفطور؟', 'هل كنت جائعا جدا قبل الغداء؟', 'هل كنت جائعا جدا قبل العشاء؟', 'هل كنت عطشانا جدا قبل الغداء؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل قطتنا سكبت الشوربة الليلة الفائتة؟)', 'options' => ['Was our cat spill the soup last night?', 'Did our cat spilt the soup last night?', 'Did our cat spill the soup last night?', 'Did our cat spilling the soup last night?'], 'correct' => 2],
        ['text' => 'Did the dry cleaner burn my thobe ? اختر الترجمة الصحيحة للسؤال', 'options' => ['هل صاحب مغسلة الملابس حرق ثوبي؟', 'هل صاحب مغسلة الملابس حرق بنطالي؟', 'هل صاحب مغسلة الملابس يحرق ثوبي؟', 'هل صاحب مغسلة الملابس سوف يحرق ثوبي؟'], 'correct' => 0],
        ['text' => 'Decided – we – England – to – go – week – last – to اختر الترتيب الصحيح للجملة', 'options' => ['England decided to go to we last week.', 'We decided to go to England week last.', 'We decided to go to England last week.', 'We to go to decided England last week.'], 'correct' => 2],
        ['text' => 'My – hugged – yesterday – grandma – me اختر الترتيب الصحيح للجملة', 'options' => ['My grandma hugged me yesterday.', 'Me hugged my grandma yesterday.', 'My grandma me hugged yesterday.', 'Yesterday me hugged my grandma.'], 'correct' => 0],
        ['text' => 'absent- and- Saeed- Ahmed- weren’t. اختر الترتيب الصحيح للجملة', 'options' => ['Ahmed weren’t and Saeed absent.', 'Ahmed and Saeed weren’t absent.', 'absent Ahmed and Saeed weren’t.', 'Ahmed Saeed weren’t and absent.'], 'correct' => 1],
        ['text' => 'Ankle – your - ? – did – hurt – you –wounded اختر الترتيب الصحيح للسؤال', 'options' => ['Did your wounded ankle hurt you?', 'Did your ankle wounded hurt you?', '?Did your wounded ankle hurt you', 'Did your wounded hurt ankle you?'], 'correct' => 0],
        ['text' => 'اعد ترتيب الجملة: (snake- crawled -The - of -the - a minute- ago- out – hole)', 'options' => ['The snake a minute ago crawled out of the hole.', 'The snake crawled out of the hole a minute ago.', 'The snake out of crawled the hole a minute ago.', 'The hole crawled out of the snake a minute ago.'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة الماضي البسيط (Past Simple Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1014.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
