<?php

/**
 * Script to import questions for Lesson ID 1031 (Past Perfect Grammar)
 * php import_lesson_1031_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1031;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1031 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما هو زمن (الماضي التام) في اللغة الإنجليزية؟', 'options' => ['Present simple', 'Present continuous', 'Present perfect', 'Past perfect'], 'correct' => 3],
        ['text' => 'ما الذي يعبر عنه زمن (الماضي التام)؟', 'options' => ['حدث قطع حدث اخر', 'سلوك وعادات متكررة', 'ترتيب وربط حدثين في الماضي', 'حقائق وعادات في الماضي'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للمثبت في زمن الماضي التام؟', 'options' => ['Subject + has \ have + v3 + object\complement.', 'Subject + had + v3 + object\complement.', 'Subject + was \ were + v3 + object\complement.', 'Subject + had + v1 + object\complement.'], 'correct' => 1],
        ['text' => 'جميع أسماء وضمائر الفاعل سواء مفرد او جمع تأخذ الفعل المساعد (Had)', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن زمن الماضي التام:', 'options' => ['The car crashed because I had driven so fast.', 'The car crashed because I was driving so fast.', 'The car crashed because I have driven so fast.', 'The car crashed because I drive so fast.'], 'correct' => 0],
        ['text' => 'اختر الفعل المساعد الصحيح لجملة (The car crashed because I __driven so fast.)', 'options' => ['Has', 'Have', 'Had', 'Did'], 'correct' => 2],
        ['text' => 'ترجمنا الفعل المساعد (Had) في الماضي التام الى:', 'options' => ['هل', 'سوف', 'قد', 'كان'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (The car crashed because I had driven so fast.)', 'options' => ['السيارة تحطمت لأنني قد سقت بسرعة كبيرة.', 'السيارة تحطمت لأنني سقت بسرعة كبيرة.', 'السيارة تحطمت لأنني أقود بسرعة كبيرة.', 'السيارة تحطمت لأنني سأقود بسرعة كبيرة.'], 'correct' => 0],
        ['text' => 'تحتوي الجملة (The car crashed because I had driven so fast.) على ظرف واحد فقط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ما هي الظروف في جملة (The car crashed because I had driven so fast.)?', 'options' => ['Because \ so', 'Fast \ so', 'fast', 'Crashed \ fast'], 'correct' => 1],
        ['text' => 'اختر الفعل المناسب في جملة (They had already __ dinner when I arrived.)', 'options' => ['Eat', 'Ate', 'Eaten', 'Eated'], 'correct' => 2],
        ['text' => 'في زمن الماضي التام نربط بين زمنين احدهما ماضي تام والثاني يكون', 'options' => ['مضارع تام', 'ماضي بسيط', 'مضارع بسيط', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اختر ادوات الربط التي تستخدم للربط بين زمن الماضي التام والماضي البسيط:', 'options' => ['When \ what', 'When \ because \ after \ before', 'But \ or \ however', 'None'], 'correct' => 1],
        ['text' => 'من اين اتى الفعل المساعد (Had)؟', 'options' => ['التصريف الثالث من (Did)', 'الماضي من (Have \ has)', 'الماضي من (Was \ were)', 'None'], 'correct' => 1],
        ['text' => 'اختر الفعل المناسب لجملة (They had travelled to Europe before they __ married.)', 'options' => ['Get', 'Had got', 'Got', 'Getting'], 'correct' => 2],
        ['text' => 'الماضي التام يربط بين حدثين جملتين ( أي جملة من الجمل التالية ماضي بسيط)؟ (They had travelled to Europe before they got married)؟', 'options' => ['They had travelled to Europe', 'They got married', 'None'], 'correct' => 1],
        ['text' => 'اين كلمة الربط في جملة (They had travelled to Europe before they got married)؟', 'options' => ['Had', 'Before', 'To', 'None'], 'correct' => 1],
        ['text' => 'اختر الماضي التام في (They had travelled to Europe before they got married)؟', 'options' => ['They had travelled to Europe', 'They got married', 'None'], 'correct' => 0],
        ['text' => 'في أي تصريف من تصريفات الفعل يكون زمن الماضي التام:', 'options' => ['V1', 'V2', 'V3', 'None'], 'correct' => 2],
        ['text' => 'ما هو زمن الحدث الأول الذي بدا وانتهى قبل الحدث الثاني في زمن الماضي التام؟', 'options' => ['Past simple', 'Present perfect', 'Past perfect', 'None'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للنفي في زمن الماضي التام:', 'options' => ['Subject + has \ have + not+ v3 + object\complement.', 'Subject + had+ not + v3 + object\complement.', 'Subject +not + had + v3 + object\complement.', 'Subject + had + not+ v1 + object\complement.'], 'correct' => 1],
        ['text' => 'اختار النفي الصحيح للجملة التالية (They had finished earlier before the guest arrived.)', 'options' => ['They not had finished earlier before the guest arrived', 'They hadn’t finished earlier before the guest arrived', 'They had not finish earlier before the guest arrived', 'They had finished not earlier before the guest arrived'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة: (She had finished her homework before she went to the party)', 'options' => ['هي قد انتهت من واجبها قبل ان تذهب الى الحفلة', 'هي قد تنتهي من واجبها قبل ان تذهب الى الحفلة', 'هي سوف تنتهي من واجبها قبل ان يذهب الى الحفل', 'لا شيء مما سبق'], 'correct' => 0],
        
        [
            'text' => 'صل ما بين ضمير الفاعل واختصاره مع الفعل المساعد:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I had', 'right' => 'I’d'],
                ['left' => 'We had', 'right' => 'We’d'],
                ['left' => 'She had', 'right' => 'Shed'],
                ['left' => 'He had', 'right' => 'He’d'],
                ['left' => 'You had', 'right' => 'You’d'],
                ['left' => 'They had', 'right' => 'They’d'],
            ]
        ],

        ['text' => 'اختر تكوين السؤال الصحيح لزمن الجملة في الماضي التام ( ليس الماضي البسيط)', 'options' => ['Had + subject +v2 + object \ complement?', 'Had + subject +v3 + object \ complement?', 'Had + subject +v1 + object \ complement?', 'Did + subject +v1 + object \ complement?'], 'correct' => 1],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة (He had paid the price.)', 'options' => ['Did he paid the price?', 'Had he paid the price?', 'Has he paid the price?', 'Had he pay the price?'], 'correct' => 1],
        ['text' => 'ما معنى الفعل المساعد (Had) في سؤال الماضي التام؟', 'options' => ['قد', 'هل قد', 'امتلك', 'كان'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (Had he left the restaurant?)', 'options' => ['هل غادر المطعم؟', 'هل قد غادر المطعم؟', 'هل يغادر المطعم؟', 'هل غادر المطعم؟'], 'correct' => 1],

        [
            'text' => 'صل ما بين كل سؤال وجوابه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Had you received an email?', 'right' => 'Yes, I had. \No, I hadn’t.'],
                ['left' => 'Had Sharifah come back?', 'right' => 'Yes, she had.\ No, she hadn’t.'],
                ['left' => 'Had Bilal travelled abroad?', 'right' => 'Yes , he had.\ No, he hadn’t .'],
                ['left' => 'Had they asked you a question?', 'right' => 'Yes, they had.\No, they hadn’t.'],
            ]
        ],

        ['text' => 'الحدث الأول في الماضي التام يكون ماضي بسيط.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (Azzah had gone home before they arrived)', 'options' => ['عزة قد ذهبت المنزل قبل ان يصلوا.', 'عزة ذهبت المنزل قبل هم يصلوا.', 'عزت تذهب المنزل قبل هم يصلوا.', 'عزة ستذهب المنزل قبل ان يصلوا.'], 'correct' => 0],
        ['text' => 'اختر جملة الماضي التام (Huda had picked up the milk before the store closed.)', 'options' => ['Huda had picked up the milk', 'The store closed', 'Before', 'None'], 'correct' => 0],
        ['text' => 'اختر باقي الجملة الصحيحة لجملة (______________ when Faleh stopped by)', 'options' => ['Khalid had left work.', 'Khalid left work.', 'Khalid is leaving work.', 'Khalid was leaving work.'], 'correct' => 0],
        ['text' => 'اختر أداة الربط الصحيحة ل (Asma had won a prize ___ she died)', 'options' => ['After', 'Before', 'And', 'While'], 'correct' => 1],
        ['text' => 'اختر باقي الجملة الصحيحة لجملة (______________ before I saw it)', 'options' => ['I had heard the lightening.', 'I heard the lightening.', 'I heared the lightening.', 'I was hearing the lightening.'], 'correct' => 0],
        ['text' => 'اكمل الجملة بالكلمات المناسبة (__ Saleh __ ___ the alarm , he ___)', 'options' => ['After \ has \ heard \ wake up', 'Before \ had \ heard \ woke up', 'After \ had \ heard \ woke up', 'After \ has \ heard \ woke up'], 'correct' => 2],
        ['text' => 'رتب الجملة التالية (We had finished exercising \ after \ we went home)', 'options' => ['After we had finished exercising, we went home.', 'we had finished exercising after we went home.', 'After we had finished exercising we went home.', 'we had finished exercising, we went home after.'], 'correct' => 0],
        ['text' => 'اختر الكلمات المناسبة الصحيحة لجملة (I closed the book__ I __ read it).', 'options' => ['Before\ does', 'After \ had', 'When \ have', 'After \ have'], 'correct' => 1],
        ['text' => 'نضيف كلمة Not في نفي الماضي البسيط (بين الفاعل والفعل المساعد).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الإجابة الصحيحة في زمن الماضي التام (They ___ solved the puzzle).', 'options' => ['Haven’t', 'Hasn’t', 'Hadn’t', 'Weren’t'], 'correct' => 2],
        ['text' => 'اعد ترتيب الجملة التالية (Saleh \ returned \ had \ the gift \not)', 'options' => ['Saleh had not returned the gift.', 'Saleh not had returned the gift.', 'The Saleh had not returned gift.', 'Saleh had returned not the gift.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة التالية ( محمد لم ينجح في اختبار القيادة )', 'options' => ['Mohammed not had pass the driving test.', 'Mohammed not had passed the driving test.', 'Mohammed had not passed the driving test.', 'Mohammed hadn’t passed the driving test.'], 'correct' => 3],
        ['text' => 'اختر تكوين السؤال الصحيح ل (She had turned off the A.C before she left.)', 'options' => ['Had she turn off the A.C before she leave?', 'Did she turned off the A.C before she left?', 'Had she turn off the A.C before she left?', 'Had she turned off the A.C before she left?'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (Had you painted the wall before you moved in?)', 'options' => ['هل قد صبغت الجدار قبل انت ان تسكن', 'هل يصبغ الجدار قبل انت ان تسكن', 'هل صبغ الجدار قبل انت ان تسكن', 'هل كنت تصبغ الجدران قبل ان تسكن'], 'correct' => 0],
        ['text' => 'استخدام الماضي البسيط مع الماضي البسيط بدلا من (الماضي التام مع البسيط) يعتبر طريقة رسمية وغير عامية.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الإجابة الصحيحة ل (__)He cleaned his hands before he touched the chemical?', 'options' => ['Has', 'Have', 'Had', 'Was'], 'correct' => 2],
        ['text' => 'اختر الكلمات الناقصة الصحيحة لجملة (___ you given __ envelope __ you sent it.)', 'options' => ['Had \ Saleh \ after', 'Had \ Saleh \ before', 'Did\ Saleh \ before', 'Has \ Saleh \ before'], 'correct' => 1],
        ['text' => 'اختر الإجابات الصحيحة ل (had Sarah ___ the newspaper ___ she threw it away?)', 'options' => ['Read \ after', 'Read \ before', 'Readed \ before', 'After \ read'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي التام (Past Perfect Grammar)',
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
            $props['correct_answer'] = 'A'; // Default for matching
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1031.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
