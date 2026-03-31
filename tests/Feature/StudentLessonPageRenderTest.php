<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\PronunciationExercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLessonPageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_lesson_page_renders_text_content_image_tokens_and_completed_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson Render Test',
            'slug' => 'lesson-render-test',
            'description' => 'Lesson description',
            'text_content' => "Intro text\n[IMG:sample-card.png]\nClosing text",
            'order_index' => 1,
            'has_quiz' => false,
            'is_free' => false,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 100,
            'completed_lessons' => 1,
            'total_lessons' => 1,
        ]);

        LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
            'is_completed' => true,
        ]);

        PronunciationExercise::create([
            'lesson_id' => $lesson->id,
            'sentence_1' => 'cake',
            'sentence_2' => 'The cake tastes sweet.',
            'sentence_3' => 'A short passage.',
            'vocabulary_json' => [
                ['word' => 'cake', 'pronunciation' => '/keik/', 'meaning_ar' => 'كعكة'],
                ['word' => 'pine', 'pronunciation' => '/pain/', 'meaning_ar' => 'صنوبر'],
            ],
            'passing_score' => 70,
            'max_duration_seconds' => 10,
            'allow_retake' => true,
        ]);

        $response = $this->actingAs($user)->get(route('student.lessons.show', [$course, $lesson]));

        $response->assertOk();
        $response->assertSee('Lesson description');
        $response->assertSee('sample-card.png');
        $response->assertSee('images/features/sample-card.png');
        $response->assertSee('cake');
        $response->assertSee('/keik/');
        $response->assertSee('lesson-vocabulary-track');
        $response->assertSee('مكتمل');
        $response->assertDontSee('مكتمل ?');
    }
}
