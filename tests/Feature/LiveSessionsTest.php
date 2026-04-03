<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LiveSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_only_sees_live_sessions_for_enrolled_courses(): void
    {
        $student = User::factory()->create();
        $enrolledCourse = Course::factory()->create(['title' => 'Enrolled Course']);
        $otherCourse = Course::factory()->create(['title' => 'Other Course']);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $enrolledCourse->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $visibleSession = LiveSession::create([
            'title' => 'Visible Session',
            'slug' => 'visible-session',
            'zoom_join_url' => 'https://example.com/zoom-visible',
            'starts_at' => now()->addHours(2),
            'ends_at' => now()->addHours(3),
            'status' => LiveSession::STATUS_SCHEDULED,
        ]);
        $visibleSession->courses()->attach($enrolledCourse);

        $hiddenSession = LiveSession::create([
            'title' => 'Hidden Session',
            'slug' => 'hidden-session',
            'zoom_join_url' => 'https://example.com/zoom-hidden',
            'starts_at' => now()->addHours(2),
            'ends_at' => now()->addHours(3),
            'status' => LiveSession::STATUS_SCHEDULED,
        ]);
        $hiddenSession->courses()->attach($otherCourse);

        $response = $this->actingAs($student)->get(route('student.live-sessions.index'));

        $response->assertOk();
        $response->assertSeeText('Visible Session');
        $response->assertDontSeeText('Hidden Session');
    }

    public function test_dashboard_prioritizes_live_session_banner_over_promo_banner(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create(['title' => 'Banner Course']);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        \App\Models\SystemSetting::set('dashboard_promo_title', 'Promo Banner', 'string', 'general');
        \App\Models\SystemSetting::set('dashboard_promo_message', 'Promo Message', 'string', 'general');
        \App\Models\SystemSetting::set('dashboard_promo_url', 'https://example.com/promo', 'string', 'general');

        $liveSession = LiveSession::create([
            'title' => 'Priority Live Session',
            'slug' => 'priority-live-session',
            'zoom_join_url' => 'https://example.com/zoom-live',
            'starts_at' => now()->addMinutes(30),
            'ends_at' => now()->addHours(2),
            'status' => LiveSession::STATUS_SCHEDULED,
            'banner_enabled' => true,
        ]);
        $liveSession->courses()->attach($course);

        $response = $this->actingAs($student)->get(route('student.dashboard'));

        $response->assertOk();
        $response->assertSeeText('Priority Live Session');
        $response->assertDontSeeText('Promo Banner');
    }

    public function test_admin_creating_scheduled_live_session_sends_notifications_to_enrolled_students_only(): void
    {
        $admin = User::factory()->admin()->create();
        $eligibleStudent = User::factory()->create();
        $otherStudent = User::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $eligibleStudent->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.live-sessions.store'), [
            'title' => 'Admin Scheduled Session',
            'description' => 'A live session',
            'zoom_join_url' => 'https://example.com/zoom-admin',
            'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'ends_at' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
            'status' => LiveSession::STATUS_SCHEDULED,
            'banner_enabled' => '1',
            'notifications_enabled' => '1',
            'course_ids' => [$course->id],
        ]);

        $response->assertRedirect(route('admin.live-sessions.index'));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $eligibleStudent->id,
            'notification_type' => 'live_session_scheduled',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $otherStudent->id,
            'notification_type' => 'live_session_scheduled',
        ]);
    }

    public function test_live_session_reminder_command_does_not_duplicate_notifications(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'price_paid' => 10,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $session = LiveSession::create([
            'title' => 'Reminder Session',
            'slug' => 'reminder-session',
            'zoom_join_url' => 'https://example.com/zoom-reminder',
            'starts_at' => now()->addMinutes(50),
            'ends_at' => now()->addHours(2),
            'status' => LiveSession::STATUS_SCHEDULED,
            'notifications_enabled' => true,
        ]);
        $session->courses()->attach($course);

        Artisan::call('live-sessions:send-notifications');
        Artisan::call('live-sessions:send-notifications');

        $this->assertEquals(1, Notification::where('notification_type', 'live_session_reminder')->count());
        $this->assertNotNull($session->fresh()->notified_1h_at);
    }
}
