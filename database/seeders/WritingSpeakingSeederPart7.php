<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart7 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'المستقبل التام' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا راح أكون خلصت قراءة الكتاب بحلول مساء الجمعة.\"\n2. 🇸🇦→🇬🇧 \"هي راح تكون طلعت من المكتب قبل ما توصل أنت.\"\n3. 🇸🇦→🇬🇧 \"هل هو راح يكون أكل عشاءه بحلول ما نوصل؟\"\n4. 🇸🇦→🇬🇧 \"هم ما راح يكونوا خلصوا المشروع بحلول صبح الاثنين.\"\n5. 🇬🇧→🇸🇦 \"By next year, she will have graduated from university.\"\n6. 🇬🇧→🇸🇦 \"Will you have finished the report by 5 p.m.?\"\n7. 🇬🇧→🇸🇦 \"I will have saved enough money to buy a car by then.\"\n8. 🇬🇧→🇸🇦 \"By the time you wake up, I will have already left.\"",
                    'model_answer' => "1. I will have finished reading this book by Friday evening.\n2. She will have left the office before you arrive.\n3. Will he have eaten dinner by the time we get there?\n4. They will not have completed the project by Monday morning.\n5. بحلول السنة الجاية هي راح تكون تخرجت من الجامعة.\n6. هل راح تكون خلصت التقرير بحلول الساعة 5 مساءً؟\n7. أنا راح أكون وفرت فلوس كافية لأشتري سيارة.\n8. بحلول ما تصحى أنا راح أكون طلعت بالفعل.",
                    'instructions' => 'Translate using future perfect (will have + past participle).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I will have finished reading this book by Friday evening.',
                    'sentence_2' => 'She will have left the office before you even arrive.',
                    'sentence_3' => 'Will he have eaten dinner by the time we get there?',
                    'sentences_json' => [
                        'They will not have completed the project by Monday morning.',
                        'By next year, she will have graduated from university.',
                        'Will you have finished the report by five p.m.?',
                        'I will have saved enough money to buy a car by then.',
                        'By the time you wake up, I will have already left.',
                        'By December, we will have been married for twenty years.',
                        'He will have learned five hundred new words by the end of this course.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'will have finished', 'pronunciation' => 'will + have + past participle', 'meaning_ar' => 'سيكون قد أنهى'],
                        ['word' => 'by', 'pronunciation' => 'Not later than a certain time', 'meaning_ar' => 'بحلول'],
                    ],
                ],
            ],
            'المستقبل التام المستمر' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا راح أكون أركض من ساعة بحلول ما توصل للحديقة.\"\n2. 🇸🇦→🇬🇧 \"هي راح تكون تدرّس في هالمدرسة من 20 سنة بحلول 2030.\"\n3. 🇸🇦→🇬🇧 \"هل هو راح يكون يقود وقت طويل بحلول ما يوصل المدينة؟\"\n4. 🇸🇦→🇬🇧 \"هم ما راح يكونوا ينتظرون وقت طويل بحلول ما نوصل.\"\n5. 🇬🇧→🇸🇦 \"By next month, I will have been learning English for two years.\"\n6. 🇬🇧→🇸🇦 \"Will you have been working here for long by retirement?\"\n7. 🇬🇧→🇸🇦 \"By graduation day, they will have been studying for four years.\"\n8. 🇬🇧→🇸🇦 \"She will have been waiting for three hours by the time he arrives.\"",
                    'model_answer' => "1. I will have been running for an hour by the time you arrive at the park.\n2. She will have been teaching at this school for twenty years by 2030.\n3. Will he have been driving for long by the time he reaches the city?\n4. They will not have been waiting long by the time we get there.\n5. بحلول الشهر الجاي أنا راح أكون أتعلم الإنجليزية من سنتين.\n6. هل راح تكون تشتغل هنا وقت طويل بحلول ما تتقاعد؟\n7. بحلول يوم التخرج هم راح يكونوا يذاكرون من أربع سنين.\n8. هي راح تكون تنتظر من ثلاث ساعات بحلول ما هو يوصل.",
                    'instructions' => 'Translate using future perfect continuous (will have been + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'By next year, I will have been living here for ten years.',
                    'sentence_2' => 'She will have been teaching for twenty years by twenty thirty.',
                    'sentence_3' => 'Will you have been waiting long by the time the bus arrives?',
                    'sentences_json' => [
                        'I will have been running for an hour by the time you arrive.',
                        'They will not have been waiting long by the time we get there.',
                        'By next month, I will have been learning English for two years.',
                        'By graduation day, they will have been studying for four years.',
                        'She will have been waiting for three hours by the time he arrives.',
                        'By retirement, she will have been working for thirty-five years.',
                        'How long will you have been living abroad by next summer?',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'will have been working', 'pronunciation' => 'will + have + been + verb-ing', 'meaning_ar' => 'سيكون قد ظل يعمل'],
                    ],
                ],
            ],
            'صيغة الأمر' => [
                'writing' => [
                    'prompt' => "Write commands:\n1. Ask someone to open the door\n2. Negative: don't run\n3. Tell someone to sit down\n4. Tell someone to be quiet\n5. Tell someone to wash their hands\n6. Tell someone to call you later\n7. No talking during exam (negative)\n8. Ask someone to wait (polite)\n9. Write a command using \"let's\"\n10. Write a command using \"never\"",
                    'model_answer' => "1. Open the door, please.\n2. Don't run in the hallway!\n3. Sit down, please.\n4. Be quiet!\n5. Wash your hands before eating.\n6. Call me later.\n7. Don't talk during the exam!\n8. Please wait here for a moment.\n9. Let's go for a walk!\n10. Never give up on your dreams!",
                    'instructions' => 'Write imperative sentences (commands).',
                    'min_words' => 20, 'max_words' => 120,
                ],
                'speaking' => [
                    'sentence_1' => 'Open the door, please. Close the window.',
                    'sentence_2' => 'Don\'t run in the hallway! Don\'t touch that!',
                    'sentence_3' => 'Please sit down. Be quiet everyone.',
                    'sentences_json' => [
                        'Wash your hands before eating. It is very important.',
                        'Call me later when you are free.',
                        'Don\'t talk during the exam. Keep your eyes on your paper.',
                        'Please wait here for a moment. I\'ll be right back.',
                        'Let\'s go for a walk in the park!',
                        'Never give up on your dreams. Keep trying!',
                        'Be careful with that glass. Don\'t drop it!',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Don\'t', 'pronunciation' => 'Negative imperative', 'meaning_ar' => 'لا (أمر سلبي)'],
                        ['word' => 'Let\'s', 'pronunciation' => 'Suggestion for group', 'meaning_ar' => 'هيا (اقتراح)'],
                        ['word' => 'Never', 'pronunciation' => 'Strong negative command', 'meaning_ar' => 'أبداً (أمر)'],
                        ['word' => 'Please', 'pronunciation' => 'Polite softener', 'meaning_ar' => 'من فضلك'],
                    ],
                ],
            ],
            'الأفعال الناقصة' => [
                'writing' => [
                    'prompt' => "Write sentences:\n1. Using \"must\" for obligation\n2. Using \"should\" for advice\n3. Using \"can\" for ability\n4. Using \"could\" for past ability\n5. Using \"had better\" for warning\n6. Using \"must not\" for prohibition\n7. Using \"should not\" for advice against\n8. Using \"could\" for polite request\n9. Fill in: \"She ___ drive — she's only 14.\"\n10. Fill in: \"You ___ better study — the exam is tomorrow.\"",
                    'model_answer' => "1. You must wear a seatbelt in the car.\n2. You should drink more water.\n3. She can speak three languages.\n4. He could swim when he was five.\n5. You had better leave now or you'll be late.\n6. You must not smoke in this building.\n7. You should not eat too much sugar.\n8. Could you help me with this, please?\n9. can't / cannot\n10. had",
                    'instructions' => 'Write sentences using modal verbs correctly.',
                    'min_words' => 20, 'max_words' => 150,
                ],
                'speaking' => [
                    'sentence_1' => 'You must wear a seatbelt in the car. It is the law.',
                    'sentence_2' => 'You should drink at least eight glasses of water every day.',
                    'sentence_3' => 'She can speak three languages: Arabic, English, and French.',
                    'sentences_json' => [
                        'He could swim very well when he was only five years old.',
                        'You had better leave now or you will definitely be late.',
                        'You must not smoke inside this building. It is forbidden.',
                        'You should not eat too much sugar. It is bad for your health.',
                        'Could you please open the window for me? It is very hot.',
                        'She can\'t drive yet because she is only fourteen years old.',
                        'You had better study hard because the exam is tomorrow.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'must', 'pronunciation' => 'Strong obligation / necessity', 'meaning_ar' => 'يجب'],
                        ['word' => 'should', 'pronunciation' => 'Advice / recommendation', 'meaning_ar' => 'ينبغي'],
                        ['word' => 'can', 'pronunciation' => 'Present ability', 'meaning_ar' => 'يستطيع'],
                        ['word' => 'could', 'pronunciation' => 'Past ability / polite request', 'meaning_ar' => 'استطاع / ممكن'],
                        ['word' => 'had better', 'pronunciation' => 'Urgent warning', 'meaning_ar' => 'من الأفضل'],
                        ['word' => 'must not', 'pronunciation' => 'Prohibition — forbidden', 'meaning_ar' => 'يُمنع'],
                    ],
                ],
            ],
            'أدوات الاستفهام' => [
                'writing' => [
                    'prompt' => "Write questions:\n1. Using \"what\" about someone's job\n2. Using \"where\" about hometown\n3. Using \"when\" about a meeting\n4. Using \"who\" about a friend\n5. Using \"why\" about being late\n6. Using \"how\" about health\n7. Using \"which\" about a choice\n8. Using \"whom\" formally\n9. Using \"how long\"\n10. Using \"how many\"",
                    'model_answer' => "1. What do you do for a living?\n2. Where are you from?\n3. When does the meeting start?\n4. Who is your best friend?\n5. Why are you always late?\n6. How are you feeling today?\n7. Which colour do you prefer?\n8. Whom did you speak to?\n9. How long have you been learning English?\n10. How many languages can you speak?",
                    'instructions' => 'Write questions using the correct WH-word.',
                    'min_words' => 20, 'max_words' => 120,
                ],
                'speaking' => [
                    'sentence_1' => 'What do you do for a living? I am a teacher.',
                    'sentence_2' => 'Where are you from? I am from Riyadh, Saudi Arabia.',
                    'sentence_3' => 'When does the meeting start? It starts at ten o\'clock.',
                    'sentences_json' => [
                        'Who is your best friend? My best friend is Ahmed.',
                        'Why are you always late? I usually miss the bus.',
                        'How are you feeling today? I am feeling much better, thank you.',
                        'Which colour do you prefer, red or blue?',
                        'Whom did you speak to at the front desk?',
                        'How long have you been learning English? For about two years.',
                        'How many languages can you speak? I can speak three.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'What', 'pronunciation' => 'Thing or information', 'meaning_ar' => 'ماذا'],
                        ['word' => 'Where', 'pronunciation' => 'Place or location', 'meaning_ar' => 'أين'],
                        ['word' => 'When', 'pronunciation' => 'Time', 'meaning_ar' => 'متى'],
                        ['word' => 'Who', 'pronunciation' => 'Person (subject)', 'meaning_ar' => 'من'],
                        ['word' => 'Why', 'pronunciation' => 'Reason', 'meaning_ar' => 'لماذا'],
                        ['word' => 'How', 'pronunciation' => 'Manner or condition', 'meaning_ar' => 'كيف'],
                        ['word' => 'Which', 'pronunciation' => 'Choice between options', 'meaning_ar' => 'أيّ'],
                    ],
                ],
            ],
            'المقارنة' => [
                'writing' => [
                    'prompt' => "Write comparisons:\n1. Comparative of \"tall\"\n2. Superlative of \"tall\"\n3. Comparative of \"interesting\"\n4. Superlative of \"interesting\"\n5. Same level: \"She is ___ smart ___ her brother.\"\n6. Compare two cities using \"bigger\"\n7. Superlative of \"good\"\n8. Superlative of \"bad\"\n9. \"Today is ___ day of the year.\" (hot)\n10. \"This car is ___ expensive ___ that one.\" (equal)",
                    'model_answer' => "1. taller than\n2. the tallest\n3. more interesting than\n4. the most interesting\n5. as smart as\n6. Riyadh is bigger than Abha.\n7. the best\n8. the worst\n9. the hottest\n10. as expensive as",
                    'instructions' => 'Write the correct comparative and superlative forms.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'tall, taller, the tallest. He is taller than his brother.',
                    'sentence_2' => 'interesting, more interesting, the most interesting.',
                    'sentence_3' => 'good, better, the best. bad, worse, the worst.',
                    'sentences_json' => [
                        'She is the best student in the whole class.',
                        'That was the worst film I have ever seen in my life.',
                        'Riyadh is bigger than Abha. Jeddah is the biggest city.',
                        'She is as smart as her brother. They both got full marks.',
                        'This book is more interesting than that one.',
                        'Today is the hottest day of the entire year.',
                        'Mount Everest is the highest mountain in the world.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'taller', 'pronunciation' => 'One syllable: add -er', 'meaning_ar' => 'أطول'],
                        ['word' => 'more interesting', 'pronunciation' => 'Long words: use more', 'meaning_ar' => 'أكثر إثارة'],
                        ['word' => 'best', 'pronunciation' => 'Irregular: good → best', 'meaning_ar' => 'الأفضل'],
                        ['word' => 'worst', 'pronunciation' => 'Irregular: bad → worst', 'meaning_ar' => 'الأسوأ'],
                        ['word' => 'as...as', 'pronunciation' => 'Equal comparison', 'meaning_ar' => 'بنفس القدر'],
                    ],
                ],
            ],
        ];

        $count = 0;
        foreach ($data as $searchKey => $exercises) {
            $level = CourseLevel::where('course_id', $courseId)->where('title', 'LIKE', "%{$searchKey}%")->first();
            if (!$level) { $this->command->warn("⚠ Not found: {$searchKey}"); continue; }
            WritingExercise::updateOrCreate(['course_level_id' => $level->id], [
                'title' => $level->title . ' — Writing', 'prompt' => $exercises['writing']['prompt'],
                'instructions' => $exercises['writing']['instructions'], 'model_answer' => $exercises['writing']['model_answer'],
                'min_words' => $exercises['writing']['min_words'], 'max_words' => $exercises['writing']['max_words'], 'passing_score' => 60,
            ]);
            PronunciationExercise::updateOrCreate(['course_level_id' => $level->id], [
                'sentence_1' => $exercises['speaking']['sentence_1'], 'sentence_2' => $exercises['speaking']['sentence_2'],
                'sentence_3' => $exercises['speaking']['sentence_3'], 'sentences_json' => $exercises['speaking']['sentences_json'] ?? null,
                'vocabulary_json' => $exercises['speaking']['vocabulary_json'],
                'passing_score' => 60, 'max_duration_seconds' => 120, 'allow_retake' => true,
            ]);
            $level->update(['has_writing_exercise' => true, 'has_speaking_exercise' => true]);
            $this->command->info("✅ {$level->title}"); $count++;
        }
        $this->command->info("🎉 Part 7 Done! {$count} levels.");
    }
}
