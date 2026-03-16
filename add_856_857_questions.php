<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;

function insertQuestionsForLesson($courseId, $lessonId, $questionsArr) {
    $lesson = Lesson::find($lessonId);
    if (!$lesson) {
        echo "Lesson $lessonId not found.\n";
        return;
    }

    $quiz = $lesson->quiz;
    if (!$quiz) {
        $quiz = Quiz::create([
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'title' => 'اختبار الدرس',
            'quiz_type' => 'lesson',
            'total_questions' => 0,
            'duration_minutes' => 30,
            'passing_score' => 50,
            'allow_retake' => true,
        ]);
        echo "Created quiz for lesson $lessonId\n";
    }

    $order = $quiz->questions()->count() + 1;
    $added = 0;

    foreach ($questionsArr as $q) {
        $existing = Question::where('lesson_id', $lessonId)
            ->where('question_text', $q['text'])
            ->first();

        if ($existing) continue;

        $type = $q['type'] ?? 'multiple_choice';
        $pairsJson = null;
        $correctAns = $q['correct'] ?? 'A';

        if ($type === 'drag_drop') {
            $pairsJson = json_encode($q['pairs'], JSON_UNESCAPED_UNICODE);
            $correctAns = 'A'; // Dummy value
        } else if ($type === 'true_false') {
             if (strtoupper($correctAns) == 'TRUE' || $correctAns === true || strtolower($correctAns) == "صح") $correctAns = 'A';
             else if (strtoupper($correctAns) == 'FALSE' || $correctAns === false || strtolower($correctAns) == "خطأ" || strtolower($correctAns) == "خطا") $correctAns = 'B';
        }

        $question = Question::create([
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $q['text'],
            'question_type' => $type,
            'difficulty' => 'medium',
            'points' => 1,
            'option_a' => $q['a'] ?? null,
            'option_b' => $q['b'] ?? null,
            'option_c' => $q['c'] ?? null,
            'option_d' => $q['d'] ?? null,
            'correct_answer' => $correctAns,
            'matching_pairs' => $pairsJson,
        ]);

        $quiz->questions()->attach($question->id, ['order_index' => $order++]);
        $added++;
    }

    $quiz->update(['total_questions' => $quiz->questions()->count()]);
    echo "Added $added questions to lesson $lessonId. Total: " . $quiz->questions()->count() . "\n";
}

$q856 = [
    ["text" => "ما معنى علامات ترقيم في اللغة الإنجليزية؟", "a" => "Linking words", "b" => "Prepositions", "c" => "Punctuation", "d" => "لا شيء مما سبق", "correct" => "C"],
    [
        "type" => "drag_drop",
        "text" => "صل كل نوع من أنواع علامات الترقيم بمعناه:",
        "pairs" => [
            ["left" => "Capitalization", "right" => "الحروف الكبيرة"],
            ["left" => "Full stop", "right" => "نقطة"],
            ["left" => "Question mark", "right" => "علامة استفهام"],
            ["left" => "Comma", "right" => "الفاصلة"],
            ["left" => "Apostrophe", "right" => "الفاصلة العلوية"],
            ["left" => "Exclamation mark", "right" => "علامة تعجب"],
            ["left" => "Colon", "right" => "نقطتين فوق بعض"]
        ]
    ],
    ["text" => "أي نوع من علامات الترقيم نستخدم في نهاية السؤال؟", "a" => "Capitalization", "b" => "Full stop", "c" => "Question mark", "d" => "Comma", "correct" => "C"],
    ["text" => "أي نوع من علامات الترقيم نستخدم في نهاية الجملة؟", "a" => "Capitalization", "b" => "Full stop (period)", "c" => "Question mark", "d" => "Comma", "correct" => "B"],
    ["text" => "أي نوع من علامات الترقيم نستخدم في نهاية الجملة التي تحتوي على كلمات التعجب؟", "a" => "Capitalization", "b" => "Exclamation mark", "c" => "Question mark", "d" => "Comma", "correct" => "B"],
    ["text" => "ما الذي نستخدمه في نهاية الجملة المركبة (الجملة التي لم تنتهي).", "a" => "النقطة", "b" => "الفاصلة", "c" => "علامة استفهام", "d" => "علامة تعجب", "correct" => "B"],
    ["text" => "ما الذي نستخدمه اذا انتهت الجملة بالكامل (جملة منفصلة وليست مركبة).", "a" => "النقطة", "b" => "الفاصلة", "c" => "علامة استفهام", "d" => "علامة تعجب", "correct" => "A"],
    ["text" => "اختر علامة الترقيم المناسبة للجملة التالية: (abdullah went to jerusalemعبدالله ذهب الى القدس )", "a" => "Abudullah went to Jerusalem?", "b" => "Abudullah went to Jerusalem.", "c" => "Abudullah went to Jerusalem!", "d" => "Abudullah went to jerusalem.", "correct" => "B"],
    ["text" => "اختر علامة الترقيم المناسبة للجملة التالية: (where are you going nowاين انت ذاهب الان )", "a" => "Where are you going now.", "b" => "Where are you going now?", "c" => "Where are you going now.", "d" => "Where are you going now!", "correct" => "B"],
    ["type" => "true_false", "text" => "اسماء العلم نقوم بكتابة اول حرف بها (صغير small)", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["text" => "ما هو ضمير الفاعل الذي يكتب دائما (كبير Capital) مهما كان موقعه في الجملة؟", "a" => "We", "b" => "You", "c" => "I", "d" => "She", "correct" => "C"],
    ["type" => "true_false", "text" => "يجب كتابة اول حرف في أسماء الألقاب مثل (dr) بحرف صغير.", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["type" => "true_false", "text" => "الخطأ في جملة : (abdullkareem is a scientist. عبد الكريم يكون عالم) الاسم عبد الكريم يجب ان يكون اول حرف من الحروف الكبيرة لأنه اسم شخص.", "a" => "صح", "b" => "خطا", "correct" => "A"],
    ["text" => "ما الخطأ في جملة : (we are grateful. نحن ممتنين او شاكرين)", "a" => "لا نضع نقطة في اخرها لأنها ليست جملة", "b" => "الضمير (We) يجب ان يبدأ بحرف كبير لانه في بداية الجملة", "c" => "كلمة (Grateful) يجب ان تبدا بحرف كبير", "d" => "لا يوجد خطأ في الجملة", "correct" => "B"],
    ["text" => "ما الخطأ في جملة : ( When did the accident happen. متى حدث الحادث )", "a" => "نضع اخر الجملة علامة تعجب (!)", "b" => "نضع اخرها علامة استفهام لانها عبارة عن سؤال(؟)", "c" => "لا نضع أداة السؤال (When) بحروف كبيرة", "d" => "لا يوجد خطأ في الجملة", "correct" => "B"],
    ["type" => "true_false", "text" => "لا نضع علامة استفهام في اخر جملة الطلب لأننا لا نعتبرها سؤال مثل (Could I have your pen)", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["text" => "ما هي علامة الترقيم التي تستخدم لفصل الكلام( الجملة) المركبة وفصل الكلمات.", "a" => "Apostrophe", "b" => "Comma", "c" => "Full stop", "d" => "لا شيء مما سبق", "correct" => "B"],
    ["text" => "اختر من علامات الترقيم المناسبة في جملة على يمين وعلى يسار كلمة ( هدى ): (Ismael _ Huda _ Mona and Khalid are doctors.)", "a" => "فاصلة", "b" => "نقطة", "c" => "علامة تعجب", "d" => "علامة استفهام", "correct" => "A"],
    ["type" => "true_false", "text" => "عند مخاطبة شخص ما فإننا نستخدم (فاصلة علوية Apostrophe) مثل: Khalid’ take this.", "a" => "صح", "b" => "خطأ", "correct" => "B"], // It should be comma, not apostrophe.
    ["text" => "استخدمنا الفاصلة هنا ل (Ahmed,give me my key)في جملة", "a" => "لأنه يذكر قائمة", "b" => "لأننا توقفنا في الكلام", "c" => "لتقسيم الجملة الى قسمين", "d" => "لا شيء مما سبق", "correct" => "B"],
    ["type" => "true_false", "text" => "نسمي الفاصلة السفلية (comma, ) ونسمي الفاصلة العلوية (apostrophe’).", "a" => "صح", "b" => "خطأ", "correct" => "A"],
    ["text" => "نستخدم الفاصلة العلوية (’) في جميع ما يلي ما عدا", "a" => "ا – أسماء الاشارة", "b" => "ب – الاختصارات ( اذا اختصرنا حرف من الكلمة )", "c" => "ج – الملكية", "correct" => "A"],
    ["text" => "اختر علامات الترقيم الصحيحة لجملة: (Im reading a poetry now.انا اقرا شعر الان )", "a" => "I,m reading a poetry now.", "b" => "I’m reading a poetry now.", "c" => "I’m reading a poetry now?", "d" => "I’m reading a poetry now", "correct" => "B"],
    ["text" => "ما الخطأ في الجملة (She doesn,t know the answer.)", "a" => "ضمير الفاعل (She) لا نكتبه بحروف كبيرة", "b" => "نكتب فاصلة علوية وليس سفلية لكلمة (doesn’t) لأنها اختصار لحرف (o)", "c" => "نكتب علامة استفهام اخر الجملة.", "d" => "لا يوجد خطأ في علامات الترقيم", "correct" => "B"],
    ["text" => "في جملة ( Faris’s mobile is on the sofa. تلفون فارس يكون على الكنبة ) وضعنا فاصلة علوية لانه", "a" => "اختصار للأفعال المساعدة", "b" => "يوجد ملكية هنا وهو ان فارس يمتلك تلفون", "c" => "استخدامها هنا خطأ من الأساس", "d" => "لأنه يوجد نفي في الجملة", "correct" => "B"],
    ["text" => "في جملة ( I don’t have any coins. أنا لا امتلك أي عملات نقدية ) وضعنا فاصلة علوية لأنه", "a" => "اختصار للأفعال المساعدة", "b" => "يوجد ملكية هنا وهو ان فاتن تمتلك الكتاب", "c" => "استخدامها هنا خطأ من الأساس", "d" => "لأنه يوجد نفي في الجملة واختصرنا حرف (o)", "correct" => "D"],
    ["text" => "في جملة (Wow it’s beautiful) نضع اخرها", "a" => "نقطة ( .)", "b" => "علامة استفهام (؟)", "c" => "علامة تعجب (!)", "d" => "لا شيء مما سبق", "correct" => "C"],
    ["type" => "true_false", "text" => "بإمكاننا ان نستخدم (:) لتوضيح السبب .", "a" => "صح", "b" => "خطأ", "correct" => "A"], // Colon can be used to introduce an explanation.
    ["text" => "اختر علامة الترقيم الأنسب في جملة: (I didn’t go to school --- I was ill.انا لم اذهب للمدرسة --- انا كنت مريض)", "a" => "نقطة (.)", "b" => "فاصلة (,)", "c" => "نقطتان فوق بعض (:)", "d" => "لا شيء مما سبق", "correct" => "C"], // or semicolon/comma with conjunction but colon is usually an option here to explain reason.
    ["text" => "في جملة (I need a lot of things: milk, sugar, and cooking oil) استخدمنا نقطتان فوق بعض لأن", "a" => "توضيح السبب", "b" => "لعمل قائمة او توضيح", "c" => "فصل الجمل", "d" => "لا شيء مما سبق", "correct" => "B"],
    ["text" => "أي نوع من علامات الترقيم التي نستخدمها في حال كتبنا اسم شخص؟", "a" => "Full stop", "b" => "Capitalization", "c" => "Comma", "d" => "Question mark", "correct" => "B"],
    ["type" => "true_false", "text" => "من هنا أسئلة الأستاذ براء\nنستخدم الفاصلة العلوية في تقليص الحروف في الكلمة وتبديل حرف بالفاصلة", "a" => "صح", "b" => "خطأ", "correct" => "A"], // Not ideal text formatting, but I combined the heading to avoid losing questions.
    ["text" => "اختصر الحرف بالفاصلة العلوية في الجملة (It is nice. انه يكون رائع)", "a" => "It’s nice", "b" => "Its’ nice", "c" => "It is’s nice", "correct" => "A"],
    ["text" => "اختصر الحرف بالفاصلة العلوية في الجملة (I am khalid. انا أكون خالد)", "a" => "I’m Khalid", "b" => "Im’ Khalid", "c" => "I am’m Khalid", "correct" => "A"],
    ["text" => "كيف نضيف الفاصلة العلوية في جملة (muslims mosque. مسجد المسلمين)", "a" => "Muslims’ mosque", "b" => "Muslim’s mosque", "c" => "Muslimss’ mosque", "correct" => "A"],
    ["text" => "كيف نضيف الفاصلة العلوية في جملة (The garden fountain. نافورة الحديقة)", "a" => "The garden’s fountain", "b" => "The gardens’ fountain", "c" => "The garden is fountain", "d" => "The gardens’s fountain", "correct" => "A"],
    ["text" => "نختصر الحقبة ( الثمانينات) 1980", "a" => "’80s", "b" => "80,", "c" => "1980s", "correct" => "A"], // Or 80s
    ["type" => "true_false", "text" => "اذا كان اخر الاسم حرف (S) مثل(Marcus) فإننا نستطيع ان نقول Marcus’ pen او Marcus’s pen", "a" => "صح", "b" => "خطأ", "correct" => "A"],
    ["text" => "كيف نضيف الفاصلة العلوية في جملة (Lucas Gym نادي لوكاس)", "a" => "ا - Lucas’ gym", "b" => "ب – Lucas’s gym", "c" => "ج – ا + ب", "d" => "د – لا يوجد لها اختصار", "correct" => "C"],
    ["text" => "اختصر الحرف في جملة (That is my phone. ذلك يكون جوالي)", "a" => "That is’s my phone", "b" => "That’s my phone", "c" => "That’ is my phone", "correct" => "B"],
    ["text" => "اختصر الحرف في جملة (those are my cousins. أولئك يكونون أولاد عمي)", "a" => "Those’re my cousins.", "b" => "Those are’ my cousins.", "c" => "لا يوجد اختصار للجملة أساسا", "correct" => "C"], // Technically "Those're" exists in spoken but usually avoided in writing. Assuming "C" is intended or A.
    ["type" => "true_false", "text" => "نستخدم الفاصلة العلوية للتعبير عن الملكية ( أسماء الملكية)", "a" => "صح", "b" => "خطأ", "correct" => "A"],
    ["type" => "true_false", "text" => "نستطيع ان نستخدم بدل أسماء الملكية ضمائر الملكية (I - we - you) ونبدلهم بأسماء ونضيف عليهم فاصلة علوية", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["text" => "اختصر الحرف في جملة (Saleh table. طاولة صالح)", "a" => "Saleh is table", "b" => "Saleh’s table", "c" => "Salehs’ table", "correct" => "B"]
];

$q857 = [
    ["text" => "This car is my car, and that car is -----.", "a" => "Ahmed", "b" => "Ahmeds", "c" => "Ahmed’s", "d" => "Ahmeds’", "correct" => "C"],
    ["text" => "My ------- favourite food is pizza. They both share one every week.", "a" => "Brother’s", "b" => "Brothers’", "c" => "Brother", "d" => "brothers", "correct" => "B"], // both share -> brothers'
    ["text" => "My ----- house is over there. They live just across the street.", "a" => "Cousins’", "b" => "Cousins", "c" => "Cousin’s", "d" => "Cousin", "correct" => "A"], // They live -> Cousins'
    ["text" => "-------- bicycle is broken so he’s going to take a bus to work today.", "a" => "Fari’s", "b" => "Faris", "c" => "Faris", "d" => "Faris’", "correct" => "D"], // Or Faris's, assuming D is Faris'
    ["text" => "The ------ teacher is standing at the front of their classroom.", "a" => "Student’s", "b" => "Students’", "c" => "Students", "d" => "Student", "correct" => "B"], // their classroom -> Students' teacher or Students' depending on context. Wait, "The students' teacher"
    ["text" => "Next ------- English class will begin at two o’clock, not three o’clock.", "a" => "Thursdays’", "b" => "Thursdays", "c" => "Thursday’s", "d" => "Thursday", "correct" => "C"],
    ["text" => "Those are my ----- toys on the floor. They didn’t put them away.", "a" => "Children", "b" => "Children’s", "c" => "Child", "d" => "Childrens’", "correct" => "B"],
    ["text" => "What is your ------- name?", "a" => "Friend", "b" => "Friends’", "c" => "Friends", "d" => "Friend’s", "correct" => "D"],
    ["text" => "What sentence uses apostrophe properly?", "a" => "Her husbands’ wallet was full of curious items.", "b" => "Her husband’s wallet was full of curious items.", "c" => "Her husbands wallet was full of curious items.", "d" => "Her husband wallet was full of curious items.", "correct" => "B"],
    ["text" => "What sentence uses apostrophe properly?", "a" => "The team of girls’ won the game.", "b" => "The team of girl’s won the game.", "c" => "The team of girls won the game.", "d" => "The team of girl won the game.", "correct" => "C"], // Wait, "The team of girls won the game." doesn't use apostrophe. The other options are wrong grammatically. So C is right.
    ["text" => "The chair is missing one of ----- legs.", "a" => "Its’", "b" => "It’s", "c" => "Its", "d" => "It", "correct" => "C"],
    ["text" => "------- never too late to do something new.", "a" => "It’s", "b" => "Its’", "c" => "It", "d" => "Its", "correct" => "A"],
    ["text" => "What do you think ----- going?", "a" => "You’re", "b" => "You", "c" => "Your", "d" => "Yours", "correct" => "A"],
    ["text" => "They have a hard time understanding why they ------ use their cellphones in the restaurant.", "a" => "Cant", "b" => "Can’t", "c" => "Cantnt", "d" => "Cantn’t", "correct" => "B"],
    ["text" => "The news said that ----- going to snow tomorrow.", "a" => "Its’", "b" => "It’s", "c" => "It", "d" => "I’ts", "correct" => "B"],
    ["text" => "He always takes ----- of his family.", "a" => "Picture’s", "b" => "Pictures’", "c" => "Picture", "d" => "Pictures", "correct" => "D"],
    ["text" => "I have never seen ----- before.", "a" => "It’s", "b" => "It", "c" => "Its’", "d" => "Its", "correct" => "B"], // "I have never seen it before."
    ["text" => "-------- leaving in five minutes.", "a" => "We’re", "b" => "Wer’e", "c" => "Were’", "d" => "were", "correct" => "A"],
    ["text" => "The ------- nice today.", "a" => "Weather", "b" => "Weathers", "c" => "Weather’s", "d" => "Weathers’", "correct" => "C"], // Weather's = Weather is
    ["text" => "-------- my best friend.", "a" => "He", "b" => "He’s", "c" => "His", "d" => "him", "correct" => "B"],
    ["text" => "I went to ------ apartment.", "a" => "Dhiaa", "b" => "Dhiaas’", "c" => "Dhiaa’s", "d" => "Dhiaa is", "correct" => "C"],
    ["text" => "It was the ------ fault.", "a" => "Bank", "b" => "Bank’s", "c" => "banks’", "d" => "Banks", "correct" => "B"],
    ["text" => "The ------ were very hard.", "a" => "Question", "b" => "Question’s", "c" => "Questions’", "d" => "Questions", "correct" => "D"],
    ["text" => "The kids swimming all day and ------ been tired by the end.", "a" => "Mustve", "b" => "Mustv’e", "c" => "Must’ve", "d" => "Must", "correct" => "C"],
    ["text" => "------ make lots of money one day because she works really hard.", "a" => "She’ll", "b" => "Shel’l", "c" => "Shell", "d" => "Sh’ell", "correct" => "A"]
];

insertQuestionsForLesson(6, 856, $q856);
insertQuestionsForLesson(6, 857, $q857);

