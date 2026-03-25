<?php

/**
 * Script to import questions for Lesson ID 948
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_948_quiz.php
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
    $lessonId = 948;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 948 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions
    $questionsData = [
        [
            'text' => 'جميع ما يلي من أنواع حروف الجر ما عدا؟',
            'type' => 'multiple_choice',
            'options' => ['حروف جر الحركة Prepositions of movement', 'حروف جر الزمانية Prepositions of time', 'حروف جر المكانية Prepositions of place', 'حروف جر الاسلوب Prepositions of manners'],
            'correct' => 3, // Manners
        ],
        [
            'text' => 'نستخدم حروف الجر في اللغة الإنجليزية ل ------؟',
            'type' => 'multiple_choice',
            'options' => ['اضافة معنى للكلمة', 'ربط كلمة بكلمة', 'تحديد الجمع والمفرد', 'اضافة معنى للكلمة\ربط كلمة بكلمة'],
            'correct' => 3, // Both
        ],
        [
            'text' => 'اختر المعنى( العام) لحرف الجر in:',
            'type' => 'multiple_choice',
            'options' => ['على', 'في', 'بين', 'على وبين'],
            'correct' => 1, // في
        ],
        [
            'text' => 'اختر المعنى( العام) لحرف الجر On:',
            'type' => 'multiple_choice',
            'options' => ['على', 'في', 'بين', 'على وبين'],
            'correct' => 0, // على
        ],
        [
            'text' => 'ما هي الحروف التي تخبرنا عن مكان الشيء؟',
            'type' => 'multiple_choice',
            'options' => ['حروف جر الزمان preposition of time', 'حروف جر المكان preposition of place', 'حروف جر المساحة preposition of space', 'ليس مما سبق'],
            'correct' => 1, // Place
        ],
        [
            'text' => 'اختر حرف الجر المناسب للصورة التالية (بناءً على موقع الكرة بالنسبة للصندوق):',
            'type' => 'multiple_choice',
            'options' => ['Between', 'Behind', 'Next to', 'under'],
            'correct' => 0, // Assuming context or placeholder
        ],
        [
            'text' => 'ما الترجمة الصحيحة لجملة (تحت الكرسي)؟',
            'type' => 'multiple_choice',
            'options' => ['next to the chair', 'on the chair', 'under the chair', 'in the chair'],
            'correct' => 2, // under
        ],
        [
            'text' => 'اختر حرف الجر المناسب ليصبح معنى الجملة( على الكرسي)؟ ( ----- the chair)',
            'type' => 'multiple_choice',
            'options' => ['In', 'On', 'Under', 'Between'],
            'correct' => 1, // On
        ],
        [
            'text' => 'ما هي الحروف التي تخبرنا عن وقت او زمن حدوث الشيء؟',
            'type' => 'multiple_choice',
            'options' => ['حروف جر الزمان preposition of time', 'حروف جر المكان preposition of place', 'حروف جر الحركة preposition of movement', 'ليس مما سبق'],
            'correct' => 0, // Time
        ],
        [
            'text' => 'استخدام حرف الجر(in) للزمان يكون قبل جميع ما يلي ما عدا ------',
            'type' => 'multiple_choice',
            'options' => ['السنوات', 'الشهور', 'الأيام', 'الأماكن'],
            'correct' => 2, // Days (on)
        ],
        [
            'text' => 'استخدام حرف الجر(at) للزمان يكون قبل -------',
            'type' => 'multiple_choice',
            'options' => ['أجزاء النهار', 'الساعات', 'أجزاء(أوقات) من اليوم والساعات', 'ليس مما سبق'],
            'correct' => 2, // Both
        ],
        [
            'text' => 'ما هي الحروف التي تخبرنا عن الحركة من مكان الى مكان اخر------',
            'type' => 'multiple_choice',
            'options' => ['حروف جر الزمان preposition of time', 'حروف جر المكان preposition of place', 'حروف جر الحركة preposition of movement'],
            'correct' => 2, // Movement
        ],
        [
            'text' => 'جميع ما يلي من حروف جر الحركة preposition of movement ما عدا--------',
            'type' => 'multiple_choice',
            'options' => ['Out of', 'Around', 'Past', 'At'],
            'correct' => 3, // At
        ],
        [
            'text' => 'اخترالصحيح من الأجوبة التالية التي تحتوي على حروف الجر الحركة (Preposition of movement):',
            'type' => 'multiple_choice',
            'options' => ['Around    at    into', 'before towards across', 'past    over    through', 'after    across    towards'],
            'correct' => 2, // past over through
        ],
        [
            'text' => 'صل كل حرف من حروف الحركة بمعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "Out of", "right" => "خارج من"],
                ["left" => "onto", "right" => "يصعد على"],
                ["left" => "around", "right" => "حول (في مكان قريب)"],
                ["left" => "Away from", "right" => "بعيدا عن"],
                ["left" => "toward", "right" => "باتجاه"],
                ["left" => "past", "right" => "متجاوز"],
                ["left" => "across", "right" => "عبر (يقطع)"],
                ["left" => "through", "right" => "من خلال"]
            ],
            'points' => 8,
        ],
        [
            'text' => 'نستخدم حرف الجر (On) مع وسيلة المواصلات taxi',
            'type' => 'true_false',
            'options' => ['صح', 'خطأ'],
            'correct' => 1, // B
        ],
        [
            'text' => 'نستخدم حرف الجر in مع وسيلتين من وسائل المواصلات وهم -------؟',
            'type' => 'multiple_choice',
            'options' => ['Car\plane', 'Plane\taxi', 'Taxi\car', 'Train\van'],
            'correct' => 2, // Taxi/car
        ],
        [
            'text' => 'ما حرف الجر المناسب لهذه الجملة ( ----- her car.) لتصبح معناها( في سيارتها)؟',
            'type' => 'multiple_choice',
            'options' => ['At', 'In', 'On', 'جميع ما سبق صحيح'],
            'correct' => 1, // In
        ],
        [
            'text' => 'اختر الإجابة الصحيحة لما يأتي بعد حرف الجر ( on ):',
            'type' => 'multiple_choice',
            'options' => ['Days أيام\ Dates تواريخ \ Special days أيام مميزة', 'Months شهور \ Countries دول\ Years سنوات', 'Some transports بعض مواصلات \ Names of streets اسماء شوارع', 'جميع ما سبق'],
            'correct' => 0, // Most accurate, though street names also use on. But usually in these courses, it's days/dates.
        ],
        [
            'text' => 'كيف نحدد المواصلات التي يجب ان نضع قبلها حرف الجر on؟',
            'type' => 'multiple_choice',
            'options' => [
                'ان تكون وسيلة المواصلات صغيرة وخاصة',
                'ان تكون وسيلة مواصلات كبيرة وعامة',
                'ان تكون وسيلة مواصلات كبيرة وعامة يمكننا ان نمشي بها(بها ممر)',
                'جميع ما سبق'
            ],
            'correct' => 2, // The specific one
        ],
        [
            'text' => 'اختر الإجابة الصحيحة التي تحتوي على جميع وسائل المواصلات التي نستطيع معها استخدام حرف الجر (on) :',
            'type' => 'multiple_choice',
            'options' => ['Car       van       taxi', 'van     plane       train', 'bike       motorcycle bus'],
            'correct' => 2, // bike motorcycle bus
        ],
        [
            'text' => 'اختر حرف الجر المناسب لجملة (---- the plane) ليصبح معناها على متن الطائرة؟',
            'type' => 'multiple_choice',
            'options' => ['In', 'On', 'At', 'of'],
            'correct' => 1, // On
        ],
        [
            'text' => 'اختر حرف الجر المناسب قبل الايام (------ Sunday.)',
            'type' => 'multiple_choice',
            'options' => ['In', 'On', 'At', 'For'],
            'correct' => 1, // On
        ],
        [
            'text' => 'نستخدم حرف الجر At لجميع ما يلي ما عدا:',
            'type' => 'multiple_choice',
            'options' => ['الساعات', 'أوقات من اليوم', 'عناوين محددة', 'أماكن عمل معينة', 'الشهور'],
            'correct' => 4, // months (in)
        ],
        [
            'text' => 'مع الأماكن التي تكون نستخدم حرف الجر (at):',
            'type' => 'multiple_choice',
            'options' => ['مكان محدودة صغيرة', 'مكان كبيرة (كبيرة الحجم)', 'أماكن كبيرة داخلها أماكن كثيرة مثل المدرسة', 'جميع ما سبق'],
            'correct' => 0, // Specific
        ],
        [
            'text' => 'اي حرف جر نستخدم مع كلمة(work)؟',
            'type' => 'multiple_choice',
            'options' => ['At work', 'In work', 'On work', 'Over work'],
            'correct' => 0, // At work
        ],
        [
            'text' => 'نستخدم حرف الجر By للمكان فيصبح معناه-------:',
            'type' => 'multiple_choice',
            'options' => ['بواسطة', 'على', 'بجانب', 'ليس مما ذكر'],
            'correct' => 2, // بجانب
        ],
        [
            'text' => 'جميع ما يلي معنى لحرف الجر to ما عدا ؟',
            'type' => 'multiple_choice',
            'options' => ['أن', 'الى', 'حتى', 'من'],
            'correct' => 3, // من
        ],
        [
            'text' => 'ما حرف الجر المناسب هنا؟ (الى النادي) ( -----the club)',
            'type' => 'multiple_choice',
            'options' => ['from', 'to', 'for', 'جميع ما سبق'],
            'correct' => 1, // to
        ],
        [
            'text' => 'صل كل حرف من حروف جر الزمن بمعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "from", "right" => "من"],
                ["left" => "after", "right" => "بعد"],
                ["left" => "Until\till", "right" => "حتى"],
                ["left" => "during", "right" => "خلال"],
                ["left" => "since", "right" => "منذ"],
                ["left" => "ago", "right" => "قبل\مضى"]
            ],
            'points' => 6,
        ],
        [
            'text' => 'نستخدم حرف الجر ----- مع المشاعر و ردة الفعل؟',
            'type' => 'multiple_choice',
            'options' => ['By', 'With', 'At', 'on'],
            'correct' => 2, // At
        ],
        [
            'text' => 'اختر حرف الجر المناسب للصورة التالية:',
            'type' => 'multiple_choice',
            'options' => ['On', 'In', 'Under', 'Next to'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما حرف الجر المناسب ليصبح معناها( مع صديقي)؟ ( ----- my friend)',
            'type' => 'multiple_choice',
            'options' => ['to', 'with', 'for', 'By'],
            'correct' => 1, // with
        ],
        [
            'text' => 'اختر حرف الجر المناسب للصورة التالية:',
            'type' => 'multiple_choice',
            'options' => ['In', 'Between', 'Behind', 'On'],
            'correct' => 1, 
        ],
        [
            'text' => 'اختر حرف الجر المناسب للصورة التالية:',
            'type' => 'multiple_choice',
            'options' => ['Between', 'Behind', 'Next to', 'Under'],
            'correct' => 2, 
        ],
        [
            'text' => 'اختر حرف الجر المناسب للصورة التالية:',
            'type' => 'multiple_choice',
            'options' => ['Among', 'At', 'In', 'on'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما هو حرف الجر المناسب للجملة (seven oclock----) ليصبح معناها (على الساعة السابعة)',
            'type' => 'multiple_choice',
            'options' => ['In', 'On', 'At', 'Under'],
            'correct' => 2, // At
        ],
        [
            'text' => 'صل كل حرف جر من حروف جر المكان بمعناه:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "In front of", "right" => "امام"],
                ["left" => "above", "right" => "فوق"],
                ["left" => "below", "right" => "اسفل"],
                ["left" => "near", "right" => "بالقرب من"],
                ["left" => "Far from", "right" => "بعيدا عن"],
                ["left" => "inside", "right" => "بالداخل"]
            ],
            'points' => 6,
        ],
        [
            'text' => 'صل حرف الجر المناسب لكل كلمة:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "on", "right" => "The bus الباص"],
                ["left" => "in", "right" => "Winter شتاء"],
                ["left" => "at", "right" => "Night ليل"]
            ],
            'points' => 3,
        ],
        [
            'text' => 'جميع ما يلى معنى لحرف الجر To ما عدا:',
            'type' => 'multiple_choice',
            'options' => ['أن', 'الى', 'فوق', 'حتى'],
            'correct' => 2, // فوق
        ],
        [
            'text' => 'صل حرف الجر المناسب ليكون المعنى صحيح:',
            'type' => 'drag_drop',
            'matching_pairs' => [
                ["left" => "of", "right" => "Piece ----- cake قطعة من الكيك"],
                ["left" => "with", "right" => "----- Ahmed مع أحمد"],
                ["left" => "in", "right" => "------ April في شهر ابريل"],
                ["left" => "since", "right" => "----- last night منذ الليلة الفائتة"],
                ["left" => "to", "right" => "------ Makkah الى مكة"]
            ],
            'points' => 5,
        ],
        [
            'text' => 'ما هي الصورة المناسبة لحرف الجر (over فوق)؟',
            'type' => 'multiple_choice',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما هي الصورة المناسبة لحرف الجر (towards الى)؟',
            'type' => 'multiple_choice',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما هي الصورة المناسبة لحرف الجر (away from يتجه بعيدا عن)؟',
            'type' => 'multiple_choice',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما هي الصورة المناسبة لحرف الجر (around حول)؟',
            'type' => 'multiple_choice',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما هي الصورة المناسبة لحرف الجر (through من خلال)؟',
            'type' => 'multiple_choice',
            'options' => ['(1)', '(2)', '(3)', '(4)'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما حرف الجر المناسب لهذه الصورة؟',
            'type' => 'multiple_choice',
            'options' => ['Up اعلى', 'Down اسفل', 'Out of خارج', 'Into داخل'],
            'correct' => 2, 
        ],
        [
            'text' => 'ما حرف الجر المناسب للصورة؟ (Along / Across / Through / Around)',
            'type' => 'multiple_choice',
            'options' => ['Along) على طول( المسافة جانبا', 'Across عبر (عبور)', 'Through خلال', 'Around حول'],
            'correct' => 1, 
        ],
        [
            'text' => 'ما حرف الجر المناسب للصورة؟ ( بمحاذاة على طول المسافة جانبا)',
            'type' => 'multiple_choice',
            'options' => ['Along) بمحاذاة على طول ( المسافة جانبا', 'Across عبر (عبور)', 'Through خلال', 'Around حول'],
            'correct' => 0, 
        ],
        [
            'text' => 'ما حرف الجر المناسب للصورة؟ (Out of / Into / Through / Across)',
            'type' => 'multiple_choice',
            'options' => ['Out of خارج من', 'Into الى الداخل (يدخل)', 'Through من خلال', 'Across عبر(يعبر)'],
            'correct' => 1, 
        ],
        [
            'text' => 'ما حرف الجر المناسب للصورة؟ (خارج من / الى الداخل / من خلال / عبر)',
            'type' => 'multiple_choice',
            'options' => ['Out of خارج من', 'Into الى الداخل (يدخل)', 'Through من خلال', 'Across عبر(يعبر)'],
            'correct' => 0, 
        ],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار حروف الجر (Prepositions)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 45,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    // Clear existing questions
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'],
            'points' => $qData['points'] ?? 1,
        ];

        if ($qData['type'] === 'drag_drop') {
            $attrs['matching_pairs'] = json_encode($qData['matching_pairs']);
            $attrs['correct_answer'] = 'X'; 
        } else {
            $attrs['option_a'] = $qData['options'][0] ?? null;
            $attrs['option_b'] = $qData['options'][1] ?? null;
            $attrs['option_c'] = $qData['options'][2] ?? null;
            $attrs['option_d'] = $qData['options'][3] ?? null;
            
            $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';
        }

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 948 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
