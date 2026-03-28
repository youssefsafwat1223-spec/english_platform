<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_only_latest_recent_pending_payment_per_course(): void
    {
        Carbon::setTestNow('2026-03-28 12:00:00');

        try {
            $user = User::factory()->create();
            $recentCourse = Course::factory()->create([
                'title' => 'Recent Checkout Course',
                'slug' => 'recent-checkout-course',
                'is_active' => false,
            ]);
            $staleCourse = Course::factory()->create([
                'title' => 'Stale Checkout Course',
                'slug' => 'stale-checkout-course',
                'is_active' => false,
            ]);

            $olderRecentPayment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $recentCourse->id,
                'transaction_id' => 'TXN-OLD-RECENT',
                'amount' => 100,
                'currency' => 'SAR',
                'discount_amount' => 0,
                'final_amount' => 100,
                'payment_status' => 'pending',
            ]);
            $olderRecentPayment->forceFill([
                'created_at' => now()->subMinutes(20),
                'updated_at' => now()->subMinutes(20),
            ])->save();

            $latestRecentPayment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $recentCourse->id,
                'transaction_id' => 'TXN-LATEST-RECENT',
                'amount' => 100,
                'currency' => 'SAR',
                'discount_amount' => 0,
                'final_amount' => 100,
                'payment_status' => 'pending',
            ]);
            $latestRecentPayment->forceFill([
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ])->save();

            $stalePayment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $staleCourse->id,
                'transaction_id' => 'TXN-STALE-PENDING',
                'amount' => 120,
                'currency' => 'SAR',
                'discount_amount' => 0,
                'final_amount' => 120,
                'payment_status' => 'pending',
            ]);
            $stalePayment->forceFill([
                'created_at' => now()->subMinutes(45),
                'updated_at' => now()->subMinutes(45),
            ])->save();

            $response = $this->actingAs($user)->get(route('student.dashboard'));

            $response->assertOk();
            $response->assertSeeText('Recent Checkout Course');
            $response->assertDontSeeText('Stale Checkout Course');
            $this->assertSame(1, substr_count($response->getContent(), 'Recent Checkout Course'));
        } finally {
            Carbon::setTestNow();
        }
    }
}
