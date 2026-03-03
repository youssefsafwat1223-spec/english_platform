<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Question;
use App\Models\Lesson;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::where('is_active', true)->get();

        if ($courses->isEmpty()) {
            $this->command->info('No active courses found. Please create courses first.');
            return;
        }

        foreach ($courses as $course) {
            $this->command->info("Adding questions for course: {$course->title}");
            
            // Try to find a lesson to attach questions to, or create a dummy one
            $lesson = $course->lessons()->first();
            
            $questions = [
                [
                    'question_text' => 'What is the past tense of "go"?',
                    'option_a' => 'Goed',
                    'option_b' => 'Gone',
                    'option_c' => 'Went',
                    'option_d' => 'Going',
                    'correct_answer' => 'C',
                    'points' => 10,
                ],
                [
                    'question_text' => 'Which word is a synonym for "happy"?',
                    'option_a' => 'Sad',
                    'option_b' => 'Joyful',
                    'option_c' => 'Angry',
                    'option_d' => 'Tired',
                    'correct_answer' => 'B',
                    'points' => 10,
                ],
                [
                    'question_text' => 'Choose the correct sentence:',
                    'option_a' => 'She dont like apples.',
                    'option_b' => 'She doesnt likes apples.',
                    'option_c' => 'She doesnt like apples.',
                    'option_d' => 'She dont likes apples.',
                    'correct_answer' => 'C',
                    'points' => 15,
                ],
                [
                    'question_text' => 'What is the plural of "child"?',
                    'option_a' => 'Childs',
                    'option_b' => 'Childrens',
                    'option_c' => 'Children',
                    'option_d' => 'Childes',
                    'correct_answer' => 'C',
                    'points' => 10,
                ],
                [
                    'question_text' => 'Which is a preposition?',
                    'option_a' => 'Table',
                    'option_b' => 'Run',
                    'option_c' => 'On',
                    'option_d' => 'Blue',
                    'correct_answer' => 'C',
                    'points' => 10,
                ],
                [
                    'question_text' => 'Identify the verb: "The cat sleeps on the mat."',
                    'option_a' => 'The',
                    'option_b' => 'Cat',
                    'option_c' => 'Sleeps',
                    'option_d' => 'Mat',
                    'correct_answer' => 'C',
                    'points' => 10,
                ],
                [
                    'question_text' => 'Which word is spelled correctly?',
                    'option_a' => 'Recieve',
                    'option_b' => 'Receive',
                    'option_c' => 'Receeve',
                    'option_d' => 'Recive',
                    'correct_answer' => 'B',
                    'points' => 20,
                ],
                [
                    'question_text' => '______ you like some coffee?',
                    'option_a' => 'Can',
                    'option_b' => 'Would',
                    'option_c' => 'Do',
                    'option_d' => 'Are',
                    'correct_answer' => 'B',
                    'points' => 15,
                ],
                [
                    'question_text' => 'He ______ to the store yesterday.',
                    'option_a' => 'Goes',
                    'option_b' => 'Gone',
                    'option_c' => 'Went',
                    'option_d' => 'Going',
                    'correct_answer' => 'C',
                    'points' => 10,
                ],
                [
                    'question_text' => 'The opposite of "Hot" is:',
                    'option_a' => 'Warm',
                    'option_b' => 'Cold',
                    'option_c' => 'Boiling',
                    'option_d' => 'Sunny',
                    'correct_answer' => 'B',
                    'points' => 5,
                ],
            ];

            foreach ($questions as $q) {
                // Check if question already exists to avoid duplicates
                $exists = Question::where('course_id', $course->id)
                    ->where('question_text', $q['question_text'])
                    ->exists();

                if (!$exists) {
                    Question::create([
                        'course_id' => $course->id,
                        'lesson_id' => $lesson ? $lesson->id : null,
                        'question_text' => $q['question_text'],
                        'question_type' => 'multiple_choice',
                        'option_a' => $q['option_a'],
                        'option_b' => $q['option_b'],
                        'option_c' => $q['option_c'],
                        'option_d' => $q['option_d'],
                        'correct_answer' => $q['correct_answer'],
                        'points' => $q['points'],
                        'difficulty' => 'easy',
                    ]);
                }
            }
        }
    }
}
