<?php

/**
 * Script to import questions for Lesson ID 926
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_926_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    // 1. Find the lesson
    $lessonId = 926;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 926 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'ما ورد في درس الأفعال وانواعها: ما الذي يعبر عن وقوع حدث او حالة Action؟',
            'type' => 'multiple_choice',
            'options' => ['Subject', 'Verb', 'Object', 'Adverb'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'غالبا الفعل المساعد في الجملة يأتي بعد الفعل الأساسي.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الأفعال التي( لا) تحتاج لمفعول به تسمى',
            'type' => 'multiple_choice',
            'options' => ['Transitive verb', 'Intransitive verb', 'Modal verb', 'Helping verb'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'يمكن ان يأتي الفعل المساعد في الجملة كفعل أساسي',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'Should – must – might تسمى الأفعال',
            'type' => 'multiple_choice',
            'options' => ['Transitive verb', 'Intransitive verb', 'Modal verb', 'Helping verb'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'دائما الفعل يأتي بصيغة V2 بعد الأفعال الناقصة.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الكينونة: يعتبر Been هو التصريف الثاني من الكينونة Be',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما الترجمة الصحيحة ل ( هي كانت)',
            'type' => 'multiple_choice',
            'options' => ['She is', 'She was', 'She were', 'He was'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'جميع ما يلي الترجمة الصحيحة لـ You are ما عدا',
            'type' => 'multiple_choice',
            'options' => ['انت تكون', 'أنتم تكونوا', 'أنتن تكونن', 'انت كنت'],
            'correct' => 3, // D
            'points' => 1,
        ],
        [
            'text' => 'اختر ضمائر الفاعل التي تأخذ التصريف (was) من( Be ) في حالة الماضي؟',
            'type' => 'multiple_choice',
            'options' => ['We – they – I', 'I – he – she – it', 'You – she – I – we', 'They- we- you'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'Sharifah ____ my teacher. اختر الكينونة الصحيحة لجملة (شريفة كانت مدرستي)',
            'type' => 'multiple_choice',
            'options' => ['Is', 'Was', 'Were', 'Are'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الترجمة الحرفية( الدقيقة والتفصيلية جدا) لجملة (I am confused).',
            'type' => 'multiple_choice',
            'options' => ['انا مشوش', 'انا أكون مشوش', 'انا كنت مشوش', 'أكون انا مشوش'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الترجمة الصحيحة لجملة They were at the library. هي (هم يكونوا في المكتبة.)',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس المفعول به: أبسط شكل ( تكوين للجملة ) هو فاعل و فعل و مفعول به و حال و تكملة جملة',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ماذا نسمي الشخص او الشيء الذي وقع عليه الفعل و استقبل اثره؟',
            'type' => 'multiple_choice',
            'options' => ['الفاعل', 'الفعل', 'المفعول به', 'الظرف او الحال'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'المفعول به يأتي فقط اسم وضمير مفعول به',
            'type' => 'true_false',
            'options' => ['صح', 'خطـأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'I saw my friend at the hospital. استبدل بضمير مفعول به في جملة',
            'type' => 'multiple_choice',
            'options' => ['Her', 'He', 'She', 'His'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'عند استخدام الضمائر المنعكسة من الذي يقع عليه أثر الفعل؟',
            'type' => 'multiple_choice',
            'options' => ['ضمير المفعول به', 'الفاعل نفسه', 'اسم المفعول به', 'لا شيء مما سبق'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'Their professor taught them math. حدد المفعول به( المباشر) والمفعول به( غير المباشر) في الجملة',
            'type' => 'multiple_choice',
            'options' => [
                'them مفعول به غير مباشر | math مفعول به مباشر',
                'them مفعول به مباشر | math مفعول به غير مباشر',
                'them مفعول به مباشر | math مفعول به مباشر',
                'Math يوجد مفعول به واحد في الجملة وهو كلمة'
            ],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'الجملة Nada saw ’im with ’em. هي نفسها الجملة:',
            'type' => 'multiple_choice',
            'options' => [
                'Nada saw them with him.',
                'Nada saw him with them.',
                'Nada saw him with her.',
                'Nada saw them with themselves.'
            ],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الصفات: الألوان blue \ red تعتبر من الصفات (مجردة).',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر الإجابة الصحيحة للترجمة الصحيحة لجملة ( عبير تكون متعلمة)',
            'type' => 'multiple_choice',
            'options' => ['Abeer is educated.', 'Abeer is educating.', 'Abeer was educated.', 'Abeer was educating.'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما الترجمة الصحيحة لجملة ( قصة مكتوبة)',
            'type' => 'multiple_choice',
            'options' => ['Writing story', 'Written story', 'Wrote story', 'Story written'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الصفات التي تأتي( بعد) الاسم تسمى Attributive adjective والصفات التي تأتي( قبل) الاسم تسمى Predicative adjective',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'I am looking at a ____, ____, _________, ______. اختر الترتيب الصحيح للصفات في الجملة:',
            'type' => 'multiple_choice',
            'options' => [
                'Beautiful, yellow, small bird.',
                'Bird beautiful, small, yellow.',
                'Beautiful, small, yellow bird.',
                'Small, yellow, beautiful bird.'
            ],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'صل الترتيب الصحيح للصفات إذا أردنا ان نذكر أكثر من صفة في نفس الجملة:',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "العدد", "right" => "الترتيب الأول"],
                ["left" => "الرأي", "right" => "الترتيب الثاني"],
                ["left" => "الحجم", "right" => "الترتيب الثالث"],
                ["left" => "العمر", "right" => "الترتيب الرابع"],
                ["left" => "الشكل", "right" => "الترتيب الخامس"],
                ["left" => "اللون", "right" => "الترتيب السادس"],
                ["left" => "الأصل (المنشأ)", "right" => "الترتيب السابع"],
                ["left" => "المادة الخام", "right" => "الترتيب الثامن"],
                ["left" => "الغرض او الهدف", "right" => "الترتيب التاسع"]
            ],
            'points' => 9,
        ],
        [
            'text' => 'الصفة Wild في جملة (The lion is wild) تسمى صفة( قبلية) لأنها أتت قبل الاسم الموصوف.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'نستخدم الحال قبل الصفة لوصف الصفة',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الظروف: الحال من الصفة Fast هو:',
            'type' => 'multiple_choice',
            'options' => ['Fastly', 'Faster', 'Fast', 'Fasttly'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس الظروف: الحال Well مأخوذ من الصفة ____',
            'type' => 'multiple_choice',
            'options' => ['Bad', 'Good', 'Wel', 'Welly'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'Amy writes her homework carefully. ما هو الحال في الجملة؟',
            'type' => 'multiple_choice',
            'options' => ['Homework', 'Carefully', 'Her', 'Writes'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'جميع الظروف والاحوال تنتهي ب Ly بلا استثناء.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'الحال من الصفة real هو:',
            'type' => 'multiple_choice',
            'options' => ['reall', 'Really', 'realily', 'real'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'صل نوع الظرف مع استخدامه المناسب:',
            'type' => 'drag_drop',
            'options' => null,
            'correct' => null,
            'matching_pairs' => [
                ["left" => "ظروف التكرار (Frequency)", "right" => "عدد مرات حدوث الشيء"],
                ["left" => "ظروف الزمان (Time)", "right" => "زمن حدوث الشيء"],
                ["left" => "ظروف المكان (Place)", "right" => "مكان حدوث الشيء"],
                ["left" => "ظروف الاسلوب (Manners)", "right" => "كيف يحدث الشيء"],
                ["left" => "ظروف الدرجة (Degree)", "right" => "درجة فعل او صفة او ظرف"],
                ["left" => "ظروف التعليق (Comment)", "right" => "رأي المتحدث بالحدث"],
                ["left" => "ظروف وجهة نظر (View point)", "right" => "خلفية عن ما سيقال"]
            ],
            'points' => 7,
        ],
        [
            'text' => 'I went to the castle yesterday. ما نوع الحال في الجملة؟',
            'type' => 'multiple_choice',
            'options' => ['Adverb of view point', 'Adverb of degree', 'Adverb of time', 'Adverb of place'],
            'correct' => 2, // C
            'points' => 1,
        ],
        [
            'text' => 'ما ورد في درس attributive nouns: ما هي الأسماء التي تستخدم كصفة ليعرف ويعدل على الاسم الذي يليه؟',
            'type' => 'multiple_choice',
            'options' => ['Compound nouns', 'Attributive nouns', 'Adjective nouns', 'لا شيء مما سبق'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'Coffee table. ما هو الاسم المعرف (Attr. Noun) في هذه الجملة؟',
            'type' => 'multiple_choice',
            'options' => ['The', 'Coffee', 'Table', 'لا يوجد اسم معرف'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'ممكن ان يأتي الاسم المعرف( قبل او بعد) الاسم الذي يريد تعريفه.',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'اختر الترجمة الصحيحة لـ (باص المدينة):',
            'type' => 'multiple_choice',
            'options' => ['City bus', 'Bus city', 'Country bus', 'Bus country'],
            'correct' => 0, // A
            'points' => 1,
        ],
        [
            'text' => 'Winter coat. اختر الترجمة الصحيحة لهذه الصفة:',
            'type' => 'multiple_choice',
            'options' => ['معطف من الشتاء', 'معطف شتوي (خاص للشتاء)', 'معطف صيفي (خاص للصيف)', 'بلوزة شتوي (خاصة للشتاء)'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'لو اردت ان تقول بان الكيكة مخصصة لحفل الزفاف (كيكة زفاف) فانك تقول:',
            'type' => 'multiple_choice',
            'options' => ['A Cake of wedding', 'A Wedding cake', 'A cake wedding', 'A wedding of cake'],
            'correct' => 1, // B
            'points' => 1,
        ],
        [
            'text' => 'A Spoon of silver. اختر الترجمة الصحيحة لهذه الجملة:',
            'type' => 'multiple_choice',
            'options' => ['ملعقة فضية', 'ملعقة مصنوعة من الفضة', 'ملعقة ذهبية', 'ملعقة مصنوعة من الذهب'],
            'correct' => 1, // B
            'points' => 1,
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار الأفعال والصفات والظروف',
            'quiz_type' => 'lesson',
            'duration_minutes' => 30,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'],
            'points' => $qData['points'],
        ];

        if ($qData['type'] === 'drag_drop') {
            $attrs['matching_pairs'] = $qData['matching_pairs'];
            $attrs['correct_answer'] = 'X'; // Dummy for drag_drop
        } else {
            $attrs['option_a'] = $qData['options'][0] ?? null;
            $attrs['option_b'] = $qData['options'][1] ?? null;
            $attrs['option_c'] = $qData['options'][2] ?? null;
            $attrs['option_d'] = $qData['options'][3] ?? null;
            
            // Map index to letter
            $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($attrs);
        
        // Attach to quiz
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 926 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
