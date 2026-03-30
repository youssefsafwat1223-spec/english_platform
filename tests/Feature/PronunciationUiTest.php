<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\PronunciationExercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PronunciationUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_pronunciation_page_uses_word_sentence_passage_labels_and_hides_raw_aborted_message(): void
    {
        app()->setLocale('en');

        $user = User::factory()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Silent E Rule',
            'slug' => 'silent-e-rule',
            'order_index' => 1,
            'has_pronunciation_exercise' => true,
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

        $exercise = PronunciationExercise::create([
            'lesson_id' => $lesson->id,
            'sentence_1' => 'cake',
            'sentence_2' => 'The cake tastes sweet.',
            'sentence_3' => 'In this case, the silent E makes the vowel before it long.',
            'passing_score' => 70,
            'max_duration_seconds' => 10,
            'allow_retake' => true,
        ]);

        $response = $this->actingAs($user)->get(route('student.pronunciation.show', $exercise));

        $response->assertOk();
        $response->assertSee('Word', false);
        $response->assertSee('Sentence', false);
        $response->assertSee('Passage', false);
        $response->assertSee('dir="ltr"', false);
        $response->assertDontSee('Sentence 1', false);
        $response->assertDontSee('Sentence 2', false);
        $response->assertDontSee('Sentence 3', false);
        $response->assertDontSee('Speech recognition error:', false);
    }

    public function test_admin_lesson_forms_use_word_sentence_passage_labels_for_pronunciation_fields(): void
    {
        app()->setLocale('en');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Pronunciation Lesson',
            'slug' => 'pronunciation-lesson',
            'order_index' => 1,
            'has_pronunciation_exercise' => true,
            'is_free' => false,
        ]);

        PronunciationExercise::create([
            'lesson_id' => $lesson->id,
            'sentence_1' => 'apple',
            'sentence_2' => 'The apple is red.',
            'sentence_3' => 'I eat an apple every morning before class.',
            'passing_score' => 70,
            'max_duration_seconds' => 10,
            'allow_retake' => true,
        ]);

        $createResponse = $this->actingAs($admin)->get(route('admin.courses.lessons.create', $course));
        $editResponse = $this->actingAs($admin)->get(route('admin.courses.lessons.edit', [$course, $lesson]));

        $createResponse->assertOk();
        $createResponse->assertSee('Word *', false);
        $createResponse->assertSee('Passage', false);
        $createResponse->assertDontSee('Sentence 1', false);

        $editResponse->assertOk();
        $editResponse->assertSee('Word *', false);
        $editResponse->assertSee('Passage', false);
        $editResponse->assertDontSee('Sentence 1', false);
    }
}
