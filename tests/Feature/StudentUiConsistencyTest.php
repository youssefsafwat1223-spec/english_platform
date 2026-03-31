<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserDevice;
use App\Http\Middleware\EnsureApprovedDevice;
use App\Services\DeviceAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StudentUiConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_arabic_pages_render_clean_translations(): void
    {
        $this->withoutMiddleware(EnsureApprovedDevice::class);

        $user = User::factory()->create();

        $guideResponse = $this->actingAs($user)
            ->withSession(['locale' => 'ar'])
            ->get(route('student.telegram.guide'));

        $guideResponse->assertOk();
        $guideResponse->assertSeeText('دليل بوت تيليجرام');
        $guideResponse->assertSeeText('أهم الأوامر');
        $this->assertNoBrokenMarkers($guideResponse);

        $onboardingResponse = $this->actingAs($user)
            ->withSession(['locale' => 'ar'])
            ->get(route('student.onboarding'));

        $onboardingResponse->assertOk();
        $onboardingResponse->assertSeeText('إكمال الملف الشخصي');
        $onboardingResponse->assertSeeText('ربط تيليجرام');
        $this->assertNoBrokenMarkers($onboardingResponse);

        $referralsResponse = $this->actingAs($user)
            ->withSession(['locale' => 'ar'])
            ->get(route('student.referrals.how-it-works'));

        $referralsResponse->assertOk();
        $referralsResponse->assertSeeText('كيف يعمل نظام الإحالات؟');
        $referralsResponse->assertSeeText('قواعد مهمة');
        $this->assertNoBrokenMarkers($referralsResponse);
    }

    public function test_student_checkout_and_learn_pages_do_not_render_known_mojibake_or_invalid_markup(): void
    {
        $this->withoutMiddleware(EnsureApprovedDevice::class);

        $checkoutUser = User::factory()->create();
        $learnUser = User::factory()->create();
        $course = Course::factory()->create([
            'title' => 'Consistency Course',
            'slug' => 'consistency-course',
        ]);

        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson One',
            'slug' => 'lesson-one',
            'description' => 'Lesson description',
            'order_index' => 1,
            'has_quiz' => false,
            'is_free' => false,
        ]);

        Enrollment::create([
            'user_id' => $learnUser->id,
            'course_id' => $course->id,
            'price_paid' => 20,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 1,
        ]);

        $checkoutResponse = $this->actingAsOnApprovedDevice($checkoutUser)
            ->withSession(['locale' => 'en'])
            ->get(route('student.courses.enroll', $course));

        $checkoutResponse->assertOk();
        $checkoutResponse->assertSeeText('Secure Checkout');
        $checkoutResponse->assertDontSee("placeholder=__('", false);
        $this->assertNoBrokenMarkers($checkoutResponse);

        $learnResponse = $this->actingAsOnApprovedDevice($learnUser)
            ->withSession(['locale' => 'en'])
            ->get(route('student.courses.learn', $course));

        $learnResponse->assertOk();
        $learnResponse->assertSeeText('Curriculum');
        $learnResponse->assertSeeText('Continue Lessons');
        $this->assertNoBrokenMarkers($learnResponse);
    }

    public function test_student_profile_games_and_attempt_pages_render_without_broken_glyphs(): void
    {
        $this->withoutMiddleware(EnsureApprovedDevice::class);

        $user = User::factory()->create();
        $course = Course::factory()->create([
            'title' => 'Student UI Course',
            'slug' => 'student-ui-course',
        ]);

        $responses = [
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.profile.show')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.games.index')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.quizzes.my-attempts')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.pronunciation.my-attempts')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.notes.index')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.notifications.index')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.leaderboard')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.referrals.index')),
            $this->actingAsOnApprovedDevice($user)->withSession(['locale' => 'en'])->get(route('student.courses.show', $course)),
        ];

        foreach ($responses as $response) {
            $response->assertOk();
            $this->assertNoBrokenMarkers($response);
        }
    }

    public function test_public_certificate_verification_page_uses_shared_responsive_student_layout(): void
    {
        $user = User::factory()->create(['name' => 'Youssef']);
        $course = Course::factory()->create([
            'title' => 'Verified Course',
            'slug' => 'verified-course',
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => 50,
            'discount_amount' => 0,
            'progress_percentage' => 100,
            'completed_lessons' => 1,
            'total_lessons' => 1,
            'completed_at' => now(),
        ]);

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'certificate_id' => 'CERT-2026-0001',
            'certificate_type' => 'completion',
            'final_score' => 95,
            'issued_at' => now(),
            'verification_url' => route('certificates.verify', 'CERT-2026-0001'),
        ]);

        $response = $this->withSession(['locale' => 'ar'])
            ->get(route('certificates.verify', $certificate->certificate_id));

        $response->assertOk();
        $response->assertSee('glass-card', false);
        $response->assertSee('grid grid-cols-1 sm:grid-cols-2', false);
        $response->assertSeeText('رقم الشهادة');
        $response->assertSeeText('تم التحقق من الشهادة!');
        $this->assertNoBrokenMarkers($response);
    }

    private function assertNoBrokenMarkers(TestResponse $response): void
    {
        $content = $response->getContent();

        $this->assertStringNotContainsString('â', $content);
        $this->assertStringNotContainsString('ًں', $content);
        $this->assertStringNotContainsString('ط¨ظ', $content);
        $this->assertStringNotContainsString('placeholder=__(', $content);
    }

    private function actingAsOnApprovedDevice(User $user, string $token = 'test-device-token-12345678901234567890')
    {
        UserDevice::firstOrCreate(
            [
                'user_id' => $user->id,
                'device_token_hash' => hash('sha256', $token),
            ],
            [
                'device_label' => 'Chrome on Windows',
                'device_type' => 'desktop',
                'platform' => 'Windows',
                'browser' => 'Chrome',
                'user_agent' => 'PHPUnit',
                'ip_address' => '127.0.0.1',
                'approved_at' => now(),
                'last_seen_at' => now(),
                'last_login_at' => now(),
            ]
        );

        return $this->actingAs($user)->withCookie(DeviceAccessService::COOKIE_NAME, $token);
    }
}
