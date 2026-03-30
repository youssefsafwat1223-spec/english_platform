<?php
// Batch 1: Lessons 793-813 (Pre-Course Intro + Alphabets + Compound Letters + Vowels)
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PronunciationExercise;
use App\Models\Lesson;

$data = [
    793 => [
        'sentence_1' => 'Welcome to this English course. You will learn to speak, read, and write in English step by step.',
        'sentence_2' => 'This course will help you master English from the very beginning.',
        'sentence_3' => 'Practice every day and you will improve quickly.',
        'passage_explanation' => 'القطعة ترحب بك في الكورس وتوضح إنك هتتعلم الإنجليزي خطوة بخطوة — تحدث وقراءة وكتابة.',
        'sentence_explanation' => 'الجملة تشجعك تتمرن كل يوم عشان تتحسن بسرعة.',
        'vocabulary_json' => json_encode([
            ['word' => 'welcome', 'pronunciation' => '/ˈwel.kəm/', 'meaning_ar' => 'مرحباً'],
            ['word' => 'course', 'pronunciation' => '/kɔːrs/', 'meaning_ar' => 'دورة / كورس'],
            ['word' => 'learn', 'pronunciation' => '/lɜːrn/', 'meaning_ar' => 'يتعلم'],
            ['word' => 'speak', 'pronunciation' => '/spiːk/', 'meaning_ar' => 'يتحدث'],
            ['word' => 'read', 'pronunciation' => '/riːd/', 'meaning_ar' => 'يقرأ'],
            ['word' => 'write', 'pronunciation' => '/raɪt/', 'meaning_ar' => 'يكتب'],
            ['word' => 'English', 'pronunciation' => '/ˈɪŋ.ɡlɪʃ/', 'meaning_ar' => 'إنجليزي'],
            ['word' => 'step', 'pronunciation' => '/step/', 'meaning_ar' => 'خطوة'],
            ['word' => 'practice', 'pronunciation' => '/ˈpræk.tɪs/', 'meaning_ar' => 'يتمرن'],
            ['word' => 'improve', 'pronunciation' => '/ɪmˈpruːv/', 'meaning_ar' => 'يتحسن'],
        ]),
    ],
    796 => [
        'sentence_1' => 'If you have any questions, please contact us through email or social media.',
        'sentence_2' => 'You can send a message anytime and we will reply as soon as possible.',
        'sentence_3' => 'Communication is the key to solving any problem.',
        'passage_explanation' => 'القطعة توضح طرق التواصل — لو عندك أي سؤال تقدر تتواصل من خلال الإيميل أو السوشيال ميديا.',
        'sentence_explanation' => 'التواصل هو المفتاح لحل أي مشكلة.',
        'vocabulary_json' => json_encode([
            ['word' => 'question', 'pronunciation' => '/ˈkwes.tʃən/', 'meaning_ar' => 'سؤال'],
            ['word' => 'contact', 'pronunciation' => '/ˈkɒn.tækt/', 'meaning_ar' => 'يتواصل'],
            ['word' => 'email', 'pronunciation' => '/ˈiː.meɪl/', 'meaning_ar' => 'بريد إلكتروني'],
            ['word' => 'message', 'pronunciation' => '/ˈmes.ɪdʒ/', 'meaning_ar' => 'رسالة'],
            ['word' => 'reply', 'pronunciation' => '/rɪˈplaɪ/', 'meaning_ar' => 'يرد'],
            ['word' => 'social', 'pronunciation' => '/ˈsoʊ.ʃəl/', 'meaning_ar' => 'اجتماعي'],
            ['word' => 'media', 'pronunciation' => '/ˈmiː.di.ə/', 'meaning_ar' => 'وسائل إعلام'],
            ['word' => 'problem', 'pronunciation' => '/ˈprɒb.ləm/', 'meaning_ar' => 'مشكلة'],
            ['word' => 'send', 'pronunciation' => '/send/', 'meaning_ar' => 'يرسل'],
            ['word' => 'key', 'pronunciation' => '/kiː/', 'meaning_ar' => 'مفتاح'],
        ]),
    ],
    797 => [
        'sentence_1' => 'Read the question carefully before you choose the correct answer.',
        'sentence_2' => 'Take your time and think about each option before making a decision.',
        'sentence_3' => 'Always check your answer before you submit it.',
        'passage_explanation' => 'القطعة تعلمك تقرأ السؤال بعناية قبل ما تختار الإجابة الصحيحة.',
        'sentence_explanation' => 'دايماً راجع إجابتك قبل ما تبعتها.',
        'vocabulary_json' => json_encode([
            ['word' => 'carefully', 'pronunciation' => '/ˈker.fə.li/', 'meaning_ar' => 'بعناية'],
            ['word' => 'choose', 'pronunciation' => '/tʃuːz/', 'meaning_ar' => 'يختار'],
            ['word' => 'correct', 'pronunciation' => '/kəˈrekt/', 'meaning_ar' => 'صحيح'],
            ['word' => 'answer', 'pronunciation' => '/ˈæn.sər/', 'meaning_ar' => 'إجابة'],
            ['word' => 'option', 'pronunciation' => '/ˈɒp.ʃən/', 'meaning_ar' => 'خيار'],
            ['word' => 'decision', 'pronunciation' => '/dɪˈsɪʒ.ən/', 'meaning_ar' => 'قرار'],
            ['word' => 'check', 'pronunciation' => '/tʃek/', 'meaning_ar' => 'يراجع'],
            ['word' => 'submit', 'pronunciation' => '/səbˈmɪt/', 'meaning_ar' => 'يرسل / يقدم'],
            ['word' => 'think', 'pronunciation' => '/θɪŋk/', 'meaning_ar' => 'يفكر'],
            ['word' => 'time', 'pronunciation' => '/taɪm/', 'meaning_ar' => 'وقت'],
        ]),
    ],
    798 => [
        'sentence_1' => 'A syllable is a part of a word that has one vowel sound. For example, the word water has two syllables: wa-ter.',
        'sentence_2' => 'Breaking words into syllables makes pronunciation much easier.',
        'sentence_3' => 'Every word has at least one syllable.',
        'passage_explanation' => 'المقطع اللفظي هو جزء من الكلمة فيه صوت حرف علة واحد. مثلاً كلمة water فيها مقطعين: wa-ter.',
        'sentence_explanation' => 'تقسيم الكلمات لمقاطع لفظية يسهّل النطق كثير.',
        'vocabulary_json' => json_encode([
            ['word' => 'syllable', 'pronunciation' => '/ˈsɪl.ə.bəl/', 'meaning_ar' => 'مقطع لفظي'],
            ['word' => 'vowel', 'pronunciation' => '/vaʊəl/', 'meaning_ar' => 'حرف علة'],
            ['word' => 'sound', 'pronunciation' => '/saʊnd/', 'meaning_ar' => 'صوت'],
            ['word' => 'word', 'pronunciation' => '/wɜːrd/', 'meaning_ar' => 'كلمة'],
            ['word' => 'part', 'pronunciation' => '/pɑːrt/', 'meaning_ar' => 'جزء'],
            ['word' => 'example', 'pronunciation' => '/ɪɡˈzæm.pəl/', 'meaning_ar' => 'مثال'],
            ['word' => 'break', 'pronunciation' => '/breɪk/', 'meaning_ar' => 'يكسر / يقسم'],
            ['word' => 'easier', 'pronunciation' => '/ˈiː.zi.ər/', 'meaning_ar' => 'أسهل'],
            ['word' => 'pronunciation', 'pronunciation' => '/prəˌnʌn.siˈeɪ.ʃən/', 'meaning_ar' => 'نطق'],
            ['word' => 'water', 'pronunciation' => '/ˈwɔː.tər/', 'meaning_ar' => 'ماء'],
        ]),
    ],
    799 => [
        'sentence_1' => 'Every verb has three forms. The base form is V1, the past simple is V2, and the past participle is V3.',
        'sentence_2' => 'For example, go is V1, went is V2, and gone is V3.',
        'sentence_3' => 'Knowing verb forms helps you build correct sentences.',
        'passage_explanation' => 'كل فعل له ثلاث تصريفات: V1 الأساسي، V2 الماضي البسيط، V3 التصريف الثالث. مثل go-went-gone.',
        'sentence_explanation' => 'معرفة تصريفات الأفعال تساعدك تبني جمل صحيحة.',
        'vocabulary_json' => json_encode([
            ['word' => 'verb', 'pronunciation' => '/vɜːrb/', 'meaning_ar' => 'فعل'],
            ['word' => 'form', 'pronunciation' => '/fɔːrm/', 'meaning_ar' => 'صيغة / شكل'],
            ['word' => 'base', 'pronunciation' => '/beɪs/', 'meaning_ar' => 'أساس'],
            ['word' => 'past', 'pronunciation' => '/pæst/', 'meaning_ar' => 'ماضي'],
            ['word' => 'simple', 'pronunciation' => '/ˈsɪm.pəl/', 'meaning_ar' => 'بسيط'],
            ['word' => 'participle', 'pronunciation' => '/ˈpɑːr.tɪ.sɪ.pəl/', 'meaning_ar' => 'اسم المفعول'],
            ['word' => 'sentence', 'pronunciation' => '/ˈsen.təns/', 'meaning_ar' => 'جملة'],
            ['word' => 'build', 'pronunciation' => '/bɪld/', 'meaning_ar' => 'يبني'],
            ['word' => 'know', 'pronunciation' => '/noʊ/', 'meaning_ar' => 'يعرف'],
            ['word' => 'help', 'pronunciation' => '/help/', 'meaning_ar' => 'يساعد'],
        ]),
    ],
    800 => [
        'sentence_1' => 'Google Translate is a free tool that helps you understand new words and phrases in any language.',
        'sentence_2' => 'Type the word in English and you will see the Arabic meaning instantly.',
        'sentence_3' => 'Use translation as a learning tool, not a shortcut.',
        'passage_explanation' => 'ترجمة قوقل أداة مجانية تساعدك تفهم كلمات وعبارات جديدة بأي لغة.',
        'sentence_explanation' => 'استخدم الترجمة كأداة تعلم مش طريقة مختصرة.',
        'vocabulary_json' => json_encode([
            ['word' => 'translate', 'pronunciation' => '/trænzˈleɪt/', 'meaning_ar' => 'يترجم'],
            ['word' => 'free', 'pronunciation' => '/friː/', 'meaning_ar' => 'مجاني'],
            ['word' => 'tool', 'pronunciation' => '/tuːl/', 'meaning_ar' => 'أداة'],
            ['word' => 'understand', 'pronunciation' => '/ˌʌn.dərˈstænd/', 'meaning_ar' => 'يفهم'],
            ['word' => 'phrase', 'pronunciation' => '/freɪz/', 'meaning_ar' => 'عبارة'],
            ['word' => 'language', 'pronunciation' => '/ˈlæŋ.ɡwɪdʒ/', 'meaning_ar' => 'لغة'],
            ['word' => 'meaning', 'pronunciation' => '/ˈmiː.nɪŋ/', 'meaning_ar' => 'معنى'],
            ['word' => 'instantly', 'pronunciation' => '/ˈɪn.stənt.li/', 'meaning_ar' => 'فوراً'],
            ['word' => 'type', 'pronunciation' => '/taɪp/', 'meaning_ar' => 'يكتب'],
            ['word' => 'shortcut', 'pronunciation' => '/ˈʃɔːrt.kʌt/', 'meaning_ar' => 'اختصار'],
        ]),
    ],
    801 => [
        'sentence_1' => 'This placement test will help us find your current English level so we can guide you to the right starting point.',
        'sentence_2' => 'Answer each question honestly to get the most accurate result.',
        'sentence_3' => 'Your level will determine where you begin your learning journey.',
        'passage_explanation' => 'اختبار تحديد المستوى يساعدنا نعرف مستواك الحالي في الإنجليزي عشان نوجهك لنقطة البداية المناسبة.',
        'sentence_explanation' => 'مستواك هيحدد من وين تبدأ رحلة التعلم.',
        'vocabulary_json' => json_encode([
            ['word' => 'placement', 'pronunciation' => '/ˈpleɪs.mənt/', 'meaning_ar' => 'تحديد / توظيف'],
            ['word' => 'test', 'pronunciation' => '/test/', 'meaning_ar' => 'اختبار'],
            ['word' => 'level', 'pronunciation' => '/ˈlev.əl/', 'meaning_ar' => 'مستوى'],
            ['word' => 'guide', 'pronunciation' => '/ɡaɪd/', 'meaning_ar' => 'يوجه'],
            ['word' => 'current', 'pronunciation' => '/ˈkʌr.ənt/', 'meaning_ar' => 'حالي'],
            ['word' => 'honest', 'pronunciation' => '/ˈɒn.ɪst/', 'meaning_ar' => 'صادق'],
            ['word' => 'accurate', 'pronunciation' => '/ˈæk.jə.rət/', 'meaning_ar' => 'دقيق'],
            ['word' => 'result', 'pronunciation' => '/rɪˈzʌlt/', 'meaning_ar' => 'نتيجة'],
            ['word' => 'determine', 'pronunciation' => '/dɪˈtɜːr.mɪn/', 'meaning_ar' => 'يحدد'],
            ['word' => 'journey', 'pronunciation' => '/ˈdʒɜːr.ni/', 'meaning_ar' => 'رحلة'],
        ]),
    ],
    802 => [
        'sentence_1' => 'The English alphabet starts with six letters: A, B, C, D, E, and F. Apple begins with A. Ball begins with B. Cat begins with C. Dog begins with D. Egg begins with E. Fish begins with F.',
        'sentence_2' => 'Can you say the first six letters of the alphabet?',
        'sentence_3' => 'Every English word is made from the twenty-six letters of the alphabet.',
        'passage_explanation' => 'الأبجدية الإنجليزية تبدأ بستة حروف: A, B, C, D, E, F. كل حرف جبنا مثال عليه بكلمة تبدأ به.',
        'sentence_explanation' => 'كل كلمة إنجليزية مكونة من الـ 26 حرف بتاعة الأبجدية.',
        'vocabulary_json' => json_encode([
            ['word' => 'alphabet', 'pronunciation' => '/ˈæl.fə.bet/', 'meaning_ar' => 'الأبجدية'],
            ['word' => 'letter', 'pronunciation' => '/ˈlet.ər/', 'meaning_ar' => 'حرف'],
            ['word' => 'apple', 'pronunciation' => '/ˈæp.əl/', 'meaning_ar' => 'تفاحة'],
            ['word' => 'ball', 'pronunciation' => '/bɔːl/', 'meaning_ar' => 'كرة'],
            ['word' => 'cat', 'pronunciation' => '/kæt/', 'meaning_ar' => 'قطة'],
            ['word' => 'dog', 'pronunciation' => '/dɒɡ/', 'meaning_ar' => 'كلب'],
            ['word' => 'egg', 'pronunciation' => '/eɡ/', 'meaning_ar' => 'بيضة'],
            ['word' => 'fish', 'pronunciation' => '/fɪʃ/', 'meaning_ar' => 'سمكة'],
            ['word' => 'begin', 'pronunciation' => '/bɪˈɡɪn/', 'meaning_ar' => 'يبدأ'],
            ['word' => 'first', 'pronunciation' => '/fɜːrst/', 'meaning_ar' => 'أول'],
        ]),
    ],
    803 => [
        'sentence_1' => 'The next six letters are G, H, I, J, K, and L. Girl begins with G. Hat begins with H. Ice begins with I. Juice begins with J. King begins with K. Lion begins with L.',
        'sentence_2' => 'Say each letter slowly and clearly: G, H, I, J, K, L.',
        'sentence_3' => 'Repeat the letters until you feel comfortable saying them.',
        'passage_explanation' => 'الحروف الستة التالية هي G, H, I, J, K, L. كل حرف معاه كلمة تبدأ به عشان تحفظه.',
        'sentence_explanation' => 'كرر الحروف لحد ما تحس إنك مرتاح في نطقها.',
        'vocabulary_json' => json_encode([
            ['word' => 'girl', 'pronunciation' => '/ɡɜːrl/', 'meaning_ar' => 'بنت'],
            ['word' => 'hat', 'pronunciation' => '/hæt/', 'meaning_ar' => 'قبعة'],
            ['word' => 'ice', 'pronunciation' => '/aɪs/', 'meaning_ar' => 'ثلج'],
            ['word' => 'juice', 'pronunciation' => '/dʒuːs/', 'meaning_ar' => 'عصير'],
            ['word' => 'king', 'pronunciation' => '/kɪŋ/', 'meaning_ar' => 'ملك'],
            ['word' => 'lion', 'pronunciation' => '/ˈlaɪ.ən/', 'meaning_ar' => 'أسد'],
            ['word' => 'slowly', 'pronunciation' => '/ˈsloʊ.li/', 'meaning_ar' => 'ببطء'],
            ['word' => 'clearly', 'pronunciation' => '/ˈklɪr.li/', 'meaning_ar' => 'بوضوح'],
            ['word' => 'repeat', 'pronunciation' => '/rɪˈpiːt/', 'meaning_ar' => 'يكرر'],
            ['word' => 'comfortable', 'pronunciation' => '/ˈkʌm.fər.tə.bəl/', 'meaning_ar' => 'مرتاح'],
        ]),
    ],
    804 => [
        'sentence_1' => 'Now we learn M, N, O, P, Q, and R. Moon starts with M. Nose starts with N. Orange starts with O. Pen starts with P. Queen starts with Q. Rain starts with R.',
        'sentence_2' => 'Practice writing each letter as you say it out loud.',
        'sentence_3' => 'The more you practice, the better your pronunciation becomes.',
        'passage_explanation' => 'الحروف من M لـ R — كل حرف معاه كلمة مشهورة تبدأ به عشان تربط الحرف بالصوت.',
        'sentence_explanation' => 'كل ما تمرنت أكثر، نطقك يتحسن أكثر.',
        'vocabulary_json' => json_encode([
            ['word' => 'moon', 'pronunciation' => '/muːn/', 'meaning_ar' => 'قمر'],
            ['word' => 'nose', 'pronunciation' => '/noʊz/', 'meaning_ar' => 'أنف'],
            ['word' => 'orange', 'pronunciation' => '/ˈɒr.ɪndʒ/', 'meaning_ar' => 'برتقال'],
            ['word' => 'pen', 'pronunciation' => '/pen/', 'meaning_ar' => 'قلم'],
            ['word' => 'queen', 'pronunciation' => '/kwiːn/', 'meaning_ar' => 'ملكة'],
            ['word' => 'rain', 'pronunciation' => '/reɪn/', 'meaning_ar' => 'مطر'],
            ['word' => 'writing', 'pronunciation' => '/ˈraɪ.tɪŋ/', 'meaning_ar' => 'كتابة'],
            ['word' => 'loud', 'pronunciation' => '/laʊd/', 'meaning_ar' => 'بصوت عالي'],
            ['word' => 'better', 'pronunciation' => '/ˈbet.ər/', 'meaning_ar' => 'أفضل'],
            ['word' => 'become', 'pronunciation' => '/bɪˈkʌm/', 'meaning_ar' => 'يصبح'],
        ]),
    ],
    805 => [
        'sentence_1' => 'The last eight letters are S, T, U, V, W, X, Y, and Z. Sun starts with S. Tree starts with T. Umbrella starts with U. Van starts with V. Window starts with W.',
        'sentence_2' => 'Now you know all twenty-six letters of the English alphabet.',
        'sentence_3' => 'Congratulations on learning the complete alphabet!',
        'passage_explanation' => 'آخر 8 حروف من S لـ Z — بكده تعرف كل حروف الأبجدية الإنجليزية!',
        'sentence_explanation' => 'مبروك إنك تعلمت كل حروف الأبجدية!',
        'vocabulary_json' => json_encode([
            ['word' => 'sun', 'pronunciation' => '/sʌn/', 'meaning_ar' => 'شمس'],
            ['word' => 'tree', 'pronunciation' => '/triː/', 'meaning_ar' => 'شجرة'],
            ['word' => 'umbrella', 'pronunciation' => '/ʌmˈbrel.ə/', 'meaning_ar' => 'مظلة'],
            ['word' => 'van', 'pronunciation' => '/væn/', 'meaning_ar' => 'شاحنة صغيرة'],
            ['word' => 'window', 'pronunciation' => '/ˈwɪn.doʊ/', 'meaning_ar' => 'نافذة'],
            ['word' => 'last', 'pronunciation' => '/læst/', 'meaning_ar' => 'آخر'],
            ['word' => 'complete', 'pronunciation' => '/kəmˈpliːt/', 'meaning_ar' => 'كامل'],
            ['word' => 'congratulations', 'pronunciation' => '/kənˌɡrætʃ.uˈleɪ.ʃənz/', 'meaning_ar' => 'مبروك / تهانينا'],
            ['word' => 'eight', 'pronunciation' => '/eɪt/', 'meaning_ar' => 'ثمانية'],
            ['word' => 'twenty', 'pronunciation' => '/ˈtwen.ti/', 'meaning_ar' => 'عشرون'],
        ]),
    ],
    806 => [
        'sentence_1' => 'Let us practice the alphabet together. Say each letter from A to Z clearly and at a steady pace.',
        'sentence_2' => 'A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z.',
        'sentence_3' => 'Great job! You can now recite the entire alphabet perfectly.',
        'passage_explanation' => 'التمرين ده مراجعة على كل الحروف — قولها بالترتيب بصوت واضح وسرعة ثابتة.',
        'sentence_explanation' => 'أحسنت! دلوقتي تقدر تقول كل حروف الأبجدية بشكل مثالي.',
        'vocabulary_json' => json_encode([
            ['word' => 'together', 'pronunciation' => '/təˈɡeð.ər/', 'meaning_ar' => 'مع بعض'],
            ['word' => 'steady', 'pronunciation' => '/ˈsted.i/', 'meaning_ar' => 'ثابت'],
            ['word' => 'pace', 'pronunciation' => '/peɪs/', 'meaning_ar' => 'سرعة / وتيرة'],
            ['word' => 'recite', 'pronunciation' => '/rɪˈsaɪt/', 'meaning_ar' => 'يسرد / يتلو'],
            ['word' => 'entire', 'pronunciation' => '/ɪnˈtaɪər/', 'meaning_ar' => 'كامل'],
            ['word' => 'perfectly', 'pronunciation' => '/ˈpɜːr.fɪkt.li/', 'meaning_ar' => 'بشكل مثالي'],
            ['word' => 'great', 'pronunciation' => '/ɡreɪt/', 'meaning_ar' => 'رائع'],
            ['word' => 'job', 'pronunciation' => '/dʒɒb/', 'meaning_ar' => 'عمل'],
            ['word' => 'say', 'pronunciation' => '/seɪ/', 'meaning_ar' => 'يقول'],
            ['word' => 'each', 'pronunciation' => '/iːtʃ/', 'meaning_ar' => 'كل واحد'],
        ]),
    ],
    807 => [
        'sentence_1' => 'Some English letters combine to make new sounds. Ph sounds like F in phone. Ch sounds like tch in church. Sh sounds like sh in ship. Th can be soft like in think or hard like in this.',
        'sentence_2' => 'Compound letters create unique sounds that do not exist in every language.',
        'sentence_3' => 'Practice these sounds daily to master English pronunciation.',
        'passage_explanation' => 'بعض الحروف لما تجتمع مع بعض تعمل أصوات جديدة: Ph = F، Ch = تش، Sh = ش، Th ممكن تكون ناعمة أو قوية.',
        'sentence_explanation' => 'تمرن على الأصوات دي يومياً عشان تتقن النطق الإنجليزي.',
        'vocabulary_json' => json_encode([
            ['word' => 'phone', 'pronunciation' => '/foʊn/', 'meaning_ar' => 'هاتف'],
            ['word' => 'church', 'pronunciation' => '/tʃɜːrtʃ/', 'meaning_ar' => 'كنيسة'],
            ['word' => 'ship', 'pronunciation' => '/ʃɪp/', 'meaning_ar' => 'سفينة'],
            ['word' => 'think', 'pronunciation' => '/θɪŋk/', 'meaning_ar' => 'يفكر'],
            ['word' => 'combine', 'pronunciation' => '/kəmˈbaɪn/', 'meaning_ar' => 'يدمج'],
            ['word' => 'unique', 'pronunciation' => '/juˈniːk/', 'meaning_ar' => 'فريد'],
            ['word' => 'exist', 'pronunciation' => '/ɪɡˈzɪst/', 'meaning_ar' => 'يوجد'],
            ['word' => 'master', 'pronunciation' => '/ˈmæs.tər/', 'meaning_ar' => 'يتقن'],
            ['word' => 'daily', 'pronunciation' => '/ˈdeɪ.li/', 'meaning_ar' => 'يومياً'],
            ['word' => 'soft', 'pronunciation' => '/sɒft/', 'meaning_ar' => 'ناعم'],
        ]),
    ],
    809 => [
        'sentence_1' => 'Vowels are the five special letters: A, E, I, O, and U. Every English word must have at least one vowel. Vowels can make short sounds or long sounds.',
        'sentence_2' => 'The vowels A, E, I, O, and U are the heart of every English word.',
        'sentence_3' => 'Without vowels, we cannot pronounce any word correctly.',
        'passage_explanation' => 'الحروف الصوتية خمسة: A, E, I, O, U — أي كلمة إنجليزية لازم يكون فيها حرف صوتي واحد على الأقل.',
        'sentence_explanation' => 'من غير الحروف الصوتية ما نقدر ننطق أي كلمة صح.',
        'vocabulary_json' => json_encode([
            ['word' => 'vowel', 'pronunciation' => '/vaʊəl/', 'meaning_ar' => 'حرف صوتي / علة'],
            ['word' => 'special', 'pronunciation' => '/ˈspeʃ.əl/', 'meaning_ar' => 'خاص / مميز'],
            ['word' => 'must', 'pronunciation' => '/mʌst/', 'meaning_ar' => 'يجب'],
            ['word' => 'short', 'pronunciation' => '/ʃɔːrt/', 'meaning_ar' => 'قصير'],
            ['word' => 'long', 'pronunciation' => '/lɒŋ/', 'meaning_ar' => 'طويل'],
            ['word' => 'heart', 'pronunciation' => '/hɑːrt/', 'meaning_ar' => 'قلب'],
            ['word' => 'without', 'pronunciation' => '/wɪˈðaʊt/', 'meaning_ar' => 'بدون'],
            ['word' => 'pronounce', 'pronunciation' => '/prəˈnaʊns/', 'meaning_ar' => 'ينطق'],
            ['word' => 'correctly', 'pronunciation' => '/kəˈrekt.li/', 'meaning_ar' => 'بشكل صحيح'],
            ['word' => 'every', 'pronunciation' => '/ˈev.ri/', 'meaning_ar' => 'كل'],
        ]),
    ],
    810 => [
        'sentence_1' => 'In the second case, when a word ends with a silent E, the vowel before it makes a long sound. For example, cake has a long A sound, and pine has a long I sound.',
        'sentence_2' => 'A silent E at the end of a word changes the vowel sound from short to long.',
        'sentence_3' => 'This rule is called the magic E rule.',
        'passage_explanation' => 'في الحالة الثانية، لما الكلمة تنتهي بحرف E ساكن، الحرف الصوتي اللي قبله يصير طويل. مثل cake و pine.',
        'sentence_explanation' => 'هذي القاعدة اسمها قاعدة الـ E السحري — الـ E الساكن يخلي الحرف الصوتي طويل.',
        'vocabulary_json' => json_encode([
            ['word' => 'silent', 'pronunciation' => '/ˈsaɪ.lənt/', 'meaning_ar' => 'ساكن / صامت'],
            ['word' => 'case', 'pronunciation' => '/keɪs/', 'meaning_ar' => 'حالة'],
            ['word' => 'cake', 'pronunciation' => '/keɪk/', 'meaning_ar' => 'كعكة'],
            ['word' => 'pine', 'pronunciation' => '/paɪn/', 'meaning_ar' => 'شجرة صنوبر'],
            ['word' => 'change', 'pronunciation' => '/tʃeɪndʒ/', 'meaning_ar' => 'يغير'],
            ['word' => 'rule', 'pronunciation' => '/ruːl/', 'meaning_ar' => 'قاعدة'],
            ['word' => 'magic', 'pronunciation' => '/ˈmædʒ.ɪk/', 'meaning_ar' => 'سحر'],
            ['word' => 'end', 'pronunciation' => '/end/', 'meaning_ar' => 'نهاية'],
            ['word' => 'before', 'pronunciation' => '/bɪˈfɔːr/', 'meaning_ar' => 'قبل'],
            ['word' => 'second', 'pronunciation' => '/ˈsek.ənd/', 'meaning_ar' => 'ثاني'],
        ]),
    ],
    811 => [
        'sentence_1' => 'In the third case, two vowels appear together. The first vowel makes its long sound and the second is silent. For example, in rain, A says its name and I is silent.',
        'sentence_2' => 'When two vowels go walking, the first one does the talking.',
        'sentence_3' => 'This pattern helps you read many English words correctly.',
        'passage_explanation' => 'في الحالة الثالثة، لما حرفين صوتيين يجون مع بعض — الأول ينطق طويل والثاني ساكن. مثل rain: الـ A ينطق والـ I ساكن.',
        'sentence_explanation' => 'هذا النمط يساعدك تقرأ كلمات إنجليزية كثيرة بشكل صحيح.',
        'vocabulary_json' => json_encode([
            ['word' => 'appear', 'pronunciation' => '/əˈpɪr/', 'meaning_ar' => 'يظهر'],
            ['word' => 'together', 'pronunciation' => '/təˈɡeð.ər/', 'meaning_ar' => 'مع بعض'],
            ['word' => 'name', 'pronunciation' => '/neɪm/', 'meaning_ar' => 'اسم'],
            ['word' => 'walking', 'pronunciation' => '/ˈwɔː.kɪŋ/', 'meaning_ar' => 'يمشي'],
            ['word' => 'talking', 'pronunciation' => '/ˈtɔː.kɪŋ/', 'meaning_ar' => 'يتكلم'],
            ['word' => 'pattern', 'pronunciation' => '/ˈpæt.ərn/', 'meaning_ar' => 'نمط'],
            ['word' => 'many', 'pronunciation' => '/ˈmen.i/', 'meaning_ar' => 'كثير'],
            ['word' => 'third', 'pronunciation' => '/θɜːrd/', 'meaning_ar' => 'ثالث'],
            ['word' => 'two', 'pronunciation' => '/tuː/', 'meaning_ar' => 'اثنان'],
            ['word' => 'rain', 'pronunciation' => '/reɪn/', 'meaning_ar' => 'مطر'],
        ]),
    ],
    812 => [
        'sentence_1' => 'The fourth case covers special vowel combinations and irregular spellings. Some words do not follow the normal rules. For example, the word said has an unexpected A sound.',
        'sentence_2' => 'English has many irregular words that you must memorize.',
        'sentence_3' => 'Do not worry, you will learn them with practice over time.',
        'passage_explanation' => 'الحالة الرابعة تغطي تركيبات خاصة وهجاءات غير منتظمة — بعض الكلمات ما تتبع القواعد العادية مثل said.',
        'sentence_explanation' => 'لا تقلق، بالتمرين والوقت هتتعلم الكلمات الشاذة.',
        'vocabulary_json' => json_encode([
            ['word' => 'cover', 'pronunciation' => '/ˈkʌv.ər/', 'meaning_ar' => 'يغطي'],
            ['word' => 'special', 'pronunciation' => '/ˈspeʃ.əl/', 'meaning_ar' => 'خاص'],
            ['word' => 'combination', 'pronunciation' => '/ˌkɒm.bɪˈneɪ.ʃən/', 'meaning_ar' => 'تركيبة'],
            ['word' => 'irregular', 'pronunciation' => '/ɪˈreɡ.jə.lər/', 'meaning_ar' => 'غير منتظم / شاذ'],
            ['word' => 'spelling', 'pronunciation' => '/ˈspel.ɪŋ/', 'meaning_ar' => 'هجاء / تهجئة'],
            ['word' => 'follow', 'pronunciation' => '/ˈfɒl.oʊ/', 'meaning_ar' => 'يتبع'],
            ['word' => 'normal', 'pronunciation' => '/ˈnɔːr.məl/', 'meaning_ar' => 'عادي / طبيعي'],
            ['word' => 'memorize', 'pronunciation' => '/ˈmem.ə.raɪz/', 'meaning_ar' => 'يحفظ'],
            ['word' => 'worry', 'pronunciation' => '/ˈwʌr.i/', 'meaning_ar' => 'يقلق'],
            ['word' => 'unexpected', 'pronunciation' => '/ˌʌn.ɪkˈspek.tɪd/', 'meaning_ar' => 'غير متوقع'],
        ]),
    ],
    813 => [
        'sentence_1' => 'Let us review all the vowel rules. Short vowels make quick sounds like in cat and dog. Long vowels say their own names like in cake and home.',
        'sentence_2' => 'Understanding vowels is the foundation of English pronunciation.',
        'sentence_3' => 'You are now ready to move on to the next lesson.',
        'passage_explanation' => 'مراجعة لقواعد الحروف الصوتية: الصوت القصير مثل cat و dog، والصوت الطويل مثل cake و home.',
        'sentence_explanation' => 'فهم الحروف الصوتية هو أساس النطق الإنجليزي — دلوقتي جاهز للدرس الجاي!',
        'vocabulary_json' => json_encode([
            ['word' => 'review', 'pronunciation' => '/rɪˈvjuː/', 'meaning_ar' => 'مراجعة'],
            ['word' => 'quick', 'pronunciation' => '/kwɪk/', 'meaning_ar' => 'سريع'],
            ['word' => 'own', 'pronunciation' => '/oʊn/', 'meaning_ar' => 'خاص / ملك'],
            ['word' => 'home', 'pronunciation' => '/hoʊm/', 'meaning_ar' => 'بيت'],
            ['word' => 'foundation', 'pronunciation' => '/faʊnˈdeɪ.ʃən/', 'meaning_ar' => 'أساس'],
            ['word' => 'understanding', 'pronunciation' => '/ˌʌn.dərˈstæn.dɪŋ/', 'meaning_ar' => 'فهم'],
            ['word' => 'ready', 'pronunciation' => '/ˈred.i/', 'meaning_ar' => 'جاهز'],
            ['word' => 'move', 'pronunciation' => '/muːv/', 'meaning_ar' => 'يتحرك'],
            ['word' => 'next', 'pronunciation' => '/nekst/', 'meaning_ar' => 'التالي'],
            ['word' => 'lesson', 'pronunciation' => '/ˈles.ən/', 'meaning_ar' => 'درس'],
        ]),
    ],
];

$created = 0;
$updated = 0;
$skipped = 0;

foreach ($data as $lessonId => $content) {
    $lesson = Lesson::find($lessonId);
    if (!$lesson) {
        echo "SKIP: Lesson ID {$lessonId} not found\n";
        $skipped++;
        continue;
    }

    // Enable pronunciation on lesson
    $lesson->update(['has_pronunciation_exercise' => true]);

    $exercise = PronunciationExercise::where('lesson_id', $lessonId)->first();
    $exerciseData = [
        'lesson_id' => $lessonId,
        'sentence_1' => $content['sentence_1'],
        'sentence_2' => $content['sentence_2'],
        'sentence_3' => $content['sentence_3'],
        'vocabulary_json' => $content['vocabulary_json'],
        'passage_explanation' => $content['passage_explanation'],
        'sentence_explanation' => $content['sentence_explanation'],
        'passing_score' => 60,
        'max_duration_seconds' => 30,
        'allow_retake' => true,
    ];

    if ($exercise) {
        $exercise->update($exerciseData);
        echo "UPDATED: Lesson {$lessonId} — {$lesson->title}\n";
        $updated++;
    } else {
        PronunciationExercise::create($exerciseData);
        echo "CREATED: Lesson {$lessonId} — {$lesson->title}\n";
        $created++;
    }
}

echo "\n=== BATCH 1 COMPLETE ===\n";
echo "Created: {$created} | Updated: {$updated} | Skipped: {$skipped}\n";
