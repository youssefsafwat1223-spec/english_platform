<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminLessonPronunciationConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_pronunciation_vocabulary_explanations_and_reference_audio(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Pronunciation Setup Lesson',
            'description' => 'Testing pronunciation persistence.',
            'course_level_id' => null,
            'order_index' => 1,
            'is_free' => 0,
            'has_quiz' => 0,
            'has_pronunciation_exercise' => 1,
            'pronunciation_sentence_1' => 'cake',
            'pronunciation_sentence_2' => 'The cake tastes sweet.',
            'pronunciation_sentence_3' => 'The silent E makes the vowel long in this example.',
            'pronunciation_vocabulary_lines' => "cake | /keik/ | cake meaning\npine | /pain/ | pine meaning",
            'pronunciation_sentence_explanation' => 'Sentence explanation text.',
            'pronunciation_passage_explanation' => 'Passage explanation text.',
            'pronunciation_passing_score' => 80,
            'pronunciation_max_duration' => 15,
            'pronunciation_allow_retake' => 1,
            'pronunciation_reference_audio_1' => UploadedFile::fake()->create('word.mp3', 10, 'audio/mpeg'),
        ]);

        $response->assertRedirect(route('admin.courses.lessons.index', $course));

        $lesson = Lesson::where('title', 'Pronunciation Setup Lesson')->firstOrFail();
        $exercise = $lesson->pronunciationExercise()->first();

        $this->assertNotNull($exercise);
        $this->assertSame('cake', $exercise->sentence_1);
        $this->assertSame('Sentence explanation text.', $exercise->sentence_explanation);
        $this->assertSame('Passage explanation text.', $exercise->passage_explanation);
        $this->assertSame(80, $exercise->passing_score);
        $this->assertSame(15, $exercise->max_duration_seconds);
        $this->assertTrue($exercise->allow_retake);
        $this->assertCount(2, $exercise->vocabulary_json);
        $this->assertSame('cake', $exercise->vocabulary_json[0]['word']);
        $this->assertSame('/keik/', $exercise->vocabulary_json[0]['pronunciation']);
        $this->assertSame('cake meaning', $exercise->vocabulary_json[0]['meaning_ar']);
        $this->assertNotNull($exercise->reference_audio_1);

        Storage::disk('public')->assertExists($exercise->reference_audio_1);
    }
}
