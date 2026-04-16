<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;

class WritingSpeakingSeederPart2 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'البادئة واللاحقة' => [
                'writing' => [
                    'prompt' => "Add prefixes or suffixes to create new words:\n1. Add prefix to make opposite of \"happy\"\n2. Add prefix to make opposite of \"possible\"\n3. Add prefix to make opposite of \"agree\"\n4. Add prefix to mean \"again\": write\n5. Add suffix to make noun from \"kind\"\n6. Add suffix to make adjective from \"help\"\n7. Add suffix to mean \"person who\": teach\n8. Add suffix to make adjective from \"care\"\n9. What does \"mis-\" mean? Give an example\n10. What does \"-tion\" mean? Give an example",
                    'model_answer' => "1. unhappy\n2. impossible\n3. disagree\n4. rewrite\n5. kindness\n6. helpful\n7. teacher\n8. careless / careful\n9. Wrong/badly — misunderstand\n10. Action/result — education",
                    'instructions' => 'Create new words by adding the correct prefix or suffix.',
                    'min_words' => 10, 'max_words' => 80,
                ],
                'speaking' => [
                    'sentence_1' => 'un-happy means not happy',
                    'sentence_2' => 'im-possible means not possible',
                    'sentence_3' => 'dis-agree means to not agree',
                    'sentences_json' => [
                        're-write means to write again',
                        'kind-ness is LN. Kindness is a wonderful quality.',
                        'help-ful means full of help. She is very helpful.',
                        'teach-er is a person who teaches. My teacher is great.',
                        'care-less means without care. Don\'t be careless with your work.',
                        'mis-understand means to understand wrongly.',
                        'edu-ca-tion comes from educate. Education is very important.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'unhappy', 'pronunciation' => 'un = not', 'meaning_ar' => 'غير سعيد'],
                        ['word' => 'impossible', 'pronunciation' => 'im = not', 'meaning_ar' => 'مستحيل'],
                        ['word' => 'disagree', 'pronunciation' => 'dis = not/opposite', 'meaning_ar' => 'يختلف'],
                        ['word' => 'rewrite', 'pronunciation' => 're = again', 'meaning_ar' => 'يعيد الكتابة'],
                        ['word' => 'kindness', 'pronunciation' => '-ness = makes noun', 'meaning_ar' => 'لطف'],
                        ['word' => 'helpful', 'pronunciation' => '-ful = full of', 'meaning_ar' => 'مفيد'],
                        ['word' => 'teacher', 'pronunciation' => '-er = person who', 'meaning_ar' => 'معلم'],
                        ['word' => 'careless', 'pronunciation' => '-less = without', 'meaning_ar' => 'مهمل'],
                        ['word' => 'misunderstand', 'pronunciation' => 'mis = wrongly', 'meaning_ar' => 'يسيء الفهم'],
                        ['word' => 'education', 'pronunciation' => '-tion = action/result', 'meaning_ar' => 'تعليم'],
                    ],
                ],
            ],
            'علامات الترقيم' => [
                'writing' => [
                    'prompt' => "Add correct punctuation to each sentence:\n1. What is your name\n2. My name is Sara I am from Riyadh\n3. Watch out\n4. I need three things bread milk and eggs\n5. Its a beautiful day isnt it\n6. She said hello how are you\n7. When do you use a comma? Give an example\n8. When do you use an apostrophe? Give an example\n9. dr ahmed is my doctor\n10. I love english its so useful",
                    'model_answer' => "1. What is your name?\n2. My name is Sara. I am from Riyadh.\n3. Watch out!\n4. I need three things: bread, milk, and eggs.\n5. It's a beautiful day, isn't it?\n6. She said, \"Hello, how are you?\"\n7. To separate items. e.g. I like coffee, tea, and juice.\n8. For contractions or possession. e.g. It's / Sara's book\n9. Dr. Ahmed is my doctor.\n10. I love English. It's so useful!",
                    'instructions' => 'Add the correct punctuation marks to each sentence.',
                    'min_words' => 20, 'max_words' => 150,
                ],
                'speaking' => [
                    'sentence_1' => 'Period means stop. This is my book.',
                    'sentence_2' => 'Question mark means ask. What is your name?',
                    'sentence_3' => 'Exclamation mark means strong feeling. Watch out!',
                    'sentences_json' => [
                        'Comma means short pause. I like tea, coffee, and juice.',
                        'Apostrophe makes contractions. It is becomes it\'s.',
                        'Apostrophe shows possession. Sara\'s bag is red.',
                        'Colon introduces a list. I need three things: bread, milk, and eggs.',
                        'Quotation marks for speech. She said, "Hello!"',
                        'Semicolon connects related sentences. I studied hard; I passed the exam.',
                        'Capital letters start sentences and names. Dr. Ahmed is my doctor.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Period', 'pronunciation' => 'Full stop (.)', 'meaning_ar' => 'نقطة'],
                        ['word' => 'Comma', 'pronunciation' => 'Short pause (,)', 'meaning_ar' => 'فاصلة'],
                        ['word' => 'Exclamation', 'pronunciation' => 'Strong feeling (!)', 'meaning_ar' => 'تعجب'],
                        ['word' => 'Question mark', 'pronunciation' => 'Ask something (?)', 'meaning_ar' => 'علامة استفهام'],
                        ['word' => 'Apostrophe', 'pronunciation' => 'uh-POS-truh-fee', 'meaning_ar' => 'فاصلة علوية'],
                        ['word' => 'Colon', 'pronunciation' => 'Before a list (:)', 'meaning_ar' => 'نقطتان'],
                        ['word' => 'Semicolon', 'pronunciation' => 'SEM-ee-KOH-lun (;)', 'meaning_ar' => 'فاصلة منقوطة'],
                        ['word' => 'Quotation marks', 'pronunciation' => 'For speech ("...")', 'meaning_ar' => 'علامات تنصيص'],
                    ],
                ],
            ],
            'المفرد والجمع' => [
                'writing' => [
                    'prompt' => "Write the plural form:\n1. cat\n2. box\n3. city\n4. child\n5. woman\n6. tooth\n7. knife\n8. mouse\n9. sheep\n10. person",
                    'model_answer' => "1. cats\n2. boxes\n3. cities\n4. children\n5. women\n6. teeth\n7. knives\n8. mice\n9. sheep\n10. people",
                    'instructions' => 'Write the correct plural form of each word.',
                    'min_words' => 10, 'max_words' => 30,
                ],
                'speaking' => [
                    'sentence_1' => 'cat becomes cats. Add s for regular plurals.',
                    'sentence_2' => 'box becomes boxes. Add es after s, x, sh, ch.',
                    'sentence_3' => 'city becomes cities. Change y to ies.',
                    'sentences_json' => [
                        'child becomes children. This is irregular.',
                        'woman becomes women. This is irregular.',
                        'tooth becomes teeth. This is irregular.',
                        'knife becomes knives. Change f or fe to ves.',
                        'mouse becomes mice. This is irregular.',
                        'sheep stays sheep. Some words don\'t change.',
                        'person becomes people. This is irregular.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'cats', 'pronunciation' => 'Regular: add s', 'meaning_ar' => 'قطط'],
                        ['word' => 'boxes', 'pronunciation' => 'After s/x/sh/ch: add es', 'meaning_ar' => 'صناديق'],
                        ['word' => 'cities', 'pronunciation' => 'y → ies', 'meaning_ar' => 'مدن'],
                        ['word' => 'children', 'pronunciation' => 'CHIL-dren (irregular)', 'meaning_ar' => 'أطفال'],
                        ['word' => 'women', 'pronunciation' => 'WIM-in (irregular)', 'meaning_ar' => 'نساء'],
                        ['word' => 'teeth', 'pronunciation' => 'Teeth (irregular)', 'meaning_ar' => 'أسنان'],
                        ['word' => 'knives', 'pronunciation' => 'f → ves', 'meaning_ar' => 'سكاكين'],
                        ['word' => 'mice', 'pronunciation' => 'Myss (irregular)', 'meaning_ar' => 'فئران'],
                        ['word' => 'sheep', 'pronunciation' => 'No change', 'meaning_ar' => 'خراف'],
                        ['word' => 'people', 'pronunciation' => 'PEE-pul (irregular)', 'meaning_ar' => 'ناس'],
                    ],
                ],
            ],
            'أدوات التعريف' => [
                'writing' => [
                    'prompt' => "Fill in with a, an, the, or — (no article):\n1. \"___ apple a day keeps the doctor away.\"\n2. \"I saw ___ dog in the park.\"\n3. \"___ sun rises in the east.\"\n4. \"She is ___ honest woman.\"\n5. \"He is ___ engineer.\"\n6. \"I want to visit ___ Eiffel Tower.\"\n7. \"She bought ___ new car. ___ car is red.\"\n8. \"They went to ___ school by ___ bus.\"\n9. \"___ Mount Everest is the highest mountain.\"\n10. \"Can you play ___ guitar?\"",
                    'model_answer' => "1. An\n2. a\n3. The\n4. an\n5. an\n6. the\n7. a / The\n8. — / —\n9. —\n10. the",
                    'instructions' => 'Fill in each blank with the correct article or no article.',
                    'min_words' => 10, 'max_words' => 50,
                ],
                'speaking' => [
                    'sentence_1' => 'A book. A car. A dog. Use a before consonant sounds.',
                    'sentence_2' => 'An apple. An egg. An hour. Use an before vowel sounds.',
                    'sentence_3' => 'The sun. The moon. The Nile. Use the for specific things.',
                    'sentences_json' => [
                        'An honest man. The h is silent so we use an.',
                        'An hour is sixty minutes. Silent h means vowel sound.',
                        'She bought a new car. The car is very fast.',
                        'I want to visit the Eiffel Tower. Famous places use the.',
                        'Mount Everest is the tallest mountain. No article before proper nouns.',
                        'Can you play the guitar? Musical instruments use the.',
                        'They went to school by bus. No article in some fixed phrases.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'a', 'pronunciation' => 'Before consonant sounds', 'meaning_ar' => 'أداة نكرة'],
                        ['word' => 'an', 'pronunciation' => 'Before vowel sounds', 'meaning_ar' => 'أداة نكرة'],
                        ['word' => 'the', 'pronunciation' => 'Specific / unique things', 'meaning_ar' => 'أداة تعريف'],
                        ['word' => 'honest', 'pronunciation' => 'ON-ist (silent h)', 'meaning_ar' => 'صادق'],
                        ['word' => 'hour', 'pronunciation' => 'OW-er (silent h)', 'meaning_ar' => 'ساعة'],
                    ],
                ],
            ],
            'الفاعل' => [
                'writing' => [
                    'prompt' => "Identify the subject or write a sentence:\n1. \"Ahmed runs every morning.\" — Identify the subject\n2. \"The old woman smiled.\" — Identify the subject\n3. \"Running is good for health.\" — Identify the subject\n4. \"It is raining outside.\" — Identify the subject\n5. \"Ali and Omar are best friends.\" — Identify the subject\n6. Write a sentence where \"The students\" is the subject\n7. Write a sentence where \"Cooking\" is the subject\n8. Write a sentence where \"She\" is the subject\n9. Write a sentence where \"My brother and I\" is the subject\n10. Write a sentence where \"The team\" is the subject",
                    'model_answer' => "1. Ahmed\n2. The old woman\n3. Running\n4. It\n5. Ali and Omar\n6. The students finished their homework.\n7. Cooking is my favourite hobby.\n8. She loves reading books.\n9. My brother and I went to the market.\n10. The team won the championship.",
                    'instructions' => 'Identify the subject or write a sentence using the given subject.',
                    'min_words' => 20, 'max_words' => 120,
                ],
                'speaking' => [
                    'sentence_1' => 'Ahmed runs every morning. Ahmed is the subject.',
                    'sentence_2' => 'The old woman smiled. The old woman is the subject.',
                    'sentence_3' => 'Running is good for health. Running is the subject.',
                    'sentences_json' => [
                        'It is raining outside. It is the subject.',
                        'Ali and Omar are best friends. Ali and Omar is a compound subject.',
                        'The students finished their homework.',
                        'Cooking is my favourite hobby.',
                        'She loves reading books.',
                        'My brother and I went to the market.',
                        'The team won the championship.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'Subject', 'pronunciation' => 'SUB-ject — doer of action', 'meaning_ar' => 'الفاعل'],
                        ['word' => 'Compound subject', 'pronunciation' => 'Two or more subjects', 'meaning_ar' => 'فاعل مركب'],
                        ['word' => 'Gerund subject', 'pronunciation' => 'Verb-ing as subject', 'meaning_ar' => 'اسم فعل كفاعل'],
                        ['word' => 'Dummy subject', 'pronunciation' => 'It/There as placeholder', 'meaning_ar' => 'فاعل شكلي'],
                    ],
                ],
            ],
            'تصريف الأفعال' => [
                'writing' => [
                    'prompt' => "Conjugate the verbs:\n1. Conjugate \"to be\" — I ___\n2. Conjugate \"to be\" — She ___\n3. Conjugate \"to be\" — They ___\n4. Conjugate \"to go\" in present simple — He ___\n5. Conjugate \"to study\" in present simple — We ___\n6. Conjugate \"to run\" in past simple — She ___\n7. Conjugate \"to eat\" in past simple — They ___\n8. Conjugate \"to finish\" in present perfect — He ___\n9. Conjugate \"to go\" in future — I ___\n10. Write V1/V2/V3 for: drink",
                    'model_answer' => "1. am\n2. is\n3. are\n4. goes\n5. study\n6. ran\n7. ate\n8. has finished\n9. will go\n10. drink / drank / drunk",
                    'instructions' => 'Write the correct conjugation of each verb.',
                    'min_words' => 10, 'max_words' => 60,
                ],
                'speaking' => [
                    'sentence_1' => 'I am, you are, he is, she is, it is.',
                    'sentence_2' => 'We are, they are. I was, you were, he was.',
                    'sentence_3' => 'I go, he goes, they go.',
                    'sentences_json' => [
                        'Past: I went, she went, they went.',
                        'We study every day. She studies every day.',
                        'She ran five kilometres yesterday.',
                        'They ate dinner at seven o\'clock.',
                        'He has finished his homework.',
                        'I will go to the gym tomorrow.',
                        'drink, drank, drunk. eat, ate, eaten. run, ran, run.',
                    ],
                    'vocabulary_json' => [
                        ['word' => 'conjugate', 'pronunciation' => 'KON-joo-gayt', 'meaning_ar' => 'يصرّف'],
                        ['word' => 'irregular', 'pronunciation' => 'ir-REG-yoo-lar', 'meaning_ar' => 'شاذ'],
                        ['word' => 'tense', 'pronunciation' => 'Tens', 'meaning_ar' => 'زمن'],
                        ['word' => 'goes', 'pronunciation' => 'Add es for go with he/she', 'meaning_ar' => 'يذهب'],
                        ['word' => 'studies', 'pronunciation' => 'y → ies for he/she', 'meaning_ar' => 'يدرس'],
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
        $this->command->info("🎉 Part 2 Done! {$count} levels.");
    }
}
