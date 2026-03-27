<?php

/**
 * Script to import questions for Lesson ID 1088 (Imperative Sentences Grammar)
 * php import_lesson_1088_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1088;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1088 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ماذا نعني ب ( فعل الامر)؟', 'options' => ['Imperative sentences', 'Negative sentences', 'Affirmative sentences', 'None'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للإثبات لجمل الامر:', 'options' => ['V2 + object\complement.', 'V1 + object \ complement.', 'Subject + v3 + object\complement.', 'Subject + be + v1 + object\complement.'], 'correct' => 1],
        ['text' => 'غالبا ما تبدا جملة الامر بالتصريف الأول للفعل V1.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'غالبا ما يأتي ذكر الشخص المخاطب يعني (المأمور) في منتصف الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اين نضع كلمة (Please) او اسم الشخص المخاطب في جملة الامر؟', 'options' => ['اول الجملة', 'اخر الجملة', 'وسط الجملة', 'اول الجملة او اخرها'], 'correct' => 3],
        ['text' => '(___ me some respect, son) اختر الفعل المناسب للجملة:', 'options' => ['Show', 'Shows', 'Showed', 'Showing'], 'correct' => 0],
        ['text' => '(___ me some respect, son) اختر المفعول به المباشر وغير المباشر في الجملة:', 'options' => ['Me الغير مباشر \ some respect المباشر', 'Me المباشر \ some respect الغير مباشر', 'كلاهما مباشر Me \ some respect', 'كلاهما غير مباشر Me \ some respect'], 'correct' => 0],
        ['text' => '(___ me some respect, son) من هو المخاطب في الجملة؟', 'options' => ['Son', 'Respect', 'Some', 'Me'], 'correct' => 0],
        ['text' => '(__ the car immediately.) اختر الفعل المناسب للجملة:', 'options' => ['Stops', 'Stop', 'Stopped', 'Stopping'], 'correct' => 1],
        ['text' => 'اختر الحال في الجملة (__ the car immediately.) ان وجد:', 'options' => ['The car', 'لا يوجد حال في الجملة', 'Immediately', 'الكلمة المحذوفة هي الحال'], 'correct' => 2],
        ['text' => '(move - , – your- Please- bike) اعد ترتيب الجملة بشكل صحيح:', 'options' => ['Move your bike, please.', 'Please, move your bike.', 'Your bike move please.', 'ا + ب'], 'correct' => 3],
        ['text' => '(Bring me a cup of coffee) اختر الترجمة الصحيحة للجملة:', 'options' => ['احضر لي كوبا من القهوة', 'كوبا من القهوة احضر', 'احضر لها كوبا من القهوة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => '(Takes your time.) حدد الخطأ في الجملة وصححه ان وجد:', 'options' => ['نضع الفعل take لان Takes عليها إضافات ومفروض ان تكون مجردة.', 'نضع you بدل Your', 'لا يوجد خطأ في الجملة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'هل ممكن ان تكون جملة الامر عبارة عن كلمة واحدة مثل (استمتع Enjoy)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(Stop talking and __ your book,please.) اختر الفعل الصحيح للجملة:', 'options' => ['Open', 'Closed', 'Opened', 'Opening'], 'correct' => 0],
        ['text' => '(please,___ this by tomorrow) اختر الفعل المناسب للجملة ليصبح معنى الجملة (لو سمحت، اكمل هذا بحلول غدا):', 'options' => ['Choose', 'Play', 'Complete', 'Visit'], 'correct' => 2],
        ['text' => 'عندما تريد ان تطلب من( حاتم بحد ذاته) ان يحضر لك المفتاح تقول:', 'options' => ['Take the key, please.', 'Hatem , give me the key, please.', 'Give me the key, please.', 'None'], 'correct' => 1],
        ['text' => '(__ careful, please) اختر الفعل المناسب لـ:', 'options' => ['Is', 'Was', 'Be', 'Been'], 'correct' => 2],
        ['text' => 'أي الجمل التالية تعبر عن جملة الامر:', 'options' => ['I think you should do your homework.', 'Could you pass the salt , pleas?', 'The dog barked loudly.', 'Stop talking and listen to me.'], 'correct' => 3],
        ['text' => 'أي الجمل التالية تعتبر جملة امر مهذبة:', 'options' => ['Hand me that box.', 'Hand me that box, Ahmed.', 'Ahmed, hand me that box, please.', 'None'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للنفي لجمل الامر:', 'options' => ['Don’t +V2 + object\complement', 'Don’t +V1 + object \ complement', 'Subject + don’t + v3 + object\complement', 'None'], 'correct' => 1],
        ['text' => 'اختر جملة الامر المنفية:', 'options' => ['Eat all your food.', 'Don’t eat while driving.', 'Not look at me like that.', 'None'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( لا تنسى ان تقفل الباب عندما تغادر):', 'options' => ['Don’t forget to lock the door when you leave.', 'Don’t remember to lock the door when you leave.', 'Forget to lock the door when you leave.', 'Remember to lock the door when you leave.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( لا تكن متأخرا على الاجتماع):', 'options' => ['Be late for the meeting.', 'Don’t be late for the meeting.', 'Don’t be early for the meeting.', 'Don’t be late for the hospital.'], 'correct' => 1],
        ['text' => '(Don’t talk with your mouth full.) اختر الترجمة الصحيحة للجملة:', 'options' => ['لا تتحدث وفمك مليء', 'تحدث وفمك مليء', 'لا تأكل وفمك مليء', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة لكلمة Don’t هي:', 'options' => ['لا', 'لن', 'لم', 'ما'], 'correct' => 0],

        [
            'text' => 'صل ما بين القائل وجملة الامر المتعلقة به:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'The policeman', 'right' => 'Don’t text while driving.'],
                ['left' => 'The dentist', 'right' => 'Open your mouth, please.'],
                ['left' => 'The teacher', 'right' => 'Don’t forget to complete your homework tonight.'],
                ['left' => 'The carpenter', 'right' => 'Hand me that hammer, please.'],
            ]
        ],

        ['text' => '(Don’t disobey your mother.) حدد نوع الجملة التالية:', 'options' => ['جملة امر مثبتة', 'جملة امر منفية', 'جملة امر على صيغة سؤال', 'ليس مما سبق'], 'correct' => 1],
        ['text' => '(Switch off your mobile.) اختر النفي الصحيح للجملة:', 'options' => ['Switch off your mobile, please.', 'Don’t switch off your mobile.', 'Can you swich off your mobile?', 'None'], 'correct' => 1],
        ['text' => '(Clean your room.) ما نوع الفعل في الجملة؟', 'options' => ['فعل متعدي Transitive يحتاج لمفعول به لكي تكون الجملة مفيدة', 'فعل لازم Intransitive لا يحتاج لمفعول به لان الجملة مفيدة', 'Irregular', 'Modal'], 'correct' => 0],
        ['text' => '(Put your water bottle on the table.) ما هو حرف الجر في الجملة ان وجد؟', 'options' => ['The', 'On', 'Your', 'لا يوجد حرف جر في الجملة'], 'correct' => 1],
        ['text' => '(Put your bottle on the table.) ما نوع حرف الجر في الجملة ان وجد؟', 'options' => ['Preposition of place', 'Preposition of time', 'Preposition of movement', 'None'], 'correct' => 0],
        ['text' => 'ما الذي يفيدنا به وجود كلمة Please في جملة الامر؟', 'options' => ['لجعل الجملة طويلة لأنها تكون جملة قصيرة', 'لجعل الجملة أكثر تهذيبا ويكون الأمر ( الطلب) بلباقة', 'لا تفيد في الجملة', 'فقط نستخدمها في الامر من الأكبر منا في السن'], 'correct' => 1],
        ['text' => 'اذا كنا مصممين على الامر الذي نأمره فإننا:', 'options' => ['نذكر الفعل مرتين', 'نكتب ( الفعل المساعد) Does في جملة الامر', 'نضيف (الفعل المساعد) Do في جملة الامر', 'نضع الفعل في التصريف الثالث'], 'correct' => 2],
        ['text' => 'الطريقة الصحيحة للإصرار على الامر في جملة Clean the room, please هي:', 'options' => ['Does clean the room, please.', 'Do clean the room, please.', 'Cleaning the room, please.', 'Clean clean the room, please.'], 'correct' => 1],
        ['text' => 'حدد الخطأ وصححه في الجملة (Do brushes your hair, please.) ان وجد:', 'options' => ['نكتب doee بدل do', 'نكتب you بدل your', 'نكتب Brush بدل brushes', 'لا يوجد خطأ في الجملة'], 'correct' => 2],
        ['text' => '(Adam wash my car) حدد الخطأ وصححه في الجملة:', 'options' => ['يجب ان نضع فاصلة (,) بعد الاسم المأمور ( آدم)', 'يجب ان نضع نقطة (.) بعد الاسم المأمور ( آدم)', 'يجب ان نضع علامة استفهام (؟) بعد الاسم المأمور ( آدم)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اذا وضعنا كلمة Please في بداية الجملة فان الفاصلة ستكون قبلها وان وضعناها في النهاية فان الفاصلة تكون بعد اخر كلمة في الجملة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(Please, don’t __ your toys.) اختر الإجابة الصحيحة للجملة:', 'options' => ['Throws', 'Throw', 'Thrown', 'threw'], 'correct' => 1],
        ['text' => '(Please, don’t __ your toys.) اختر الكلمة التي جعلت الجملة اكثر تهذيبا في الجملة:', 'options' => ['Don’t', 'Please', 'Your', 'Toys'], 'correct' => 1],
        ['text' => 'هل جملة الامر (Please, don’t __ your toys.) مثبتة ام منفية وكيف عرفت ذلك؟', 'options' => ['جملة الامر مثبتة بسبب وجود Please', 'جملة الامر منفية بسبب وجود Don’t', 'جملة الامر مثبتة بسبب وجود toys', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(Please, don’t __ your toys.) اختر المفعول به في الجملة:', 'options' => ['Please', 'الكلمة المحذوفة', 'Your toys', 'Don’t'], 'correct' => 2],
        ['text' => '(Please, don’t __ your toys.) الشخص المخاطب في الجملة هو:', 'options' => ['شخص محدد', 'لم يذكر الشخص المخاطب', 'Your toys', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => '(Do turn the radio off, Khalid.) جملة الامر جملة منفية.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(Do turn the radio off, Khalid.) المخاطب في الامر هو:', 'options' => ['You', 'Khalid', 'لم يذكر الشخص المخاطب', 'The radio'], 'correct' => 1],
        ['text' => '(Do turn the radio off, Khalid.) القائل هنا لديه إصرار بان يقوم خالد بفعل الامر كيف عرفت ذلك؟', 'options' => ['لأنه ذكر اسم المخاطب', 'بسبب وجود حرف الجر Off', 'لأنه ذكر الفعل المساعد Do قبل الفعل الاساسي', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => '(Do turn the radio off, Khalid.) اختر الفعل الأساسي في الجملة:', 'options' => ['Do', 'Turn', 'Turn off', 'The radio'], 'correct' => 2],
        ['text' => '(Do turn the radio off, Khalid.) اختر المفعول به في الجملة:', 'options' => ['Khalid', 'The radio', 'Turn', 'Do'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد جمل الأمر (Imperative Sentences Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1088.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
