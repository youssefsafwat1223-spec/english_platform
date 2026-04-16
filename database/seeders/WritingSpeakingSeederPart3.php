<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart3 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'أنواع الأفعال' => [
                'writing' => [
                    'prompt' => "Identify the type of verb:\n1. \"She seems tired.\" — type of \"seems\"?\n2. \"He can swim.\" — type of \"can\"?\n3. \"They are playing.\" — type of \"are\"?\n4. \"I run every day.\" — type of \"run\"?\n5. Give an example of a linking verb in a sentence\n6. Give an example of a modal verb in a sentence\n7. Give an example of an auxiliary verb in a sentence\n8. Give an example of an action verb in a sentence\n9. \"The food tastes delicious.\" — type of \"tastes\"?\n10. \"You must wear a seatbelt.\" — type of \"must\"?",
                    'model_answer' => "1. Linking verb\n2. Modal verb\n3. Auxiliary verb\n4. Action verb\n5. She feels happy.\n6. You should study.\n7. He is working.\n8. She runs every morning.\n9. Linking verb\n10. Modal verb",
                    'instructions' => 'Identify whether each verb is an action, linking, modal, or auxiliary verb.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'Action verbs show what someone does: run, eat, write.',
                    'sentence_2' => 'Linking verbs connect the subject to a description: be, seem, feel.',
                    'sentence_3' => 'Modal verbs show ability or obligation: can, must, should.',
                    'sentences_json' => [
                        'Auxiliary verbs help the main verb: is, are, have, do.',
                        'She seems tired. Seems is a linking verb.',
                        'He can swim very well. Can is a modal verb.',
                        'They are playing football. Are is an auxiliary verb.',
                        'I run every day. Run is an action verb.',
                        'The food tastes delicious. Tastes is a linking verb here.',
                        'You must wear a seatbelt. Must is a modal verb.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Action verb', 'pronunciation' => 'Physical or mental action', 'meaning_ar' => 'فعل حركي'],
                        ['word' => 'Linking verb', 'pronunciation' => 'Connects subject to description', 'meaning_ar' => 'فعل ربط'],
                        ['word' => 'Modal verb', 'pronunciation' => 'Ability, obligation, advice', 'meaning_ar' => 'فعل ناقص'],
                        ['word' => 'Auxiliary verb', 'pronunciation' => 'Helps the main verb', 'meaning_ar' => 'فعل مساعد'],
                        ['word' => 'seems', 'pronunciation' => 'Linking — looks/appears', 'meaning_ar' => 'يبدو'],
                        ['word' => 'tastes', 'pronunciation' => 'Linking — flavor sense', 'meaning_ar' => 'مذاقه'],
                    ],
                ],
            ],
            'فعل الكينونة' => [
                'writing' => [
                    'prompt' => "Fill in with the correct form of \"to be\":\n1. I ___ a student. (present)\n2. She ___ happy yesterday. (past)\n3. They ___ playing football now. (present continuous)\n4. He ___ tall and strong. (present)\n5. We ___ at home last night. (past)\n6. It ___ a beautiful day today. (present)\n7. The children ___ in the garden. (present)\n8. I ___ not feeling well yesterday. (past)\n9. ___ you ready for the exam? (present)\n10. She ___ going to be a doctor. (future)",
                    'model_answer' => "1. am\n2. was\n3. are\n4. is\n5. were\n6. is\n7. are\n8. was\n9. Are\n10. is",
                    'instructions' => 'Complete each sentence with the correct form of the verb "to be".',
                    'min_words' => 10, 'max_words' => 30,
                ],
                'speaking' => [
                    'sentence_1' => 'I am a student. I am happy.',
                    'sentence_2' => 'She is tall. He is strong. It is beautiful.',
                    'sentence_3' => 'You are ready. We are friends. They are here.',
                    'sentences_json' => [
                        'I was tired yesterday. She was happy.',
                        'We were at home last night. They were playing.',
                        'The children are in the garden.',
                        'I was not feeling well yesterday.',
                        'Are you ready for the exam? Yes, I am.',
                        'She is going to be a doctor when she grows up.',
                        'It is a beautiful day today. We are very lucky.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'am', 'pronunciation' => 'Used with I only', 'meaning_ar' => 'أكون'],
                        ['word' => 'is', 'pronunciation' => 'Used with he/she/it', 'meaning_ar' => 'يكون'],
                        ['word' => 'are', 'pronunciation' => 'Used with you/we/they', 'meaning_ar' => 'يكونون'],
                        ['word' => 'was', 'pronunciation' => 'Past of am/is', 'meaning_ar' => 'كان'],
                        ['word' => 'were', 'pronunciation' => 'Past of are', 'meaning_ar' => 'كانوا'],
                    ],
                ],
            ],
            'المفعول به' => [
                'writing' => [
                    'prompt' => "Fill in with the correct object pronoun:\n1. \"She called ___.\" (object of I)\n2. \"Give ___ the book.\" (object of I)\n3. \"I can see ___.\" (object of they)\n4. \"The teacher helped ___.\" (object of we)\n5. \"He gave ___ a gift.\" (object of she)\n6. \"Can you help ___?\" (object of I)\n7. \"She saw ___ at the mall.\" (object of he)\n8. \"They invited ___ to the party.\" (object of we)\n9. Write a sentence using \"them\" as object\n10. Write a sentence using \"her\" as object",
                    'model_answer' => "1. me\n2. me\n3. them\n4. us\n5. her\n6. me\n7. him\n8. us\n9. I saw them at the party.\n10. He called her last night.",
                    'instructions' => 'Fill in with the correct object pronoun.',
                    'min_words' => 15, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'She called me. Give me the book please.',
                    'sentence_2' => 'I can see them. The teacher helped us.',
                    'sentence_3' => 'He gave her a gift. She saw him at the mall.',
                    'sentences_json' => [
                        'Can you help me with this homework?',
                        'They invited us to the party last Friday.',
                        'I saw them at the supermarket yesterday.',
                        'He called her on the phone last night.',
                        'The dog followed him all the way home.',
                        'Please tell us the answer to this question.',
                        'I bought it from the store near our house.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'me', 'pronunciation' => 'Object of I', 'meaning_ar' => 'ـني'],
                        ['word' => 'him', 'pronunciation' => 'Object of he', 'meaning_ar' => 'ـه'],
                        ['word' => 'her', 'pronunciation' => 'Object of she', 'meaning_ar' => 'ـها'],
                        ['word' => 'us', 'pronunciation' => 'Object of we', 'meaning_ar' => 'ـنا'],
                        ['word' => 'them', 'pronunciation' => 'Object of they', 'meaning_ar' => 'ـهم'],
                        ['word' => 'it', 'pronunciation' => 'Object of it', 'meaning_ar' => 'ـه (لغير العاقل)'],
                    ],
                ],
            ],
            'الصفات' => [
                'writing' => [
                    'prompt' => "Write sentences using adjectives:\n1. Describe \"a day\" using 3 adjectives\n2. Write a sentence using \"angry\"\n3. Write a sentence using \"delicious\"\n4. Write the opposite of \"happy\"\n5. Write the opposite of \"fast\"\n6. Fill in: \"He is a ___ student.\" (hardworking)\n7. Fill in: \"It was a ___ movie.\" (boring)\n8. Write 5 adjectives to describe a city\n9. Fill in: \"She wore a ___ and ___ dress.\"\n10. Write a sentence using 2 adjectives before a noun",
                    'model_answer' => "1. It was a cold, windy, and beautiful day.\n2. The angry dog barked loudly.\n3. She made a delicious cake.\n4. sad / unhappy\n5. slow\n6. hardworking\n7. boring\n8. busy, crowded, noisy, modern, beautiful\n9. long and red / beautiful and elegant\n10. She has beautiful blue eyes.",
                    'instructions' => 'Complete each task using adjectives correctly.',
                    'min_words' => 20, 'max_words' => 120,
                ],
                'speaking' => [
                    'sentence_1' => 'It was a cold, windy, and beautiful day.',
                    'sentence_2' => 'The angry dog barked loudly at the stranger.',
                    'sentence_3' => 'She made a delicious chocolate cake for the party.',
                    'sentences_json' => [
                        'The opposite of happy is sad.',
                        'The opposite of fast is slow.',
                        'He is a very hardworking student.',
                        'It was a very boring movie. I fell asleep.',
                        'This city is busy, crowded, noisy, modern, and beautiful.',
                        'She wore a long and elegant red dress.',
                        'She has beautiful blue eyes and long dark hair.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'beautiful', 'pronunciation' => 'BYOO-tih-ful', 'meaning_ar' => 'جميل'],
                        ['word' => 'delicious', 'pronunciation' => 'deh-LISH-us', 'meaning_ar' => 'لذيذ'],
                        ['word' => 'angry', 'pronunciation' => 'ANG-ree', 'meaning_ar' => 'غاضب'],
                        ['word' => 'hardworking', 'pronunciation' => 'HARD-wur-king', 'meaning_ar' => 'مجتهد'],
                        ['word' => 'boring', 'pronunciation' => 'BOR-ing', 'meaning_ar' => 'ممل'],
                        ['word' => 'crowded', 'pronunciation' => 'KROW-did', 'meaning_ar' => 'مزدحم'],
                        ['word' => 'elegant', 'pronunciation' => 'EL-eh-gant', 'meaning_ar' => 'أنيق'],
                    ],
                ],
            ],
            'الظروف' => [
                'writing' => [
                    'prompt' => "Work with adverbs:\n1. Form an adverb from \"quick\"\n2. Form an adverb from \"happy\"\n3. Form an adverb from \"good\"\n4. Fill in: \"She speaks English ___.\" (fluent)\n5. Fill in: \"He ___ forgets his keys.\" (always)\n6. Fill in: \"They arrived ___.\" (early)\n7. Write a sentence using \"never\" correctly\n8. Write a sentence using \"quite\" correctly\n9. Write a sentence using \"already\" correctly\n10. Identify the adverb: \"He ran very quickly to catch the bus.\"",
                    'model_answer' => "1. quickly\n2. happily\n3. well\n4. fluently\n5. always\n6. early\n7. I have never seen snow.\n8. The exam was quite difficult.\n9. She has already finished her homework.\n10. very (degree), quickly (manner)",
                    'instructions' => 'Form adverbs and use them correctly in sentences.',
                    'min_words' => 15, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'Quick becomes quickly. Add ly to make an adverb.',
                    'sentence_2' => 'Happy becomes happily. Change y to ily.',
                    'sentence_3' => 'Good becomes well. This is irregular.',
                    'sentences_json' => [
                        'She speaks English fluently. Fluent becomes fluently.',
                        'He always forgets his keys. Always is a frequency adverb.',
                        'They arrived early this morning. Early can be adjective or adverb.',
                        'I have never seen snow in my life.',
                        'The exam was quite difficult for everyone.',
                        'She has already finished all her homework.',
                        'He ran very quickly to catch the bus. Very and quickly are both adverbs.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'quickly', 'pronunciation' => 'KWIK-lee', 'meaning_ar' => 'بسرعة'],
                        ['word' => 'happily', 'pronunciation' => 'HAP-ih-lee', 'meaning_ar' => 'بسعادة'],
                        ['word' => 'well', 'pronunciation' => 'Irregular adverb of good', 'meaning_ar' => 'بشكل جيد'],
                        ['word' => 'fluently', 'pronunciation' => 'FLOO-ent-lee', 'meaning_ar' => 'بطلاقة'],
                        ['word' => 'always', 'pronunciation' => 'AWL-wayz', 'meaning_ar' => 'دائماً'],
                        ['word' => 'never', 'pronunciation' => 'NEV-er', 'meaning_ar' => 'أبداً'],
                        ['word' => 'already', 'pronunciation' => 'awl-RED-ee', 'meaning_ar' => 'بالفعل'],
                        ['word' => 'quite', 'pronunciation' => 'KWYT', 'meaning_ar' => 'تماماً / إلى حد ما'],
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
        $this->command->info("🎉 Part 3 Done! {$count} levels.");
    }
}
