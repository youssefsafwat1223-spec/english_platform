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
        ['text' => 'الماضي البسيط يعبر عن جميع ما يلي ما عدا', 'options' => ['شيء حدث في الماضي', 'حقائق في الماضي', 'شيء حدث في الماضي و ما زال مستمر', '(V+ing)'], 'correct' => 2],
        ['text' => 'سمي الماضي البسيط بهذا الاسم لانه يكون بابسط حالاته وهو فاعل وفعل ومفعول به و (V+ing)', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لجملة الماضي البسيط التي (لا) يكون be فعل أساسي بها', 'options' => ['Subject + v2 + object + complement.', 'Subject + v3 + object + complement.', 'Subject + v2 + ing + object + complement.', 'Subject + is + v2 + object + complement.'], 'correct' => 0],
        ['text' => 'الكينونة في صيغة الماضي البسيط (v2) هي:', 'options' => ['Is \ am \ are', 'Was \ were', 'Be', 'Been'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة ل (I was) هي انا اكون', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة ل ( هم كانوا ) هي', 'options' => ['They was', 'They were', 'They are', 'They is'], 'correct' => 1],
        ['text' => 'Fatima __ her phone screen yesterday. اختر الفعل المناسب', 'options' => ['Cleans', 'Cleaned', 'Cleaning', 'Has cleaned'], 'correct' => 1],
        ['text' => 'تعبر الجملة (Fatima cleaned her phone screen yesterday.) عن', 'options' => ['حقيقة في الماضي', 'شيء سيحدث في المستقبل', 'شيء حدث في الماضي وانتهي', 'شيء يستمر الحدوث الان'], 'correct' => 2],
        ['text' => 'ما نوع الفعل في الجملة (Fatima cleaned her phone screen yesterday.)', 'options' => ['Modal verb', 'Transitive', 'Intransitive', 'Irregular'], 'correct' => 1],
        ['text' => 'لماذا وضعنا الفعل في التصريف الثاني في جملة (Fatima cleaned her phone screen yesterday.)', 'options' => ['لانه فعل منتظم', 'بسب وجود الظرف (Yesterday) الذي حدد الزمن في الماضي', 'لان الفاعل (فاطمة) مفرد', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'Abdullah __ a new apartment last month. اختر الفعل المناسب', 'options' => ['Buyed', 'Bought', 'Buys', 'Buy'], 'correct' => 1],
        ['text' => "لماذا لم نضع (ed) للفعل (saw) في جملة (I saw an old friend last night.)", 'options' => ['لانه فعل Modal', 'لانه فعل Intransitive', 'لانه فعل غير منتظم Irregular verb', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'كيف عرفت بان هذه الجملة زمن الماضي البسيط (Abdullah bought a new apartment last month)', 'options' => ['من كلمة Month', 'من كلمة Last والتي تعني الفائت او السابق', 'من كلمة A new', 'الجملة ليست ماضي بسيط'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لجملة الماضي البسيط التي يكون be فعل أساسي بها', 'options' => ['Subject + v2 + object + complement.', 'Subject + v3 + object + complement.', 'Subject + v2 + ing + object + complement.', 'Subject + was \ were + object + complement.'], 'correct' => 3],
        ['text' => 'غالبا عندما نتحدث عن حقائق في الماضي فان الفعل يكون', 'options' => ['V 1', 'V3', 'التصريف الثاني من Be (v2)', 'have'], 'correct' => 2],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ الفعل الأساسي (Was):', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ الفعل الأساسي (Were):', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'Saleh _ sick yesterday. اختر الفعل المناسب', 'options' => ['Is', 'Be', 'Was', 'Has'], 'correct' => 2],
        ['text' => 'تعبر الجملة (Saleh was sick yesterday.) عن', 'options' => ['حقيقة في الماضي', 'شيء سيحدث في المستقبل', 'شيء حدث في الماضي', 'شيء يستمر الحدوث الان'], 'correct' => 0],
        ['text' => 'كيف اعرف بان الفعل (Was \were) فعل أساسي في الجملة؟', 'options' => ['اذا أتوا في زمن الماضي', 'اذا اتى بعدهم صفة', 'اذا اتى بعدهم مفعول به', 'اذا لم يكن في الجملة فعل غيره'], 'correct' => 3],
        ['text' => 'ما الترجمة الصحيحة ل (Was او were) في زمن الماضي البسيط', 'options' => ['كان \ كانوا \ كانت ----- الخ', 'قد', 'صارله', 'أكون ، تكون ، يكونوا ---- الخ'], 'correct' => 0],
        ['text' => '(My last visit to Jeddah__wonderful) اختر الفعل المناسب', 'options' => ['Is', 'Are', 'Was', 'Were'], 'correct' => 2],
        ['text' => '(My last visit to Jeddah was wonderful) اختر الترجمة الصحيحة', 'options' => ['زيارتي الأخيرة لجدة كانت رائعة.', 'زيارتي الأخيرة لجدة ستكون رائعة.', 'زيارتي الاخرة لجدة تكون رائعة', 'لا شيء مما سبق.'], 'correct' => 0],
        ['text' => 'لا بد للتصريف الثاني للفعل في زمن الماضي البسيط ان ينتهي ب (Ed) في جميع الاحوال.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لنفي الماضي البسيط:', 'options' => ['Subject + did +not+ v1+ object + complement.', 'Subject + do+ not + v1+ object + complement.', 'Subject + does + not+ v1+ object + complement.', 'Subject + did +not+ v2+ object + complement.'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح لنفي الماضي البسيط اذا كان الفعل الأساسي هو (Be)', 'options' => ['Subject + isn’t \ aren’t + object \complement.', 'Subject + wasn’t \ weren’t+ object \ complement.', 'Subject + wasn’t \ weren’t+ v1+ object \ complement.', 'Subject + didn’t + v1 + object \ complement.'], 'correct' => 1],
        ['text' => 'لا بد للفعل الأساسي في الجملة الذي يأتي بعد الفعل المساعد( Did) ان يكون في صيغة ماضي بسيط (v2) مهما كان حال؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'She __ __ the store yesterday. اكمل بفعل مناسب', 'options' => ['Doesn’t go', 'Wasn’t go', 'Didn’t go', 'Didn’t went'], 'correct' => 2],
        ['text' => 'هل تحتوي الجملة (She didn’t go the store yesterday.) على حال؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما هو الحال في الجملة (She didn’t go the store yesterday.)', 'options' => ['Store', 'Yesterday', 'Go', 'She'], 'correct' => 1],
        ['text' => 'ما نوع الحال في الجملة (She didn’t go the store yesterday.)', 'options' => ['Adverb of manner', 'Adverb of degree', 'Adverb of time', 'Adverb of comment'], 'correct' => 2],
        ['text' => 'They watched a movie last night. اختر النفي الصحيح للجملة التالية', 'options' => ['They didn’t watched a movie last night.', 'They didn’t watch a movie last night.', 'They wasn’t watched a movie last night.', 'They wasn’t watch a movie last night.'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح اذا لم يكن الفعل الأساسي (Be)؟', 'options' => ['Was \ were + subject + complement?', 'Did + subject + v1 + object + complement?', 'Did + subject + v2 + object + complement?', 'Was \ were + subject + v1 + complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح اذا كان الفعل الأساسي (Be)؟', 'options' => ['Was \ were + subject + object+ complement?', 'Did + subject + v1 + object + complement?', 'Did + subject + v2 + object + complement?', 'Was \ were + subject + v1 + complement?'], 'correct' => 0],
        ['text' => 'Othman went to the zoo yesterday. اختر تكوين السؤال الصحيح لجملة', 'options' => ['Did Abdullah go to the zoo yesterday?', 'Did Abdullah went to the zoo yesterday?', 'was Abdullah go to the zoo yesterday?', 'was Abdullah went to the zoo yesterday?'], 'correct' => 0],
        ['text' => 'Did Abdullah go to the zoo yesterday? اختر الإجابة الصحيحة للسؤال', 'options' => ['Yes, he did.', 'Yes, he didn’t.', 'No, she didn’t.', 'No, he did.'], 'correct' => 0],
        ['text' => 'My dad was in Sharurah last night. الترجمة الصحيحة لجملة', 'options' => ['ابي قد كان في شرورة الليلة الفائتة.', 'ابي كان في شرورة الليلة الفائتة.', 'ابي صارله في شرورة منذ الليلة الفائتة.', 'ابي يكون في شرورة الليلة الفائتة.'], 'correct' => 1],
        ['text' => 'My dad was in Sharurah last night. اختر تكوين السؤال الصحيح لجملة', 'options' => ['Were your dad in Sharurah last night?', 'Was your dad in Sharurah last night?', 'Did your dad in Sharurah last night?', 'Does your dad in Sharurah last night?'], 'correct' => 1],
        ['text' => 'My dad was in Jeddah last night. اختر الإجابة الصحيحة لجملة', 'options' => ['Yes, he wasn’t.', 'Yes, she was.', 'No, he wasn’t.', 'No, he was.'], 'correct' => 2],
        [
            'text' => 'صل بين كل سؤال وجوابه الصحيح:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Did Ameer go to school?', 'right' => 'Yes, he did \ No, he didn’t.'],
                ['left' => 'Did Mohammed and Nasser visit their relatives?', 'right' => 'Yes, they did.\No, they didn’t.'],
                ['left' => 'Did Samia revise her lesson?', 'right' => 'Yes, she did. \ No, she didn’t.'],
                ['left' => 'Did the frog sleep under the sofa?', 'right' => 'Yes, it did \ No, it didn’t.'],
                ['left' => 'Did we meet at the party yesterday?', 'right' => 'Yes, we did.\ No, we didn’t.'],
            ]
        ],
        ['text' => '(Yes he did) اختر علامات الترقيم المناسبة لجملة', 'options' => ['Yes, he did', 'Yes, he did.', 'Yes he, did.', 'Yes he did.'], 'correct' => 1],
        ['text' => 'ماذا نضيف في نهاية اللفعل المنتظم في التصريف الثاني والثالث؟', 'options' => ['Ed', 'Er', 'Est', 'None'], 'correct' => 0],
        ['text' => 'اذا انتهى الفعل بحرف (y فاننا:', 'options' => ['نحول حرف (y) الى (i) ثم نضيف (Ed) اذا كان الحرف قبل (y) حرف صوتي.', 'نحول حرف (y) الى (i) ثم نضيف (Ed) اذا كان الحرف قبل (y) حرف ساكن.', 'نضيف (Ed) فقط الى الفعل', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'الفعل الغير منتظم بشكل عام هو الفعل الذي لا نستطيع ان نضيف له (Ed)', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن الماضي البسيط:', 'options' => ['I have lived in London.', 'I have been living in London.', 'I am living in London.', 'None.'], 'correct' => 3],
        ['text' => 'حول الجملة التالية الى ماضي (I go to work).', 'options' => ['I have gone to work.', 'I have been going to work.', 'I went to work.', 'None.'], 'correct' => 2],
        ['text' => 'الماضي البسيط من الفعل (break) هو', 'options' => ['Broken', 'Broke', 'Breaded', 'Breaked'], 'correct' => 1],
        ['text' => 'الماضي البسيط من الفعل (put) هو', 'options' => ['Put', 'Puted', 'Putted', 'None'], 'correct' => 0],
        ['text' => 'الماضي البسيط هو فعل او حدث او حقيقة في الماضي ولم تعد موجودة الان ( غير مستمرة )', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'She buys pizza for lunch اختر الترجمة الصحيحة للجملة', 'options' => ['هي قد اشترت بيتزا للغداء', 'هي كانت تشتري بيتزا للغداء', 'هي اشترت بيتزا للغداء', 'هي تشتري بيتزا للغداء'], 'correct' => 2],
        ['text' => 'She buys pizza for lunch اختر الزمن الصحيح للجملة', 'options' => ['ماضي بسيط', 'مضارع بسيط', 'ماضي تام', 'مضارع مستمر'], 'correct' => 1],
        ['text' => 'حول الجملة (She buys pizza for lunch) الى زمن ماضي بسيط لتصبح', 'options' => ['She is buying pizza for lunch.', 'She bought pizza for lunch.', 'She has been buying pizza for lunch.', 'She was buying pizza for lunch.'], 'correct' => 1],
        ['text' => 'They arrived yesterday. اختر الترجمة الصحيحة لجملة', 'options' => ['هم وصلوا أمس', 'هم قد وصلوا امس', 'هم صارلهم يوصلون امس', 'هم كانوا يوصلون امس'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة (نحن لعبنا الأسبوع الماضي ) هي', 'options' => ['We’ve played last week.', 'We played last week.', 'We plaied last week.', 'We were playing last week.'], 'correct' => 1],
        ['text' => 'الفعل المساعد في الماضي البسيط هو:', 'options' => ['Do', 'Does', 'Did', 'Has'], 'correct' => 2],
        ['text' => 'للتأكيد على الأزمنة بشكل عام لا بد من وجود الفعل المساعد بين', 'options' => ['الفاعل والفعل', 'الفاعل والمفعول به', 'الفاعل وتكملة الجملة', 'الفعل والمفعول به'], 'correct' => 0],
        ['text' => 'الفعل الأساسي في الجملة في زمن الماضي البسيط اذا اتى بعد كلمة التأكيد الفعل المساعد (Did) ياتي', 'options' => ['مضاف له Ed', 'فعل مجرد (v1)', 'مضاف له ied', 'مضاف له ing'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( انا بالفعل اتصلت بهم ) هي', 'options' => ['I called them', 'I did called them.', 'I did call them', 'I do call them.'], 'correct' => 2],
        ['text' => 'I did __ my lunch. اختر الإجابة الصحيحة لجملة', 'options' => ['eat', 'ate', 'eaten', 'eated'], 'correct' => 0],
        ['text' => 'زمن الماضي البسيط هو الزمن الوحيد الذي لا نذكر فيه الوقت اذا كان المخاطب يعرف زمن او وقت حدوث الفعل', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( انا لم اسافر الشهر الماضي )', 'options' => ['I was not travelled last month.', 'I didn’t travelled last month.', 'I didn’t travel last month.', 'I am not travelled last month.'], 'correct' => 2],
        ['text' => 'He __ not __ there اختر الاجابة الصحيحة لجملة', 'options' => ['Does \ go', 'Did \ go', 'Has \ go', 'Did \ went'], 'correct' => 1],
        ['text' => 'Salem didn’t go to school اختر الترجمة الصحيحة لجملة', 'options' => ['سالم ما قد ذهب الى المدرسة.', 'سالم لم يذهب الى المدرسة.', 'سالم ذهب الى المدرسة.', 'لا شيء مما سبق.'], 'correct' => 1],
        ['text' => 'في الماضي البسيط نستخدم صيغة التعبير عن حقيقة او وصف في الماضي ونستخدم معه (Was | were) كفعل أساسي (V2)', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'Did Mohammed pass the exam? اختر الإجابة الصحيحة للسؤال', 'options' => ['Yes, Mohammed did', 'Yes, he did.', 'Yes he did', 'No , she didn’t.'], 'correct' => 1],
        ['text' => 'I __ sick اختر الإجابة الصحيحة لجملة', 'options' => ['Did', 'Were', 'Was', 'Are'], 'correct' => 2],
        ['text' => 'They __ in a hurry. اختر الإجابة الصحيحة لجملة', 'options' => ['Were not', 'Was not', 'Did not', 'Has not'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( خالد كان طالب)', 'options' => ['Khalid did a student.', 'Khalid was a student.', 'Khalid was student.', 'Khalid is a student.'], 'correct' => 1],
        ['text' => '__ was young اختر الإجابة الصحيحة لجملة', 'options' => ['Children', 'Fatima', 'Grandmothers', 'None'], 'correct' => 1],
        ['text' => '__ were noisy اختر الإجابة الصحيحة لجملة', 'options' => ['My birds', 'My bird', 'My television', 'None'], 'correct' => 0],
        ['text' => 'We __ the flight yesterday. اختر الإجابة الصحيحة لجملة', 'options' => ['Were', 'Missed', 'Was', 'are'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي البسيط (Past Simple Grammar)',
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
        ];

        if ($props['question_type'] === 'drag_drop') {
            $props['matching_pairs'] = $qData['matching_pairs'];
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1013.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
