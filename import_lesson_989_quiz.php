<?php

/**
 * Script to import questions for Lesson ID 989 (Present Continuous Grammar)
 * php import_lesson_989_quiz.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;

try {
    $lessonId = 989;
    $lesson = Lesson::find($lessonId);
    if (!$lesson) die("❌ Lesson 989 not found.\n");
    $courseId = $lesson->course_id;

    $questionsData = [
        ['text' => 'المضارع المستمر في اللغة الإنجليزية هو:', 'options' => ['Present simple', 'Present continuous', 'Present perfect', 'Present perfect continuous'], 'correct' => 1],
        ['text' => 'جميع ما يلي يعبر عن المضارع المستمر ما عدا:', 'options' => ['شيء يحدث الان', 'مستقبل مؤكد', 'سلوكيات متكررة', 'شيء يحدث خلال هذه الفترة', 'عادات وروتين'], 'correct' => 4],
        ['text' => 'ممكن ان يعبر المضارع المستمر عن مستقبل مؤكد.', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح لجملة مثبتة في زمن المضارع المستمر:', 'options' => ['Subject + be + (v1 + ing) + obj', 'Subject + do/does + (v1 + ing) + obj', 'Subject + be + v1 + s/es', 'لا شيء'], 'correct' => 0],
        ['text' => 'الكينونة (Be) في زمن المضارع المستمر هي:', 'options' => ['Was \ were', 'Been', 'Is \ am \ are', 'Be'], 'correct' => 2],
        ['text' => 'لماذا نستخدم الكينونة (be) في هذا الزمن؟', 'options' => ['لأنه زمن بسيط', 'لأنه زمن تام', 'لأنه زمن مستمر ونستخدم Be دائما فيه', 'لا شيء'], 'correct' => 2],
        ['text' => 'نكتب الفعل في زمن المستمر (V1+ing) لأن قبله الكينونة Be والتي دائما بعدها (V1+ing)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'ما هي ضمائر الفاعل التي تأتي مع (is)؟', 'options' => ['He – she – it – I', 'We – you – they', 'I فقط', 'He – she – it'], 'correct' => 3],
        ['text' => 'ما هي ضمائر الفاعل التي تأتي مع (are)؟', 'options' => ['He – she – it – I', 'We – you – they', 'I فقط', 'He – she – it'], 'correct' => 1],
        ['text' => 'ما هي ضمائر الفاعل التي تأتي مع (am)؟', 'options' => ['He – she – it – I', 'We – you – they', 'I فقط', 'He – she – it'], 'correct' => 2],
        ['text' => 'اختر الجملة التي تعبر عن مضارع مستمر:', 'options' => ['is playing yesterday', 'is playing every day', 'He is playing football right now.', 'لا شيء'], 'correct' => 2],
        ['text' => '(I am currently _ a response to your question) اختر الفعل المناسب:', 'options' => ['Type', 'Types', 'Typing', 'Typed'], 'correct' => 2],
        ['text' => '(I am currently typing a response...) يمكننا استبدال ضمير الفاعل باسم فاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(I am currently typing a response...) تحتوي الجملة على ظرف؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0], // currently
        ['text' => '(I am currently typing a response...) حدد نوع الفعل:', 'options' => ['Transitive', 'Intransitive', 'Model verb', 'لا شيء'], 'correct' => 0],
        ['text' => '(I am currently typing a response...) المفعول به في الجملة هو:', 'options' => ['Currently', 'A response', 'Typing', 'I'], 'correct' => 1],
        ['text' => 'My mother _ cooking dinner for us tonight.', 'options' => ['Is', 'Am', 'Are', 'Was'], 'correct' => 0],
        ['text' => 'My mother is cooking dinner for us tonight تعبر عن:', 'options' => ['شيء يحدث الآن', 'احداث مستقبلية مرتب لها حاليا', 'سلوكيات متكررة', 'خطة مستقبلية'], 'correct' => 1],
        ['text' => 'يمكن استبدال My mother بضمير:', 'options' => ['He', 'She', 'I', 'It'], 'correct' => 1],
        ['text' => 'الجملة My mother is cooking dinner tonight تحتوي على مفعول به واحد؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'My friend is _ to Europe next week.', 'options' => ['travels', 'traveling', 'travelling', 'travel'], 'correct' => 2], // UK double LL standard
        ['text' => 'لماذا تكتب كلمة Europe بأول حرف كبير؟', 'options' => ['تبدأ بحرف علة', 'اسم علم Proper noun', 'اسم فريد لا يوجد منه الا واحد', 'لا شيء'], 'correct' => 1],
        ['text' => 'الجملة My mother is talking on the phone with her sister تعبر عن:', 'options' => ['شيء يحدث الان', 'احداث مستقبلية مرتب لها حاليا', 'سلوكيات متكررة', 'خطة مستقبلية'], 'correct' => 0],
        ['text' => 'اختر الجملة التي تعبر عن سلوك متكرر:', 'options' => ['watching now', 'Abdullah is constantly visiting his grandmother.', 'playing in park', 'لا شيء'], 'correct' => 1],
        ['text' => 'We _ learning new skills in our online class.', 'options' => ['Is', 'Am', 'Are', 'Was'], 'correct' => 2],
        ['text' => 'يمكن استبدال ضمير الفاعل We بـ:', 'options' => ['Hassan', 'Ahmed and Hassan', 'Ahmed and I', 'Ahmed and you'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح للنفي الجملة في زمن المضارع المستمر:', 'options' => ['Subject + be + not + (v1 + ing)', 'Subject+ not + be', 'Subject + be + v1+ not + ing', 'لا شيء'], 'correct' => 0],
        ['text' => '(The children are laughing) اختر النفي الصحيح:', 'options' => ['isn’t laughing', 'The children aren’t laughing.', 'aren’t laughing not?', 'Not the children'], 'correct' => 1],
        ['text' => '(The dog is barking loudly outside) الترجمة الصحيحة هي:', 'options' => ['لا ينبح', 'الكلب ينبح بصوت مرتفع بالخارج.', 'ينبح بصوت منخفض', 'هل ينبح؟'], 'correct' => 1],
        ['text' => '(Is not) اختر المكان الصحيح للفاصلة العلوية عند اختصار:', 'options' => ['Isn’t', 'Is’nt', 'I’snt', 'Isnt’'], 'correct' => 0],
        ['text' => 'عند اختصار أي فعل مساعد في حالة النفي فإننا نحذف حروف من الفعل المساعد لختصره؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح لتكوين السؤال في زمن المضارع المستمر:', 'options' => ['Be + subject + (v1 + ing)?', 'Be + subject + (v1 + ing).', 'subject + be + (v1 + ing)?', 'لا شيء'], 'correct' => 0],
        ['text' => '(The students are waiting for the bus) اختر تكوين السؤال المناسب:', 'options' => ['Are the students waiting for the bus.', 'Are the students wait...?', 'Are the students waiting for the bus?', 'Is the students...?'], 'correct' => 2],
        ['text' => 'في تكوين السؤال في زمن المضارع المستمر نرجع الفعل الى مجرد وبدون اضافات؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(Are you using my mobile?) اختر الإجابة الصحيحة للسؤال:', 'options' => ['No , I am not.', 'Yes, I am not.', 'No, he isn’t.', 'لا شيء'], 'correct' => 0],
        ['text' => '(Is your father sleeping?) اختر الإجابة الصحيحة للسؤال:', 'options' => ['Yes, she is.', 'Yes, he is.', 'No, he is.', 'Yes, my father is.'], 'correct' => 1],
        ['text' => 'عند إجابة السؤال فإننا نقوم بتحويل اسم الفاعل الي ضمير الفاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => '(She, work) at the moment? اختر تكوين سؤال مناسب:', 'options' => ['Does she work...', 'Did she work...', 'Is she working at the moment?', 'Is she work...'], 'correct' => 2],
        ['text' => '(they, have) a party tonight? اختر تكوين سؤال مناسب:', 'options' => ['Are they having a party tonight?', 'Are they having...', 'Do they have...', 'Did they having...'], 'correct' => 0],
        ['text' => 'ما هي كلمة ( قاعد او جالس ) في المضارع المستمر التي تعبر ان الشيء مستمر؟', 'options' => ['Subject', 'Be', 'V1', 'لا شيء'], 'correct' => 1],
        ['text' => 'ما هي الترجمة الصحيحة لجملة (أنا قاعد أتكلم معك)؟', 'options' => ['I is talking...', 'I am talk...', 'I’m talking to you.', 'I talking...'], 'correct' => 2],
        ['text' => 'Is – am – are في زمن المضارع المستمر يكون فعل مساعد لأنه يحدد صيغة الزمن؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'الفعل الأساسي بعد Be لا يشترط ان نضيف له (ing) ويكون فعل مجرد V1 فقط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['text' => '(She is making pasta) الترجمة الصحيحة هي:', 'options' => ['هي قاعدة تصنع باستا.', 'هي تصنع باستا.', 'هي تكون تصنع باستا.', 'هي صنعت باستا.'], 'correct' => 0],
        ['text' => 'متى يأتي الفعل Be فعل مساعد في الجملة؟', 'options' => ['اذا اتى بعده فعل مضاف اليه ing', 'إذا اتى كفعل أساسي', 'بعد الفاعل مباشرة', 'بعده صفة'], 'correct' => 0],
        ['text' => 'متى يكون الفعل Be كفعل أساسي؟', 'options' => ['اذا لم يأت فعل ثاني بعد be', 'إذا اتى في مضارع مستمر', 'بعد الفاعل مباشرة', 'في ماضي مستمر'], 'correct' => 0],
        ['text' => '(Rami is always complaining) الجملة تعبر عن:', 'options' => ['ماضي', 'مستقبل', 'يحدث الان', 'حدث وسلوك متكرر'], 'correct' => 3],
        ['text' => '(You are taking a test now) الجملة تعبر عن:', 'options' => ['حدث وسلوك متكرر', 'حدث يحدث حاليا', 'ماضي', 'مستقبل'], 'correct' => 1],
        ['text' => 'الترجمة الصحيحة لجملة (صبا قاعدة تتفرج على آيبادها):', 'options' => ['Seba is watching her IPad.', 'Seba is watch...', 'Seba watching...', 'Seba watches...'], 'correct' => 0],
        ['text' => '(Mohammed is training for a match) تعبر عن:', 'options' => ['سلوك متكرر', 'حدث يحدث الآن', 'حدث يحدث خلال الفترة الحالية', 'مستقبل'], 'correct' => 2],
        ['text' => '(He _ shopping for a pen) الإجابة الصحيحة هي:', 'options' => ['Am', 'Is', 'Are', 'Has'], 'correct' => 1],
        ['text' => 'جميع ما يلي للتعبير عن سلوك متكرر يحدث دائما ما عدا:', 'options' => ['Always', 'Constantly', 'Continually', 'Often'], 'correct' => 3],
        ['text' => '(Hamzah is _ arriving late) الإجابة الصحيحة هي:', 'options' => ['Usually', 'Constantly', 'Sometimes', 'Often'], 'correct' => 1],
        ['text' => '(Dhafer _ washing his car) اختر الإجابة الصحيحة:', 'options' => ['Isn’t', 'Amn’t', 'Doesn’t', 'aren’t'], 'correct' => 0],
        ['text' => '(Fayez isn’t _ now) اختر الإجابة الصحيحة:', 'options' => ['Running', 'Swim', 'Talk', 'Goes'], 'correct' => 0],
        ['text' => '(Mohammed isn’t driving now) الترجمة الصحيحة هي:', 'options' => ['محمد مو قاعد يسوق الآن', 'محمد مو قد ساق الآن', 'محمد قاعد يسوق الآن', 'ليس مما سبق'], 'correct' => 0],
        ['text' => 'اختر الترجمة الصحيحة لجملة (عبير مو قاعدة تلعب):', 'options' => ['Abeer isn’t playing.', 'Abeer wasn’t...', 'Abeer is playing.', 'Abeer hasn’t...'], 'correct' => 0],
        ['text' => '(He ain’t leaving tomorrow) هي نفسها:', 'options' => ['doesn’t leaving', 'He isn’t leaving tomorrow.', 'aren’t leaving', 'لا شيء'], 'correct' => 1],
        ['text' => '(They aren’t working) هي نفسها:', 'options' => ['They ain’t working.', 'They don’t working.', 'They isn’t working.', 'لا شيء'], 'correct' => 0],
        ['text' => 'في تكوين السؤال نبدأ بالفاعل أولا ثم الفعل المساعد Be وتكون معناها (هل)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // Should start with Be
        ['text' => '(The dog is playing in the park) تكوين السؤال هو:', 'options' => ['Is the dog playing in the park?', 'Is the dog play...?', 'Does the dog...?', 'Is the dog plays...?'], 'correct' => 0],
        ['text' => '(_ khalid swimming in the pool?) اختر الفعل المساعد:', 'options' => ['Does', 'Is', 'Are', 'Do'], 'correct' => 1],
        ['text' => '(Is Aisha _ texting?) اختر الإجابة الصحيحة:', 'options' => ['Constantly', 'Sometimes', 'Rarely', 'Often'], 'correct' => 0],
        ['text' => '(Eating – Wafaa – late – is – always) الترتيب الصحيح هو:', 'options' => ['Is wafaa always...', 'Wafaa is always eating late.', 'Wafaa is eating always late.', 'Late wafaa...'], 'correct' => 1],
        ['text' => '(I’m shipping this box tomorrow) الترجمة الصحيحة هي:', 'options' => ['انا قاعد اشحن...', 'انا بأشحن هذا الصندوق غدا.', 'انا يشحن هذا...', 'لا شيء'], 'correct' => 1],
        ['text' => '(I’m shipping this box tomorrow) تعبر الجملة عن:', 'options' => ['شيء يحدث الان', 'مستقبل مؤكد', 'سلوكيات متكررة', 'شيء يحدث خلال هذه الفترة'], 'correct' => 1],
        ['text' => 'كيف عرفت ان هذه الجملة تعبر عن مستقبل مؤكد؟', 'options' => ['بسبب وجود Be', 'عن طريق ظرف الزمان Tomorrow', 'بسبب (V+ing)', 'لا شيء'], 'correct' => 1],
        ['text' => 'اختر الكلمة التي تعرفنا انك تفعل الشيء (الآن)؟', 'options' => ['Always', 'Now', 'Tomorrow', 'Next'], 'correct' => 1],
        ['text' => 'على ماذا تعتمد استخدامات المضارع المستمر في الجمل؟', 'options' => ['الفاعل', 'استخدام الظروف (الأحوال)', 'عن طريق الفعل نفسه', 'جميع ما سبق'], 'correct' => 1],
        ['text' => 'اذا كان المضارع المستمر يعبر عن (مستقبل مؤكد) فإننا نترجم Be لـ:', 'options' => ['قد', 'بـ (سأفعل)', 'سوف', 'فإنني'], 'correct' => 1],
        ['text' => 'عندما نعبر عن المستقبل باستخدام مضارع مستمر فانه يكون اكثر تأكيدا من المستبل البسيط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'في العامية في المضارع المستمر نحذف الفعل المساعد Be عند السؤال؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'اختر التكوين الصحيح للسؤال في زمن المضارع المستمر (في العامية):', 'options' => ['Sub + be + v-ing', 'Sub + do/does', 'Sub + be + v1', 'Subject + (v1 + ing) + obj?'], 'correct' => 3],
        ['text' => '(Are you teaching now?) هو نفسه في العامية:', 'options' => ['Is you teaching...', 'You teaching now?', 'Am you...', 'لا شيء'], 'correct' => 1],
        ['text' => '(playing – she – volleyball – is – now) الترتيب الصحيح هو:', 'options' => ['She playing is...', 'She is playing volleyball now.', 'Volleyball she...', 'جميع ما ذكر'], 'correct' => 1],
        ['text' => '(visiting – tomorrow – not – they – are – us) الترتيب الصحيح هو:', 'options' => ['They are not visiting us tomorrow.', 'They are tomorrow not...', 'They are visiting not...', 'They are tomorrow...'], 'correct' => 0],
        ['text' => '(enjoying – this – I - am – not – webinar) الترتيب الصحيح هو:', 'options' => ['This webinar...', 'I am not enjoying this webinar.', 'Enjoying this webinar...', 'None.'], 'correct' => 1],
        ['text' => '(attending – week – you – this – are – funnel - the) الترتيب الصحيح للسؤال:', 'options' => ['You are attending...', 'Are you attending the funnel this week?', 'This week are you...', 'Are you attending this funnel the week?'], 'correct' => 1],
        ['text' => '(seeing – tonight – Zahra – is – doctor -the) الترتيب الصحيح للسؤال:', 'options' => ['Zahra is seeing...', 'Is Zahra seeing the doctor tonight?', 'Is the Zahra...', 'Is Zahra seeing doctor the...'], 'correct' => 1],
        
        ['text' => 'I -------strawberry now.', 'options' => ['eat', 'eats', 'eating', 'am eating'], 'correct' => 3],
        ['text' => 'My husband--------dinner at the moment.', 'options' => ['cook', 'is cooking', 'cooks', 'cooked'], 'correct' => 1],
        ['text' => 'My dog-------on my bed right now.', 'options' => ['sleeps', 'is sleeps', 'is sleeping', 'slept'], 'correct' => 2],
        ['text' => 'My hands-------blue.', 'options' => ['is becoming', 'become', 'are becoming', 'becomes'], 'correct' => 2],
        ['text' => 'They------soccer outside.', 'options' => ['is playing', 'are playing', 'play', 'plays'], 'correct' => 1],
        ['text' => 'He ------to music.', 'options' => ['listen', 'listens', 'is listening', 'are listening'], 'correct' => 2],
        ['text' => 'She ------dinner at the moment.', 'options' => ['cook', 'are cooking', 'isn’t cooking', 'cook'], 'correct' => 2],
        ['text' => 'I------ watching TV now.', 'options' => ['is', 'am', 'are', 'do'], 'correct' => 1],
        ['text' => 'Are they riding their horses now?', 'options' => ['Yes, they are.', 'No, they are', 'No, they is', 'Yes, they aren’t'], 'correct' => 0],
        ['text' => 'Are the students playing football?', 'options' => ['Yes, they is', 'No, they aren’t', 'Yes, she is', 'No, she isn’t'], 'correct' => 1],
        ['text' => 'My grandpa------the morning newspaper now.', 'options' => ['am reading', 'is reading', 'is reads', 'are read'], 'correct' => 1],
        ['text' => 'Seba and Nour-------walking in the park now.', 'options' => ['is', 'am', 'are', 'do'], 'correct' => 2],
        ['text' => 'My sister -----tennis at the moment.', 'options' => ['play', 'plays', 'is playing', 'played'], 'correct' => 2],
        ['text' => 'I -------my old friend.', 'options' => ['am visiting', 'is visiting', 'are visiting', 'visiting'], 'correct' => 0],
        ['text' => 'Look ! the birds ------------.', 'options' => ['fly', 'flying', 'is flying', 'are flying'], 'correct' => 3],
        ['text' => 'They -------to music.', 'options' => ['is not listening', 'are not listening', 'not is listening', ' not listening'], 'correct' => 1],
        ['text' => 'The dog ------barking fiercely.', 'options' => ['is', 'am', 'are', 'do'], 'correct' => 0],
        ['text' => '----------my parents------now?', 'options' => ['Are /travelling', 'Is / travelling', 'Is/ travel', 'Are/travel'], 'correct' => 0],
        ['text' => 'My little brother----------because he is hungry.', 'options' => ['cries', 'is crying', 'are crying', 'does cry'], 'correct' => 1],
        ['text' => '----------the number of Arabic speakers rising?', 'options' => ['Is', 'Am', 'Are', 'Does'], 'correct' => 0],
        ['text' => 'Ouch! You---------on my toe.', 'options' => ['am stepping', 'are stepping', 'is stepping', 'do stepping'], 'correct' => 1],
        ['text' => 'The girls--------for the exam.', 'options' => ['study', 'isn’t studying', 'aren’t studying', 'studies'], 'correct' => 2],
        ['text' => 'Listen ! your father-------to a friend right now.', 'options' => ['talk', 'talks', 'is talking', 'are talking'], 'correct' => 2],
        ['text' => 'The children are---------by the river.', 'options' => ['fished', 'fishing', 'fishes', 'fish'], 'correct' => 1],
        ['text' => '------listening to me now?', 'options' => ['Is you', 'Are you', 'You are', 'Do you'], 'correct' => 1],
    ];

    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'قواعد المضارع المستمر (Present Continuous Grammar)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 60,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    $quiz->questions()->detach();
    $letterMap = ['A', 'B', 'C', 'D', 'E'];
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
            'option_e' => $qData['options'][4] ?? null,
            'correct_answer' => $letterMap[$qData['correct']] ?? 'A',
            'points' => 1,
        ];
        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
    }
    echo "🎉 Imported " . count($questionsData) . " questions for Lesson 989.\n";
} catch (\Exception $e) { echo "❌ Error: " . $e->getMessage() . "\n"; }
