<?php

/**
 * Script to import questions for Lesson ID 1162 (Comprehensive Review 1)
 * php import_lesson_1162_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1162;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1162 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        // Imperatives
        ['text' => 'اختر التكوين الصحيح للإثبات لجمل الامر:', 'options' => ['V2 + object\complement.', 'V1 + object \ complement.', 'Subject + v3 + object\complement.', 'Subject + be + v1 + object\complement.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة:', 'options' => ['Cut me another piece of cake.', 'Another piece of cake cut me.', 'Cut me another cake of piece.', 'Cut me piece another of cake.'], 'correct' => 0],
        ['text' => 'حدد الخطأ في الجملة (gives me a ring) وصححه ان وجد:', 'options' => ['نضع الفعل give لأن gives عليها إضافات ومفروض ان تكون مجردة في صيغة الامر', 'نضع my وليس me', 'لا يوجد خطأ', 'يفضل ان لا يكون في الجملة مفعول به'], 'correct' => 0],
        ['text' => 'عندما تريد ان تطلب من (امك) ان تصب لك الشاي تقول:', 'options' => ['Pour the tea, please.', 'Mum, pour the tea, please.', 'Pour the tea.', 'None'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للنفي لجمل الامر:', 'options' => ['Don’t + V2 + object\complement', 'Don’t + V1 + object \ complement', 'Subject + don’t + v3 + object\complement', 'None'], 'correct' => 1],
        ['text' => 'الطريقة الصحيحة للإصرار على الامر في جملة (Bring bread):', 'options' => ['Does bring bread with you, please.', 'Do bring bread with you, please.', 'Bringing bread with you, please.', 'Bring bring bread with you, please.'], 'correct' => 1],

        // Modals
        ['text' => 'الأفعال الناقصة تعتبر أفعال أساسية في الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الأفعال الناقصة (Modal verbs) من الخيارات التالية:', 'options' => ['Must - may - should – have to', 'Is – am – are – was – were', 'Has – have – had', 'Do – did – does'], 'correct' => 0],
        ['text' => 'ما هو الفعل الناقص الذي يعبر عن النصيحة ومعناه (ينبغي)؟', 'options' => ['Can', 'Should', 'Must', 'Would'], 'correct' => 1],
        ['text' => 'يأتي التصريف الثاني للفعل V2 بعد الأفعال الناقصة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'احد استخدامات May / might للأفعال الناقصة هو:', 'options' => ['القدرة', 'الاحتمالية', 'النصيحة والمشورة', 'الالزام'], 'correct' => 1],
        [
            'text' => 'صل كل نوع من أنواع الأفعال الناقصة باستخدامه المناسب:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Should/had better/ought to', 'right' => 'Advice (النصيحة)'],
                ['left' => 'Can/could/may/let’s', 'right' => 'Permission/Suggestion'],
                ['left' => 'Must/have to', 'right' => 'Obligation (الالزام)'],
                ['left' => 'May/might/can/could', 'right' => 'Possibilities (الاحتمالات)'],
                ['left' => 'Must be/can be/could be', 'right' => 'Deduction (الاستنتاج)'],
                ['left' => 'Can/could', 'right' => 'Ability (القدرة)'],
            ]
        ],
        ['text' => 'نستخدم كلمة (Let’s) للتعبير عن اقتراح، فما أصلها؟', 'options' => ['Let us', 'Let is', 'Let was', 'Let has'], 'correct' => 0],
        ['text' => 'ما هي صيغة السؤال الصحيحة في الأفعال الناقصة؟', 'options' => ['Modal verb+subject+object?', 'Modal verb+subject+main verb (v1)+object?', 'Subject+model verb+main verb(v1)?', 'لاشيء مما سبق'], 'correct' => 1],

        // Wh Questions
        ['text' => 'اختر التكوين الصحيح للسؤال Wh:', 'options' => ['Interrogative pronoun + aux + subject + verb + object?', 'Aux + subject + verb + interrogative?', 'Interrogative + subject + verb + aux?', 'Interrogative + aux + subject + verb \complement+ object?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح في حال كانت (Be) هي الفعل الأساسي:', 'options' => ['Interrogative pronoun + be + subject?', 'Interrogative pronoun + do\does + subject + verb?', 'Interrogative pronoun + subject + verb?', 'Interrogative pronoun + has\have + subject + verb?'], 'correct' => 0],
        [
            'text' => 'صل ما بين الفعل المساعد والزمن المناسب:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Was / were', 'right' => 'الماضي المستمر'],
                ['left' => 'Is / am / are', 'right' => 'المضارع المستمر'],
                ['left' => 'Has / have', 'right' => 'المضارع التام'],
                ['left' => 'Had', 'right' => 'الماضي التام'],
            ]
        ],
        ['text' => '(__ mug is this?) الجواب (It is for Ahmed):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 2],
        ['text' => 'ما هي أداة الاستفهام التي تستخدم للسؤال عن المكان (أين)؟', 'options' => ['When', 'Where', 'Who', 'What'], 'correct' => 1],
        ['text' => 'اختر السؤال المناسب للجواب (to buy a new car):', 'options' => ['When did she save money?', 'Why did she save money?', 'What did she save to buy?', 'Why did she saved money?'], 'correct' => 1],
        ['text' => 'حدد الخطأ في الجملة (Where Nadin will spend her holiday?):', 'options' => ['نكتب spend بدل spent', 'نكتب what بدل where', 'نكتب will قبل Nadin', 'لا يوجد خطأ'], 'correct' => 2],

        // Comparison
        ['text' => 'متى نضيف (er + than) على الصفة؟', 'options' => ['عند مقارنة شيئيين او شخصين باستخدام صفة أحادية المقطع', 'عند مقارنة شخص او شيء بمجموعة باستخدام صفة أحادية المقطع', 'عند مقارنة شخصيين باستخدام صفة متعددة المقاطع', 'عند مقارنة شخص بمجموعة باستخدام صفة متعددة المقاطع'], 'correct' => 0],
        ['text' => 'متى نضيف (the + est) على الصفة؟', 'options' => ['عند مقارنة شيئيين او شخصين باستخدام صفة أحادية المقطع', 'عند مقارنة شخص او شيء بمجموعة باستخدام صفة أحادية المقطع', 'عند مقارنة شخصيين باستخدام صفة متعددة المقاطع', 'عند مقارنة شخص بمجموعة باستخدام صفة متعددة المقاطع'], 'correct' => 1],
        ['text' => 'متى نضيف (more + than) قبل الصفة؟', 'options' => ['عند مقارنة شيئين باستخدام صفة أحادية المقطع', 'عند مقارنة شخص بمجموعة باستخدام صفة أحادية المقطع', 'عند مقارنة شيئيين باستخدام صفة متعددة المقاطع', 'عند مقارنة شخص بمجموعة باستخدام صفة متعددة المقاطع'], 'correct' => 2],
        ['text' => 'متى نضيف (the most) قبل الصفة؟', 'options' => ['عند مقارنة شخصين باستخدام صفة أحادية المقطع', 'عند مقارنة شخص بمجموعة باستخدام صفة أحادية المقطع', 'عند مقارنة شخصين باستخدام صفة متعددة المقاطع', 'عند مقارنة شخص او شيء بمجموعة باستخدامهما'], 'correct' => 3],
        ['text' => 'ما الترجمة الصحيحة لـ (شقتي تكون اصغر من شقتك)؟', 'options' => ['My apartment is smaller than your apartment.', 'Your apartment is smaller than my apartment.', 'My apartment is bigger than your apartment.', 'Your apartment is bigger than my apartment.'], 'correct' => 0],
        ['text' => 'عند إضافة (er) للصفة (Heavy) تصبح:', 'options' => ['Heavier', 'Heavyer', 'Heavyyer', 'heaveer'], 'correct' => 0],
        ['text' => 'هل نضيف (est) للصفة (Exciting) عند المقارنة؟', 'options' => ['Excitingest نعم', 'Excitest نعم', 'لا نضيف لأنها متعددة المقاطع', 'نضيف er فقط'], 'correct' => 2],

        // Quantifiers
        ['text' => 'ما معنى جملة (زيت زيتون كثير)؟', 'options' => ['Many olive oil', 'Much olive oil', 'Few olive oil', 'A little olive oil'], 'correct' => 1],
        ['text' => 'ما معنى جملة (كثير من المعلومات)؟', 'options' => ['A Lot of information', 'Many information', 'Few information', 'A little information'], 'correct' => 0],
        ['text' => 'محدد الكمية (Some) يأتي مع:', 'options' => ['الاسماء المعدودة فقط', 'الاسماء الغير المعدودة فقط', 'الأسماء المعدودة وغير المعدودة', 'الأسماء المفردة فقط'], 'correct' => 2],
        ['text' => 'ماذا نسمي الأسماء القابلة للجمع؟', 'options' => ['Countable nouns', 'Uncountable nouns', 'Irregular nouns', 'لاشيء'], 'correct' => 0],
        ['text' => 'ماذا نسمي الأسماء التي لا نجمعها؟', 'options' => ['Countable nouns', 'Uncountable nouns', 'Irregular nouns', 'لاشيء'], 'correct' => 1],
        ['text' => 'عند تحديد الأسماء الغير معدودة فإننا نضيف لها وحدة قياس مثل (A little flour).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'لإظهار درجة محدد الكمية فإننا نضع قبله حال مثل (Too).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال بـ (How much) سعر وتكون (Be) أساسية:', 'options' => ['How much + be + subject?', 'How much +v + aux + subject?', 'How much + subject + v1?', 'How much + do + subject + v1?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال بـ (How much) كمية ولا تكون (Be) أساسية:', 'options' => ['How much + be + subject?', 'How much +v + aux + subject?', 'How much + subject + v?', 'How much+ noun + aux + subject + v?'], 'correct' => 3],
        ['text' => 'اختر تكوين السؤال بـ (How many) للعدد:', 'options' => ['How many + be + subject + v?', 'How many + noun + aux + subject + v ?', 'How many + subject + v ?', 'None'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال بـ (How many) للعدد و (Be) فعل اساسي:', 'options' => ['How many + be + subject?', 'How many + subject + be?', 'How many + noun + aux + subject + v?', 'How many + subject + v ?'], 'correct' => 0],

        // Delexical Verbs
        ['text' => 'ماذا نسمي الأفعال التي لا تعبر عن معنى وحدها وتعتمد على الاسم بعدها؟', 'options' => ['Modal verbs', 'Delexical verbs', 'Helping verb', 'لاشيء'], 'correct' => 1],
        ['text' => 'ما معنى (Have / take a shower)؟', 'options' => ['يأخذ دوش', 'ينظف', 'يغسل', 'يتناول مشروب'], 'correct' => 0],
        ['text' => 'غالبا الفعل الذي يأتي بعد (Go) هو مجرد (infinitive).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'الفعل المناسب قبل كلمة (An excuse) هو:', 'options' => ['do', 'Get', 'Make', 'Go'], 'correct' => 2],
        ['text' => 'اذا اتى (V3) بعد فعل (Get) مثل (Get hit) فما معناه؟', 'options' => ['يحصل على', 'يصبح', 'يكون في محل المفعول به كالكينونة', 'لاشيء'], 'correct' => 2],
        ['text' => 'يوجد فرق في المعني بين (I have got a plan) و (I have a plan).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],

        // There is / There are
        ['text' => 'للتعبير عن وجود جمع (أكثر من شيء) في الحاضر نستخدم:', 'options' => ['There is', 'There are', 'There was', 'There were'], 'correct' => 1],
        ['text' => 'للتعبير عن وجود جمع في الماضي نستخدم:', 'options' => ['There is', 'There are', 'There was', 'There were'], 'correct' => 3],
        ['text' => 'للتعبير عن وجود مفرد في الماضي نستخدم:', 'options' => ['There is', 'There are', 'There was', 'There were'], 'correct' => 2],
        ['text' => 'للتعبير عن وجود مفرد في الحاضر نستخدم:', 'options' => ['There is', 'There are', 'There was', 'There were'], 'correct' => 0],
        ['text' => '(There are) تعني يوجد هنالك و (there was) تعني كان هنالك.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'السؤال الصحيح لـ (There was an electrical fault...):', 'options' => ['Was there an electrical fault... .', 'Was there an electrical fault...?', 'There Was an electrical fault...?', 'Is there an electrical fault...?'], 'correct' => 1],

        // Time Review
        ['text' => 'الاختصار (am) تعني صباحا و (pm) تعني مساء.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'كلمة (o’clock) هي ساعة حائط اما (Watch) هي ساعة يد.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اقرا الساعة (بالطريقة الرسمية) 7:35:', 'options' => ['seven o’clock', 'seven thirty five', 'twenty five to eight', 'seven forty five'], 'correct' => 2],
        ['text' => 'اقرا الساعة (بالطريقة الحديثة) 5:25:', 'options' => ['five o’clock', 'five twenty five', 'thirty five to six', 'five fifty two'], 'correct' => 1],
        ['text' => 'اقرا الساعة (بالطريقة العسكرية) 8:35pm:', 'options' => ['It’s eight o’clock', 'It’s eight thirty five', 'It’s twenty five to nine', 'It’s twenty thirty five'], 'correct' => 3],
        ['text' => 'تحديد ما اذا كانت الساعة في الصباح او المساء يساعد عند قراءة الطريقة:', 'options' => ['الرسمية', 'الحديثة', 'العسكرية', 'القديمة'], 'correct' => 2],
        ['text' => 'الساعة التي تستخدم للتنبيه تسمى بـ:', 'options' => ['Military o’clock', 'Alarm o’clock', 'Golden o’clock', 'Analog o’clock'], 'correct' => 1],

        // Date Review
        ['text' => 'هناك طريقتين لقراءة التاريخ: طريقة حديثة وطريقة قديمة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ما هو حرف الجر الذي (ننطقه ولا نكتبه) قبل الشهر في الطريقة البريطانية؟', 'options' => ['At', 'Of', 'For', 'To'], 'correct' => 1],
        ['text' => 'نضيف فاصلة (,) بعد اسم الشهر ليظهر لنا الاختصار.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'نطق التاريخ الأمريكي والبريطاني لا يختلف وإنما يكون الاختلاف في الكتابة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الترتيب المكتوب بالطريقة الامريكية:', 'options' => ['4/21/1996', '1996/21/4', '21/4/1996', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'ترتيب كتابة التاريخ البريطاني هو (Day + month + year).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ترتيب كتابة التاريخ الأمريكي هو (Month + day + year).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'هل نستخدم الفاصلة في الطريقة الامريكية؟', 'options' => ['نعم قبل السنة', 'نعم قبل الشهر', 'لا نستخدمها', 'نعم قبل اليوم'], 'correct' => 0],
        ['text' => 'بالطريقة البريطانية؟ 24\6\2018 نلفظ التاريخ:', 'options' => ['The sixth of twenty four eighteen', 'June the twenty fourth eighteen', 'The twenty fourth of June twenty eighteen'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة شاملة 1 (Comprehensive Review 1)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 90,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1162.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
