<?php

/**
 * Script to import questions for Lesson ID 1102 (Modal Verbs Grammar)
 * php import_lesson_1102_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1102;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1102 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما هي الأفعال الناقصة (modal verbs)؟', 'options' => ['هي أفعال مساعدة يعني تساعد في معنى الجملة وتؤثر على الفعل الأساسي في الجملة', 'هي أفعال رئيسة', 'هي أفعال لا تؤثر في الجملة', 'ليس مما ذكر'], 'correct' => 0],
        ['text' => 'الأفعال الناقصة هي في الأصل أفعال مساعدة لانها تساعد في معنى الجملة وتؤثر على الفعل الأساسي في الجملة.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 0],
        ['text' => 'جميع ما يلي سبب تسمية الأفعال الناقصة بافعال ناقصة( ما عدا):', 'options' => ['لا تتغير من ناحية الكتابة', 'لا تتصرف', 'ed/ing لا يضاف عليها إضافات مثل', 'حروفها ناقصة في الكتابة'], 'correct' => 3],
        ['text' => 'الأفعال الناقصة تستخدم للتعبير عن مزاجية حدوث الفعل الأساسي في الجملة.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 0],
        ['text' => 'الأفعال الناقصة مأخوذة من كلمة مزاج يعني تعطيك نبذة عن مزاج الكلام وحدوث الفعل.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 0],
        ['text' => 'اختر مجموعة الأفعال التي تكون أفعال ناقصة (Modal verbs):', 'options' => ['Play can is', 'will are visit', 'could may should', 'might has must'], 'correct' => 2],
        ['text' => 'ما هو الفعل الناقص الذي يعبر عن القدرة في الحاضر ومعناه (يستطيع او يقدر)؟', 'options' => ['Shall', 'could', 'Can', 'May'], 'correct' => 2],
        ['text' => 'ما تصريف الفعل الذي يأتي بعد الأفعال الناقصة؟', 'options' => ['تصريف اول مجرد v1', 'تصريف ثاني v2', 'تصريف ثالث v3', 'لا شي مما سبق'], 'correct' => 0],
        ['text' => 'فعل ناقص من أفعال الاستطاعة يستخدم بمعنى( استطاع في الماضي) ؟', 'options' => ['Can', 'Will', 'Could', 'May'], 'correct' => 2],
        ['text' => 'للتعبير عن فعل شي سيحدث في المستقبل فاننا نستخدم -------', 'options' => ['May', 'Will', 'Should', 'Must'], 'correct' => 1],
        ['text' => 'نستخدم الفعل الناقص Should للتعبير عن ------', 'options' => ['شيء محتمل الحدوث في المستقبل', 'لتقديم النصيحة او المشورة', 'للالزام والضرورة', 'للتعبير عن القدرة والاستطاعة'], 'correct' => 1],
        ['text' => 'نستخدم ----- للتعبير عن احتمالية حدوث شي ؟', 'options' => ['must', 'Will\would', 'May\might', 'Should'], 'correct' => 2],
        ['text' => 'ما هو الفعل الناقص المستخدم للإلزام والضرورة لفعل الشيء؟', 'options' => ['Can', 'Shall', 'Must', 'Will'], 'correct' => 2],
        ['text' => '(It may rain tomorrow) تعني أن -------:', 'options' => ['انها سوف تمطر غدا', 'انها ربما تمطر غدا', 'انها يجب ان تمطر غدا', 'لا شي مما سبق'], 'correct' => 1],
        ['text' => '(You should go to the dentist) تعني ------:', 'options' => ['انت سوف تذهب الى طبيب الاسنان', 'انت يجب ان تذهب الى طبيب الاسنان(بالقوة)', 'انت ينبغي ان تذهب الى طبيب الاسنان (نصيحة)', 'انت ربما تذهب الى طبيب الاسنان'], 'correct' => 2],
        ['text' => '(flight attendants must wear the uniform) تعني -------:', 'options' => ['المضيفين يجب ان يلبسوا الزي الرسمي (نصيحة)', 'المضيفين يجب ان يلبسوا الزي الرسمي (بالالزام) أي انه قانون', 'المضيفين ربما يلبسوا الزمي الرسمي', 'المضيفين سوف يلبسوا الزي الرسمي'], 'correct' => 1],
        ['text' => '(I will be in the class next week) تعني ------:', 'options' => ['انا سوف أكون في الحصة الأسبوع القادم', 'انا ربما أكون في الحصة الأسبوع القادم', 'انا يجب ان أكون في الحصة الأسبوع القادم( نصيحة)', 'انا يجب ان أكون في الحصة الأسبوع القادم(بالالزام)'], 'correct' => 0],
        ['text' => 'انا استطيع ان العب كرة قدم): اختر الترجمة الصحيحة:', 'options' => ['I will play football', 'I may play football', 'I can play football', 'I must play football'], 'correct' => 2],

        [
            'text' => 'صل كل نوع من أنواع الأفعال الناقصة باستخدامه المناسب:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Should\had better\ought to', 'right' => 'النصيحة advice'],
                ['left' => 'Can\could\may', 'right' => 'الاستئذان permission'],
                ['left' => 'Must\have to', 'right' => 'الالزام obligation'],
                ['left' => 'May\might\can\could', 'right' => 'الاحتمالات possibilities'],
                ['left' => 'Can\could\let’s', 'right' => 'الاقتراح suggestion'],
                ['left' => 'Must be\can be\could be', 'right' => 'الاستنتاج deduction'],
                ['left' => 'Can\could', 'right' => 'القدرة ability'],
            ]
        ],

        ['text' => 'يأتي الفعل الناقص (بعد الفعل الأساسي) لانه يعتبر نوعا ما فعل مساعد.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 1],
        ['text' => '(could I use your laptop?) وضح استخدام الفعل الناقص في هذه الجملة:', 'options' => ['Ability', 'Permission', 'Possibility', 'Deduction'], 'correct' => 1],
        ['text' => 'نستخدم اختصار كلمة (’d better) للتعبير عن نصيحة فما اصل هذه الكلمة؟', 'options' => ['Would better', 'Had better', 'Did better', 'Could better'], 'correct' => 1],
        ['text' => '(Let’s) هي عبارة عن كلمة مختصرة لكلمتين فما اصل هذه الكلمة؟', 'options' => ['Let us', 'Let is', 'Let was', 'Let has'], 'correct' => 0],
        ['text' => '(Abdullah ate all the sandwiches on the table, he must be hungry) ماهو استخدام الفعل الناقص بهذه الجملة؟', 'options' => ['Obligation', 'Deduction', 'Possibility', 'Advice'], 'correct' => 1],
        ['text' => 'الطلب غالبا ما يأتي في صيغة سؤال.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 0],
        ['text' => 'ما هي صيغة السؤال الصحيحة في الأفعال الناقصة؟', 'options' => ['Modal verb+subject+object+complement?', 'Modal verb+subject+main verb+object+complement?', 'Subject+model verb+main verb+object+complement?', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(Will\would) تعتبر كلمة من الأفعال (الغير ناقصة) لأنها تستخدم في الماضي والمستقبل.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 1],
        ['text' => 'عند تكوين السؤال نعكس دائما اول خانتين من الجملة(فنضع الفاعل أولا ثم نضع الفعل الناقص ثانيا).', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 1],
        ['text' => 'عند الإجابة باستخدام (yes\no) فإننا نضع بعدها الفعل المساعد أولا ثم الفاعل.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 1],
        ['text' => '(Can salma wake up earlier?) اختر الإجابة الصحيحة:', 'options' => ['Yes, can she.', 'Yes,she can.', 'She can,yes.', 'Yes,she can’t.'], 'correct' => 1],
        ['text' => 'ما هو السؤال المكتوب بالشكل الصحيح؟', 'options' => ['Abdul Kareem will build a mosque?', 'Will Abdul Kareem build a mosque?', 'Abdul Kareem build will a mosque?', 'Will Abdul Kareem a mosque build?'], 'correct' => 1],
        ['text' => 'الطلب عادة يأتي في صيغة جملة عادية.', 'type' => 'true_false', 'options' => ['نعم', 'لا'], 'correct' => 1],
        ['text' => '(can \could) نستطيع ان نستخدم للتعبير عن جميع ما يلي ما عدا:', 'options' => ['Ability', 'Permission', 'Advice', 'Possibility'], 'correct' => 2],
        ['text' => '(May I open the door?) يعبر الفعل الناقص في هذه الجملة عن ------:', 'options' => ['Permission', 'Advice', 'Offer', 'request'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الأفعال الناقصة (Modal Verbs Grammar)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 45,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1102.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
