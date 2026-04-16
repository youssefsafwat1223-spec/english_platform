<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\ListeningExercise;

class ListeningSeederPart4 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'Future Continuous' => [
                ['type' => 'mcq', 'prompt' => '"At 8 p.m., she ___ dinner."', 'options' => ['will eat', 'will be eating', 'is eating', 'eats'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"This time tomorrow, they ___."', 'options' => ['study', 'will study', 'will be studying', 'are studying'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ you be working on Saturday?"', 'options' => ['Do', 'Are', 'Will', 'Have'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ not be attending."', 'options' => ['is', 'will', 'does', 'has'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"I will be sleeping by midnight so please do not call."', 'correct_answer' => 'I will be sleeping by midnight so please do not call.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She will be driving to work at seven in the morning."', 'correct_answer' => 'She will be driving to work at seven in the morning.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Will they be joining us for dinner this evening?"', 'correct_answer' => 'Will they be joining us for dinner this evening?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He will not be working tomorrow evening due to the holiday."', 'correct_answer' => 'He will not be working tomorrow evening due to the holiday.', 'accept_variants' => []],
            ],
            'Future Perfect' => [
                ['type' => 'mcq', 'prompt' => '"By 5 p.m., she ___ the report."', 'options' => ['will finish', 'will have finished', 'finishes', 'has finished'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"By next year, they ___ the project."', 'options' => ['will complete', 'have completed', 'will have completed', 'completed'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ you have left by noon?"', 'options' => ['Do', 'Have', 'Will', 'Are'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ not have arrived by then."', 'options' => ['has', 'will', 'would', 'is'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"I will have finished reading this book by Friday."', 'correct_answer' => 'I will have finished reading this book by Friday.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She will have left the office before you arrive."', 'correct_answer' => 'She will have left the office before you arrive.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Will he have eaten dinner by the time we get there?"', 'correct_answer' => 'Will he have eaten dinner by the time we get there?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They will not have completed the project by Monday."', 'correct_answer' => 'They will not have completed the project by Monday.', 'accept_variants' => []],
            ],
            'Future Perfect Continuous' => [
                ['type' => 'mcq', 'prompt' => '"By next month, she ___ here for a year."', 'options' => ['will work', 'will be working', 'will have been working', 'has been working'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"By 6 p.m., I ___ for three hours."', 'options' => ['will study', 'will have been studying', 'have been studying', 'will be studying'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"___ they have been waiting long?"', 'options' => ['Have', 'Are', 'Will', 'Do'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ not have been sleeping well."', 'options' => ['has', 'will', 'is', 'does'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"I will have been running for an hour by the time you arrive."', 'correct_answer' => 'I will have been running for an hour by the time you arrive.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She will have been teaching for twenty years by 2030."', 'correct_answer' => 'She will have been teaching for twenty years by 2030.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Will he have been driving for long by then?"', 'correct_answer' => 'Will he have been driving for long by then?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They will not have been waiting long."', 'correct_answer' => 'They will not have been waiting long.', 'accept_variants' => []],
            ],
            'Imperative' => [
                ['type' => 'mcq', 'prompt' => '"___ the door, please."', 'options' => ['Opens', 'Opening', 'Open', 'Opened'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ run in the hallway!"', 'options' => ['Not', 'Don\'t', 'Doesn\'t', 'No'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"___ your homework before TV."', 'options' => ['Does', 'Did', 'Doing', 'Do'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"___ quiet in the library!"', 'options' => ['Be', 'Are', 'Is', 'Being'], 'correct_index' => 0],
                ['type' => 'dictation', 'prompt' => '"___ your hands before eating."', 'correct_answer' => 'Wash', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ talk during the exam!"', 'correct_answer' => 'Don\'t', 'accept_variants' => ['Do not']],
                ['type' => 'dictation', 'prompt' => '"___ a seat, please."', 'correct_answer' => 'Take', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ careful on the road."', 'correct_answer' => 'Be', 'accept_variants' => []],
            ],
            'Modal Verbs' => [
                ['type' => 'mcq', 'prompt' => '"You ___ see a doctor — urgent."', 'options' => ['should', 'could', 'must', 'can'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"You ___ exercise more — advice."', 'options' => ['must', 'should', 'could', 'can'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"___ you swim when young?"', 'options' => ['Can', 'Must', 'Could', 'Should'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"You ___ better leave now."', 'options' => ['had', 'should', 'must', 'could'], 'correct_index' => 0],
                ['type' => 'dictation', 'prompt' => '"You ___ study." — strong advice', 'correct_answer' => 'should', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She ___ speak four languages."', 'correct_answer' => 'can', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"You ___ better hurry!"', 'correct_answer' => 'had', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"You ___ not smoke here."', 'correct_answer' => 'must', 'accept_variants' => []],
            ],
            'Wh-' => [
                ['type' => 'mcq', 'prompt' => '"___ is your name?"', 'options' => ['Where', 'When', 'What', 'Which'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ did you go yesterday?"', 'options' => ['What', 'Who', 'Where', 'How'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ gave you that gift?"', 'options' => ['What', 'Whom', 'Who', 'Which'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ does the movie start?"', 'options' => ['Where', 'Why', 'Who', 'When'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"___ are you crying?" — reason', 'correct_answer' => 'Why', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ do you feel today?"', 'correct_answer' => 'How', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ colour do you prefer?"', 'correct_answer' => 'Which', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ are you talking to?"', 'correct_answer' => 'Whom', 'accept_variants' => ['Who']],
            ],
            'Comparison' => [
                ['type' => 'mcq', 'prompt' => '"She is ___ than her sister."', 'options' => ['tall', 'tallest', 'taller', 'most tall'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"This is the ___ book ever."', 'options' => ['more interesting', 'interestingest', 'most interesting', 'interesting'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He is ___ strong ___ his brother."', 'options' => ['so / as', 'more / than', 'as / as', 'the / as'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"This road is ___ than that one."', 'options' => ['long', 'longest', 'most long', 'longer'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"the ___ (smart) student" — superlative', 'correct_answer' => 'smartest', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"This car is ___ (expensive) than mine."', 'correct_answer' => 'more expensive', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"as fast as" — write the missing words', 'correct_answer' => 'as / as', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Today is the ___ (hot) day." — superlative', 'correct_answer' => 'hottest', 'accept_variants' => ['the hottest']],
            ],
            'Quantifiers' => [
                ['type' => 'mcq', 'prompt' => '"There is ___ milk." — uncountable', 'options' => ['many', 'few', 'a little', 'a few'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"There are ___ students." — large', 'options' => ['much', 'many', 'little', 'a little'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"I have ___ money left." — small', 'options' => ['few', 'a few', 'little', 'many'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She has ___ friends." — small positive', 'options' => ['little', 'much', 'a little', 'a few'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"not ___ time left" — uncountable', 'correct_answer' => 'much', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"I have ___ books." — large number', 'correct_answer' => 'many', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She needs ___ help." — small', 'correct_answer' => 'a little', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ students passed." — some', 'correct_answer' => 'A few', 'accept_variants' => []],
            ],
            'Delexical' => [
                ['type' => 'mcq', 'prompt' => '"She ___ a photo."', 'options' => ['did', 'made', 'took', 'had'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ a mistake."', 'options' => ['took', 'did', 'had', 'made'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"They ___ a good time."', 'options' => ['made', 'took', 'did', 'had'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"Can you ___ me a favour?"', 'options' => ['make', 'do', 'take', 'have'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"She ___ a shower every morning."', 'correct_answer' => 'takes', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They ___ a decision together."', 'correct_answer' => 'made', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He ___ his best on the exam."', 'correct_answer' => 'did', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"We ___ lunch at noon."', 'correct_answer' => 'had', 'accept_variants' => []],
            ],
            'There is' => [
                ['type' => 'mcq', 'prompt' => '"___ a cat on the roof."', 'options' => ['There are', 'There is', 'There was', 'There were'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"___ many problems."', 'options' => ['There is', 'There was', 'There are', 'There were'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ any milk?" — question', 'options' => ['Is there', 'Are there', 'Was there', 'Were there'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"___ not enough chairs."', 'options' => ['There is', 'There are', 'There isn\'t', 'There aren\'t'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"___ a problem with the system."', 'correct_answer' => 'There is', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ many options available."', 'correct_answer' => 'There are', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ any sugar left?"', 'correct_answer' => 'Is there', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ enough rooms." — negative', 'correct_answer' => 'There aren\'t', 'accept_variants' => ['There are not']],
            ],
            'الوقت' => [
                ['type' => 'mcq', 'prompt' => '"quarter past three" — time?', 'options' => ['3:45', '2:45', '3:15', '3:30'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"half past seven" — time?', 'options' => ['7:15', '7:45', '6:30', '7:30'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"ten to five" — time?', 'options' => ['5:10', '4:50', '4:10', '5:50'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"noon" — time?', 'options' => ['12:00 p.m.', '12:00 a.m.', '11:00 a.m.', '1:00 p.m.'], 'correct_index' => 0],
                ['type' => 'dictation', 'prompt' => '"twenty past six" — digits', 'correct_answer' => '6:20', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"five to nine" — digits', 'correct_answer' => '8:55', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"quarter to two" — digits', 'correct_answer' => '1:45', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"midnight" — digits', 'correct_answer' => '12:00 a.m.', 'accept_variants' => ['12:00']],
            ],
            'التاريخ' => [
                ['type' => 'mcq', 'prompt' => '"3rd March 2020" — in numbers?', 'options' => ['3/3/2020', '3/2/2020', '30/3/2020', '3/3/2002'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"January is the ___ month."', 'options' => ['second', 'third', 'first', 'fourth'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"Day after Wednesday?"', 'options' => ['Monday', 'Tuesday', 'Thursday', 'Friday'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"Month after July?"', 'options' => ['June', 'August', 'September', 'October'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"15th August 1947" — digits', 'correct_answer' => '15/8/1947', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"sixth month?" — name', 'correct_answer' => 'June', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Day before Friday?"', 'correct_answer' => 'Thursday', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Last month of the year?"', 'correct_answer' => 'December', 'accept_variants' => []],
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
                    ['title' => $level->title . ' — Listening', 'script_ar' => 'تمرين استماع', 'questions_json' => $questions, 'passing_score' => 70]
                );
                $level->update(['has_listening_exercise' => true]);
                $this->command->info("✅ " . $level->title);
                $count++;
            } else {
                $this->command->warn("⚠ Not found: {$searchKey}");
            }
        }
        $this->command->info("🎉 Part 4 Done! {$count} levels.");
    }
}
