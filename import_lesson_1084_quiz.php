<?php

/**
 * Script to import questions for Lesson ID 1084 (Comprehensive Future Review)
 * php import_lesson_1084_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1084;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1084 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        // Future Simple
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن المستقبل البسيط:', 'options' => ['حدثان مختلفان سوف يحدثان ويستمران في نفس الوقت في المستقبل.', 'يستخدم للتعبير عن حدوث حدثين في المستقبل واحد سينتهي في المستقبل قبل حدث اخر', 'فترة حدوث فعل بدأ في الماضي واستمر حتى وقت محدد في المستقبل.', 'حدث سوف يحدث في المستقبل'], 'correct' => 3],
        ['text' => 'ما هو تكوين ( المثبت) للمستقبل البسيط؟', 'options' => ['Subject + shall \will+ v1 +object\complement.', 'Subject + will + be + (v1+ing) + object \complement.', 'Subject + will + have + v3 + object \complement.', 'Subject + will+ have + been+ v1 + ing + object \ complement.'], 'correct' => 0],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمستقبل البسيط؟', 'options' => ['Subject + will + not + be + (v1+ing) + object \complement.', 'Subject + will + not + have + v3 + object \complement.', 'Subject + will +not+ have + been+ v1 + ing + object \ complement.', 'Subject + will \will+ not+ v1 +object\complement.'], 'correct' => 3],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمستقبل البسيط؟', 'options' => ['Will +Subject + be + (v1+ing) + object \complement?', 'Will+ subject + have + v3 + object \complement?', 'Shall \Will + subject +v1+object \complement?', 'will+ subject + have + been+ v1 + ing + object \ complement?'], 'correct' => 2],
        ['text' => 'الفاعل يتحكم بالفعل المساعد Will \shall و يؤثر عليه.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'لماذا نضع V1 بعد Shall\Will؟', 'options' => ['لان Will \shall من الأفعال الناقصة Modal والأفعال الناقصة دائما بعدها فعل مجرد (V1)', 'لان Will \shall تأتي مع ضمائر وأسماء الفاعل المفرد والجمع', 'لان الزمن مستقبل وزمن المستقبل دائما نضع معه فعل مجرد ( تصريف أول)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'ما معنى كلمة shall \Will؟', 'options' => ['قد', 'س \ سوف', 'صارلي', 'لم'], 'correct' => 1],
        ['text' => 'اختصار Will في النفي هو:', 'options' => ['Willn’t', 'Won’t', 'Will not', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'من الذي يتحكم بالفعل المساعد Be قبل Going to؟', 'options' => ['الفاعل الذي يأتي قبله', 'الفعل الذي يأتي بعده', 'المفعول به الذي يأتي بعد الفعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'زمن المضارع المستمر( لا يمكن) استخدامه في التعبير عن المستقبل.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'عند (النفي) في زمن المضارع المستمر الذي يعبر عن المستقبل فإننا نضع not قبل الـ Be.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن زمن المستقبل البسيط:', 'options' => ['My doctor will see my scans.', 'They will be sleeping late tonight.', 'Alaa will have been using her phone for six hours by noon.', 'Khalid will have slept 8:00 pm.'], 'correct' => 0],
        ['text' => 'My colleague ___ ___ ___ to another unit. اختر الإجابة الصحيحة للجملة:', 'options' => ['Are going to transfer', 'Is going to transfer', 'Is going to transfers', 'Will transfers'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( الجندي سوف يذهب الى ارض المعركة.):', 'options' => ['The soldier shall goes to the battlefield.', 'The soldier will have gone to the battlefield.', 'The soldier shall go to the battlefield.', 'The policeman will go to the battlefield.'], 'correct' => 2],
        ['text' => 'اختر تكوين( المثبت) لزمن المستقبل البسيط في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are) + going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) + going to + v1 +object\complement.', 'Subject + be (is\am\are) + going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) + going to + have +v3 +object\complement.'], 'correct' => 1],
        ['text' => 'اختر تكوين( النفي) لزمن المستقبل البسيط في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are)+not+ going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + v1 +object\complement.', 'Subject + be (is\am\are) + not+ going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + have +v3 +object\complement.'], 'correct' => 1],
        ['text' => 'اختر تكوين( السؤال) الصحيح لجملة مستقبل بسيط تحتوي على be + going to:', 'options' => ['be (is\am\are) + Subject + going to + have+ been + ing +object\complement?', 'be (is\am\are) + Subject + going to + v1 +object\complement?', 'be (is\am\are) + Subject + going to + be+ v1 + ing +object\complement?', 'be (is\am\are) + Subject + going to + have +v3 +object\complement?'], 'correct' => 1],

        // Future Continuous
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن المستقبل المستمر:', 'options' => ['حدثان مختلفان سوف يحدثان ويستمران في نفس الوقت في المستقبل.', 'يستخدم للتعبير عن حدوث حدثين في المستقبل واحد سينتهي في المستقبل قبل حدث اخر', 'فترة حدوث فعل بدأ في الماضي واستمر حتى وقت محدد في المستقبل.', 'حدث سوف يبدأ ويستمر في وقت محدد'], 'correct' => 3],
        ['text' => 'ما هو تكوين ( المثبت) للمستقبل المستمر؟', 'options' => ['Subject + shall \will+ v1 +object\complement.', 'Subject + will + be + (v1+ing) + object \complement.', 'Subject + will + have + v3 + object \complement.', 'Subject + will+ have + been+ v1 + ing + object \ complement.'], 'correct' => 1],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمستقبل المستمر؟', 'options' => ['Subject + will + not + be + (v1+ing) + object \complement.', 'Subject + will + not + have + v3 + object \complement.', 'Subject + will +not+ have + been+ v1 + ing + object \ complement.', 'Subject + will \will+ not+ v1 +object\complement.'], 'correct' => 0],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمستقبل المستمر؟', 'options' => ['Will +Subject + be + (v1+ing) + object \complement?', 'Will+ subject + have + v3 + object \complement?', 'Shall \Will + subject +v1+object \complement?', 'will+ subject + have + been+ v1 + ing + object \ complement?'], 'correct' => 0],
        ['text' => 'اختر تكوين( المثبت) لزمن المستقبل المستمر في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are) + going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) + going to + v1 +object\complement.', 'Subject + be (is\am\are) + going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) + going to + have +v3 +object\complement.'], 'correct' => 2],
        ['text' => 'اختر تكوين( النفي) لزمن المستقبل المستمر في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are)+not+ going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + v1 +object\complement.', 'Subject + be (is\am\are) + not+ going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + have +v3 +object\complement.'], 'correct' => 2],
        ['text' => 'اختر تكوين( السؤال) الصحيح لجملة مستقبل مستمر تحتوي على be + going to:', 'options' => ['be (is\am\are) + Subject + going to + have+ been + ing +object\complement?', 'be (is\am\are) + Subject + going to + v1 +object\complement?', 'be (is\am\are) + Subject + going to + be+ v1 + ing +object\complement?', 'be (is\am\are) + Subject + going to + have +v3 +object\complement?'], 'correct' => 2],
        ['text' => 'لا يمكننا استخدام زمن المستقبل المستمر في حالة الطلب المهذب.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'لماذا نستخدم ing (الفعل الاساسي) في زمن المستقبل المستمر؟', 'options' => ['بسبب وجود الكينونة (Be) والتي دائما بعدها (V1+ing)', 'بسبب وجود Will', 'لان V1+ing تأتي في كل الازمنة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نستخدم الكينونة Be في زمن المستقبل المستمر؟', 'options' => ['لأن الزمن مستمر والكينونة Be يجب ان تكون في كافة الأزمنة المستمرة', 'لأن الكينونة Be يجب ان تكون في كافة الأزمنة سواء البسيط – التام او المستمر.', 'لأن قبلها فاعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة لـ Will في جملة (الطلب المهذب) هي:', 'options' => ['سوف', 'قد', 'هل تقدر ( يمديك)', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'عند استخدام المضارع المستمر كمستقبل في حالة ( النفي) نضع not قبل الـ Be مثل She not is taking a vacation tomorrow.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن زمن المستقبل المستمر:', 'options' => ['My favorite store will be offering discount this week.', 'I am going to deliver this package.', 'The candle will have been burning for half an hour by 3 o’clock.', 'The gardener will have mowed the grass.'], 'correct' => 0],
        ['text' => 'اختر الإجابة الصحيحة للجملة: The water company __ __ __ the water supply this afternoon.', 'options' => ['Will be stop', 'Will be stopping', 'Will have stopping', 'Will been stopping'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة: The driver will be cleaning his car before working.', 'options' => ['السائق كان ينظف سيارته قبل العمل.', 'السائق سوف يكون ينظف سيارته قبل العمل.', 'السائق سوف يكون يغلق سيارته قبل العمل.', 'السائق سوف يكون ينظف سيارته بعد العمل.'], 'correct' => 1],

        // Future Perfect
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن المستقبل التام:', 'options' => ['حدثان مختلفان سوف يحدثان ويستمران في نفس الوقت في المستقبل.', 'يستخدم للتعبير عن حدوث حدثين في المستقبل واحد سينتهي في المستقبل قبل حدث اخر', 'فترة حدوث فعل بدأ في الماضي واستمر حتى وقت محدد في المستقبل.', 'حدث سوف يحدث في المستقبل'], 'correct' => 1],
        ['text' => 'ما هو تكوين ( المثبت) للمستقبل التام؟', 'options' => ['Subject + shall \will+ v1 +object\complement.', 'Subject + will + be + (v1+ing) + object \complement.', 'Subject + will + have + v3 + object \complement.', 'Subject + will+ have + been+ v1 + ing + object \ complement.'], 'correct' => 2],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمستقبل التام؟', 'options' => ['Subject + will + not + be + (v1+ing) + object \complement.', 'Subject + will + not + have + v3 + object \complement.', 'Subject + will +not+ have + been+ v1 + ing + object \ complement.', 'Subject + will \will+ not+ v1 +object\complement.'], 'correct' => 1],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمضارع المستقبل التام؟', 'options' => ['Will +Subject + be + (v1+ing) + object \complement?', 'Will+ subject + have + v3 + object \complement?', 'Shall \Will + subject +v1+object \complement?', 'will+ subject + have + been+ v1 + ing + object \ complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين( المثبت) لزمن المستقبل التام في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are) + going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) + going to + v1 +object\complement.', 'Subject + be (is\am\are) + going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) + going to + have +v3 +object\complement.'], 'correct' => 3],
        ['text' => 'اختر تكوين( النفي) لزمن المستقبل التام في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are)+not+ going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + v1 +object\complement.', 'Subject + be (is\am\are) + not+ going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + have +v3 +object\complement.'], 'correct' => 3],
        ['text' => 'اختر تكوين( السؤال) الصحيح لجملة مستقبل تام تحتوي على be + going to:', 'options' => ['be (is\am\are) + Subject + going to + have+ been + ing +object\complement?', 'be (is\am\are) + Subject + going to + v1 +object\complement?', 'be (is\am\are) + Subject + going to + be+ v1 + ing +object\complement?', 'be (is\am\are) + Subject + going to + have +v3 +object\complement?'], 'correct' => 3],
        ['text' => 'نستخدم التصريف الثالث V3 في ( زمن المستقبل التام) للأسباب التالية (ما عدا):', 'options' => ['لان أي زمن في المستقبل لا بد من وجود V3 فيه', 'لان بعد Have كفعل مساعد يأتي التصريف الثالث للفعل', 'لان الزمن تام وأي زمن تام يكون فيه التصريف الثالث للفعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نضع Will \ shall في ( زمن المستقبل التام)؟', 'options' => ['لان Will \ shall ( أفعال مساعدة )تعبر عن زمن المستقبل', 'لأننا نضع Will \ shall في كافة الازمنة', 'لان Will \ shall هما الوحيدتان اللتان تأتيان مع الفاعل الجمع والمفرد', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نضع have وليس Has في تركيب ( زمن المستقبل التام)؟', 'options' => ['لان Have تأتي للمفرد والجمع', 'لان بعد الأفعال الناقصة مثل will \ shall يأتي فعل مجرد والمجرد هو Have وليس Has', 'بسبب وجود التصريف الثالث', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'جميع تصريفات الفعل الثالث ( v3) تنتهي ب (ed) بدون أي استثناء.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'عند تكوين السؤال في زمن المستقبل التام فإن( الفعل الأساسي) ياتي في التصريف الأول (v1).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن زمن المستقبل التام:', 'options' => ['By June, I will have graduated from high school.', 'I will be graduating from college next semester.', 'My mom will wake me up.', 'They will have been rowing for half an hour.'], 'correct' => 0],
        ['text' => 'When the gate opens, I __ ___ ___ the boarding pass. اختر الإجابة الصحيحة للجملة:', 'options' => ['Will have preparing', 'Will have prepared', 'Will has prepared', 'Will preparing'], 'correct' => 1],

        // Future Perfect Continuous
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن المستقبل التام المستمر؟', 'options' => ['حدثان مختلفان سوف يحدثان ويستمران في نفس الوقت في المستقبل.', 'يستخدم للتعبير عن حدوث حدثين في المستقبل واحد سينتهي في المستقبل قبل حدث اخر', 'فترة حدوث فعل بدأ في الماضي واستمر حتى وقت محدد في المستقبل.', 'حدث سوف يحدث في المستقبل'], 'correct' => 2],
        ['text' => 'ما هو تكوين ( المثبت) للمستقبل التام المستمر؟', 'options' => ['Subject + shall \will+ v1 +object\complement.', 'Subject + will + be + (v1+ing) + object \complement.', 'Subject + will + have + v3 + object \complement.', 'Subject + will+ have + been+ v1 + ing + object \ complement.'], 'correct' => 3],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمستقبل التام المستمر؟', 'options' => ['Subject + will + not + be + (v1+ing) + object \complement.', 'Subject + will + not + have + v3 + object \complement.', 'Subject + will +not+ have + been+ v1 + ing + object \ complement.', 'Subject + will \will+ not+ v1 +object\complement.'], 'correct' => 2],
        ['text' => 'ما هو التكوين الصحيح (لالسؤال) للمستقبل التام المستمر؟', 'options' => ['Will +Subject + be + (v1+ing) + object \complement?', 'Will+ subject + have + v3 + object \complement?', 'Shall \Will + subject +v1+object \complement?', 'will+ subject + have + been+ v1 + ing + object \ complement?'], 'correct' => 3],
        ['text' => 'لماذا نستخدم shall\Will في زمن المستقبل التام المستمر؟', 'options' => ['لان الزمن مستمر', 'لان الزمن مستقبل واي زمن مستقبل نستخدم معه ( أدوات المستقبل)', 'Shall \Will لان الجملة مثبتة واي جملة مثبتة نستخدم معها', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'لماذا لا نكتب Has بدل Have في زمن المستقبل التام المستمر؟', 'options' => ['لان Have تاني مع كافة الازمنة', 'لان بعد الأفعال الناقصة مثل will \ shall يأتي فعل مجرد والمجرد هو Have وليس Has', 'لان الزمن مستقبل تام', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'لماذا نضع V1+ing في زمن المستقبل التام المستمر؟', 'options' => ['لان الزمن تام', 'لان أي زمن مستمر لا بد ان نضع معه V1 + ing', 'لان قبلها الكينونة Be ودائما يأتي قبل الكينونة Be نضع V+ing', 'د – ب + ج'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة للفعل المساعد (Will have been):', 'options' => ['قد', 'لا', 'سوف اكون \ راح أكون', 'فائت'], 'correct' => 2],
        ['text' => 'اذا كان الفاعل (they\we\ I \you) نضع الفعل المساعد Will have been اما لوكان الفاعل (he \she\ it) فإننا نضع الفعل المساعد Will has been.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن زمن المستقبل التام المستمر:', 'options' => ['The teacher will have been discussing the grammar for the entire period.', 'The artificial intelligence will get smarter by time.', 'My siblings will be visiting my father.', 'The ice will have melted by then.'], 'correct' => 0],
        ['text' => 'By tomorrow morning, the earth __ __ __ __ for an entire day. اختر الإجابة الصحيحة لـ:', 'options' => ['Will have be orbiting', 'Will have been orbiting', 'Will have been orbited', 'Will have orbit'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة: The factories’ emissions will have been destroying nature for a century.', 'options' => ['انبعاثات المصانع راح يكون صارلها تدمر الطبيعة لمدة 10 سنوات.', 'انبعاثات المصانع راح يكون صارلها تدمر الطبيعة لمدة قرن.', 'انبعاثات الشركات راح يكون صارلها تدمر الطبيعة لمدة قرن.', 'انبعاثات المصنع راح يكون صارله يدمر الطبيعة لمدة قرن.'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة شاملة للمستقبل (Comprehensive Future Review)',
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

        $props['option_a'] = $qData['options'][0] ?? null;
        $props['option_b'] = $qData['options'][1] ?? null;
        $props['option_c'] = $qData['options'][2] ?? null;
        $props['option_d'] = $qData['options'][3] ?? null;
        $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1084.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
