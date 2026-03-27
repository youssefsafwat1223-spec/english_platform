<?php

/**
 * Script to import questions for Lesson ID 1055 (Future Simple Grammar)
 * php import_lesson_1055_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1055;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1055 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى زمن المستقبل البسيط في اللغة الإنجليزية؟', 'options' => ['Present simple', 'Past simple', 'Future simple', 'Future continuous'], 'correct' => 2],
        ['text' => 'يعبر زمن المستقبل البسيط عموما عن:', 'options' => ['حدث سوف يحدث في المستقبل', 'حدث يحدث ويستمر الان', 'شيء حدث في الماضي', 'عادات في الوقت الحاضر وستكون مستقبلية'], 'correct' => 0],
        ['text' => 'اختر تكوين المثبت لزمن المستقبل البسيط في قاعدة (Will\shall):', 'options' => ['Subject + will\shall + v2+object\complement.', 'Subject + shall \will+ v1 +object\complement.', 'Subject + be (is –am – are) + will \ shall+ v1 +object\complement.', 'Subject +shall \ will + v1 + ing + object\complement.'], 'correct' => 1],
        ['text' => 'ضمائر وأسماء الفاعل تؤثر على Will في الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'لماذا نضع V1 بعد Shall\Will؟', 'options' => ['لان Will \shall من الأفعال الناقصة Modal والافعال الناقصة دائما بعدها فعل مجرد (V1)', 'لان Will \shall تأتي مع ضمائر وأسماء الفاعل المفرد والجمع', 'لان الزمن مستقبل وزمن المستقبل دائما نضع معه فعل مجرد ( تصريف أول)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'جميع ما يلي استخدامات Will ما عدا:', 'options' => ['قرار سريع Quick decision', 'عرض خدمة Offer', 'وعد Promise', 'نصيحة advice'], 'correct' => 3],
        ['text' => 'ما معنى كلمة Will؟', 'options' => ['قد', 'س \ سوف', 'صارلي', 'لم'], 'correct' => 1],
        ['text' => 'We will ___ the new system next month. اختر الإجابة الصحيحة للجملة:', 'options' => ['Implements', 'Implement', 'Implemented', 'Implementing'], 'correct' => 1],
        ['text' => 'ما هو الفاعل في الجملة: (We will ___ the new system next month.)', 'options' => ['Will', 'We', 'الكلمة الناقصة', 'The new software system'], 'correct' => 1],
        ['text' => 'اختر من أسماء الفاعل ما يمكن ان نبدل به ضمير الفاعل في الجملة: (We will ___ the new system next month.)', 'options' => ['I', 'Mohammed and I', 'Mohammed and you', 'You'], 'correct' => 1],
        ['text' => 'اختر الفعل المساعد في الجملة: (We will ___ the new system next month.)', 'options' => ['الكلمة الناقصة في الجملة', 'Will', 'The new software system', 'Next month'], 'correct' => 1],
        ['text' => 'ما هو الجزء المفقود من الجملة (الفراغ) في: (We will ___ the new system next month. )', 'options' => ['الفاعل لان الجملة ينقصها فاعل', 'الفعل مساعد لان Will هي الفعل الاساسي', 'الفعل أساسي لان الفعل Will هو فعل مساعد هنا', 'المفعول به لان Will فعل اساسي تحتاج لمفعول به بعدها'], 'correct' => 2],
        ['text' => 'الجملة (We will ___ the new system next month.) لا تحتوي على مفعول به.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ما هو المفعول به في الجملة: (We will ___ the new system next month.)', 'options' => ['Will', 'The new system', 'Next month', 'We'], 'correct' => 1],
        ['text' => 'ما الذي يدل على زمن المستقبل في الجملة: (We will ___ the new system next month.)', 'options' => ['New\we', 'System\the', 'Next month\will', 'الجملة ليست في زمن المستقبل'], 'correct' => 2],
        ['text' => 'ما معنى كلمة New في الجملة وما نوعها من حيث اقسام الكلام؟', 'options' => ['معناها جديد وهي فعل', 'معناها قديم وهي صفة', 'معناها جديد وهي صفة', 'معناها عتيق وهي حال'], 'correct' => 2],
        ['text' => 'لماذا أتت أداة التعريف the قبل كلمة new في الجملة؟', 'options' => ['لان الصفات تأتي قبل الأسماء الموصوفة وأدوات التعريف تأتي قبل الصفات.', 'لأننا نضع الأداة للصفة New', 'بسبب وجود Will', 'ليس مما سبق'], 'correct' => 0],
        ['text' => 'I ___ buy a new necklace. اختر الإجابة الصحيحة للجملة:', 'options' => ['Am', 'Will', 'Going to', 'Was'], 'correct' => 1],
        ['text' => 'They will go to Hajj ___ week. اختر الإجابة الصحيحة للجملة:', 'options' => ['ago', 'Last', 'next', 'for'], 'correct' => 2],
        ['text' => 'لماذا الفعل مجرد في الجملة: (They will go to Hajj ___week.)', 'options' => ['لان بعد Will يأتي فعل مجرد', 'لان ضمير الفاعل they لا يأخذ s مع الفعل', 'لان الزمن مستقبل واي زمن مستقبل يأتي فيه الفعل مجرد', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'كيف عرفت ان الجملة (They will go to Hajj ___week.) في زمن مستقبل بسيط؟', 'options' => ['بسبب وجود Will كفعل مساعد (وحيد) في الجملة.', 'بسبب وجود ضمير فاعل في بداية الجملة', 'بسبب وجود كلمة By في الجملة', 'لا شيء مما سبق'], 'correct' => 0],
        
        [
            'text' => 'صل ما بين كل جملة وما تعبر عنه من استخدامات Will:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Don’t worry. I am sure the economic situation will improve soon.', 'right' => 'التأكيد على شيء سوف يحدث في المستقبل'],
                ['left' => 'The next flight from London will be within an hour.', 'right' => 'اعلان للجمهور لشيء سوف يحدث'],
                ['left' => 'If you find it hard, I will help you.', 'right' => 'عرض خدمة'],
                ['left' => 'The bus is leaving, I will take a taxi.', 'right' => 'قرار سريع'],
                ['left' => 'Don’t worry mum, I will call you every day.', 'right' => 'الوعد'],
            ]
        ],

        ['text' => 'اذا قال محمد: I am thirsty . ورد خالد عليه: I will bring you water. لماذا استخدمنا will وليس going to في رد خالد؟', 'options' => ['لأنه قرار سريع والقرارات السريعة نستخدم معها will وليس going to', 'لأنه قرار مخطط له فنستخدم will وليس going to', 'لا فرق في الاستخدام بين will و going to وكلاهما صحيح', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'He will __ from college in two years. اختر الإجابة الصحيحة للجملة:', 'options' => ['Graduate', 'Graduates', 'Graduating', 'Is going'], 'correct' => 0],
        ['text' => 'اختر نفي المستقبل البسيط في قاعدة (Will\shall):', 'options' => ['Subject + will\shall + not+ v2+object\complement.', 'Subject + will \will+ not+ v1 +object\complement.', 'Subject + be (is –am – are) + not+ will \ shall+ v1 +object\complement.', 'Not +Subject +shall \ will + v1 + ing + object\complement.'], 'correct' => 1],
        ['text' => 'اختر النفي الصحيح للجملة: (I will show you how to use this camera.)', 'options' => ['I willn’t show you how to use this camera.', 'I won’t show you how to use this camera.', 'I not will show you how to use this camera.', 'I am not will show you how to use this camera.'], 'correct' => 1],
        ['text' => 'اختصار Will في النفي هو:', 'options' => ['Willn’t', 'Won’t', 'Will not', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة مستقبل بسيط تحتوي على Shall \Will:', 'options' => ['Shall \Will + subject +v1+object \complement?', 'Will\shall + (do\does)+ subject +v1+object \complement?', 'Will\shall + subject +v1+object \complement.', 'Will\shall + to + subject +v1+object \complement?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح للجملة: ( My brother will sleep till noon.)', 'options' => ['Will your brother sleeps till noon?', 'Will your brother sleep till noon?', 'Will your brother sleep till noon.', 'Will your brother to sleep till noon?'], 'correct' => 1],
        ['text' => 'اختر النفي الصحيح للجملة: ( My brother will sleep till noon.)', 'options' => ['My brother will sleep till noon.', 'Will your brother sleep till noon?', 'My brother won’t sleep till noon.', 'My brother doesn’t sleep till noon.'], 'correct' => 2],
        ['text' => 'Will the surgeon finish the operation by 4:00 pm? اختر الإجابة الصحيحة للسؤال:', 'options' => ['Yes, he will', 'Yes, he won’t', 'No, I won’t', 'No, he will'], 'correct' => 0],

        [
            'text' => 'صل بين كل سؤال وجوابه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Will you wake up early tomorrow?', 'right' => 'Yes, I will.\No, I won’t.'],
                ['left' => 'Will Salma start her new job next week?', 'right' => 'Yes, she will.\No, she won’t.'],
                ['left' => 'Will the lawyers draft contracts for the new business?', 'right' => 'Yes, they will.\No, they won’t.'],
                ['left' => 'Will Abdullah serve in this place?', 'right' => 'Yes, he will.\No, he won’t.'],
            ]
        ],

        ['text' => 'اختر تكوين المثبت لزمن المستقبل البسيط في قاعدة (be +going to):', 'options' => ['Subject + will\shall + going to+ v2+object\complement.', 'Subject + be (is\am\are) + going to + v1 + ing +object\complement.', 'Subject + be (is –am – are) + going to+ v1 +object\complement.', 'Subject +going to + v1 + ing + object\complement.'], 'correct' => 2],
        ['text' => 'جميع ما يلي استخدام Going to في زمن المستقبل البسيط ما عدا:', 'options' => ['وعد promise', 'خطط مستقبلية Future plans', 'أشياء سوف تحدث مع وجود دليل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا going to وليس Will في الجملة (This man is careless .He is going to make an accident.)؟', 'options' => ['لأنه يوجد دليل بعمل الحادث وهو ان الرجل مهمل وعند وجود دليل نستخدم Going to', 'لان الزمن مستقبل ونستخدم Going to في المستقبل دائما.', 'لان Going to تستخدم في الأشياء السلبية مثل عمل الحادث.', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'من الذي يتحكم بالفعل المساعد Be قبل Going to؟', 'options' => ['الفاعل الذي يأتي قبله', 'الفعل الذي يأتي بعده', 'المفعول به الذي يأتي بعد الفعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'أي من الكينونة Be تستخدم مع قاعدة going to إذا كان الفاعل جمع؟', 'options' => ['Is', 'Am', 'Are', 'Be'], 'correct' => 2],
        ['text' => 'أي من الكينونة Be تستخدم مع قاعدة going to إذا كان الفاعل مفرد؟', 'options' => ['Is', 'Am', 'Are', 'Be'], 'correct' => 0],
        ['text' => 'أي من الكينونة Be تستخدم مع قاعدة going to اذا كان الفاعل هو I ( أنا )؟', 'options' => ['Is', 'Am', 'Are', 'Be'], 'correct' => 1],
        ['text' => 'She __ going to buy a new car. اختر الإجابة الصحيحة للجملة:', 'options' => ['Is', 'Will', 'Am', 'Are'], 'correct' => 0],
        ['text' => 'We are __ celebrate his birthday at a restaurant. اختر الإجابة الصحيحة للجملة:', 'options' => ['Will', 'Going', 'Going to', 'Won’t'], 'correct' => 2],
        ['text' => 'The chief of finance officer is going to __ (review) the budget. اختر الشكل الصحيح للفعل:', 'options' => ['Review', 'Reviewing', 'Reviews', 'To review'], 'correct' => 0],
        ['text' => 'ما نوع الفعل (review) في جملة: (The chief of finance officer is going to review the budget)؟', 'options' => ['Modal verb', 'Helping verb', 'Transitive verb فعل متعدي', 'Intransitive verb فعل لازم'], 'correct' => 2],
        ['text' => 'لماذا تعتبر budget مفعول به في الجملة السابقة؟', 'options' => ['لأن كلمة Budget تأثرت بالفعل ووقع عليها أثر الفعل.', 'لأنها في اخر الجملة', 'لان قبلها الأداة The', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'هل is في الجملة السابقة (فعل أساسي)؟', 'options' => ['تعتبر فعل أساسي ولا يحتاج لفعل بعده', 'تعتبر فعل مساعد', 'تعتبر فعل ناقص', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'لماذا لا نستخدم will هنا بدل going to في: (She is going to learn how to program the computer.)؟', 'options' => ['لان ضمير الفاعل She مفرد', 'لان Going to تأتي دائما في زمن المستقبل', 'لان الجملة تعبر عن شيء تم التخطيط له مسبقا ونستخدم Going to للشيء المخطط له.', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'اختر تكوين النفي لزمن المستقبل البسيط في قاعدة (be +going to):', 'options' => ['Subject + will\shall +not+ going to+ v2+object\complement.', 'Subject + be + not+ going to + v1 +object\complement.', 'Subject + be (is –am – are) + going to+ v1 +object\complement.', 'Not +Subject +going to + v1 + ing + object\complement.'], 'correct' => 1],
        ['text' => 'اختر النفي الصحيح للجملة: (She is going to learn how to play volleyball.)', 'options' => ['She is not going to learn how to play volleyball.', 'She not is going to learn how to play volleyball.', 'Not she is going to learn how to play volleyball.', 'She won’t is going to learn how to play volleyball.'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة مستقبل بسيط تحتوي على be + going to:', 'options' => ['Be (is\am\are) + subject + going to + v1 + object \complement.', 'Be (is\am\are) + subject +going to + v1 + object \complement?', 'Will (is\am\are) + subject + going to + v1 + object \complement?', 'Be (is\am\are) + subject + going to + v2 + object \complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح للجملة: (She is going to learn how to play volleyball.)', 'options' => ['Will she going to learn how to play volleyball?', 'Are she going to learn how to play volleyball?', 'Is she going to learn how to play volleyball?', 'Is she go to learn how to play volleyball?'], 'correct' => 2],

        [
            'text' => 'صل ما بين كل سؤال وجوابه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Are you going to take permission from your parents?', 'right' => 'Yes, I am.\No, I am not.'],
                ['left' => 'Is Aisha going to prepare herself for this event?', 'right' => 'Yes, she is.\No, she isn’t.'],
                ['left' => 'Is Khalid going to cancel all his plans?', 'right' => 'Yes, he is.\No, he isn’t.'],
                ['left' => 'Are the employers going to hire additional staff?', 'right' => 'Yes, they are.\No, they aren’t.'],
            ]
        ],

        ['text' => 'زمن المضارع المستمر( لا) يمكن استخدامه في التعبير عن المستقبل.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'متى نستخدم زمن المضارع المستمر للتعبير عن المستقبل البسيط؟', 'options' => ['احداث سوف تحدث بالمستقبل مع اتخاذ خطوات لإجرائها', 'تنبؤ مع وجود دليل', 'شيء يحدث الان', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر تكوين المثبت لزمن المستقبل البسيط عند استخدام زمن مضارع مستمر:', 'options' => ['Subject + will\shall + v1+ ing +object\complement.', 'Subject + be  + v1 + ing +object\complement.', 'Subject + be (is –am – are) + going + v1 +object\complement.', 'Subject  + v1 + ing + object\complement.'], 'correct' => 1],
        ['text' => 'اختر الإجابة الصحيحة للجملة: The team ___ a match on Saturday.', 'options' => ['Will plays', 'Is playing', 'Is going play', 'Play'], 'correct' => 1],
        ['text' => 'Ali is ___ in London for two days next week. اختر الإجابة الصحيحة للجملة:', 'options' => ['Working', 'Will work', 'Going work', 'Works'], 'correct' => 0],
        ['text' => 'اختر الشكل الصحيح للفعل: I can’t accept your invitation tonight. I ___(study) for an important exam.', 'options' => ['Am studying', 'Will study', 'Am going to study', 'Studies'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (I am leaving tomorrow. I have already bought the train’s ticket. )', 'options' => ['انا بغادر (مغادر) غدا، لقد اشتريت مسبقا تذكرة القطار.', 'انا غادرت بالأمس، لقد اشتريت مسبقا تذكرة القطار.', 'انا سوف لن اغادر غدا، لقد اشتريت مسبقا تذكرة القطار.', 'انا سوف اغادر غدا، و سوف اشتري تذكرة القطار.'], 'correct' => 0],
        ['text' => 'اين نضع not في المستقبل البسيط اذا استخدمنا المضارع المستمر؟', 'options' => ['Be قبل الكينونة', 'V1 + ing بعد', 'بعد الفعل الأساسي', 'Be بعد الكينونة'], 'correct' => 3],
        ['text' => 'اختر تكوين النفي الصحيح للجملة: (I am leaving tomorrow.)', 'options' => ['I not am leaving tomorrow.', 'I am not leaving tomorrow.', 'I will not am leaving tomorrow.', 'I am not leave tomorrow.'], 'correct' => 1],
        ['text' => 'They  __ preparing for a trade show next weekend. اختر الإجابة الصحيحة للجملة:', 'options' => ['Will', 'Is', 'Going to', 'Are'], 'correct' => 3],
        ['text' => 'من الفاعل في الجملة: (They  __ preparing for a trade show next weekend)', 'options' => ['They', 'Preparing', 'الكلمة الناقصة', 'لا يوجد فاعل في الجملة'], 'correct' => 0],
        ['text' => 'ما هو الفعل الأساسي في الجملة الأخيرة؟', 'options' => ['الكلمة الناقصة هي الفعل الأساسي', 'They والكلمة التي تحتاج الى تصحيح معا', 'Preparing', 'show'], 'correct' => 2],
        ['text' => 'كيف عرفت ان زمن الجملة الأخيرة هو ( مستقبل)؟', 'options' => ['بسبب وجود Ing في الجملة', 'بسبب وجود كلمة Next weekend وتعني عطلة نهاية الأسبوع القادم وهذا يعتبر في المستقبل.', 'بسبب وجود كلمة For', 'زمن الجملة ليس مستقبل والزمن مضارع هنا'], 'correct' => 1],
        ['text' => 'لماذا وضعنا الاداة a قبل كلمة trade في الجملة الأخيرة؟', 'options' => ['بسبب تحقق الشروط الأربعة للتعريف للأداة A', 'لأن الاسم جمع', 'أسهل في النطق', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة مضارع مستمر تعبر عن المستقبل:', 'options' => ['Be (is\am\are) + subject + v1 + object \complement.', 'Be (is\am\are) + subject + v1 + ing + object \complement?', 'Will (is\am\are) + subject+ v1 + object \complement?', 'Be (is\am\are) + subject + v2 + object \complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح للجملة: (They are leaving tomorrow.)', 'options' => ['Are they leaving tomorrow?', 'Am you leaving tomorrow?', 'Will you leaving tomorrow?', 'Are you leave tomorrow?'], 'correct' => 0],
        ['text' => 'حدد الخطأ وصححه ان وجد في الجملة: ( Saudi Arabia is going to build Neom)', 'options' => ['نكتب will بدل Is going to لأن الجملة تتحدث عن شيء رسمي (حكومي)', 'نكتب built بدل Build', 'نكتب are بدل Is', 'لا يوجد خطأ في الجملة'], 'correct' => 0],
        ['text' => 'في قاعدة going to تعتبر (Be+ going to) هي الفعل الأساسي في الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'في قاعدة المضارع المستمر الفعل الذي يحتوي على Ing هو الفعل الأساسي في الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'في قاعدة going to بعد to مستحيل أن يأتي فعل له Ing.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'عند استخدام going to كأداة مستقبل فان ال be قبلها يكون فعل مساعد وأيضا going to تكون فعل مساعد يعبر عن المستقبل عكس الأزمنة المستمرة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'حدد الخطأ في الجملة وصححه ان وجد: (I will goes to the beach).', 'options' => ['نضع go بدل Goes', 'نضع ضمير فاعل جمع بدل من ضمير الفاعل I', 'لا نضع الأداة The', 'لا يوجد خطأ في الجملة'], 'correct' => 0],
        ['text' => 'لماذا وضعنا ing للفعل ما بين قوسين: They are (conducting) a fire drill next month. ؟', 'options' => ['لأنها الفعل اساسي في الجملة وقبله اتى Be فأضفنا Ing عليه', 'لأنها فعل مساعد', 'لأنها فعل يتكون من مقطع واحد', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للجملة: (workshop - training - attend  - I - will - a)', 'options' => ['I will training attend a workshop.', 'I will attend a training workshop.', 'Workshop will attend a training I.', 'I will attend training a workshop.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة: (Shall – he – buy – detector – a smoke)', 'options' => ['Shall he buy a smoke detector.', 'He shall buy a smoke detector.', 'He shall buy  smoke a detector.', 'He buy shall a smoke detector.'], 'correct' => 1],
        ['text' => 'الجملة (We ain’t making a new marketing strategy.) هي نفسها:', 'options' => ['We won’t implementing a new marketing strategy.', 'We aren’t implementing a new marketing strategy.', 'We isn’t implementing a new marketing strategy.', 'We haven’t implementing a new marketing strategy.'], 'correct' => 1],
        ['text' => 'ما هو اختصار الفعل المساعد Going to؟', 'options' => ['Gonna', 'Gonna to', 'GT', 'none'], 'correct' => 0],
        ['text' => 'ain’t هي اختصار لجميع ما سبق ما عدا:', 'options' => ['Isn’t', 'Aren’t', 'Am not', 'Hasn’t'], 'correct' => 3],
        ['text' => 'Shall we go out for dinner tonight? اختر الاجابة الصحيحة للسؤال:', 'options' => ['Yes, we shall.', 'Yes, we shan’t.', 'No, we shall.', 'No, we shallent.'], 'correct' => 0],
        ['text' => 'الاختصار للنفي Shall not هو:', 'options' => ['Shan’t', 'Shallont', 'Shon’t', 'لا شيء مما سبق'], 'correct' => 0],

        [
            'text' => 'صل ضمائر الفاعل واختصاره مع الفعل المساعد Will:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I will', 'right' => 'I’ll'],
                ['left' => 'You will', 'right' => 'you’ll'],
                ['left' => 'He will', 'right' => 'He’ll'],
                ['left' => 'She will', 'right' => 'She’ll'],
                ['left' => 'It will', 'right' => 'it’ll'],
                ['left' => 'We will', 'right' => 'We’ll'],
                ['left' => 'They will', 'right' => 'they’ll'],
            ]
        ],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المستقبل البسيط (Future Simple Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1055.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
