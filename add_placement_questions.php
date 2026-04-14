<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Course;

$courseId = 8;

// Verify course exists
$course = Course::find($courseId);
if (!$course) {
    echo "Error: Course ID {$courseId} not found!\n";
    exit(1);
}

$questions = [
    // Q1 - correct: B (Aitch)
    [
        'question_text' => 'كيف يُنطق حرف (H) في اللغة الإنجليزية باللهجة الأمريكية؟',
        'option_a' => 'Haitch',
        'option_b' => 'Aitch',
        'option_c' => 'Hitch',
        'option_d' => 'Etch',
        'correct_answer' => 'B',
        'difficulty' => 'easy',
    ],
    // Q2 - correct: C (4)
    [
        'question_text' => 'كم يوجد نطق للحرف المركب (gh) في اللغة الإنجليزية؟',
        'option_a' => '3',
        'option_b' => '5',
        'option_c' => '4',
        'option_d' => '2',
        'correct_answer' => 'C',
        'difficulty' => 'medium',
    ],
    // Q3 - correct: A
    [
        'question_text' => 'ما قاعدة تكرار كتابة الحرف (Double Letter) في آخر الكلمة؟',
        'option_a' => 'نكرر الحرف الأخير إذا جاءت الكلمة على نمط: حرف ساكن + حرف علة + حرف ساكن، وكان المقطع الأخير مُشددًا',
        'option_b' => 'نكرر أول حرف في الكلمة إذا كان بعدها حرف علة',
        'option_c' => 'نكرر أي حرف متحرك في وسط الكلمة',
        'option_d' => 'لا توجد قاعدة محددة',
        'correct_answer' => 'A',
        'difficulty' => 'medium',
    ],
    // Q4 - correct: D (Fifteen hundred)
    [
        'question_text' => 'اختر النطق الصحيح للرقم 1500:',
        'option_a' => 'Fifty hundred',
        'option_b' => 'One five zero zero',
        'option_c' => 'Fifteen thousands',
        'option_d' => 'Fifteen hundred',
        'correct_answer' => 'D',
        'difficulty' => 'easy',
    ],
    // Q5 - correct: B (8)
    [
        'question_text' => 'كم عدد أقسام (أنواع) الأسماء في اللغة الإنجليزية؟',
        'option_a' => '6',
        'option_b' => '8',
        'option_c' => '5',
        'option_d' => '10',
        'correct_answer' => 'B',
        'difficulty' => 'medium',
    ],
    // Q6 - correct: C (2)
    [
        'question_text' => 'كم اسمًا شائعًا يوجد في اللغة الإنجليزية لعلامة النقطة (.) التي تأتي في آخر الجملة؟',
        'option_a' => '1',
        'option_b' => '4',
        'option_c' => '2',
        'option_d' => '3',
        'correct_answer' => 'C',
        'difficulty' => 'medium',
    ],
    // Q7 - correct: A (خطأ)
    [
        'question_text' => 'نضع في آخر كلمة (photo) الحرفين (es) لأنها تنتهي بحرف (o).',
        'option_a' => 'خطأ',
        'option_b' => 'صح',
        'option_c' => 'أحيانًا',
        'option_d' => 'لا يمكن تحديد ذلك',
        'correct_answer' => 'A',
        'difficulty' => 'easy',
    ],
    // Q8 - correct: D (We)
    [
        'question_text' => 'في جملة (Mohammed and I)، ما ضمير الفاعل المناسب؟',
        'option_a' => 'They',
        'option_b' => 'He',
        'option_c' => 'You',
        'option_d' => 'We',
        'correct_answer' => 'D',
        'difficulty' => 'easy',
    ],
    // Q9 - correct: B (The baby slept peacefully.)
    [
        'question_text' => 'أي جملة تحتوي على فعل لازم (Intransitive Verb) وليس متعديًا؟',
        'option_a' => 'She opened the door.',
        'option_b' => 'The baby slept peacefully.',
        'option_c' => 'They built a bridge.',
        'option_d' => 'He answered the question.',
        'correct_answer' => 'B',
        'difficulty' => 'medium',
    ],
    // Q10 - correct: C (الصفات)
    [
        'question_text' => 'أحد استخدامات التصريف الثالث للفعل هو:',
        'option_a' => 'الماضي البسيط',
        'option_b' => 'المضارع البسيط',
        'option_c' => 'الصفات',
        'option_d' => 'التعريف',
        'correct_answer' => 'C',
        'difficulty' => 'medium',
    ],
    // Q11 - correct: A (Be)
    [
        'question_text' => 'من هي أم الكينونة في اللغة الإنجليزية؟',
        'option_a' => 'Be',
        'option_b' => 'Why',
        'option_c' => 'You',
        'option_d' => 'Them',
        'correct_answer' => 'A',
        'difficulty' => 'easy',
    ],
    // Q12 - correct: D (يلزم مفعولًا به)
    [
        'question_text' => 'إذا كان الفعل Transitive فإنه:',
        'option_a' => 'لا يلزم مفعولًا به في الجملة',
        'option_b' => 'لا يأتي معه فاعل',
        'option_c' => 'لا يُستخدم إلا في الماضي',
        'option_d' => 'يلزم مفعولًا به في الجملة',
        'correct_answer' => 'D',
        'difficulty' => 'easy',
    ],
    // Q13 - correct: B
    [
        'question_text' => 'إذا جاءت صفات كثيرة في الجملة، فما الترتيب الصحيح لها؟',
        'option_a' => 'الرأي، العدد، الحجم، العمر، اللون، الشكل، الأصل، المادة، الغرض',
        'option_b' => 'العدد، الرأي، الحجم، العمر، الشكل، اللون، الأصل أو المنشأ، المادة، الغرض',
        'option_c' => 'اللون، الشكل، العمر، الحجم، الرأي، العدد، المادة، الأصل، الغرض',
        'option_d' => 'الحجم، العدد، الرأي، اللون، الشكل، العمر، المادة، الأصل، الغرض',
        'correct_answer' => 'B',
        'difficulty' => 'hard',
    ],
    // Q14 - correct: A
    [
        'question_text' => 'أي مجموعة من التالية تحتوي على أنواع الظروف الصحيحة في اللغة الإنجليزية؟',
        'option_a' => 'ظرف زمان، ظرف مكان، ظرف كيفية',
        'option_b' => 'ظرف لون، ظرف حجم، ظرف رأي',
        'option_c' => 'ظرف مفرد، ظرف جمع، ظرف مثنى',
        'option_d' => 'ظرف مذكر، ظرف مؤنث، ظرف محايد',
        'correct_answer' => 'A',
        'difficulty' => 'easy',
    ],
    // Q15 - correct: C (نعم)
    [
        'question_text' => 'هل تعرف ما هو الـ Attributive Noun؟',
        'option_a' => 'لا',
        'option_b' => 'أحيانًا',
        'option_c' => 'نعم',
        'option_d' => 'ليس له معنى',
        'correct_answer' => 'C',
        'difficulty' => 'medium',
    ],
    // Q16 - correct: A
    [
        'question_text' => 'اختر المجموعة التي تحتوي على إضافات تُستخدم كبادئة (Prefixes):',
        'option_a' => 'Im – il – pre – post – super',
        'option_b' => 'Ex – mid – ly – ee – er',
        'option_c' => 'Ful – ly – less – in – im',
        'option_d' => 'جميع ما سبق',
        'correct_answer' => 'A',
        'difficulty' => 'medium',
    ],
    // Q17 - correct: D (His)
    [
        'question_text' => 'الكلمة التي تبقى كما هي في ضمير الملكية وصفة الملكية هي:',
        'option_a' => 'My',
        'option_b' => 'Her',
        'option_c' => 'Ours',
        'option_d' => 'His',
        'correct_answer' => 'D',
        'difficulty' => 'medium',
    ],
    // Q18 - correct: B
    [
        'question_text' => 'نستخدم حروف الجر في اللغة الإنجليزية لِـ:',
        'option_a' => 'تحديد الجمع والمفرد',
        'option_b' => 'إضافة معنى للكلمة وربط كلمة بكلمة',
        'option_c' => 'تحويل الفعل إلى اسم',
        'option_d' => 'تكوين الماضي فقط',
        'correct_answer' => 'B',
        'difficulty' => 'easy',
    ],
    // Q19 - correct: C (Is this a table?)
    [
        'question_text' => 'اختر تكوين السؤال الصحيح للجملة: (This is a table.)',
        'option_a' => 'Is a table this?',
        'option_b' => 'This is a table?',
        'option_c' => 'Is this a table?',
        'option_d' => 'A table this is?',
        'correct_answer' => 'C',
        'difficulty' => 'easy',
    ],
    // Q20 - correct: A (Furthermore)
    [
        'question_text' => 'اختر كلمة الربط المناسبة للجملة التالية: The company reduced costs, ____ it increased its market share.',
        'option_a' => 'Furthermore',
        'option_b' => 'Because',
        'option_c' => 'First',
        'option_d' => 'Therefore',
        'correct_answer' => 'A',
        'difficulty' => 'medium',
    ],
    // Q21 - correct: B
    [
        'question_text' => 'إذا انتهى الفعل بحرف (y) وقبله حرف علة، وأردنا إضافة (s) أو (es)، فإننا:',
        'option_a' => 'نقلب حرف (y) إلى (i) ثم نضيف (es)',
        'option_b' => 'نبقي حرف (y) كما هو ثم نضيف (s)',
        'option_c' => 'نقلب حرف (y) إلى (i) ثم نضيف (s)',
        'option_d' => 'لا شيء مما سبق',
        'correct_answer' => 'B',
        'difficulty' => 'medium',
    ],
    // Q22 - correct: D (خطأ)
    [
        'question_text' => 'في تكوين السؤال في زمن المضارع المستمر، نُرجع الفعل إلى فعل مجرد بدون إضافات لأنه أصبح سؤالًا.',
        'option_a' => 'صح',
        'option_b' => 'أحيانًا',
        'option_c' => 'في الماضي فقط',
        'option_d' => 'خطأ',
        'correct_answer' => 'D',
        'difficulty' => 'medium',
    ],
    // Q23 - correct: C (صح)
    [
        'question_text' => 'الفعل المساعد الأساسي في زمن المضارع التام هو have/has.',
        'option_a' => 'خطأ',
        'option_b' => 'فقط مع الجمع',
        'option_c' => 'صح',
        'option_d' => 'فقط في النفي',
        'correct_answer' => 'C',
        'difficulty' => 'easy',
    ],
];

echo "Creating questions for course: {$course->title} (ID: {$courseId})...\n\n";

$createdQuestions = [];
$orderIndex = 1;

foreach ($questions as $q) {
    $question = Question::create([
        'course_id' => $courseId,
        'lesson_id' => null,
        'question_text' => $q['question_text'],
        'question_type' => 'multiple_choice',
        'option_a' => $q['option_a'],
        'option_b' => $q['option_b'],
        'option_c' => $q['option_c'],
        'option_d' => $q['option_d'],
        'correct_answer' => $q['correct_answer'],
        'explanation' => null,
        'difficulty' => $q['difficulty'],
        'points' => 5,
    ]);
    $createdQuestions[] = $question;
    echo "Q{$orderIndex}: Created (ID: {$question->id}) - Answer: {$q['correct_answer']}\n";
    $orderIndex++;
}

// Create a quiz for this placement test
$quiz = Quiz::create([
    'course_id' => $courseId,
    'lesson_id' => null,
    'title' => 'اختبار تحديد المستوى في اللغة الإنجليزية',
    'quiz_type' => 'final_exam',
    'description' => 'اختبار لتحديد مستوى الطالب في اللغة الإنجليزية ويشمل قواعد اللغة والمفردات والنطق',
    'total_questions' => count($questions),
    'duration_minutes' => 30,
    'passing_score' => 60,
    'is_active' => true,
    'allow_retake' => true,
    'show_results_immediately' => true,
]);

echo "\nCreated Quiz: {$quiz->title} (ID: {$quiz->id})\n";

// Attach questions to quiz
foreach ($createdQuestions as $index => $question) {
    $quiz->questions()->attach($question->id, ['order_index' => $index + 1]);
}

echo "Attached " . count($createdQuestions) . " questions to the quiz.\n";
echo "\nDone! Answer distribution:\n";
echo "  A: 6 questions (Q3, Q7, Q11, Q14, Q16, Q20)\n";
echo "  B: 6 questions (Q1, Q5, Q9, Q13, Q18, Q21)\n";
echo "  C: 6 questions (Q2, Q6, Q10, Q15, Q19, Q23)\n";
echo "  D: 5 questions (Q4, Q8, Q12, Q17, Q22)\n";