<?php

/**
 * Script to import questions for Lesson ID 1075 (Future Perfect Grammar)
 * php import_lesson_1075_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1075;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1075 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى زمن المستقبل التام في اللغة الإنجليزية؟', 'options' => ['Present perfect', 'Past perfect', 'Future perfect', 'Future simple'], 'correct' => 2],
        ['text' => 'يستخدم زمن المستقبل التام للتعبير عن:', 'options' => ['حدث سوف يستمر في المستقبل', 'حدث سيبدأ في المستقبل وانتهى قبل بدء حدث جديد اتى بعده أيضا في المستقبل', 'أحداث متداخلة في المستقبل', 'شيء حدث وانتهى قبل فترة قصيرة'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت في زمن المستقبل التام:', 'options' => ['Subject + will\shall + be+ v3 + object \complement.', 'Subject + will\shall + have + v1 + object \complement.', 'Subject + will\shall + have + v3 + object \complement.', 'Subject + will\shall + has + v3 + object \complement.'], 'correct' => 2],
        ['text' => 'نستخدم التصريف الثالث V3 في ( زمن المستقبل التام) للأسباب التالية (ما عدا):', 'options' => ['لان أي زمن في المستقبل لا بد من وجود V3 فيه', 'لان بعد Have يأتي التصريف الثالث للفعل', 'لان الزمن تام وأي زمن تام يكون فيه التصريف الثالث للفعل', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نضع Will \ shall في ( زمن المستقبل التام)؟', 'options' => ['لان الزمن (مستقبل)، فلذلك تعتبر اداة زمن المستقبل Will', 'لأننا نضع Will في كافة الازمنة', 'لان Will هي الوحيدة التي تأتي مع الفاعل الجمع والمفرد', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نضع have وليس Has في تركيب ( زمن المستقبل التام)؟', 'options' => ['لان Have تأتي للمفرد والجمع', 'لان دائما بعد الفعل الناقص will\shall يأتي الفعل (مجرد) والفعل Have هو المجرد وليس Has', 'بسبب وجود التصريف الثالث', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'By next year, I will __ __ from college. اختر المناسب لجملة:', 'options' => ['Have \ graduated', 'Has \ graduated', 'Have \ graduating', 'None'], 'correct' => 0],
        ['text' => 'She shall __ returned home by the end of the week. اختر المناسب لجملة:', 'options' => ['Be', 'Has', 'Have', 'Is'], 'correct' => 2],
        ['text' => 'جميع تصريفات الفعل الثالث تنتهي بـ Ed بدون أي استثناء.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'By next summer, I will have __ money for a trip to Makkah. اختر الفعل المناسب لـ:', 'options' => ['Savd', 'Saved', 'Save', 'Saving'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة التالية: (By the time he arrives, we will have already prepared dinner.)', 'options' => ['بمجرد ان يصل سوف نكون قد حضرنا العشاء من قبل.', 'بمجرد ان يصل سنكون نحضر العشاء.', 'بينما يصل سيكون قد انتهى من تحضير العشاء من قبل.', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر تكوين النفي الصحيح لزمن المستقبل التام:', 'options' => ['Subject + will\shall + not +be+ v1 + object \complement.', 'Subject +not + will\shall + have + v1 + object \complement.', 'Subject + will\shall + not + have + v3 + object \complement.', 'Subject + will\shall + has + not + v3 + object \complement.'], 'correct' => 2],
        ['text' => 'They will have fixed the broken knob by tonight. اختر النفي الصحيح للجملة:', 'options' => ['They will have not fixed the broken knob by tonight.', 'They will not have fixed the broken knob by tonight.', 'They no will have fixed the broken knob by tonight.', 'Not they will have fixed the broken knob by tonight.'], 'correct' => 1],
        ['text' => 'We shan’t have achieved our goals by the end of the year. اختر الترجمة الصحيحة لـ:', 'options' => ['نحن ننجز اهدافنا قبل نهاية العام.', 'نحن سوف قد ننجز اهدافنا قبل نهاية العام.', 'نحن سوف لن نكون منجزين اهدافنا قبل نهاية العام.', 'نحن ما انجزنا اهدافنا قبل نهاية العام.'], 'correct' => 2],
        ['text' => 'Nada __ have learned how to speak French fluently by the end of this month. اختر الكلمة المناسبة للجملة:', 'options' => ['Has', 'Is', 'Won’t', 'wasn’t'], 'correct' => 2],
        ['text' => 'I will have learned new skills ___ June. اختر الكلمة المناسبة للجملة:', 'options' => ['Last', 'Before', 'Then', 'When'], 'correct' => 1],
        ['text' => 'When they reach the top of the mountain, the sun will have __. اختر المناسب لـ:', 'options' => ['Seted', 'Set', 'Sat', 'Setied'], 'correct' => 1],
        ['text' => 'When they reach the top of the mountain, the night will have __. اختر المناسب لـ:', 'options' => ['Come', 'Came', 'Comes', 'coming'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال لزمن المستقبل التام:', 'options' => ['Will\shall+ subject + have + v3 + object \complement?', 'Subject + will\shall + have + v1 + object \complement?', 'Will\shall + subject + have + v1 + object \complement?', 'Will\shall +subject + have + v3 + object \complement.'], 'correct' => 0],
        ['text' => 'عند تكوين السؤال في زمن المستقبل التام فإن الفعل الأساسي في الجملة يكون تصريف اول v1.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'She will have made a decision before 7:00 pm. اختر الترجمة الصحيحة لـ:', 'options' => ['هي سوف تكون قد اتخذت قرار قبل الساعة السابعة مساء.', 'هي اخذت قرار قبل الساعة السابعة مساء.', 'هي راح قد تأخد قرار قبل الساعة السابعة صباحا.', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'She will have made a decision before 7:00 pm. اختر النفي الصحيح للجملة:', 'options' => ['She will not have made a decision before 7:00 pm?', 'She will have not made a decision before 7:00 pm.', 'She will not have make a decision before 7:00 pm.', 'None'], 'correct' => 0],
        ['text' => 'She will have made a decision before 7:00 pm. اختر تكوين السؤال المناسب لـ:', 'options' => ['Will she have made a decision before 7:00 pm?', 'Will she have made a decision before 7:00 pm.', 'Will she have make a decision before 7:00 pm?', 'Will she has made a decision before 7:00 pm?'], 'correct' => 0],

        [
            'text' => 'صل السؤال بجوابه المناسب:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Will you have finished your work by next week?', 'right' => 'Yes, I will. \ No, I won’t.'],
                ['left' => 'Will Sara have transferred to a new department?', 'right' => 'Yes, she will.\No, she will not.'],
                ['left' => 'Will the streets have been flooded by the end of winter?', 'right' => 'Yes, they will.\No, they won’t.'],
                ['left' => 'Will your ear have healed before July?', 'right' => 'Yes, it will.\ No, it won’t.'],
            ]
        ],

        ['text' => 'Zaid will have __ weight in five months from now. اختر الفعل المناسب لجملة:', 'options' => ['Lose', 'Losed', 'Lost', 'Loses'], 'correct' => 2],
        ['text' => '___ will have landed by 9 pm. اختر اسم الفاعل المناسب لجملة:', 'options' => ['The car', 'The plane', 'The dog', 'The taxi'], 'correct' => 1],
        ['text' => '___ will have eaten the food by then. اختر اسم الفاعل المناسب لجملة:', 'options' => ['The tree', 'The rabbit', 'The sun', 'The table'], 'correct' => 1],
        ['text' => 'We __ __ __ to the market before you come. اختر المناسب لجملة:', 'options' => ['shall have gone', 'Will be going', 'Will be goes', 'None'], 'correct' => 0],
        ['text' => 'يمكننا استبدال ضمير الفاعل في الجملة (We __ __ __ to the market before you come) باسم الفاعل:', 'options' => ['Ahmed and khalid', 'Khalid and I', 'Yasser and you', 'Fareeda'], 'correct' => 1],
        ['text' => 'I will have __ the office __ 9 o’clock. اختر المناسب لجملة:', 'options' => ['Reached \ in', 'Reached \ by', 'Reached \ on', 'Reaching \ at'], 'correct' => 1],
        ['text' => 'The Laundromat will have closed by evening. اختر الترجمة الصحيحة للجملة التالية:', 'options' => ['مغسلة الملابس سوف تكون قد اغلقت بحلول المساء.', 'مغسلة الملابس سوف تغلق بحلول المساء.', 'مغسلة الملابس سوف تكون قد مغلقة بحلول الصباح.', 'مغسلة الملابس تغلق بحلول المساء.'], 'correct' => 0],
        ['text' => 'كيف عرفت ان زمن الجملة التالية (The Laundromat will have closed by evening) هو مستقبل؟', 'options' => ['Will بسبب وجود', 'Have بسبب وجود', 'V3 بسبب وجود تصريف ثالث للفعل', 'الجملة ليست مستقبل'], 'correct' => 0],
        ['text' => 'كيف عرفت ان زمن الجملة التالية (The Laundromat shall have closed by evening) هو مستقبل(تام)؟', 'options' => ['بسبب وجود Have وأي زمن تام نضع فيه Have وتصريفاتها', 'لان الفاعل مفرد', 'لان الفعل منتظم', 'ليس مما سبق'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا V3 في الجملة التالية: (The Laundromat will have closed by evening)؟', 'options' => ['لان أي زمن تام يجب ان يحتوي على تصريف ثالث للفعل', 'لان بعد have اتى الفعل في التصريف الثالث', 'لان زمن المستقبل لا بد ان يحتوي على تصريف ثالث للفعل', 'ا + ب'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة لجملة (ابي سوف يكون قد اشترى سيارة جديدة في غضون 3 شهور):', 'options' => ['My father will have bought a new car within three days.', 'My father will have bought a new car within three months.', 'My father have bought a new car within three months.', 'My father will be buying a new car within three months.'], 'correct' => 1],
        ['text' => 'Qasim will have __ all the cookies. اختر الفعل المناسب للجملة التالية:', 'options' => ['Eated', 'Eaten', 'Ate', 'Eats'], 'correct' => 1],
        ['text' => 'she __(not\ buy) a nightstand by tomorrow. اختر المناسب للجملة:', 'options' => ['Won’t have bought', 'Will buy', 'Willnt have bought', 'None'], 'correct' => 0],
        ['text' => 'I will __ have spent so much money on clothes. اختر المناسب للجملة:', 'options' => ['On', 'No', 'Not', 'An'], 'correct' => 2],
        ['text' => 'لماذا وضعنا المحدد much هنا في جملة: (I will not have spent so much money on clothes)؟', 'options' => ['لان كلمة Money اسم معدود', 'لان كلمة Money اسم غير معدود', 'لأنها جملة تتكون من مقطع واحد', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'I __ __ __ by then.', 'options' => ['Will be leave', 'Will have left', 'Will leaving', 'Will left'], 'correct' => 1],
        ['text' => 'Will you __ __ by 8 am? اختر المناسب لـ:', 'options' => ['Have arrived', 'Be arrived', 'Have arriving', 'None'], 'correct' => 0],
        ['text' => 'Will you __ the contract by Thursday. اختر المناسب لـ:', 'options' => ['Have mailed', 'Mailing', 'Mailed', 'To have mailed'], 'correct' => 0],
        ['text' => 'حدد الخطأ ان وجد في: ( Will you have mailed the contract by Thursday. )', 'options' => ['نكتب thursday بدل Thursday', 'بدل النقطة في النهاية نكتب علامة استفهام لأنها سؤال.', 'لا يوجد خطأ في الجملة', 'لا نضع You بعد Will'], 'correct' => 1],
        ['text' => 'اختر المناسب للجملة التالية: (By the time you arrive I ___ some snacks.)', 'options' => ['Will prepare', 'Will have prepared', 'Will be preparing', 'Am preparing'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن المستقبل التام:', 'options' => ['I will be installing the software.', 'I will have installed the software by tomorrow.', 'I go to install the software tomorrow.', 'I want to install the software tomorrow.'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تعبر عن المستقبل التام:', 'options' => ['I will have hidden my bike by then.', 'I will hide my bike by then.', 'I will hidden my bike by then.', 'I will not have hide my bike by then.'], 'correct' => 0],
        ['text' => 'By 2050, scientists _____ a new method. اختر المناسب للجملة:', 'options' => ['Will be found', 'Will be founding', 'Will have found', 'Will have finding'], 'correct' => 2],
        ['text' => 'You __ __ __ a new piece of decoration at the wall by midday. اختر المناسب لجملة:', 'options' => ['’ll have put up', 'Will putting up', '’ve put up', '’ll have putted up'], 'correct' => 0],
        ['text' => 'Sarah __ ___ cut her hair before 9:am. اختر المناسب لجملة:', 'options' => ['Will no', 'Will not have', 'Will not be', 'Not have'], 'correct' => 1],
        ['text' => 'We will have ____ the client’s offer before the meeting. اختر المناسب للجملة:', 'options' => ['Preparing', 'prepared', 'Be prepared', 'prepare'], 'correct' => 1],
        ['text' => '( Hatem -all –his- have – will- money - spent) اعد ترتيب الجملة:', 'options' => ['Hatem have will spent all his money.', 'Hatem will have spent all his money.', 'Hatem will have spent his money all.', 'Hatem will have spent all money his.'], 'correct' => 1],
        ['text' => 'يمكننا استبدال اسم الفاعل في الجملة السابقة (Hatem will have spent all his money) بـ:', 'options' => ['She', 'I', 'He', 'We'], 'correct' => 2],
        ['text' => 'حدد الخطأ في الجملة وصححه ان وجد: (Ahmed and Mohammed will have go on a long hike before the sunset)', 'options' => ['نضع has بدل have', 'نضع التصريف الثالث وهو Gone بدل go', 'نضع But بدل and', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'يمكننا تغيير اسم الفاعل في الجملة السابقة (Ahmed and Mohammed will have gone on a long hike before the sunset) الى ضمير الفاعل:', 'options' => ['We', 'They', 'I', 'He'], 'correct' => 1],
        ['text' => 'لماذا استخدمنا have وليس Has في الجملة السابقة؟', 'options' => ['لان الفاعل جمع', 'لان بعد will يأتي فعل مجرد والمجرد هو have وليس Has', 'لان الزمن مستقبل', 'كلاهما صحيح في الجملة have او Has'], 'correct' => 1],
        ['text' => '_____ (you\ explore) a new cave in the mountain? اختر المناسب للسؤال:', 'options' => ['Will have explored you', 'You will have explored', 'Will you have explored', 'None'], 'correct' => 2],
        ['text' => 'حدد نوع الفعل في السؤال السابق:', 'options' => ['فعل متعدي Transitive يحتاج الى مفعول به لكي تكون الجملة مفيدة', 'فعل لازم Intransitive لا يحتاج الى مفعول به لان الجملة مفيدة', 'Modal', 'Irregular'], 'correct' => 0],
        ['text' => 'ما هو الفعل في السؤال السابق؟', 'options' => ['A new', 'Explore', 'You', 'The mountain'], 'correct' => 1],
        ['text' => 'حدد المفعول به في السؤال السابق:', 'options' => ['A new', 'The mountain', 'A new cave', 'Explored'], 'correct' => 2],
        ['text' => 'لماذا وضعنا أداة التعريف (the) قبل كلمة mountain في السؤال السابق؟', 'options' => ['لان الجبل معروف للمخاطب والمتحدث.', 'لان كلمة Mountain مفرد', 'لان كلمة Mountain تبدأ بحرف ساكن', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'My sister ___ (not \cook) a new recipe by 7:pm. اختر المناسب للجملة:', 'options' => ['Won’t have cooked', 'Willnt have cooked', 'Won’t has cooked', 'Won’t have cook'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (Fahad will have installed a firewall on the system before he leaves work.)', 'options' => ['فهد ثبت نظام حماية على النظام قبل (هو) ان يغادر.', 'فهد سوف يكون قد ثبت نظام حماية على النظام قبل (هو) ان يغادر.', 'فهد يثبت نظام حماية على النظام قبل (هو) ان يغادر.', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'Nour will have __her fear. اختر المناسب للجملة التالية:', 'options' => ['Overcame', 'Overcome', 'Overcomed', 'Overcomes'], 'correct' => 1],
        ['text' => 'The patient will have ___ gratitude towards doctors. اختر الإجابة الصحيحة لـ:', 'options' => ['Express', 'Expressed', 'Expressing', 'Expresses'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (امي سوف تكون قد علقت الرفوف قبل المساء):', 'options' => ['My sister will have put up the shelves before evening.', 'My mother will have put up the shelves before evening.', 'My mother will be putting up the shelves before evening.', 'My sister will put up the shelves before evening.'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت لزمن المستقبل التام في قاعدة (be +going to):', 'options' => ['Subject + be + going to + have + v3 + object\complement.', 'Subject + be + going to + be + v1+ing + object\complement.', 'Subject + be + going to + have + v1 + object\complement.', 'Subject + be + going to +v1 + object\complement.'], 'correct' => 0],
        ['text' => 'She is going to have __ her studies by the time she turns 25. اختر المناسب للجملة:', 'options' => ['Complete', 'Completing', 'Completed', 'Completes'], 'correct' => 2],
        ['text' => 'He ____ written his book before the conference. اختر المناسب للجملة:', 'options' => ['Going to have', 'Is going to', 'Is going to have', 'Have'], 'correct' => 2],
        ['text' => 'we are going to __ planted new flowers in the garden by spring. اختر المناسب للجملة:', 'options' => ['Has', 'Have', 'Had', 'be'], 'correct' => 1],
        ['text' => 'اختر تكوين( النفي) لزمن المستقبل التام في قاعدة (be +going to):', 'options' => ['Subject + be + not+ going to + have + v3 + object\complement.', 'Subject + not+ be + going to + have + v3 + object\complement.', 'Subject + be + not+ going to + have + v1 + object\complement.', 'Subject + be + not+ going to +v1+ object\complement.'], 'correct' => 0],
        ['text' => 'اختر الترجمة( حسب الدرس) لـ be + going to:', 'options' => ['سوف', 'ب', 'راح', 'سوف يكون'], 'correct' => 2],
        ['text' => 'اختر النفي الصحيح للجملة: She is going to have travelled to five different countries by the end of the summer.', 'options' => ['She is not going to have travelled to five different countries by the end of the summer.', 'She is going to have not travelled to five different countries by the end of the summer.', 'She not is going to have travelled to five different countries by the end of the summer.', 'She is going not to have travelled to five different countries by the end of the summer.'], 'correct' => 0],
        ['text' => 'اختر تكوين( السؤال) لزمن المستقبل التام في قاعدة (be +going to):', 'options' => ['Be + Subject + going to + have + v3 + object\complement?', 'Have + Subject + going to + be+ v3 + object\complement?', 'Going to + Subject + be + have + v3 + object\complement?', 'Be + Subject + going to + have + v3 + object\complement?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال المناسب لـ: Azzah is going to have completed her training program in two months.', 'options' => ['Is Azzah going to have completed her training program in two months?', 'Azzah is going to have completed her training program in two months?', 'Have Azzah is going to completed her training program in two months?', 'Be Azzah going to have completed her training program in two months?'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المستقبل التام (Future Perfect Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1075.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
