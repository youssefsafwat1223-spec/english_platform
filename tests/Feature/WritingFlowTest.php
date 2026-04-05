<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\WritingExercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WritingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_writing_page_and_submit_answer_with_fallback_feedback(): void
    {
        app()->setLocale('en');

        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $user = User::factory()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Writing Lesson',
            'slug' => 'writing-lesson',
            'order_index' => 1,
            'has_writing_exercise' => true,
            'is_free' => false,
        ]);

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $exercise = WritingExercise::create([
            'lesson_id' => $lesson->id,
            'title' => 'Daily Routine',
            'prompt' => 'Write about your daily routine.',
            'instructions' => 'Use complete sentences.',
            'min_words' => 20,
            'max_words' => 120,
            'passing_score' => 70,
            'model_answer' => null,
            'rubric_json' => [
                'grammar' => 25,
                'vocabulary' => 25,
                'coherence' => 25,
                'task_completion' => 25,
            ],
        ]);

        $showResponse = $this->actingAs($user)->get(route('student.writing.show', $exercise));

        $showResponse->assertOk();
        $showResponse->assertSee('Task Prompt', false);
        $showResponse->assertSee('Submit Writing', false);
        $showResponse->assertSee('Daily Routine', false);

        $submitResponse = $this->actingAs($user)->postJson(route('student.writing.submit', $exercise), [
            'answer_text' => 'I wake up at six in the morning. I have breakfast with my family and then I go to work by bus every day.',
        ]);

        $submitResponse->assertOk();
        $submitResponse->assertJsonPath('success', true);
        $submitResponse->assertJsonPath('word_count', 24);
        $submitResponse->assertJsonStructure([
            'overall_score',
            'grammar_score',
            'vocabulary_score',
            'coherence_score',
            'task_score',
            'summary',
            'strengths',
            'improvements',
            'rewrite_suggestion',
            'grammar_issues',
            'passed',
        ]);

        $this->assertDatabaseHas('writing_submissions', [
            'writing_exercise_id' => $exercise->id,
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => 'evaluated',
        ]);
    }

    public function test_admin_lesson_forms_render_writing_fields(): void
    {
        app()->setLocale('en');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Admin Writing Lesson',
            'slug' => 'admin-writing-lesson',
            'order_index' => 1,
            'has_writing_exercise' => true,
            'is_free' => false,
        ]);

        WritingExercise::create([
            'lesson_id' => $lesson->id,
            'title' => 'Opinion Paragraph',
            'prompt' => 'Write your opinion about studying online.',
            'instructions' => 'Support your answer with examples.',
            'min_words' => 50,
            'max_words' => 150,
            'passing_score' => 70,
            'model_answer' => 'Online study can be flexible and practical.',
            'rubric_json' => [],
        ]);

        $createResponse = $this->actingAs($admin)->get(route('admin.courses.lessons.create', $course));
        $editResponse = $this->actingAs($admin)->get(route('admin.courses.lessons.edit', [$course, $lesson]));

        $createResponse->assertOk();
        $createResponse->assertSee('Has writing exercise', false);
        $createResponse->assertSee('Writing Title *', false);
        $createResponse->assertSee('Prompt *', false);

        $editResponse->assertOk();
        $editResponse->assertSee('Has writing exercise', false);
        $editResponse->assertSee('Writing Title *', false);
        $editResponse->assertSee('Model Answer', false);
    }
}
