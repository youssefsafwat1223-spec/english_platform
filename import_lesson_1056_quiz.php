<?php

/**
 * Script to import questions for Lesson ID 1056 (Future Simple Translation)
 * php import_lesson_1056_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 1056;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 1056 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'Adel will peel the potatoes before cooking them. اختر الترجمة الصحيحة بالجملة:', 'options' => ['عادل سوف يقطع البطاطس قبل طبخهم.', 'عادل سوف يقشر البندورة قبل طبخهم.', 'عادل سوف يكون قد قشر البطاطس قبل طبخهم.', 'عادل سوف يقشر البطاطس قبل طبخهم.'], 'correct' => 3],
        ['text' => 'The earthquake will shake the entire region. اختر الترجمة الصحيحة للجملة:', 'options' => ['الزلزال سوف يهز كامل المنطقة.', 'الزلزال هز كامل المنطقة.', 'الزلزال سوف قد يكون هز كامل المنطقة.', 'الزلزال سوف يهز أجزاء من المنطقة.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( المجرم سوف ينكر أي تورط في الجريمة.):', 'options' => ['The criminal won’t deny any involvement in the crime.', 'The criminal will deny any involvement in the crime.', 'The criminal is denying any involvement in the crime.', 'The crime will deny any involvement in the criminal.'], 'correct' => 1],
        ['text' => 'Saleh won’t submit his assignment tonight. اختر الترجمة الصحيحة للجملة:', 'options' => ['صالح سوف لن يقدم مهمته الليلة القادمة.', 'صالح سوف يقدم مهمته الليلة.', 'صالح سوف لن يقدم مهمته الليلة.', 'صالح ما قدم مهمته الليلة.'], 'correct' => 2],
        ['text' => 'My lawyer won’t review the contract tonight. اختر الترجمة الصحيحة للجملة:', 'options' => ['المحامي الخاص بي سوف لن يراجع العقد غدا.', 'القاضي الخاص بي سوف لن يراجع العقد غدا.', 'المحامي الخاص ما راجع العقد أمس.', 'المحامي الخاص بي سوف يراجع العقد غدا.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( الموظف سوف لن يوقع العقد.):', 'options' => ['The employee won’t sign the contract.', 'The employee will sign the contract.', 'The employee won’t signed the contract.', 'The contract will sign the employee.'], 'correct' => 0],
        ['text' => 'Will the new estate enforce a new rule? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل الولاية الجديدة تفرض القانون الجديد؟', 'هل سوف الولاية الجديدة تفرض القانون الجديد؟', 'هل الولاية الجديدة فرضت القانون الجديد؟', 'هل سوف الولاية الجديدة تفرض القانون الجديد.'], 'correct' => 1],
        ['text' => 'Will Mr. Ahmed coordinate you? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف السيد أحمد ينسق لهم.', 'هل السيد أحمد نسق لك.', 'هل سوف السيد أحمد ينسق لك؟', 'هل سوف السيد أحمد ينسق لك.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل سوف السكرتير يبتكر قائمة جديدة؟):', 'options' => ['Will the secretary create a new list?', 'Does the secretary create a new list?', 'Will the secretary create a new list.', 'Will the new list create a secretary?'], 'correct' => 0],
        ['text' => '(Drive – will – separately – I) اختر الترتيب الصحيح للجملة:', 'options' => ['I will fly separately.', 'I will drive separately.', 'I am drive separately.', 'I may drive separately.'], 'correct' => 1],
        ['text' => '(You- They - ? -take- with- will- them) اختر الترتيب الصحيح للسؤال:', 'options' => ['Will you take they with them?', 'Will they take you with them?', 'Will they? take you with them', 'Will them take you with they?'], 'correct' => 1],
        ['text' => '(will - We - early - leave - work - not - today) اختر الترتيب الصحيح للجملة:', 'options' => ['We not leave will work early today.', 'We not will leave work early today.', 'We will not leave work early today.', 'Early we will not leave work today.'], 'correct' => 2],
        ['text' => 'The convict is going to justify his actions in front of the judge. اختر الترجمة الصحيحة للجملة:', 'options' => ['الحاكم راح يبرر افعاله امام القاضي.', 'المحكوم عليه راح يبرر افعاله امام القاضي.', 'المحكوم عليه برر افعاله امام القاضي.', 'المحكوم عليه راح يبرر افعاله امام المحامي.'], 'correct' => 1],
        ['text' => 'The soldiers are going to alternate in AL Hajj mission next week. اختر الترجمة الصحيحة للجملة:', 'options' => ['الجنود راح يتناوبون في مهمة الحج الأسبوع القادم.', 'رجال الشرطة راح يتناوبون في مهمة الحج الأسبوع القادم.', 'الجنود يتناوبون في مهمة الحج الأسبوع القادم.', 'الجنود راح يتناوبون في مهمة الحج الأسبوع الفائت.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة (محلل البيانات راح يقدر التكلفة.):', 'options' => ['The data analyst are going to estimate the cost.', 'The data analyst is going to estimate the cost.', 'The data analyst is going to estimated the cost.', 'The data analyst going to estimate the cost.'], 'correct' => 1],
        ['text' => 'I am not going to exchange my dollars to Saudi riyals. اختر الترجمة الصحيحة للجملة:', 'options' => ['انا ما راح احول دولاراتي الى ريالات سعودية.', 'انا ما راح احول ريالاتي السعودية الى دولارات.', 'انا راح حول دولاراتي الى ريالات سعودية.', 'انا ما راح احول دنانيري الى ريالات سعودية.'], 'correct' => 0],
        ['text' => 'You aren’t going to buy a heater for winter. اختر الترجمة الصحيحة للجملة:', 'options' => ['انت راح تشتري سخان للشتاء.', 'انت ما راح تشتري مروحة للشتاء.', 'انت ما راح تشتري سخان للشتاء.', 'انت ما راح تشتري سخان للصيف.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للجملة (جيراننا ما راح ينتقلون الى دولة أخرى.):', 'options' => ['Our neighbors are going to move to another country.', 'Our neighbors isn’t going to move to another country.', 'Our neighbors aren’t going to move to another country.', 'Our neighbors won’t aren’t going to move to another country.'], 'correct' => 2],
        ['text' => 'Is the pharmacist going to bring the medicine? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل سوف الصيدلي أحضر الدواء؟', 'هل راح الصيدلي يجلب الدواء؟', 'هل راح الصيدلي يبيع الدواء؟', 'هل راح الصيدلي يجهز الدواء.'], 'correct' => 1],
        ['text' => 'Is the professor going to grade student’s papers? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل البروفيسور صحح أوراق الطلاب؟', 'هل البروفيسور سوف يوزع أوراق الطلاب؟', 'هل راح البروفيسور يصحح أوراق الطلاب؟', 'هل البروفيسور سوف يصحح أوراق الطلاب.'], 'correct' => 2],
        ['text' => 'اختر الترجمة الصحيحة للسؤال (هل راح الخياط يأخذ المقاسات؟):', 'options' => ['Does the tailor going to take the measurements?', 'Is the tailor going to take the measurements?', 'Is the tailor going take the measurements?', 'Is the tailor going to take the measurements.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة: (Masjid - to - have - is - Qur’an - going - competition - the)', 'options' => ['The Masjid have is going to Qur’an competition.', 'The Masjid is going to have Qur’an competition.', 'The Qur’an competition is going to have Masjid.', 'The Masjid have is to going Qur’an competition.'], 'correct' => 1],
        ['text' => '(hit - This - to - going - car - the - is - wall.) اختر الترتيب الصحيح للجملة:', 'options' => ['This car is going to hit the wall.', 'This wall is going to hit the car.', 'The car hit going to is the wall.', 'The wall car is going to this hit.'], 'correct' => 0],
        ['text' => '(dust - There – is- , - sky - turn - the - is - gonna – orange) اختر الترتيب الصحيح للجملة:', 'options' => ['There is dust, the sky is gonna turn orange.', 'There is sky, the dust is gonna turn orange.', 'There is orange, the sky is gonna turn dust.', 'The is dust, there sky is gonna turn orange.'], 'correct' => 0],
        ['text' => 'Fatima is leaving to Vancouver tomorrow night. اختر الترجمة الصحيحة للجملة:', 'options' => ['فاطمة غادرت الى فانكوفر ليلة امس.', 'فاطمة بتغادر الي فانكوفر ليلة الغد.', 'فاطمة تغادر الى فانكوفر كل ليلة.', 'فاطمة بتوصيل الى فانكوفر ليل غدا.'], 'correct' => 1],
        ['text' => 'They are targeting a new brand next quarter. اختر الترجمة الصحيحة للجملة:', 'options' => ['هم بيستهدفون (مستهدفين) علامة تجارية جديدة الربع القادم.', 'هم بيستهدفون (مستهدفين) علامة تجارية قديمة الربع القادم.', 'هم استهدفوا علامة تجارية جديدة الربع الفائت.', 'هم ما راح يستهدفون علامة تجارية جديدة الربع القادم.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( محمد بيبدأ حمية غذائية جديد الشهر القادم.):', 'options' => ['Mohammed is starting a new diet last month.', 'Mohammed is starting a new diet next month.', 'Mohammed starts a new diet next month.', 'Mohammed is starting a new diet next week.'], 'correct' => 1],
        ['text' => 'We are not leaving the camp next Monday. اختر الترجمة الصحيحة للجملة:', 'options' => ['نحن بنغادر المخيم الاثنين القادم.', 'نحن ما بنغادر المخيم الاثنين القادم.', 'نحن ما غادرنا المخيم الاثنين الفائت.', 'هم ما بيغادروا المخيم الاثنين القادم.'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للجملة (خالد ما بيعطي (ما هو معطي) محمد ماله الشهر القادم.):', 'options' => ['Khalid ain’t giving Mohammed his money next month.', 'Khalid won’t giving Mohammed his money next month.', 'Khalid wasn’t giving Mohammed his money next month.', 'Mohammed isn’t giving Khalid his money next month.'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة للجملة ( طاقم التمريض ما بيجري ندوة عبر الانترنت الخميس المقبل.):', 'options' => ['The nursing staff is not conducting a webinar next Tuesday.', 'The nursing staff is not conducting a webinar next Thursday.', 'The nursing staff doesn’t conduct a webinar next Thursday.', 'The nursing staff is conducting a webinar next Thursday.'], 'correct' => 1],
        ['text' => 'Are the roses blooming next week? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل الزهور تفتحت الأسبوع الفائت؟', 'هل الزهور بتتفتح الأسبوع المقبل.', 'هل الزهور بتتفتح الأسبوع المقبل؟', 'هل الزهور بتذبل الأسبوع المقبل؟'], 'correct' => 2],
        ['text' => 'Is the painter opening his studio next week? اختر الترجمة الصحيحة للسؤال:', 'options' => ['هل الرسام بيفتح مرسمه الأسبوع القادم.', 'هل الرسام بيفتح مرسمه الأسبوع القادم؟', 'هل الرسام ما بيفتح مرسمه الأسبوع القادم؟', 'هل الرسام فتح مرسمه الأسبوع الفائت؟'], 'correct' => 1],
        ['text' => 'اختر الترجمة الصحيحة للسؤال ( هل الطباخ بيجرب اكله جديدة غدا.):', 'options' => ['Is the chief trying a new dish tomorrow.', 'Is the chief trying a new dish tomorrow?', 'Are the chief trying a new dish tomorrow?', 'Has the chief tried a new dish tomorrow?'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة: (drone - The - delivery - is - tomorrow - bringing - your - morning - package)', 'options' => ['The delivery drone is bringing your package tomorrow morning.', 'Your the package is bringing delivery drone tomorrow morning.', 'The delivery drone is bringing your package morning tomorrow.', 'The delivery drone is bringing package your tomorrow morning.'], 'correct' => 0],
        ['text' => 'اختر الترتيب الصحيح للجملة: (next – the - retiring - is - general - year)', 'options' => ['The general is next retiring year.', 'The general is retiring next year.', 'Retiring the general is next year.', 'Next the general is retiring year.'], 'correct' => 1],
        ['text' => 'اختر الترتيب الصحيح للجملة: (horses - practicing - the - are - for - week - the - competition - next)', 'options' => ['For the horses are practicing the competition next week.', 'The horses are practicing for the competition week next.', 'The competition are practicing for the horses next week.', 'The horses are practicing for the competition next week.'], 'correct' => 3],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'ترجمة المستقبل البسيط (Future Simple Translation)',
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
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 1056.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
