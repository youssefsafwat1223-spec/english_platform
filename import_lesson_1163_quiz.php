<?php

/**
 * Script to import questions for Lesson ID 1163 (Comprehensive Review 2)
 * php import_lesson_1163_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1163;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1163 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'حرف (C) يلفظ س (فقط) في كل الحالات.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'لا يشترط في الإنجليزية ان يكون اسم الحرف هو نطقه، مثل الحرف (W) اسمه دبل يو ولكن صوته (وا).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اللفظ الصحيح للضمير (She) هو:', 'options' => ['سهي', 'ستشي', 'شي', 'كي'], 'correct' => 2],
        ['text' => 'اللفظ الصحيح للكلمة (Father) هو:', 'options' => ['فاتهر', 'فاذر', 'فاتر', 'فاثر'], 'correct' => 1],
        ['text' => 'عند إضافة (er) للصفة (fat) تصبح:', 'options' => ['Fater', 'Fatter', 'Fateer', 'Fatteer'], 'correct' => 1],
        ['text' => 'عندما نضيف في اخر الكلمة (er / ing) فإننا ندبل الحرف الأخير في جميع الحالات (بدون اي استثناء).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'المعادلة (70 + 10 = eighteen) صحيحة.', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 1],
        ['text' => 'كيف نكتب العدد (One) كعدد ترتيبي؟', 'options' => ['Onest', 'First', '1rd', '1th'], 'correct' => 1],
        ['text' => 'نوع الكلمة (went) في (Abdullah went to the beach) هي:', 'options' => ['Noun', 'Verb', 'Preposition', 'Adjective'], 'correct' => 1],
        ['text' => 'كيف نختصر كلمة (Adjective)؟', 'options' => ['Adje.', 'Adj.', 'Adj,', 'Adj:'], 'correct' => 1],
        [
            'text' => 'صل ما بين الحرف وصوته:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Ph', 'right' => 'ف'],
                ['left' => 'Th', 'right' => 'ث/ذ'],
                ['left' => 'Ch', 'right' => 'تش'],
                ['left' => 'Sh', 'right' => 'ش'],
            ]
        ],
        ['text' => 'ما نوع كلمة (a turtle) في (saw a turtle)؟', 'options' => ['Noun', 'Verb', 'Adjective', 'Adverb'], 'correct' => 0],
        ['text' => 'لماذا وضعنا الأداة (a) قبل كلمة (race)؟', 'options' => ['اسم مفرد معدود مبدوء بحرف علة', 'اسم مفرد معدود مبدوء بحرف ساكن', 'اسم جمع معدودو مبدوء بحرف علة', 'اسم جمع معدود مبدوء بحرف ساكن'], 'correct' => 1],
        ['text' => 'ما نوع الكلمات (one, two, three)؟', 'options' => ['ordinal numbers', 'cardinal numbers', 'compound numbers', 'letters'], 'correct' => 1],
        ['text' => 'لماذا لم ندبل الحرف الأخير في كلمة (faster) عند إضافة (er)؟', 'options' => ['لأنها لم تنتهي ب CVC', 'لأنها تبدا ب CVC', 'لانها تتكون من مقطع واحد', 'لاشيء'], 'correct' => 0],
        ['text' => 'ما نوع كلمة (Needed) من حيث اقسام الكلام؟', 'options' => ['Noun', 'Verb', 'Adjective', 'Adverb'], 'correct' => 1],
        ['text' => 'ما نوع الكلمة (First)؟', 'options' => ['ordinal numbers', 'cardinal numbers', 'compound numbers', 'letters'], 'correct' => 0],
        ['text' => 'ما زمن جملة (I went to the club yesterday and I played chess...)؟', 'options' => ['Present simple', 'Past simple', 'Present continuous', 'Past continuous'], 'correct' => 1],
        ['text' => 'لماذا كتبنا الضمير (I) بحرف كبير في الجملة السابقة؟', 'options' => ['لانه حرف واحد فقط', 'لان الضمير (أنا) يجب ان يكون Capital دائما', 'لانه لا يستبدل لاسم فاعل', 'لاشيء'], 'correct' => 1],
        ['text' => 'اختر الفعل من الجملة (I went to the club yesterday and I played chess...):', 'options' => ['Went', 'Yesterday', 'My', 'And'], 'correct' => 0],
        ['text' => 'ما هو الخطأ في الجملة السابقة من ناحية الترقيم؟', 'options' => ['يجب ان يكون هناك نقطة في النهاية', 'يجب ان يكون هناك علامة تعجب', 'يجب ان يكون My بحروف كبيرة', 'لا يوجد خطأ'], 'correct' => 0],
        ['text' => 'هل تحتوي الجملة على (كلمة ربط)؟', 'options' => ['Yesterday', 'My', 'And', 'لا تحتوي'], 'correct' => 2],
        ['text' => 'ما نوع كلمة (Yesterday) في الجملة السابقة؟', 'options' => ['Verb', 'Adverb', 'Preposition', 'Noun'], 'correct' => 1],
        
        [
            'text' => 'صل كل جملة والزمن الذي أتت فيه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Sarah has done her homework.', 'right' => 'Present perfect'],
                ['left' => 'Sarah is going to do her homework.', 'right' => 'Future simple'],
                ['left' => 'Sarah did her homework.', 'right' => 'Past simple'],
                ['left' => 'Sarah has been doing her homework.', 'right' => 'Present perfect continuous'],
            ]
        ],

        [
            'text' => 'صل الجملة بالفعل المناسب حسب الزمن:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'I will ___ to the beach.', 'right' => 'go'],
                ['left' => 'I have been ___ to the beach.', 'right' => 'going'],
                ['left' => 'She ___ to the beach every day.', 'right' => 'goes'],
                ['left' => 'I ___ to the beach yesterday.', 'right' => 'went'],
                ['left' => 'He has ___ to the beach.', 'right' => 'gone'],
            ]
        ],

        [
            'text' => 'صنف الكلمات التالية (The government will improve our educational system):',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'the', 'right' => 'article'],
                ['left' => 'Government / system', 'right' => 'noun'],
                ['left' => 'will', 'right' => 'modal verb'],
                ['left' => 'improve', 'right' => 'verb'],
                ['left' => 'our', 'right' => 'Possessive adjective'],
                ['left' => 'Educational', 'right' => 'adjective'],
            ]
        ],

        ['text' => 'ما هو المشترك بين جميع الأزمنة التامة؟', 'options' => ['V1', 'V3', 'V1+ing', 'V2'], 'correct' => 1],
        ['text' => 'ما هو المشترك بين جميع الأزمنة المستمرة؟', 'options' => ['V1 والكينونة', 'V3 والكينونة', 'V1+ing والكينونة', 'V2 والكينونة'], 'correct' => 2],
        
        [
            'text' => 'صل ما بين اسم الزمن وتكوينه الصحيح:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Present simple', 'right' => 'Subject + V1'],
                ['left' => 'Present continuous', 'right' => 'Subject + is/am/are + v1+ing'],
                ['left' => 'Present perfect', 'right' => 'Subject + has/have + v3'],
                ['left' => 'Present perfect continuous', 'right' => 'Subject + has/have + been + v1+ing'],
                ['left' => 'Past simple', 'right' => 'Subject + v2'],
                ['left' => 'Past continuous', 'right' => 'Subject + was/were + v1+ing'],
                ['left' => 'Past perfect', 'right' => 'Subject + had + v3'],
                ['left' => 'Past perfect continuous', 'right' => 'Subject + had + been + v1+ing'],
            ]
        ],

        [
            'text' => 'صل ما بين زمن المستقبل وتكوينه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Future simple', 'right' => 'Subject + will + v1'],
                ['left' => 'Future continuous', 'right' => 'Subject + will + be + v1+ing'],
                ['left' => 'Future perfect', 'right' => 'Subject + will + have + v3'],
                ['left' => 'Future perfect continuous', 'right' => 'Subject + will+ have + been+ v1+ing'],
            ]
        ],

        [
            'text' => 'صل الزمن بالفعل المساعد المخفي او الظاهر في المثبت:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ['left' => 'Present / Past simple', 'right' => 'الفعل المساعد يكون مخفي'],
                ['left' => 'Present continuous', 'right' => 'Is \ am \ are'],
                ['left' => 'Present perfect', 'right' => 'Has \ have'],
                ['left' => 'Past continuous', 'right' => 'Was \ were'],
                ['left' => 'Past perfect', 'right' => 'Had'],
                ['left' => 'Present perfect continuous', 'right' => 'Has been \ have been'],
                ['left' => 'Past perfect continuous', 'right' => 'Had been'],
            ]
        ],

        ['text' => 'اوجد الكلمة الخاطئة: (The lion is as bigger as the tiger)', 'options' => ['The', 'Is', 'Bigger', 'Tiger'], 'correct' => 2],
        ['text' => 'اختر الترتيب الصحيح للجملة:', 'options' => ['Ruba is fast eating.', 'Ruba is eating fast.', 'Ruba eating is fast.', 'Is Ruba eating fast.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة:', 'options' => ['Saad has not seen the movie.', 'Saad has not the movie seen.', 'Saad not has seen the movie.', 'Has Saad has not seen the movie.'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للسؤال:', 'options' => ['Did Ahmed his hands wash?', 'Did wash Ahmed his hands?', 'Did Ahmed wash his hands?', 'Did Ahmed wash?his hands'], 'correct' => 2],
        ['text' => 'الترجمة لـ (Normal people sleep during night) هي:', 'options' => ['البشر الطبيعيين ينامون خلال الليل.', 'البشر الطبيعيين ناموا ليلا.', 'البشر الطبيعيين قد ناموا ليلا.', 'البشر الطبيعيين يستيقظون ليلا.'], 'correct' => 0],
        ['text' => 'الترجمة لـ (I am not studying tonight) هي:', 'options' => ['لأنني ادرس الليلة', 'لأنني ما بادرس الليلة', 'لأنني لا ادرس الليلة', 'لأنني قد ادرس الليلة'], 'correct' => 1],
        ['text' => 'الترجمة لـ (Has Abdullah used his new mobile?) هي:', 'options' => ['عبدالله قد استخدم تلفونه الجديد.', 'هل قد عبدالله استخدم تلفونه الجديد؟', 'عبدالله استخدم تلفونه الجديد.', 'هل عبدالله يستخدم تلفونه الجديد؟'], 'correct' => 1],
        ['text' => 'الترجمة لـ (Thamer has been assembling the parts...) هي:', 'options' => ['ثامر قد ركب القطع لمدة 3 ساعات.', 'ثامر صارله يركب القطع لمدة 3 ساعات.', 'ثامر صارله يركب القطع لمدة 3 أيام.', 'ثامر صارله يركب القطع منذ 3 ساعات.'], 'correct' => 1],
        ['text' => 'الترجمة لـ (Khalid left his job last month) هي:', 'options' => ['خالد ترك وظيفته الشهر الفائت.', 'خالد حصل على وظيفته الشهر الفائت.', 'خالد صارله تارك وظيفته منذ الشهر الفائت.', 'خالد ترك وظيفته الأسبوع الفائت.'], 'correct' => 0],
        ['text' => 'الترجمة لـ (My mother was cleaning our clothes...) هي:', 'options' => ['امي كانت تنظف ملابسنا مساء الامس.', 'امي نظفت ملابسنا مساء الامس.', 'امي قد نظفت ملابسنا مساء الامس.', 'امي ما كانت تنظف ملابسنا مساء الامس.'], 'correct' => 0],
        ['text' => 'الترجمة لـ (Had you visited your uncle before...?) هي:', 'options' => ['هل زرت عمك قبل ان ذهبت للعمل؟', 'هل صارلك زرت عمك قبل العمل؟', 'هل قد زرت عمك قبل ان ذهبت للعمل؟', 'هل تزور عمك قبل ان تذهب للعمل؟'], 'correct' => 2],
        ['text' => 'الترجمة لـ (Samar hadn’t been watching horror movies...) هي:', 'options' => ['سمر ما شاهدت أفلام رعب.', 'سمر لا تشاهد أفلام رعب.', 'سمر ما قد كانت تشاهد أفلام رعب لمدة 4 أعوام.', 'سمر ما قد شاهدت أفلام رعب.'], 'correct' => 2],
        ['text' => 'الترجمة لـ (Sarah will share her ideas...) هي:', 'options' => ['سارة سوف تشارك افكارها مع زميلتها في العمل.', 'سارة راح تشارك افكارها مع صديقتها.', 'سارة قد تشارك افكارها.', 'سارة شاركت افكارها.'], 'correct' => 0],
        ['text' => 'الترجمة لـ (By 5 pm, Heba isn’t going to be arriving...) هي:', 'options' => ['بحلول 5 صباحا، هبة ما راح تكون واصلة.', 'بحلول 5 مساء، هبة ما راح تكون واصلة من جدة.', 'بحلول 5 مساء، هبة سوف لن تكون واصلة.', 'بحلول 5 مساء، هبة ما راح تكون ذاهبة.'], 'correct' => 1],
        ['text' => 'الترجمة لـ (Will Fahad have applied...?) هي:', 'options' => ['هل قد فهد قدم على الوظيفة؟', 'هل راح يكون فهد قد قدم على الوظيفة عندما يتخرج؟', 'هل سوف يكون فهد قدم على الوظيفة؟', 'هل راح يكون فهد قد قدم عندما يلتحق بالجامعة؟'], 'correct' => 1],
        ['text' => 'الترجمة لـ (By next quarter, Saleh will have been living...) هي:', 'options' => ['بحلول الربع القادم، صالح راح يكون صارله يعيش منذ 8 سنوات.', 'بحلول الربع القادم، صالح راح يكون صارله يعيش في مكة لمدة 8 سنوات.', 'بحلول الربع القادم، صالح سوف يعيش.', 'لاشيء'], 'correct' => 1],
        ['text' => 'كلمة (Mistake) تعني خطأ، كيف تصبح عند تحويلها لصفة؟', 'options' => ['Enmistake', 'Mistaken', 'Unmistake', 'Mistakes'], 'correct' => 1],
        ['text' => 'ما هو الـ (Root) في أي كلمة؟', 'options' => ['هو الجذر (أصل الكلمة) بدون إضافات', 'لاحقة تنتهي بها الكلمة', 'بادئة تأتي قبل الكلمة', 'لاشيء'], 'correct' => 0],
        ['text' => 'اختر الجمع الصحيح لكلمة (City):', 'options' => ['Citys', 'Citees', 'Cities', 'City'], 'correct' => 2],
        ['text' => 'الجمع الصحيح لكلمة (stomach) هو (stomaches).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'التصريف الثاني والثالث للفعل (put) هو (putted).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'في الجملة (Ghadah doesn’t like...), ما نوع (doesn’t)؟', 'options' => ['فعل أساسي', 'فعل مساعد', 'فعل ناقص', 'لاشيء'], 'correct' => 1],
        ['text' => 'الجملة (My father gave ’er ...) هي نفسها جملة:', 'options' => ['gave him', 'gave her', 'gave them', 'gave Ameer'], 'correct' => 1],
        ['text' => 'في (I searched for information for my manager)، هل (manager) مفعول به غير مباشر؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر الضمير المناسب: (My uncle hurt __ with his decision).', 'options' => ['He', 'His', 'Herself', 'himself'], 'correct' => 3],
        ['text' => 'اختر الكلمة الصحيحة: (Abdullah drives __).', 'options' => ['Carful', 'Careless', 'Carelessly', 'Fastly'], 'correct' => 2],
        ['text' => 'اختر الكلمة الصحيحة: (Mahmoud is a __ worker).', 'options' => ['Hardly', 'Hard', 'Harder', 'Quickly'], 'correct' => 1],
        ['text' => 'بإمكاننا ذكر أكثر من صفة في الجملة بدون الحاجة لترتيب معين.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => 'اختر اسم الإشارة: (__ machine is good).', 'options' => ['These', 'Those', 'This', 'A'], 'correct' => 2],
        ['text' => 'اختر أداة الربط: (The dog is barking __ it isn’t hungry).', 'options' => ['Therefore', 'however', 'As', 'So'], 'correct' => 1],
        ['text' => 'اختر الجملة الصحيحة:', 'options' => ['Hassan’s idea', 'Hassans’ idea', 'Hassan’ss idea', 'لاشيء'], 'correct' => 0],
        ['text' => 'Be تفرع وتتغير حسب الزمن (مضارع - ماضي - مستقبل).', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'المسجد يكون مقابل المشفى:', 'options' => ['next to', 'opposite', 'on', 'beside'], 'correct' => 1],
        ['text' => 'الترجمة لـ (Don’t behave like a baby):', 'options' => ['لا تحمل الطفل', 'لا تضرب طفل', 'تصرف وكأنك طفل', 'لا تتصرف كطفل'], 'correct' => 3],
        ['text' => 'أنت يجب أن (obey) والديك:', 'options' => ['Obey', 'Obeys', 'Obeyed', 'Obeying'], 'correct' => 0],
        ['text' => 'جميع الأفعال الناقصة يأتي بعدها الفعل الاساسي مجرد بدون استثناء.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'رتب التاريخ الأمريكي: (19) (september) (1983)', 'options' => ['19\9\1983', '9\19\1983', '1983\19\9', '1983\9\19'], 'correct' => 1],
        ['text' => 'بالطريقة البريطانية؟ 21\9\2023 نلفظ التاريخ:', 'options' => ['The ninth of twenty one', 'September the twenty one', 'The twenty first of September twenty twenty three'], 'correct' => 2],
        ['text' => 'ما هو السؤال الصحيح للجواب (to travel)؟', 'options' => ['What does Manar save money?', 'Why does Manar save money?', 'Why does Manar saves money?', 'Where does Manar save money?'], 'correct' => 1],
        ['text' => 'الترجمة لـ (هبة تكون اكثر نشاطا من عبدالله):', 'options' => ['Heba is more active than Abdullah.', 'Abdullah is more active than Heba.', 'Heba is the most active.', 'Heba is activer.'], 'correct' => 0],
        ['text' => 'اختر محدد الكمية: (I don’t have __ cooking oil).', 'options' => ['A few', 'much', 'A little', 'Many'], 'correct' => 1],
        ['text' => 'اختر الفعل: (I go __ in the morning).', 'options' => ['Jug', 'Jugging', 'jugs', 'Jugged'], 'correct' => 1],
        ['text' => 'There __ many students... yesterday.', 'options' => ['are', 'is', 'was', 'were'], 'correct' => 3],
        ['text' => 'معنى (Sea food) هو:', 'options' => ['طعام بحري', 'طعام البحر', 'طعام مصنوع من البحر', 'بحر من الطعام'], 'correct' => 0],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'مراجعة شاملة 2 (Comprehensive Review 2)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 120,
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1163.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
