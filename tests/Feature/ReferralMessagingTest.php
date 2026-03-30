<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DeviceAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferralMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_referral_index_uses_free_course_wording_instead_of_free_subscription(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, str_repeat('a', 40))
            ->get(route('student.referrals.index'));

        $response->assertOk();
        $response->assertSeeText('كورس مجاني عند تسجيل 5 أشخاص عبر رابطك');
        $response->assertDontSeeText('اشتراك مجاني');
    }

    public function test_profile_referral_card_mentions_one_free_course_after_five_registrations(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, str_repeat('b', 40))
            ->get(route('student.profile.show'));

        $response->assertOk();
        $response->assertSeeText('شارك كود الدعوة الخاص بك لتحصل على كورس واحد مجاني بعد 5 تسجيلات ناجحة عبر رابطك.');
        $response->assertDontSeeText('free subscription');
    }

    public function test_referral_how_it_works_page_explains_the_free_course_reward(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, str_repeat('c', 40))
            ->get(route('student.referrals.how-it-works'));

        $response->assertOk();
        $response->assertSeeText('عند اكتمال 5 تسجيلات ناجحة عبر رابطك، تحصل على كورس واحد مجاني.');
    }
}
