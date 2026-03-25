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
        ['text' => 'ما معنى زمن الماضي التام المستمر في اللغة الإنجليزية؟', 'options' => ['Past Perfect', 'Past Continuous', 'Past Perfect Continuous', 'Present Perfect Continuous'], 'correct' => 2],
        ['text' => 'يعبر الماضي التام المستمر عن حدث استمر في الماضي لغاية نقطة معينة او حتى بدأ حدث اخر؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للمثبت في الماضي التام المستمر:', 'options' => ['Subject + had + been + v+ing', 'Subject + have + been + v-ing', 'had + v3', 'was + been + v-ing'], 'correct' => 0],
        ['text' => 'ما هو الفعل المساعد الأساسي في الماضي التام المستمر؟', 'options' => ['Was', 'Had', 'Have', 'Is'], 'correct' => 1],
        ['text' => 'لماذا نستخدم Been في تكوين هذا الزمن؟', 'options' => ['لانه زمن مضارع', 'لانه زمن تام', 'لان الفعل فيه s', 'لا شيء'], 'correct' => 1],
        ['text' => 'لماذا نضع ing للفعل في هذا الزمن؟', 'options' => ['بسبب وجود had', 'لانه زمن مستمر', 'بسبب وجود been', 'بسبب الفاعل'], 'correct' => 1],
        ['text' => 'ضمائر الفاعل التي تأخذ Had been هي:', 'options' => ['I – he – she – it', 'They – we – you', 'جميع ضمائر الفاعل', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'أكمل: (They __ __ __ all day) ', 'options' => ['had been working', 'have been working', 'had working', 'were working'], 'correct' => 0],
        ['text' => 'ما الفرق بين الماضي التام والماضي التام المستمر؟', 'options' => ['الأول يركز على انتهاء الحدث والثاني على استمراريته لفترة', 'كلاهما واحد', 'الأول للجمع والثاني للمفرد', 'لا شيء'], 'correct' => 0],
        ['text' => 'أكمل: (He __ __ __ English for ten years when he moved to London) ', 'options' => ['has been studying', 'had been studying', 'had studied', 'did study'], 'correct' => 1],
        ['text' => 'كلمات الربط والدلالة غالباً ما تكون:', 'options' => ['Since \ for', 'When \ before', 'كلاهما', 'None'], 'correct' => 2],
        ['text' => 'نستخدم الماضي التام المستمر لإظهار سبب لشيء ما في الماضي؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن سبب:', 'options' => ['I was tired because I had been working all day.', 'I was working.', 'I had worked.', 'I’m tired.'], 'correct' => 0],
        ['text' => 'أكمل: (She was out of breath because she __ __ __) ', 'options' => ['is running', 'has been running', 'had been running', 'ran'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للنفي في الماضي التام المستمر:', 'options' => ['Subject + had + not + been + v+ing', 'had + been + not', 'not had been', 'was not been'], 'correct' => 0],
        ['text' => 'ما هو النفي الصحيح لـ I had been waiting:', 'options' => ['I had not been waiting.', 'I haven’t been wait', 'I hadn’t wait', 'none'], 'correct' => 0],
        ['text' => 'الاختصار الصحيح لـ Had not been هو:', 'options' => ['hadn’t been', 'had’nt been', 'hadnt’ been', 'none'], 'correct' => 0],
        ['text' => 'أكمل النفي: (They __ __ __ long when the bus arrived)', 'options' => ['hadn’t been waiting', 'haven’t been wait', 'not had waiting', 'none'], 'correct' => 0],
        ['text' => 'كيف نسأل في الماضي التام المستمر؟', 'options' => ['Had + subject + been + v-ing ?', 'Had been + subject', 'Have + been + subject', 'none'], 'correct' => 0],
        ['text' => 'أكمل السؤال: (__ you __ __ for a long time?) ', 'options' => ['Had \ been \ waiting', 'Has \ been \ waiting', 'Did \ wait', 'none'], 'correct' => 0],
        ['text' => 'ما معنى Had في أول السؤال في هذا الزمن؟', 'options' => ['هل كان قد', 'هل كان مستمر', 'هل قد كان', 'هل يملك'], 'correct' => 2],
        ['text' => 'الإجابة على (Had they been playing?) بـ "نعم":', 'options' => ['Yes, they had.', 'Yes, they had been.', 'Yes, they were.', 'none'], 'correct' => 0],
        ['text' => 'الإجابة بـ "لا":', 'options' => ['No, they hadn’t.', 'No, they haven’t.', 'No, they weren’t.', 'none'], 'correct' => 0],
        ['text' => 'هل يمكن استخدام الأفعال التي لا تقبل الاستمرار (Stative verbs) في هذا الزمن؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // Usually we use Past Perfect then
        ['text' => 'مثل فعلي (Know \ Like) نستخدم معهم:', 'options' => ['Past Perfect', 'Past Perfect Continuous', 'Present Simple', 'none'], 'correct' => 0],
        ['text' => 'أكمل: (I __ __ him for years when we met) اختر الفعل Know:', 'options' => ['had known', 'had been knowing', 'have known', 'none'], 'correct' => 0],
        ['text' => 'جملة (I had been knowing him) صحيحة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ترجمة: It had been raining:', 'options' => ['كانت تمطر لفترة والقصد الاستمرار.', 'انها امطرت', 'سوف تمطر', 'none'], 'correct' => 0],
        ['text' => 'ترجمة: I had been working:', 'options' => ['أنا قد كنت أعمل.', 'أنا عملت', 'أنا سوف أعمل', 'none'], 'correct' => 0],
        ['text' => '(She – been – studying – for – three – hours – had) اعد الترتيب:', 'options' => ['She had been studying for three hours.', 'Studying been she...', 'had she been studying', 'none'], 'correct' => 0],
        ['text' => 'ما معنى For في هذا الزمن؟', 'options' => ['لـ', 'منذ', 'لمدة', 'عن'], 'correct' => 2],
        ['text' => 'ما معنى Since في هذا الزمن؟', 'options' => ['لـ', 'منذ', 'لمدة', 'بسبب'], 'correct' => 1],
        ['text' => 'نستخدم Since مع نقطة زمنية محددة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'نستخدم For مع مدة زمنية؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'أكمل: (We had been playing __ 5 o’clock)', 'options' => ['Since', 'For', 'In', 'At'], 'correct' => 0],
        ['text' => 'أكمل: (We had been playing __ two hours)', 'options' => ['Since', 'For', 'After', 'Before'], 'correct' => 1],
        ['text' => 'أكمل: (____ you been waiting long?) ', 'options' => ['Had', 'Has', 'Were', 'Did'], 'correct' => 0],
        ['text' => 'ترجمة: (هل كنت قد كنت نائماً؟):', 'options' => ['Had you been sleeping?', 'Have you been sleeping?', 'Were you sleeping?', 'none'], 'correct' => 0],
        ['text' => 'الفرق الأساسي بين الماضي المستمر والماضي التام المستمر:', 'options' => ['التام المستمر يركز على حدث استمر لغاية وقت معين او حدث اخر', 'لا فرق', 'المستمر ابسط', 'none'], 'correct' => 0],
        ['text' => 'ترجمة: (الأرض كانت رطبة لانها كانت تمطر):', 'options' => ['The ground was wet because it had been raining.', 'ground was wet raining', 'it rained ground wet', 'none'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي التام المستمر (Past Perfect Continuous Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1040.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
