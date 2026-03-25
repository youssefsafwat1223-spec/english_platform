<?php

/**
 * Script to import questions for Lesson ID 1024 (Past Continuous Grammar)
 * php import_lesson_1024_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1024;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1024 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى ماضي مستمر في اللغة الإنجليزية؟', 'options' => ['Past simple', 'Present simple', 'Past continuous', 'Present continuous'], 'correct' => 2],
        ['text' => 'يعبر الماضي المستمر عن جميع ما يلي ما عدا:', 'options' => ['فترة محددة في الماضي', 'حدثان استمرا بنفس الفترة', 'شيء حدث وانتهى في الماضي', 'احداث وعادات متكررة'], 'correct' => 2],
        ['text' => 'يمكننا استخدام زمن الماضي المستمر للطلب بطريقة مؤدبة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اذا كان هناك حدثان تقاطعا في الماضي فلا يمكننا استخدام الماضي المستمر؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت في الماضي المستمر:', 'options' => ['Subject + was \were + (v+ing)', 'is \are + (v+ing)', 'was \were + v1', 'v2'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن الماضي المستمر:', 'options' => ['cleaning now', 'cleaned yesterday', 'have already cleaned', 'I was cleaning my room yesterday morning.'], 'correct' => 3],
        ['text' => 'الكينونة في صيغة الماضي المستمر هي:', 'options' => ['Is \ am \ are', 'Was \ were', 'Be', 'Been'], 'correct' => 1],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ Was:', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ Were:', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'لماذا نعتبر Was \ were فعل مساعد في الماضي المستمر؟', 'options' => ['لا يعبر عن الحدث الأساسي الفعلي', 'يأتي بعدهم صفة', 'يأتي بعدهم مفعول', 'لا شيء'], 'correct' => 0],
        ['text' => '(I __ __ my bags... yesterday at seven o’clock) اختر المناسب:', 'options' => ['am \ preparing', 'was \ preparing', 'were\preparing', 'did \ prepare'], 'correct' => 1],
        ['text' => 'في (I was reordering my cases...) لماذا وضعنا s في cases؟', 'options' => ['מלكية', 'اختصار was', 'اسم جمع', 'لا شيء'], 'correct' => 2],
        ['text' => 'اختر الرابط المناسب لـ (The audience were clapping in the festival):', 'options' => ['حدثان استمرا بنفس الفترة', 'فعل واحد استمر في الماضي', 'عادات متكررة', 'طلب مؤدب'], 'correct' => 1],
        ['text' => 'اختر الحالة لـ (Aisha was always coming to class late):', 'options' => ['متقاطعة', 'عادات متكررة في الماضي', 'طلب مؤدب', 'فترة محددة'], 'correct' => 1],
        ['text' => '(I was __ in the park when I saw her) اختر الكلمة:', 'options' => ['Walk', 'Walked', 'Walking', 'Walks'], 'correct' => 2],
        ['text' => 'الجملة السابقة تعبر عن:', 'options' => ['فترة محددة', 'حدثان مستمران', 'عادات متكررة', 'احداث متقاطعة'], 'correct' => 3],
        ['text' => 'ما الزمن الآخر المستخدم مع الماضي المستمر في الأحداث المتقاطعة؟', 'options' => ['مضارع مستمر', 'ماضي بسيط', 'مضارع بسيط', 'مضارع تام'], 'correct' => 1],
        ['text' => '(Khalid was wondering if Salman could fix our heater) تعبر عن:', 'options' => ['فترة محددة', 'طلب بطريقة مؤدبة', 'عادات', 'متقاطعة'], 'correct' => 1],
        ['text' => 'تتميز جملة الطلب المؤدب باحتوائها على:', 'options' => ['Could', 'Wondering if', 'Subject', 'لا شيء'], 'correct' => 1],
        ['text' => 'الجملة التي تعبر عن حدثان استمرا في الماضي تكون احداها ماضي بسيط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // Both are continuous
        ['text' => '(When I phoned Sami , __ was having an interview) اختر الفاعل:', 'options' => ['Sami', 'He', 'She', 'I'], 'correct' => 1],
        ['text' => 'عند ذكر حدثين عن نفس الفاعل نكتب الضمير أولا ثم الاسم ثانيا؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // Usually Name first then Pronoun
        ['text' => 'اختر التكوين الصحيح للنفي في الماضي المستمر:', 'options' => ['Subject + was \were+ not + (v1+ing)', 'is \are+ not', 'was \were+ not + v1', 'v2 + not'], 'correct' => 0],
        ['text' => '(The tea __ boiling) اختر الكلمة المناسبة:', 'options' => ['Was', 'Are', 'Have', 'Were'], 'correct' => 0],
        ['text' => 'ترجمة: The tea was boiling:', 'options' => ['يغلي', 'كان يغلي', 'على', 'قد على'], 'correct' => 1],
        ['text' => 'النفي الصحيح لـ (The tea was boiling):', 'options' => ['The tea was not boiling.', 'was boiling not', 'is not boiling', 'has not boiling'], 'correct' => 0],
        ['text' => 'لماذا اخترنا was ولم نضع were مع The tea؟', 'options' => ['اسم غير معدود يعامل معاملة المفرد', 'مقطع واحد', 'مفرد وجمع للسوائل', 'لا شيء'], 'correct' => 0],
        ['text' => 'اختر الاختصار الصحيح لـ Was not / were not:', 'options' => ['Wasn’t\weren’t', 'Was’nt', 'Wasnt’', 'None'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة ماضي مستمر:', 'options' => ['Was \were + subject+ (v+ing) ?', 'Was \were + subject+ (v1)', 'Do \does', 'Did + v+ing'], 'correct' => 0],
        ['text' => '(He was watching TV while his wife was reading...) اختر السؤال:', 'options' => ['He was watching...?', 'Was he watching TV while his wife was reading a book?', 'Did he...?', 'None'], 'correct' => 1],
        ['text' => 'عند تكوين السؤال نضع Was/were أولا ثم الفاعل ثانيا؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما معنى Was / were في تكوين السؤال؟', 'options' => ['كان', 'يكون', 'هل كان \ هل كانوا', 'قد'], 'correct' => 2],
        ['text' => 'ترجمة: (هل كانت أمي بالخارج؟):', 'options' => ['was outside.', 'is outside.', 'Was my mother outside?', 'Is my mother outside?'], 'correct' => 2],
        ['text' => '(Were your brothers sleeping?) الترجمة هي:', 'options' => ['كانوا نائمون', 'هل كانوا اخوتك نائمون؟', 'هل يكونوا', 'يكونوا نائمون'], 'correct' => 1],
        ['text' => '(Were your brothers sleeping?) الإجابة الصحيحة:', 'options' => ['Yes, he was.', 'Yes they were.', 'Yes, they were. (duplicate check)', 'None'], 'correct' => 2],
        ['text' => '(Were your brothers sleeping?) اختر النفي الصحيح في الجواب:', 'options' => ['No, they wasn’t', 'No, they were', 'No, they wasn’t...', 'No, they weren’t.'], 'correct' => 3],
        ['text' => '(symposium – was – I – watching – a – nice) اعد الترتيب:', 'options' => ['I was watching a nice symposium.', 'a symposium nice', 'watching symposium nice a', 'a nice symposium?'], 'correct' => 0],
        ['text' => 'ترجمة: (أنا كنت أتغدى):', 'options' => ['I was having lunch.', 'having dinner', 'was have', 'have lunch'], 'correct' => 0],
        ['text' => '(__ was __ yesterday morning) اختر الكلمات:', 'options' => ['It \ snows', 'It \ snowing', 'She \ snowing', 'They \ snowing'], 'correct' => 1],
        ['text' => '(__ were __ a horror movie yesterday at 9) اختر الكلمات:', 'options' => ['Watching \ we', 'We \ watching', 'We \ watched', 'She \ watching'], 'correct' => 1],
        ['text' => 'جملة (I was using the computer now) هي:', 'type' => 'true_false', 'options' => ['صح', 'خاطئة (بسبب now)'], 'correct' => 1],
        ['text' => 'كلمات الربط مع حالة الحدثين المستمرين في وقت واحد هي:', 'options' => ['And \ when', 'While', 'When\ therefore', 'However'], 'correct' => 1],
        ['text' => 'الماضي البسيط هو الحدث الذي يقطع الحدث المستمر؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر التكملة: (______ when she called):', 'options' => ['I was watching TV', 'watching TV', 'watched TV', 'am watching'], 'correct' => 0],
        ['text' => 'اختر التكملة: (________ while I was sending a message):', 'options' => ['My phone died', 'My phone dying', 'My phone dead', 'is dying'], 'correct' => 0],
        ['text' => 'اختر جملة الماضي البسيط في: (While she was cooking, dad was working):', 'options' => ['الأولى', 'الثانية', 'كلاهما', 'None'], 'correct' => 3], // Both are continuous
        ['text' => 'اختر الحالة الصحيحة للحدث المتقاطع:', 'options' => ['While we were taking a picture, the camera died.', 'camera died, we were taking', 'Both', 'None'], 'correct' => 0],
        ['text' => 'أكمل: (When I opened the door, ________):', 'options' => ['Saleh was waiting', 'Saleh waited', 'is waiting', 'waiting'], 'correct' => 0],
        ['text' => 'أكمل: (While Huda was shopping, ______):', 'options' => ['I arrived', 'I was driving', 'I arrive', 'am driving'], 'correct' => 0],
        ['text' => 'ترجمة: While Huda was shopping:', 'options' => ['بينما هدى كانت تتسوق', 'عندما هدى كانت تتسوق', 'تكون تتسوق', 'تسوقت'], 'correct' => 0],
        ['text' => 'اختر الترتيب والرابط الصحيح لـ (While – teacher explaining – I arrived):', 'options' => ['While I arrived...', 'I arrived while...', 'While the teacher was explaining, I arrived.', 'Both (b+c)'], 'correct' => 3],
        ['text' => 'كلمات نستخدمها للتعبير عن السلوكيات المتكررة في الماضي المستمر:', 'options' => ['Always', 'Constantly', 'Constantly – always – continually', 'None'], 'correct' => 2],
        ['text' => '(Omar was __ drinking Soda) اختر الكلمة:', 'options' => ['Usually', 'Always', 'Often', 'Sometimes'], 'correct' => 1],
        ['text' => '(Sarah ___ celebrating all day yesterday) اختر الفعل:', 'options' => ['Is not', 'Were not', 'Was not', 'Are not'], 'correct' => 2],
        ['text' => '(Firemen ___ rescuing) اختر الفعل:', 'options' => ['Was not', 'Weren’t', 'Hasn’t', 'Isn’t'], 'correct' => 1],
        ['text' => '(They __ __ Math) اختر الفعل:', 'options' => ['Was not \ study', 'Weren’t \ study', 'Were not \ studying', 'Were not \ studied'], 'correct' => 2],
        ['text' => '(__ they living in China?) اختر المساعد:', 'options' => ['Was', 'Were', 'Has', 'Is'], 'correct' => 1],
        ['text' => '(__ you __ a sandwich?) اختر الكلمات:', 'options' => ['Was \ eat', 'Were \ eat', 'Were \ eating', 'Was \ eating'], 'correct' => 2],
        ['text' => '(__ he __ waking up early?) اختر الكلمات:', 'options' => ['Was \ always', 'Were \ usually', 'Were \ always', 'Was \ often'], 'correct' => 0],
        ['text' => 'نطق Wasn’t في العامية:', 'options' => ['دزن', 'وزن', 'ورن', 'دون'], 'correct' => 1],
        ['text' => 'نطق Weren’t في العامية:', 'options' => ['دزن', 'وزن', 'ورن', 'دون'], 'correct' => 2],
        ['text' => 'أكمل: (__ Seba __ while her brother __ __?)', 'options' => ['Was\drawing\ was playing', 'Was \ drawing \ did play', 'Was \ drew \ was playing', 'None'], 'correct' => 0],
        ['text' => 'بعد While يأتي الحدث الطويل (الماضي المستمر)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'لماذا نستخدم التصريف الثاني (Was/were) من الكينونة be؟', 'options' => ['لأن زمن الجملة ماضي', 'بعده ing', 'لا شيء'], 'correct' => 0],
        ['text' => 'لماذا نستخدم v1+ing في الماضي المستمر؟', 'options' => ['بسبب وجود الكينونة (be/was/were)', 'الزمن ماضي', 'يجوز مع كافة الأزمنة', 'لا شيء'], 'correct' => 0],
        ['text' => 'ترجمة: They were:', 'options' => ['هم يكونوا', 'هم كانوا', 'نحن كنا', 'نحن نكون'], 'correct' => 1],
        ['text' => 'ترجمة: she was:', 'options' => ['هي تكون', 'هو يكون', 'هي كانت', 'هو كان'], 'correct' => 2],
        ['text' => 'ترجمة (هو كان):', 'options' => ['She was', 'He was', 'He is', 'He were'], 'correct' => 1],
        ['text' => 'ترجمة (نحن كنا):', 'options' => ['We are', 'We were', 'We was', 'They were'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي المستمر (Past Continuous Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1024.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
