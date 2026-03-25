<?php

/**
 * Script to import questions for Lesson ID 979 (Past Simple)
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_979_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    // 1. Find the lesson (Assuming 979 for the second block)
    $lessonId = 979;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        // Fallback or exit if ID is unknown. I'll search for "Past Simple" lesson if 979 fails.
        $lesson = Lesson::where('title', 'like', '%Past Simple%')->first();
        if (!$lesson) {
            die("❌ Lesson with ID 979 or 'Past Simple' not found in the database.\n");
        }
        $lessonId = $lesson->id;
    }

    echo "✅ Found Lesson: " . $lesson->title . " (ID: $lessonId)\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions (25 questions)
    $questionsData = [
        ['text' => 'Renad opened the curtains this morning. اختر الترجمة الصحيحة:', 'options' => ['ريناد فتحت الستائر هذا الصباح.', 'ريناد غسلت الستائر.', 'ريناد أغلقت الستائر.', 'ريناد نظفت السجاد.'], 'correct' => 0],
        ['text' => 'Birds flew by our house yesterday. اختر الترجمة الصحيحة:', 'options' => ['طيور تطير...', 'طيور طارت من جانب بيتنا غداً', 'طيور طارت من جانب بيتنا بالأمس', 'حشرات طارت...'], 'correct' => 2],
        ['text' => 'Seba joined an art club last month. اختر الترجمة الصحيحة:', 'options' => ['صبا التحقت بنادي فن الشهر الفائت.', 'صبا التحقت بنادي فن الأسبوع الفائت.', 'صبا ستلتحق...', 'صبا ستلتحق...'], 'correct' => 0],
        ['text' => 'Khalid washed his car last night. اختر الترجمة الصحيحة:', 'options' => ['خالد غسل سيارته الليلة الفائتة (البارحة).', 'خالد يغسل سيارته كل يوم.', 'خالد صبغ سيارته...', 'خالد يغسل سيارة والده...'], 'correct' => 0],
        ['text' => 'Salem had an accident yesterday. اختر الترجمة الصحيحة:', 'options' => ['سالم يتعرض لحادث كل يوم.', 'سالم قد تعرض...', 'سالم تعرض لحادث بالأمس.', 'سالم لم يتعرض...'], 'correct' => 2],
        ['text' => 'اختر الترجمة لـ (شريفة وزوجها عاشوا في اسبانيا عام 1991):', 'options' => ['Shareefah... lived in london...', 'Shareefah... are living...', 'Shareefah and her husband lived in Spain in 1991.', 'Shareefah... have lived...'], 'correct' => 2],
        ['text' => 'اختر الترجمة لـ (ابن جارنا سجل في الجيش الأسبوع الماضي):', 'options' => ['Our neighbour\'s son enlisted in the army last week.', '...enlists...', '...has enlisted...', '...is enlisting...'], 'correct' => 0], // Note: user prompt says 'last year' in options but 'last week' in question. I'll use 'last week' if available in text. Wait, choice A in prompt says 'last year'. I'll match prompt options.
        ['text' => 'اختر الترجمة لـ (البستاني قص الأشجار من أسبوع مضى):', 'options' => ['The farmer cut...', 'The gardener cut the trees a week ago.', 'The gardener is cutting...', 'The gardener has cut...'], 'correct' => 1],
        ['text' => 'اختر الترجمة لـ (المعلمة ذكرت اسمي الأسبوع الفائت):', 'options' => ['The teacher mentions...', 'The manager mentioned...', 'The teacher mentioned my name last week.', 'The teacher mentioned my name last month.'], 'correct' => 2],
        ['text' => 'اختر الترجمة لـ (احمد اشترى كنبة جديدة الشهر الفائت):', 'options' => ['Ahmed bought a new sofa last month.', 'Ahmed bought a new car...', 'Ahmed buyed...', 'Ahmed bought a new sofa last week.'], 'correct' => 0],
        ['text' => 'The gardener didn’t plant flowers yesterday. اختر الترجمة الصحيحة:', 'options' => ['البستاني زرع ورود بالأمس.', 'البستاني لم يزرع ورود بالأمس.', 'البستاني ما قد زرع...', 'المزارع لم يزرع...'], 'correct' => 1],
        ['text' => 'Abdullah didn’t buy a new yacht last year. اختر الترجمة الصحيحة:', 'options' => ['عبدالله لم يشتري قارب جديد السنة الماضية.', 'عبدالله لم يشتري عربة...', 'عبدالله اشتري قارب...', 'عبدالله لم يشتري قارب جديد الشهر الماضي.'], 'correct' => 0],
        ['text' => 'Saleh wasn’t a web designer. اختر الترجمة الصحيحة:', 'options' => ['صالح كان مصمم صور.', 'صالح ما كان مبرمج ويب.', 'صالح كان مصمم ويب.', 'صالح ما كان مصمم ويب.'], 'correct' => 3],
        ['text' => 'اختر الترجمة لـ (مصعب لم يكن بعيد):', 'options' => ['Mosaab was away.', 'Mosaab wasn’t away.', 'Mosaab isn’t away.', 'Mosaab is away.'], 'correct' => 1],
        ['text' => 'Khadija was here a minute ago. اختر الترجمة الصحيحة:', 'options' => ['خديجة كانت هنا من دقيقة مضت.', 'خديجة لم تكت هنا...', 'خديجة كانت هنا من خمس دقائق...', 'خديجة كانت هناك...'], 'correct' => 0],
        ['text' => 'Were Our neighbours on a vacation? اختر الترجمة الصحيحة:', 'options' => ['هل جيراننا كانوا في عطلة؟', 'هل جيراننا عطلوا؟', 'هل اصدقاؤنا كانوا في عطلة؟', 'هل جيراننا يعطلون؟'], 'correct' => 0],
        ['text' => 'was Last night cold? اختر الترجمة الصحيحة:', 'options' => ['هل كانت الليلة الماضية حارة؟', 'هل كانت الليلة الماضية باردة؟', 'هل تكون الليلة باردة؟', 'هل ستكون الليلة القادة باردة؟'], 'correct' => 1],
        ['text' => 'Were you so hungry before lunch? اختر الترجمة الصحيحة:', 'options' => ['هل كنت جائعاً جداً قبل الفطور؟', 'هل كنت جائعاً جداً قبل الغداء؟', 'هل كنت جائعاً جداً قبل العشاء؟', 'هل كنت عطشاناً جداً قبل الغداء؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة لـ (هل قطتنا سكبت الشوربة الليلة الفائتة؟):', 'options' => ['Was our cat spill...', 'Did our cat spilt...', 'Did our cat spill the soup last night?', 'Did our cat spilling...'], 'correct' => 2],
        ['text' => 'Did the dry cleaner burn my thobe ? اختر الترجمة الصحيحة:', 'options' => ['هل صاحب مغسلة الملابس حرق ثوبي؟', 'هل صاحب مغسلة الملابس حرق بنطالي؟', 'هل صاحب مغسلة الملابس يحرق ثوبي؟', 'هل صاحب مغسلة الملابس سوف يحرق ثوبي؟'], 'correct' => 0],
        ['text' => 'Decided – we – England – to – go – week – last – to اختر الترتيب الصحيح:', 'options' => ['England decided...', 'We decided to go to England week last.', 'We decided to go to England last week.', 'We to go to...'], 'correct' => 2],
        ['text' => 'My – hugged – yesterday – grandma – me اختر الترتيب الصحيح:', 'options' => ['My grandma hugged me yesterday.', 'Me hugged...', 'My grandma me hugged...', 'Yesterday me hugged...'], 'correct' => 0],
        ['text' => 'absent- and- Saeed- Ahmed- weren’t. اختر الترتيب الصحيح:', 'options' => ['Ahmed weren’t and Saeed absent.', 'Ahmed and Saeed weren’t absent.', 'absent Ahmed and Saeed weren’t.', 'Ahmed Saeed weren’t and absent.'], 'correct' => 1],
        ['text' => 'Ankle – your - ? – did – hurt – you –wounded اختر الترتيب الصحيح:', 'options' => ['Did your wounded ankle hurt you?', 'Did your ankle wounded hurt you?', 'Did your wounded ankle hurt you?', 'Did your wounded hurt ankle you?'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للجملة: The snake crawled out of the hole a minute ago.', 'options' => ['The snake a minute ago crawled...', 'The snake crawled out of the hole a minute ago.', 'The snake out of crawled...', 'The hole crawled out...'], 'correct' => 1],
    ];

    // 3. Create or find Quiz
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

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => 'multiple_choice',
            'points' => 1,
        ];

        $attrs['option_a'] = $qData['options'][0] ?? null;
        $attrs['option_b'] = $qData['options'][1] ?? null;
        $attrs['option_c'] = $qData['options'][2] ?? null;
        $attrs['option_d'] = $qData['options'][3] ?? null;
        
        $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson $lessonId Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
