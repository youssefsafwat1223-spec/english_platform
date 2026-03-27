<?php

/**
 * Script to import questions for Lesson ID 1160 (Date Grammar)
 * php import_lesson_1160_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1160;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1160 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى كلمة تاريخ في اللغة الإنجليزية؟', 'options' => ['Time', 'Year', 'Date', 'Age'], 'correct' => 2],
        ['text' => 'ماذا نعني بكلمة (Decade) عقد؟', 'options' => ['حقبة من الزمن مدتها 30 يوم', 'حقبة من الزمن مدتها 10 سنوات', 'حقبة من الزمن مدتها 100 سنة', 'حقبة من الزمن مدتها 7 أيام'], 'correct' => 1],
        ['text' => 'ماذا نعني بكلمة (century) قرن؟', 'options' => ['حقبة من الزمن مدتها 30 يوم', 'حقبة من الزمن مدتها 10 سنوات', 'حقبة من الزمن مدتها 100 سنة', 'حقبة من الزمن مدتها 7 أيام'], 'correct' => 2],
        ['text' => 'القرن العشرون هو القرن الذي:', 'options' => ['يبدا من عام 2000 وينتهي ب 2099', 'يبدا من عام 1900 وينتهي ب 1999', 'يبدا من عام 1800 وينتهي ب 1899', 'ليس مما ذكر'], 'correct' => 1],
        ['text' => 'هناك طريقتين لقراءة التاريخ في اللغة الإنجليزية وهم:', 'options' => ['طريقة صينية وطريقة أمريكية', 'طريقة بريطانية وطريقة أمريكية', 'طريقة أمريكية وطريقة صينية', 'طريقة صينية وطريقة المانية'], 'correct' => 1],
        ['text' => 'عند كتابة رقم اليوم في الطريقة الامريكية فإننا نكتبه دائما رقم عادي (cardinal) ونقرأه أيضا رقم عادي، وليس ترتيبي (ordinal).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما هو حرف الجر الذي ننطقه قبل الشهر في الطريقة البريطانية؟', 'options' => ['At', 'Of', 'For', 'To'], 'correct' => 1],
        ['text' => 'حرف الجر (Of) قبل اسم الشهر في الطريقة البريطانية يظهر عند:', 'options' => ['النطق فقط', 'الكتابة فقط', 'النطق والكتابة', 'لا يظهر اصلا'], 'correct' => 0],
        ['text' => 'ماهو حرف الجر المستخدم قبل الأيام والتواريخ؟', 'options' => ['At', 'On', 'In', 'Of'], 'correct' => 1],
        ['text' => 'نستخدم حرف الجر (In) قبل جميع ما يلي ما عدا؟', 'options' => ['الشهور', 'القرون', 'التواريخ', 'العقود'], 'correct' => 2],
        
        [
            'text' => 'صل كل اسم شهر مع معناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'January', 'right' => 'يناير(شهر1)'],
                ['left' => 'February', 'right' => 'فبراير(شهر2)'],
                ['left' => 'March', 'right' => 'مارس(شهر3)'],
                ['left' => 'April', 'right' => 'ابريل(شهر4)'],
                ['left' => 'May', 'right' => 'مايو(شهر5)'],
                ['left' => 'June', 'right' => 'يونيو(شهر6)'],
                ['left' => 'July', 'right' => 'يوليو(شهر7)'],
                ['left' => 'August', 'right' => 'أغسطس(شهر8)'],
                ['left' => 'September', 'right' => 'سبتمبر(شهر9)'],
                ['left' => 'October', 'right' => 'أكتوبر(شهر10)'],
                ['left' => 'November', 'right' => 'نوفمبر(شهر11)'],
                ['left' => 'December', 'right' => 'ديسمبر(شهر12)'],
            ]
        ],

        [
            'text' => 'رتب أيام الأسبوع (من السبت):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => '1', 'right' => 'Saturday'],
                ['left' => '2', 'right' => 'Sunday'],
                ['left' => '3', 'right' => 'Monday'],
                ['left' => '4', 'right' => 'Tuesday'],
                ['left' => '5', 'right' => 'Wednesday'],
                ['left' => '6', 'right' => 'Thursday'],
                ['left' => '7', 'right' => 'Friday'],
            ]
        ],

        ['text' => 'كيف نختصر كلمة فبراير (February)؟', 'options' => ['Febr', 'Feb.', 'F', 'Febru'], 'correct' => 1],
        ['text' => 'الاختصار (Sun.) هو اختصار لكلمة:', 'options' => ['Sunny', 'Sunday', 'Saturday', 'ليس مما ذكر'], 'correct' => 1],
        ['text' => 'ما هو الشيء الذي نضيفه بعد الشهر ليبين لنا الاختصار؟', 'options' => ['(.) Period نقطة', '(,) Comma فاصلة', '(-) Dash شرطة', '(:) The colon النقطتين'], 'correct' => 0],
        ['text' => 'أسماء الشهور تكتب حروف صغيرة (small) ولكن أسماء الأيام تكتب حروف كبيرة (Capital).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],

        [
            'text' => 'صل القراءة الصحيحة للسنوات (حسب الدرس):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => '1996', 'right' => 'Nineteen ninety six'],
                ['left' => '1878', 'right' => 'Eighteen seventy eight'],
                ['left' => '2008', 'right' => 'Oh eight'],
                ['left' => '2009', 'right' => 'Oh nine'],
            ]
        ],

        ['text' => 'ترتيب كتابة التاريخ الأمريكي هو:', 'options' => ['Month + year + day', 'Month + day + year', 'Year + day + month', 'Day + month + year'], 'correct' => 1],
        ['text' => 'اليوم في التاريخ الأمريكي يكون:', 'options' => ['Ordinal number عدد ترتيبي', 'Cardinal number عدد عادي(أساسي)', 'A + B', 'عدد يتكون من منزلتين'], 'correct' => 2],
        ['text' => 'في طريقة النطق الأمريكي فإننا نكتب الأداة (the) ولا ننطقها؟', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'نطق التاريخ الأمريكي والبريطاني لا يختلف وإنما يكون الاختلاف في الكتابة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'نستخدم الفاصلة (,) ونكتبها قبل السنة في:', 'options' => ['الطريقة الأمريكية', 'الطريقة البريطانية فقط', 'الطريقة الامريكية والبريطانية معا', 'لا نستخدمها أصلا'], 'correct' => 0],
        ['text' => 'اختر الترتيب المكتوب بالطريقة الأمريكية:', 'options' => ['5/21/1997', '1997/21/5', '21/5/1997', 'جميع ما ذكر'], 'correct' => 0],
        ['text' => 'في التاريخ (5/5/2005) اول رقم نقرأه بالطريقة الامريكية يعبر عن:', 'options' => ['اليوم الخامس من شهر مايو', 'شهر مايو (شهر 5)', 'يوم الخميس من الأسبوع', 'ليس مما سبق'], 'correct' => 1],
        ['text' => 'ممكن اختصار التاريخ بالنطق والكتابة معا ليس فقط بالكتابة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ترتيب كتابة التاريخ البريطاني هو:', 'options' => ['Month + year + day', 'Month + day + year', 'Year + day + month', 'Day + month + year'], 'correct' => 3],
        ['text' => 'اختر الترتيب المكتوب بالطريقة البريطانية:', 'options' => ['6/27/1989', '1989/27/6', '27/6/1989', 'جميع ما ذكر'], 'correct' => 2],
        ['text' => 'كم حرف جر نستخدم مع التواريخ؟', 'options' => ['حرف واحد فقط', 'حرفان', 'ثلاثة حروف', 'أربعة حروف'], 'correct' => 1],
        ['text' => 'وجود حرف الجر (on) قبل التواريخ يكون اجباري؟', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'لماذا يكون حرف الجر (in) في التواريخ اجباري؟', 'options' => ['لأنها تكون فترة محدودة', 'لأنها فترة مفتوحة غير محدودة', 'لأنها فترة طويلة', 'لأنها فترة قصيرة'], 'correct' => 2],
        ['text' => 'التاريخ (4/16/2022) مكتوب بالطريقة:', 'options' => ['الامريكية', 'البريطانية', 'كليهما', 'ليس مما سبق'], 'correct' => 0],
        ['text' => 'الطريقة الصحيحة لقراءة التاريخ (4/16/2022) بالطريقة الأمريكية:', 'options' => ['The sixteenth of April twenty twenty two', 'April the sixteenth twenty twenty two', 'The fourth of sixteen twenty twenty two', 'ليس مما سبق'], 'correct' => 1],
        ['text' => 'نميز طريقة اللفظ البريطانية بوجود حرف الجر ------ قبل الشهور.', 'options' => ['In', 'At', 'Of', 'For'], 'correct' => 2],
        ['text' => 'اعد ترتيب التاريخ حسب (الطريقة البريطانية) وقم بقرائته صحيحا (4/16/2022):', 'options' => ['The sixteenth of April twenty twenty two', 'April the sixteenth twenty twenty two'], 'correct' => 0],
        ['text' => 'رتب التاريخ التالي حسب الطريقة الامريكية: (16) (February) (2013)', 'options' => ['16\2\2013', '2\16\2013', '2013\16\2', '2013\2\16'], 'correct' => 1],
        ['text' => 'رتب التاريخ التالي حسب الطريقة البريطانية: (1998) (March) (22)', 'options' => ['1998\3\22', '22\3\1998', '3\22\1998', '3\1998\22'], 'correct' => 1],
        ['text' => '(January the fourth) تكتب بالطريقة الامريكية جميع ما سبق (ما عدا):', 'options' => ['01 \ 04', 'Jan. 4', 'Jan. 4th', '04 \ 01'], 'correct' => 3],
        ['text' => '(The seventeenth of September twenty twenty) التاريخ التالي هو لفظ للكتابة:', 'options' => ['7th Sep. 210', '7th Sep. 2020', '2020.7th Sep', 'Sep 7th .2020'], 'correct' => 1],
        ['text' => '(August the fifth oh 9) التاريخ التالي لفظ للكتابة:', 'options' => ['August 5th, 2009', '5th Aug. 2009', '2009, Aug. 5th', 'ليس مما ذكر'], 'correct' => 0],
        ['text' => 'بالطريقة الامريكية؟ 7\26\2014 نلفظ التاريخ:', 'options' => ['The seventh of twenty six twenty fourteen', 'July the twenty sixth twenty fourteen', 'The twenty sixth of July twenty fourteen'], 'correct' => 1],
        ['text' => 'بالطريقة البريطانية؟ 7\26\2014 نلفظ التاريخ:', 'options' => ['The seventh of twenty six twenty fourteen', 'July the twenty sixth twenty fourteen', 'The twenty sixth of July twenty fourteen'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد التاريخ (Date Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1160.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
