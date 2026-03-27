<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTestimonialSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrolled_student_can_open_testimonial_form(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 99,
            'total_lessons' => 10,
        ]);

        $response = $this->actingAs($user)->get(route('student.testimonial.edit'));

        $response->assertOk();
        $response->assertSee('اكتب رأيك');
    }

    public function test_student_submission_creates_pending_testimonial_and_updates_same_record(): void
    {
        $user = User::factory()->create([
            'name' => 'Youssef Safwat',
            'avatar' => 'avatars/test.jpg',
        ]);
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 149,
            'total_lessons' => 8,
        ]);

        $this->actingAs($user)->post(route('student.testimonial.store'), [
            'role' => 'طالب جامعي',
            'rating' => 5,
            'content' => 'المنصة ساعدتني أذاكر بشكل أوضح، والدروس مرتبة جدًا وسهلة المتابعة.',
        ])->assertRedirect(route('student.testimonial.edit'));

        $testimonial = Testimonial::where('user_id', $user->id)->first();

        $this->assertNotNull($testimonial);
        $this->assertSame('Youssef Safwat', $testimonial->name);
        $this->assertSame('طالب جامعي', $testimonial->role);
        $this->assertSame(5, $testimonial->rating);
        $this->assertSame('avatars/test.jpg', $testimonial->avatar);
        $this->assertFalse($testimonial->is_active);

        $testimonial->update(['is_active' => true]);

        $this->actingAs($user)->post(route('student.testimonial.store'), [
            'role' => 'خريج',
            'rating' => 4,
            'content' => 'حدثت رأيي بعد فترة، وما زالت المنصة مفيدة جدًا في المراجعة اليومية.',
        ])->assertRedirect(route('student.testimonial.edit'));

        $this->assertDatabaseCount('testimonials', 1);
        $this->assertDatabaseHas('testimonials', [
            'user_id' => $user->id,
            'role' => 'خريج',
            'rating' => 4,
            'is_active' => false,
        ]);
    }

    public function test_student_without_enrollment_cannot_submit_testimonial(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('student.testimonial.store'), [
            'role' => 'طالب',
            'rating' => 5,
            'content' => 'رأي لا يجب أن يُقبل لأن المستخدم غير مشترك في أي كورس.',
        ])->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseCount('testimonials', 0);
    }
}
