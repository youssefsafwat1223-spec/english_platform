<?php

/**
 * Script to import questions for Lesson ID 1040 (Past Perfect Continuous Grammar)
 * php import_lesson_1040_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1040;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1040 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        [
            'text' => 'صل ما بين كل اسم زمن ومعناه (باللغة الإنجليزية):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'مضارع بسيط', 'right' => 'Present simple'],
                ['left' => 'ماضي بسيط', 'right' => 'Past simple'],
                ['left' => 'مضارع مستمر', 'right' => 'Present continuous'],
                ['left' => 'ماضي مستمر', 'right' => 'Past continuous'],
                ['left' => 'مضارع تام', 'right' => 'Present perfect'],
                ['left' => 'ماضي تام', 'right' => 'Past perfect'],
                ['left' => 'ماضي تام مستمر', 'right' => 'Past perfect continuous'],
            ]
        ],

        ['text' => 'يعبر زمن الماضي التام المستمر عن:', 'options' => ['حدث بدا واستمر في الماضي ثم انتهى قبل بدء حدث اخر بعده', 'حدث بدا وانتهى في الماضي', 'حدث كان مستمر لفترة معينة في الماضي', 'ليس مما سبق'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للمثبت في زمن الماضي التام المستمر:', 'options' => ['Subject + had + been +(v1+ing) + object \complement.', 'Subject + had + been + v3 + object \complement.', 'Subject + had +(v1+ing) + object \complement.', 'Subject + had + v3+ object \complement.'], 'correct' => 0],
        ['text' => 'اختر الكلمة المناسبة للجملة: (I __ been working on my project for six hours when my computer crashed.)', 'options' => ['Has', 'Had', 'Have', 'Was'], 'correct' => 1],
        ['text' => 'اختر الكلمة المناسبة للجملة: (They had been __ for weeks before they finally reached their destination.)', 'options' => ['Travel', 'Travelled', 'Travelling', 'Travels'], 'correct' => 2],
        ['text' => 'اختر الكلمة المناسبة للجملة: (She had __ studying Spanish for years before she felt comfortable speaking it.)', 'options' => ['Be', 'Being', 'Been', 'Was'], 'correct' => 2],
        ['text' => 'ليس جميع أسماء وضمائر الفاعل تأخذ الفعل المساعد (Had) في زمن الماضي التام المستمر.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'نضع v1+ing في زمن الماضي التام المستمر لجميع الأسباب التالية (ما عدا)؟', 'options' => ['يجب ان تكون في جميع الأزمنة المستمرةV1+ingلان', '(Beenلأنها أتت بعد الكينونة(', 'V1+ingلان جميع الأزمنة تأخذ', 'لا شيء'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة : (We had been waiting for the bus for over an hour before it finally arrived.)', 'options' => ['نحن قد كنا ننتظر لأجل الباص لمدة اكثر من ساعة قبل ان وصل اخيرا.', 'نحن قد ننتظر لأجل الباص لمدة اكثر من ساعة قبل ان يوصل اخيرا.', 'نحن قد انتظرنا لأجل الباص لمدة اكثر من ساعة قبل ان وصل اخيرا.', 'نحن سننتظر لأجل الباص لمدة اكثر من ساعة قبل ان يوصل اخيرا.'], 'correct' => 0],
        ['text' => 'اختر تكوين النفي الصحيح لزمن الماضي التام المستمر:', 'options' => ['Subject + had + been+ not +(v1+ing) + object \complement.', 'Subject + not + had + been + v3 + object \complement.', 'Subject + had + been+ not + v1 + object \complement.', 'Subject + had + not + been +(v1+ing) + object \complement.'], 'correct' => 3],
        ['text' => 'اختر النفي الصحيح للجملة: (She had been playing video games all day before her mother told her to go outside.)', 'options' => ['She not had been playing video games all day before her mother told her to go outside.', 'She hadn’t been playing video games all day before her mother told her to go outside.', 'She had been not playing video games all day before her mother told her to go outside.', 'None'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة: (We had been taking care of our sick mother for weeks before she finally recovered.)', 'options' => ['نحن قد كنا نعتني بوالدتنا المريضة لعدة أسابيع قبل هي أخيرا تعافت.', 'نحن ما قد كنا نعتني بوالدتنا المريضة لعدة أسابيع قبل هي أخيرا تعافت.', 'نحن لم نعتني بوالدتنا المريضة لعدة أسابيع قبل هي أخيرا تعافت.', 'نحن اعتنينا بوالدتنا المريضة لعدة أسابيع قبل هي أخيرا تعافت.'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لزمن الماضي التام المستمر:', 'options' => ['Had +Subject + been +(v1+ing) + object \complement.', 'Had +Subject + been +(v1+ing) + object \complement?', 'Had +Subject + been +v1 + object \complement?', 'Have\has+Subject + been +(v1+ing) + object \complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح ل (She had been working at the company.)', 'options' => ['Had she been working at the company?', 'Had she been work at the company?', 'Had she been working at the company.', 'Had she working at the company?'], 'correct' => 0],
        ['text' => 'ما معنى الفعل المساعد (Had) في سؤال زمن الماضي التام المستمر؟', 'options' => ['هل', 'هل قد', 'كان', 'امتلك'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة ل (Had he been studying for the exam?)', 'options' => ['هل قد كان يدرس لأجل الاختبار؟', 'هل يدرس لأجل الاختبار؟', 'هل درس لأجل الاختبار؟', 'هل كان يدرس لأجل الاختبار؟'], 'correct' => 0],
        ['text' => 'ماذا نعني ب Been في الماضي التام المستمر؟', 'options' => ['قد', 'كان \ كانت \ كانوا ....', 'لا', 'يكون \ يكونوا \ تكون ......'], 'correct' => 1],
        ['text' => 'يكون بديل زمن الماضي التام المستمر( في العامية فقط).', 'options' => ['زمن المضارع التام', 'زمن الماضي التام', 'زمن الماضي المستمر', 'لا شيء'], 'correct' => 2],
        ['text' => 'متى نعرف ان اختصار I’d هو ( I + had )؟', 'options' => ['V3اذا اتى الفعل بعده', 'v1+ingاذا اتى بعده', 'V2اذا اتى الفعل بعده', 'None'], 'correct' => 1],
        ['text' => 'رتب الجملة: (Hani had been treating his patient.\his patient died \ before)', 'options' => ['Hani had been treating his patient before his patient died.', 'Before Hani had been treating his patient, his patient died.', 'Hani had been treating his patient, his patient died before.', 'All of the above'], 'correct' => 0],
        ['text' => 'اختر الإجابة الصحيحة لجملة (Ali __ carrying his bag __the bag fell off.)', 'options' => ['Has been , before', 'Had been , before', 'Had been, after', 'None'], 'correct' => 1],
        ['text' => 'اختر الإجابة الصحيحة لجملة: (Hatem ___ been __ about his food __ the delivery guy arrived.)', 'options' => ['Had, asked , before', 'Had , ask , before', 'Had , asking , before', 'None'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (He had been lifting weights before he broke his leg).', 'options' => ['هو قد كان يرفع الاثقال قبل ان كسر قدمه', 'هو يرفع الاثقال قبل ان يكسر قدمه', 'هو كان يرفع الاثقال قبل ان يكسر قدمه', 'None'], 'correct' => 0],
        ['text' => 'اختر البديل للماضي التام المستمر (The dog had been barking before I opened the door.)', 'options' => ['The dog is barking before I opened the door.', 'The dog had barking before I opened the door.', 'The dog was barking before I opened the door.', 'None'], 'correct' => 2],
        ['text' => 'اختر النفي للجملة: (I had been taking care of my teeth before I had cavity.)', 'options' => ['I hadn’t been taking care of my teeth before I had caving.', 'I not had been taking care of my teeth before I had caving.', 'I had been not taking care of my teeth before I had caving.', 'None'], 'correct' => 0],
        ['text' => 'اختر الإجابة الصحيحة للجملة (My car had been __ well before I had the accident.)', 'options' => ['Working', 'Work', 'Worked', 'Works'], 'correct' => 0],
        ['text' => 'في السؤال على الماضي التام المستمر اول الجملة يكون الفاعل ثم يأتي الفعل المساعد ثانيا.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'الجواب على أي سؤال في الماضي التام المستمر يكون_______', 'options' => ['Yes\No + , + had + subject.', 'Yes\No + , + subject+ had.', 'Yes\No + had + subject.', 'Yes\No + , + subject + had\hadn’t.'], 'correct' => 3],
        ['text' => 'إذا اتى الفاعل كإسم ( ليس ضمير فاعل) في سؤال على الماضي التام المستمر فاننا في الإجابة على السؤال نستخدم نفس اسم الفاعل ولا نحوله الى ضمير فاعل مثل: (Yes, Saleh had.)', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'كلمات الربط في الماضي التام المستمر نقدر نستخدمها في اول الجمل', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر كلمة الربط المناسبة للجملتين: (Muna opened her bakery __ she had been baking for years.)', 'options' => ['Before', 'After', 'Now', 'While'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للسؤال (Nabil \ been\learning\had\photography\?)', 'options' => ['Had Nabil been learning photography?', 'Nabil had been learning photography?', 'Nabil been had learning photography?', 'Nabil been had learning photography?'], 'correct' => 0],
        ['text' => 'بعد ترتيب السؤال (Nabil \ been\learning\had\photography\?) اختر الإجابة الصحيحة له:', 'options' => ['Yes, Nabil had.', 'Yes, she had.', 'No, he hadn’t.', 'None'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي التام المستمر (Past Perfect Continuous Grammar)',
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
        ];

        if ($props['question_type'] === 'drag_drop') {
            $props['matching_pairs'] = $qData['matching_pairs'];
        } else {
            $props['option_a'] = $qData['options'][0] ?? null,
            $props['option_b'] = $qData['options'][1] ?? null,
            $props['option_c'] = $qData['options'][2] ?? null,
            $props['option_d'] = $qData['options'][3] ?? null,
            $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A',
        }

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1040.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
