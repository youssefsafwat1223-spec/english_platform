<?php

/**
 * Script to import questions for Lesson ID 999 (Present Perfect Grammar)
 * php import_lesson_999_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 999;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 999 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى مضارع تام في اللغة الإنجليزية؟', 'options' => ['Present simple', 'Past simple', 'Present perfect', 'Present continuous'], 'correct' => 2],
        ['text' => 'جميع ما يلي يعبر عن المضارع التام ما عدا:', 'options' => ['حدث في الماضي غير محدد وقته', 'حدث حصل للتو قبل فترة وجيزة', 'سلوك متكرر', 'فترة بدأت ولم تنتهي'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للاثبات في زمن المضارع التام:', 'options' => ['Subject + has\have + v3', 'Subject + do \ does + v3', 'Subject + has\have + v1', 'Subject + be + v3'], 'correct' => 0],
        ['text' => 'يمكننا استخدام الفعل المساعد (Be) في بعض حالات المضارع التام؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ما هي الضمائر التي تأتي مع (Has)؟', 'options' => ['She – we – he – it', 'She – he – it', 'I – we – they – you', 'I – he – she'], 'correct' => 1],
        ['text' => 'ما هي الضمائر التي تأتي مع (Have)؟', 'options' => ['She – we – he – it', 'She – he – it', 'I – we – they – you', 'I – he – she'], 'correct' => 2],
        ['text' => 'التصريف الثالث (V3) في المضارع التام يكون نوعان وهما:', 'options' => ['Regular \ irregular', 'Singular \ plural', 'Modal \ main verb', 'None'], 'correct' => 0],
        ['text' => 'ما هي الأفعال المنتظمة (Regular verbs)؟', 'options' => ['تختلف كتابتها', 'التي نضيف لها (Ed) في الزمن الثاني والثالث', 'لا تصرف', 'لا شيء'], 'correct' => 1],
        ['text' => 'ما هي الأفعال غير المنتظمة (Irregular verbs)؟', 'options' => ['تختلف كتابتها عند التصريف', 'التي نضيف لها (Ed)', 'لا تصرف', 'لا شيء'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن زمن المضارع التام:', 'options' => ['team is winning', 'The team has won four times.', 'team does win', 'None'], 'correct' => 1],
        ['text' => 'I _ painted a portrait for my grandmother.', 'options' => ['Has', 'Are', 'Have', 'Am'], 'correct' => 2],
        ['text' => 'ما هي ترجمة: I have painted a portrait for my grandmother؟', 'options' => ['رسمت لوحة', 'أنا قد رسمت لوحة لجدتي.', 'أرسم لوحة', 'بأرسم لوحة'], 'correct' => 1],
        ['text' => 'في جملة I have painted a portrait for my grandmother مفعول به واحد؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما نوع الفعل في جملة I have painted a portrait...؟', 'options' => ['Intransitive', 'Transitive', 'Modal', 'Irregular'], 'correct' => 1],
        ['text' => 'تحتوي الجملة I have painted a portrait... على حرف جر ما هو؟', 'options' => ['My', 'A', 'For', 'I'], 'correct' => 2],
        ['text' => 'She has_ to play the guitar.', 'options' => ['Learn', 'Learnt', 'Learned', 'Learns'], 'correct' => 2], // Standard V3 in many dialects, but Learnt is also ok. Prompt choice C is Learned.
        ['text' => 'He has_ a tree house in his backyard.', 'options' => ['Build', 'Builded', 'Built', 'Builts'], 'correct' => 2],
        ['text' => 'He has _ a book about his travels.', 'options' => ['Wrote', 'Written', 'Writed', 'Writes'], 'correct' => 1],
        ['text' => 'ما هي الترجمة المناسبة للفعل المساعد Has/have في المضارع التام؟', 'options' => ['الملكية', 'قد', 'يتناول', 'هل'], 'correct' => 1],
        ['text' => 'التكوين الصحيح لأي جملة منفية في زمن المضارع التام هو:', 'options' => ['Sub + not + has/have', 'Sub + has/have + v3 + not', 'Subject + has/have + not + v3 + obj', 'Not + subject'], 'correct' => 2],
        ['text' => '(She has climbed to the top of a mountain) النفي الصحيح هو:', 'options' => ['Not she has...', 'She has not climbed to the top of a mountain.', 'climbed not', 'She have not'], 'correct' => 1],
        ['text' => 'اختر من الكلمات التي نستخدمهم مع المضارع التام في زمن الماضي القريب:', 'options' => ['still', 'already', 'never', 'yesterday'], 'correct' => 1],
        ['text' => 'I have not visited Makkah _.', 'options' => ['Never', 'Yet', 'Just', 'Already'], 'correct' => 1],
        ['text' => 'يمكننا اختصار الفعل المساعد has/have عند وضع not فتصبح:', 'options' => ['Hasn’t \ haven’t', 'Has’n’t', 'Hasn’', 'None'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للسؤال في زمن المضارع التام:', 'options' => ['Has/have + subject + v3', 'Has/have + subject + v1', 'Has/have + subject + v3 + object ?', 'Do/does'], 'correct' => 2],
        ['text' => 'في صيغة المثبت في المضارع التام نضع الفعل المساعد Has/have قبل الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'في صيغة السؤال في المضارع التام نضع الفعل المساعد Has/have قبل الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(Ahmed has come to this place before) اختر تكوين السؤال الصحيح:', 'options' => ['Has he come to this place before?', 'Is he coming...', 'Has you come...', 'Has he come... (statement)'], 'correct' => 0],
        ['text' => 'ما معنى الفعل المساعد Has \ have في تكوين السؤال؟', 'options' => ['قد', 'هل قد', 'يملك', 'يتناول'], 'correct' => 1],
        ['text' => '(has – he – Manchester – joined – club – just) اعد ترتيب الجملة:', 'options' => ['Has he just joined...?', 'He has just joined Manchester club.', 'He has joined... just.', 'Just he has...'], 'correct' => 1],
        ['text' => '(Haven’t – prepared – yet – the – for – picnic – I) اعد ترتيب الجملة:', 'options' => ['I haven’t prepared for the picnic yet.', 'Yet I haven’t...', 'I haven’t yet for...', 'The I haven’t...'], 'correct' => 0],
        ['text' => '(Has – ever – Hassan – climbed – tree – a?) اعد ترتيب السؤال:', 'options' => ['Hassan has ever...', 'Has Hassan ever climbed a tree?', 'Has Hassan climbed ever...', 'لا يمكن'], 'correct' => 1],
        ['text' => 'عندما يبدأ السؤال بـ Has \ have فإن اجابته تبدأ بـ Yes او no؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(Has Ahmed worked hard this year?) ما الإجابة الصحيحة؟', 'options' => ['Yes, he hasn’t.', 'Yes, he has.', 'No, he has.', 'No, she hasn’t.'], 'correct' => 1],
        ['text' => 'في الجواب عن سؤال المضارع التام نذكر اسم الفاعل بدل من ضمير الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'ماذا يأتي بعد Yes \ no عند الإجابة على سؤال في المضارع التام؟', 'options' => ['اسم فاعل', 'ضمير فاعل', 'حرف جر', 'لا شيء'], 'correct' => 1],
        
        ['text' => '_ you _ Areekah before?', 'options' => ['Have, cooked', 'Have, cook', 'Do , cooked', 'Has , cooked'], 'correct' => 0],
        ['text' => 'كيف نعرف ان اختصار \'s هو Has (مضارع تام)؟', 'options' => ['اذا جاء بعده فعل تصريف ثالث V3', 'جاء بعده V1+ing', 'جاء بعده اسم/صفة', 'لا شيء'], 'correct' => 0],
        ['text' => 'كيف نعرف ان اختصار \'s هو Is (مضارع بسيط)؟', 'options' => ['V3', 'V1+ing', 'جاء بعده اسم او صفة', 'لا شيء'], 'correct' => 2],
        ['text' => 'كيف نعرف ان اختصار \'s هو Is (مضارع مستمر)؟', 'options' => ['V3', 'اذا جاء بعده فعل V1+ing', 'اسم/صفة', 'لا شيء'], 'correct' => 1],
        ['text' => 'الفعل Has \ have في هذا الدرس هو:', 'options' => ['فعل مساعد يحدد زمن المضارع التام (قد)', 'فعل (يملك)', 'فعل مشتق من Be', 'لا شيء'], 'correct' => 0],
        ['text' => 'الترجمة الصحيحة لجملة I have finished my homework هي:', 'options' => ['انهيت واجبي', 'أنا قد انهيت واجبي', 'انهي واجبي', 'بانهي واجبي'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة لجملة (أنا قد كتبت التقرير) هي:', 'options' => ['I has written...', 'I have write...', 'I have written the report.', 'None.'], 'correct' => 2],
        ['text' => '_ has bought a novel. اختر الضمير المناسب:', 'options' => ['I', 'She', 'We', 'They'], 'correct' => 1],
        ['text' => 'Sarah __ eaten pizza.', 'options' => ['Have', 'Has', 'Does', 'Do'], 'correct' => 1],
        ['text' => 'الفرق بين الماضي البسيط والمضارع التام هو تحديد الوقت للماضي البسيط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن مضارع تام:', 'options' => ['I broke my leg.', 'I have broken my leg.', 'I has broke', 'None.'], 'correct' => 1],
        ['text' => 'نستخدم مع المضارع التام كلمة Since ومعناها هو:', 'options' => ['من زمان', 'في الماضي', 'لمدة', 'منذ'], 'correct' => 3],
        ['text' => 'نستخدم مع المضارع التام كلمة For ومعناها هو:', 'options' => ['من زمان', 'في الماضي', 'لمدة', 'منذ'], 'correct' => 2],
        ['text' => 'متى نستخدم كلمة For \ since في المضارع التام؟', 'options' => ['مع الحدث الذي بدأ ولم ينته', 'الذي انتهى تماما', 'في الماضي البسيط', 'لا شيء'], 'correct' => 0],
        ['text' => 'Ali has worked here __ 8 years.', 'options' => ['Since', 'For', 'Yet', 'Until'], 'correct' => 1],
        ['text' => 'Seba has lived here __ 2020.', 'options' => ['Since', 'For', 'Yet', 'Until'], 'correct' => 0],
        ['text' => 'كلمة Since تعني منذ ونستخدم معها تواريخ معينة مثل January/2019؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'كلمة For تعني لمدة ونستخدم معها مدة كاملة مثل 7 days/9 years؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'نستخدم المضارع التام مع فترات غير محددة مثل (this month) وغير منتهية؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'Mohammed’s started working here __. اختر الإجابة:', 'options' => ['Today', 'Since 1994', 'For ten years', 'Yesterday'], 'correct' => 1], // Since 1994 fits the pattern best
        ['text' => 'اختر الجملة الصحيحة للمدة الغير منتهية:', 'options' => ['movie has begun an hour ago', 'The mall has opened.', 'The constructions have started this month.', 'None.'], 'correct' => 2],
        ['text' => 'I’ve __ eaten some snacks.', 'options' => ['Already', 'Did', 'Do', 'Were'], 'correct' => 0],
        ['text' => 'She’s __ finished her work.', 'options' => ['Done', 'Just', 'Does', 'Still'], 'correct' => 1],
        ['text' => 'I have done it__.', 'options' => ['Done', 'Already', 'Just', 'Yet'], 'correct' => 1],
        ['text' => 'Muna __ done her homework.', 'options' => ['Haven’t', 'Isn’t', 'Aren’t', 'Hasn’t'], 'correct' => 3],
        ['text' => 'You __ played this game.', 'options' => ['Aren’t', 'Haven’t', 'Hasn’t', 'Wasn’t'], 'correct' => 1],
        ['text' => 'I __ started working here__. (بوقت معين)', 'options' => ['Haven’t/for 1970', 'Has/since', 'Have / since 2008.', 'Hasn’t/since'], 'correct' => 2],
        ['text' => 'الفعل المساعد Has / have في السؤال يكون بعد الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '__ haven’t studied German.', 'options' => ['He', 'I', 'It', 'She'], 'correct' => 1],
        ['text' => '__ you borrowed money from Khalid?', 'options' => ['Has', 'Do', 'Have', 'Does'], 'correct' => 2],
        ['text' => 'They __ __ their schedule.', 'options' => ['Has, forgotten', 'Have, forgot', 'Have, forgotten', 'Has, forgot'], 'correct' => 2],
        ['text' => 'تتحول Hasn’t \ haven’t في العامية الى:', 'options' => ['ain’t', 'in’t', 'nt’', 'لا شيء'], 'correct' => 0],
        ['text' => 'جملة She ain’t prepared the lunch yet هي نفسها:', 'options' => ['isn’t prepared', 'She hasn’t prepared the lunch yet.', 'haven’t prepared', 'wasn’t prepared'], 'correct' => 1],
        ['text' => 'في العامية كيف تكون Sarah still has not washed the dishes؟', 'options' => ['still hasn’t', 'Sarah still ain’t washed the dishes.', 'still has washed', 'لا شيء'], 'correct' => 1],
        ['text' => 'في العامية ain’t تعني Has+not او Have+not؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'جملة We ain’t done هي نفسها:', 'options' => ['We haven’t done', 'isn’t done', 'weren’t done', 'hasnt done'], 'correct' => 0],
        
        ['text' => 'He ------a job yet.', 'options' => ['found', 'has found', 'hasn’t found', 'have not found'], 'correct' => 2],
        ['text' => 'Samia --------just cleaned the dishes.', 'options' => ['has', 'have', 'had', 'hasn’t'], 'correct' => 0],
        ['text' => 'The film------already-----.', 'options' => ['has/started', 'have/started', 'hasn’t/started', 'haven’t/started'], 'correct' => 0],
        ['text' => 'I haven’t read-------.', 'options' => ['already', 'just', 'yet', 'recently'], 'correct' => 2],
        ['text' => '-------you ever visited London?', 'options' => ['Have', 'Has', 'Haven’t', 'Hasn’t'], 'correct' => 0],
        ['text' => '--------you-------your homework yet?', 'options' => ['Has / done', 'Have/ do', 'Have /done', 'Had/do'], 'correct' => 2],
        ['text' => 'Have you ever------ to China?', 'options' => ['go', 'went', 'been', 'goes'], 'correct' => 2],
        ['text' => 'My mother and father--------to Barcelona?', 'options' => ['have never been', 'has never been', 'has went', 'have went'], 'correct' => 0],
        ['text' => 'Have we -----before?', 'options' => ['meet', 'meets', 'met', 'meeting'], 'correct' => 2],
        ['text' => 'It ----------already-------several times.', 'options' => ['has/happen', 'have/ happened', 'has/happened', 'has/happens'], 'correct' => 2],
        ['text' => 'We ----------------at that restaurant many times.', 'options' => ['have eaten', 'has eaten', 'had eaten', 'eaten'], 'correct' => 0],
        ['text' => 'Abdulmajeed has just---------his leg.', 'options' => ['break', 'broke', 'broken', 'brook'], 'correct' => 2],
        ['text' => 'Have you cleaned the car yet?', 'options' => ['Yes, I haven’t', 'No, I have', 'No, I haven’t', 'Yes, I did'], 'correct' => 2],
        ['text' => 'They ------ the mall twice this week.', 'options' => ['have been', 'have be', 'has be', 'been'], 'correct' => 0],
        ['text' => 'I -------lost my purse.', 'options' => ['has', 'have', 'had', 'do'], 'correct' => 1],
        ['text' => 'Ameer--------two miles.', 'options' => ['has run', 'has ran', 'running', 'runs'], 'correct' => 0],
        ['text' => 'I --------- the right person yet.', 'options' => ['has met', 'have met', 'haven’t met', 'hasn’t met'], 'correct' => 2],
        ['text' => 'They --------each other since high school.', 'options' => ['have know', 'has known', 'have known', 'knew'], 'correct' => 2],
        ['text' => 'They -------any movie at the cinema.', 'options' => ['has never watched', 'have never watched', 'have never watch', 'Were never watched'], 'correct' => 1],
        ['text' => '--------Alia and Heba read this book?', 'options' => ['Has', 'Have', 'Is', 'Were'], 'correct' => 1],
        ['text' => 'Has Fares ever drunk cola?', 'options' => ['Yes, he have.', 'Yes, he has.', 'No, he have.', 'No, he haven’t.'], 'correct' => 1],
        ['text' => 'They ------- the meeting time.', 'options' => ['Have not forgot', 'Have not forgotten', 'Hasn’t forget', 'hasn’t forgotten'], 'correct' => 1],
        ['text' => 'My mother hasn’t swept the floor------.', 'options' => ['already', 'so far', 'just', 'recently'], 'correct' => 1],
        ['text' => 'You ------ the train ticket.', 'options' => ['have not buyed', 'hasn’t buyed', 'haven’t bought', 'hasn’t bought'], 'correct' => 2],
        ['text' => 'She ----- a horse before.', 'options' => ['has not ridden', 'has not rode', 'have not ridden', 'have not rode'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المضارع التام (Present Perfect Grammar)',
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
        $attrs = [
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
        ];
        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 999.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
