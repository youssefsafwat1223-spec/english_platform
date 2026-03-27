<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_note_updates_existing_note_instead_of_creating_duplicate(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson One',
            'slug' => 'lesson-one',
            'order_index' => 1,
            'has_quiz' => false,
        ]);
        $note = UserNote::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'note_text' => 'Old note',
        ]);

        $response = $this->actingAs($user)->postJson(route('student.notes.store'), [
            'note_id' => $note->id,
            'lesson_id' => $lesson->id,
            'note_text' => 'Updated note text',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseCount('user_notes', 1);
        $this->assertDatabaseHas('user_notes', [
            'id' => $note->id,
            'note_text' => 'Updated note text',
        ]);
    }

    public function test_note_show_page_uses_note_text_accessor_and_delete_redirects(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson Two',
            'slug' => 'lesson-two',
            'order_index' => 2,
            'has_quiz' => false,
        ]);
        $note = UserNote::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'note_text' => "Important detail\nSecond line",
        ]);

        $this->actingAs($user)
            ->get(route('student.notes.show', $note))
            ->assertOk()
            ->assertSee('Important detail')
            ->assertSee('Second line');

        $this->actingAs($user)
            ->delete(route('student.notes.delete', $note))
            ->assertRedirect(route('student.notes.index'));

        $this->assertSoftDeleted('user_notes', [
            'id' => $note->id,
        ]);
    }
}
