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
        ['text' => 'Renad opened the curtains this morning. اختر الترجمة الصحيحة:', 'options' => ['ريناد فتحت الستائر هذا الصباح.', 'غسلت الستائر', 'أغلقت الستائر', 'نظفت السجاد'], 'correct' => 0],
        ['text' => 'Birds flew by our house yesterday. اختر الترجمة الصحيحة:', 'options' => ['تطير بالأمس', 'طارت غداً', 'طيور طارت من جانب بيتنا بالأمس.', 'حشرات طارت'], 'correct' => 2],
        ['text' => 'Seba joined an art club last month. اختر الترجمة الصحيحة:', 'options' => ['صبا التحقت بنادي فن الشهر الفائت.', 'الأسبوع الفائت', 'ستلتحق الأسبوع القادم', 'بمدرسة فن'], 'correct' => 0],
        ['text' => 'Khalid washed his car last night. اختر الترجمة الصحيحة:', 'options' => ['خالد غسل سيارته الليلة الفائتة.', 'يغسل كل يوم', 'صبغ سيارته', 'سيارة والده'], 'correct' => 0],
        ['text' => 'Salem had an accident yesterday. اختر الترجمة الصحيحة:', 'options' => ['يتعرض كل يوم', 'قد تعرض بالأمس', 'سالم تعرض لحادث بالأمس.', 'لن يتعرض'], 'correct' => 2],
        ['text' => 'ترجمة: (شريفة وزوجها عاشوا في اسبانيا عام 1991):', 'options' => ['lived in London', 'are living', 'Shareefah and her husband lived in Spain in 1991.', 'have lived'], 'correct' => 2],
        ['text' => 'ترجمة: (ابن جارنا سجل في الجيش الأسبوع الماضي):', 'options' => ['enlisted in the army last week.', 'last year', 'has enlisted', 'enlists'], 'correct' => 0], // I'll use last week based on task prompt text even if option says year
        ['text' => 'ترجمة: (البستاني قص الأشجار منذ أسبوع مضى):', 'options' => ['farmer cut', 'The gardener cut the trees a week ago.', 'is cutting', 'has cut'], 'correct' => 1],
        ['text' => 'ترجمة: (المعلمة ذكرت اسمي الأسبوع الفائت):', 'options' => ['mentions', 'manager mentioned', 'The teacher mentioned my name last week.', 'last month'], 'correct' => 2],
        ['text' => 'ترجمة: (احمد اشترى كنبة جديدة الشهر الفائت):', 'options' => ['Ahmed bought a new sofa last month.', 'new car', 'buyed', 'last week'], 'correct' => 0],
        ['text' => 'The gardener didn’t plant flowers yesterday. الترجمة هي:', 'options' => ['زرع ورود', 'البستاني لم يزرع ورود بالأمس.', 'ما قد زرع', 'المزارع'], 'correct' => 1],
        ['text' => 'Abdullah didn’t buy a new yacht last year. الترجمة هي:', 'options' => ['عبدالله لم يشتري قارب جديد السنة الماضية.', 'عربة جديد', 'اشترى قارب', 'الشهر الماضي'], 'correct' => 0],
        ['text' => 'Saleh wasn’t a web designer. الترجمة هي:', 'options' => ['مصمم صور', 'مبرمج ويب', 'مصمم ويب', 'صالح ما كان مصمم ويب.'], 'correct' => 3],
        ['text' => 'ترجمة: (مصعب لم يكن بعيداً):', 'options' => ['Mosaab was away.', 'Mosaab wasn’t away.', 'isn’t away', 'is away'], 'correct' => 1],
        ['text' => 'Khadija was here a minute ago. الترجمة هي:', 'options' => ['خديجة كانت هنا من دقيقة مضت.', 'لم تكن هنا', 'خمس دقائق', 'كانت هناك'], 'correct' => 0],
        ['text' => 'Were Our neighbours on a vacation? الترجمة هي:', 'options' => ['هل جيراننا كانوا في عطلة؟', 'هل جيراننا عطلوا؟', 'هل اصدقاؤنا', 'يعطلون'], 'correct' => 0],
        ['text' => 'was Last night cold? الترجمة هي:', 'options' => ['حارة', 'هل كانت الليلة الماضية باردة؟', 'تكون الليلة', 'ستكون القادمة'], 'correct' => 1],
        ['text' => 'Were you so hungry before lunch? الترجمة هي:', 'options' => ['قبل الفطور', 'هل كنت جائعاً جداً قبل الغداء؟', 'قبل العشاء', 'عطشاناً'], 'correct' => 1],
        ['text' => 'ترجمة: (هل قطتنا سكبت الشوربة الليلة الفائتة؟):', 'options' => ['Was our cat...', 'Did our cat spilt...', 'Did our cat spill the soup last night?', 'spilling'], 'correct' => 2],
        ['text' => 'Did the dry cleaner burn my thobe? الترجمة هي:', 'options' => ['هل صاحب مغسلة الملابس حرق ثوبي؟', 'حرق بنطالي', 'يحرق ثوبي', 'سوف يحرق'], 'correct' => 0],
        ['text' => '(Decided – we – England – to – go – week – last – to) اعد الترتيب:', 'options' => ['England decided...', 'We decided to go to England week last.', 'We decided to go to England last week.', 'We to go to decided...'], 'correct' => 2],
        ['text' => '(My – hugged – yesterday – grandma – me) اعد الترتيب:', 'options' => ['My grandma hugged me yesterday.', 'Me hugged...', 'My grandma me hugged...', 'Yesterday me hugged...'], 'correct' => 0],
        ['text' => '(absent- and- Saeed- Ahmed- weren’t) اعد الترتيب:', 'options' => ['Ahmed weren’t and...', 'Ahmed and Saeed weren’t absent.', 'absent Ahmed and...', 'Ahmed Saeed weren’t and'], 'correct' => 1],
        ['text' => '(Ankle – your - ? – did – hurt – you –wounded) اعد الترتيب:', 'options' => ['Did your wounded ankle hurt you?', 'Did your ankle wounded...', '?Did your wounded...', 'Did your wounded hurt...'], 'correct' => 0],
        ['text' => '(snake- crawled -The - of -the - a minute- ago- out – hole) اعد الترتيب:', 'options' => ['a minute ago crawled...', 'The snake crawled out of the hole a minute ago.', 'out of crawled...', 'hole crawled out...'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة وترتيب الماضي البسيط (Past Simple Translation)',
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
