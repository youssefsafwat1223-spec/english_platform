<?php

/**
 * Script to import questions for Lesson ID 1067 (Future Continuous Grammar)
 * php import_lesson_1067_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1067;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1067 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى زمن المستقبل المستمر في اللغة الإنجليزية؟', 'options' => ['Future simple', 'Future continuous', 'Present continuous', 'Past continuous'], 'correct' => 1],
        ['text' => 'يعبر زمن المستقبل المستمر عن جميع ما يلي( ما عدا)؟', 'options' => ['حدث سوف يبدأ ويستمر في وقت محدد', '(تداخل احداث في المستقبل ) حدث سيحدث وسيقاطعه حدث اخر في المستقبل', 'حدثان مختلفان سوف يحدثان ويستمران في نفس الوقت في المستقبل.', 'حدث استمر في الماضي وسينتهي في المستقبل'], 'correct' => 3],
        ['text' => 'لا يمكننا استخدام زمن المستقبل المستمر في حالة الطلب المهذب.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت في زمن المستقبل المستمر:', 'options' => ['Subject + will\shall + be + (v1+ing) + object \complement.', 'Subject + will\shall + be + (v1) + object \complement.', 'Subject + will\shall + (v1+ing) + object \complement.', 'Subject + will\shall + v1 + object \complement.'], 'correct' => 0],
        ['text' => 'اختر تكوين( المثبت) لزمن المستقبل المستمر في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are) + going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) + going to + v1 +object\complement.', 'Subject + be (is\am\are) + going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) + going to + have +v3 +object\complement.'], 'correct' => 2],
        ['text' => 'By this time next year, they are going to __ __ their 10th wedding anniversary. اختر الإجابة الصحيحة للجملة:', 'options' => ['Be celebrate', 'Be celebrating', 'Will celebrates', 'Be celebrated'], 'correct' => 1],
        ['text' => 'اختر الفعل المناسب للجملة: Things are going to be ___ worse before they get better.', 'options' => ['Get', 'Getting', 'Will get', 'gets'], 'correct' => 1],
        ['text' => 'لماذا نستخدم (V1+ing) في زمن المستقبل المستمر؟', 'options' => ['بسبب وجود الكينونة (Be) والتي دائما بعدها (V1+ing)', 'بسبب وجود (Will\shall)', 'لان (V1+ing) تأتي في كل الازمنة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نستخدم الكينونة (Be) في زمن المستقبل المستمر؟', 'options' => ['لأن الزمن مستمر والكينونة (Be) يجب ان تكون في كافة الأزمنة المستمرة', 'لأن الكينونة (Be) يجب ان تكون في كافة الأزمنة سواء البسيط – التام او المستمر.', 'لأن قبلها فاعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'I will be __ your lesson at 6:00pm o’clock tomorrow. اختر الفعل المناسب للجملة:', 'options' => ['Watch', 'Watched', 'Watching', 'Watches'], 'correct' => 2],

        [
            'text' => 'صل ما بين كل جملة واستخدامها في زمن المستقبل المستمر:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I will be revising my lesson tomorrow evening.', 'right' => 'حدث سوف يبدأ ويستمر في وقت محدد'],
                ['left' => 'I will be watching TV when she arrives tonight.', 'right' => 'تداخل حدثين في المستقبل'],
                ['left' => 'Next summer, Khalid will be traveling to different countries while volunteering for a humanitarian organization.', 'right' => 'حدثان سيستمران في المستقبل'],
                ['left' => 'Will you be helping me move my furniture next weekend?', 'right' => 'الطلب المهذب'],
            ]
        ],

        ['text' => 'I will __ on a new project all day tomorrow. اختر المناسب للجملة:', 'options' => ['Be work', 'Be working', 'Be works', 'am working'], 'correct' => 1],
        ['text' => 'She __ be preparing for the big game all week. اختر الفعل المساعد المناسب للجملة:', 'options' => ['Is', 'Will', 'Does', 'Has'], 'correct' => 1],
        ['text' => 'تعبر الجملة التالية عن: (Will you be taking care of my cats when I am out of town next week?)', 'options' => ['سؤال', 'طلب مهذب', 'حدثان متداخلان في المستقبل', 'حدث سوف يستمر لفترة محددة في المستقبل'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة لـ Will في جملة الطلب المهذب هي:', 'options' => ['سوف', 'قد', 'هل تقدر ( يمديك)', 'لا شيء مما سبق'], 'correct' => 2],

        [
            'text' => 'صل ما بين الفاعل واختصاره الصحيح مع الفعل المساعد Will:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I will', 'right' => 'I’ll'],
                ['left' => 'She will', 'right' => 'She’ll'],
                ['left' => 'He will', 'right' => 'He’ll'],
                ['left' => 'It will', 'right' => 'It’ll'],
                ['left' => 'They will', 'right' => 'they’ll'],
                ['left' => 'You will', 'right' => 'you’ll'],
                ['left' => 'We will', 'right' => 'We’ll'],
            ]
        ],

        ['text' => 'اختر التكوين الصحيح للنفي في زمن المستقبل المستمر:', 'options' => ['Subject + will\shall + be+ not + (v1) + object \complement.', 'Subject + will\shall + not + be + (v1+ing) + object \complement.', 'Subject + not+ will\shall + be + (v1+ing) + object \complement.', 'Not +Subject + will\shall + be + (v1+ing) + object \complement.'], 'correct' => 1],
        ['text' => 'اختر تكوين( النفي) لزمن المستقبل المستمر في قاعدة (be +going to):', 'options' => ['Subject + be (is\am\are)+not+ going to + have+ been + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + v1 +object\complement.', 'Subject + be (is\am\are) + not+ going to + be+ v1 + ing +object\complement.', 'Subject + be (is\am\are) +not+ going to + have +v3 +object\complement.'], 'correct' => 2],
        ['text' => 'اختر الإجابة الصحيحة لجملة: I can join you for lunch tomorrow, I __ __ __ attending a business meeting.', 'options' => ['Am not going to be', 'Am going to', 'Will not', 'Will not be'], 'correct' => 0],
        ['text' => 'اختر الاختصار الصحيح في النفي لـ Will:', 'options' => ['Willn’t', 'Won’t', 'Willnot’', 'None'], 'correct' => 1],
        ['text' => 'اختر الاختصار الصحيح في النفي لـ shall:', 'options' => ['Shallnot', 'Shan’t', 'Shalln’t', 'Shall’t'], 'correct' => 1],
        ['text' => 'في حالة النفي في زمن المستقبل المستمر يمكننا وضع (Not) بعد (be).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر النفي الصحيح لجملة: (At this time tomorrow, I will be finishing my work.)', 'options' => ['At this time tomorrow, I won’t be finish my work.', 'At this time tomorrow, I won’t be finishing my work.', 'At this time tomorrow, I will be not finishing my work.', 'At this time tomorrow, not I will be finishing my work.'], 'correct' => 1],
        ['text' => 'This time next year, I __ be working here. اختر الكلمة المناسبة للجملة:', 'options' => ['Am', 'Wasn’t', 'Won’t', 'Have'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (This time tomorrow, I will be taking a flight to visit my family in another country.)', 'options' => ['بهذا الوقت غدا، أخذ رحلة طيران لزيارة عائلتي في بلد اخر.', 'بهذا الوقت غدا، سآخذ رحلة طيران ( سأطير ) لزيارة عائلتي في بلد اخر.', 'بهذا الوقت غدا، أخذت رحلة طيران لزيارة عائلتي في بلد اخر.', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(Cooking – be – won’t –I – tomorrow) اعد ترتيب الجملة التالية:', 'options' => ['I won’t be cooking tomorrow.', 'won’t I be cooking tomorrow.', 'I be won’t cooking tomorrow.', 'I tomorrow won’t be cooking.'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للسؤال في زمن المستقبل المستمر:', 'options' => ['Will\shall +Subject + be + (v1+ing) + object \complement?', 'Will\shall +Subject + be + (v1) + object \complement?', 'Will\shall + be + subject + (v1+ing) + object \complement?', 'Will\shall + be + (v1+ing) + subject + object \complement?'], 'correct' => 0],
        ['text' => 'اختر تكوين( السؤال) الصحيح لجملة مستقبل مستمر تحتوي على be + going to:', 'options' => ['be (is\am\are) + Subject + going to + have+ been + ing +object\complement?', 'be (is\am\are) + Subject + going to + v1 +object\complement?', 'be (is\am\are) + Subject + going to + be+ v1 + ing +object\complement?', 'be (is\am\are) + Subject + going to + have +v3 +object\complement?'], 'correct' => 2],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة: She is going to be participating in the marathon.', 'options' => ['Is she going to be participating in a marathon?', 'Is she going to be participate in a marathon?', 'Is she going to participating in a marathon?', 'Is she going to be participating in a marathon.'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح للجملة: (She will be sleeping at her parent’s house.)', 'options' => ['Will she be sleeping at her parent’s house.', 'Will she be sleeping at her parent’s house?', 'Will she be sleep at her parent’s house?', 'Will be she sleeping at her parent’s house ?'], 'correct' => 1],
        ['text' => 'They will be doing a surgery. اختر تكوين السؤال الصحيح للجملة:', 'options' => ['Will they be doing a surgery?', 'Will be they doing a surgery?', 'Will they be do a surgery?', 'Are they be doing a surgery?'], 'correct' => 0],
        ['text' => 'Will Mustafa be studying in Mexico next semester? اختر الإجابة الصحيحة للسؤال:', 'options' => ['Yes, he won’t.', 'Yes, Mustafa will.', 'No, he won’t.', 'No, she won’t.'], 'correct' => 2],

        [
            'text' => 'صل ما بين كل سؤال وجوابه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Will you be starting your own business?', 'right' => 'Yes, I will. \ No, I won’t.'],
                ['left' => 'Will Faris be bringing chocolate?', 'right' => 'Yes, he will. \ No, he won’t.'],
                ['left' => 'Will your parents be sitting up a camp this weekend?', 'right' => 'Yes, they will. \ No, they won’t.'],
                ['left' => 'Will Nada be training for the math Olympics?', 'right' => 'Yes, she will.\ No, she won’t.'],
            ]
        ],

        ['text' => 'Abdullah __ be __ his driving lesson for an hour this afternoon. اختر المناسب لـ:', 'options' => ['Will \ practice', 'shall \ practicing', 'Is \ practicing', 'Has \ practiced'], 'correct' => 1],
        ['text' => '(They shall be working on their garden all weekend.) اختر الترجمة الصحيحة لجملة:', 'options' => ['هم سوف يكونوا يعملوا على حديقتهم طيلة عطلة نهاية الأسبوع.', 'هم كانوا يعملوا على حديقتهم طيلة عطلة نهاية الأسبوع.', 'هم سوف يعملوا على حديقتهم طيلة عطلة نهاية الأسبوع.', 'هم سوف عملوا على حديقتهم طيلة عطلة نهاية الأسبوع.'], 'correct' => 0],
        ['text' => 'She shall be baking a cake for our friend’s goodbye party. اختر الترجمة الصحيحة لـ:', 'options' => ['هي سوف تخبز كيكة لحفلة وداع صديقتنا', 'هي سوف تكون تخبز كيكة لحفلة وداع صديقتنا.', 'هي خبزت كيكة لحفلة وداع صديقتنا.', 'هي سوف لن تكون تخبز كيكة لحفلة وداع صديقتنا.'], 'correct' => 1],
        ['text' => 'كم مفعول به تحتوي الجملة: (She shall be baking a cake for our friend’s goodbye party.)؟', 'options' => ['مفعول به واحد', 'مفعولان اثنان', 'ثلاثة مفاعيل', 'لا يوجد مفعول به في الجملة'], 'correct' => 1],
        ['text' => 'حدد المفعول به في الجملة: (She shall be baking a cake for our friend’s goodbye party.)', 'options' => ['goodbye', 'A cake \ our friend’s goodbye party', 'shall', 'Friend’s \ a cake'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( خالد سوف يكون (راح) يذهب في رحلة الى المتحف الأسبوع القادم):', 'options' => ['Khalid will going on a trip to the museum next week.', 'Khalid will be going on a trip to the museum next week.', 'Khalid is be going on a trip to the museum next week.', 'Khalid will be going on a trip to the museum next month.'], 'correct' => 1],
        ['text' => 'We will be organizing a charity event for the orphans__ month. اختر الكلمة المناسبة لجملة:', 'options' => ['Last', 'Next', 'Tomorrow', 'Ago'], 'correct' => 1],
        ['text' => 'لماذا وضعنا الأداة a قبل الكلمة ما بين قوسين: We will be organizing (a charity event) for the orphans.؟', 'options' => ['لان الاسم Event بدأ بحرف علة', 'أن الصفة charity أتت قبل الموصوف event وهي التي تتحكم بالنطق وأضفنا (a) لأن الصفة بدأت بحرف ساكن وهو (C)', 'لان (A charity event) اسم مركب', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اختر النفي الصحيح لـ: (She will be having a video call with her family tonight.)', 'options' => ['She will be not having a video call with her family tonight.', 'She will not be having a video call with her family tonight.', 'She will be having not a video call with her family tonight.', 'Will she be having a video call with her family tonight?'], 'correct' => 1],
        ['text' => 'اكتشف الخطأ في الجملة وصححه ان وجد: (I will be read a book at the park tomorrow after noon.)', 'options' => ['نضع Been بدل be', 'نضع Am بدل will', 'نضع Reading بدل read', 'لا يوجد خطأ في الجملة'], 'correct' => 2],
        ['text' => 'By this time next year, I ___ ___ ___ for my master degree. اختر المناسب لـ:', 'options' => ['Am be studying', 'Will be studying', 'Will have study', 'Will be studied'], 'correct' => 1],
        ['text' => 'Will you be sleeping when I arrive? اختر نوع الكلام:', 'options' => ['جملة مثبتة', 'جملة منفية', 'سؤال', 'جملة أمر'], 'correct' => 2],
        ['text' => 'اكتشف الخطأ في الجملة وصححه ان وجد: Will you be bring your friend to the party tonight?', 'options' => ['نضع Been بدل be', 'نضع Bringing بدل bring', 'نضع You بدل your', 'لا يوجد خطأ في الجملة'], 'correct' => 1],
        ['text' => 'I will be __ all night at Al-Qadir night. اختر المناسب للجملة:', 'options' => ['Pray', 'Praying', 'Prayed', 'Prays'], 'correct' => 1],
        ['text' => 'في جملة: I will be__ all night at (Al-Qadir night.) لماذا كتبت الكلمة ما بين قوسين بحروف كبيرة؟', 'options' => ['لأنها اسم شخص معروف', 'لأنها في اخر الجملة', 'لان ليلة القدر من الليالي المعروفة والعظيمة ( اسم علم)', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => '(We – staying – beach – day – will – be – the – at –all) اعد ترتيب الجملة التالية:', 'options' => ['We be will staying at the beach all day.', 'We will be staying at the beach all day.', 'We will be staying at the day all beach.', 'We will be all staying at beach the day.'], 'correct' => 1],
        ['text' => '(My – won’t – I –car – work – be – taking – to) اعد ترتيب الجملة التالية:', 'options' => ['I taking won’t be my car to work.', 'I be wont taking my car to work.', 'I won’t be taking my work to car.', 'I won’t be taking my car to work.'], 'correct' => 3],
        ['text' => 'اختر (علامات الترقيم) الصحيحة لـ: (will abdullah be coming with us tomorrow)', 'options' => ['Will Abdullah be coming with us tomorrow.', 'Will Abdullah be coming with us tomorrow?', 'will Abdullah be coming with us Tomorrow?', 'Will Abdullah be coming with us Tomorrow.'], 'correct' => 1],
        ['text' => 'اكتشف الخطأ في الجملة وصححه ان وجد: (Nihal willn’t be coming to the picnic.)', 'options' => ['نضع Won’t بدل willn’t للنفي', 'نضع Come بدل coming', 'لا نضع الأداة The قبل Picnic', 'لا يوجد خطأ في الجملة'], 'correct' => 0],
        ['text' => 'اكتشف الخطأ في الجملة وصححه ان وجد: (It will be snowing by the time you get to school.)', 'options' => ['نضع snow بدل Snowing', 'نضع الأداة the قبل كلمة School', 'نضع في النهاية علامة استفهام بدل النقطة', 'لا يوجد خطأ في الجملة'], 'correct' => 3],
        ['text' => 'I will be picking up my niece this afternoon. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا سوف أكون اصطحب ( آخذ) ابنة اخي بعد الظهر.', 'انا سوف لن أكون اصطحب ( آخذ) ابنة اخي بعد الظهر.', 'هو سوف يكون يصطحب (يأخذ) ابنة أخيه بعد الظهر.', 'انا اصطحبت ( أخذت) ابنة اخي بعد الظهر.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة (ابي سوف يكون يقرأ القرآن عندما أصل):', 'options' => ['My dad will be reading Quran when I arrive.', 'My dad is reading Quran when I arrive.', 'My dad will be read Quran when I arrive.', 'My dad read Quran when I arrive.'], 'correct' => 0],
        ['text' => 'عندما ننفي جملة في ( زمن المستقبل المستمر) فإننا نضع Not بعد الفاعل مباشرة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'When she arrives, you______ (not \sleep). صحح ما بين قوسين ليناسب الجملة:', 'options' => ['Won’t be sleep', 'Won’t be sleeping', 'Willnt be sleeping', 'None'], 'correct' => 1],
        ['text' => 'The tour bus will be arriving at this time ___ اختر المناسب للجملة التالية:', 'options' => ['Yesterday', 'Tomorrow', 'Next', 'None'], 'correct' => 1],
        ['text' => 'لماذا وضعنا (the) في جملة: (The tour bus will be arriving at this time__.)؟', 'options' => ['لان كلمة tour تبدا بحرف ساكن', 'لأنها في بداية الجملة', 'لان المتحدث والسامع يعرفون أي باص (tour bus) نتكلم عنه', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'In an hour ,I’ll __ ironing my clothes. اختر الإجابة الصحيحة لجملة:', 'options' => ['Be', 'Been', 'Being', 'Was'], 'correct' => 0],
        ['text' => 'اختر الفاعل في جملة: (In an hour ,I’ll __ ironing my clothes.)', 'options' => ['Hour', 'I', 'My', 'Ironing'], 'correct' => 1],
        ['text' => 'يمكننا استبدال ضمير الفاعل في الجملة السابقة بـ:', 'options' => ['Sarah', 'Abdullah', 'لا يمكننا استبدال ضمير الفاعل(I) ويبقى كما هو', 'Adam and salwa'], 'correct' => 2],
        ['text' => 'اختر الفعل في الجملة السابقة:', 'options' => ['An hour', 'Ironing', 'My clothes', '’ll'], 'correct' => 1],
        ['text' => 'ما نوع الفعل في الجملة السابقة؟', 'options' => ['Irregular', 'متعدي Transitive يحتاج لمفعول به لكي تكون الجملة مفيدة', 'لازم Intransitive لا يحتاج الى مفعول به والجملة مفيدة', 'Modal'], 'correct' => 1],
        ['text' => 'اختر المفعول به في الجملة السابقة ( ان وجد):', 'options' => ['’ll', 'Ironing', 'My clothes', 'In an hour'], 'correct' => 2],
        ['text' => 'ما المقصود بـ I’ll في الجملة السابقة؟', 'options' => ['I shall', 'I will', 'I’m ill', 'None'], 'correct' => 1],
        ['text' => 'لماذا وضعنا ing للفعل في جملة: (Jamal will be eating with his friend this evening.)؟', 'options' => ['لان الزمن يحتوي على Be (V1 +Ing ودائما يأتي بعدها)', 'لان الفاعل عاقل', 'بسبب وجود Will', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'حدد الفاعل في جملة: (Jamal will be eating with his friend this evening.)', 'options' => ['His friend', 'Evening', 'Jamal', 'Will be'], 'correct' => 2],
        ['text' => 'يمكننا استبدال اسم الفاعل في جملة (Jamal will be eating with his friend this evening.) بضمير الفاعل:', 'options' => ['She', 'He', 'They', 'We'], 'correct' => 1],
        ['text' => 'حدد زمن جملة (Jamal will be eating with his friend this evening.):', 'options' => ['مستقبل بسيط', 'مستقبل تام', 'مستقبل مستمر', 'مضارع مستمر'], 'correct' => 2],
        ['text' => 'اختر المناسب للجملة: I am goimg to be ___ a workshop on leadership skills next week.', 'options' => ['Attend', 'Attends', 'Attending', 'Attended'], 'correct' => 2],
        ['text' => 'اختر المناسب للجملة: They __ planning a surprise party for their friend.', 'options' => ['Going to', 'Are going to', 'Be', 'Are going to be'], 'correct' => 3],
        ['text' => 'اختر المناسب للجملة: I am going to __ working on my writing skills during the summer break.', 'options' => ['Be', 'Been', 'Being', 'Have'], 'correct' => 0],
        ['text' => 'اختر( النفي) الصحيح للجملة: They are going to be developing a new software application for their company.', 'options' => ['They aren’t going to be developing a new software application for their company.', 'They are going to not be developing a new software application for their company.', 'They are going to be developing not a new software application for their company.', 'Not they are going to be developing a new software application for their company.'], 'correct' => 0],
        ['text' => 'اختر تكوين( السؤال) الصحيح للجملة: She is going to be improving her language proficiency through daily practice.', 'options' => ['Is she going to be improving her language proficiency through daily practice.', 'Be she going to is improving her language proficiency through daily practice?', 'Is she going to be improving her language proficiency through daily practice?', 'Does she going to be improving her language proficiency through daily practice?'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المستقبل المستمر (Future Continuous Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1067.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
