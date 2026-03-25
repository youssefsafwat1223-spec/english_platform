<?php

/**
 * Script to import questions for Lesson ID 1013 (Past Simple Grammar)
 * php import_lesson_1013_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1013;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1013 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى الماضي البسيط في اللغة الإنجليزية؟', 'options' => ['Present simple', 'Past simple', 'Future simple', 'Past continuous'], 'correct' => 1],
        ['text' => 'الماضي البسيط يعبر عن جميع ما يلي ما عدا:', 'options' => ['شيء حدث في الماضي', 'حقائق في الماضي', 'شيء حدث في الماضي و ما زال مستمر', 'لا شيء'], 'correct' => 2],
        ['text' => 'سمي الماضي البسيط بهذا الاسم لانه يكون بابسط حالاته (v+ing)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت (بدون be):', 'options' => ['Subject + v2 + object', 'Subject + v3 + object', 'Subject + v2 + ing', 'Subject + is + v2'], 'correct' => 0],
        ['text' => 'الكينونة في صيغة الماضي البسيط (v2) هي:', 'options' => ['Is \ am \ are', 'Was \ were', 'Be', 'Been'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة لـ I was هي (أنا أكون)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة لـ (هم كانوا) هي:', 'options' => ['They was', 'They were', 'They are', 'They is'], 'correct' => 1],
        ['text' => '(Fatima __ her phone screen yesterday) اختر الفعل:', 'options' => ['Cleans', 'Cleaned', 'Cleaning', 'Has cleaned'], 'correct' => 1],
        ['text' => 'عما تعبر جملة Fatima cleaned her phone...؟', 'options' => ['حقيقة', 'مستقبل', 'شيء حدث في الماضي وانتهى', 'يستمر الان'], 'correct' => 2],
        ['text' => 'ما نوع الفعل Cleaned في الجملة السابقة؟', 'options' => ['Modal', 'Transitive', 'Intransitive', 'Irregular'], 'correct' => 1],
        ['text' => 'لماذا وضعنا الفعل في التصريف الثاني؟', 'options' => ['فعل منتظم', 'بسبب وجود Yesterday', 'لان الفاعل مفرد', 'لا شيء'], 'correct' => 1],
        ['text' => '(Abdullah __ a new apartment last month) اختر الفعل:', 'options' => ['Buyed', 'Bought', 'Buys', 'Buy'], 'correct' => 1],
        ['text' => 'لماذا لم نضع ed للفعل saw في (I saw an old friend)؟', 'options' => ['Modal', 'Intransitive', 'Irregular verb', 'لا شيء'], 'correct' => 2],
        ['text' => 'كيف عرفت بأن الجملة (Abdullah bought...) ماضي بسيط؟', 'options' => ['Month', 'وجود كلمة Last (الفائت)', 'A new', 'ليست ماضي بسيط'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لجملة الماضي البسيط التي يكون be فعل أساسي بها:', 'options' => ['Subject + v2', 'Subject + v3', 'Subject + v2+ing', 'Subject + was \ were + object'], 'correct' => 3],
        ['text' => 'عندما نتحدث عن حقائق في الماضي فإن الفعل يكون:', 'options' => ['V1', 'V3', 'Be في التصريف الثاني (Was/Were)', 'have'], 'correct' => 2],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ Was:', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ Were:', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 1],
        ['text' => '(Saleh _ sick yesterday) اختر الفعل:', 'options' => ['Is', 'Be', 'Was', 'Has'], 'correct' => 2],
        ['text' => 'عما تعبر جملة Saleh was sick yesterday؟', 'options' => ['حقيقة في الماضي', 'مستقبل', 'حدث في الماضي', 'يستمر الان'], 'correct' => 0],
        ['text' => 'كيف أعرف بأن Was/Were فعل أساسي في الجملة؟', 'options' => ['اذا أتوا في الماضي', 'اذا أتى بعدهم صفة', 'اذا أتى بعدهم مفعول', 'إذا لم يكن في الجملة فعل غيره'], 'correct' => 3],
        ['text' => 'ما الترجمة الصحيحة لـ Was/Were في الماضي البسيط؟', 'options' => ['كان \ كانوا \ كانت', 'قد', 'صارله', 'أكون'], 'correct' => 0],
        ['text' => '(My last visit to Jeddah __ wonderful) اختر الفعل:', 'options' => ['Is', 'Are', 'Was', 'Were'], 'correct' => 2],
        ['text' => 'ترجمة: My last visit to Jeddah was wonderful:', 'options' => ['زيارتي الأخيرة كانت رائعة.', 'ستكون رائعة', 'تكون رائعة', 'لا شيء'], 'correct' => 0],
        ['text' => 'لا بد للتصريف الثاني (V2) أن ينتهي بـ ed في جميع الأحوال؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لنفي الماضي البسيط:', 'options' => ['Subject + did +not+ v1', 'Subject + do+ not + v1', 'Subject + does + not', 'Subject + did +not+ v2'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح لنفي الماضي البسيط إذا كان الفعل الأساسي هو Be:', 'options' => ['Subject + isn’t \ aren’t', 'Subject + wasn’t \ weren’t + object', 'Subject + wasn’t \ weren’t + v1', 'Subject + didn’t + v1'], 'correct' => 1],
        ['text' => 'لا بد للفعل الأساسي بعد Did أن يكون في صيغة ماضي (V2)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(She __ the store yesterday) اكمل بنفي مناسب:', 'options' => ['Doesn’t go', 'Wasn’t go', 'Didn’t go', 'Didn’t went'], 'correct' => 2],
        ['text' => 'هل تحتوي جملة (She didn’t go the store yesterday) على حال؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما هو الحال في الجملة السابقة؟', 'options' => ['Store', 'Yesterday', 'Go', 'She'], 'correct' => 1],
        ['text' => 'ما نوع الحال في الجملة السابقة؟', 'options' => ['Manner', 'Degree', 'Adverb of time', 'Comment'], 'correct' => 2],
        ['text' => '(They watched a movie...) اختر النفي الصحيح:', 'options' => ['didn’t watched', 'They didn’t watch a movie last night.', 'wasn’t watched', 'wasn’t watch'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح إذا لم يكن الفعل الأساسي Be:', 'options' => ['Was \ were + subject', 'Did + subject + v1 + object ?', 'Did + subject + v2', 'Was \ were + v1'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح إذا كان الفعل الأساسي Be:', 'options' => ['Was \ were + subject + object?', 'Did + subject + v1', 'Did + subject + v2', 'Was \ were + v1'], 'correct' => 0],
        ['text' => '(Othman went to the zoo...) اختر تكوين السؤال الصحيح:', 'options' => ['Did Abdullah go to the zoo yesterday?', 'Did Abdullah went...?', 'was Abdullah go...?', 'was Abdullah went...?'], 'correct' => 0],
        ['text' => '(Did Abdullah go to the zoo yesterday?) الإجابة الصحيحة:', 'options' => ['Yes, he did.', 'Yes, he didn’t.', 'No, she didn’t.', 'No, he did.'], 'correct' => 0],
        ['text' => '(My dad was in Sharurah last night) الترجمة الصحيحة:', 'options' => ['قد كان', 'ابي كان في شرورة الليلة الفائتة.', 'صارله', 'يكون'], 'correct' => 1],
        ['text' => '(My dad was in Sharurah...) اختر السؤال المناسب:', 'options' => ['Were your dad...', 'Was your dad in Sharurah last night?', 'Did your dad...', 'Does your dad...'], 'correct' => 1],
        ['text' => '(My dad was in Jeddah last night) اختر الإجابة الصحيحة:', 'options' => ['Yes, he wasn’t.', 'Yes, she was.', 'No, he wasn’t.', 'No, he was.'], 'correct' => 2],
        ['text' => '(Yes he did) اختر علامات الترقيم المناسبة:', 'options' => ['Yes, he did', 'Yes, he did.', 'Yes he, did.', 'Yes he did.'], 'correct' => 1],
        ['text' => 'ماذا نضيف في نهاية الفعل المنتظم لعمل V2/V3؟', 'options' => ['Ed', 'Er', 'Est', 'None'], 'correct' => 0],
        ['text' => 'اذا انتهى الفعل بـ y وكان قبلها حرف ساكن:', 'options' => ['نضيف ed فقط', 'نحول y إلى i ثم نضيف ed', 'نضيف d فقط', 'لا شيء'], 'correct' => 1],
        ['text' => 'الفعل غير المنتظم لا يمكننا إضافة ed له؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن الماضي البسيط:', 'options' => ['have lived', 'have been living', 'am living', 'None'], 'correct' => 3],
        ['text' => 'حول (I go to work) إلى ماضي:', 'options' => ['have gone', 'have been going', 'I went to work.', 'None'], 'correct' => 2],
        ['text' => 'الماضي البسيط من Break هو:', 'options' => ['Broken', 'Broke', 'Breaded', 'Breaked'], 'correct' => 1],
        ['text' => 'الماضي البسيط من Put هو:', 'options' => ['Put', 'Puted', 'Putted', 'None'], 'correct' => 0],
        ['text' => 'الماضي البسيط هو حدث في الماضي ولم يعد مستمراً؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(She buys pizza for lunch) الترجمة الصحيحة هي:', 'options' => ['قد اشترت', 'كانت تشتري', 'هي اشترت بيتزا للغداء', 'هي تشتري بيتزا للغداء'], 'correct' => 3], // Present simple -> تشتري
        ['text' => 'ما زمن جملة (She buys pizza for lunch)؟', 'options' => ['ماضي بسيط', 'مضارع بسيط', 'ماضي تام', 'مضارع مستمر'], 'correct' => 1],
        ['text' => 'حول (She buys pizza) إلى ماضي بسيط:', 'options' => ['is buying', 'She bought pizza for lunch.', 'has been buying', 'was buying'], 'correct' => 1],
        ['text' => '(They arrived yesterday) الترجمة الصحيحة هي:', 'options' => ['هم وصلوا أمس', 'قد وصلوا', 'صارلهم', 'كانوا يصلون'], 'correct' => 0],
        ['text' => 'ترجمة: (نحن لعبنا الأسبوع الماضي):', 'options' => ['We’ve played', 'We played last week.', 'We plaied', 'were playing'], 'correct' => 1],
        ['text' => 'الفعل المساعد في الماضي البسيط هو:', 'options' => ['Do', 'Does', 'Did', 'Has'], 'correct' => 2],
        ['text' => 'للتأكيد على الجملة نضع الفعل المساعد بين:', 'options' => ['الفاعل والفعل', 'الفاعل والمفعول', 'الفاعل والتكملة', 'الفعل والمفعول'], 'correct' => 0],
        ['text' => 'بعد الفعل المساعد للتاكيد (Did) نضع الفعل الأساسي في حالة:', 'options' => ['Ed', 'فعل مجرد (v1)', 'ied', 'ing'], 'correct' => 1],
        ['text' => 'ترجمة: (أنا بالفعل اتصلت بهم):', 'options' => ['I called', 'I did called', 'I did call them', 'I do call'], 'correct' => 2],
        ['text' => '(I did __ my lunch) اختر الإجابة:', 'options' => ['eat', 'ate', 'eaten', 'eated'], 'correct' => 0],
        ['text' => 'الماضي البسيط هو الزمن الوحيد الذي قد لا نذكر فيه الوقت إذا كان معروفا؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ترجمة: (أنا لم أسافر الشهر الماضي):', 'options' => ['was not', 'didn’t travelled', 'I didn’t travel last month.', 'am not travelled'], 'correct' => 2],
        ['text' => '(He __ not __ there) اختر الكلمات المناسبة:', 'options' => ['Does \ go', 'Did \ go', 'Has \ go', 'Did \ went'], 'correct' => 1],
        ['text' => '(Salem didn’t go to school) الترجمة هي:', 'options' => ['ما قد ذهب', 'سالم لم يذهب إلى المدرسة.', 'ذهب إلى المدرسة', 'لا شيء'], 'correct' => 1],
        ['text' => 'في حقيقة أو وصف نستخدم Was/Were كفعل أساسي؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(Did Mohammed pass the exam?) الإجابة الصحيحة:', 'options' => ['Mohammed did', 'Yes, he did.', 'Yes he did (no code check)', 'No , she didn’t.'], 'correct' => 1],
        ['text' => '(I __ sick) اختر الفعل:', 'options' => ['Did', 'Were', 'Was', 'Are'], 'correct' => 2],
        ['text' => '(They __ in a hurry) اختر الفعل:', 'options' => ['Were not', 'Was not', 'Did not', 'Has not'], 'correct' => 0],
        ['text' => 'ترجمة: (خالد كان طالباً):', 'options' => ['did a student', 'Khalid was a student.', 'was student (missing a)', 'is a student'], 'correct' => 1],
        ['text' => '(__ was young) اختر الفاعل:', 'options' => ['Children', 'Fatima', 'Grandmothers', 'None'], 'correct' => 1],
        ['text' => '(__ were noisy) اختر الفاعل:', 'options' => ['My birds', 'My bird', 'My television', 'None'], 'correct' => 0],
        ['text' => '(We __ the flight yesterday) اختر الفعل المناسب:', 'options' => ['Were', 'Missed', 'Was', 'are'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي البسيط (Past Simple Grammar)',
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
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'option_a' => $qData['options'][0] ?? null,
            'option_b' => $qData['options'][1] ?? null,
            'option_c' => $qData['options'][2] ?? null,
            'option_d' => $qData['options'][3] ?? null,
            'correct_answer' => $letterMap[$qData['correct']] ?? 'A',
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1013.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
