<?php

/**
 * Script to import questions for Lesson ID 1043 (Mixed Past Tense Review)
 * php import_lesson_1043_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1043;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1043 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        // Past Simple
        ['text' => 'ما هو استخدام زمن الماضي البسيط المناسب؟', 'options' => ['حقائق في الماضي', 'احداث وعادات متكررة في الماضي', 'ترتيب وربط حدثين في الماضي', 'حدث بدا واستمر في الماضي ثم انتهى قبل بدء حدث اخر بعده'], 'correct' => 0],
        ['text' => 'ما هو تكوين ( المثبت) للماضي البسيط؟', 'options' => ['Subject + was \were + (v1+ing) + object \complement.', 'Subject + had + v3 + object\complement.', 'Subject + had + been +(v1+ing) + object \complement.', 'Subject + v2 + object + complement.'], 'correct' => 3],
        ['text' => 'ما هو تكوين ( المنفي ) للماضي البسيط؟', 'options' => ['Subject + did +not+ v1+ object + complement.', 'Subject + was \were+ not + (v1+ing) + object \complement.', 'Subject + had+ not + v3 + object\complement.', 'Subject + had + not + been +(v1+ing) + object \complement.'], 'correct' => 0],
        ['text' => 'ما هو تكوين السؤال للماضي البسيط؟', 'options' => ['Did + subject + v1 + object + complement?', 'Was \were + subject+ (v1+ing) + object \complement?', 'Had + subject +v3 + object \ complement?', 'Had +Subject + been +(v1+ing) + object \complement?'], 'correct' => 0],
        ['text' => '(V1+ing) سمي الماضي البسيط بهذا الاسم لانه يكون بأبسط حالاته وهو فاعل وفعل ومفعول به و', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'غالبا عندما نتحدث عن( حقائق او وصف) في الماضي فان الفعل يكون:', 'options' => ['V1', 'V3', '(v2(Beالتصريف الثاني من', 'have'], 'correct' => 2],
        ['text' => 'فعل أساسي بها be) اختر التكوين الصحيح( للمثبت) الماضي البسيط التي يكون)', 'options' => ['Subject + v2 + object + complement.', 'Subject + v3 + object + complement.', 'Subject + v2 + ing + object + complement.', 'Subject + was \ were + object + complement.'], 'correct' => 3],
        ['text' => 'Be اختر التكوين الصحيح( للنفي) الماضي البسيط اذا كان الفعل الأساسي هو (', 'options' => ['Subject + isn’t \ aren’t + object \complement.', 'Subject + wasn’t \ weren’t+ object  \ complement.', 'Subject + wasn’t \ weren’t+ v1+ object \ complement.', 'Subject + didn’t + v1 + object \ complement.'], 'correct' => 1],
        ['text' => '؟Be اختر تكوين( السؤال) للماضي البسيط اذا كان الفعل الأساسي (', 'options' => ['Was \ were + subject + object+ complement?', 'Did + subject + v1 + object + complement?', 'Did + subject + v2 + object + complement?', 'Was \ were + subject + v1 + complement?'], 'correct' => 0],
        ['text' => 'Was اختر ضمائر الفاعل التي تأخذ الفعل الأساسي (', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'Were اختر ضمائر الفاعل التي تأخذ الفعل الأساسي (', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'Was \were كيف اعرف بان الفعل ( فعل أساسي في الجملة؟)', 'options' => ['اذا أتوا في زمن الماضي', 'اذا اتى بعدهم صفة', 'اذا اتى بعدهم مفعول به', 'اذا لم يكن في الجملة فعل غيره'], 'correct' => 3],
        ['text' => '؟ is \am\are) بدل was\were) من الكينونة V2 لماذا نستخدم التصريف الثاني', 'options' => ['في زمن الماضي البسيط V2 لأنه زمن الجملة في الماضي البسيط ولا بد ان يكون تصريف ثاني', 'V1+ingلانه يأتي بعده', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'يكون الفعل الاساسي ( v2 ) did عند( السؤال) عن الماضي البسيط مع استخدام', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ماذا نضيف في نهاية الفعل المنتظم في التصريف الثاني والثالث؟', 'options' => ['Ed', 'Er', 'Est', 'None'], 'correct' => 0],
        ['text' => 'ما هو الفعل المنتظم؟', 'options' => ['edهو الفعل الذي نستطيع ان نضيف له', 'الفعل الذي يتغير مع التصريف', 'الفعل الذي لا يتغير أبدا ويكون نفس الكلمة في جميع التصريفات', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن زمن الماضي البسيط:', 'options' => ['I watched a movie for two hours last night.', 'Khalid was trying to jump while Ahmed was assisting him.', 'Our pilot had contacted the tower before he landed.', 'The engineers had been planning the project before we started working.'], 'correct' => 0],
        ['text' => 'The plants __ __ in winter.', 'options' => ['Was \ withered', 'Were \withered', 'Was \ wither', 'Was \ withering'], 'correct' => 1],
        ['text' => 'اختر التصريف الصحيح للفعل: Abdullah ___ the stain on his car last week.', 'options' => ['Remove', 'Removed', 'Removing', 'removes'], 'correct' => 1],

        // Past Continuous
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن الماضي المستمر:', 'options' => ['شيء حدث في الماضي', 'حدثان استمرا بنفس الفترة في الماضي', 'ترتيب وربط حدثين في الماضي', 'حدث بدا واستمر في الماضي ثم انتهى قبل بدء حدث اخر بعده'], 'correct' => 1],
        ['text' => 'ما هو تكوين ( المثبت) للماضي المستمر؟', 'options' => ['Subject + was \were + (v1+ing) + object \complement.', 'Subject + had + v3 + object\complement.', 'Subject + had + been +(v1+ing) + object \complement.', 'Subject + v2 + object + complement.'], 'correct' => 0],
        ['text' => 'ما هو تكوين ( المنفي ) للماضي المستمر؟', 'options' => ['Subject + did +not+ v1+ object + complement.', 'Subject + was \were+ not + (v1+ing) + object \complement.', 'Subject + had+ not + v3 + object\complement.', 'Subject + had + not + been +(v1+ing) + object \complement.'], 'correct' => 1],
        ['text' => 'ما هو تكوين( السؤال) للماضي المستمر؟', 'options' => ['Did + subject + v1 + object + complement?', 'Was \were + subject+ (v1+ing) + object \complement?', 'Had + subject +v3 + object \ complement?', 'Had +Subject + been +(v1+ing) + object \complement?'], 'correct' => 1],
        ['text' => 'الطلب بطريقة مهذبة هو أحد استخدامات الماضي المستمر.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(لا ) يمكننا استخدام زمن الماضي المستمر عند وجود احداث متقاطعة( يعني حدث كان يستمر والأخر قطعه).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'Was \ were ماذا نعتبر ( في زمن الماضي المستمر؟)', 'options' => ['فعل أساسي في الجملة.', 'فعل مساعد يعرف بالزمن و (لا) يوصف ماذا حدث في الجملة.', 'فعل ناقص', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'الجملة التي تعبر عن( حدثان استمرا) في الماضي تكون( احداها) ماضي بسيط', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'Was not \ were not اختر الاختصار الصحيح ل (', 'options' => ['Wasn’t\weren’t', 'Was’nt \ were’nt', 'Wasnt’ \werent’', 'None'], 'correct' => 0],
        ['text' => 'في زمن الماضي المستمر؟ Was\were ما معنى الكينونة', 'options' => ['يكون \ يكونوا \تكون \ أكون ......', 'كان\ كانت \ كانوا \ كنت ......', 'قد', 'قد صارله'], 'correct' => 1],
        ['text' => 'V1+ing لماذا نستخدم ( في الماضي المستمر؟)', 'options' => ['Beبسبب وجود الكينونة', 'لان الزمن ماضي', 'مع كافة الازمنةV1+ingلأنه يجوز استخدام', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن زمن الماضي المستمر:', 'options' => ['I was drinking water with my medication this morning.', 'They were absent yesterday.', 'They had been explaining the situation before the police came in.', 'Abeer had charged the laptop before she used it at school.'], 'correct' => 0],
        ['text' => 'I __ __ heavy boxes all day yesterday.', 'options' => ['Was carrying', 'Were carrying', 'Had carried', 'Was carry'], 'correct' => 0],
        ['text' => 'The driver was always arriving on time. اختر الترجمة الصحيحة للجملة:', 'options' => ['السائق كان عادة يصل في الوقت المحدد.', 'السائق كان دائما يصل في الوقت المحدد.', 'الراكب كان دائما يصل في الوقت المحدد.', 'السائق كان دائما يصل في الوقت المناسب.'], 'correct' => 1],

        // Past Perfect
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن الماضي التام:', 'options' => ['شيء حدث في الماضي', 'حدثان استمرا بنفس الفترة في الماضي', 'ترتيب وربط حدثين في الماضي', 'حدث بدا واستمر في الماضي ثم انتهى قبل بدء حدث اخر بعده'], 'correct' => 2],
        ['text' => 'ما هو تكوين ( المثبت) للماضي التام؟', 'options' => ['Subject + was \were + (v1+ing) + object \complement.', 'Subject + had + v3 + object\complement.', 'Subject + had + been +(v1+ing) + object \complement.', 'Subject + v2 + object + complement.'], 'correct' => 1],
        ['text' => 'ما هو تكوين ( المنفي ) للماضي التام؟', 'options' => ['Subject + did +not+ v1+ object + complement.', 'Subject + was \were+ not + (v1+ing) + object \complement.', 'Subject + had+ not + v3 + object\complement.', 'Subject + had + not + been +(v1+ing) + object \complement.'], 'correct' => 2],
        ['text' => 'ما هو تكوين السؤال للماضي التام؟', 'options' => ['Did + subject + v1 + object + complement?', 'Was \were + subject+ (v1+ing) + object \complement?', 'Had + subject +v3 + object \ complement?', 'Had +Subject + been +(v1+ing) + object \complement?'], 'correct' => 2],
        ['text' => 'he /she /it ما عدا had جميع أسماء وضمائر الفاعل الجمع تأخذ الفعل المساعد', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ترجمنا الفعل المساعد (Had) في الماضي التام الى:', 'options' => ['هل', 'سوف', 'قد', 'كان'], 'correct' => 2],
        ['text' => 'في زمن الماضي التام نربط بين ( زمنين )احدهما ماضي تام والثاني يكون:', 'options' => ['مضارع تام', 'ماضي بسيط', 'مضارع بسيط', 'مضارع تام مستمر'], 'correct' => 1],
        ['text' => 'من اين اتى الفعل المساعد (Had)؟', 'options' => ['التصريف الثالث من (Did)', 'الماضي من (Have \ has)', 'الماضي من (Was \ were)', 'None'], 'correct' => 1],
        ['text' => 'في أي تصريف من تصريفات الفعل يكون زمن الماضي التام:', 'options' => ['V1', 'V2', 'V3', 'None'], 'correct' => 2],
        ['text' => 'ما هو زمن الحدث الأول الذي بدا وانتهى قبل الحدث الثاني في زمن الماضي التام؟', 'options' => ['Past simple', 'Present perfect', 'Past perfect', 'None'], 'correct' => 2],
        
        [
            'text' => 'صل ما بين ضمير الفاعل واختصاره مع الفعل المساعد Had:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I had', 'right' => 'I’d'],
                ['left' => 'We had', 'right' => 'We’d'],
                ['left' => 'She had', 'right' => 'Shed'],
                ['left' => 'He had', 'right' => 'He’d'],
                ['left' => 'You had', 'right' => 'You’d'],
                ['left' => 'They had', 'right' => 'They’d'],
            ]
        ],

        ['text' => 'في( سؤال) الماضي التام؟ Had ما معنى الفعل المساعد:', 'options' => ['قد', 'هل قد', 'امتلك', 'كان'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن الماضي التام:', 'options' => ['Adam had used his blinker before he started driving.', 'Khalid was looking for a realty.', 'Aisha liked vanilla.', 'Albaraa had been coughing before he took the medication.'], 'correct' => 0],
        ['text' => 'The pilgrims __ __from Makkah before they performed Hajj.', 'options' => ['Had arrive', 'Had arrived', 'Was arrived', 'Has arrived'], 'correct' => 1],
        ['text' => 'I had slept well before I went to the gym. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا قد نمت جيدا قبل ان اذهب الى النادي الرياضي.', 'انا نمت جيدا قبل ان اذهب الى النادي الرياضي.', 'انا ما نمت جيدا قبل ان اذهب الى النادي الرياضي.', 'انا قد نمت جيدا بعد ان اذهب الى النادي الرياضي.'], 'correct' => 0],

        // Past Perfect Continuous
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر( استخدام) لزمن الماضي التام المستمر:', 'options' => ['شيء حدث في الماضي', 'حدثان استمرا بنفس الفترة في الماضي', 'ترتيب وربط حدثين في الماضي', 'حدث بدا واستمر في الماضي ثم انتهى قبل بدء حدث اخر بعده'], 'correct' => 3],
        ['text' => 'ما هو تكوين ( المثبت) للماضي التام المستمر؟', 'options' => ['Subject + was \were + (v1+ing) + object \complement.', 'Subject + had + v3 + object\complement.', 'Subject + had + been +(v1+ing) + object \complement.', 'Subject + v2 + object + complement.'], 'correct' => 2],
        ['text' => 'ما هو تكوين ( المنفي ) للماضي التام المستمر؟', 'options' => ['Subject + did +not+ v1+ object + complement.', 'Subject + was \were+ not + (v1+ing) + object \complement.', 'Subject + had+ not + v3 + object\complement.', 'Subject + had + not + been +(v1+ing) + object \complement.'], 'correct' => 3],
        ['text' => 'ما هو تكوين( السؤال) للماضي التام المستمر؟', 'options' => ['Did + subject + v1 + object + complement?', 'Was \were + subject+ (v1+ing) + object \complement?', 'Had + subject +v3 + object \ complement?', 'Had +Subject + been +(v1+ing) + object \complement?'], 'correct' => 3],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ الفعل المساعد (Had) في زمن الماضي التام المستمر:', 'options' => ['He \ she \ it', 'جميع الضمائر', 'They \we \you', 'I فقط الضمير'], 'correct' => 1],
        ['text' => 'نضع v1+ing في زمن الماضي التام المستمر لجميع الأسباب التالية (ما عدا)؟', 'options' => ['يجب ان تكون في جميع الأزمنة المستمرةV1+ingلان', '(Beenلأن الفعل الاساسي أتى بعد الكينونة(', 'V1+ingلان جميع الأزمنة تأخذ', 'None'], 'correct' => 2],
        ['text' => 'في الماضي التام المستمر؟ had Been ماذا نعني ب:', 'options' => ['قد صارله', 'قد كان \ كانت \ كانوا ...', 'ما قد', 'يكون \ يكونوا \ تكون ......'], 'correct' => 1],
        ['text' => 'متى نعرف ان اختصار I’d هو ( I + had )؟', 'options' => ['اذا اتى بعده الفعل تصريف ثالث', 'v1+ingاذا اتى بعده', 'اذا اتى الفعل بعده الفعل تصريف ثاني', 'None'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن زمن الماضي التام المستمر:', 'options' => ['This store had been selling bags before it shut down.', 'Fatima has been designing her room before signed with the contractor.', 'The coffee shop had sold cold prew before it changed the menu.', 'Abdurrahman had breakfast with his father today.'], 'correct' => 0],
        ['text' => 'أكمل الجملة: The IT department__ __ __ for a new system before they got the project.', 'options' => ['Had promising', 'Had been promising', 'Has promised', 'Was promise'], 'correct' => 1],
        ['text' => 'My dad had been preparing the bait before he went fishing. اختر الترجمة الصحيحة للجملة:', 'options' => ['أبي قد كان يجهز الطعم قبل ان ذهب للصيد.', 'أبي جهز الطعم قبل الذهاب للصيد.', 'أبي قد كان يجهز الطعم بعد ان ذهب للصيد.', 'أخي قد كان يجهز الطعم قبل ان ذهب للصيد.'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة شاملة للماضي (Comprehensive Past Tense Review)',
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
            $props['correct_answer'] = 'A'; // Default for matching
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1043.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
