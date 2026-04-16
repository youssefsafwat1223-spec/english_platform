<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart6 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'الماضي المستمر' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"هي كانت تطبخ العشاء لما الهاتف رن.\"\n2. 🇸🇦→🇬🇧 \"هم كانوا يذاكرون طول المساء.\"\n3. 🇸🇦→🇬🇧 \"هل أنت كنت تشاهد التلفزيون لما دقيت الباب؟\"\n4. 🇸🇦→🇬🇧 \"هو ما كان يسوق بسرعة لما الحادث صار.\"\n5. 🇬🇧→🇸🇦 \"I was reading a book when the lights went out.\"\n6. 🇬🇧→🇸🇦 \"What were you doing at 9 p.m. last night?\"\n7. 🇬🇧→🇸🇦 \"She was not listening when the teacher explained.\"\n8. 🇬🇧→🇸🇦 \"While I was sleeping, my phone rang three times.\"",
                    'model_answer' => "1. She was cooking dinner when the phone rang.\n2. They were studying together all evening.\n3. Were you watching TV when I knocked on the door?\n4. He was not driving fast when the accident happened.\n5. أنا كنت أقرأ كتاب لما الكهرباء انقطعت.\n6. وش كنت تسوي الساعة 9 مساء أمس؟\n7. هي ما كانت تسمع لما المعلم شرح.\n8. وأنا نايم رن هاتفي ثلاث مرات.",
                    'instructions' => 'Translate using past continuous (was/were + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'She was cooking dinner when the phone suddenly rang.',
                    'sentence_2' => 'They were studying together all evening in the library.',
                    'sentence_3' => 'Were you watching TV when I knocked on the door?',
                    'sentences_json' => [
                        'He was not driving fast when the accident happened.',
                        'I was reading a book when the lights went out.',
                        'What were you doing at nine p.m. last night?',
                        'She was not listening when the teacher explained the lesson.',
                        'While I was sleeping, my phone rang three times.',
                        'It was raining heavily when we left the building.',
                        'The kids were playing outside while their parents were cooking.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'was cooking', 'pronunciation' => 'was/were + verb-ing', 'meaning_ar' => 'كانت تطبخ'],
                        ['word' => 'while', 'pronunciation' => 'Two actions at same time', 'meaning_ar' => 'بينما'],
                        ['word' => 'when', 'pronunciation' => 'One action interrupts another', 'meaning_ar' => 'عندما'],
                    ],
                ],
            ],
            'الماضي التام' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا خلصت شغلي كله لما هي وصلت.\"\n2. 🇸🇦→🇬🇧 \"هي ما شافت ثلج قبل ما تنتقل لكندا.\"\n3. 🇸🇦→🇬🇧 \"هل هو طلع من المكتب قبل ما تجي؟\"\n4. 🇸🇦→🇬🇧 \"هم ما أكلوا شي لما وصلنا للمطعم.\"\n5. 🇬🇧→🇸🇦 \"By the time the ambulance arrived, he had already recovered.\"\n6. 🇬🇧→🇸🇦 \"She had studied French before she moved to Paris.\"\n7. 🇬🇧→🇸🇦 \"I hadn't heard that news until you told me.\"\n8. 🇬🇧→🇸🇦 \"Had you eaten before the meeting started?\"",
                    'model_answer' => "1. I had finished all my work by the time she arrived.\n2. She had never seen snow before she moved to Canada.\n3. Had he left the office before you got there?\n4. They had not eaten anything when we arrived at the restaurant.\n5. لما الإسعاف وصل، هو كان تعافى بالفعل.\n6. هي كانت تعلمت الفرنسي قبل ما تنتقل لباريس.\n7. أنا ما سمعت هالخبر إلا لما أخبرتني أنت.\n8. هل أكلت قبل ما يبدأ الاجتماع؟",
                    'instructions' => 'Translate using past perfect (had + past participle).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I had finished all my work by the time she arrived.',
                    'sentence_2' => 'She had never seen snow before she moved to Canada.',
                    'sentence_3' => 'Had he left the office before you got there?',
                    'sentences_json' => [
                        'They had not eaten anything when we arrived at the restaurant.',
                        'By the time the ambulance arrived, he had already recovered.',
                        'She had studied French before she moved to Paris.',
                        'I hadn\'t heard that news until you told me.',
                        'Had you eaten before the meeting started?',
                        'I realised I had forgotten my keys at home.',
                        'By noon, he had already read three chapters of the book.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'had finished', 'pronunciation' => 'had + past participle', 'meaning_ar' => 'كان قد أنهى'],
                        ['word' => 'by the time', 'pronunciation' => 'Before that moment', 'meaning_ar' => 'بحلول'],
                        ['word' => 'before', 'pronunciation' => 'Earlier than', 'meaning_ar' => 'قبل'],
                        ['word' => 'already', 'pronunciation' => 'Before expected time', 'meaning_ar' => 'بالفعل'],
                    ],
                ],
            ],
            'الماضي التام المستمر' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا كنت أركض من ساعة قبل ما تبدأ الأمطار.\"\n2. 🇸🇦→🇬🇧 \"هي كانت تشتغل في تلك الشركة من عشر سنين قبل ما تستقيل.\"\n3. 🇸🇦→🇬🇧 \"هل كانوا يتشاجرون وقت طويل قبل ما توصل؟\"\n4. 🇸🇦→🇬🇧 \"هو ما كان ينام زين من أسابيع قبل ما يروح للدكتور.\"\n5. 🇬🇧→🇸🇦 \"She had been crying for hours before she calmed down.\"\n6. 🇬🇧→🇸🇦 \"How long had you been waiting before the bus arrived?\"\n7. 🇬🇧→🇸🇦 \"They had been planning the trip for months before it was cancelled.\"\n8. 🇬🇧→🇸🇦 \"I was exhausted because I had been working all night.\"",
                    'model_answer' => "1. I had been running for an hour before it started to rain.\n2. She had been working at that company for ten years before she quit.\n3. Had they been arguing for a long time before you arrived?\n4. He had not been sleeping well for weeks before seeing the doctor.\n5. هي كانت تبكي من ساعات قبل ما تهدى.\n6. من متى كنت تنتظر قبل ما الباص يجي؟\n7. هم كانوا يخططون للرحلة من شهور قبل ما تتلغى.\n8. أنا كنت تعبان لأني كنت أشتغل طول الليل.",
                    'instructions' => 'Translate using past perfect continuous (had been + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I had been running for an hour before it started to rain.',
                    'sentence_2' => 'She had been working at that company for ten years before she quit.',
                    'sentence_3' => 'Had they been arguing for a long time before you arrived?',
                    'sentences_json' => [
                        'He had not been sleeping well for weeks before seeing the doctor.',
                        'She had been crying for hours before she finally calmed down.',
                        'How long had you been waiting before the bus arrived?',
                        'They had been planning the trip for months before it was cancelled.',
                        'I was exhausted because I had been working all night long.',
                        'Her eyes were red because she had been reading without glasses.',
                        'The ground was wet because it had been raining all morning.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'had been running', 'pronunciation' => 'had + been + verb-ing', 'meaning_ar' => 'كان يركض منذ فترة'],
                        ['word' => 'exhausted', 'pronunciation' => 'ig-ZAWS-tid', 'meaning_ar' => 'منهك'],
                    ],
                ],
            ],
            'المستقبل البسيط' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا راح أتصل عليك أول ما أوصل البيت.\"\n2. 🇸🇦→🇬🇧 \"هي ما راح تجي الشغل بكرة لأنها مريضة.\"\n3. 🇸🇦→🇬🇧 \"هل هو راح يخلص التقرير في وقته؟\"\n4. 🇸🇦→🇬🇧 \"هم راح يسافرون لباريس الشهر الجاي.\"\n5. 🇬🇧→🇸🇦 \"Don't worry — everything will be fine.\"\n6. 🇬🇧→🇸🇦 \"I think it will rain this evening.\"\n7. 🇬🇧→🇸🇦 \"Will you help me with this project?\"\n8. 🇬🇧→🇸🇦 \"She will probably arrive late.\"",
                    'model_answer' => "1. I will call you as soon as I get home.\n2. She will not come to work tomorrow because she is sick.\n3. Will he finish the report on time?\n4. They will travel to Paris next month.\n5. لا تقلق — كل شي راح يكون تمام.\n6. أحسب راح تمطر المساء.\n7. هل راح تساعدني في هذا المشروع؟\n8. هي على الأرجح راح تتأخر.",
                    'instructions' => 'Translate using future simple (will + base verb).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I will call you as soon as I get home.',
                    'sentence_2' => 'She will not come to work tomorrow because she is sick.',
                    'sentence_3' => 'Will he finish the report on time?',
                    'sentences_json' => [
                        'They will travel to Paris next month for their holiday.',
                        'Don\'t worry — everything will be fine.',
                        'I think it will rain this evening.',
                        'Will you help me with this project please?',
                        'She will probably arrive late to the meeting.',
                        'I\'ll do it right now! Don\'t worry about it.',
                        'One day I will visit Japan. It is my dream.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'will', 'pronunciation' => 'Future marker', 'meaning_ar' => 'سوف'],
                        ['word' => 'won\'t', 'pronunciation' => 'Will not', 'meaning_ar' => 'لن'],
                        ['word' => 'probably', 'pronunciation' => 'PROB-ab-lee', 'meaning_ar' => 'على الأرجح'],
                        ['word' => 'I\'ll', 'pronunciation' => 'Short form of I will', 'meaning_ar' => 'سأ'],
                    ],
                ],
            ],
            'المستقبل المستمر' => [
                'writing' => [
                    'prompt' => "Translate:\n1. 🇸🇦→🇬🇧 \"أنا راح أكون نايم بحلول منتصف الليل.\"\n2. 🇸🇦→🇬🇧 \"هي راح تكون تسوق لشغلها الساعة 7.\"\n3. 🇸🇦→🇬🇧 \"هل هم راح يكونون يجون للعشاء الليلة؟\"\n4. 🇸🇦→🇬🇧 \"هو ما راح يكون يشتغل مساء الغد.\"\n5. 🇬🇧→🇸🇦 \"At this time next week, I will be sitting on the beach.\"\n6. 🇬🇧→🇸🇦 \"Will you be using the car tonight?\"\n7. 🇬🇧→🇸🇦 \"They will be travelling to London this time tomorrow.\"\n8. 🇬🇧→🇸🇦 \"I won't be attending the meeting.\"",
                    'model_answer' => "1. I will be sleeping by midnight.\n2. She will be driving to work at seven.\n3. Will they be joining us for dinner this evening?\n4. He will not be working tomorrow evening.\n5. بنفس الوقت الأسبوع الجاي أنا راح أكون جالس على الشاطئ.\n6. هل راح تكون تستخدم السيارة الليلة؟\n7. هم راح يكونون مسافرين لندن بنفس الوقت بكرة.\n8. أنا ما راح أكون حاضر الاجتماع.",
                    'instructions' => 'Translate using future continuous (will be + verb-ing).',
                    'min_words' => 30, 'max_words' => 200,
                ],
                'speaking' => [
                    'sentence_1' => 'I will be sleeping by midnight, so please do not call.',
                    'sentence_2' => 'She will be driving to work at seven in the morning.',
                    'sentence_3' => 'Will they be joining us for dinner this evening?',
                    'sentences_json' => [
                        'He will not be working tomorrow evening due to the holiday.',
                        'At this time next week, I will be sitting on the beach.',
                        'Will you be using the car tonight? I need it.',
                        'They will be travelling to London this time tomorrow.',
                        'I won\'t be attending the meeting. I have another appointment.',
                        'At eight p.m., I will be having dinner with my family.',
                        'Don\'t call at nine — I will be sleeping by then.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'will be sleeping', 'pronunciation' => 'will + be + verb-ing', 'meaning_ar' => 'سيكون نائماً'],
                        ['word' => 'this time tomorrow', 'pronunciation' => 'Future time reference', 'meaning_ar' => 'في نفس الوقت غداً'],
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
        $this->command->info("🎉 Part 6 Done! {$count} levels.");
    }
}
