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
        ['text' => 'ما معنى زمن الماضي التام في اللغة الإنجليزية؟', 'options' => ['Past Simple', 'Past Perfect', 'Past Continuous', 'Present Perfect'], 'correct' => 1],
        ['text' => 'يعبر الماضي التام عن حدث وقع قبل حدث آخر في الماضي؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'الماضي التام هو الحدث الثاني وقوعاً في الجملة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // He is the first occurrence
        ['text' => 'اختر التكوين الصحيح للمثبت في الماضي التام:', 'options' => ['Subject + had + v3 + object', 'Subject + have + v3', 'Subject + had + v1', 'Subject + has + v3'], 'correct' => 0],
        ['text' => 'الكلمة التي ترمز وتدل على الماضي التام هي:', 'options' => ['Have', 'Has', 'Had', 'Did'], 'correct' => 2],
        ['text' => 'يأتي الفعل بعد Had في التصريف:', 'options' => ['الأول', 'الثاني', 'الثالث', 'الرابع'], 'correct' => 2],
        ['text' => 'ضمائر الفاعل التي تأخذ Had هي:', 'options' => ['I – he – she – it', 'They – we – you', 'جميع ضمائر الفاعل', 'لا شيء'], 'correct' => 2],
        ['text' => 'الجملة التي تعبر عن الماضي التام هي:', 'options' => ['I had already done my homework.', 'I done my homework', 'I did my homework', 'I’m doing'], 'correct' => 0],
        ['text' => 'ما معنى Had في الماضي التام؟', 'options' => ['يملك', 'قد', 'سوف', 'كان'], 'correct' => 1],
        ['text' => 'نستخدم الماضي التام في حالة التمني والأشياء الغير حقيقية في الماضي؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن التمني في الماضي التام:', 'options' => ['I wish I had been there.', 'I was there', 'I’m there', 'I will'], 'correct' => 0],
        ['text' => 'أكمل: (I __ arrived there when the rain __) اختر الكلمات:', 'options' => ['had \ started', 'have \ started', 'had \ starts', 'did \ start'], 'correct' => 0],
        ['text' => 'الحدث الثاني في الماضي التام دائماً يكون في زمن:', 'options' => ['مضارع بسيط', 'ماضي مستمر', 'ماضي بسيط', 'مضارع تام'], 'correct' => 2],
        ['text' => 'التصريف الثالث للفعل See هو:', 'options' => ['See', 'Saw', 'Seen', 'Seeing'], 'correct' => 2],
        ['text' => 'التصريف الثالث للفعل (يكون) Be هو:', 'options' => ['Was', 'Were', 'Been', 'Be'], 'correct' => 2],
        ['text' => 'التصريف الثالث للفعل (يكتب) Write هو:', 'options' => ['Write', 'Wrote', 'Written', 'Writes'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح لنفي الماضي التام:', 'options' => ['Subject + had + not + v3', 'Subject + not had', 'not + have', 'did not'], 'correct' => 0],
        ['text' => 'اختصار Had not هو:', 'options' => ['Had’nt', 'Hadnt’', 'Hadn’t', 'Hasn’t'], 'correct' => 2],
        ['text' => 'النفي الصحيح لـ (Sarah had forgotten her phone):', 'options' => ['Sarah had not forgotten her phone.', 'Sarah not had', 'Sarah hadn’t forget', 'none'], 'correct' => 0],
        ['text' => 'كيف نسأل في الماضي التام (نعم/لا)؟', 'options' => ['Had + subject + v3... ?', 'Had + v3 + subject', 'Do + subject', 'Have + subject'], 'correct' => 0],
        ['text' => 'ما معنى Had في أول السؤال؟', 'options' => ['هل كان', 'هل قد', 'هل سوف', 'هل يملك'], 'correct' => 1],
        ['text' => 'أكمل السؤال: (__ she __ her keys?)', 'options' => ['Had \ found', 'Has \ found', 'Did \ find', 'Had \ find'], 'correct' => 0],
        ['text' => 'الإجابة على (Had she found her keys?) هي:', 'options' => ['Yes, she had.', 'Yes, she has.', 'No, she didn’t.', 'Yes, she did.'], 'correct' => 0],
        ['text' => 'أكمل: (I ___ cleaned the house by the time my guests ___) ', 'options' => ['had \ arrived', 'have \ arrive', 'has \ arrived', 'had \ arrives'], 'correct' => 0],
        ['text' => 'كلمات الربط في الماضي التام هي:', 'options' => ['By the time', 'Before \ after', 'Since \ for', 'جميع ما سبق (للترتيب الزمني)'], 'correct' => 3],
        ['text' => 'ما معنى By the time؟', 'options' => ['في ذاك الوقت', 'في الوقت الذي فيه', 'بعد ذلك', 'قبل ذلك'], 'correct' => 1],
        ['text' => 'بماذا نختصر الضمير مع Had؟ مثلاً I had:', 'options' => ['I’h', 'I’d', 'I’v', 'I’s'], 'correct' => 1],
        ['text' => 'بماذا نختصر الضمير مع would؟ مثلاً I would:', 'options' => ['I’w', 'I’d', 'I’v', 'I’l'], 'correct' => 1], // Same abbreviation 'd
        ['text' => "كيف نفرق بـ 'd المختصرة بين Had و Would؟", 'options' => ['بعد had يأتي v3 وبعد would يأتي v1', 'كلاهما واحد', 'بالمعنى فقط', 'لا شيء'], 'correct' => 0],
        ['text' => 'أكمل: (They’d __ already ____ of the project)', 'options' => ['Heard', 'Hear', 'Hearing', 'Hears'], 'correct' => 0],
        ['text' => 'كلمة (بالفعل) في الإنجليزية:', 'options' => ['Ready', 'Already', 'Yet', 'Just'], 'correct' => 1],
        ['text' => 'كلمة (للتو) في الإنجليزية:', 'options' => ['Already', 'Just', 'Yet', 'Since'], 'correct' => 1],
        ['text' => 'المكان الصحيح لـ Just / Already في جملة الماضي التام:', 'options' => ['بين had والفعل الأساسي', 'أول الجملة', 'آخر الجملة', 'بعد المفعول'], 'correct' => 0],
        ['text' => 'ماضي كلمة Lose هي:', 'options' => ['Loser', 'Lost', 'Losted', 'Losing'], 'correct' => 1],
        ['text' => 'تصريف (يذهب) Go الثالث هو:', 'options' => ['Go', 'Went', 'Gone', 'Going'], 'correct' => 2],
        ['text' => 'ترجمة: I had been alone:', 'options' => ['انا كنت وحيداً لقترة', 'انا قد كنت وحيداً.', 'انا سوف اكون', 'انا وحيد'], 'correct' => 1],
        ['text' => 'ترجمة: They had lived in Dubai:', 'options' => ['هم سكنوا', 'هم قد سكنوا في دبي.', 'سوف يسكنون', 'يسكنون الان'], 'correct' => 1],
        ['text' => 'هل نستخدم الماضي التام عند سرد قصة وتحكي عن حدث قبل البداية؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'أيهما أقدم (The movie had started) ام (we arrived)؟', 'options' => ['الأولى', 'الثانية', 'في نفس الوقت', 'لا شيء'], 'correct' => 0],
        ['text' => 'ترجمة: (هل صليت قبل أن تخرج؟):', 'options' => ['Had you prayed before you went out?', 'Have you prayed...', 'Did you pray...', 'none'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي التام (Past Perfect Grammar)',
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
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'option_a' => $qData['options'][0] ?? null,
            'option_b' => $qData['options'][1] ?? null,
            'option_c' => $qData['options'][2] ?? null,
            'option_d' => $qData['options'][3] ?? null,
            'correct_answer' => $letterMap[$qData['correct']] ?? 'A',
            'points' => 1,
        ]);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1031.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
