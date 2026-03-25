<?php

/**
 * Script to import questions for Lesson ID 978 (Present Simple)
 * This script handles 124 questions!
 * Place this inside your Laravel root directory and run: 
 * php import_lesson_978_quiz.php
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
    $lessonId = 978;
    $lesson = Lesson::find($lessonId);

    if (!$lesson) {
        die("❌ Lesson with ID 978 not found in the database.\n");
    }

    echo "✅ Found Lesson: " . $lesson->title . "\n";

    $courseId = $lesson->course_id;

    // 2. Questions Array Definitions (124 questions)
    $questionsData = [
        ['text' => 'ما معنى المضارع البسيط في اللغة الإنجليزية؟', 'options' => ['Past simple', 'Present simple', 'Past continuous', 'Present continuous'], 'correct' => 1],
        ['text' => 'يعبر المضارع البسيط عن جميع ما يلي (ما عدا)؟', 'options' => ['الحقائق والوصف', 'عادات و روتين', 'وصف عادة بالماضي', 'تعبير عن شيء سيحدث بالمستقبل ( المواعيد)'], 'correct' => 2],
        ['text' => 'ما هي الأفعال التي نضيف لها (es)؟', 'options' => ['تنتهي بـ (x – s – o – z - sh – ch)', 'هي الأفعال المنتظمة', 'هي الأفعال الغير منتظمة', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'لماذا نضيف (es) للأفعال؟', 'options' => ['لانها تكون أفعال بسيطة', 'حتى لا يحصل لعثمة في النطق', 'لكي يتحول الى جمع', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'اذا كان الفعل ينتهي بحرف (Y) مسبوق بحرف ساكن فاننا:', 'options' => ['نقلب (y) الى (i) ثم نضيف (es)', 'نقلب (y) الى (i) ثم نضيف (s)', 'يبقى (y) كما هو ثم نضيف (ies)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'اذا كان الفعل ينتهي بحرف (Y) مسبوق بحرف علة فاننا:', 'options' => ['نقلب (y) الى (i) ثم نضيف (es)', 'نقلب (y) الى (i) ثم نضيف (s)', 'يبقى (y) كما هو ثم نضيف (s)', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'اختر ضمائر الفاعل التي تأخذ (s) او (es) مع الفعل:', 'options' => ['She – he – it – I', 'She – he – it', 'We – he – you', 'They – you – I – we'], 'correct' => 1],
        ['text' => 'اختر ضمائر الفاعل التي (لا) تأخذ (s) او (es) مع الفعل:', 'options' => ['She – he – it – I', 'She – he – it', 'We – he – you', 'They – you – I – we'], 'correct' => 3],
        ['text' => 'اختر التكوين الصحيح لجملة (العادات والحقائق):', 'options' => ['Subject + V1 + do/does + obj', 'Subject + V1 + obj', 'Subject + V1 + s/es + obj', 'Subject + obj + V1'], 'correct' => 2],
        ['text' => 'اختر التكوين الصحيح عندما يكون الفعل الأساسي هو (Is – am – are):', 'options' => ['Subject + V1 + obj', 'Subject + does/do + not + V1', 'Subject + are/is/am + obj', 'Subject + obj + are/is/am'], 'correct' => 2],
        ['text' => 'She always _ breakfast at 7 am.', 'options' => ['eat', 'eats', 'ate', 'eates'], 'correct' => 1],
        ['text' => 'الفاعل في الجملة (She) يكون اسم فاعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['text' => 'جملة (She always eats breakfast at 7 am) تعتبر من:', 'options' => ['الحقائق والوصف', 'عادات و روتين', 'وصف عادة بالماضي', 'مستقبل'], 'correct' => 1],
        ['text' => 'لماذا استخدمنا حرف الجر (at) في جملة (at 7 am)؟', 'options' => ['قبل الارقام', 'قبل الساعات', 'قبل الايام', 'قبل السنوات'], 'correct' => 1],
        ['text' => 'The sun _ in the west.', 'options' => ['Set', 'Setes', 'Sets', 'Sat'], 'correct' => 2],
        ['text' => 'جملة (The sun sets in the west) تعتبر من:', 'options' => ['الحقائق والوصف', 'عادات و روتين', 'وصف عادة بالماضي', 'مستقبل'], 'correct' => 0],
        ['text' => 'لماذا وضعنا (the) قبل كلمة (Sun)؟', 'options' => ['لانها مفرد', 'لانها شمس واحدة في الكون (فريدة)', 'ليس لها جمع', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'The store _ at 9 am.', 'options' => ['Open', 'Opens', 'Openes', 'Opened'], 'correct' => 1],
        ['text' => 'اي من الضمائر يمكن استخدامه لـ (The store)؟', 'options' => ['She', 'It', 'He', 'We'], 'correct' => 1],
        ['text' => 'جملة (The store opens at 9 am) تعبر عن:', 'options' => ['الحقائق', 'عادات', 'ماضي', 'مستقبل (مواعيد)'], 'correct' => 3],
        ['text' => 'Abdullah _ a Muslim.', 'options' => ['Be', 'Is', 'Was', 'Are'], 'correct' => 1],
        ['text' => 'جملة (Abdullah is a Muslim) تعبر عن:', 'options' => ['الحقائق والوصف', 'عادات و روتين', 'ماضي', 'مستقبل'], 'correct' => 0],
        ['text' => 'Hassan and Albaraa _ to the gym everyday.', 'options' => ['go', 'goes', 'gos', 'goies'], 'correct' => 0],
        ['text' => 'ممكن استبدال (Hassan and Albaraa) بضمير:', 'options' => ['We', 'He', 'They', 'You'], 'correct' => 2],
        ['text' => 'The birds _ loudly in the morning.', 'options' => ['chirp', 'chirps', 'chirpes', 'chirpies'], 'correct' => 0],
        ['text' => 'ممكن استبدال (The birds) بضمير:', 'options' => ['We', 'He', 'They', 'You'], 'correct' => 2],
        ['text' => 'ضمير الفاعل (They) يحل محل اسم الفاعل:', 'options' => ['العاقل الجمع', 'الغير عاقل الجمع', 'العاقل وغير العاقل الجمع', 'المفرد'], 'correct' => 2],
        ['text' => 'I usually _ a movie on Friday nights.', 'options' => ['watchs', 'watches', 'watch', 'watchies'], 'correct' => 2],
        ['text' => 'لماذا وضعنا (on) في (on Friday nights)؟', 'options' => ['قبل الأيام', 'قبل الشهور', 'قبل الفصول', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'ما الفعل المساعد المناسب لنفي (العادة و الحقيقة)؟', 'options' => ['Did', 'Do', 'Do \ does', 'Have'], 'correct' => 2],
        ['text' => 'التكوين الصحيح لنفي جملة (العادات والحقائق):', 'options' => ['Sub + not + do/does', 'Sub + do/does + not + V1', 'Sub + do/does + not + V+s', 'Sub + obj + V1'], 'correct' => 1],
        ['text' => 'النفي الصحيح لـ (My mother drinks coffee):', 'options' => ['don’t drink', 'doesn’t drink', 'didn’t drink', 'doesn’t drinks'], 'correct' => 1],
        ['text' => 'ما الخطأ في جملة (My mother doesn’t drinks coffee)؟', 'options' => ['يجب ان تكون مجردة (drink)', 'يجب ان تكون (do)', 'يجب ان تكون بعد كلمة (drink)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'ما المفعول به في (My mother drinks coffee every morning)؟', 'options' => ['Coffee', 'My mother', 'Drinks', 'Every morning'], 'correct' => 0],
        ['text' => 'النفي الصحيح لـ (The flowers in the garden bloom in the spring):', 'options' => ['don’t bloom', 'doesn’t bloom', 'don’t blooms', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'الفعل (bloom) في الجملة السابقة هو:', 'options' => ['Intransitive (لازم)', 'Transitive (متعدي)', 'Intransitive (متعدي)', 'لا شيء مما سبق'], 'correct' => 0],
        ['text' => 'نفي (Is – am – are) يكون بتكوين:', 'options' => ['Sub + not + do/does', 'Sub + do/does + not', 'Sub + is/am/are + not + obj', 'لا شيء مما سبق'], 'correct' => 2],
        ['text' => 'النفي الصحيح لـ (volunteers are good people):', 'options' => ['don’t are', 'aren’t', 'are good not', 'do aren’t'], 'correct' => 1],
        ['text' => 'تكوين السؤال لـ (Yes / No) في المضارع البسيط:', 'options' => ['Wh + do/does', 'Do/Does + subject + V1 + obj?', 'Did + subject', 'Do/Does + s/es'], 'correct' => 1],
        ['text' => 'السؤال الصحيح لـ (She writes in her journal...):', 'options' => ['Do she...', 'Does she write ... .', 'Does she write ...?', 'Does she writes...?'], 'correct' => 2],
        ['text' => 'السؤال الصحيح لـ (They practice boxing...):', 'options' => ['Does they...', 'Do they ... .', 'Do they ...?', 'Do they practices...?'], 'correct' => 2],
        ['text' => 'السؤال بـ (Is – am – are) يكون بـ:', 'options' => ['Wh + does', 'Is \ am\are + subject + obj?', 'Is + subject + V1?', 'Is + V1?'], 'correct' => 1],
        ['text' => 'السؤال لـ (Flowers are plants):', 'options' => ['Do flowers are?', 'Are flowers plants?', 'Are flowers do?', 'لا شيء مما سبق'], 'correct' => 1],
        ['text' => 'يجب ان نضيف (s) للفعل (Sarah pass every exam):', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // False, needs 'es'
        ['text' => 'يجب ان نضيف (es) للفعل (My knee hurt me):', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // False, needs only 's'
        ['text' => 'يجب ان نضيف (es) للفعل (The printer mix paints):', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0], // True
        ['text' => 'يجب ان نضيف (S) للفعل (Saleh catch the ball):', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1], // False, needs 'es'
        ['text' => 'My sister _ money.', 'options' => ['Stash', 'Stashes', 'Stashs', 'Stashies'], 'correct' => 1],
        ['_ talk every night.', 'options' => ['We', 'She', 'He', 'It'], 'correct' => 0],
        ['(Habits) هي حقائق كونية لا يمكننا تغيرها؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['اختر الجملة التي تعبر عن (Habit):', 'options' => ['I go to the mosque every Friday.', 'She is smart.', 'The moon’s color is white.', 'Abdullah is an engineer.'], 'correct' => 0],
        ['I _ every morning.', 'options' => ['exercises', 'exercise', 'works', 'goes'], 'correct' => 1],
        ['ترجمة (محمد يركض كل يوم):', 'options' => ['Mohammed run...', 'Mohammed is running...', 'Mohammed runs every day.', 'Mohammed ran...'], 'correct' => 2],
        ['ترجمة (هي تنام متأخراً):', 'options' => ['She sleeps early.', 'She sleeps late.', 'She slept late.', 'She is sleep late.'], 'correct' => 1],
        ['الحقيقة في المضارع البسيط نستخدم معها الكينونة فقط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['ترجمة (صلاح يكون طبيب بيطري):', 'options' => ['Salah is an engineer.', 'Salah I an veterinarian.', 'Salah is a veterinarian.', 'Salah are veterinarian.'], 'correct' => 2],
        ['جملة (سارة تغسل يدها قبل الاكل):', 'options' => ['Sarah washes her hand...', 'Sarah is washing...', 'Sarah washs...', 'Sarah washes her hands before eating.'], 'correct' => 3],
        ['اختر الجملة التي تعبر عن (Fact):', 'options' => ['They study every night.', 'Anas shops...', 'Lions are wild animals.', 'لا يوجد'], 'correct' => 2],
        ['ما الفعل في جملة (Ali is a pilot)؟', 'options' => ['Ali', 'Is', 'A pilot', 'لا شيء'], 'correct' => 1],
        ['الفعل (Is – am – are) يكون فعل أساسي في الحقيقة والوصف فقط؟', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['الكينونةbe تعتبر فعل يتصرف حسب الزمن؟', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['الجملة (We look tired) تعبر عن:', 'options' => ['Fact or description', 'Habit', 'Future', 'لا شيء'], 'correct' => 0],
        ['الجملة (the bus stops at 6 pm) تعبر عن:', 'options' => ['Fact', 'Habit', 'Future', 'لا شيء'], 'correct' => 2],
        ['لا نستخدم المضارع البسيط لزمن المستقبل مثل (المواعيد)؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['الجملة (The hospital opens at 8 am) تعبر عن:', 'options' => ['Fact', 'Habit', 'Future', 'لا شيء'], 'correct' => 2],
        ['ترجمة (الفيلم يبدا الساعة 2 مساء):', 'options' => ['starts at 2 am', 'start at 2 am', 'starts at 2 pm', 'start at 2 pm'], 'correct' => 2],
        ['نستخدم (Do\ does) مع (Not) في نفي العادات فقط؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['Eman _ not eat strawberry.', 'options' => ['do', 'is', 'does', 'am'], 'correct' => 2],
        ['Tayel _ not a college student.', 'options' => ['does', 'is', 'am', 'do'], 'correct' => 1],
        ['me and Ali _not rich.', 'options' => ['is', 'are', 'am', 'does'], 'correct' => 1],
        ['لنفي العادات والحقائق نستخدم (does) ولا بد ان نضيف (s) للفعل؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['اختر الجملة الصحيحة:', 'options' => ['Goats doent...', 'Goats don’t eat meat.', 'Goats is not...', 'Goats aren’t...'], 'correct' => 1],
        ['ما ضمير الفاعل لـ (Goats)؟', 'options' => ['We', 'They', 'It', 'He'], 'correct' => 1],
        ['النفي لـ (I eat cornflakes...):', 'options' => ['I don’t eat...', 'I doesn’t...', 'I am not...', 'I don’t eats...'], 'correct' => 0],
        ['النفي لـ (Faris is an entrepreneur):', 'options' => ['don’t', 'isn’t', 'aren’t', 'doesn’t'], 'correct' => 1],
        ['من الذي يتحكم بالفعل في (I do like coffee)؟', 'options' => ['الفاعل', 'فعل مساعد (do)', 'مفعول به', 'الفعل نفسه'], 'correct' => 1],
        ['كم حرف صوتي يجب ان يكون قبل حرف (S) في نهاية الكلمة؟', 'options' => ['واحد', 'اثنان', 'ثلاثة', 'أربعة'], 'correct' => 0],
        ['من الذي يتحكم بالمفعول به في الجملة؟', 'options' => ['Subject', 'Main verb', 'Object', 'Auxiliary verb'], 'correct' => 1],
        ['من الذي يتحكم بالفعل المساعد؟', 'options' => ['Subject', 'Main verb', 'Object', 'Auxiliary verb'], 'correct' => 0],
        ['بماذا يتحكم الفاعل في الجملة؟', 'options' => ['Subject', 'Main verb', 'Object', 'Auxiliary verb'], 'correct' => 1], // Controls main verb or aux
        ['لماذا يجب معرفة ضمير الفاعل؟', 'options' => ['لتحكم بالمفعول به', 'لتحكم بالفعل والفعل المساعد', 'لتحكم بالتكملة', 'لا شيء'], 'correct' => 1],
        ['(do) في (I do love chocolate) يعتبر:', 'options' => ['فعل أساسي', 'فعل مساعد للتأكيد', 'فعل ثاني', 'مفعول به'], 'correct' => 1],
        ['He _ work as a policeman.', 'options' => ['does', 'do', 'is', 'am'], 'correct' => 0],
        ['الجملة (She doesn’t plays tennis) تعتبر صحيحة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['ترجمة (هل مصلح يلعب كرة سلة ؟):', 'options' => ['Do Muslih...', 'Does Muslih play...?', 'Is Muslih...', 'Does Muslih plays...?'], 'correct' => 1],
        ['نضع الفعل المساعد أولا في السؤال عكس المثبت؟', 'type' => 'true_false', 'options' => ['صح', 'خطا'], 'correct' => 0],
        ['بعد الأفعال المساعدة (Do | does) يأتي الفعل مجرد؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['زمن المضارع البسيط يتكون من فاعل وفعل فقط في ابسط صورة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['_ Saleh work here?', 'options' => ['Do', 'Does', 'Is', 'Are'], 'correct' => 1],
        ['_ he an astronaut?', 'options' => ['Do', 'Does', 'Is', 'Are'], 'correct' => 2],
        ['_ plants need oxygen?', 'options' => ['Do', 'Does', 'Is', 'Are'], 'correct' => 0],
        ['_ she happy?', 'options' => ['Do', 'Does', 'Is', 'Are'], 'correct' => 2],
        ['جملة (She does lives in London) صحيحة؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 1],
        ['(does) في (She does live in London) اتى للتأكيد؟', 'type' => 'true_false', 'options' => ['صح', 'خطأ'], 'correct' => 0],
        ['أي جملة صحيحة؟', 'options' => ['We’re not Irish.', 'Sarah is no here.', 'Sarah is not speak French.', 'لا شيء'], 'correct' => 0],
        ['كيف ننطق (Doesn’t) في العامية؟', 'options' => ['دزن', 'رن', 'دونت', 'دوزنت'], 'correct' => 1], // 'ran' as per prompt context
        ['كيف ننطق (Don’t) في العامية؟', 'options' => ['دزن', 'رن', 'دونت', 'دوزنت'], 'correct' => 2], // 'dont'
        ['ترجمة (انا لا أكون دكتور):', 'options' => ['I not a doctor', 'I isn’t a doctor', 'I am not a doctor', 'لا شيء'], 'correct' => 2],
        ['(She ain’t a school teacher) هي اختصار لـ:', 'options' => ['She not...', 'She isn’t...', 'She doesn’t...', 'She don’t...'], 'correct' => 1],
        ['------ Your children climb trees?', 'options' => ['Do', 'Why', 'Does', 'Are'], 'correct' => 0],
        ['Abdullah --------for UNRWA.', 'options' => ['works', 'work', 'worked', 'working'], 'correct' => 0],
        ['I ----- my car every day.', 'options' => ['drive', 'drove', 'drives', 'ride'], 'correct' => 0],
        ['She ---- a chef in the restaurant.', 'options' => ['is', 'are', 'do', 'does'], 'correct' => 0],
        ['I ---- volleyball twice a week.', 'options' => ['plays', 'play', 'do', 'does'], 'correct' => 1],
        ['Seba------dinner at home.', 'options' => ['don’t eat', 'doesn’t eats', 'eat', 'doesn’t eat'], 'correct' => 3],
        ['She-----reads a book in the evening.', 'options' => ['every day', 'now', 'always', 'yesterday'], 'correct' => 2],
        ['My husband-------news on TV.', 'options' => ['watch', 'watches', 'do', 'watched'], 'correct' => 1],
        ['She ----literature.', 'options' => ['like', 'likes', 'liking', 'liked'], 'correct' => 1],
        ['My brother------- on the floor.', 'options' => ['are sleeping', 'is sleeping', 'sleeps', 'sleep'], 'correct' => 2],
        ['She -------play violin.', 'options' => ['Play', 'don’t play', 'doesn’t play', 'will play'], 'correct' => 2],
        ['They ----- to the supermarket every Saturday.', 'options' => ['went', 'go', 'goes', 'going'], 'correct' => 1],
        ['------she shop in the supermarket? Yes, she--------', 'options' => ['Do/do', 'Does/does', 'Do/don’t', 'Does/doesn’t'], 'correct' => 1],
        ['They ----- play at home, they play at school', 'options' => ['Play', 'Plays', 'Don’t', 'doesn’t'], 'correct' => 2],
        ['The student-----many questions about rain forest.', 'options' => ['Ask', 'Asks', 'Asking', 'askes'], 'correct' => 1],
        ['Our school------small.', 'options' => ['are', 'does', 'do', 'is'], 'correct' => 3],
        ['We don’t -------to the beach.', 'options' => ['goes', 'go', 'gone', 'do'], 'correct' => 1],
        ['I --------near my school.', 'options' => ['live', 'living', 'lives', 'lived'], 'correct' => 0],
        ['The dog--------in the garden.', 'options' => ['live', 'is lives', 'lives', 'living'], 'correct' => 2],
        ['The cat-------inside the cage.', 'options' => ['do sleep', 'sleep', 'sleeps', 'are sleep'], 'correct' => 2],
        ['Sarah --------milk.', 'options' => ['don’t like', 'doesn’t likes', 'doesn’t like', 'like'], 'correct' => 2],
        ['The student-------very hard.', 'options' => ['work', 'works', 'do work', 'are work'], 'correct' => 1],
        ['Khalil -------his car every weekend.', 'options' => ['wash', 'washs', 'washes', 'does wash'], 'correct' => 2],
        ['Hassan and Ali--------books.', 'options' => ['don’t reads', 'reads', 'not read', 'don’t read'], 'correct' => 3],
        ['My sister-------vegetable every day.', 'options' => ['eats', 'do eat', 'is eat', 'eats'], 'correct' => 0],
    ];

    // 3. Create or find Quiz
    $quiz = Quiz::updateOrCreate(
        ['lesson_id' => $lessonId, 'course_id' => $courseId],
        [
            'title' => 'اختبار المضارع البسيط (Present Simple)',
            'quiz_type' => 'lesson',
            'duration_minutes' => 60,
            'total_questions' => count($questionsData),
            'passing_score' => 50,
            'is_active' => 1,
        ]
    );

    echo "✅ Quiz Prepared (ID: {$quiz->id}).\n";

    // 4. Import Questions
    $count = 0;
    $letterMap = ['A', 'B', 'C', 'D'];
    
    $quiz->questions()->detach();

    foreach ($questionsData as $idx => $qData) {
        $attrs = [
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'question_text' => $qData['text'],
            'question_type' => $qData['type'] ?? 'multiple_choice',
            'points' => 1,
        ];

        $attrs['option_a'] = $qData['options'][0] ?? null;
        $attrs['option_b'] = $qData['options'][1] ?? null;
        $attrs['option_c'] = $qData['options'][2] ?? null;
        $attrs['option_d'] = $qData['options'][3] ?? null;
        
        $attrs['correct_answer'] = $letterMap[$qData['correct']] ?? 'A';

        $question = Question::create($attrs);
        $quiz->questions()->attach($question->id, ['order_index' => $idx]);
        
        $count++;
    }

    echo "🎉 Successfully added " . $count . " questions to Lesson 978 Quiz!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
