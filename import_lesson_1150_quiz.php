<?php

/**
 * Script to import questions for Lesson ID 1150 (Time Grammar)
 * php import_lesson_1150_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1150;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1150 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى كلمة (Time)؟', 'options' => ['العصر', 'الوقت(الزمن)', 'التاريخ', 'ليس مما ذكر'], 'correct' => 1],
        ['text' => 'للسؤال عن الوقت فاننا نستخدم صيغة السؤال:', 'options' => ['When is the time?', 'What time is it?', 'What’s the time?', 'الاجابتين ( ب + ج)'], 'correct' => 3],
        ['text' => 'اذا كانت الساعة السابعة صباحا وسألنا (What’s the time)؟ فإننا نجيب:', 'options' => ['It’s seven pm', 'It’s seven am', 'It’s seven o’clock', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'ماذا نعني بالاختصار (am) صباحا عندما نتحدث عن الوقت؟', 'options' => ['Inti meridiem', 'Ante meridiem', 'Post meridiem', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'كلمة (am) تعني ما قبل الظهيرة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ماذا نعني بالاختصار (pm) مساء عندما نتحدث عن الوقت؟', 'options' => ['Inti meridiem', 'Ante medidiem', 'Post meridiem', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'كلمة (pm) تعني ما قبل الظهيرة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'متى نبدا باستخدام الاختصار (Am) بجانب الوقت؟', 'options' => ['عندما تصبح الساعة الثانية عشر صباحا( منتصف الليل)', 'عندما تصبح الساعة الثانية عشر مساء( منتصف النهار)', 'عند شروق الشمس', 'عند غروب الشمس'], 'correct' => 0],
        ['text' => 'متى نستخدم الاختصار (Pm)؟', 'options' => ['عند حلول الساعة الثانية عشر صباحا', 'من الساعة الثانية عشر ظهرا حتى 11:59 مساء', 'عند ظهور القمر', 'لا شيء مما سبق'], 'correct' => 1],
        
        [
            'text' => 'صل معاني الكلمات التالية التي تدلل على الزمن:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'year', 'right' => 'سنة'],
                ['left' => 'month', 'right' => 'شهر'],
                ['left' => 'week', 'right' => 'أسبوع'],
                ['left' => 'day', 'right' => 'يوم'],
                ['left' => 'moment', 'right' => 'لحظة'],
                ['left' => 'hour', 'right' => 'ساعة'],
                ['left' => 'second', 'right' => 'ثانية'],
                ['left' => 'minute', 'right' => 'دقيقة'],
            ]
        ],

        [
            'text' => 'صل ما بين أوقات اليوم وفتراتها (رتبها من اول فترة في النهار):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => '1 (First)', 'right' => 'Dawn (الفجر)'],
                ['left' => '2 (Second)', 'right' => 'Morning (الصباح)'],
                ['left' => '3 (Third)', 'right' => 'Noon (الظهر)'],
                ['left' => '4 (Fourth)', 'right' => 'Afternoon (بعد الظهيرة)'],
                ['left' => '5 (Fifth)', 'right' => 'Dusk (الغروب)'],
                ['left' => '6 (Sixth)', 'right' => 'Evening (المساء)'],
                ['left' => '7 (Seventh)', 'right' => 'Night (ليل)'],
                ['left' => '8 (Eighth)', 'right' => 'Midnight (منتصف الليل)'],
            ]
        ],

        ['text' => 'اختر الصحيح من الأجوبة التالية ( لأنواع الساعات):', 'options' => ['Golden o’clock / digital o’clock', 'Digital o’clock / analog o’clock', 'big o’clock / Alarm o’clock'], 'correct' => 1],
        ['text' => 'الفرق بين (o’clock) و (Watch) هو أن:', 'options' => ['o’clock هي ساعة يد / Watch هي ساعة حائط', 'o’clock هي ساعة حائط / Watch هي ساعة يد', 'o’clock هي ساعة قديمة / Watch هي ساعة جديدة', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'نسمي عقرب الساعة بـ:', 'options' => ['Leg', 'Hand', 'Foot', 'Finger'], 'correct' => 1],
        ['text' => 'عند قراءة الساعة بالطريقة الرسمية (Past و to) فإننا دائما ننطق الساعة أولا ثم الدقائق؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة الرسمية): 9:35', 'options' => ['nine o’clock', 'nine thirty five', 'twenty five to ten', 'seven forty five'], 'correct' => 2],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة الحديثة): 9:35', 'options' => ['nine o’clock', 'nine thirty five', 'twenty five to ten', 'seven forty five'], 'correct' => 1],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة العسكرية): 09:35 am', 'options' => ['It’s nine o’clock', 'It’s nine thirty five', 'It’s twenty five to ten', 'It’s zero nine thirty five in the morning'], 'correct' => 3],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة العسكرية): 09:35 pm', 'options' => ['nine o’clock', 'nine thirty five', 'twenty one thirty five', 'nine thirty five in the morning'], 'correct' => 2],
        ['text' => 'نستخدم حرف الجر ------ للتعبير عن الوقت المحدد مثل (7:00).', 'options' => ['In', 'On', 'At', 'For'], 'correct' => 2],
        ['text' => 'عند قراءة الساعة (بالطريقة الحديثة) فاننا نقرا الساعة أولا ثم الدقيقة.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['text' => 'في الطريقة الحديثة نقرا الصفر بـ (Oh - اوه) فقط للدقائق.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'الشيء الوحيد الذي يجعلنا نحدد الوقت في (الطريقة العسكرية) هو؟', 'options' => ['ما اذا كانت الساعة في الصباح او المساء (Am / pm)', 'اذا كانت تستخدم مع الطريقة القديمة', 'اذا كانت تستخدم مع الطريقة الحديثة', 'لا يوجد فرق في الأساس'], 'correct' => 0],
        ['text' => 'للإجابة على سؤال (What time is it؟) فإننا نبدأ الجواب بـ:', 'options' => ['There is', 'It’s', 'It is', 'د – ب وج معا'], 'correct' => 3],
        ['text' => 'كيف نكتب الساعة السابعة (صباحا) بالتوقيت العسكري؟', 'options' => ['7000', '0700', '0070', 'لاشي مما سبق'], 'correct' => 1],
        ['text' => 'كيف نكتب الساعة العاشرة (مساء) بالتوقيت العسكري؟', 'options' => ['1000', '0100', '2200', '1010'], 'correct' => 2],
        ['text' => 'حول الوقت من الطريقة العسكرية الى العادية (0207):', 'options' => ['2:00 am', '2:07 am', '2:00 pm', '2:07 pm'], 'correct' => 1],
        ['text' => 'حول الوقت من الطريقة العسكرية الى العادية (1500):', 'options' => ['2:00am', '3:00am', '3:00pm', '2:00pm'], 'correct' => 2],
        ['text' => 'كيف نقرأ الساعة (8:17 am) بالطريقة الحديثة؟', 'options' => ['Eight seventeen', 'eight seventeen', 'Seventeen eight', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'كيف نقرأ الساعة (08:17 am) بالطريقة العسكرية؟', 'options' => ['Eight seventeen', 'Zero eight seventeen', 'Seventeen past eight', 'Twenty hundred'], 'correct' => 1],
        ['text' => 'كيف نقرا الساعة (01:00 am)؟', 'options' => ['One in the morning', 'One at midnight', 'One in the afternoon', 'Two o’clock'], 'correct' => 0],
        ['text' => 'الساعة التي تستخدم للتنبيه تسمى بـ:', 'options' => ['Military o’clock', 'Alarm o’clock', 'Golden o’clock', 'Analog o’clock'], 'correct' => 1],
        ['text' => 'كيف تقرأ الساعة (11:09 pm) بالطريقة القديمة؟', 'options' => ['Nine past eleven', 'Eleven oh nine', 'Twenty three oh nine', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'كيف تقرأ الساعة (11:09 pm) بالطريقة العسكرية؟', 'options' => ['Nine past eleven', 'Eleven oh nine', 'Twenty three zero nine', 'جميع ما سبق'], 'correct' => 2],
        ['text' => 'كيف تقرأ الساعة (11:09 pm) بالطريقة الحديثة؟', 'options' => ['Nine past eleven', 'Eleven oh nine', 'Twenty three oh nine', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'اختر من الأوقات التالية الوقت الذي نستطيع استخدام معه (oh - اوه) بدلا من الصفر:', 'options' => ['0915', '0102', '1010', '1235'], 'correct' => 1],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة الرسمية): 4:15 م', 'options' => ['four o’clock', 'fifteen past four', 'fifteen to four', 'sixteen fifteen'], 'correct' => 1],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة الحديثة): 4:15 م', 'options' => ['four fifteen', 'fifteen past four', 'fifteen to four', 'sixteen fifteen'], 'correct' => 0],
        ['text' => 'انظر الى الساعة وقم بقراءتها (بالطريقة العسكرية): 4:15 م', 'options' => ['four o’clock', 'fifteen past four', 'fifteen to four', 'sixteen fifteen'], 'correct' => 3],
        
        ['text' => 'أي نوع من أنواع الساعات في الصورة (1)؟', 'options' => ['Alarm o’clock', 'Digital o’clock', 'Analog o’clock', 'Analog watch'], 'correct' => 0],
        ['text' => 'أي نوع من أنواع الساعات في الصورة (2)؟', 'options' => ['Alarm o’clock', 'Digital o’clock', 'Analog o’clock', 'Analog watch'], 'correct' => 1],
        ['text' => 'أي نوع من أنواع الساعات في الصورة (3)؟', 'options' => ['Alarm o’clock', 'Digital o’clock', 'Analog o’clock', 'Analog watch'], 'correct' => 2],

        ['text' => 'الساعة (6:15) تلفظ بالطريقة التقليدية (Fifteen past six).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'القراءة الصحيحة للساعة (6:15) بالطريقة التقليدية هو:', 'options' => ['Fifteen past six', 'Quarter past six', 'Fifteen to six', 'Quarter to six'], 'correct' => 1],
        ['text' => 'الساعة (7:30) تلفظ بالطريقة التقليدية (half past seven).', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['text' => 'الطريقة الصحيحة لقراءة الساعة (7:30) بالطريقة الحديثة:', 'options' => ['Half past seven', 'Seven thirty', 'Thirty seven', 'Half to seven'], 'correct' => 1],
        ['text' => 'نستخدم (to) عندما نريد ان:', 'options' => ['ننطق الساعة السابقة', 'ننطق الساعة التالية', 'ننطق الساعة الحالية', 'لا نستخدمها أصلا'], 'correct' => 1],
        ['text' => 'كيف تقرأ الساعة (6:25) بالطريقة التقليدية؟', 'options' => ['Six twenty five', 'Twenty five past six', 'Twenty five six', 'Twenty five to six'], 'correct' => 1],
        ['text' => 'عندما نريد ان نقول بان الساعة الرابعة الا عشرة فإننا نقول:', 'options' => ['Ten to four', 'Ten past four', 'Half past four', 'Quarter to four'], 'correct' => 0],
        
        [
            'text' => 'صل كل توقيت بلفظه الصحيح (بالطريقة التقليدية):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => '3:30', 'right' => 'Half past three'],
                ['left' => '3:25', 'right' => 'Twenty five past three'],
                ['left' => '3:45', 'right' => 'A quarter to four'],
                ['left' => '3:15', 'right' => 'A quarter past three'],
                ['left' => '3:35', 'right' => 'Twenty five to four'],
                ['left' => '3:00', 'right' => 'Three o\'clock'],
                ['left' => '3:07', 'right' => 'Seven minutes past three'],
            ]
        ],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الوقت (Time Grammar)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 60,
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

        if ($props['question_type'] === 'drag_drop') {
            $props['matching_pairs'] = $qData['matching_pairs'];
            $props['correct_answer'] = 'A';
        } else {
            $props['option_a'] = $qData['options'][0] ?? null;
            $props['option_b'] = $qData['options'][1] ?? null;
            $props['option_c'] = $qData['options'][2] ?? null;
            $props['option_d'] = $qData['options'][3] ?? null;
            $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1150.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
