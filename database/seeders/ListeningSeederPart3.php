<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\ListeningExercise;

class ListeningSeederPart3 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'Linking' => [
                ['type' => 'mcq', 'prompt' => '"I was tired, ___ I kept working." — contrast', 'options' => ['so', 'because', 'but', 'and'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She studied hard, ___ she passed." — result', 'options' => ['although', 'however', 'so', 'but'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ it was raining, we went outside."', 'options' => ['Because', 'Although', 'So', 'And'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"He didn\'t eat ___ he was hungry." — reason', 'options' => ['although', 'so', 'because', 'but'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I like coffee ___ I don\'t like tea." — contrast', 'correct_answer' => 'but', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ of the storm, the match was cancelled."', 'correct_answer' => 'Because', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She is smart; ___, she works hard." — addition', 'correct_answer' => 'furthermore', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He was late; ___, he missed the bus." — result', 'correct_answer' => 'therefore', 'accept_variants' => []],
            ],
            'Present Simple' => [
                ['type' => 'mcq', 'prompt' => '"She ___ to school every day."', 'options' => ['go', 'goes', 'going', 'gone'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ___ football on weekends."', 'options' => ['plays', 'playing', 'play', 'played'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"The sun ___ in the east."', 'options' => ['rise', 'rising', 'risen', 'rises'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"He ___ not like spicy food."', 'options' => ['do', 'does', 'is', 'did'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"I speak English every day at work." — write sentence', 'correct_answer' => 'I speak English every day at work.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She drinks coffee every morning before school." — write sentence', 'correct_answer' => 'She drinks coffee every morning before school.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Does he play tennis on Fridays?" — write sentence', 'correct_answer' => 'Does he play tennis on Fridays?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They do not work on public holidays." — write sentence', 'correct_answer' => 'They do not work on public holidays.', 'accept_variants' => []],
            ],
            'Present Continuous' => [
                ['type' => 'mcq', 'prompt' => '"She ___ right now."', 'options' => ['studies', 'studied', 'is studying', 'study'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"They ___ football at the moment."', 'options' => ['play', 'played', 'are playing', 'plays'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ you listening to me?"', 'options' => ['Do', 'Did', 'Are', 'Is'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He ___ not working today."', 'options' => ['do', 'does', 'is', 'are'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I am reading a very interesting book right now."', 'correct_answer' => 'I am reading a very interesting book right now.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She is listening to music in her room."', 'correct_answer' => 'She is listening to music in her room.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Are they coming to the party tonight?"', 'correct_answer' => 'Are they coming to the party tonight?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He is not eating because he is not hungry."', 'correct_answer' => 'He is not eating because he is not hungry.', 'accept_variants' => []],
            ],
            'Present Perfect' => [
                ['type' => 'mcq', 'prompt' => '"She ___ already eaten."', 'options' => ['has', 'have', 'had', 'is'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"I ___ never been to Japan."', 'options' => ['has', 'had', 'have', 'am'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"They ___ just arrived."', 'options' => ['has', 'is', 'was', 'have'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"He ___ finished his homework."', 'options' => ['have', 'had', 'is', 'has'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"I have eaten breakfast already this morning."', 'correct_answer' => 'I have eaten breakfast already this morning.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She has visited Paris twice in her life."', 'correct_answer' => 'She has visited Paris twice in her life.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Have you ever tried Japanese food before?"', 'correct_answer' => 'Have you ever tried Japanese food before?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They have not finished the project yet."', 'correct_answer' => 'They have not finished the project yet.', 'accept_variants' => []],
            ],
            'Present Perfect Continuous' => [
                ['type' => 'mcq', 'prompt' => '"She ___ for two hours."', 'options' => ['has been studying', 'have been studying', 'is studying', 'studied'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"They ___ since morning."', 'options' => ['has been working', 'are working', 'have been working', 'worked'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"How long ___ you been waiting?"', 'options' => ['did', 'do', 'have', 'has'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"It ___ been raining all day."', 'options' => ['have', 'is', 'has', 'was'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I have been studying English for three hours without a break."', 'correct_answer' => 'I have been studying English for three hours without a break.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She has been running every morning since January."', 'correct_answer' => 'She has been running every morning since January.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Has he been sleeping all afternoon?"', 'correct_answer' => 'Has he been sleeping all afternoon?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They have not been working together for very long."', 'correct_answer' => 'They have not been working together for very long.', 'accept_variants' => []],
            ],
            'Past Simple' => [
                ['type' => 'mcq', 'prompt' => '"She ___ to school yesterday."', 'options' => ['go', 'goes', 'going', 'went'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"He ___ a book last night."', 'options' => ['read', 'reads', 'reading', 'readed'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"___ you see that movie?"', 'options' => ['Do', 'Did', 'Does', 'Have'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ___ not come to the party."', 'options' => ['does', 'do', 'did', 'have'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I ate a delicious burger for lunch yesterday."', 'correct_answer' => 'I ate a delicious burger for lunch yesterday.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She wrote a long letter to her friend last week."', 'correct_answer' => 'She wrote a long letter to her friend last week.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Did he call you after the meeting?"', 'correct_answer' => 'Did he call you after the meeting?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They did not sleep well because of the noise."', 'correct_answer' => 'They did not sleep well because of the noise.', 'accept_variants' => []],
            ],
            'Past Continuous' => [
                ['type' => 'mcq', 'prompt' => '"She ___ when I called."', 'options' => ['sleeps', 'slept', 'was sleeping', 'sleep'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"They ___ football at 5 p.m."', 'options' => ['played', 'were playing', 'are playing', 'play'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"___ he working when you arrived?"', 'options' => ['Did', 'Does', 'Was', 'Is'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"I ___ not listening carefully."', 'options' => ['did', 'was', 'were', 'am'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"She was cooking dinner when the phone suddenly rang."', 'correct_answer' => 'She was cooking dinner when the phone suddenly rang.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They were studying together all evening in the library."', 'correct_answer' => 'They were studying together all evening in the library.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Were you watching TV when I knocked on the door?"', 'correct_answer' => 'Were you watching TV when I knocked on the door?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He was not driving fast when the accident happened."', 'correct_answer' => 'He was not driving fast when the accident happened.', 'accept_variants' => []],
            ],
            'Past Perfect' => [
                ['type' => 'mcq', 'prompt' => '"She ___ already left when I arrived."', 'options' => ['has', 'had', 'have', 'was'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"By the time he called, I ___ eaten."', 'options' => ['have', 'has', 'had', 'was'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ they finished before noon?"', 'options' => ['Did', 'Have', 'Has', 'Had'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"He ___ not seen her before that day."', 'options' => ['have', 'has', 'had', 'did'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I had finished all my work by the time she arrived."', 'correct_answer' => 'I had finished all my work by the time she arrived.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She had never seen snow before she moved to Canada."', 'correct_answer' => 'She had never seen snow before she moved to Canada.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Had he left the office before you got there?"', 'correct_answer' => 'Had he left the office before you got there?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They had not eaten anything when we arrived at the restaurant."', 'correct_answer' => 'They had not eaten anything when we arrived at the restaurant.', 'accept_variants' => []],
            ],
            'Past Perfect Continuous' => [
                ['type' => 'mcq', 'prompt' => '"She ___ for an hour before the bus came."', 'options' => ['has been waiting', 'had been waiting', 'was waiting', 'waited'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ___ all night when the storm stopped."', 'options' => ['were working', 'have been working', 'had been working', 'worked'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"How long ___ he been studying before the exam?"', 'options' => ['has', 'had', 'did', 'was'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"I ___ not been feeling well for days."', 'options' => ['have', 'has', 'had', 'was'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"I had been running for an hour before it started to rain."', 'correct_answer' => 'I had been running for an hour before it started to rain.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She had been working at that company for ten years before she quit."', 'correct_answer' => 'She had been working at that company for ten years before she quit.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Had they been arguing for a long time before you arrived?"', 'correct_answer' => 'Had they been arguing for a long time before you arrived?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He had not been sleeping well for several weeks before seeing the doctor."', 'correct_answer' => 'He had not been sleeping well for several weeks before seeing the doctor.', 'accept_variants' => []],
            ],
            'Future Simple' => [
                ['type' => 'mcq', 'prompt' => '"She ___ visit us tomorrow."', 'options' => ['is', 'will', 'would', 'shall'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ___ not come to the meeting."', 'options' => ['will', 'would', 'won\'t', 'aren\'t'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ you help me with this?"', 'options' => ['Do', 'Did', 'Will', 'Would'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"I think it ___ rain today."', 'options' => ['is', 'will', 'would', 'shall'], 'correct_index' => 1],
                ['type' => 'dictation', 'prompt' => '"I will call you as soon as I get home."', 'correct_answer' => 'I will call you as soon as I get home.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She will not come to work tomorrow because she is sick."', 'correct_answer' => 'She will not come to work tomorrow because she is sick.', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Will he finish the report on time?"', 'correct_answer' => 'Will he finish the report on time?', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They will travel to Paris next month for their holiday."', 'correct_answer' => 'They will travel to Paris next month for their holiday.', 'accept_variants' => []],
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
        $this->command->info("🎉 Part 3 Done! Processed {$count} levels.");
    }
}
