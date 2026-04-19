<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart8 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'محددات الكمية' => [
                'writing' => [
                    'prompt' => "Fill in with the correct quantifier:\n1. \"There is ___ milk in the fridge.\" (uncountable)\n2. \"There are ___ students in the school.\" (large number)\n3. \"I have ___ time left.\" (very small)\n4. \"She has ___ friends here.\" (small positive)\n5. \"There is not ___ sugar left.\"\n6. \"We need ___ information before deciding.\"\n7. \"He has ___ money to buy a car.\" (sufficient)\n8. \"There are too ___ cars on the road.\"\n9. \"She has too ___ free time.\"\n10. Difference between \"few\" and \"a few\"?",
                    'model_answer' => "1. a little / some\n2. many\n3. little\n4. a few\n5. much\n6. some / more\n7. enough\n8. many\n9. much\n10. few = almost none (negative). a few = some, not many (positive)",
                    'instructions' => 'Choose the correct quantifier.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'There is a little milk in the fridge. We need to buy more.',
                    'sentence_2' => 'There are many students in our school. About five hundred.',
                    'sentence_3' => 'I have very little time left. The exam starts in ten minutes.',
                    'sentences_json' => [
                        'She has a few friends here, but she is not lonely.',
                        'There is not much sugar left. Can you buy some?',
                        'We need more information before we can make a decision.',
                        'He has enough money to buy a new car.',
                        'There are too many cars on the road during rush hour.',
                        'She has too much free time and gets bored easily.',
                        'Few people know about this place. It is very quiet. A few friends came to help us move.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'many', 'pronunciation' => 'Countable — large number', 'meaning_ar' => 'كثير (معدود)'],
                        ['word' => 'much', 'pronunciation' => 'Uncountable — large amount', 'meaning_ar' => 'كثير (غير معدود)'],
                        ['word' => 'a few', 'pronunciation' => 'Small number (positive)', 'meaning_ar' => 'بعض'],
                        ['word' => 'few', 'pronunciation' => 'Almost none (negative)', 'meaning_ar' => 'قليل جداً'],
                        ['word' => 'a little', 'pronunciation' => 'Small amount (positive)', 'meaning_ar' => 'قليل من'],
                        ['word' => 'little', 'pronunciation' => 'Almost none (negative)', 'meaning_ar' => 'قليل جداً من'],
                        ['word' => 'enough', 'pronunciation' => 'Sufficient amount', 'meaning_ar' => 'كافي'],
                    ],
                ],
            ],
            'الأفعال الانعكاسية' => [
                'writing' => [
                    'prompt' => "Fill in with the correct delexical verb (take, make, have, do, give):\n1. \"She ___ a photo of the sunset.\"\n2. \"He ___ a mistake in his report.\"\n3. \"They ___ a good time at the party.\"\n4. \"Can you ___ me a favour?\"\n5. \"She ___ a shower every morning.\"\n6. \"They ___ a decision together.\"\n7. \"He ___ his best on the exam.\"\n8. \"We ___ lunch at noon.\"\n9. \"She ___ a speech at the wedding.\"\n10. Write 3 phrases with \"make\"",
                    'model_answer' => "1. took\n2. made\n3. had\n4. do\n5. takes\n6. made\n7. did\n8. had\n9. made / gave\n10. make a mistake / make a decision / make a phone call",
                    'instructions' => 'Fill in with the correct delexical verb.',
                    'min_words' => 15, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'She took a photo of the beautiful sunset.',
                    'sentence_2' => 'He made a mistake in his report. He needs to fix it.',
                    'sentence_3' => 'They had a really good time at the party last night.',
                    'sentences_json' => [
                        'Can you do me a favour? I need your help.',
                        'She takes a shower every morning before breakfast.',
                        'They made a decision together as a family.',
                        'He did his best on the exam, but it was very hard.',
                        'We had lunch at noon in the hotel restaurant.',
                        'She gave a wonderful speech at the wedding.',
                        'Take a break, make a plan, have a meeting, do your homework.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'take a photo', 'pronunciation' => 'take + action noun', 'meaning_ar' => 'يلتقط صورة'],
                        ['word' => 'make a mistake', 'pronunciation' => 'make + result noun', 'meaning_ar' => 'يرتكب خطأ'],
                        ['word' => 'have a good time', 'pronunciation' => 'have + experience', 'meaning_ar' => 'يستمتع'],
                        ['word' => 'do a favour', 'pronunciation' => 'do + task noun', 'meaning_ar' => 'يقدم معروف'],
                        ['word' => 'give a speech', 'pronunciation' => 'give + communication', 'meaning_ar' => 'يلقي خطاب'],
                    ],
                ],
            ],
            'يوجد' => [
                'writing' => [
                    'prompt' => "Fill in or write sentences:\n1. \"___ a problem with the system.\"\n2. \"___ many options available.\"\n3. \"___ any sugar left?\" (question)\n4. \"___ enough rooms for everyone.\" (negative)\n5. Write about your city using \"there is\"\n6. Write about your city using \"there are\"\n7. \"___ no time left!\"\n8. \"___ five people waiting outside.\"\n9. Change to question: \"There is a cat in the garden.\"\n10. Change to negative: \"There are students in the class.\"",
                    'model_answer' => "1. There is\n2. There are\n3. Is there\n4. There aren't\n5. There is a beautiful park near my house.\n6. There are many shopping malls in Riyadh.\n7. There is\n8. There are\n9. Is there a cat in the garden?\n10. There aren't any students in the class.",
                    'instructions' => 'Use there is / there are correctly.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'There is a problem with the system. We need to fix it.',
                    'sentence_2' => 'There are many options available. Choose one you like.',
                    'sentence_3' => 'Is there any sugar left? I need some for my tea.',
                    'sentences_json' => [
                        'There aren\'t enough rooms for everyone. We need to book more.',
                        'There is a beautiful park near my house. I go there every day.',
                        'There are many shopping malls in Riyadh. It is a big city.',
                        'There is no time left! We must hurry up now.',
                        'There are five people waiting outside the office.',
                        'Is there a cat in the garden? Yes, there is a grey one.',
                        'There aren\'t any students in the class yet. It is still early.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'There is', 'pronunciation' => 'Singular noun follows', 'meaning_ar' => 'يوجد'],
                        ['word' => 'There are', 'pronunciation' => 'Plural noun follows', 'meaning_ar' => 'يوجد (جمع)'],
                        ['word' => 'Is there', 'pronunciation' => 'Question — singular', 'meaning_ar' => 'هل يوجد'],
                        ['word' => 'Are there', 'pronunciation' => 'Question — plural', 'meaning_ar' => 'هل يوجد (جمع)'],
                        ['word' => 'There was', 'pronunciation' => 'Past singular', 'meaning_ar' => 'كان يوجد'],
                        ['word' => 'There were', 'pronunciation' => 'Past plural', 'meaning_ar' => 'كان يوجد (جمع)'],
                    ],
                ],
            ],
            'الوقت' => [
                'writing' => [
                    'prompt' => "Write the time in English:\n1. الساعة 5:30\n2. الساعة 3:15\n3. الساعة 8:00 مساءً\n4. الساعة 9:45\n5. الساعة 11:50\n6. الاجتماع من 2:00 لـ 4:30\n7. كيف تسأل شخص عن الوقت بطريقة مؤدبة؟\n8. كيف تقول 12:00 ظهراً و 12:00 منتصف الليل؟",
                    'model_answer' => "1. It's half past five.\n2. It's quarter past three.\n3. The match starts at eight o'clock in the evening.\n4. It's quarter to ten.\n5. It's ten to twelve.\n6. From two o'clock to half past four.\n7. Excuse me, could you tell me what time it is?\n8. 12:00 noon / midday — 12:00 midnight",
                    'instructions' => 'Write each time correctly in English.',
                    'min_words' => 20, 'max_words' => 120,
                ],
                'speaking' => [
                    'sentence_1' => 'What time is it? It is half past five.',
                    'sentence_2' => 'It is quarter past three. The appointment is at three fifteen.',
                    'sentence_3' => 'It is eight o\'clock in the evening. The show starts now.',
                    'sentences_json' => [
                        'It is quarter to ten. We need to hurry.',
                        'It is ten to twelve. Almost lunchtime.',
                        'The meeting is from two o\'clock to half past four.',
                        'Excuse me, could you tell me what time it is, please?',
                        'It is twelve noon. It is twelve midnight.',
                        'The train leaves at seven forty-five a.m. Don\'t be late.',
                        'The shop opens at nine in the morning and closes at ten at night.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'half past', 'pronunciation' => '30 minutes past', 'meaning_ar' => 'والنصف'],
                        ['word' => 'quarter past', 'pronunciation' => '15 minutes past', 'meaning_ar' => 'والربع'],
                        ['word' => 'quarter to', 'pronunciation' => '15 minutes before', 'meaning_ar' => 'إلا ربع'],
                        ['word' => 'noon', 'pronunciation' => '12:00 daytime', 'meaning_ar' => 'الظهر'],
                        ['word' => 'midnight', 'pronunciation' => '12:00 nighttime', 'meaning_ar' => 'منتصف الليل'],
                        ['word' => 'a.m.', 'pronunciation' => 'Before noon', 'meaning_ar' => 'صباحاً'],
                        ['word' => 'p.m.', 'pronunciation' => 'After noon', 'meaning_ar' => 'مساءً'],
                    ],
                ],
            ],
            'التاريخ' => [
                'writing' => [
                    'prompt' => "Write dates in English:\n1. 5 يناير 2026\n2. 20 مارس 1998\n3. 13 أبريل 2026\n4. حجزت فندق من 1 أغسطس لـ 10 أغسطس\n5. اكتب 25/12/2025 بالأمريكي وبالبريطاني\n6. ما الشهر السادس والثاني عشر؟\n7. \"الاجتماع القادم هو يوم الخميس 3 أكتوبر\"\n8. تاريخ اليوم الأول من السنة الجديدة",
                    'model_answer' => "1. The fifth of January, 2026. / January 5th, 2026.\n2. The twentieth of March, 1998.\n3. The thirteenth of April, 2026.\n4. From the first to the tenth of August.\n5. American: December 25, 2025 — British: 25th December 2025\n6. 6th = June — 12th = December\n7. The next meeting is on Thursday, the third of October.\n8. The first of January. / January 1st.",
                    'instructions' => 'Write each date correctly in English.',
                    'min_words' => 20, 'max_words' => 150,
                ],
                'speaking' => [
                    'sentence_1' => 'Today is Monday, the thirteenth of April, twenty twenty-six.',
                    'sentence_2' => 'My birthday is on the twentieth of March, nineteen ninety-eight.',
                    'sentence_3' => 'The meeting is on the fifth of January, twenty twenty-six.',
                    'sentences_json' => [
                        'I booked a hotel from the first to the tenth of August.',
                        'In American English: December twenty-fifth, twenty twenty-five.',
                        'In British English: the twenty-fifth of December, twenty twenty-five.',
                        'The sixth month is June. The twelfth month is December.',
                        'The next meeting is on Thursday, the third of October.',
                        'The first day of the new year is January the first.',
                        'January, February, March, April, May, June, July, August, September, October, November, December.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'first', 'pronunciation' => '1st — ordinal', 'meaning_ar' => 'الأول'],
                        ['word' => 'second', 'pronunciation' => '2nd — ordinal', 'meaning_ar' => 'الثاني'],
                        ['word' => 'third', 'pronunciation' => '3rd — ordinal', 'meaning_ar' => 'الثالث'],
                        ['word' => 'fifth', 'pronunciation' => '5th — ordinal', 'meaning_ar' => 'الخامس'],
                        ['word' => 'twelfth', 'pronunciation' => '12th — ordinal', 'meaning_ar' => 'الثاني عشر'],
                        ['word' => 'twentieth', 'pronunciation' => '20th — ordinal', 'meaning_ar' => 'العشرون'],
                        ['word' => 'thirty-first', 'pronunciation' => '31st — ordinal', 'meaning_ar' => 'الحادي والثلاثون'],
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
        $this->command->info("🎉 Part 8 Done! {$count} levels.");
    }
}
