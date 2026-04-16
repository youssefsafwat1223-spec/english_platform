<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\ListeningExercise;

class ListeningSeederPart1 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'الحروف الأبجدية' => [
                ['type' => 'mcq', 'prompt' => 'Bee', 'options' => ['A', 'B', 'D', 'P'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => 'Kay', 'options' => ['C', 'G', 'K', 'Q'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => 'Double-you', 'options' => ['U', 'V', 'W', 'Y'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => 'Aitch', 'options' => ['A', 'H', 'N', 'X'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"Jay" — write the capital letter', 'correct_answer' => 'J', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Queue" — write the small letter', 'correct_answer' => 'q', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Are" — write the capital letter', 'correct_answer' => 'R', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Zee" — write the small letter', 'correct_answer' => 'z', 'accept_variants' => []],
            ],
            'الحروف المركبة' => [
                ['type' => 'mcq', 'prompt' => '"sh" sound — as in "sheep"', 'options' => ['SK', 'SH', 'SP', 'ST'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"ch" sound — as in "chair"', 'options' => ['CK', 'CR', 'CH', 'CL'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"th" sound — as in "think"', 'options' => ['TR', 'TH', 'TP', 'TW'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"wh" sound — as in "whale"', 'options' => ['WH', 'WR', 'WL', 'WN'], 'correct_index' => 0],
                ['type' => 'dictation', 'prompt' => '"ng" — write the blend', 'correct_answer' => 'ng', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"ph" — write the blend', 'correct_answer' => 'ph', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"ck" — write the blend', 'correct_answer' => 'ck', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"qu" — write the blend', 'correct_answer' => 'qu', 'accept_variants' => []],
            ],
            'الحروف الصوتية' => [
                ['type' => 'mcq', 'prompt' => '"cat" — which vowel sound?', 'options' => ['e', 'i', 'a', 'o'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"bit" — which vowel sound?', 'options' => ['a', 'e', 'u', 'i'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"dog" — which vowel sound?', 'options' => ['a', 'o', 'u', 'e'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"cup" — which vowel sound?', 'options' => ['o', 'u', 'i', 'a'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"bed" — write the word', 'correct_answer' => 'bed', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"rain" — write the word', 'correct_answer' => 'rain', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"moon" — write the word', 'correct_answer' => 'moon', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"time" — write the word', 'correct_answer' => 'time', 'accept_variants' => []],
            ],
            'الأرقام' => [
                ['type' => 'mcq', 'prompt' => 'Fifteen', 'options' => ['50', '5', '15', '51'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => 'Forty-two', 'options' => ['24', '14', '42', '420'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => 'Seventy-seven', 'options' => ['17', '77', '707', '770'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => 'One hundred', 'options' => ['10', '110', '100', '1000'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"Eighty-nine" — write in digits', 'correct_answer' => '89', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Three hundred and sixty" — write in digits', 'correct_answer' => '360', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Two thousand" — write in digits', 'correct_answer' => '2000', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Five hundred and twelve" — write in digits', 'correct_answer' => '512', 'accept_variants' => []],
            ],
            'أقسام الكلام' => [
                ['type' => 'mcq', 'prompt' => '"The CAT is sleeping." — type of CAT?', 'options' => ['Verb', 'Adjective', 'Noun', 'Adverb'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She RUNS every day." — type of RUNS?', 'options' => ['Noun', 'Verb', 'Adjective', 'Adverb'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"The BEAUTIFUL flowers." — type of BEAUTIFUL?', 'options' => ['Adverb', 'Noun', 'Adjective', 'Verb'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He spoke QUICKLY." — type of QUICKLY?', 'options' => ['Adjective', 'Noun', 'Verb', 'Adverb'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"The book is ON the table." — write type', 'correct_answer' => 'Preposition', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"SHE likes coffee." — write type', 'correct_answer' => 'Pronoun', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"I was tired BUT I finished." — write type', 'correct_answer' => 'Conjunction', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"The sky is BLUE." — write type', 'correct_answer' => 'Adjective', 'accept_variants' => []],
            ],
            'علامات الترقيم' => [
                ['type' => 'mcq', 'prompt' => '"What is your name ?" — what punctuation ends this?', 'options' => ['Period', 'Comma', 'Question mark', 'Exclamation'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"Stop! Don\'t move!" — what punctuation is used?', 'options' => ['Period', 'Exclamation', 'Comma', 'Colon'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"I bought apples, oranges, and bananas." — separates items?', 'options' => ['Semicolon', 'Hyphen', 'Comma', 'Colon'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She said: \'Hello!\'" — introduces the quote?', 'options' => ['Comma', 'Colon', 'Period', 'Dash'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"He is happy ___" — correct punctuation', 'correct_answer' => '.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Are you ready ___" — correct punctuation', 'correct_answer' => '?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Wow, that is amazing ___" — correct punctuation', 'correct_answer' => '!', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"I need three things ___" — correct punctuation', 'correct_answer' => ':', 'accept_variants' => []],
            ],
            'المفرد والجمع' => [
                ['type' => 'mcq', 'prompt' => '"One cat — two ___"', 'options' => ['cats', 'caties', 'cates', 'cat'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"One child — two ___"', 'options' => ['childs', 'childes', 'children', 'childrens'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"One box — three ___"', 'options' => ['boxs', 'boxes', 'boxies', 'boxen'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"One woman — four ___"', 'options' => ['womans', 'womens', 'wommen', 'women'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"One tooth — many ___" — plural', 'correct_answer' => 'teeth', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"One city — many ___" — plural', 'correct_answer' => 'cities', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"One mouse — many ___" — plural', 'correct_answer' => 'mice', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"One knife — many ___" — plural', 'correct_answer' => 'knives', 'accept_variants' => []],
            ],
            'أدوات التعريف' => [
                ['type' => 'mcq', 'prompt' => '"___ apple a day keeps the doctor away."', 'options' => ['A', 'An', 'The', '—'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"I saw ___ dog in the park." (first mention)', 'options' => ['an', 'the', 'a', '—'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ sun rises in the east."', 'options' => ['A', 'An', '—', 'The'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"She is ___ honest woman."', 'options' => ['a', 'the', 'an', '—'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"___ elephant is a large animal." — article', 'correct_answer' => 'An', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"I want to visit ___ Eiffel Tower." — article', 'correct_answer' => 'the', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He is ___ engineer." — article', 'correct_answer' => 'an', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She bought ___ new car." — article', 'correct_answer' => 'a', 'accept_variants' => []],
            ],
            'الفاعل' => [
                ['type' => 'mcq', 'prompt' => '"The teacher explains the lesson." — subject?', 'options' => ['lesson', 'explains', 'teacher', 'the'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"They play football every Friday." — subject?', 'options' => ['football', 'play', 'Friday', 'They'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"My brother and I went to the market." — subject?', 'options' => ['market', 'went', 'My brother and I', 'brother'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"Running is good for your health." — subject?', 'options' => ['good', 'Running', 'health', 'your'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"___ loves reading books." — hear: Sara', 'correct_answer' => 'Sara', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ are playing in the garden." — hear: The children', 'correct_answer' => 'The children', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ is raining outside."', 'correct_answer' => 'It', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ and ___ are best friends." — hear: Ali and Omar', 'correct_answer' => 'Ali and Omar', 'accept_variants' => []],
            ],
            'تصريف الأفعال' => [
                ['type' => 'mcq', 'prompt' => '"She ___ (to go) to school every day." — simple present', 'options' => ['go', 'goes', 'going', 'gone'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ___ (to watch) TV right now." — present continuous', 'options' => ['watch', 'watches', 'are watching', 'watched'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ (to finish) his homework yesterday." — past simple', 'options' => ['finish', 'finishes', 'finishing', 'finished'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"We ___ (to study) for two hours by noon." — future perfect', 'options' => ['will study', 'have studied', 'will have studied', 'studied'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I ___ (to be) happy." — present simple', 'correct_answer' => 'am', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She ___ (to run) fast." — present simple', 'correct_answer' => 'runs', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They ___ (to eat) dinner now." — present continuous', 'correct_answer' => 'are eating', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He ___ (to leave) last night." — past simple', 'correct_answer' => 'left', 'accept_variants' => []],
            ],
        ];

        $count = 0;
        foreach ($data as $searchKey => $questions) {
            $level = CourseLevel::where('course_id', $courseId)
                ->where('title', 'LIKE', "%{$searchKey}%")
                ->first();

            if ($level) {
                ListeningExercise::updateOrCreate(
                    ['course_level_id' => $level->id],
                    [
                        'title' => $level->title . ' — Listening',
                        'script_ar' => 'تمرين استماع',
                        'questions_json' => $questions,
                        'passing_score' => 70,
                    ]
                );
                $level->update(['has_listening_exercise' => true]);
                $this->command->info("✅ Added listening to: " . $level->title);
                $count++;
            } else {
                $this->command->warn("⚠ Not found: {$searchKey}");
            }
        }
        $this->command->info("🎉 Part 1 Done! Processed {$count} levels.");
    }
}
