<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart1 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'الحروف الأبجدية' => [
                'writing' => [
                    'prompt' => "Write the English word for each Arabic word:\n1. تفاحة\n2. كرة\n3. قطة\n4. كلب\n5. بيضة\n6. سمكة\n7. عنزة\n8. منزل\n9. جزيرة\n10. مربى",
                    'model_answer' => "1. Apple\n2. Ball\n3. Cat\n4. Dog\n5. Egg\n6. Fish\n7. Goat\n8. House\n9. Island\n10. Jam",
                    'instructions' => 'Write the correct English word for each Arabic word listed below.',
                    'min_words' => 10, 'max_words' => 50,
                ],
                'speaking' => [
                    'sentence_1' => 'A — Apple',
                    'sentence_2' => 'B — Ball',
                    'sentence_3' => 'C — Cat',
                    'sentences_json' => [
                        'D — Dog',
                        'E — Egg',
                        'F — Fish',
                        'G — Goat',
                        'H — House',
                        'I — Island',
                        'J — Jam',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Apple', 'pronunciation' => 'Say Ay-pull', 'meaning_ar' => 'تفاحة'],
                        ['word' => 'Ball', 'pronunciation' => 'Say Bawl', 'meaning_ar' => 'كرة'],
                        ['word' => 'Cat', 'pronunciation' => 'Say Kat', 'meaning_ar' => 'قطة'],
                        ['word' => 'Dog', 'pronunciation' => 'Say Dawg', 'meaning_ar' => 'كلب'],
                        ['word' => 'Egg', 'pronunciation' => 'Say Eg', 'meaning_ar' => 'بيضة'],
                        ['word' => 'Fish', 'pronunciation' => 'Say Fish', 'meaning_ar' => 'سمكة'],
                        ['word' => 'Goat', 'pronunciation' => 'Say Goht', 'meaning_ar' => 'عنزة'],
                        ['word' => 'House', 'pronunciation' => 'Say Hows', 'meaning_ar' => 'منزل'],
                        ['word' => 'Island', 'pronunciation' => 'Say EYE-lund', 'meaning_ar' => 'جزيرة'],
                        ['word' => 'Jam', 'pronunciation' => 'Say Jam', 'meaning_ar' => 'مربى'],
                    ],
                ],
            ],
            'الحروف المركبة' => [
                'writing' => [
                    'prompt' => "Write a word that contains each of the following letter blends:\n1. sh\n2. ch\n3. th\n4. wh\n5. ph\n6. ck\n7. ng\n8. qu\n9. bl\n10. tr",
                    'model_answer' => "1. Ship / Sheep\n2. Chair / Child\n3. Think / Three\n4. Whale / Where\n5. Phone / Photo\n6. Clock / Black\n7. Ring / Sing\n8. Queen / Quick\n9. Black / Blue\n10. Tree / Train",
                    'instructions' => 'Write one English word that contains each letter blend.',
                    'min_words' => 10, 'max_words' => 50,
                ],
                'speaking' => [
                    'sentence_1' => 'sh — Ship — she sells seashells',
                    'sentence_2' => 'ch — Chair — I sit on the chair',
                    'sentence_3' => 'th — Think — I think this is right',
                    'sentences_json' => [
                        'wh — Whale — where is the whale?',
                        'ph — Phone — take a photo with your phone',
                        'ck — Clock — the clock says two o\'clock',
                        'ng — Ring — I hear the phone ring',
                        'qu — Queen — the queen is quick',
                        'bl — Black — the black bird flew',
                        'tr — Train — the train arrived on time',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Ship', 'pronunciation' => 'sh sounds like shh whisper', 'meaning_ar' => 'سفينة'],
                        ['word' => 'Chair', 'pronunciation' => 'ch sounds like tch', 'meaning_ar' => 'كرسي'],
                        ['word' => 'Think', 'pronunciation' => 'th tongue between teeth', 'meaning_ar' => 'يفكر'],
                        ['word' => 'Whale', 'pronunciation' => 'wh sounds like w', 'meaning_ar' => 'حوت'],
                        ['word' => 'Phone', 'pronunciation' => 'ph sounds like f', 'meaning_ar' => 'هاتف'],
                        ['word' => 'Clock', 'pronunciation' => 'ck sounds like k', 'meaning_ar' => 'ساعة'],
                        ['word' => 'Ring', 'pronunciation' => 'ng nasal sound', 'meaning_ar' => 'خاتم'],
                        ['word' => 'Queen', 'pronunciation' => 'qu sounds like kw', 'meaning_ar' => 'ملكة'],
                        ['word' => 'Black', 'pronunciation' => 'bl blend', 'meaning_ar' => 'أسود'],
                        ['word' => 'Train', 'pronunciation' => 'tr blend', 'meaning_ar' => 'قطار'],
                    ],
                ],
            ],
            'الحروف الصوتية' => [
                'writing' => [
                    'prompt' => "Identify the vowel sounds and write words:\n1. Write the vowel sound in \"cat\"\n2. Write the vowel sound in \"bit\"\n3. Write the vowel sound in \"dog\"\n4. Write the vowel sound in \"cup\"\n5. Write the vowel sound in \"bed\"\n6. Write a word with long \"a\" sound\n7. Write a word with long \"e\" sound\n8. Write a word with long \"i\" sound\n9. Write a word with long \"o\" sound\n10. Write a word with long \"u\" sound",
                    'model_answer' => "1. a (short a)\n2. i (short i)\n3. o (short o)\n4. u (short u)\n5. e (short e)\n6. Rain / Cake\n7. Tree / Sleep\n8. Time / Bike\n9. Home / Boat\n10. Moon / Blue",
                    'instructions' => 'Identify each vowel sound and write example words.',
                    'min_words' => 10, 'max_words' => 60,
                ],
                'speaking' => [
                    'sentence_1' => 'Short A — Cat — the cat sat on a mat',
                    'sentence_2' => 'Short I — Bit — I bit into the apple',
                    'sentence_3' => 'Short O — Dog — the dog sat on a log',
                    'sentences_json' => [
                        'Short U — Cup — pass me the cup',
                        'Short E — Bed — I am in bed',
                        'Long A — Rain — the rain falls on the plain',
                        'Long E — Tree — the tree is green',
                        'Long I — Time — what time is it?',
                        'Long O — Home — I went back home',
                        'Long U — Moon — the moon is bright tonight',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Cat', 'pronunciation' => 'Short sharp a', 'meaning_ar' => 'قطة'],
                        ['word' => 'Bit', 'pronunciation' => 'Short i', 'meaning_ar' => 'قضم'],
                        ['word' => 'Dog', 'pronunciation' => 'Short o', 'meaning_ar' => 'كلب'],
                        ['word' => 'Cup', 'pronunciation' => 'Short u', 'meaning_ar' => 'كوب'],
                        ['word' => 'Bed', 'pronunciation' => 'Short e', 'meaning_ar' => 'سرير'],
                        ['word' => 'Rain', 'pronunciation' => 'Long ay sound', 'meaning_ar' => 'مطر'],
                        ['word' => 'Tree', 'pronunciation' => 'Long ee sound', 'meaning_ar' => 'شجرة'],
                        ['word' => 'Time', 'pronunciation' => 'Long eye sound', 'meaning_ar' => 'وقت'],
                        ['word' => 'Home', 'pronunciation' => 'Long oh sound', 'meaning_ar' => 'بيت'],
                        ['word' => 'Moon', 'pronunciation' => 'Long oo sound', 'meaning_ar' => 'قمر'],
                    ],
                ],
            ],
            'الأرقام' => [
                'writing' => [
                    'prompt' => "Convert between numbers and words:\n1. Write in digits: Forty-two\n2. Write in digits: Seventy-five\n3. Write in digits: One hundred and twenty\n4. Write in digits: Three hundred and sixty-five\n5. Write in digits: Two thousand and twenty-six\n6. Write in words: 18\n7. Write in words: 90\n8. Write in words: 500\n9. Write in words: 1000\n10. Write in words: 1,000,000",
                    'model_answer' => "1. 42\n2. 75\n3. 120\n4. 365\n5. 2026\n6. Eighteen\n7. Ninety\n8. Five hundred\n9. One thousand\n10. One million",
                    'instructions' => 'Convert numbers between digits and written English words.',
                    'min_words' => 10, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'One, two, three, four, five',
                    'sentence_2' => 'Six, seven, eight, nine, ten',
                    'sentence_3' => 'Eleven, twelve, thirteen, fourteen, fifteen',
                    'sentences_json' => [
                        'Sixteen, seventeen, eighteen, nineteen, twenty',
                        'Thirty, forty, fifty, sixty, seventy',
                        'Eighty, ninety, one hundred',
                        'Forty-two — I am forty-two years old',
                        'Three hundred and sixty-five days in a year',
                        'Two thousand and twenty-six',
                        'One million dollars',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Twelve', 'pronunciation' => 'TWELV — Not Twelf', 'meaning_ar' => 'اثنا عشر'],
                        ['word' => 'Fifteen', 'pronunciation' => 'Fif-TEEN — stress on teen', 'meaning_ar' => 'خمسة عشر'],
                        ['word' => 'Fifty', 'pronunciation' => 'FIF-tee — stress on fif', 'meaning_ar' => 'خمسون'],
                        ['word' => 'Forty', 'pronunciation' => 'FOR-tee — Not Four-ty', 'meaning_ar' => 'أربعون'],
                        ['word' => 'Hundred', 'pronunciation' => 'HUN-dred', 'meaning_ar' => 'مائة'],
                        ['word' => 'Thousand', 'pronunciation' => 'THOW-zund', 'meaning_ar' => 'ألف'],
                        ['word' => 'Million', 'pronunciation' => 'MIL-yun', 'meaning_ar' => 'مليون'],
                    ],
                ],
            ],
            'أقسام الكلام' => [
                'writing' => [
                    'prompt' => "Identify the part of speech for the underlined word:\n1. \"Ahmed runs fast.\" — What type is \"Ahmed\"?\n2. \"She runs fast.\" — What type is \"runs\"?\n3. \"He is a tall man.\" — What type is \"tall\"?\n4. \"He speaks very quickly.\" — What type is \"quickly\"?\n5. \"The book is on the table.\" — What type is \"on\"?\n6. \"She and I are friends.\" — What type is \"and\"?\n7. \"Oh! That hurt!\" — What type is \"Oh\"?\n8. \"They are happy.\" — What type is \"They\"?\n9. \"Give me a book.\" — What type is \"a\"?\n10. \"The old man walked slowly.\" — Identify all parts of speech",
                    'model_answer' => "1. Noun\n2. Verb\n3. Adjective\n4. Adverb\n5. Preposition\n6. Conjunction\n7. Interjection\n8. Pronoun\n9. Article\n10. The(Article) old(Adjective) man(Noun) walked(Verb) slowly(Adverb)",
                    'instructions' => 'Identify the part of speech for each highlighted word.',
                    'min_words' => 10, 'max_words' => 100,
                ],
                'speaking' => [
                    'sentence_1' => 'Noun: a person, place, or thing.',
                    'sentence_2' => 'Verb: an action word like run, eat, or sleep.',
                    'sentence_3' => 'Adjective: a word that describes a noun like tall, happy, or cold.',
                    'sentences_json' => [
                        'Adverb: a word that describes a verb like quickly, always, or very.',
                        'Preposition: a word that shows position like in, on, at, under.',
                        'Conjunction: a word that connects like and, but, or.',
                        'Interjection: a word that shows emotion like oh, wow, ouch.',
                        'Pronoun: a word that replaces a noun like he, she, they.',
                        'Article: a, an, the — used before nouns.',
                        'The happy child runs quickly in the big green park.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Noun', 'pronunciation' => 'Nown', 'meaning_ar' => 'اسم'],
                        ['word' => 'Verb', 'pronunciation' => 'Vurb', 'meaning_ar' => 'فعل'],
                        ['word' => 'Adjective', 'pronunciation' => 'AD-jek-tiv', 'meaning_ar' => 'صفة'],
                        ['word' => 'Adverb', 'pronunciation' => 'AD-vurb', 'meaning_ar' => 'ظرف'],
                        ['word' => 'Preposition', 'pronunciation' => 'PREP-oh-ZI-shun', 'meaning_ar' => 'حرف جر'],
                        ['word' => 'Conjunction', 'pronunciation' => 'kun-JUNK-shun', 'meaning_ar' => 'أداة ربط'],
                        ['word' => 'Interjection', 'pronunciation' => 'IN-ter-JEK-shun', 'meaning_ar' => 'أداة تعجب'],
                        ['word' => 'Pronoun', 'pronunciation' => 'PRO-nown', 'meaning_ar' => 'ضمير'],
                    ],
                ],
            ],
        ];

        $count = 0;
        foreach ($data as $searchKey => $exercises) {
            $level = CourseLevel::where('course_id', $courseId)
                ->where('title', 'LIKE', "%{$searchKey}%")
                ->first();

            if (!$level) {
                $this->command->warn("⚠ Not found: {$searchKey}");
                continue;
            }

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

            // Speaking
            PronunciationExercise::updateOrCreate(
                ['course_level_id' => $level->id],
                [
                    'sentence_1' => $exercises['speaking']['sentence_1'],
                    'sentence_2' => $exercises['speaking']['sentence_2'],
                    'sentence_3' => $exercises['speaking']['sentence_3'],
                    'sentences_json' => $exercises['speaking']['sentences_json'] ?? null,
                    'vocabulary_json' => $exercises['speaking']['vocabulary_json'],
                    'passing_score' => 60,
                    'max_duration_seconds' => 120,
                    'allow_retake' => true,
                ]
            );

            $level->update([
                'has_writing_exercise' => true,
                'has_speaking_exercise' => true,
            ]);

            $this->command->info("✅ {$level->title}");
            $count++;
        }
        $this->command->info("🎉 Part 1 Done! {$count} levels.");
    }
}
