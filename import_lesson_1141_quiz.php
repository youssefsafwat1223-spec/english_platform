<?php

/**
 * Script to import questions for Lesson ID 1141 (There is/are Grammar)
 * php import_lesson_1141_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1141;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1141 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'متى نستخدم (There is)؟', 'options' => ['للتعبير عن وجود شيء او شخص واحد في الوقت الحالي (المضارع)', 'للتعبير عن وجود شيء او شخص واحد في الماضي', 'للتعبير عن وجود اكثر من شيء او شخص في الوقت الحالي', 'للتعبير عن وجود اكثر من شيء او شخص في الماضي'], 'correct' => 0],
        ['text' => 'متى نستخدم (There was)؟', 'options' => ['للتعبير عن وجود شيء او شخص واحد في الوقت الحالي (المضارع)', 'للتعبير عن وجود شيء او شخص واحد في الماضي', 'للتعبير عن وجود أكثر من شيء او شخص في الوقت الحالي', 'للتعبير عن وجود أكثر من شيء او شخص في الماضي'], 'correct' => 1],
        ['text' => 'متى نستخدم (There are)؟', 'options' => ['للتعبير عن وجود شيء او شخص واحد في الوقت الحالي', 'للتعبير عن وجود شيء او شخص واحد في الماضي', 'للتعبير عن وجود اكثر من شيء او شخص في الوقت الحالي (المضارع)', 'للتعبير عن وجود أكثر من شيء او شخص في الماضي'], 'correct' => 2],
        ['text' => 'متى نستخدم (There were)؟', 'options' => ['للتعبير عن وجود شيء او شخص واحد في الوقت الحالي', 'للتعبير عن وجود شيء او شخص واحد في الماضي', 'للتعبير عن وجود أكثر من شيء او شخص في الوقت الحالي', 'للتعبير عن وجود أكثر من شيء او شخص في المكان في الماضي'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة لـ (يوجد هنالك قطة في الغرفة):', 'options' => ['There was a cat in the room', 'There is a cat in the room', 'There are a cat in the room', 'There were a cat in the room'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لـ (يوجد هنالك أربعة اسود في حديقة الحيوان):', 'options' => ['There is four lions in the zoo', 'There are four lions in the zoo', 'There was four lions in the zoo', 'There were four lions in the zoo'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لـ (يوجد هنالك علب فارغة على الرف):', 'options' => ['There is empty bottles on the shelf', 'There were empty bottles on the shelf', 'There was empty bottles on the shelf', 'There are empty bottles on the shelf'], 'correct' => 3],
        ['text' => 'اختر الترجمة الصحيحة لـ (كان يوجد هنالك ازدحام مروري كبير هذا الصبح):', 'options' => ['There are a lot of traffic this morning', 'There were a lot of traffic this morning', 'There was a lot of traffic this morning', 'There is a lot of traffic this morning'], 'correct' => 2],
        ['text' => '(There was a big storm last night) اختر الترجمة الصحيحة:', 'options' => ['يوجد هنالك عاصفة كبيرة الليلة الماضية', 'كان يوجد هنالك عاصفة كبيرة الليلة الماضية', 'يوجد هنالك عواصف كبيرة الليلة الماضية', 'كان يوجد هنالك عواصف كبيرة الليلة الماضية'], 'correct' => 1],
        ['text' => '(There _ no problems with the project) ليصبح معناها (لم تكن هنالك مشاكل في المشروع):', 'options' => ['Is', 'Are', 'Was', 'Were'], 'correct' => 3],
        ['text' => '(There _ a show at the mall this weekend) ليصبح معناها (يوجد عرض تقديمي في المركز التجاري):', 'options' => ['Is', 'Are', 'Was', 'Were'], 'correct' => 0],
        ['text' => '(There _ many different kinds of fruit at the market) ليصبح معناها (يوجد هنالك العديد من أنواع الفواكه):', 'options' => ['Is', 'Are', 'Was', 'Were'], 'correct' => 1],
        ['text' => 'كلمة (There’s) هي اختصار لـ:', 'options' => ['There are', 'There is', 'There were', 'There was'], 'correct' => 1],
        ['text' => 'كلمة (There’re) هي اختصار لـ:', 'options' => ['There are', 'There is', 'There was', 'There were'], 'correct' => 0],
        ['text' => 'بعد (there is) و (there was) يأتي الاسم مفرد، وبعد (there are) و (there were) يأتي اسم جمع.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'عند تكوين السؤال لجملة تبدأ بـ (there) فإننا نضع (verb to be) أولا ثم (There).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(There are restaurants in this area) اختر تكوين السؤال الصحيح:', 'options' => ['Are there restaurants in this area?', 'Were there restaurants in this area?', 'Are restaurants there in this area?', 'Is there restaurants in this area?'], 'correct' => 0],
        ['text' => '(There is sugar on the floor) اختر تكوين السؤال الصحيح:', 'options' => ['Are there sugar on the floor?', 'were there sugar on the floor?', 'was there sugar on the floor?', 'Is there sugar on the floor?'], 'correct' => 3],
        ['text' => '(There was a swimming pool in our town) اختر تكوين السؤال الصحيح:', 'options' => ['were there a swimming pool in our town?', 'are there a swimming pool in our town?', 'was there a swimming pool in our town?', 'Is there a swimming pool?'], 'correct' => 2],
        ['text' => '(There were many high buildings in Palestine) اختر تكوين السؤال الصحيح:', 'options' => ['There was many high buildings in Palestine?', 'Was there many high buildings in Palestine?', 'Were there many high buildings in Palestine?', 'Is there many high buildings in Palestine?'], 'correct' => 2],
        ['text' => '(There is juice in the jug) اختر النفي الصحيح:', 'options' => ['Is there juice in the jug.', 'There isn’t juice in the jug.', 'There wasn’t juice in the jug.', 'There weren’t juice in the jug.'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد هناك (There is/are Grammar)',
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
        $props = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'points' => 1,
            'correct_answer' => 'A', // Default
        ];

        $props['option_a'] = $qData['options'][0] ?? null;
        $props['option_b'] = $qData['options'][1] ?? null;
        $props['option_c'] = $qData['options'][2] ?? null;
        $props['option_d'] = $qData['options'][3] ?? null;
        $props['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($props);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1141.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
