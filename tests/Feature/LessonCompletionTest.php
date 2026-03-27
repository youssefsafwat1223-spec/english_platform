<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LessonCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_cannot_be_completed_until_the_attached_quiz_is_passed(): void
    {
        [$user, $course, $lesson, $quiz, $enrollment] = $this->createLessonWithQuiz();

        LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->postJson(
            route('student.lessons.complete', [$course, $lesson])
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'error' => __('You must pass the lesson quiz before marking this lesson as completed.'),
            ]);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'is_completed' => false,
        ]);
    }

    public function test_passing_a_lesson_quiz_marks_the_lesson_as_completed_automatically(): void
    {
        Mail::fake();
        Http::fake();

        [$user, $course, $lesson, $quiz, $enrollment] = $this->createLessonWithQuiz();

        $question = Question::create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'question_text' => 'Choose A',
            'question_type' => 'multiple_choice',
            'option_a' => 'Correct',
            'option_b' => 'Wrong',
            'option_c' => 'Wrong 2',
            'option_d' => 'Wrong 3',
            'correct_answer' => 'A',
            'difficulty' => 'easy',
            'points' => 1,
            'has_audio' => false,
        ]);

        $quiz->questions()->attach($question->id, ['order_index' => 1]);

        $response = $this->actingAs($user)->postJson(route('student.quizzes.submit', $quiz), [
            'answers' => [
                [
                    'question_id' => $question->id,
                    'user_answer' => 'A',
                    'time_taken' => 5,
                ],
            ],
            'started_at' => now()->subMinute()->toIso8601String(),
            'completed_at' => now()->toIso8601String(),
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => true,
            ]);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
            'is_completed' => true,
        ]);
    }

    /**
     * @return array{0: User, 1: Course, 2: Lesson, 3: Quiz, 4: Enrollment}
     */
    private function createLessonWithQuiz(): array
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson With Quiz',
            'slug' => 'lesson-with-quiz',
            'order_index' => 1,
            'has_quiz' => false,
            'is_free' => false,
        ]);

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Lesson Quiz',
            'quiz_type' => 'lesson',
            'total_questions' => 1,
            'duration_minutes' => 10,
            'passing_score' => 70,
            'is_active' => true,
            'allow_retake' => true,
            'show_results_immediately' => true,
            'enable_audio' => false,
            'audio_auto_play' => false,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 2,
        ]);

        return [$user, $course, $lesson, $quiz, $enrollment];
    }
}
