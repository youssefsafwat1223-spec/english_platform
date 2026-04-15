<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\PronunciationExercise;
use App\Models\WritingExercise;
use App\Models\ListeningExercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoCourseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Course ───────────────────────────────────────────────
        $course = Course::create([
            'title'                    => 'كورس ديمو — كل الميزات',
            'slug'                     => 'demo-full-features-' . Str::random(4),
            'short_description'        => 'كورس تجريبي يحتوي على كل الأنشطة: كويز، نطق، كتابة، استماع.',
            'description'              => 'هذا الكورس الديمو يعرض جميع ميزات المنصة في مكان واحد.',
            'price'                    => 0,
            'is_active'                => true,
            'order_index'              => 99,
            'created_by'               => 1,
        ]);

        // ─── Level 1: الأساسيات ───────────────────────────────────
        $level1 = CourseLevel::create([
            'course_id'              => $course->id,
            'title'                  => 'الأساسيات',
            'slug'                   => 'basics-' . $course->id,
            'description'            => 'مقدمة للغة الإنجليزية',
            'order_index'            => 1,
            'is_active'              => true,
            'has_writing_exercise'   => true,
            'has_speaking_exercise'  => true,
            'has_listening_exercise' => true,
        ]);

        // ─── Lesson 1 — has quiz ──────────────────────────────────
        $lesson1 = Lesson::create([
            'course_id'       => $course->id,
            'course_level_id' => $level1->id,
            'title'           => 'الدرس الأول: المقدمة',
            'slug'            => 'lesson-intro-' . $course->id,
            'description'     => 'مقدمة عامة باللغة الإنجليزية',
            'text_content'    => '<p>مرحباً بك في الدرس الأول!</p>',
            'order_index'     => 1,
            'is_free'         => true,
            'has_quiz'        => true,
        ]);

        // Questions for lesson 1
        $q1 = Question::create([
            'course_id'     => $course->id,
            'lesson_id'     => $lesson1->id,
            'question_text' => 'What is the English word for "كتاب"?',
            'question_type' => 'multiple_choice',
            'option_a'      => 'Book',
            'option_b'      => 'Pen',
            'option_c'      => 'Table',
            'option_d'      => 'Chair',
            'correct_answer' => 'A',
            'explanation'   => 'كتاب بالإنجليزية = Book',
            'difficulty'    => 'easy',
            'points'        => 10,
        ]);

        $q2 = Question::create([
            'course_id'     => $course->id,
            'lesson_id'     => $lesson1->id,
            'question_text' => 'Which sentence is correct?',
            'question_type' => 'multiple_choice',
            'option_a'      => 'She go to school.',
            'option_b'      => 'She goes to school.',
            'option_c'      => 'She going to school.',
            'option_d'      => 'She gone to school.',
            'correct_answer' => 'B',
            'explanation'   => 'مع الضمير She نستخدم goes مع المضارع البسيط.',
            'difficulty'    => 'easy',
            'points'        => 10,
        ]);

        // Quiz for lesson 1
        $quiz1 = Quiz::create([
            'course_id'                => $course->id,
            'lesson_id'                => $lesson1->id,
            'title'                    => 'كويز الدرس الأول',
            'quiz_type'                => 'lesson',
            'total_questions'          => 2,
            'duration_minutes'         => 10,
            'passing_score'            => 70,
            'is_active'                => true,
            'allow_retake'             => true,
            'show_results_immediately' => true,
            'enable_audio'             => false,
        ]);

        $quiz1->questions()->sync([
            $q1->id => ['order_index' => 1],
            $q2->id => ['order_index' => 2],
        ]);

        // ─── Lesson 2 — pronunciation exercise ───────────────────
        $lesson2 = Lesson::create([
            'course_id'               => $course->id,
            'course_level_id'         => $level1->id,
            'title'                   => 'الدرس الثاني: النطق',
            'slug'                    => 'lesson-pronunciation-' . $course->id,
            'description'             => 'تمرين على النطق الصحيح',
            'text_content'            => '<p>في هذا الدرس ستتعلم النطق الصحيح.</p>',
            'order_index'             => 2,
            'is_free'                 => false,
            'has_pronunciation_exercise' => true,
        ]);

        PronunciationExercise::create([
            'lesson_id'           => $lesson2->id,
            'sentence_1'          => 'The quick brown fox jumps over the lazy dog.',
            'sentence_2'          => 'She sells seashells by the seashore.',
            'sentence_3'          => 'How much wood would a woodchuck chuck?',
            'passing_score'       => 70,
            'max_duration_seconds' => 15,
            'allow_retake'        => true,
            'vocabulary_json'     => [
                ['word' => 'quick',     'pronunciation' => 'kwɪk',    'meaning_ar' => 'سريع'],
                ['word' => 'brown',     'pronunciation' => 'braʊn',   'meaning_ar' => 'بني'],
                ['word' => 'fox',       'pronunciation' => 'fɒks',    'meaning_ar' => 'ثعلب'],
                ['word' => 'lazy',      'pronunciation' => 'ˈleɪzi',  'meaning_ar' => 'كسول'],
                ['word' => 'seashells', 'pronunciation' => 'ˈsiːʃɛlz','meaning_ar' => 'أصداف بحرية'],
            ],
            'sentence_explanation' => 'انطق كل كلمة بوضوح وتباطأ في الكلمات الطويلة.',
            'passage_explanation'  => 'هذه الجمل تحتوي على جميع أصوات اللغة الإنجليزية الأساسية.',
        ]);

        // ─── Lesson 3 — writing exercise ─────────────────────────
        $lesson3 = Lesson::create([
            'course_id'           => $course->id,
            'course_level_id'     => $level1->id,
            'title'               => 'الدرس الثالث: الكتابة',
            'slug'                => 'lesson-writing-' . $course->id,
            'description'         => 'تمرين على الكتابة الإنجليزية',
            'text_content'        => '<p>اكتب عن يومك باللغة الإنجليزية.</p>',
            'order_index'         => 3,
            'is_free'             => false,
            'has_writing_exercise' => true,
        ]);

        WritingExercise::create([
            'lesson_id'    => $lesson3->id,
            'title'        => 'اكتب عن روتينك اليومي',
            'prompt'       => 'Write 50 to 100 words about your daily routine. Include what time you wake up, what you eat, and what you do in the evening.',
            'instructions' => 'Use simple present tense. Write complete sentences. Start each sentence with a capital letter.',
            'min_words'    => 50,
            'max_words'    => 100,
            'passing_score' => 70,
            'model_answer' => 'I wake up at 7 AM every day. I brush my teeth and have breakfast. I usually eat eggs and toast. Then I go to work by bus. In the evening, I watch TV and read a book before sleeping.',
            'rubric_json'  => [
                'grammar'         => 25,
                'vocabulary'      => 25,
                'coherence'       => 25,
                'task_completion' => 25,
            ],
        ]);

        // ─── Lesson 4 — listening exercise (lesson-level) ────────
        $lesson4 = Lesson::create([
            'course_id'              => $course->id,
            'course_level_id'        => $level1->id,
            'title'                  => 'الدرس الرابع: الاستماع',
            'slug'                   => 'lesson-listening-' . $course->id,
            'description'            => 'تمرين على الاستماع والفهم',
            'text_content'           => '<p>استمع جيداً ثم أجب على الأسئلة.</p>',
            'order_index'            => 4,
            'is_free'                => false,
            'has_listening_exercise' => true,
        ]);

        ListeningExercise::create([
            'lesson_id'      => $lesson4->id,
            'course_level_id' => null,
            'title'          => 'استماع: التعريف بالنفس',
            'script_ar'      => 'في هذا الدرس سنتعلم كيفية التعريف بالنفس باللغة الإنجليزية. عندما تلتقي بشخص جديد يمكنك أن تقول <lang xml:lang="en-US">Hello, my name is Ahmed. I am from Egypt. I am a student.</lang> وهذا يعني: مرحباً، اسمي أحمد، أنا من مصر، أنا طالب.',
            'questions_json' => [
                [
                    'type'          => 'mcq',
                    'question'      => 'ما هو اسم الشخص في النص؟',
                    'options'       => ['أحمد', 'محمد', 'علي', 'خالد'],
                    'correct_index' => 0,
                    'explanation'   => 'قال الشخص "my name is Ahmed" أي اسمه أحمد.',
                ],
                [
                    'type'        => 'truefalse',
                    'question'    => 'الشخص في النص من السعودية.',
                    'correct'     => 'false',
                    'explanation' => 'قال "I am from Egypt" أي هو من مصر وليس السعودية.',
                ],
                [
                    'type'          => 'mcq',
                    'question'      => 'ما هي مهنة الشخص؟',
                    'options'       => ['مدرس', 'طالب', 'مهندس', 'طبيب'],
                    'correct_index' => 1,
                    'explanation'   => 'قال "I am a student" أي هو طالب.',
                ],
            ],
            'passing_score'   => 67,
            'audio_generated' => false,
        ]);

        // ─── Level 2: المستوى المتوسط ─────────────────────────────
        $level2 = CourseLevel::create([
            'course_id'              => $course->id,
            'title'                  => 'المستوى المتوسط',
            'slug'                   => 'intermediate-' . $course->id,
            'description'            => 'قواعد ومفردات متوسطة',
            'order_index'            => 2,
            'is_active'              => true,
            'has_writing_exercise'   => true,
            'has_speaking_exercise'  => false,
            'has_listening_exercise' => true,
        ]);

        // ─── Lesson 5 — quiz + writing ────────────────────────────
        $lesson5 = Lesson::create([
            'course_id'           => $course->id,
            'course_level_id'     => $level2->id,
            'title'               => 'درس: الأفعال الشائعة',
            'slug'                => 'lesson-verbs-' . $course->id,
            'description'         => 'تعلم أكثر الأفعال الإنجليزية شيوعاً',
            'text_content'        => '<p>الأفعال الأساسية: go, come, eat, drink, sleep, work, study, play.</p>',
            'order_index'         => 1,
            'is_free'             => false,
            'has_quiz'            => true,
            'has_writing_exercise' => true,
        ]);

        $q3 = Question::create([
            'course_id'     => $course->id,
            'lesson_id'     => $lesson5->id,
            'question_text' => 'What is the past tense of "go"?',
            'question_type' => 'multiple_choice',
            'option_a'      => 'goed',
            'option_b'      => 'gone',
            'option_c'      => 'went',
            'option_d'      => 'goes',
            'correct_answer' => 'C',
            'explanation'   => 'الفعل go شاذ، ماضيه went.',
            'difficulty'    => 'medium',
            'points'        => 10,
        ]);

        $q4 = Question::create([
            'course_id'     => $course->id,
            'lesson_id'     => $lesson5->id,
            'question_text' => '"I ___ to the gym every morning." Choose the correct verb.',
            'question_type' => 'multiple_choice',
            'option_a'      => 'goes',
            'option_b'      => 'go',
            'option_c'      => 'going',
            'option_d'      => 'went',
            'correct_answer' => 'B',
            'explanation'   => 'مع الضمير I في المضارع البسيط نستخدم go بدون s.',
            'difficulty'    => 'medium',
            'points'        => 10,
        ]);

        $quiz2 = Quiz::create([
            'course_id'                => $course->id,
            'lesson_id'                => $lesson5->id,
            'title'                    => 'كويز الأفعال',
            'quiz_type'                => 'lesson',
            'total_questions'          => 2,
            'duration_minutes'         => 10,
            'passing_score'            => 70,
            'is_active'                => true,
            'allow_retake'             => true,
            'show_results_immediately' => true,
            'enable_audio'             => false,
        ]);

        $quiz2->questions()->sync([
            $q3->id => ['order_index' => 1],
            $q4->id => ['order_index' => 2],
        ]);

        WritingExercise::create([
            'lesson_id'    => $lesson5->id,
            'title'        => 'اكتب عن نشاطاتك باستخدام أفعال متنوعة',
            'prompt'       => 'Write 60 to 120 words describing your typical Saturday. Use at least 5 different verbs from today\'s lesson.',
            'instructions' => 'Use simple present tense. Underline the verbs you use. Write at least 5 sentences.',
            'min_words'    => 60,
            'max_words'    => 120,
            'passing_score' => 70,
            'model_answer' => 'On Saturdays, I wake up late at 9 AM. I eat a big breakfast with my family. Then I go to the gym and work out for one hour. In the afternoon, I study English and do my homework. In the evening, I play video games or watch a movie.',
            'rubric_json'  => [
                'grammar'         => 25,
                'vocabulary'      => 25,
                'coherence'       => 25,
                'task_completion' => 25,
            ],
        ]);

        // ─── Section-level listening exercise for level2 ─────────
        ListeningExercise::create([
            'lesson_id'       => null,
            'course_level_id' => $level2->id,
            'title'           => 'اختبار استماع العنوان: المستوى المتوسط',
            'script_ar'       => 'مرحباً بكم في اختبار الاستماع للمستوى المتوسط. سنتحدث اليوم عن الروتين اليومي. يستيقظ <lang xml:lang="en-US">Tom</lang> كل صباح في السابعة. يأكل <lang xml:lang="en-US">breakfast</lang> ثم يذهب إلى <lang xml:lang="en-US">work</lang> بالسيارة. في المساء يمارس <lang xml:lang="en-US">Tom</lang> رياضة <lang xml:lang="en-US">swimming</lang> لمدة ساعة.',
            'questions_json'  => [
                [
                    'type'          => 'mcq',
                    'question'      => 'في أي ساعة يستيقظ Tom؟',
                    'options'       => ['السادسة', 'السابعة', 'الثامنة', 'التاسعة'],
                    'correct_index' => 1,
                    'explanation'   => 'ذُكر في النص أنه يستيقظ في السابعة صباحاً.',
                ],
                [
                    'type'        => 'truefalse',
                    'question'    => 'يذهب Tom إلى العمل بالأتوبيس.',
                    'correct'     => 'false',
                    'explanation' => 'يذهب بالسيارة وليس بالأتوبيس.',
                ],
                [
                    'type'          => 'mcq',
                    'question'      => 'ما الرياضة التي يمارسها Tom في المساء؟',
                    'options'       => ['كرة القدم', 'الجري', 'السباحة', 'كرة السلة'],
                    'correct_index' => 2,
                    'explanation'   => 'يمارس swimming أي السباحة.',
                ],
                [
                    'type'        => 'truefalse',
                    'question'    => 'يمارس Tom الرياضة لمدة ساعتين.',
                    'correct'     => 'false',
                    'explanation' => 'يمارسها لمدة ساعة واحدة فقط.',
                ],
            ],
            'passing_score'   => 75,
            'audio_generated' => false,
        ]);

        // ─── Level 3: المستوى المتقدم (no exercises — plain lessons) ──
        $level3 = CourseLevel::create([
            'course_id'              => $course->id,
            'title'                  => 'المستوى المتقدم',
            'slug'                   => 'advanced-' . $course->id,
            'description'            => 'نصوص وقواعد متقدمة',
            'order_index'            => 3,
            'is_active'              => true,
            'has_writing_exercise'   => false,
            'has_speaking_exercise'  => false,
            'has_listening_exercise' => false,
        ]);

        foreach (['المفردات المتقدمة', 'الجمل المركبة', 'الأساليب البلاغية'] as $i => $title) {
            Lesson::create([
                'course_id'       => $course->id,
                'course_level_id' => $level3->id,
                'title'           => "درس: {$title}",
                'slug'            => Str::slug("lesson-adv-{$i}-{$course->id}"),
                'description'     => "درس تفصيلي عن {$title}",
                'text_content'    => "<p>محتوى درس {$title}.</p>",
                'order_index'     => $i + 1,
                'is_free'         => false,
            ]);
        }

        $this->command->info("✅ Demo course created: [{$course->id}] {$course->title}");
        $this->command->line("   Level 1 (id={$level1->id}): 4 lessons — quiz, pronunciation, writing, listening");
        $this->command->line("   Level 2 (id={$level2->id}): 1 lesson — quiz+writing + section-level listening");
        $this->command->line("   Level 3 (id={$level3->id}): 3 plain lessons");
    }
}
