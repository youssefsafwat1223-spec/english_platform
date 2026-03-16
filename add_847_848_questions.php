<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;

$courseId = 6;

function insertQuestionsForLesson($lessonId, $questionsArr) {
    global $courseId;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) {
        echo "Lesson $lessonId not found.\n";
        return;
    }

    $quiz = $lesson->quiz;
    if (!$quiz) {
        $quiz = Quiz::create([
            'course_id' => $courseId, // Uses global var
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
            $correctAns = 'A'; // Dummy
        } else if ($type === 'true_false') {
             // ensure correct answer format for true false
             if (strtoupper($correctAns) == 'TRUE' || $correctAns === true) $correctAns = 'A';
             else if (strtoupper($correctAns) == 'FALSE' || $correctAns === false) $correctAns = 'B';
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

$q847 = [
    [
        "type" => "drag_drop",
        "text" => "صل الكلمة بمعناها:",
        "pairs" => [
            ["left" => "prefix", "right" => "بادئة تأتي قبل الكلمة (في بدايتها)"],
            ["left" => "suffix", "right" => "لاحقة تنتهي بها الكلمة"],
            ["left" => "root", "right" => "جذر (أصل الكلمة)"]
        ]
    ],
    ["text" => "اختر التكوين الصحيح للكلمة التي تحتوي على (بادئة):", "a" => "Root + prefix", "b" => "Prefix + root", "c" => "Prefix + root +prefix", "d" => "لا شيء مما سبق", "correct" => "B"],
    ["text" => "اختر التكوين الصحيح للكلمة التي تحتوي على (لاحقة ):", "a" => "Suffix + root", "b" => "Root +suffix +suffix", "c" => "Root +suffix", "d" => "لا شيء مما سبق", "correct" => "C"],
    ["text" => "ما هي الكلمة التي تعتبر الأصل او(الأساس) التي تكون بدون إضافات ونستطيع إضافة ملحق عليها؟", "a" => "Root", "b" => "Suffix", "c" => "Prefix", "correct" => "A"],
    ["text" => "اخترمن الإضافات التي تستخدم ك (بادئة):", "a" => "Ful – ly – less – in – im", "b" => "Im – il – pre – post – super", "c" => "Ex – mid – ly – ee –er", "d" => "جميع ما سبق", "correct" => "B"],
    ["text" => "اختر من الإضافات التي تستخدم ك (لاحقة) :", "a" => "Er –ful – able – ing – ial – less", "b" => "in –ful – pre – ing – ial – less", "c" => "un –ful – able – dis – ial – less", "d" => "mid –ful – ir – ing – ial – super", "correct" => "A"],
    ["text" => "ما هو معنى البادئة (Super)؟", "a" => "كبير او صغير", "b" => "فوق او قوي", "c" => "كبير (حجم ) او اعلى (قوة)", "d" => "قوي او ضعيف", "correct" => "C"],
    ["text" => "عند إضافة البادئة (Super ) لكلمة (human):", "a" => "تعني انسان كبير", "b" => "يصبح معناها انسان قوي", "c" => "يصبح معناها بشر", "d" => "تبقى كما هي انسان", "correct" => "B"],
    ["text" => "ما هو معنى البادئة (ir)؟", "a" => "لا او غير", "b" => "مهم او غير مهم", "c" => "بعد او قبل", "correct" => "A"],
    ["text" => "عند إضافة البادئة (ir) كلمة (relevant متعلق بالموضوع) تصبح (Irrelevant) فما معناها ؟", "a" => "غير متعلق بالموضوع", "b" => "الموضوع", "c" => "معناها يبقى كمان هو", "correct" => "A"],
    ["text" => "ما هي البادئة التي ممكن ان نضيفها لكلمة (سعيد Happy) لتصبح غير سعيد؟", "a" => "Im", "b" => "Un", "c" => "Ly", "d" => "Ir", "correct" => "B"],
    ["type" => "true_false", "text" => "لا يمكن استخدام الإضافة (En) (كلاحقة suffix وبادئة Prefix)؟", "a" => "صح", "b" => "خطا", "correct" => "B"],
    ["text" => "عند إضافة البادئة (En) لكلمة (courage شجاعة) يتحول الاسم الى", "a" => "فعل", "b" => "حال", "c" => "صفة", "d" => "مفعول به", "correct" => "A"],
    ["text" => "كلمة Force تعني قوة (وعندما نضيف قبلها البادئة (en) تصبح (Enforce) فما يصبح معناها:", "a" => "يقوى", "b" => "يضعف", "c" => "يجبر", "d" => "ضعف", "correct" => "C"],
    ["text" => "كلمة (يوافق Agree) ما البادئة التي يمكن اضافتها لتعطيني عكسها وتصبح (يعارض \ لا يوافق)؟", "a" => "Ir", "b" => "Dis", "c" => "In", "d" => "Im", "correct" => "B"],
    ["text" => "جذر الكلمة هو(Misunderstanding)", "a" => "Mis", "b" => "Understand", "c" => "Ing", "d" => "Understanding", "correct" => "B"],
    ["text" => "ما البادئة التي نضيفها لكلمة (خلوي Cellular) ليصبح معناها ( احادي الخلية )؟", "a" => "anti", "b" => "uni", "c" => "un", "d" => "im", "correct" => "B"],
    ["text" => "ما هي البادئة في كلمة ( Misunderstanding) هو", "a" => "Mis", "b" => "Understand", "c" => "Ing", "d" => "Understanding", "correct" => "A"],
    ["text" => "ما هي اللاحقة في كلمة ( Misunderstanding) هو", "a" => "Mis", "b" => "Understand", "c" => "Ing", "d" => "Understanding", "correct" => "C"],
    ["text" => "ما معنى البادئة (Re)؟", "a" => "الإعادة", "b" => "الانتهاء", "c" => "البدء", "d" => "التوقف", "correct" => "A"],
    ["text" => "ما معنى (Replay)؟", "a" => "يعيد التشغيل", "b" => "يبدأ اللعب", "c" => "ينهي اللعب", "d" => "يتوقف عن اللعب", "correct" => "A"],
    [
        "type" => "drag_drop",
        "text" => "صل بين كل بادئة ومعناها:",
        "pairs" => [
            ["left" => "pre", "right" => "قبل"],
            ["left" => "ex", "right" => "سابق"],
            ["left" => "post", "right" => "بعد"]
        ]
    ],
    ["text" => "كلمة (teacher) تعني معلم اما (ex-teacher)؟", "a" => "معلم جديد", "b" => "معلم صغير", "c" => "معلم سابق", "d" => "معلم متميز", "correct" => "C"],
    ["text" => "كلمة (graduate) تعني يتخرج او تخرج اما (post-graduate)؟", "a" => "ما قبل التخرج", "b" => "ما بعد التخرج", "c" => "اثناء التخرج", "d" => "بداية التخرج حتى نهايتها", "correct" => "B"],
    ["text" => "كلمة (school) تعني مدرسة اما (pre-school)؟", "a" => "مرحلة ما قبل المدرسة", "b" => "مرحلة ما بعد المدرسة", "c" => "اثناء المدرس", "d" => "اول أيام المدرسة", "correct" => "A"],
    ["text" => "اختر البادئة التي تعني (غير او لا)؟", "a" => "Post", "b" => "Mis", "c" => "il", "d" => "pre", "correct" => "C"],
    ["type" => "true_false", "text" => "كلمة ( illogical غير منطقي ) تحتوي فقط على (بادئة Prefix) وهي (il)", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["text" => "البادئة (Mid) تعني :", "a" => "اول", "b" => "وسط", "c" => "اخر", "d" => "بداية", "correct" => "B"],
    ["text" => "ما معنى كلمة (Midsummer)؟", "a" => "اول فصل الصيف", "b" => "اخر فصل الصيف", "c" => "منتصف فصل الصيف", "d" => "طوال الصيف", "correct" => "C"],
    ["text" => "كلمة (يعلم teach) عند إضافة اللاحقة (er) تصبح (Teacher معلم) فان فائدة اللاحقة (er) هي", "a" => "تحويل الفعل الى اسم (فاعل)", "b" => "تحويل الفعل الى صفة", "c" => "تحويل الفعل الى حال", "correct" => "A"],
    ["text" => "كلمة (play يلعب) عند تحويلها الى اسم (فاعل) تصبح:", "a" => "enplay", "b" => "Playen", "c" => "Player", "d" => "Played", "correct" => "C"],
    ["text" => "فائدة إضافة اللاحقة (Ly) للجذر هي", "a" => "تحويل الاسم الى فعل", "b" => "تحويل الصفة الى حال (ظرف)", "c" => "تحويل الفعل الى اسم", "d" => "تحويل الحال الى صفة", "correct" => "B"],
    ["text" => "ما معنى اللاحقة (Less)؟", "a" => "قبل او بعد", "b" => "بدون او لا", "c" => "وسط او اول", "correct" => "B"],
    ["text" => "كلمة (Help) تعني مساعدة فما معنى كلمة ( بلا مساعدة)؟", "a" => "Helpful", "b" => "Helpless", "c" => "Helps", "d" => "Helped", "correct" => "B"],
    ["text" => "كلمة (pain) تعني (ألم) فما اللاحقة التي ممكن ان نضيفها لها لنحولها الى صفة (مؤلم)؟", "a" => "Less", "b" => "ful", "c" => "Ness", "d" => "Ly", "correct" => "B"],
    ["text" => "اللفظ الصحيح لكلمة ( خاص Special) هو", "a" => "سبيسال", "b" => "سبيكال", "c" => "سبيشال", "d" => "سبيشنال", "correct" => "C"],
    ["text" => "عند وجود حرف (c) قبل اللاحقة (ial) فاننا نلفظ المقطع (ial)", "a" => "كال", "b" => "شال", "c" => "سال", "d" => "لا شيء مما سبق", "correct" => "B"],
    ["type" => "true_false", "text" => "لا يمكن إضافة (اكثر من ملحق ) بالكلمة سواء اكثر من بادئة او اكثر من لاحقة؟", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["text" => "حدد اللاحقة في كلمة ( Managerial)؟", "a" => "Manage", "b" => "er", "c" => "ial", "d" => "er \ ial", "correct" => "D"],
    ["type" => "true_false", "text" => "كلمة (بنجاح successfully) تحتوي على لاحقة واحدة؟", "a" => "صح", "b" => "خطأ", "correct" => "B"],
    ["type" => "true_false", "text" => "الكلمة ( Interviewer) تعني الشخص الذي يتم اجراء المقابلة (معه \ عليه)؟", "a" => "صح", "b" => "خطا", "correct" => "B"],
    ["type" => "true_false", "text" => "الكلمة ( Interviewee) تعني الشخص الذي يتم اجراء المقابلة (معه\ عليه )؟", "a" => "صح", "b" => "خطا", "correct" => "A"],
    ["text" => "كلمة ( gold) تعني ذهب ، عند إضافة اللاحقة (en) تصبح (Golden) ومعناها؟", "a" => "ذهب", "b" => "فضة", "c" => "ذهبي", "d" => "فضي", "correct" => "C"],
    ["text" => "ما معنى ( Antimissile) ؟", "a" => "صواريخ", "b" => "انتاج صواريخ", "c" => "مضاد صواريخ", "d" => "لا شيء مما سبق", "correct" => "C"],
    ["text" => "ما تعني البادئة (Anti)؟", "a" => "غير", "b" => "لا", "c" => "بدون", "d" => "مضاد \ عكس", "correct" => "D"],
    ["text" => "كلمة (dependent) تعني (شخص متواكل يعتمد على الاخرون) اما عند إضافة البادئة (In) يصبح معناها:", "a" => "شخص حر", "b" => "شخص متواضع", "c" => "شخص مستقل (غير اعتمادي)", "d" => "لا يتغير شيء في الكلمة", "correct" => "C"],
    ["text" => "كلمة Polite تعني (مؤدب) فما عكسها ( غير مؤدب )؟", "a" => "inpolite", "b" => "impolite", "c" => "irpolite", "d" => "unpolite", "correct" => "B"],
    ["text" => "ما البادئة التي نضيفها للكلمة (bodies اجسام) حتى يصبح معناها (اجسام مضادة)؟", "a" => "anti", "b" => "uni", "c" => "un", "d" => "im", "correct" => "A"],
    ["text" => "تعليم education عند تحويلها لصفة ( تعليمي ) فإننا نضيف لها اللاحقة؟", "a" => "al", "b" => "ist", "c" => "able", "d" => "ing", "correct" => "A"],
    ["text" => "حدد أيهما الصفة وايهما الاسم من بين الكلمات التالية (cultural – culture)؟", "a" => "Culture ثقافة (اسم)– cultural ثقافي (صفة )", "b" => "Cultureثقافي (صفة ) - culturalثقافة (اسم)", "c" => "Culture ثقافة (اسم)- culturalثقافة ( اسم)", "d" => "Cultureثقافي ( صفة) – culturalثقافي ( صفة )", "correct" => "A"],
    ["text" => "ما الذي تفعله اللاحقة (ness) في الكلمة؟", "a" => "تحويل الاسم الى صفة", "b" => "تحويل الاسم لفعل", "c" => "تحويل الصفة لاسم", "d" => "تحويل الصفة لحال", "correct" => "C"],
    ["text" => "كلمة clear تعني نقي وواضح اما كلمة (Clearness) تعني ؟", "a" => "بشكل واضح ( ظرف)", "b" => "نقاء ووضوح ( اسم )", "c" => "غير نقي (صفة)", "d" => "ينقي (فعل)", "correct" => "B"],
    ["text" => "ما اللاحقة التي تدلل على القدرة او القابلية للقيام بشيء ما؟", "a" => "al", "b" => "able", "c" => "ist", "d" => "ness", "correct" => "B"],
    ["text" => "كلمة (capable) تعني؟", "a" => "القدرة", "b" => "بشكل قادر", "c" => "قادر على القيام بشيء ما", "d" => "يقدر", "correct" => "C"],
    ["text" => "ما هي وظيفة اللاحقة (ist)؟", "a" => "ا – تدل على التخصص في الشيء ( اسم تخصص)", "b" => "ب – تحويل الصفة الى فاعل", "c" => "ج – تحويل الاسم لفعل", "d" => "د – إضافة معنى للظرف", "correct" => "A"],
    ["text" => "ما معنى كلمة ( Journalist)؟", "a" => "يتصحف", "b" => "صحيفة", "c" => "شخص يعمل في الصحافة (صحفي)", "d" => "لا شيء مما سبق", "correct" => "C"],
    ["text" => "اللفظ الصحيح لكلمة ( Initial) هو", "a" => "انيتيال", "b" => "انتال", "c" => "انيشل", "d" => "انيتشل", "correct" => "C"],
];

$q848 = [
    ["text" => "What is the meaning of the word “prefix”?", "a" => "A word within a word", "b" => "A group of letters put before a root word which changes its meaning.", "c" => "A group of letters put after a root word which changes its meaning.", "d" => "A group of letters put in the middle of a root word which changes its meaning.", "correct" => "B"],
    ["text" => "What does the word “unhurt” mean?", "a" => "Hurt badly", "b" => "Hurt", "c" => "Not hurt", "correct" => "C"],
    ["type" => "true_false", "text" => "If you take away the prefix from “disagree”, the root word is agree.", "a" => "True", "b" => "False", "correct" => "A"],
    ["text" => "What do you do if you reread a book?", "a" => "Read it", "b" => "Read it for the first time", "c" => "Read it again", "d" => "Don’t read it", "correct" => "C"],
    ["type" => "true_false", "text" => "A suffix is a group of letters that you add to the start of a root word?", "a" => "True", "b" => "False", "correct" => "B"],
    ["text" => "Which of the following is a suffix?", "a" => "Pre", "b" => "Un", "c" => "Ed", "d" => "Ir", "correct" => "C"],
    ["text" => "Which of the following is not a suffix?", "a" => "Ing", "b" => "Ful", "c" => "Re", "d" => "ed", "correct" => "C"],
    ["text" => "Which suffix can you add to the end of “slow” to make a new word?", "a" => "Ful", "b" => "Able", "c" => "Ly", "d" => "Less", "correct" => "C"],
    ["text" => "Which suffix can you add to the end of “reason” to make a new word?", "a" => "Ful", "b" => "Able", "c" => "Ion", "d" => "Less", "correct" => "B"],
    ["text" => "Which of the following is the correct spelling? That dress is very ------.", "a" => "Colorfoll", "b" => "Colorful", "c" => "Coulorful", "d" => "Coulorfull", "correct" => "B"],
    ["text" => "The word “pre-war” means?", "a" => "Before the war", "b" => "After the war", "c" => "During the war", "d" => "At the beginning of the war", "correct" => "A"],
    ["text" => "If you wanted to say someone is not helpful, which word would you use?", "a" => "Inhelpful", "b" => "Helpfulless", "c" => "Unhelpful", "d" => "Helpful", "correct" => "C"],
    ["text" => "Which of these words means “not sure”?", "a" => "Undone", "b" => "No sure", "c" => "Uncertain", "d" => "Dissure", "correct" => "C"],
    ["text" => "If you take out the prefix and the suffix, what is left of the word “unemployed?”", "a" => "Unemploy", "b" => "Employed", "c" => "Employ", "d" => "Ploy", "correct" => "C"],
    ["text" => "Which suffix can’t add to the root word “box” to make a new word?", "a" => "Ful", "b" => "Es", "c" => "Ing", "correct" => "A"],
    ["text" => "Which prefix would you add to the word “finished” to show that there is still some work to be done?", "a" => "Un", "b" => "Mis", "c" => "Dis", "d" => "Im", "correct" => "A"],
    ["text" => "What prefix would you add to the word “view” to indicate that you see something before other people do?", "a" => "Re", "b" => "Dis", "c" => "Pre", "d" => "Un", "correct" => "C"],
    ["text" => "What suffix would you add to the word ‘blame” to show you have done nothing wrong?", "a" => "Ness", "b" => "Ed", "c" => "Less", "d" => "Full", "correct" => "C"],
    ["text" => "A prefix goes -------- of a word.", "a" => "At the beginning", "b" => "At the end", "c" => "In the middle", "correct" => "A"],
    ["text" => "A suffix goes ------- of a word.", "a" => "At the beginning", "b" => "At the end", "c" => "In the middle", "correct" => "B"],
    ["text" => "The suffix” less” means ------ “for example careless.”", "a" => "Full of", "b" => "Not", "c" => "Without", "correct" => "C"],
    ["text" => "I had to “retie” my shoe because it wasn’t tight enough. The word “retie” means ---------", "a" => "Tie again", "b" => "Without a tie", "c" => "Untie", "d" => "Does not tie", "correct" => "A"],
    ["text" => "The prefix “un” means--------", "a" => "Does not", "b" => "Without", "c" => "Not", "d" => "Full of", "correct" => "C"],
    ["text" => "The prefix “re” has two meanings -------", "a" => "Again/back", "b" => "Full of/in a certain way", "c" => "Not/ does not", "d" => "It doesn’t have two meanings", "correct" => "A"],
    ["text" => "What is the root, base word, in the word “preheat”?", "a" => "Heat", "b" => "Pre", "c" => "Preheat", "d" => "Heatpre", "correct" => "A"],
];

insertQuestionsForLesson(847, $q847);
insertQuestionsForLesson(848, $q848);
