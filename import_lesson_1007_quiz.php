<?php

/**
 * Script to import questions for Lesson ID 1007 (Mixed Review: Basic Tenses)
 * php import_lesson_1007_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1007;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1007 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        // Present Simple
        ['text' => 'اختر الإجابة الصحيحة التي تعتبر استخدام لزمن المضارع البسيط:', 'options' => ['العادات والروتين', 'شيء يحدث الان', 'حدث حصل للتو', 'احداث بدأت في الماضي'], 'correct' => 0],
        ['text' => 'في أي زمن نضيف S أو Es للفعل الأساسي؟', 'options' => ['مضارع بسيط', 'مضارع مستمر', 'مضارع تام', 'مضارع تام مستمر'], 'correct' => 0],
        ['text' => 'ما هي الأحرف التي نضيف بعدها Es في آخر الفعل؟', 'options' => ['K- R – t', 'x – s – o – z - sh – ch', 'g – sh – ch', 'لا شيء'], 'correct' => 1],
        ['text' => 'ما هو تكوين (المثبت) للمضارع البسيط؟', 'options' => ['Sub + V1 + s/es', 'Sub + be + v-ing', 'Sub + has/have + v3', 'Sub + has/have + been'], 'correct' => 0],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمضارع البسيط؟', 'options' => ['Sub + does/do + not + V1', 'Sub + be + not', 'Sub + has/have + not', 'Sub + has/have+ not + been'], 'correct' => 0],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمضارع البسيط؟', 'options' => ['Do\Does + subject + V1?', 'Be + subject + v-ing?', 'Has\have + v3?', 'Has\have + been?'], 'correct' => 0],
        ['text' => 'عند السؤال في جميع الأزمنة عادة نضع:', 'options' => ['المساعد أولا ثم الأساسي', 'الفعل المساعد أولا ثم الفاعل', 'الفاعل أولا', 'الفاعل أولا ثم المفعول'], 'correct' => 1],
        ['text' => 'ما هو الفعل المساعد في المضارع البسيط؟', 'options' => ['Has\have', 'Do \ does', 'Been', 'V+ing'], 'correct' => 1],
        ['text' => 'ما هي المواقع (الأماكن) التي يظهر فيها الفعل المساعد لزمن المضارع البسيط؟', 'options' => ['التأكيد والمنفي والمثبت', 'التأكيد والمنفي والسؤال', 'المثبت والمنفي فقط', 'المثبت والسؤال'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تكون في زمن المضارع البسيط:', 'options' => ['This perfume smells nice.', 'The mirror... broken.', 'always sleeping', 'لا شيء'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تكون في زمن المضارع البسيط:', 'options' => ['talking to me', 'woken up', 'watching movie', 'My son doesn’t talk to strangers.'], 'correct' => 3],
        ['text' => '(Lions is wild animals) ما الخطأ في الجملة؟', 'options' => ['نضع are بدل is', 'نضع do بدل is', 'نضع does بدل is', 'لا يوجد خطأ'], 'correct' => 0],

        // Present Continuous
        ['text' => 'اختر الإجابة التي تعتبر استخدام لزمن المضارع المستمر:', 'options' => ['حقائق', 'سلوكيات متكررة', 'حدث حصل للتو', 'بدأت في الماضي'], 'correct' => 1],
        ['text' => 'ما هو تكوين (المثبت) للمضارع المستمر؟', 'options' => ['Sub + V1 + s/es', 'Subject + be + (v1 + ing)', 'Sub + v3', 'Sub + been'], 'correct' => 1],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمضارع المستمر؟', 'options' => ['Sub + does/do not', 'Sub + has/have not', 'Sub + has/have not been', 'Subject + be + not + (v1 + ing)'], 'correct' => 3],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمضارع المستمر؟', 'options' => ['Do/Does + sub?', 'Be + subject + (v1 + ing)?', 'Has/have?', 'Has/have+ been?'], 'correct' => 1],
        ['text' => 'لا يمكن استخدام المضارع المستمر للتعبير عن (المستقبل)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر الجملة التي تحتوي على مضارع مستمر:', 'options' => ['Khalid is tall.', 'sky has been raining', 'has survived', 'Farah is watching me lecturing.'], 'correct' => 3],
        ['text' => 'اختر الاستخدام الصحيح في: (Abrar is always taking care of her sons):', 'options' => ['سلوك متكرر', 'مستقبل مؤكد', 'شيء يحدث الان', 'خطة مستقبلية'], 'correct' => 0],
        ['text' => 'الاختصار الصحيح لـ we are هو Wea’re؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // We're
        ['text' => 'لماذا نستخدم الكينونة (be) في زمن المضارع المستمر؟', 'options' => ['في كل زمن', 'عادات وروتين', 'لأنه زمن مستمر ونستخدم Be دائما فيه', 'لا شيء'], 'correct' => 2],
        ['text' => 'نترجم الكينونة Be في زمن المضارع المستمر إلى:', 'options' => ['أكون', 'قاعد', 'قد', 'هل'], 'correct' => 1],
        ['text' => 'لماذا أضفنا ing مع الفعل الأساسي في زمن المضارع المستمر؟', 'options' => ['لان قبله الكينونة be', 'في أي زمن', 'ينتهي بحرف علة', 'لا شيء'], 'correct' => 0],

        // Present Perfect
        ['text' => 'اختر الإجابة التي تعتبر استخدام لزمن المضارع التام:', 'options' => ['العادات', 'ماضي في وقت محدد', 'حدث في الماضي غير محدد وقته', 'بدأت في الماضي'], 'correct' => 2],
        ['text' => 'ما هو تكوين (المثبت) للمضارع التام؟', 'options' => ['Sub + V1', 'Sub + be', 'Subject + has\have + v3', 'Sub + been'], 'correct' => 2],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمضارع التام؟', 'options' => ['Sub + not V1', 'Subject + has\have + not + v3', 'Sub + not been', 'Sub + be not'], 'correct' => 1],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمضارع التام؟', 'options' => ['Do/Does...?', 'Be...?', 'Has\have + subject + v3 ?', 'Has\have+ been?'], 'correct' => 2],
        ['text' => 'اختر الجملة التي تعبر عن زمن المضارع التام:', 'options' => ['visited last week', 'I have visited Alqirnah once.', 'visiting next week', 'None'], 'correct' => 1],
        ['text' => 'ما هي الترجمة الصحيحة لـ Has \ have في المضارع التام؟', 'options' => ['صار', 'قد', 'هل', 'قاعد'], 'correct' => 1],
        ['text' => 'ما معنى الفعل المساعد Has \ have في تكوين السؤال؟', 'options' => ['قد', 'هل قد', 'يملك', 'يتناول'], 'correct' => 1],
        ['text' => 'في الجواب عن سؤال المضارع التام نذكر اسم الفاعل بدل من ضمير الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'كيف نعرف أن اختصار \'s هو Has (مضارع تام)؟', 'options' => ['اذا جاء بعده V3', 'V1+ing', 'اسم/صفة', 'لا شيء'], 'correct' => 0],
        ['text' => 'متى نستخدم كلمة For \ since في المضارع التام؟', 'options' => ['مع الحدث الذي بدا ولم ينتهي', 'انتهى تماما', 'ماضي بسيط', 'لا شيء'], 'correct' => 0],
        ['text' => 'جملة They ain’t finished their tasks هي نفسها:', 'options' => ['They hasn’t', 'They haven’t finished their tasks.', 'isn’t finished', 'wasn’t finished'], 'correct' => 1],
        ['text' => 'يمكننا اختصار الفعل المساعد has/have عند وضع not فتصبح:', 'options' => ['Hasn’t \ haven’t', 'Has’n’t', 'Hasn’', 'None'], 'correct' => 0],

        // Present Perfect Continuous
        ['text' => 'اختر الإجابة التي تعتبر استخدام للمضارع التام المستمر:', 'options' => ['يستمر الفترة الحالية', 'يحدث الان', 'قبل فترة وجيزة', 'احداث بدأت في الماضي ولا تزال مستمرة'], 'correct' => 3],
        ['text' => 'ما هو تكوين (المثبت) للمضارع التام المستمر؟', 'options' => ['Sub + V1', 'Sub + be', 'Sub + has/have + v3', 'Subject + has\have + been + v1+ing'], 'correct' => 3],
        ['text' => 'ما هو التكوين الصحيح (للنفي) للمضارع التام المستمر؟', 'options' => ['Sub + not V1', 'Sub + not v3', 'Subject + has\have+ not + been + v1+ing', 'Sub + be not'], 'correct' => 2],
        ['text' => 'ما هو التكوين الصحيح (للسؤال) للمضارع التام المستمر؟', 'options' => ['Do/Does...?', 'Be...?', 'Has/have + v3?', 'Has\have + subject + been + v1+ing ?'], 'correct' => 3],
        ['text' => 'اختر الجملة التي تعبر عن زمن المضارع التام المستمر:', 'options' => ['I have been running since 4 o’clock.', 'She is walking', 'chatting every day', 'watered yesterday'], 'correct' => 0],
        ['text' => 'اختر الفعل المناسب: Our university has been ___ new technologies.', 'options' => ['Research', 'Researching', 'Researched', 'Researches'], 'correct' => 1],
        ['text' => 'ترجمة: (الشركة صارلها تصنع هذا المنتج منذ عام 2012):', 'options' => ['الشركة صارلها تصنع هذا المنتج منذ عام 2012', 'صنعت', 'قد تصنع', 'لمدة 2012'], 'correct' => 0],
        ['text' => 'ترجمة: (شركة الكمبيوتر تلك صارلها تخترع البرنامج منذ بدأت):', 'options' => ['invents', 'has invented', 'That computer company has been inventing the software since it started.', 'for it started'], 'correct' => 2],
        ['text' => 'الترجمة الصحيحة لـ Has been \ have been هي:', 'options' => ['قد', 'ما قد', 'صار بجميع تصريفاتها', 'لا شيء'], 'correct' => 2],
        ['text' => 'ما معنى Has \ have في سؤال المضارع التام المستمر؟', 'options' => ['هل صار', 'هل قد', 'ماذا', 'لا شيء'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا (Been) في المضارع التام المستمر؟', 'options' => ['الزمن مستمر', 'مضارع', 'لان قبله (Has \ have)', 'لا شيء'], 'correct' => 2],
        ['text' => 'لماذا استخدمنا (V1+ing) بعد Been؟', 'options' => ['مستمر مع كينونة', 'مضارع', 'قبله Has/Have been', 'لا شيء'], 'correct' => 0],
        ['text' => 'لماذا استخدمنا (been) بدل (Was \ were)؟', 'options' => ['قبله مساعد يتطلب زمن تام', 'بعده v-ing', 'مضارع', 'لا شيء'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة الأزمنة (Mixed Review: Tenses)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1007.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
