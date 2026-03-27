<?php

/**
 * Script to import questions for Lesson ID 1110 (Interrogative Pronouns Grammar & Practice)
 * php import_lesson_1110_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1110;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1110 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'ما معنى ضمائر الاستفهام في اللغة الإنجليزية؟', 'options' => ['Reflexive pronouns', 'Interrogative pronouns', 'Relative pronouns', 'Possessive pronouns'], 'correct' => 1],
        ['text' => 'اختر ضمائر الاستفهام:', 'options' => ['Myself – what – where – mine', 'Who – what – which – whose', 'Who – herself – himself – where', 'Ourselves – that – I – we – them'], 'correct' => 1],
        ['text' => 'تستخدم ضمائر الاستفهام في:', 'options' => ['الجمل المثبتة', 'الجمل المنفية', 'السؤال', 'جمل الطلب والامر'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للسؤال:', 'options' => ['Interrogative pronoun + auxiliary verb + subject +verb + object \complement?', 'Auxiliary verb + subject +verb + object \complement +Interrogative pronoun?', 'Interrogative pronoun + subject +verb + auxiliary verb + object \complement?', 'Interrogative pronoun + auxiliary verb + subject +verb \complement+ object?'], 'correct' => 0],
        ['text' => 'اختر تكوين السؤال الصحيح في زمن المضارع والماضي البسيط في حال كانت الكينونة Be هي الفعل الأساسي في الجملة:', 'options' => ['Interrogative pronoun + be + subject \complement?', 'Interrogative pronoun + do\does\did + subject +verb + object \complement?', 'Interrogative pronoun + subject +verb + object \complement?', 'Interrogative pronoun + has\have + subject +verb + object \complement?'], 'correct' => 0],
        ['text' => 'الأسئلة التي تبدأ ب Interrogative pronoun تحتاج لإجابة تبدأ ب Yes \No.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'نستخدم الضمير الاستفهامي (Who) لـ:', 'options' => ['السؤال عن شخص عاقل فاعل ومفعول به', 'للسؤال عن شيء غير عاقل (فاعل) فقط', 'للسؤال عن شخص عاقل (مفعول به) فقط', 'للسؤال عن شيء غير عاقل ( مفعول به)'], 'correct' => 0],
        ['text' => 'ما هو الضمير الاستفهامي الأكثر استخداما ( الأنسب) للسؤال عن المفعول به؟', 'options' => ['Who', 'Whom', 'What', 'where'], 'correct' => 1],
        ['text' => 'ما الضمير الاستفهامي الذي نستخدمه للسؤال عن الملكية؟', 'options' => ['What', 'Who', 'Whose', 'When'], 'correct' => 2],
        ['text' => 'نستخدم الضمير الاستفهامي What للسؤال عن:', 'options' => ['الشيء (الغير عاقل) يعني ليس بشر', 'فاعل عاقل', 'مفعول به', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'ما الضمير الاستفهامي الذي نستخدمه للسؤال عن السبب؟', 'options' => ['Where', 'When', 'What', 'Why'], 'correct' => 3],
        ['text' => 'نستخدم الضمير الاستفهامي When للسؤال عن:', 'options' => ['الوقت ( الزمن)', 'مكان', 'فاعل', 'سبب'], 'correct' => 0],
        ['text' => 'نستخدم الضمير الاستفهامي Where للسؤال عن:', 'options' => ['الوقت', 'المكان', 'الفاعل', 'سبب'], 'correct' => 1],
        
        [
            'text' => 'صل كل ضمير من ضمائر الاستفهام بمعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'who', 'right' => 'من (للفاعل والمفعول به العاقل )'],
                ['left' => 'whom', 'right' => 'من( للمفعول به)'],
                ['left' => 'what', 'right' => 'ماذا\ ما'],
                ['left' => 'when', 'right' => 'متى'],
                ['left' => 'where', 'right' => 'أين'],
                ['left' => 'why', 'right' => 'لماذا'],
                ['left' => 'which', 'right' => 'أي (للاختيار)'],
            ]
        ],

        ['text' => 'ما الفعل المساعد الذي نكتبه بعد ضمير الاستفهام في زمن المضارع البسيط؟', 'options' => ['Was \ were', 'Do \ does', 'Has \have', 'Can \could'], 'correct' => 1],
        ['text' => 'اختر السؤال المناسب للجملة التالية (I went to school):', 'options' => ['Where do you go?', 'Where did you go?', 'When do you go?', 'When did you go?'], 'correct' => 1],
        ['text' => 'اختر السؤال المناسب للجملة التالية (I brush my teeth every day):', 'options' => ['Who do you do every day?', 'What do you do every day?', 'When do you do every day?', 'Why do you do every day?'], 'correct' => 1],
        ['text' => 'اختر السؤال المناسب للجملة التالية (I have gone to U.S.A to learn):', 'options' => ['Where have you gone ?', 'Why have you gone to U.S.A?', 'When have you gone to U.S.A?', 'What have you gone to U.S.A?'], 'correct' => 1],
        ['text' => 'اذا كانت الجملة تحتوي على الفاعل I او we فعند تكوين السؤال للجملة نحول الفاعل الى you.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        
        [
            'text' => 'بعد كل ضمير استفهامي لا بد ان يأتي فعل مساعد، صل كل زمن بالفعل المساعد الخاص به:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Present simple', 'right' => 'Do \ does'],
                ['left' => 'Present continuous', 'right' => 'Is \ am \ are'],
                ['left' => 'Present perfect \ perfect continuous', 'right' => 'Has \ have'],
                ['left' => 'Past simple', 'right' => 'Did'],
                ['left' => 'Past continuous', 'right' => 'Was\were'],
                ['left' => 'Past perfect\perfect continuous', 'right' => 'Had'],
                ['left' => 'Future simple \ perfect \ perfect continuous', 'right' => 'will'],
            ]
        ],

        ['text' => '(Abdullah has been sleeping for 3 hours.) اختر السؤال الصحيح (الذي يسال عن الفاعل) في الجملة التالية:', 'options' => ['Why has Abdullah been sleeping for 3 hours?', 'Who has been sleeping for 3 hours?', 'Who has Abdullah been sleeping for 3 hours?', 'When has Abdullah been sleeping for 3 hours?'], 'correct' => 1],
        ['text' => 'ما هي الأزمنة التي( لا) نستخدم معها الفعل المساعد الخاص بها عند استخدام What\Who في السؤال عن الفاعل:', 'options' => ['مضارع بسيط \ ماضي بسيط', 'مضارع مستمر \ ماضي مستمر', 'مضارع تام \ ماضي تام', 'مستقبل بسيط \ مستقبل تام'], 'correct' => 0],
        ['text' => '(___ is your favourite colour?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: black):', 'options' => ['Who', 'Whom', 'What', 'Why'], 'correct' => 2],
        ['text' => '(___ did you go to prayer with?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: with my friend):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 0],
        ['text' => '(___ castle did you visit last week?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: Salah Al Din castle):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 3],
        ['text' => '(___ bag is this?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: my cousin’s):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 2],
        ['text' => '(___ of these two options do you prefer?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: the middle option):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 3],
        ['text' => '(___ is the owner of this car?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: My boss):', 'options' => ['Who', 'Whom', 'Whose', 'Which'], 'correct' => 0],
        ['text' => '(___ is the name of the author you recommended?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: Mustafa Mahmoud):', 'options' => ['Who', 'What', 'Why', 'Where'], 'correct' => 1],
        ['text' => '(___ team do you support?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: The city team):', 'options' => ['Who', 'Which', 'Where', 'When'], 'correct' => 1],
        ['text' => '(___ did you send the email to?) اختر الضمير الاستفهامي المناسب للسؤال (الجواب: my colleagues):', 'options' => ['Who', 'Whom', 'Whose', 'which'], 'correct' => 1],
        ['text' => 'Who is coming to our flat tonight? اختر الإجابة الصحيحة للسؤال:', 'options' => ['At the weekend', 'At the zoo', 'Our siblings', 'Under the table'], 'correct' => 2],
        ['text' => 'Whose glasses are these on the sofa? اختر الإجابة الصحيحة للسؤال:', 'options' => ['These are mine', 'On Monday', 'Because they are clean', 'Ibrahim’s'], 'correct' => 3],
        ['text' => 'Which movie do you want to watch tonight? اختر الإجابة الصحيحة للسؤال:', 'options' => ['At 23 Nasser street', 'On the shelf', 'Ahmed', 'A horror movie'], 'correct' => 3],
        ['text' => 'When will you be back from your sabbatical? اختر الإجابة الصحيحة للسؤال:', 'options' => ['Next April', 'In the drawer', 'Noura', 'To learn'], 'correct' => 0],
        ['text' => 'Why does she go to school? اختر الإجابة الصحيحة للسؤال:', 'options' => ['To learn', 'Rania', 'Between the mosque and the supermarket', 'On 13th November'], 'correct' => 0],
        ['text' => 'Did – he – how – it- clean? اعد ترتيب السؤال:', 'options' => ['How he clean it did?', 'How did he clean it?', 'He clean did how it?', 'How did he it clean?'], 'correct' => 1],
        ['text' => 'ما هو زمن السؤال (Did Faleh how it clean)?', 'options' => ['ماضي تام', 'ماضي مستمر', 'مضارع بسيط', 'ماضي بسيط'], 'correct' => 3],
        ['text' => 'Favorite – cuisine – what – your – is? اعد ترتيب السؤال:', 'options' => ['What is your favorite cuisine?', 'What your is favorite cuisine?', 'What is your cuisine favorite?', 'What your cuisine favorite is?'], 'correct' => 0],
        ['text' => 'Many – stories – how – Zainab – read –has? اعد ترتيب السؤال:', 'options' => ['How many has stories Zainab read?', 'How many stories Zainab has read?', 'How many stories has Zainab read?', 'How many has Zainab stories read?'], 'correct' => 2],
        ['text' => 'ما الترجمة الصحيحة للسؤال ( كيف تصلح هذه الالة؟):', 'options' => ['What do you fix this machine?', 'How do you fix this machine?', 'What have you fixed this machine?', 'How will you fix this machine?'], 'correct' => 1],
        ['text' => 'ما الترجمة الصحيحة للسؤال ( من الذي دعاك الى المؤتمر؟):', 'options' => ['Who invited you to the conference?', 'Who did invited you to the conference?', 'Who is inviting you to the conference?', 'Who has invited you to the conference?'], 'correct' => 0],
        ['text' => 'ما الترجمة الصحيحة للسؤال ( ماذا سوف تحضر عمتي معها للنزهة؟):', 'options' => ['What has my aunt brought for the picnic?', 'What does my aunt bring for the picnic?', 'What will my aunt bring for the picnic?', 'What has my aunt been bringing for the picnic?'], 'correct' => 2],
        ['text' => '(Where did he hide while we were searching for him?) ما الترجمة الصحيحة للسؤال:', 'options' => ['اين (هي) اختبأت بينما نحن كنا نبحث عنها؟', 'اين ( هو ) اختبأ بينما نحن كنا نبحث عنه؟', 'متى ( هو) اختبأ بينما نحن كنا نبحث عنه؟', 'ماذا ( هو) اختبأ بينما نحن كنا نبحث عنه؟'], 'correct' => 1],
        ['text' => '(When – the – coming – doctor – is ?) اعد ترتيب السؤال:', 'options' => ['When the doctor is coming?', 'When is the doctor coming?', 'When coming is the doctor?', 'When is coming the doctor?'], 'correct' => 1],
        ['text' => '(The – movie – think – about – what – do – you ?) اعد ترتيب السؤال:', 'options' => ['What do you think the movie about?', 'What do you about think the movie?', 'What do you think about the movie?', 'What you think do about the movie?'], 'correct' => 2],
        ['text' => '(Choose – one – which – did – Abdullah?) اعد ترتيب السؤال:', 'options' => ['Which one did Abdullah choose?', 'Which did Abdullah choose one?', 'Which one Abdullah did choose?', 'Which Abdullah one did choose?'], 'correct' => 0],
        ['text' => '(What will he does next week?) حدد الخطأ في السؤال وصححه ان وجد:', 'options' => ['نكتب what بدل when', 'نكتب you بدل your', 'نكتب فعل مجرد do بدل does لان جاء بعد الفعل الناقص will', 'لا يوجد خطأ في السؤال'], 'correct' => 2],
        ['text' => '(When have you decide to go?) حدد الخطأ في السؤال وصححه ان وجد:', 'options' => ['نكتب what بدل when', 'نكتب has بدل have', 'نكتب decided بدل decide لأنها بعد have', 'لا يوجد خطأ في الجملة'], 'correct' => 2],
        ['text' => '(What is your mum doing?) حدد الخطأ في السؤال وصححه ان وجد:', 'options' => ['نكتب are بدل is', 'نكتب do بدل doing', 'نكتب you بدل your', 'لا يوجد خطا في الجملة'], 'correct' => 3],
        ['text' => 'اختر من علامات الترقيم المناسبة لنهاية أي سؤال؟', 'options' => ['Full stop', 'Question mark', 'Exclamation mark', 'Comma'], 'correct' => 1],
        ['text' => '(When will the train arrive?) اختر الإجابة الصحيحة المحتملة للسؤال:', 'options' => ['In air', 'The doctors', 'At midday', 'None'], 'correct' => 2],
        ['text' => 'استخدمت أداة السؤال الاستفهامية في الجملة (When will the train arrive?) للسؤال عن:', 'options' => ['المكان', 'الوقت ( الزمن)', 'السبب', 'الفاعل'], 'correct' => 1],
        ['text' => 'ما هو (الفاعل) في السؤال (When will the train arrive?):', 'options' => ['What', 'The train', 'Arrive', 'Will'], 'correct' => 1],
        ['text' => 'ما نوع الفعل (arrive) في السؤال (When will the train arrive):', 'options' => ['Irregular verb', 'Modal verb', 'Transitive verb يحتاج لمفعول به', 'Intransitive verb لا يحتاج لمفعول به'], 'correct' => 3],
        ['text' => 'ما الفعل المساعد في السؤال (When will the train arrive):', 'options' => ['Will', 'Arrive', 'Train', 'What'], 'correct' => 0],
        ['text' => 'لماذا وضعنا الاداة the قبل train في السؤال (When will the train arrive?):', 'options' => ['لان كلا من السامع والمتكلم يعرفان أي قطار بالتحديد', 'لان كلمة Train تتكون من مقطع واحد', 'لان كلمة Train تبدأ بحرف ساكن', 'لا شيء مما سبق'], 'correct' => 0],

        // Practice questions starts here
        ['text' => '------- did you do then?', 'options' => ['What', 'Which', 'How', 'Why'], 'correct' => 0],
        ['text' => '-------- would you like to eat?', 'options' => ['What', 'Which', 'Who', 'Where'], 'correct' => 0],
        ['text' => '-------- is knocking at the door?', 'options' => ['What', 'Which', 'Who', 'Why'], 'correct' => 2],
        ['text' => '------- is your phone number?', 'options' => ['What', 'Which', 'Who', 'When'], 'correct' => 0],
        ['text' => '--------- do you want to see?', 'options' => ['What', 'Who', 'Whom', 'All of the above'], 'correct' => 3],
        ['text' => '------will he say?', 'options' => ['What', 'How', 'Who', 'Why'], 'correct' => 0],
        ['text' => '------- came here in the morning?', 'options' => ['Who', 'Whom', 'What', 'Why'], 'correct' => 0],
        ['text' => '------ do you mean?', 'options' => ['Where', 'What', 'Who', 'How'], 'correct' => 1],
        ['text' => '------ do you think took the money?', 'options' => ['Who', 'Whom', 'Why', 'How'], 'correct' => 0],
        ['text' => '------- was the boy reading?', 'options' => ['Who', 'Whom', 'Whose', 'What'], 'correct' => 3],
        ['text' => '-------- wrote this book?', 'options' => ['Who', 'Whom', 'Which', 'Where'], 'correct' => 0],
        ['text' => '-------- is your birthday?', 'options' => ['What', 'Where', 'When', 'How'], 'correct' => 2],
        ['text' => '-------- old are you?', 'options' => ['When', 'Where', 'How', 'Which'], 'correct' => 2],
        ['text' => '--------- is the teachers’ office?', 'options' => ['When', 'What', 'Why', 'Where'], 'correct' => 3],
        ['text' => '------ are you crying?', 'options' => ['Why', 'What', 'Where', 'Who'], 'correct' => 0],
        ['text' => '------- do you prefer, the red bicycle or the blue bicycle?', 'options' => ['When', 'Which', 'Why', 'Who'], 'correct' => 1],
        ['text' => '------- is your science teacher?', 'options' => ['When', 'Why', 'Who', 'What'], 'correct' => 2],
        ['text' => '-------- much is your coat?', 'options' => ['Why', 'What', 'How', 'When'], 'correct' => 2],
        ['text' => '------- is the longer ruler, the yellow or the blue one?', 'options' => ['Which', 'Why', 'How', 'What'], 'correct' => 0],
        ['text' => '------- are you so happy today?', 'options' => ['Which', 'Why', 'How', 'What'], 'correct' => 1],
        ['text' => '----- did the cat eat?', 'options' => ['Which', 'Why', 'How', 'What'], 'correct' => 3],
        ['text' => 'The pronoun ------ is used to ask about ownership.', 'options' => ['Whose', 'Who', 'Who’s', 'What'], 'correct' => 0],
        ['text' => 'The pronoun ------ is used to ask about the reason.', 'options' => ['What', 'Why', 'Who', 'Where'], 'correct' => 1],
        ['text' => 'The pronoun ------ is used to ask about the place.', 'options' => ['When', 'Where', 'How', 'Who'], 'correct' => 1],
        ['text' => '------- road do you take to go to school?', 'options' => ['Who', 'Which', 'What', 'When'], 'correct' => 1],
        ['text' => 'The pronoun ------ is used to ask about the time.', 'options' => ['who', 'when', 'where', 'how'], 'correct' => 1],
        ['text' => 'The pronoun ------ is used to ask about the subject?', 'options' => ['Who', 'When', 'Where', 'How'], 'correct' => 0],
        ['text' => 'The pronoun ------ is used to ask about the object of the verb?', 'options' => ['Who', 'Whom', 'Where', 'when'], 'correct' => 1],
        ['text' => 'The pronoun ------ is used to ask about the way things are done?', 'options' => ['Who', 'Where', 'How', 'When'], 'correct' => 2],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد ضمائر الاستفهام (Interrogative Pronouns Grammar & Practice)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1110.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
