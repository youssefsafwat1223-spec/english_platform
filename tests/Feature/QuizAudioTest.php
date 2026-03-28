<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizAudioTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_page_includes_audio_fallback_and_tracking_inputs(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Audio Quiz',
            'quiz_type' => 'final_exam',
            'total_questions' => 1,
            'duration_minutes' => 15,
            'passing_score' => 70,
            'is_active' => true,
            'allow_retake' => true,
            'show_results_immediately' => true,
            'enable_audio' => true,
            'audio_auto_play' => true,
        ]);

        $question = Question::create([
            'course_id' => $course->id,
            'question_text' => 'Choose the correct answer',
            'question_type' => 'multiple_choice',
            'option_a' => 'Correct',
            'option_b' => 'Wrong',
            'correct_answer' => 'A',
            'difficulty' => 'easy',
            'points' => 1,
            'has_audio' => false,
        ]);

        $quiz->questions()->attach($question->id, ['order_index' => 1]);

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('student.quizzes.start', $quiz));

        $response->assertOk();
        $response->assertSee('speechSynthesis', false);
        $response->assertSee('questionSpeechTexts', false);
        $response->assertSee('answers[0][audio_played]', false);
        $response->assertSee('answers[0][audio_replay_count]', false);
        $response->assertSeeText('استمع للسؤال');
    }
}
