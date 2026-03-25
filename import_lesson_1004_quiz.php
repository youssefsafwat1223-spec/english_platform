<?php

/**
 * Script to import questions for Lesson ID 1004 (Present Perfect Continuous Grammar)
 * php import_lesson_1004_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1004;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1004 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى المضارع التام المستمر في اللغة الإنجليزية؟', 'options' => ['Present simple', 'Present perfect', 'Present continuous', 'Present perfect continuous'], 'correct' => 3],
        ['text' => 'يستخدم المضارع التام المستمر ليعبر عن:', 'options' => ['سلوك متكرر', 'شيء يستمر الحدوث الفترة الحالية', 'احداث بدأت في الماضي ولا تزال مستمرة', 'حقائق وعادات'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح لجملة المضارع التام المستمر (المثبتة):', 'options' => ['Subject + has\have + been + v1+ing', 'Subject + has\have + been + v3', 'Subject + do\does + been + v1+ing', 'Subject + was\were + been + v1+ing'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا (Been) في تكوين المضارع التام المستمر؟', 'options' => ['لأنه الزمن مستمر', 'لأنه مضارع', 'لان قبله (Has \ have)', 'لا شيء'], 'correct' => 2],
        ['text' => 'لماذا استخدمنا (V1+ing) بعد Been في زمن المضارع التام المستمر؟', 'options' => ['لأنه مستمر مع كينونة', 'لأنه مضارع', 'لان قبله Has been \ have been', 'لا شيء'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا (been) بدل (Was \ were) في التام المستمر؟', 'options' => ['لأنه يأتي قبلها فعل مساعد يتطلب زمن تام', 'لأنه يأتي بعدها V+ing', 'لأنه زمن مضارع', 'لا شيء'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة لـ (Has been \ have been) في هذا السياق هي:', 'options' => ['قد', 'ما قد', 'صار بجميع تصريفاتها', 'لا شيء'], 'correct' => 2],
        ['text' => 'ما الضمائر التي تأخذ الفعل (Has)؟', 'options' => ['She – we – he – it', 'She – he – it', 'I – we – they – you', 'I – he – she'], 'correct' => 1],
        ['text' => 'ما الضمائر التي تأخذ الفعل (Have)؟', 'options' => ['She – we – he – it', 'She – he – it', 'I – we – they – you', 'I – he – she'], 'correct' => 2],
        ['text' => 'ما شكل الفعل المساعد (be) قبل (V1 +ing) في المضارع التام المستمر؟', 'options' => ['Is \ am \ are', 'Was \ were', 'Be', 'Been'], 'correct' => 3],
        ['text' => 'اختر الجملة التي تعبر عن المضارع التام المستمر؟', 'options' => ['He has been learning French for a year.', 'He has learned...', 'He has be leaning...', 'He have been leaning...'], 'correct' => 0],
        ['text' => '(He has been __ at the local masjid for two weeks) اختر الفعل المناسب:', 'options' => ['Volunteers', 'Volunteered', 'Volunteering', 'None'], 'correct' => 2],
        ['text' => 'في الجملة (She has been volunteering... for two weeks) هل استخدام For صحيح؟', 'options' => ['نعم لأن For تحدد طول الفترة (اسبوعان)', 'نعم لأن For تحدد بداية الفترة', 'غير صحيح والأنسب Since', 'لا شيء'], 'correct' => 0],
        ['text' => '(She has been cooking dinner for us tonight) الترجمة الصحيحة هي:', 'options' => ['قد طبخت', 'هي صارلها تطبخ العشاء لنا كل ليلة هذا الأسبوع', 'سوف تطبخ', 'لا شيء'], 'correct' => 1],
        ['text' => 'كم مفعول به في جملة (She has been cooking dinner for us...):', 'options' => ['مفعول به واحد', 'اثنان (Us, Dinner)', 'ثلاثة', 'لا يوجد'], 'correct' => 1],
        ['text' => 'حدد المفعول المباشر وغير المباشر في الجملة السابقة:', 'options' => ['Us مباشر / dinner غير مباشر', 'Us غير مباشر / dinner مباشر', 'Every night مباشر', 'None'], 'correct' => 1],
        ['text' => '(She __ attending online classes since the past semester) اختر الفعل المساعد:', 'options' => ['Have been', 'Has been', 'Is be', 'Is been'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح لنفي الجملة في المضارع التام المستمر:', 'options' => ['Subject + has\have+ not + been + v1+ing', 'Subject + not + has\have + been', 'Subject + has\have + been + v-ing + not', 'لا شيء'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة للفعل المساعد المنفي (Has not been/have not been) هي:', 'options' => ['ما قد', 'ما صارله', 'لم يفعل', 'لن'], 'correct' => 1],
        ['text' => 'اختر الجملة المنفية بالشكل الصحيح:', 'options' => ['He hasn’t been practicing...', 'He haven’t been...', 'He not has been...', 'Not he has...'], 'correct' => 0],
        ['text' => '(They have been building a tree house __ a month) اختر الكلمة المناسبة:', 'options' => ['Since', 'All', 'For', 'Yet'], 'correct' => 2],
        ['text' => 'لماذا وضعنا الأداة (a) قبل كلمة month؟', 'options' => ['لأن الأربع شروط توفرت لوضعها', 'لا بد أن نضعها في هذا الزمن', 'نضعها قبل الأسماء جميعها', 'لا شيء'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لزمن المضارع التام المستمر:', 'options' => ['Has\have+ subject + been + v1+ing ?', 'Has\have+ subject + been + v1?', 'Subject + has\have + been...', 'Is/are'], 'correct' => 0],
        ['text' => '(She has been organizing her closet since morning) اختر السؤال المناسب:', 'options' => ['Has she been organize...?', 'Has she been organizing her closet since morning?', 'Has she been organizing... (no ?)', 'Has she be...'], 'correct' => 1],
        ['text' => 'في تكوين السؤال للمضارع التام المستمر هل يأتي الفاعل قبل الفعل المساعد؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ما معنى الفعل المساعد Has \ have في تكوين السؤال؟', 'options' => ['هل صار', 'هل قد', 'ماذا', 'لا شيء'], 'correct' => 0],
        ['text' => '(Been – researching – my – for – I –have – eraser– yesterday – since) اعد ترتيبها:', 'options' => ['...yesterday since', 'I have been searching for my eraser since yesterday.', '...for since yesterday', '...searching been for...'], 'correct' => 1],
        ['text' => 'بعد إعادة الترتيب هل تحتوي الجملة السابقة على صفة ملكية؟', 'type' => 'true_false', 'options' => ['نعم (My)', 'لا'], 'correct' => 0],
        ['text' => 'ما هي صفة الملكية في الجملة السابقة؟', 'options' => ['Since', 'My', 'For', 'have'], 'correct' => 1],
        ['text' => '(Hiking – we – been – have – 30 – minutes- for) اعد ترتيبها:', 'options' => ['We have been hiking for 30 minutes.', '...30 for minutes', '...hiking been for...', '...for minutes 30'], 'correct' => 0],
        ['text' => 'لماذا وضعنا حرف (s) في آخر كلمة Minutes؟', 'options' => ['اسم جمع منتظم', 'للملكية', 'اختصار لـ Is', 'لا شيء'], 'correct' => 0],
        ['text' => '(Running – has – since – been – she -3 o’clock – not) اعد ترتيب الجملة المنفية:', 'options' => ['She has been not...', 'She has not been running since o’clock 3', 'Has she not...', 'She has not been running since 3 o’clock.'], 'correct' => 3],
        ['text' => 'عند نفي الجملة نرجع الفعل لأصله (فعل أساسي بدون إضافات)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(They have been playing soccer...) اختر تكوين السؤال الصحيح:', 'options' => ['Have they been play...?', 'Has they be...?', 'Have they been playing soccer since this morning?', '...morning. (no ?)'], 'correct' => 2],
        ['text' => '(Ahmed has been looking outside...) اختر تكوين السؤال الصحيح:', 'options' => ['Has he been looking outside for a long time?', 'Has she been...', 'He has been look...', 'None'], 'correct' => 0],
        ['text' => '(He has been trying quit sugar since September) الترجمة الصحيحة هي:', 'options' => ['هو صارله يحاول ان يترك السكر منذ شهر سبتمبر.', 'هو قد ترك السكر', 'هو حاول ان يترك', 'لا شيء'], 'correct' => 0],
        ['text' => '(__ have been playing the guitar for a long time) اختر الفاعل:', 'options' => ['She', 'It', 'They', 'He'], 'correct' => 2],
        ['text' => 'الترجمة الصحيحة لـ (هو صارله يحاول يفقد وزن منذ الأسبوع الفائت):', 'options' => ['He has tried...', 'He has been trying to lose weight since last week.', 'He is trying...', '...since for week'], 'correct' => 1],
        ['text' => 'أي سؤال يبدأ بـ Has \ have فإن إجابته تبدأ بـ:', 'options' => ['Yes \ No', 'اسم مكان', 'نحدد الوقت', 'Left \ right'], 'correct' => 0],
        ['text' => '(Has she been working on a new project?) الإجابة الصحيحة:', 'options' => ['Yes , she hasn’t.', 'No , she hasn’t.', 'No , she has.', 'Yes, he has.'], 'correct' => 1],
        ['text' => '(I have been working __ this afternoon) اختر الكلمة المناسبة:', 'options' => ['For', 'Since', 'But', 'None'], 'correct' => 1],
        ['text' => '(Khalid has been working __ 7 years) اختر الكلمة المناسبة:', 'options' => ['For', 'Since', 'But', 'None'], 'correct' => 0],
        ['text' => '(Seba has been cleaning __ yesterday) اختر الكلمة المناسبة:', 'options' => ['For', 'Since', 'But', 'None'], 'correct' => 1],
        ['text' => '(My mother has been calling __ 2 hours) اختر الكلمة المناسبة:', 'options' => ['For', 'Since', 'But', 'None'], 'correct' => 0],
        ['text' => 'لماذا نستخدم (V1 + ing) مع المضارع التام المستمر؟', 'options' => ['لأنه في صيغة المستمر', 'لأنه قبل الفعل be/been', 'الاثنان معا', 'لا شيء'], 'correct' => 2],
        ['text' => '(I have been waiting for you) الترجمة الصحيحة هي:', 'options' => ['انا قاعد انتظرك', 'انا صارلي انتظرك', 'انا كنت انتظرك', 'انا سوف انتظرك'], 'correct' => 1],
        ['text' => 'نستخدم كلمات الربط (__، __) مع زمن المضارع التام المستمر:', 'options' => ['Since \ for', 'Am \ is', 'Was \ were', 'To \ of'], 'correct' => 0],
        ['text' => '(We have __ __for 3 hours) اختر الإجابة الصحيحة:', 'options' => ['Has \ chat', 'Been \ chatting', 'Been \ chat', 'Are \ been'], 'correct' => 1],
        ['text' => '(They have been working since 9 am) الترجمة الصحيحة هي:', 'options' => ['هم صار لهم يعملون منذ التاسعة صباحا.', 'كانوا يعملون', 'عملوا', 'سوف يعملوا'], 'correct' => 0],
        ['text' => 'ترجمة: (أنا صارلي عايش في جيرسي لمدة 10 سنوات):', 'options' => ['He have been...', 'I have been living in Jersey for 10 years.', 'He have ben...', 'I have be living...'], 'correct' => 1],
        ['text' => 'ترجمة: (هم صارلهم يقرؤون رواية منذ هذا الصباح):', 'options' => ['They’ve been reading a novel since this morning.', 'They has been...', 'Both', 'None'], 'correct' => 0],
        ['text' => '(Saleh __ been thinking about his future) اختر الفعل المساعد:', 'options' => ['Have', 'Is', 'Has', 'Was'], 'correct' => 2],
        ['text' => 'ترجمة: (خالد صارله يخطط لمدة ساعتين):', 'options' => ['Khalid has been planing...', 'Khalid has planned...', 'Khalid has been planning for 2 hours.', 'Khalid planned...'], 'correct' => 2],
        ['text' => '(The driver __ been __ the car for 30 minutes):', 'options' => ['Have , parked', 'Has , parking', 'Has , parkied', 'Have , barking'], 'correct' => 1],
        ['text' => '(I __ been going to the gym lately):', 'options' => ['Am not', 'Has not', 'Have not', 'Are not'], 'correct' => 2],
        ['text' => '(our boss __ been delaying his meeting since afternoon):', 'options' => ['Has', 'Have', 'Is', 'was'], 'correct' => 0],
        ['text' => '(__ Dhafir been __ lately?)', 'options' => ['Has , jogging', 'Has , jog', 'Have , jogging', 'Have , jog'], 'correct' => 0],
        ['text' => 'في الجواب نذكر (Yes/no) ثم فاصة ثم (فعل مساعد + فاعل)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // Subject then Aux is correct generally, but prompt says "اسم فاعل + فعل مساعد"
        ['text' => '(Have you been saving money?) الإجابة الصحيحة:', 'options' => ['Yes, I have.', 'Yes, I am', 'Yes, I haven’t', 'No , I have.'], 'correct' => 0],
        ['text' => '(Has __ been improving his English?) الفاعل المناسب:', 'options' => ['Khalid', 'They', 'The cat', 'You'], 'correct' => 0],
        ['text' => '(I __ been sleeping for 3 hours):', 'options' => ['Has', 'Have', 'Was', 'Am'], 'correct' => 1],
        ['text' => '(I have been sleeping for 3 hours) الترجمة الصحيحة هي:', 'options' => ['انا صارلي نايم لمدة ثلاث ساعات', 'انا قد نمت', 'انا نمت', 'انا سأنام'], 'correct' => 0],
        ['text' => '(I have been sleeping for 3 hours) النفي الصحيح هو:', 'options' => ['been not sleeping', 'I haven’t been sleeping for 3 hours.', 'I hasn’t been...', 'I not been...'], 'correct' => 1],
        ['text' => 'ترجمة (I haven’t been sleeping for 3 hours):', 'options' => ['ما قد نمت', 'أنا ما صارلي نايم لمدة 3 ساعات', 'سوف لن انام', 'لا شيء'], 'correct' => 1],
        ['text' => 'تتحول Hasn’t \ haven’t في العامية إلى:', 'options' => ['ain’t', 'in’t', 'nt’', 'لا شيء'], 'correct' => 0],
        ['text' => 'جملة She ain’t prepared the lunch yet هي نفسها:', 'options' => ['isn’t prepared', 'She hasn’t prepared the lunch yet.', 'haven’t prepared', 'wasn’t prepared'], 'correct' => 1],
        ['text' => 'Sarah still has not washed the dishes. (في العامية):', 'options' => ['still hasn’t', 'Sarah still ain’t washed the dishes.', 'still has washed', 'لا شيء'], 'correct' => 1],
        ['text' => 'في العامية ain’t تعني (Has + not) أو (Have + not)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'We ain’t done هي نفسها:', 'options' => ['We haven’t done', 'isn’t done', 'weren’t done', 'hasnt done'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المضارع التام المستمر (Present Perfect Continuous)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1004.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
