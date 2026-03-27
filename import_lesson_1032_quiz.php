<?php

/**
 * Script to import questions for Lesson ID 1032 (Past Perfect Translation)
 * php import_lesson_1032_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1032;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1032 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'I had visited this town before the army stationed me here. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا قد زرت هذه البلدة بعد ان يرابطني الجيش هنا.', 'انا قد زرت هذه البلدة قبل ان يرابطني الجيش هنا.', 'انا قد زرت هذه الدولة قبل ان يرابطني الجيش هنا.', 'انا أزور هذه البلدة قبل ان يرابطني الجيش هنا.'], 'correct' => 1],
        ['text' => 'When AbdullRahman arrived at the station, the bus had already left. اختر الترجمة الصحيحة للجملة:', 'options' => ['عندما عبدالرحمن وصل الى المحطة، الباص قد غادر مسبقا.', 'عندما عبدالرحمن وصل الى المحطة، القطار قد غادر مسبقا.', 'عندما عبدالرحمن وصل الى البلدة، الباص قد غادر مسبقا.', 'عندما عبدالرحمن يصل الى المحطة، الباص يغادر مسبقا.'], 'correct' => 0],
        ['text' => 'When my husband arrived home, I had already finished cooking. اختر الترجمة الصحيحة للجملة:', 'options' => ['عندما زوجتي وصل البيت انا قد انتهيت من الطبخ مسبقا.', 'عندما زوجي وصل العمل انا قد انتهيت من الطبخ مسبقا.', 'عندما زوجي وصل البيت انا قد انتهيت من الطبخ مسبقا.', 'عندما زوجي وصل البيت انا قد انتهيت من التنظيف مسبقا.'], 'correct' => 2],
        ['text' => 'Omar had washed his face before he went to school. اختر الترجمة الصحيحة للجملة:', 'options' => ['عمر قد غسل وجهه قبل ان ذهب ( هو) الى المدرسة.', 'عمر قد غسل يديه قبل ان ذهب ( هو) الى المدرسة.', 'عمر قد غسل وجهه قبل ان عاد ( هو) من المدرسة.', 'عمر يغسل وجهه قبل ان ذهب ( هو) الى المدرسة.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( بعد ان اصلحت دراجتي النارية، ذهبت (انا) في جولة.)', 'options' => ['After I had repaired my car , I went for a drive.', 'After I had repaired my motorcycle, I went for a drive.', 'After I had washed my motorcycle, I went for a drive.', 'After I had repaired my motorcycle, I went for a swim.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (عندما احمد وصل الى الاحتفال ، عمر قد ذهب الى المنزل مسبقا.)', 'options' => ['when Ahmed arrived at the festival, Omar had already gone home.', 'when Ahmed arrives at the festival, Omar has already gone home.', 'when Ahmed arrived at the conference, Omar had already gone school.', 'when Omar arrived at the festival, Ahmed had already gone home.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( انا فهمت الفيلم لاني قد قرأت(انا) رواية من قبل.)', 'options' => ['I had understood the movie because I had read the novel before.', 'I understood the movie because I had read the novel before.', 'I understood the accident because I had read the novel before.', 'She understood the movie because she had read the novel before.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( صبا قد درست العربية قبل ان تعيش (هي) في مصر.)', 'options' => ['Seba  had studied Arabic before she lived in Palestine.', 'Seba  had studied Arabic before she lived in Egypt.', 'Seba  had studied Arabic before she travelled to Egypt.', 'Seba had taught Arabic before she lived in Egypt.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( انا لم أشعر بتحسن كبير بعد ان تناولت (انا) الدواء.)', 'options' => ['I didn’t feel much better after I had taken the medicine.', 'I didn’t felt much better after I had taken the medicine.', 'I hadn’t felt much better after I hadn’t taken the medicine.', 'I felt much better after I had taken the course.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( انا ما قد استمعت الى هذا التسجيل الصوتي قبل ان ينشروه.)', 'options' => ['I hadn’t listened to this podcast before they published it.', 'I hadn’t listened to this forecast before they had published it.', 'I didn’t listened to this podcast before they prepared it.', 'I had listened to this podcast before they published it.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( المدينة ما قد بنت هذا الجسر قبل ان ننتقل (نحن) الى هذه المدينة.)', 'options' => ['The city hadn’t built this bridge after we moved to this city.', 'The city hadn’t built this bridge before we moved to this city.', 'The city hadn’t built this bridge after we had moved to this city.', 'The city hadn’t built this bridge before you moved to this city.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الممرضة ما قد نظفت الجرح قبل ان ياتى الطبيب.)', 'options' => ['The nurse hadn’t cleaned the wound before the doctor came.', 'The nurse had cleaned the wound before the doctor came.', 'The doctor hadn’t cleaned the wound before the nurse came.', 'The nurse hadn’t cleaned the wound after the doctor came.'], 'correct' => 0],
        ['text' => 'Had the teller checked my balance before I withdrew my money? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قد الصراف تأكد من رصيدي قبل ان سحبت اموالي؟', 'هل قد مدير البنك تأكد من رصيدي قبل ان سحبت اموالي؟', 'هل قد الصراف تأكد من رصيدي قبل ان أودعت اموالي؟', 'هل الصراف يتأكد من رصيدي قبل ان اسحب اموالي؟'], 'correct' => 0],
        ['text' => 'Had Mansour already given you his car keys before I offered you mine? اختر الترجمة الصحيحة للسؤال :', 'options' => ['هل قد منصور اخذ منك مسبقا مفتاح سيارته قبل انا عرضت عليك مفاتيحي؟', 'هل قد منصور اعطاك مسبقا مفتاح سيارته قبل انا عرضت عليك مفاتيحي؟', 'هل منصور اعطاك مسبقا مفتاح سيارته قبل انا أخذت منك مفاتيحي؟', 'هل منصور يعطيك مسبقا مفتاح سيارته قبل انا أعرض عليك مفاتيحي؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل قد تابعت هذه القناة قبل ان أصبحت مشهورة؟)', 'options' => ['Had you followed this channel before it became famous.', 'Had you followed this channel before it became famous?', 'Had you followed this site before it became famous?', 'Did you follow this channel before it become famous?'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل قد عامل الدهان اختار لون الدهان قبل ان دهن ؟)', 'options' => ['Had the painter chosen the paint color before he painted?', 'Had the painter chosen the paint color before he painted.', 'Did the painter chosen the paint color before he had painted?', 'Had the painter choose the paint color before he painted?'], 'correct' => 0],
        ['text' => '(Elephant – already – eaten – had – the) اختر الترتيب الصحيح لجملة :', 'options' => ['The elephant already had eaten.', 'The elephant had already eaten.', 'The already elephant had eaten.', 'The eaten elephant had already.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة : (Had – alarm – had – gone – off – before – the – came – firefighters.)', 'options' => ['The alarm had gone off before the firefighters came.', 'The firefighters came before the alarm had gone off.', 'The firefighters had gone off before the alarm came.', 'The alarm had gone off the firefighters before came.'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة الماضي التام (Past Perfect Translation)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 30,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    $quiz->questions()->detach();
    $letterMap = ['A', 'B', 'C', 'D'];
    foreach ($questionsData as $idx => $qData) {
        $question = Question::create([
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => 'multiple_choice',
            'option_a' => $qData['options'][0] ?? null,
            'option_b' => $qData['options'][1] ?? null,
            'option_c' => $qData['options'][2] ?? null,
            'option_d' => $qData['options'][3] ?? null,
            'correct_answer' => $letterMap[$qData['correct']] ?? 'A',
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1032.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
