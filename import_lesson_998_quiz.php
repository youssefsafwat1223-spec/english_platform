<?php

/**
 * Script to import questions for Lesson ID 998 (Present Perfect Translation/Ordering)
 * php import_lesson_998_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 998;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 998 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'He has already achieved his fitness goal. اختر الترجمة الصحيحة للجملة:', 'options' => ['هو قد حقق من قبل هدف اللياقة الخاص به.', 'هو يحقق هدف اللياقة الخاص به.', 'هو سيحقق هدف اللياقة الخاص به.', 'لا يحقق الان هدف اللياقة الخاص به.'], 'correct' => 0],
        ['text' => 'khalid has won several poetry competitions recently. اختر الترجمة الصحيحة للجملة:', 'options' => ['خالد فاز بعدة جوائز شعرية.', 'خالد قد فاز بعدة جوائز شعرية مؤخرا.', 'خالد يفوز بعدة جوائز شعرية مؤخرا.', 'خالد سوف يفوز بعدة جوائز شعرية مؤخرا.'], 'correct' => 1],
        ['text' => 'Waleed has just learned to sail. اختر الترجمة الصحيحة للجملة:', 'options' => ['وليد سوف قد يتعلم ان يبحر.', 'وليد قد تعلم للتو ان يبحر (الإبحار).', 'وليد يتعلم الإبحار.', 'وليد سوف يتعلم ان يبحر.'], 'correct' => 1],
        ['text' => 'Eman has learned to make pottery. اختر الترجمة الصحيحة للجملة:', 'options' => ['ايمان تعلمت ان تصنع فخار.', 'ايمان تتعلم ان تصنع فخار.', 'ايمان قد تعلمت ان تصنع فخار.', 'ايمان قد تعلم صنع الفخار.'], 'correct' => 2],
        ['text' => 'You have forgotten to switch off the light. اختر الترجمة الصحيحة للجملة:', 'options' => ['انت قد نسيت ان تطفئ النور.', 'انت تنسى ان تطفئ النور.', 'انت نسيت ان تطفئ النور.', 'انت قد تذكرت ان تطفئ النور.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (أنا قد شربت للتو عصير برتقال)', 'options' => ['drunk apple juice.', 'I have just drunk orange juice.', 'I haven’t drunk orange juice yet.', 'I drink orange juice.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (المزارعون قد زرعوا البذور مؤخرا)', 'options' => ['Farmers have planted the seeds recently.', 'Farmers have planted the seeds just.', 'Farmers planted checking recently.', 'Farmers plant the seeds recently.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (طبيب الاسنان قد نسي موعدي)', 'options' => ['dentist have missed...', 'The dentist has missed my appointment.', 'dentist missed...', 'dentist has miss...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة (مدير الموارد البشرية قد قابل العديد من المرشحين)', 'options' => ['HR manager have interviewed...', 'HR manager has interviewed few...', 'The HR manager has interviewed several candidates.', 'HR manager interviewed several...'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة لجملة (روابي قد تخرجت من قبل من كلية طبية)', 'options' => ['Rawabi has already graduated from a medical college.', 'Rawabi has already graduated from a medical school.', 'Rawabi have already graduated...', 'Rawabi graduates...'], 'correct' => 0],
        ['text' => 'Seba hasn’t gone to school yet. اختر الترجمة الصحيحة للجملة:', 'options' => ['صبا ما قد ذهبت الى المدرسة بعد.', 'صبا لا تذهب الى المدرسة.', 'صبا ما قد ذهبت الى المشفى حتى الان.', 'صبا ما ذهبت الى المدرسة بالأمس.'], 'correct' => 0],
        ['text' => 'Nada’ s not started painting yet. اختر الترجمة الصحيحة للجملة:', 'options' => ['لم تبدأ الرسم كـهواية بعد.', 'ندى ما قد بدأت الرسم كـهواية بعد.', 'ندى بدأت الرسم كـهواية', 'ندى سوف لن تبدأ بالرسم بعد'], 'correct' => 1],
        ['text' => 'Sharif and Azza haven’t been to Makkah. اختر الترجمة الصحيحة للجملة:', 'options' => ['شريفة وعزة ما قد ذهبوا الى مكة.', 'شريفة وعزة ذهبوا الى مكة.', 'شريفة وعزة يذهبون الى مكة.', 'شريفة وعزة سوف لن يذهبون الى مكة.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (القطة ما قد اصطادت عدة فئران)', 'options' => ['cat hasn’t catch...', 'The cat hasn’t caught several mice', 'cat caught several mice', 'cat didn’t catch...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (الحصان ما قد فاز بعدة سباقات)', 'options' => ['donkey hasn’t won...', 'The horse hasn’t won several races.', 'horse has won...', 'horse doesn’t win...'], 'correct' => 1],
        ['text' => 'Has the teacher taught us this lesson before? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل قد المعلم علمنا هذا الدرس من قبل؟', 'المعلم ما قد علمنا هذا الدرس من قبل.', 'المعلم علمنا هذا الدرس من قبل', 'هل قد الطبيب علمنا هذا الدرس من قبل؟'], 'correct' => 0],
        ['text' => 'Has the taxi driver lost his destination? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل التاكسي فقدت وجهتها؟', 'هل قد سائق التاكسي فقد وجهته؟', 'سائق التاكسي قد فقد وجهته', 'سائق التاكسي ما قد فقد وجهته'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل قد نور المدينة انطفأ؟)', 'options' => ['Has the city light has turned off?', 'The city light has turned off?', 'The city light hasn’t turned off.', 'Has the city light has turn off?'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل قد الحصان كسر كاحله؟)', 'options' => ['The horse has broken...', 'Has the horse broken his ankle?', 'Has the lion has broken...', 'The horse hasn’t broken...'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل قد النجار صنع الطاولة الخشبية؟)', 'options' => ['carpenter has made...', 'Has the carpenter made the wooden table?', 'farmer made the wooden table?', 'carpenter make the wooden...'], 'correct' => 1],
        ['text' => 'Khalid - have - Mohammed - taken - the - and – train اختر الترتيب الصحيح للجملة:', 'options' => ['Khalid and Mohammed have taken the train.', 'Khalid and Mohammed have the train taken.', 'Khalid and Mohammed have the taken train.', 'Khalid have taken and Mohammed...'], 'correct' => 0],
        ['text' => '(Just - Lost - have - I - headphones - my) اختر الترتيب الصحيح للجملة:', 'options' => ['I just have lost...', 'I have just lost my headphones.', 'I have my just lost...', 'I have just my headphones lost.'], 'correct' => 1],
        ['text' => 'she - has -submitted - ? - resume -her اختر الترتيب الصحيح للسؤال:', 'options' => ['?She has her submitted resume', 'She has submitted her resume?', 'Has She submitted her resume?', 'She submitted has her...'], 'correct' => 2],
        ['text' => 'Explorers - travelled - have - distance - the - a long اختر الترتيب الصحيح للجملة:', 'options' => ['explorers travelled have...', 'long distance have travelled explorers.', 'The explorers have travelled a long distance.', 'distance have travelled...'], 'correct' => 2],
        ['text' => 'Car - dealership - has - discount - a month - for - made - the اعد ترتيب الجملة:', 'options' => ['dealership discount has made...', 'car has dealership made...', 'dealership has made discount a month for.', 'The car dealership has made discount for a month.'], 'correct' => 3],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار ترجمة وترتيب (Present Perfect)',
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
            'option_a' => $qData['options'][0],
            'option_b' => $qData['options'][1],
            'option_c' => $qData['options'][2],
            'option_d' => $qData['options'][3],
            'correct_answer' => $letterMap[$qData['correct']],
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 998.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
