<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LiveSessionsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin-live@example.com'],
            $this->buildUserAttributes([
                'name' => 'Live Sessions Admin',
                'email' => 'admin-live@example.com',
                'role' => 'admin',
            ])
        );

        $studentOne = User::firstOrCreate(
            ['email' => 'student-live-1@example.com'],
            $this->buildUserAttributes([
                'name' => 'Live Student One',
                'email' => 'student-live-1@example.com',
            ])
        );

        $studentTwo = User::firstOrCreate(
            ['email' => 'student-live-2@example.com'],
            $this->buildUserAttributes([
                'name' => 'Live Student Two',
                'email' => 'student-live-2@example.com',
            ])
        );

        $studentThree = User::firstOrCreate(
            ['email' => 'student-live-3@example.com'],
            $this->buildUserAttributes([
                'name' => 'Live Student Three',
                'email' => 'student-live-3@example.com',
            ])
        );

        $grammarCourse = Course::firstOrCreate(
            ['slug' => 'demo-live-grammar-course'],
            Course::factory()->make([
                'title' => 'Demo Live Grammar Course',
                'slug' => 'demo-live-grammar-course',
                'short_description' => 'Demo course for testing grammar live sessions.',
                'description' => 'Used locally to test the new live sessions feature.',
            ])->toArray()
        );

        $conversationCourse = Course::firstOrCreate(
            ['slug' => 'demo-live-conversation-course'],
            Course::factory()->make([
                'title' => 'Demo Live Conversation Course',
                'slug' => 'demo-live-conversation-course',
                'short_description' => 'Demo course for conversation live sessions.',
                'description' => 'Used locally to test cross-course live session visibility.',
            ])->toArray()
        );

        $this->enrollStudent($studentOne, $grammarCourse);
        $this->enrollStudent($studentTwo, $conversationCourse);
        $this->enrollStudent($studentThree, $grammarCourse);
        $this->enrollStudent($studentThree, $conversationCourse);

        $upcomingSession = LiveSession::updateOrCreate(
            ['slug' => 'demo-upcoming-live-session'],
            [
                'title' => 'Demo Upcoming Live Session',
                'description' => 'This session should appear as upcoming and show in the dashboard banner if no live-now session takes priority.',
                'zoom_join_url' => 'https://zoom.us/j/11111111111',
                'starts_at' => now()->addHours(6),
                'ends_at' => now()->addHours(7),
                'status' => LiveSession::STATUS_SCHEDULED,
                'banner_enabled' => true,
                'notifications_enabled' => true,
                'created_by' => $admin->id,
                'published_notification_sent_at' => now(),
            ]
        );
        $upcomingSession->courses()->sync([$grammarCourse->id]);

        $liveNowSession = LiveSession::updateOrCreate(
            ['slug' => 'demo-live-now-session'],
            [
                'title' => 'Demo Live Now Session',
                'description' => 'This session is currently live and should take priority in the student dashboard banner.',
                'zoom_join_url' => 'https://zoom.us/j/22222222222',
                'starts_at' => now()->subMinutes(20),
                'ends_at' => now()->addMinutes(40),
                'status' => LiveSession::STATUS_LIVE,
                'banner_enabled' => true,
                'notifications_enabled' => true,
                'created_by' => $admin->id,
                'published_notification_sent_at' => now(),
                'notified_1h_at' => now()->subMinutes(40),
            ]
        );
        $liveNowSession->courses()->sync([$grammarCourse->id, $conversationCourse->id]);

        $endedSession = LiveSession::updateOrCreate(
            ['slug' => 'demo-ended-live-session'],
            [
                'title' => 'Demo Ended Live Session',
                'description' => 'This one should show in the past sessions list with a recording link.',
                'zoom_join_url' => 'https://zoom.us/j/33333333333',
                'recording_url' => 'https://example.com/demo-recording',
                'starts_at' => now()->subDays(2)->subHour(),
                'ends_at' => now()->subDays(2),
                'status' => LiveSession::STATUS_ENDED,
                'banner_enabled' => false,
                'notifications_enabled' => true,
                'created_by' => $admin->id,
                'published_notification_sent_at' => now()->subDays(3),
                'notified_24h_at' => now()->subDays(3),
                'notified_1h_at' => now()->subDays(2),
            ]
        );
        $endedSession->courses()->sync([$conversationCourse->id]);

        SystemSetting::set('dashboard_promo_title', 'Local Promo Banner', 'string', 'general');
        SystemSetting::set('dashboard_promo_message', 'This promo banner is seeded for local testing. It should appear only when no live session banner has priority.', 'string', 'general');
        SystemSetting::set('dashboard_promo_url', 'https://example.com/local-promo', 'string', 'general');

        $this->seedNotification($studentOne, 'live_session_scheduled', 'Live Session Scheduled', "A new live session '{$upcomingSession->title}' was scheduled for " . $upcomingSession->starts_at->format('M d, Y h:i A') . '.', route('student.live-sessions.show', $upcomingSession));
        $this->seedNotification($studentOne, 'live_session_reminder', 'Live Session Reminder', "Reminder: '{$liveNowSession->title}' is live now.", route('student.live-sessions.show', $liveNowSession), false);
        $this->seedNotification($studentTwo, 'promo_announcement', 'Special Offer', 'A seeded promo banner is available locally for testing notification rendering.', route('student.live-sessions.index'), false);
    }

    private function enrollStudent(User $student, Course $course): void
    {
        Enrollment::firstOrCreate(
            [
                'user_id' => $student->id,
                'course_id' => $course->id,
            ],
            [
                'price_paid' => $course->price,
                'discount_amount' => 0,
                'discount_type' => null,
                'discount_code' => null,
                'progress_percentage' => 0,
                'completed_lessons' => 0,
                'total_lessons' => 10,
                'started_at' => now()->subDays(3),
                'last_accessed_at' => now()->subHours(3),
            ]
        );
    }

    private function seedNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        bool $isUnread = true
    ): void {
        Notification::updateOrCreate(
            [
                'user_id' => $user->id,
                'notification_type' => $type,
                'title' => $title,
            ],
            [
                'message' => $message,
                'action_url' => $actionUrl,
                'is_read' => !$isUnread ? true : false,
                'read_at' => !$isUnread ? now()->subHour() : null,
            ]
        );
    }

    private function buildUserAttributes(array $overrides = []): array
    {
        $email = $overrides['email'] ?? ('demo-' . Str::lower(Str::random(6)) . '@example.com');

        return array_merge([
            'name' => 'Demo User',
            'email' => $email,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '010' . fake()->numerify('#######'),
            'telegram_username' => fake()->userName(),
            'role' => 'student',
            'is_active' => true,
            'total_points' => fake()->numberBetween(0, 100),
            'current_streak' => fake()->numberBetween(0, 10),
            'longest_streak' => fake()->numberBetween(0, 100),
            'last_activity_at' => now()->subHours(fake()->numberBetween(1, 24)),
            'referral_code' => strtoupper(Str::random(8)),
        ], $overrides);
    }
}
