<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Lesson;

class EnglishCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // ── Cleanup: delete partially-seeded course from failed run ──
            $oldCourse = Course::where('slug', 'english-mastery')->first();
            if ($oldCourse) {
                Lesson::where('course_id', $oldCourse->id)->forceDelete();
                CourseLevel::where('course_id', $oldCourse->id)->forceDelete();
                $oldCourse->forceDelete();
                echo "🗑  Cleaned up old partial course (ID {$oldCourse->id})\n";
            }

            // 1) Create the Course
            $course = Course::create([
                'title'                  => 'احتراف اللغة الإنجليزية',
                'slug'                   => 'english-mastery',
                'short_description'      => 'منهج شامل لتعلم اللغة الإنجليزية من الصفر حتى الاحتراف',
                'description'            => '<p>كورس شامل لتعلم اللغة الإنجليزية يغطي كل شيء من الحروف الأبجدية وحتى الأزمنة المتقدمة والقواعد النحوية الكاملة.</p>',
                'price'                  => 0,
                'is_active'              => true,
                'order_index'            => 1,
                'created_by'             => 1,
            ]);

            $courseId = $course->id;

            // Curriculum: level_title => [lesson titles]
            $curriculum = [
                'التمهيد الأول — First Introduction' => [
                    'ابدأ هنا — Start Here',
                    'المنهج — Curriculum',
                    'المرفقات — Attachments',
                    'طرق التواصل — Contact Methods',
                    'طريقة حل الأسئلة — How to Solve Questions',
                    'كيفية استخدام المقاطع اللفظية — How to Use Syllables',
                    'كيفية معرفة تصريف الفعل — How to Know Verb Forms V1 V2 V3',
                    'استخدام أداة ترجمة قوقل — Using Google Translate',
                    'اختبار تحديد المستوى — Placement Test',
                ],
                'الحروف الأبجدية — Alphabets' => [
                    'الحروف من A إلى F — Letters A to F',
                    'الحروف من G إلى L — Letters G to L',
                    'الحروف من M إلى R — Letters M to R',
                    'الحروف من S إلى Z — Letters S to Z',
                    'أسئلة نموذجية — Practice Questions',
                ],
                'الحروف المركبة — Compound Letters' => [
                    'Ph · Ch · Sh · Th · Gh · Dh · Kh · Ck · Sc · Ng · Rh · Wh',
                    'أسئلة نموذجية — Practice Questions',
                ],
                'الحروف الصوتية — Vowels' => [
                    'المقدمة والحالة الأولى — Introduction and First Case',
                    'الحالة الثانية — Second Case',
                    'الحالة الثالثة — Third Case',
                    'الحالة الرابعة وغير المنتظمة — Fourth Case and Irregular',
                    'أسئلة نموذجية — Practice Questions',
                ],
                'الأرقام — Numbers' => [
                    'المجموعة الأولى — Group 1',
                    'المجموعة الثانية — Group 2',
                    'المجموعة الثالثة — Group 3',
                    'المجموعة الرابعة — Group 4',
                    'المجموعة الخامسة — Group 5',
                    'المجموعة السادسة — Group 6',
                    'المجموعة السابعة — Group 7',
                    'المجموعة الثامنة — Group 8',
                    'المجموعة التاسعة — Group 9',
                    'الأرقام الترتيبية — Ordinal Numbers',
                    'أسئلة نموذجية — Practice Questions',
                    'جدول الأرقام الترتيبية والعادية — Ordinal and Cardinal Numbers Chart',
                    'المرفقات — Attachments',
                ],
                'أقسام الكلام — Parts of Speech' => [
                    'الاسم — Noun',
                    'الضمير — Pronoun',
                    'الفعل — Verb',
                    'الصفة — Adjective',
                    'الظرف — Adverb',
                    'حرف الجر — Preposition',
                    'حرف العطف — Conjunction',
                    'حرف التعجب — Interjection',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي الأول — Midterm 1' => [],
                'التمهيد الثاني — Second Introduction' => [
                    'ابدأ هنا — Start Here',
                    'المنهج — Curriculum',
                    'المرفقات — Attachments',
                    'طرق التواصل — Contact Methods',
                    'طريقة حل الأسئلة — How to Solve Questions',
                ],
                'البادئة واللاحقة — Prefix and Suffix' => [
                    'المقدمة — Introduction',
                    'البادئة — Prefix',
                    'اللاحقة — Suffix',
                    'الكل في واحد — All in One',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'علامات الترقيم — Punctuation' => [
                    'الحرف الكبير — Capitalization',
                    'النقطة — Full Stop',
                    'علامة الاستفهام — Question Mark',
                    'الفاصلة — Comma',
                    'الفاصلة العلوية — Apostrophe',
                    'علامة التعجب — Exclamation Mark',
                    'النقطتان الرأسيتان — Colon',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المفرد والجمع — Singular and Plural' => [
                    'الحالة الأولى — First Case',
                    'الحالة الثانية — Second Case',
                    'الحالة الثالثة — Third Case',
                    'الحالة الرابعة — Fourth Case',
                    'الحالة الخامسة — Fifth Case',
                    'الحالة السادسة — Sixth Case',
                    'الحالة السابعة — Seventh Case',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'أدوات التعريف — Articles (A, An, The)' => [
                    'أداتا التعريف a و an — Articles A and An',
                    'أداة التعريف The — Article The',
                    'أدوات التعريف مع الصفة — Articles with Adjectives',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الفاعل — Subject' => [
                    'الفاعل — Subject',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'تصريف الأفعال — Verb Forms' => [
                    'المقدمة — Introduction',
                    'الصيغة الأساسية — Base Form V1',
                    'صيغة الماضي البسيط — Past Simple V2',
                    'الفعل المنتظم — Regular Verb',
                    'الفعل غير المنتظم — Irregular Verb',
                    'الماضي التام — Past Participle V3',
                    'أسئلة نموذجية — Practice Questions',
                ],
                'الاختبار النصفي الثاني — Midterm 2' => [],
                'أنواع الأفعال — Types of Verbs' => [
                    'الأفعال الأساسية — Main Verbs',
                    'الأفعال المساعدة — Helping Verbs',
                    'الأفعال الناقصة — Modal Verbs',
                    'الأفعال المتعدية — Transitive Verbs',
                    'الأفعال اللازمة — Intransitive Verbs',
                    'الأفعال المتعدية واللازمة — Transitive and Intransitive Verbs',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'فعل الكينونة — Verb to Be' => [
                    'فعل Be في المضارع — Be in Present Tense',
                    'فعل Be في الماضي — Be in Past Tense',
                    'فعل Be في التصريف الثالث — Be in Past Participle',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المفعول به — Object' => [
                    'ضمائر المفعول به — Objective Pronouns',
                    'الضمائر المنعكسة — Reflexive Pronouns',
                    'المفعول به المباشر وغير المباشر — Direct and Indirect Object',
                    'أمثلة على أشكال المفعول به — Examples of Object Forms',
                    'المفعول به في العامية — Object in Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الصفات — Adjectives' => [
                    'مواقع الصفات — Location of Adjectives',
                    'الصفات المجردة — Abstract Adjectives',
                    'صفات الفاعل — Present Participle V1+ing',
                    'صفات المفعول به — Past Participle V3',
                    'صفات المقارنة والتفضيل — Comparative and Superlative',
                    'الفرق بين صفات الفاعل وصفات المفعول به — Difference Between Active and Passive Adjectives',
                    'ترتيب الصفات — Order of Adjectives',
                    'استخدام الظروف مع الصفات — Using Adverbs with Adjectives',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الظروف — Adverbs' => [
                    'المقدمة — Introduction',
                    'موقع الظرف — Location of Adverb',
                    'ظرف الزمان — Adverb of Time',
                    'ظرف المكان — Adverb of Place',
                    'ظرف التكرار — Adverb of Frequency',
                    'ظرف الدرجة — Adverb of Degree',
                    'ظرف الأسلوب — Adverb of Manner',
                    'ظرف التعليق — Adverb of Comment',
                    'ظرف وجهة النظر — Adverb of Point of View',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاسم المنسوب — Attributive Nouns' => [
                    'الاسم المنسوب — Attributive Nouns',
                    'أسئلة نموذجية — Practice Questions',
                ],
                'الاختبار النصفي الثالث — Midterm 3' => [],
                'التمهيد الثالث — Third Introduction' => [
                    'ابدأ هنا — Start Here',
                    'المنهج — Curriculum',
                    'طرق التواصل — Contact Methods',
                    'طريقة حل الأسئلة — How to Solve Questions',
                ],
                'الملكية — Possessive' => [
                    'أنواع وصفات الملكية — Types and Adjectives of Possessive',
                    'ضمائر الملكية — Possessive Pronouns',
                    'الفرق بين صفات وضمائر الملكية — Difference Between Possessive Adjectives and Pronouns',
                    'أسماء الملكية — Possessive Nouns',
                    'التملك الكامل — Own',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'حروف الجر — Prepositions' => [
                    'المقدمة — Introduction',
                    'حرف الجر In — Preposition In',
                    'حرف الجر On — Preposition On',
                    'حرف الجر At — Preposition At',
                    'حرف الجر By — Preposition By',
                    'حرف الجر To — Preposition To',
                    'حروف جر الزمن — Time Prepositions',
                    'حروف جر المكان — Place Prepositions',
                    'حروف جر الحركة — Movement Prepositions',
                    'حروف جر أخرى — Other Prepositions',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الأسماء الإشارية — Demonstrative Pronouns' => [
                    'المقدمة — Introduction',
                    'السؤال — Question',
                    'صيغة الماضي — Past Tense',
                    'السؤال في صيغة الماضي — Questions in Past Tense',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'كلمات وأفعال الربط — Linking Words and Verbs' => [
                    'المقدمة وكلمات الربط — Introduction and Linking Words',
                    'التباين — Contrast',
                    'الترتيب والتسلسل — Sequence',
                    'العواقب والنتائج — Consequences',
                    'السبب — Reason',
                    'الشرط — Condition',
                    'اليقين والتأكيد — Certainty',
                    'الملخص — Summary',
                    'أفعال الربط — Linking Verbs',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي الرابع — Midterm 4' => [],
                'المضارع البسيط — Present Simple' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'العادة — Habit',
                    'الحقيقة — Fact',
                    'المستقبل — Future',
                    'النفي — Negative',
                    'التأكيد — Confirmation',
                    'السؤال — Question',
                    'العامية — Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المضارع المستمر — Present Continuous' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'أفعال تحدث الآن — Actions Happening Now',
                    'عمل شيء خلال فترة معينة — Doing Something Over a Period',
                    'المستقبل المؤكد — Certain Future',
                    'السلوك المكرر — Repeated Behaviour',
                    'النفي — Negative',
                    'السؤال — Question',
                    'العامية — Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المضارع التام — Present Perfect' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'حدث بدأ في الماضي ومازال له أثر — Event Started in Past and Still Has Effect',
                    'الوقت غير المنتهي — Unfinished Time',
                    'الماضي القريب — Recent Past',
                    'النفي — Negative',
                    'السؤال — Question',
                    'الاختصارات — Contractions',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المضارع التام المستمر — Present Perfect Continuous' => [
                    'المقدمة والأمثلة — Introduction and Examples',
                    'الفرق بين المضارع التام والمضارع التام المستمر — Difference Between Present Perfect and Present Perfect Continuous',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي الخامس — Midterm 5' => [],
                'الماضي البسيط — Past Simple' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'الأمثلة — Examples',
                    'التأكيد — Confirmation',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الماضي المستمر — Past Continuous' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'حدث مستمر في فترة محددة — Ongoing Event in a Specific Period',
                    'حدثان مستمران بالتوازي — Two Parallel Ongoing Events',
                    'حدث قطع حدثاً مستمراً — Event That Interrupted an Ongoing Event',
                    'سلوكيات متكررة في الماضي — Repeated Behaviours in the Past',
                    'الاستخدام في الطلب — Use in Requests',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الماضي التام — Past Perfect' => [
                    'الاستخدامات والتكوين والأمثلة — Uses, Formation and Examples',
                    'النفي — Negative',
                    'السؤال — Question',
                    'العامية — Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الماضي التام المستمر — Past Perfect Continuous' => [
                    'الاستخدامات والتكوين والأمثلة — Uses, Formation and Examples',
                    'حدث بسبب حدث مستمر قبله — Event Caused by a Previous Ongoing Event',
                    'الأفعال الخبرية — Stative Verbs',
                    'الفرق بين الماضي المستمر والماضي التام المستمر — Difference Between Past Continuous and Past Perfect Continuous',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي السادس — Midterm 6' => [],
                'التمهيد الرابع — Fourth Introduction' => [
                    'ابدأ هنا — Start Here',
                    'المنهج — Curriculum',
                    'طرق التواصل — Contact Methods',
                    'طريقة حل الأسئلة — How to Solve Questions',
                ],
                'المستقبل البسيط — Future Simple' => [
                    'الاستخدامات والتكوين والاختصارات — Uses, Formation and Contractions',
                    'استخدامات Will و Shall — Uses of Will and Shall',
                    'استخدامات Going to — Uses of Going to',
                    'المضارع المستمر للمستقبل — Present Continuous for Future',
                    'النفي — Negative',
                    'السؤال — Question',
                    'العامية — Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المستقبل المستمر — Future Continuous' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'الاستخدام الأول — First Use',
                    'الاستخدام الثاني — Second Use',
                    'الاستخدام الثالث — Third Use',
                    'الاستخدام الرابع — Fourth Use',
                    'الاستخدام الخامس — Fifth Use',
                    'النفي — Negative',
                    'السؤال — Question',
                    'العامية — Slang',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المستقبل التام — Future Perfect' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'الاستخدام الأول — First Use',
                    'الاستخدام الثاني — Second Use',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المستقبل التام المستمر — Future Perfect Continuous' => [
                    'الاستخدامات والتكوين والأمثلة — Uses, Formation and Examples',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي السابع — Midterm 7' => [],
                'صيغة الأمر — Imperative' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'المنع — Prohibition',
                    'الإصرار — Insisting',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الأفعال الناقصة — Modal Verbs' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'القدرة — Ability',
                    'الاحتمالية — Possibility',
                    'الإذن — Permission',
                    'الإلزام — Obligation',
                    'النصيحة — Advice',
                    'الاقتراح — Suggestion',
                    'الاستنتاج — Deduction',
                    'الطلب — Request',
                    'العرض — Offer',
                    'Will و Would — Will and Would',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'أدوات الاستفهام — Wh- Question Words' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'أدوات السؤال Wh — Wh Question Words',
                    'أمثلة في الأزمنة المختلفة — Examples Across Tenses',
                    'السؤال المنفي — Negative Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'المقارنة — Comparison' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'المقارنة — Comparative',
                    'التفضيل — Superlative',
                    'نفس الشيء — Same',
                    'النفي — Negative',
                    'السؤال — Question',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة ترجمة — Translation Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'محددات الكمية — Quantifiers' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'الأسماء المعدودة — Countable Nouns',
                    'الأسماء غير المعدودة — Uncountable Nouns',
                    'السؤال — Question',
                    'Any — Any',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الأفعال الانعكاسية — Delexical Verbs' => [
                    'الفعل Have — Verb Have',
                    'الفعل Take — Verb Take',
                    'الفعل Give — Verb Give',
                    'الفعل Make — Verb Make',
                    'الفعل Go — Verb Go',
                    'الفعل Do — Verb Do',
                    'الفعل Get — Verb Get',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'يوجد — There is / There are' => [
                    'الاستخدامات والتكوين — Uses and Formation',
                    'النفي — Negative',
                    'السؤال — Question',
                    'تأكيد التشكيك — Confirmation of Doubt',
                    'الماضي — Past',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الوقت — Time' => [
                    'المقدمة — Introduction',
                    'أوقات وفترات اليوم — Times and Periods of the Day',
                    'أنواع الساعات — Types of Clocks',
                    'الطريقة الرسمية — Formal Way',
                    'الطريقة الحديثة — Modern Way',
                    'التوقيت العسكري — Military Time',
                    'أنواع نطق الوقت — Ways of Telling the Time',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'التاريخ — Date' => [
                    'المقدمة والأيام — Introduction and Days',
                    'الشهور — Months',
                    'العقود — Decades',
                    'الأعداد الترتيبية — Ordinal Numbers',
                    'القرون — Centuries',
                    'طريقة قراءة السنة — How to Read the Year',
                    'النطق الأمريكي والبريطاني — American and British Pronunciation',
                    'حروف الجر مع التاريخ — Prepositions with Dates',
                    'أسئلة نموذجية — Practice Questions',
                    'أسئلة عادية — Regular Questions',
                ],
                'الاختبار النصفي الثامن — Midterm 8' => [],
                'الاختبار النهائي — Final Exam' => [],
            ];

            // 2) Create Levels and Lessons
            $levelOrder = 1;
            $globalLessonOrder = 1;

            foreach ($curriculum as $levelTitle => $lessons) {
                $level = CourseLevel::create([
                    'course_id'   => $courseId,
                    'title'       => $levelTitle,
                    'order_index' => $levelOrder,
                    'is_active'   => true,
                ]);

                // If level has no lessons, create one lesson with the level's title
                if (empty($lessons)) {
                    $lessons = [$levelTitle];
                }

                foreach ($lessons as $lessonTitle) {
                    $baseSlug = Str::slug($lessonTitle);
                    if (empty($baseSlug)) {
                        $baseSlug = 'lesson';
                    }
                    $uniqueSlug = $baseSlug . '-' . $globalLessonOrder;

                    Lesson::create([
                        'course_id'       => $courseId,
                        'course_level_id' => $level->id,
                        'title'           => $lessonTitle,
                        'slug'            => $uniqueSlug,
                        'order_index'     => $globalLessonOrder,
                        'is_free'         => false,
                        'has_quiz'        => false,
                    ]);
                    $globalLessonOrder++;
                }

                $levelOrder++;
            }

            echo "✅ Course '{$course->title}' created with ID: {$courseId}\n";
            echo "   Levels: " . ($levelOrder - 1) . "\n";
            echo "   Lessons: " . ($globalLessonOrder - 1) . "\n";
        });
    }
}
