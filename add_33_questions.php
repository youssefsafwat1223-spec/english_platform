<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Question;
use App\Models\Lesson;
use App\Models\Quiz;

$lessonId = 806;
$courseId = 6;

// Find Lesson
$lesson = Lesson::find($lessonId);
if (!$lesson) {
    echo "Lesson $lessonId not found.\n";
    exit;
}

// Find or create quiz for this lesson
$quiz = Quiz::where('lesson_id', $lessonId)->first();
if (!$quiz) {
    echo "Creating new quiz for lesson 806...\n";
    $quiz = new Quiz();
    $quiz->lesson_id = $lessonId;
    $quiz->course_id = $courseId;
    $quiz->title = "اختبار الدرس";
    $quiz->time_limit = 30; // 30 minutes
    $quiz->pass_mark = 50;
    $quiz->save();
}

$questionsData = [
    [
        "text" => "كم عدد حروف اللغة الإنجليزية:",
        "a" => "28 حرف", "b" => "19 حرف", "c" => "26 حرف", "d" => "29 حرف",
        "correct" => "C"
    ],
    [
        "text" => "(cat) كيف ننطق الحرف الأول من كلمة",
        "a" => "س", "b" => "ك", "c" => "ش", "d" => "تش",
        "correct" => "B"
    ],
    [
        "text" => "هو:(A) الحرف الصغير من حرف",
        "a" => "e", "b" => "o", "c" => "a", "d" => "i",
        "correct" => "C"
    ],
    [
        "text" => ") اسم هذا الحرف بالبريطاني:h (",
        "a" => "ايتش", "b" => "اتش", "c" => "هيتش", "d" => "1و3",
        "correct" => "C"
    ],
    [
        "text" => "هو: (D) الحرف الصغير من حرف",
        "a" => "b", "b" => "d", "c" => "p", "d" => "q",
        "correct" => "B"
    ],
    [
        "text" => "هو:(G)الحرف الصغير من",
        "a" => "c", "b" => "g", "c" => "j", "d" => "d",
        "correct" => "B"
    ],
    [
        "text" => "هو: (Q)الحرف الصغير من",
        "a" => "q", "b" => "p", "c" => "d", "d" => "b",
        "correct" => "A"
    ],
    [
        "text" => ":(d )ما الحرف الذي يأتي بعد",
        "a" => "o", "b" => "e", "c" => "f", "d" => "c",
        "correct" => "B"
    ],
    [
        "text" => ":(s)ما الحرف الذي يأتي بعد",
        "a" => "F", "b" => "L", "c" => "h", "d" => "t",
        "correct" => "D"
    ],
    [
        "text" => "ما هو اخر حرف في اللغة الإنجليزية؟",
        "a" => "S", "b" => "Z", "c" => "C", "d" => "K",
        "correct" => "B"
    ],
    [
        "text" => "؟(w)كيف ننطق هذا الحرف",
        "a" => "ي", "b" => "و", "c" => "ف", "d" => "ن",
        "correct" => "B"
    ],
    [
        "text" => "(small) منها مع الصغيرة (capital)أي من الحروف التالية تتشابه كتابة الحروف",
        "a" => "A-B-D-E", "b" => "G-H-I-J", "c" => "N-Q-R-T", "d" => "C-O-K-U",
        "correct" => "D"
    ],
    [
        "text" => "أي حرف اسمه (يلفظ) بي؟",
        "a" => "Bb", "b" => "Aa", "c" => "Dd", "d" => "Tt",
        "correct" => "A"
    ],
    [
        "text" => "؟(s) ما اسم  هذا الحرف",
        "a" => "تي", "b" => "بي", "c" => "اس", "d" => "جي",
        "correct" => "C"
    ],
    [
        "text" => "بالامريكي؟(H)كيف ينطق هذا الحرف",
        "a" => "ايتش", "b" => "اتش", "c" => "هيتش", "d" => "1و3",
        "correct" => "A"
    ],
    [
        "text" => "بالبريطاني؟(Z)كيف ينطق هذا الحرف",
        "a" => "زي", "b" => "س", "c" => "زد", "d" => "ذ",
        "correct" => "C"
    ],
    [
        "text" => "بالامريكي؟(Z)كيف ينطق هذا الحرف",
        "a" => "زي", "b" => "س", "c" => "زد", "d" => "ذ",
        "correct" => "A"
    ],
    [
        "text" => "هو: (B) الحرف الصغير من حرف",
        "a" => "d", "b" => "b", "c" => "P", "d" => "q",
        "correct" => "B"
    ],
    [
        "text" => "جميع الحروف التالية تعتبر حروف كبيرة ما عدا؟",
        "a" => "A", "b" => "B", "c" => "C", "d" => "d",
        "correct" => "D"
    ],
    [
        "text" => "جميع الحروف التالية صغيرة ما عدا؟",
        "a" => "i", "b" => "e", "c" => "Q", "d" => "t",
        "correct" => "C"
    ],
    [
        "text" => "هو: (j) الحرف الكبير من حرف",
        "a" => "I", "b" => "T", "c" => "J", "d" => "L",
        "correct" => "C"
    ],
    [
        "text" => "هو: (n) الحرف الكبير من حرف",
        "a" => "M", "b" => "N", "c" => "U", "d" => "Y",
        "correct" => "B"
    ],
    [
        "text" => "هو: (r) الحرف الكبير من حرف",
        "a" => "R", "b" => "D", "c" => "W", "d" => "E",
        "correct" => "A"
    ],
    [
        "text" => "هو: (h) الحرف الكبير من حرف",
        "a" => "W", "b" => "Q", "c" => "H", "d" => "K",
        "correct" => "C"
    ],
    [
        "text" => "هو: (z) الحرف الكبير من حرف",
        "a" => "S", "b" => "C", "c" => "Z", "d" => "Y",
        "correct" => "C"
    ],
    [
        "text" => "هو: (g) الحرف الكبير من حرف",
        "a" => "J", "b" => "G", "c" => "K", "d" => "H",
        "correct" => "B"
    ],
    [
        "text" => "هو: (e) الحرف الكبير من حرف",
        "a" => "A", "b" => "I", "c" => "L", "d" => "E",
        "correct" => "D"
    ],
    [
        "text" => "هو(X) الحرف الصغير من حرف :",
        "a" => "y", "b" => "x", "c" => "c", "d" => "z",
        "correct" => "B"
    ],
    [
        "text" => "(T) الحرف الصغير من حرف :",
        "a" => "y", "b" => "n", "c" => "f", "d" => "t",
        "correct" => "D"
    ],
    [
        "text" => "(f) الحرف الكبير من حرف :",
        "a" => "Y", "b" => "N", "c" => "F", "d" => "T",
        "correct" => "C"
    ],
    [
        "text" => "؟(y)ما اسم هذا الحرف",
        "a" => "ي", "b" => "دبل يو", "c" => "واي", "d" => "يو",
        "correct" => "C"
    ],
    [
        "text" => "ما اسم هذا الحرف؟(L)",
        "a" => "أي", "b" => "ال", "c" => "ل", "d" => "ان",
        "correct" => "B"
    ],
    [
        "text" => ")ما اسم هذا الحرف؟k)",
        "a" => "ك", "b" => "كاي", "c" => "كوا", "d" => "كيو",
        "correct" => "B"
    ]
];

$count = 0;
// Fetch existing questions order
$startOrder = $quiz->questions()->count();

foreach ($questionsData as $idx => $q) {
    // Create Question
    $question = new Question();
    $question->course_id = $courseId;
    $question->lesson_id = $lessonId;
    $question->question_text = $q['text'];
    $question->question_type = 'multiple_choice';
    $question->option_a = $q['a'];
    $question->option_b = $q['b'];
    $question->option_c = $q['c'];
    $question->option_d = $q['d'];
    $question->correct_answer = $q['correct'];
    $question->difficulty = 'medium';
    $question->points = 1;
    $question->save();

    // Attach to Quiz
    $quiz->questions()->attach($question->id, ['order_index' => $startOrder + $idx]);
    $count++;
}

echo "Successfully added $count questions to Quiz ID: " . $quiz->id . " for Lesson 806.\n";
