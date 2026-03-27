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
        ['text' => 'يعبر الماضي المستمر عن جميع ما يلي ما عدا', 'options' => ['شيء استمر خلال فترة محددة في الماضي', 'حدثان استمرا بنفس الفترة في الماضي', 'شيء حدث وانتهى في الماضي', 'احداث وعادات متكررة في الماضي'], 'correct' => 2],
        ['text' => 'يمكننا استخدام زمن الماضي المستمر للطلب بطريقة مؤدبة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اذا كان هناك حدثان تقاطعا في الماضي فلا يمكننا استخدام الماضي المستمر في هذه الحالة.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للمثبت في الماضي المستمر:', 'options' => ['Subject + was \were + (v+ing) + object \complement.', 'Subject + is \are + (v+ing) + object \complement.', 'Subject + was \were + v1+ object \complement.', 'Subject + v2 + object \complement.'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن الماضي المستمر:', 'options' => ['I am cleaning my room now.', 'I cleaned my room yesterday.', 'I have already cleaned my room.', 'I was cleaning my room yesterday morning.'], 'correct' => 3],
        ['text' => 'الكينونة في صيغة الماضي المستمر هي:', 'options' => ['Is \ am \ are', 'Was \ were', 'Be', 'Been'], 'correct' => 1],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ الفعل المساعد (Was):', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 0],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ الفعل المساعد (Were):', 'options' => ['He – she – it – I', 'They – we – you', 'They – we – you – I', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'لماذا نعتبر (Was \ were) في زمن الماضي المستمر فعل مساعد وليس أساسي؟', 'options' => ['لأنه لا يعبر عن الحدث الأساسي في الجملة يعني (اللي صار فعليا في الجملة)', 'لان يأتي بعدهم صفة', 'لأنه يأتي بعدهم مفعول به', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'I __ __ my bags for travelling yesterday at seven o’clock. اختر المناسب', 'options' => ['am \ preparing', 'was \ preparing', 'were\preparing', 'did \ prepare'], 'correct' => 1],
        ['text' => 'في جملة (I was reordering my cases yesterday at seven o’clock.) لماذا وضعنا (S) في اخر المفعول به هنا؟', 'options' => ['لأنها S ملكية', 'لأن S هنا اختصار ل was', 'لأنها اسم جمع وعند جمعه نضيف (S)', 'لا شيء مما سبق'], 'correct' => 2],
        
        [
            'text' => 'صل كل جملة مع استخدامها في زمن الماضي المستمر:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'حدثان استمرا بنفس الفترة في الماضي.', 'right' => 'Ahmed was fixing the sink while Soaad was helping me.'],
                ['left' => 'The audience were clapping in the festival.', 'right' => 'شيء استمر خلال فترة محددة في الماضي'],
                ['left' => 'احداث وعادات متكررة في الماضي', 'right' => 'Aisha was always coming to class late.'],
                ['left' => 'طلب بطريقة مؤدبة', 'right' => 'I was wondering if you could look over this contract.'],
                ['left' => 'احداث متقاطعة في الماضي', 'right' => 'A thief entered my room while I was sleeping.'],
            ]
        ],

        ['text' => 'I was __ in the park when I saw her. اختر الكلمة المناسبة', 'options' => ['Walk', 'Walked', 'Walking', 'Walks'], 'correct' => 2],
        ['text' => 'جملة السؤال السابق تعبر عن', 'options' => ['شيء استمر خلال فترة محددة في الماضي', 'حدثان استمرا بنفس الفترة في الماضي', 'احداث وعادات متكررة في الماضي', 'احداث متقاطعة في الماضي'], 'correct' => 3],
        ['text' => 'عندما نعبر عن احداث متقاطعة في زمن الماضي المستمر فاننا نستخدم زمن اخر مع الماضي المستمر ما هو؟', 'options' => ['مضارع مستمر', 'ماضي بسيط', 'مضارع بسيط', 'مضارع تام'], 'correct' => 1],
        ['text' => 'الجملة (Khalid was wondering if Salman could fix our heater.) تعبر عن', 'options' => ['شيء استمر خلال فترة محددة في الماضي', 'طلب بطريقة مؤدبة', 'احداث وعادات متكررة في الماضي', 'احداث متقاطعة في الماضي'], 'correct' => 1],
        ['text' => 'تتميز الجملة التي تعبر عن الطلب بالطريقة المؤدبة بانها تحتوي على', 'options' => ['Could', 'Wondering if', 'Subject', 'لا تحتوي على مفعول به'], 'correct' => 1],
        ['text' => 'الجملة التي تعبر عن حدثان استمرا في الماضي تكون احداها ماضي بسيط', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'When I phoned Sami , __ was having an interview اختر الفاعل المناسب', 'options' => ['Sami', 'He', 'She', 'I'], 'correct' => 1],
        ['text' => 'عند ذكر حدثان عن نفس الفاعل فإننا نكتب ضمير الفاعل في الحدث الأول واسم الفاعل في الحدث الثاني', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر التكوين الصحيح للنفي في الماضي المستمر:', 'options' => ['Subject + was \were+ not + (v1+ing) + object \complement.', 'Subject + is \are+ not + (v1+ing) + object \complement.', 'Subject + was \were+ not + v1+ object \complement.', 'Subject + v2 + not+ object \complement.'], 'correct' => 0],
        ['text' => 'The tea __ boiling اختر الكلمة المناسبة للجملة', 'options' => ['Was', 'Are', 'Have', 'Were'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة لجملة (The tea __ boiling.)', 'options' => ['الشاي يغلي', 'الشاي كان يغلي', 'الشاي على', 'الشاي قد على'], 'correct' => 1],
        ['text' => 'The tea was boiling. اختر النفي الصحيح للجملة', 'options' => ['The tea was not boiling.', 'The tea was boiling not.', 'The tea is not boiling.', 'The tea has not boiling.'], 'correct' => 0],
        ['text' => 'لماذا اخترنا الفعل المساعد(was) ولم نضع (were) في جملة (The tea was boiling)؟', 'options' => ['لان الشاي اسم غير معدود والغير معدود يعامل معاملة المفرد', 'لأنها كلمة تتكون من مقطع واحد', 'لان الفعل المساعد (Was) نستخدمه للمفرد والجمع لو كان الاسم من السوائل مثل الشاي', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اختر الاختصار الصحيح ل (Was not \ were not)', 'options' => ['Wasn’t\weren’t', 'Was’nt \ were’nt', 'Wasnt’ \werent’', 'None'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح لجملة ماضي مستمر:', 'options' => ['Was \were + subject+ (v+ing) + object \complement?', 'Was \were + subject+ (v1) + object \complement.', 'Do \does + subject+ (v3) + object \complement?', 'Did + subject+ (v+ing) + object \complement?'], 'correct' => 0],
        ['text' => 'He was watching TV while his wife was reading a book. اختر تكوين السؤال لجملة', 'options' => ['He was watching TV while his wife was reading a book?', 'Was he watching TV while his wife was reading a book?', 'Did he watching TV while his wife was reading a book?', 'None'], 'correct' => 1],
        ['text' => 'عند تكوين السؤال نضع (Was\were) أولا ثم الفاعل ثانيا.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة ل (Was \ were) في تكوين السؤال هي', 'options' => ['كان \ كانوا ----- الخ', 'يكون يكونوا ----- الخ', 'هل كان \ هل كانوا ---- الخ', 'قد'], 'correct' => 2],
        ['text' => 'الترجمة الصحيحة للسؤال(هل كانت امي بالخارج؟) هي', 'options' => ['My mother was outside.', 'My mother is outside.', 'Was my mother outside?', 'Is my mother outside?'], 'correct' => 2],
        ['text' => 'Were your brothers sleeping ? الترجمة الصحيحة للسؤال', 'options' => ['اخوتك كانوا نائمون.', 'هل كانوا اخوتك نائمون؟', 'هل يكونوا اخوتك نائمون؟', 'اخوتك يكونوا نائمون.'], 'correct' => 1],
        ['text' => 'Were your brothers sleeping ? الاجابة الصحيحة للسؤال', 'options' => ['Yes, he was.', 'Yes they were.', 'Yes, they were.', 'None'], 'correct' => 2],
        ['text' => 'Were your brothers sleeping ? اختر الاجابة الصحيحة للسؤال (No, they ___)', 'options' => ['Was', 'Were', 'Wasn’t', 'Weren’t'], 'correct' => 3],
        
        [
            'text' => 'صل ما بين كل سؤال وجوابه؟',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Were you reading a novel?', 'right' => 'Yes, I was \ No, I wasn’t'],
                ['left' => 'Was Ghadah watching a movie?', 'right' => 'Yes, she was \ No, she wasn’t.'],
                ['left' => 'Was Adam swimming?', 'right' => 'Yes, he was.\No, he wasn’t.'],
                ['left' => 'Were Abdullah and Sarah cleaning the garden?', 'right' => 'Yes, they were.\ No, they weren’t.'],
            ]
        ],

        ['text' => 'I was watching a nice symposium. اعد ترتيب الجملة التالية', 'options' => ['I was watching a nice symposium.', 'I was watching a symposium nice.', 'I was watching symposium nice a.', 'I was watching a nice symposium?'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة ( انا كنت اتغدى)', 'options' => ['I was having lunch.', 'I was having dinner.', 'I was have lunch.', 'I have lunch.'], 'correct' => 0],
        ['text' => '__ was __ yesterday morning اختر الكلمة المناسبة', 'options' => ['It \ snows', 'It \ snowing', 'She \ snowing', 'They \ snowing'], 'correct' => 1],
        ['text' => '__ were __ a horror movie yesterday at 9 o’clock اختر الكلمة المناسبة', 'options' => ['Watching \ we', 'We \ watching', 'We \ watched', 'She \ watching'], 'correct' => 1],
        ['text' => 'I was using the computer now. الجملة', 'type' => 'true_false', 'options' => ['صحيحة', 'خاطئة'], 'correct' => 1],
        ['text' => 'كلمات الربط المستخدمة مع حالة الحدثين المستمرين في وقت واحد هي', 'options' => ['And \ when', 'While', 'When\ therefore', 'However'], 'correct' => 1],
        ['text' => 'كلمات الربط المستخدمة مع حالة حدث مستمر في الماضي وقطعه حدث اخرهي', 'options' => ['While \ when', 'And \ however', 'When', 'Therefore'], 'correct' => 0],
        ['text' => 'زمن الماضي البسيط هو الحدث الذي يقطع الحدث المستمر', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة الصحيحة للحالة ( حدث مستمر في الماضي وقطعه حدث اخر ) لجملة: (______ when she called.)', 'options' => ['I was watching TV', 'I watching TV', 'I watched TV', 'I am watching TV'], 'correct' => 0],
        ['text' => 'اختر الجملة الصحيحة للحالة ( حدث مستمر في الماضي وقطعه حدث اخر ) لجملة: (________ while I was sending a message.)', 'options' => ['My phone died', 'My phone dying', 'My phone dead', 'My phone is dying'], 'correct' => 0],
        ['text' => 'اختر جملة الماضي البسيط في الجملة التالية : (While she was cooking, dad was working.)', 'options' => ['The first sentence', 'The second sentence', 'Both', 'None'], 'correct' => 3],
        ['text' => 'اختر الجمل المناسبة : (While ________,_______)', 'options' => ['We were taking a picture , the camera died.', 'The camera died ,we were taking a picture.', 'Both', 'None'], 'correct' => 2],
        ['text' => 'When I opened the door,_________ اختر الإجابة الصحيحة', 'options' => ['Saleh was waiting', 'Saleh waited', 'Saleh is waiting', 'Saleh waiting'], 'correct' => 0],
        ['text' => 'While Huda was shopping, ______ اختر الإجابة الصحيحة', 'options' => ['I arrived', 'I was driving', 'I arrive', 'I am driving'], 'correct' => 0],
        ['text' => 'هي While Huda was shopping الترجمة الصحيحة لجملة', 'options' => ['بينما هدى كانت تتسوق', 'عندما هدى كانت تتسوق', 'عندما هدى تكون تتسوق', 'عندما هدى تسوقت'], 'correct' => 0],
        ['text' => 'اعد ترتيب الجمل وضع الرابط بمكانه الصحيح: (While – the teacher was explaining – I arrived)', 'options' => ['A - While I arrived, the teacher was explaining.', 'B - I arrived while the teacher was explaining.', 'C -While the teacher was explaining, I arrived.', 'D – (b + c)'], 'correct' => 3],
        ['text' => 'اختر الكلمات التي نستخدمها في زمن الماضي المستمر للتعبير عن السلوكيات المتكررة:', 'options' => ['Always – often', 'Constantly', 'Constantly – always – continually', 'None'], 'correct' => 2],
        ['text' => '(Omar was __ drinking Soda .) اختر الإجابة الصحيحة', 'options' => ['Usually', 'Always', 'Often', 'Sometimes'], 'correct' => 1],
        ['text' => 'Sarah ___ celebrating all day yesterday. اختر الإجابة الصحيحة', 'options' => ['Is not', 'Were not', 'Was not', 'Are not'], 'correct' => 2],
        ['text' => 'Firemen ___ rescuing. اختر الإجابة الصحيحة', 'options' => ['Was not', 'Weren’t', 'Hasn’t', 'Isn’t'], 'correct' => 1],
        ['text' => 'They __ __ Math. اختر الإجابة الصحيحة', 'options' => ['Was not \ study', 'Weren’t \ study', 'Were not \ studying', 'Were not \ studied'], 'correct' => 2],
        ['text' => '__ they living in china? اختر الإجابة الصحيحة للسؤال', 'options' => ['Was', 'Were', 'Has', 'Is'], 'correct' => 1],
        ['text' => '__ you __ a sandwich? اختر الإجابة الصحيحة للسؤال', 'options' => ['Was \ eat', 'Were \ eat', 'Were \ eating', 'Was \ eating'], 'correct' => 2],
        ['text' => '__ he __ waking up early? اختر الإجابة الصحيحة للسؤال', 'options' => ['Was \ always', 'Were \ usually', 'Were \ always', 'Was \ often'], 'correct' => 0],
        ['text' => 'في العامية: Wasn’t اختر الإجابة الصحيحة لنطق كلمة', 'options' => ['دزن', 'وزن', 'ورن', 'دون'], 'correct' => 1],
        ['text' => 'في العامية: Weren’t اختر الإجابة الصحيحة لنطق كلمة', 'options' => ['دزن', 'وزن', 'ورن', 'دون'], 'correct' => 2],
        ['text' => '(__ Seba __ while her brother __ __?) اختر الإجابات الصحيحة', 'options' => ['Was\play\ was drawing', 'Was \ drawing \ did play', 'Was \ drew \ was playing', 'None'], 'correct' => 1],
        ['text' => '(__ khalid __ when his uncle __?) اختر الإجابات الصحيحة', 'options' => ['Was \ recording \ jumped', 'Were \ jumped \ record', 'Was \ recording \ jumping', 'None'], 'correct' => 0],
        ['text' => 'الحدث الطويل يعني الجملة التي تكون في زمن (الماضي المستمر) While يأتي بعد', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'لماذا نستخدم التصريف الثاني (V2) من الكينونة be؟', 'options' => ['ولا بد ان يكون تصريف ثاني في زمن الماضي لأنه زمن الجملة ماضي', 'لانه يأتي بعده (V1+ing)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نستخدم (V1+ing) في الماضي المستمر؟', 'options' => ['بسبب وجود الكينونة (Be)', 'لان الزمن ماضي', 'لأنه يجوز استخدام V1+ing مع كافة الازمنة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'They were اختر الترجمة الصحيحة ل', 'options' => ['هم يكونوا', 'هم كانوا', 'نحن كنا', 'نحن نكون'], 'correct' => 1],
        ['text' => 'she was اختر الترجمة الصحيحة ل', 'options' => ['هي تكون', 'هو يكون', 'هي كانت', 'هو كان'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة ل ( هو كان ):', 'options' => ['She was', 'He was', 'He is', 'He were'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة ل ( نحن كنا ):', 'options' => ['We are', 'We were', 'We was', 'They were'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد الماضي المستمر (Past Continuous Grammar)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1024.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
