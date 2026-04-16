<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart4 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'الاسم المنسوب' => [
                'writing' => [
                    'prompt' => "Write the compound noun:\n1. A driver who drives a bus?\n2. A room for a class?\n3. A bag for school?\n4. A table in a kitchen?\n5. A station for police?\n6. A match of football?\n7. A book with stories?\n8. A door of a car?\n9. Write 3 examples of attributive nouns from daily life\n10. Why is \"bus\" not plural in \"bus driver\"?",
                    'model_answer' => "1. A bus driver\n2. A classroom\n3. A school bag\n4. A kitchen table\n5. A police station\n6. A football match\n7. A story book\n8. A car door\n9. Coffee shop / Phone charger / Bus stop\n10. Attributive nouns don't take plural — they act as adjectives",
                    'instructions' => 'Form the correct compound noun for each description.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'A bus driver drives the bus every day.',
                    'sentence_2' => 'The classroom is very big and bright.',
                    'sentence_3' => 'I put my books in my school bag.',
                    'sentences_json' => [
                        'There is a cup on the kitchen table.',
                        'The police station is near the hospital.',
                        'We watched a football match last night.',
                        'I bought a new story book from the store.',
                        'Please close the car door carefully.',
                        'I go to the coffee shop every morning.',
                        'My phone charger is broken. I need a new one.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'bus driver', 'pronunciation' => 'Transport + driver', 'meaning_ar' => 'سائق حافلة'],
                        ['word' => 'classroom', 'pronunciation' => 'Purpose + room', 'meaning_ar' => 'فصل دراسي'],
                        ['word' => 'school bag', 'pronunciation' => 'Place + bag', 'meaning_ar' => 'حقيبة مدرسة'],
                        ['word' => 'kitchen table', 'pronunciation' => 'Room + furniture', 'meaning_ar' => 'طاولة المطبخ'],
                        ['word' => 'police station', 'pronunciation' => 'Function + station', 'meaning_ar' => 'مركز شرطة'],
                        ['word' => 'football match', 'pronunciation' => 'Sport + event', 'meaning_ar' => 'مباراة كرة قدم'],
                        ['word' => 'coffee shop', 'pronunciation' => 'Product + shop', 'meaning_ar' => 'مقهى'],
                        ['word' => 'phone charger', 'pronunciation' => 'Device + tool', 'meaning_ar' => 'شاحن هاتف'],
                    ],
                ],
            ],
            'الملكية' => [
                'writing' => [
                    'prompt' => "Write possessive forms:\n1. The car of Ahmed\n2. The books of the students\n3. Fill in: \"This bag is ___.\" (she)\n4. Fill in: \"It is ___ responsibility.\" (we)\n5. Fill in: \"The house is ___.\" (they)\n6. The laptop belonging to Sara\n7. The car belonging to my parents\n8. Fill in: \"___ name is Ahmed.\" (he)\n9. Fill in: \"Is this pen ___?\" (you)\n10. Write a sentence using possessive apostrophe",
                    'model_answer' => "1. Ahmed's car\n2. the students' books\n3. hers\n4. our\n5. theirs\n6. Sara's laptop\n7. my parents' car\n8. His\n9. yours\n10. The teacher's explanation was very clear.",
                    'instructions' => 'Write the correct possessive form for each item.',
                    'min_words' => 15, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'This is Ahmed\'s car. Ahmed\'s car is very fast.',
                    'sentence_2' => 'The students\' books are on the desk.',
                    'sentence_3' => 'This bag is hers. That house is theirs.',
                    'sentences_json' => [
                        'It is our responsibility to keep the place clean.',
                        'Sara\'s laptop is brand new.',
                        'My parents\' car is parked outside.',
                        'His name is Ahmed. Her name is Sara.',
                        'Is this pen yours? No, it is not mine.',
                        'The teacher\'s explanation was very clear and helpful.',
                        'My, your, his, her, its, our, their are possessive adjectives.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'mine', 'pronunciation' => 'Possessive pronoun of I', 'meaning_ar' => 'ملكي'],
                        ['word' => 'yours', 'pronunciation' => 'Possessive pronoun of you', 'meaning_ar' => 'ملكك'],
                        ['word' => 'his', 'pronunciation' => 'Possessive of he', 'meaning_ar' => 'ملكه'],
                        ['word' => 'hers', 'pronunciation' => 'Possessive pronoun of she', 'meaning_ar' => 'ملكها'],
                        ['word' => 'ours', 'pronunciation' => 'Possessive pronoun of we', 'meaning_ar' => 'ملكنا'],
                        ['word' => 'theirs', 'pronunciation' => 'Possessive pronoun of they', 'meaning_ar' => 'ملكهم'],
                        ['word' => "Ahmed's", 'pronunciation' => "Add 's for singular", 'meaning_ar' => 'ملك أحمد'],
                        ['word' => "students'", 'pronunciation' => "Add ' for plural ending in s", 'meaning_ar' => 'ملك الطلاب'],
                    ],
                ],
            ],
            'حروف الجر' => [
                'writing' => [
                    'prompt' => "Fill in with the correct preposition:\n1. \"The book is ___ the table.\" (on top)\n2. \"I was born ___ 1995.\"\n3. \"The meeting starts ___ 9 o'clock.\"\n4. \"She lives ___ London.\"\n5. \"I will see you ___ Monday.\"\n6. \"The cat is ___ the box.\" (inside)\n7. \"She walked ___ the bridge.\"\n8. \"He sat ___ me.\" (next to)\n9. Write a sentence using \"despite\"\n10. Write a sentence using \"throughout\"",
                    'model_answer' => "1. on\n2. in\n3. at\n4. in\n5. on\n6. in\n7. across\n8. beside / next to\n9. Despite the rain, they went out.\n10. She worked throughout the night.",
                    'instructions' => 'Choose the correct preposition for each sentence.',
                    'min_words' => 15, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'The book is on the table. The pen is on the desk.',
                    'sentence_2' => 'I was born in nineteen ninety-five.',
                    'sentence_3' => 'The meeting starts at nine o\'clock sharp.',
                    'sentences_json' => [
                        'She lives in London. He lives in Riyadh.',
                        'I will see you on Monday morning.',
                        'The cat is in the box. The bird is in the cage.',
                        'She walked across the long bridge over the river.',
                        'He sat beside me during the whole meeting.',
                        'Despite the heavy rain, they went out for a walk.',
                        'She worked throughout the night to finish the project.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'on', 'pronunciation' => 'Surface / days', 'meaning_ar' => 'على'],
                        ['word' => 'in', 'pronunciation' => 'Inside / months / years / cities', 'meaning_ar' => 'في'],
                        ['word' => 'at', 'pronunciation' => 'Exact time / specific place', 'meaning_ar' => 'في / عند'],
                        ['word' => 'across', 'pronunciation' => 'From one side to other', 'meaning_ar' => 'عبر'],
                        ['word' => 'beside', 'pronunciation' => 'bih-SYDE — next to', 'meaning_ar' => 'بجانب'],
                        ['word' => 'despite', 'pronunciation' => 'dih-SPYTE — even though', 'meaning_ar' => 'بالرغم من'],
                        ['word' => 'throughout', 'pronunciation' => 'threw-OWT — all during', 'meaning_ar' => 'طوال'],
                    ],
                ],
            ],
            'الأسماء الإشارية' => [
                'writing' => [
                    'prompt' => "Fill in with this, that, these, or those:\n1. \"___ is my pen.\" (close)\n2. \"___ are my books over there.\" (far plural)\n3. \"___ shoes are expensive!\" (close plural)\n4. \"___ building over there is very tall.\" (far)\n5. \"___ was a great movie!\" (past)\n6. \"___ days, everything is expensive.\" (current)\n7. Write a sentence using \"this\" correctly\n8. Write a sentence using \"those\" correctly\n9. Difference between \"this\" and \"that\"?\n10. Difference between \"these\" and \"those\"?",
                    'model_answer' => "1. This\n2. Those\n3. These\n4. That\n5. That\n6. These\n7. This coffee is too hot.\n8. Those mountains look beautiful from here.\n9. This = close. That = far.\n10. These = close plural. Those = far plural.",
                    'instructions' => 'Choose the correct demonstrative pronoun.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'This is my pen. It is right here in my hand.',
                    'sentence_2' => 'That is your car over there across the street.',
                    'sentence_3' => 'These shoes are very expensive. I like them a lot.',
                    'sentences_json' => [
                        'Those books on the top shelf are mine.',
                        'That building over there is the tallest in the city.',
                        'That was a great movie! I really enjoyed it.',
                        'These days, everything is more expensive than before.',
                        'This coffee is too hot. I will wait for it to cool down.',
                        'Those mountains look beautiful from here.',
                        'This means near me. That means far from me. These and those are their plurals.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'this', 'pronunciation' => 'Singular, near speaker', 'meaning_ar' => 'هذا/هذه'],
                        ['word' => 'that', 'pronunciation' => 'Singular, far from speaker', 'meaning_ar' => 'ذلك/تلك'],
                        ['word' => 'these', 'pronunciation' => 'Plural, near speaker', 'meaning_ar' => 'هؤلاء'],
                        ['word' => 'those', 'pronunciation' => 'Plural, far from speaker', 'meaning_ar' => 'أولئك'],
                    ],
                ],
            ],
            'كلمات وأفعال الربط' => [
                'writing' => [
                    'prompt' => "Join sentences using the linking word:\n1. \"I was tired. I kept working.\" (but)\n2. \"She stayed home. She was sick.\" (because)\n3. \"It was raining. We went out.\" (although)\n4. \"He studied hard. He passed.\" (so)\n5. \"The exam was hard. She passed.\" (however)\n6. Fill in: \"She is smart; ___, she works hard.\" (addition)\n7. Fill in: \"He was late; ___, he missed the bus.\" (result)\n8. Write a sentence using \"despite\"\n9. Write a sentence using \"whereas\"\n10. Write a sentence using \"consequently\"",
                    'model_answer' => "1. I was tired, but I kept working.\n2. She stayed home because she was sick.\n3. Although it was raining, we went out.\n4. He studied hard, so he passed.\n5. The exam was hard. However, she passed.\n6. furthermore / moreover\n7. therefore / as a result\n8. Despite being tired, she finished the work.\n9. He likes coffee, whereas she prefers tea.\n10. It rained heavily; consequently, the match was cancelled.",
                    'instructions' => 'Use the correct linking word to join each pair of sentences.',
                    'min_words' => 30, 'max_words' => 180,
                ],
                'speaking' => [
                    'sentence_1' => 'I was tired, but I kept working until midnight.',
                    'sentence_2' => 'She stayed home because she was feeling very sick.',
                    'sentence_3' => 'Although it was raining heavily, we still went out.',
                    'sentences_json' => [
                        'He studied very hard, so he passed the exam easily.',
                        'The exam was hard. However, she managed to pass it.',
                        'She is smart; furthermore, she works extremely hard.',
                        'He was late; therefore, he missed the bus.',
                        'Despite being tired, she finished all the work on time.',
                        'He likes coffee, whereas she prefers tea.',
                        'It rained heavily all day; consequently, the football match was cancelled.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'but', 'pronunciation' => 'Contrast — opposite ideas', 'meaning_ar' => 'لكن'],
                        ['word' => 'because', 'pronunciation' => 'Reason — why', 'meaning_ar' => 'لأن'],
                        ['word' => 'although', 'pronunciation' => 'awl-THO — despite the fact', 'meaning_ar' => 'بالرغم من'],
                        ['word' => 'however', 'pronunciation' => 'how-EV-er — but (formal)', 'meaning_ar' => 'ومع ذلك'],
                        ['word' => 'therefore', 'pronunciation' => 'THAIR-for — so (formal)', 'meaning_ar' => 'لذلك'],
                        ['word' => 'furthermore', 'pronunciation' => 'FUR-ther-more — also (formal)', 'meaning_ar' => 'علاوة على ذلك'],
                        ['word' => 'consequently', 'pronunciation' => 'KON-sih-kwent-lee', 'meaning_ar' => 'بالتالي'],
                        ['word' => 'whereas', 'pronunciation' => 'wair-AZ — while/but', 'meaning_ar' => 'بينما'],
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
        $this->command->info("🎉 Part 4 Done! {$count} levels.");
    }
}
