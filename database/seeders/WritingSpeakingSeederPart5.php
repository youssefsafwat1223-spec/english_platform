<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart5 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'المضارع البسيط' => [
                'writing' => [
                    'prompt' => "Translate the sentences:\n1. 🇸🇦→🇬🇧 \"أنا أتكلم الإنجليزية كل يوم في العمل.\"\n2. 🇸🇦→🇬🇧 \"هي تشرب قهوة كل صباح قبل المدرسة.\"\n3. 🇸🇦→🇬🇧 \"هل يلعب هو التنس في الجمعة؟\"\n4. 🇸🇦→🇬🇧 \"الشمس تشرق من الشرق وتغرب في الغرب.\"\n5. 🇬🇧→🇸🇦 \"They do not work on public holidays.\"\n6. 🇬🇧→🇸🇦 \"My father reads the newspaper every morning.\"\n7. 🇬🇧→🇸🇦 \"Do you speak Arabic?\"\n8. 🇬🇧→🇸🇦 \"She always arrives early to class.\"",
                    'model_answer' => "1. I speak English every day at work.\n2. She drinks coffee every morning before school.\n3. Does he play tennis on Fridays?\n4. The sun rises in the east and sets in the west.\n5. هم لا يعملون في الأعياد الرسمية.\n6. والدي يقرأ الجريدة كل صباح.\n7. هل تتكلم العربية؟\n8. هي دائماً تصل مبكرة إلى الفصل.",
                    'instructions' => 'Translate each sentence using present simple tense.',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I speak English every day at work.',
                    'sentence_2' => 'She drinks coffee every morning before school.',
                    'sentence_3' => 'Does he play tennis on Fridays?',
                    'sentences_json' => [
                        'The sun rises in the east and sets in the west.',
                        'They do not work on public holidays.',
                        'My father reads the newspaper every morning.',
                        'Do you speak Arabic? Yes, I do.',
                        'She always arrives early to class.',
                        'Water boils at one hundred degrees Celsius.',
                        'I usually wake up at six o\'clock in the morning.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'speaks', 'pronunciation' => 'Add s for he/she/it', 'meaning_ar' => 'يتحدث'],
                        ['word' => 'drinks', 'pronunciation' => 'Add s for he/she/it', 'meaning_ar' => 'يشرب'],
                        ['word' => 'Does', 'pronunciation' => 'Question helper for he/she/it', 'meaning_ar' => 'هل (للمفرد)'],
                        ['word' => 'always', 'pronunciation' => 'AWL-wayz — 100% frequency', 'meaning_ar' => 'دائماً'],
                        ['word' => 'usually', 'pronunciation' => 'YOO-zhoo-uh-lee — ~80%', 'meaning_ar' => 'عادةً'],
                    ],
                ],
            ],
            'المضارع المستمر' => [
                'writing' => [
                    'prompt' => "Translate the sentences:\n1. 🇸🇦→🇬🇧 \"محمد قاعد يتكلم مع خالد الحين.\"\n2. 🇸🇦→🇬🇧 \"هي قاعدة تقرأ كتاب مثير للاهتمام الحين.\"\n3. 🇸🇦→🇬🇧 \"هل هم يجون للحفلة الليلة؟\"\n4. 🇸🇦→🇬🇧 \"هو مو آكل لأنه مو جوعان.\"\n5. 🇬🇧→🇸🇦 \"She is listening to music in her room.\"\n6. 🇬🇧→🇸🇦 \"We are planning a trip to Japan.\"\n7. 🇬🇧→🇸🇦 \"It is raining heavily outside right now.\"\n8. 🇬🇧→🇸🇦 \"Are you watching TV or studying?\"",
                    'model_answer' => "1. Mohammed is talking with Khalid now.\n2. She is reading a very interesting book right now.\n3. Are they coming to the party tonight?\n4. He is not eating because he is not hungry.\n5. هي قاعدة تسمع موسيقى في غرفتها.\n6. نحن قاعدين نخطط لرحلة إلى اليابان.\n7. الجو قاعد يمطر بشدة بالخارج الحين.\n8. هل أنت قاعد تشاهد التلفزيون أو تذاكر؟",
                    'instructions' => 'Translate using present continuous (am/is/are + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'Mohammed is talking with Khalid right now.',
                    'sentence_2' => 'She is reading a very interesting book at the moment.',
                    'sentence_3' => 'Are they coming to the party tonight?',
                    'sentences_json' => [
                        'He is not eating because he is not hungry.',
                        'She is listening to music in her room.',
                        'We are planning a trip to Japan next summer.',
                        'It is raining heavily outside right now.',
                        'Are you watching TV or studying for the exam?',
                        'I am getting better at speaking English every day.',
                        'Look! The children are playing in the garden.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'talking', 'pronunciation' => 'talk + ing', 'meaning_ar' => 'يتحدث الآن'],
                        ['word' => 'reading', 'pronunciation' => 'read + ing', 'meaning_ar' => 'يقرأ الآن'],
                        ['word' => 'listening', 'pronunciation' => 'listen + ing', 'meaning_ar' => 'يسمع الآن'],
                        ['word' => 'planning', 'pronunciation' => 'plan + n + ing (double n)', 'meaning_ar' => 'يخطط الآن'],
                        ['word' => 'raining', 'pronunciation' => 'rain + ing', 'meaning_ar' => 'تمطر الآن'],
                    ],
                ],
            ],
            'المضارع التام' => [
                'writing' => [
                    'prompt' => "Translate the sentences:\n1. 🇸🇦→🇬🇧 \"أنا أكلت فطوري بالفعل هالصبح.\"\n2. 🇸🇦→🇬🇧 \"هي زارت باريس مرتين في حياتها.\"\n3. 🇸🇦→🇬🇧 \"هل جربت أكل ياباني قبل كذا؟\"\n4. 🇸🇦→🇬🇧 \"هم لحد الحين ما خلصوا المشروع.\"\n5. 🇬🇧→🇸🇦 \"He has just arrived from London.\"\n6. 🇬🇧→🇸🇦 \"I have never been to Japan.\"\n7. 🇬🇧→🇸🇦 \"She has lived in this city for ten years.\"\n8. 🇬🇧→🇸🇦 \"Have you finished your homework yet?\"",
                    'model_answer' => "1. I have already eaten my breakfast this morning.\n2. She has visited Paris twice in her life.\n3. Have you ever tried Japanese food before?\n4. They have not finished the project yet.\n5. هو لتوه وصل من لندن.\n6. أنا ما رحت اليابان في حياتي.\n7. هي ساكنة في هذي المدينة من عشر سنين.\n8. هل خلصت واجبك؟",
                    'instructions' => 'Translate using present perfect (have/has + past participle).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I have already eaten my breakfast this morning.',
                    'sentence_2' => 'She has visited Paris twice in her life.',
                    'sentence_3' => 'Have you ever tried Japanese food before?',
                    'sentences_json' => [
                        'They have not finished the project yet.',
                        'He has just arrived from London.',
                        'I have never been to Japan in my life.',
                        'She has lived in this city for ten years.',
                        'Have you finished your homework yet?',
                        'We have known each other since primary school.',
                        'I have been to Egypt three times.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'already', 'pronunciation' => 'Before expected time', 'meaning_ar' => 'بالفعل'],
                        ['word' => 'yet', 'pronunciation' => 'Up to now (negative/question)', 'meaning_ar' => 'حتى الآن'],
                        ['word' => 'just', 'pronunciation' => 'Very recently', 'meaning_ar' => 'لتوه'],
                        ['word' => 'ever', 'pronunciation' => 'At any time in your life', 'meaning_ar' => 'أبداً (سؤال)'],
                        ['word' => 'never', 'pronunciation' => 'Not at any time', 'meaning_ar' => 'أبداً (نفي)'],
                        ['word' => 'for', 'pronunciation' => 'Duration of time', 'meaning_ar' => 'لمدة'],
                        ['word' => 'since', 'pronunciation' => 'Starting point in time', 'meaning_ar' => 'منذ'],
                    ],
                ],
            ],
            'المضارع التام المستمر' => [
                'writing' => [
                    'prompt' => "Translate the sentences:\n1. 🇸🇦→🇬🇧 \"أنا قاعد أذاكر الإنجليزية من ثلاث ساعات بدون استراحة.\"\n2. 🇸🇦→🇬🇧 \"هي قاعدة تركض كل صبح من يناير.\"\n3. 🇸🇦→🇬🇧 \"هل هو قاعد ينام طول العصر؟\"\n4. 🇸🇦→🇬🇧 \"هم ما عندهم وقت طويل مع بعض.\"\n5. 🇬🇧→🇸🇦 \"I have been waiting for you for an hour!\"\n6. 🇬🇧→🇸🇦 \"She has been feeling unwell since yesterday.\"\n7. 🇬🇧→🇸🇦 \"How long have you been learning English?\"\n8. 🇬🇧→🇸🇦 \"They have been arguing since this morning.\"",
                    'model_answer' => "1. I have been studying English for three hours without a break.\n2. She has been running every morning since January.\n3. Has he been sleeping all afternoon?\n4. They have not been working together for very long.\n5. أنا قاعد أنتظرك من ساعة!\n6. هي قاعدة تحس بتعب من أمس.\n7. من متى وأنت تتعلم الإنجليزية؟\n8. هم قاعدين يتشاجرون من الصبح.",
                    'instructions' => 'Translate using present perfect continuous (have/has been + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I have been studying English for three hours without a break.',
                    'sentence_2' => 'She has been running every morning since January.',
                    'sentence_3' => 'Has he been sleeping all afternoon?',
                    'sentences_json' => [
                        'They have not been working together for very long.',
                        'I have been waiting for you for an hour!',
                        'She has been feeling unwell since yesterday.',
                        'How long have you been learning English?',
                        'They have been arguing since this morning.',
                        'It has been raining all day. I am so tired of it.',
                        'He has been practising the piano for two years.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'have been studying', 'pronunciation' => 'have + been + verb-ing', 'meaning_ar' => 'أدرس منذ فترة'],
                        ['word' => 'has been running', 'pronunciation' => 'has + been + verb-ing', 'meaning_ar' => 'تركض منذ فترة'],
                        ['word' => 'for', 'pronunciation' => 'Duration (still ongoing)', 'meaning_ar' => 'لمدة'],
                        ['word' => 'since', 'pronunciation' => 'Start point (still ongoing)', 'meaning_ar' => 'منذ'],
                    ],
                ],
            ],
            'الماضي البسيط' => [
                'writing' => [
                    'prompt' => "Translate the sentences:\n1. 🇸🇦→🇬🇧 \"أنا أكلت برجر لذيذ على الغداء أمس.\"\n2. 🇸🇦→🇬🇧 \"هي كتبت رسالة طويلة لصاحبتها الأسبوع الماضي.\"\n3. 🇸🇦→🇬🇧 \"هل هو اتصل عليك بعد الاجتماع؟\"\n4. 🇸🇦→🇬🇧 \"هم ما ناموا زين بسبب الضجة.\"\n5. 🇬🇧→🇸🇦 \"I went to the market and bought some vegetables.\"\n6. 🇬🇧→🇸🇦 \"She didn't come to school because she was sick.\"\n7. 🇬🇧→🇸🇦 \"Where did you go last summer?\"\n8. 🇬🇧→🇸🇦 \"The match started at 8 and ended at 10.\"",
                    'model_answer' => "1. I ate a delicious burger for lunch yesterday.\n2. She wrote a long letter to her friend last week.\n3. Did he call you after the meeting?\n4. They did not sleep well because of the noise.\n5. أنا رحت السوق واشتريت بعض الخضار.\n6. هي ما جات المدرسة لأنها كانت مريضة.\n7. وين رحت الصيف اللي فات؟\n8. المباراة بدأت الساعة 8 وانتهت الساعة 10.",
                    'instructions' => 'Translate using past simple tense.',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I ate a delicious burger for lunch yesterday.',
                    'sentence_2' => 'She wrote a long letter to her friend last week.',
                    'sentence_3' => 'Did he call you after the meeting?',
                    'sentences_json' => [
                        'They did not sleep well because of the noise.',
                        'I went to the market and bought some vegetables.',
                        'She didn\'t come to school because she was sick.',
                        'Where did you go last summer? I went to Turkey.',
                        'The match started at eight and ended at ten.',
                        'We watched a very exciting movie last night.',
                        'He finished his homework and then went to bed.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'ate', 'pronunciation' => 'eat → ate (irregular)', 'meaning_ar' => 'أكل'],
                        ['word' => 'wrote', 'pronunciation' => 'write → wrote (irregular)', 'meaning_ar' => 'كتب'],
                        ['word' => 'went', 'pronunciation' => 'go → went (irregular)', 'meaning_ar' => 'ذهب'],
                        ['word' => 'bought', 'pronunciation' => 'buy → bought (irregular)', 'meaning_ar' => 'اشترى'],
                        ['word' => 'didn\'t', 'pronunciation' => 'did not — negative past', 'meaning_ar' => 'لم'],
                    ],
                ],
            ],
        ];

        $count = 0;
        foreach ($data as $searchKey => $exercises) {
            $level = CourseLevel::where('course_id', $courseId)->where('title', 'LIKE', "%{$searchKey}%")->first();
            if (!$level) { $this->command->warn("⚠ Not found: {$searchKey}"); continue; }
            $writingData = $exercises['writing'];
            
            // Parse for exact match short answers
            $questionsJson = null;
            $evalType = 'ai';
            
            if (str_contains($writingData['prompt'] ?? '', "\n1. ")) {
                $evalType = 'exact_match';
                $questionsJson = [];
                $promptLines = explode("\n", $writingData['prompt']);
                $answerLines = explode("\n", $writingData['model_answer'] ?? '');
                
                // Extract questions
                foreach ($promptLines as $line) {
                    if (preg_match('/^\d+\.\s+(.*)$/', trim($line), $matches)) {
                        $questionsJson[] = ['question' => $matches[1], 'answer' => ''];
                    }
                }
                
                // Extract answers
                $aIndex = 0;
                foreach ($answerLines as $line) {
                    if (preg_match('/^\d+\.\s+(.*)$/', trim($line), $matches)) {
                        if (isset($questionsJson[$aIndex])) {
                            $questionsJson[$aIndex]['answer'] = $matches[1];
                        }
                        $aIndex++;
                    }
                }
            }

            // Writing
            WritingExercise::updateOrCreate(
                ['course_level_id' => $level->id],
                [
                    'title' => $level->title . ' — Writing',
                    'prompt' => $writingData['prompt'] ?? '',
                    'instructions' => $writingData['instructions'] ?? '',
                    'model_answer' => $writingData['model_answer'] ?? '',
                    'min_words' => $writingData['min_words'] ?? 0,
                    'max_words' => $writingData['max_words'] ?? 0,
                    'passing_score' => 60,
                    'evaluation_type' => $evalType,
                    'questions_json' => $questionsJson,
                ]
            );
            PronunciationExercise::updateOrCreate(['course_level_id' => $level->id], [
                'sentence_1' => $exercises['speaking']['sentence_1'], 'sentence_2' => $exercises['speaking']['sentence_2'],
                'sentence_3' => $exercises['speaking']['sentence_3'], 'sentences_json' => $exercises['speaking']['sentences_json'] ?? null,
                'vocabulary_json' => $exercises['speaking']['vocabulary_json'],
                'passing_score' => 60, 'max_duration_seconds' => 120, 'allow_retake' => true,
            ]);
            $level->update(['has_writing_exercise' => true, 'has_speaking_exercise' => true]);
            $this->command->info("✅ {$level->title}"); $count++;
        }
        $this->command->info("🎉 Part 5 Done! {$count} levels.");
    }
}
