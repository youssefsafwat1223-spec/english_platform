<?php

namespace Tests\Feature;

use App\Http\Middleware\RequireAdminTwoFactor;
use App\Models\User;
use App\Services\TwoFactorAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_with_enabled_two_factor_is_redirected_to_challenge_after_login(): void
    {
        $service = app(TwoFactorAuthService::class);

        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password'),
            'two_factor_secret' => $service->generateSecret(),
            'two_factor_recovery_codes' => $service->generateRecoveryCodes(),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.two-factor.challenge'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_can_complete_two_factor_challenge_with_authenticator_code(): void
    {
        $service = app(TwoFactorAuthService::class);
        $secret = $service->generateSecret();

        $admin = User::factory()->admin()->create([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $service->generateRecoveryCodes(),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.two-factor.verify'), [
            'code' => $service->currentCode($secret),
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas(RequireAdminTwoFactor::SESSION_VERIFIED, true);
        $response->assertSessionHas(RequireAdminTwoFactor::SESSION_USER_ID, $admin->id);
    }

    public function test_admin_can_use_recovery_code_once(): void
    {
        $service = app(TwoFactorAuthService::class);
        $recoveryCodes = $service->generateRecoveryCodes();

        $admin = User::factory()->admin()->create([
            'two_factor_secret' => $service->generateSecret(),
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.two-factor.verify'), [
            'code' => $recoveryCodes[0],
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertCount(7, $admin->fresh()->two_factor_recovery_codes);
        $this->assertNotContains($recoveryCodes[0], $admin->fresh()->two_factor_recovery_codes);
    }

    public function test_admin_can_enable_and_disable_two_factor_from_security_settings(): void
    {
        $service = app(TwoFactorAuthService::class);
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.settings.security.two-factor.setup'))
            ->assertRedirect(route('admin.settings.security'));

        $setup = app('session')->get('admin_two_factor_setup');

        $this->assertIsArray($setup);
        $this->assertArrayHasKey('secret', $setup);

        $this->actingAs($admin)
            ->post(route('admin.settings.security.two-factor.confirm'), [
                'code' => $service->currentCode($setup['secret']),
            ])
            ->assertRedirect(route('admin.settings.security'));

        $admin->refresh();

        $this->assertTrue($admin->hasTwoFactorEnabled());
        $this->assertCount(8, $admin->two_factor_recovery_codes);

        $this->actingAs($admin)
            ->delete(route('admin.settings.security.two-factor.disable'), [
                'password' => 'password',
            ])
            ->assertRedirect();

        $admin->refresh();

        $this->assertFalse($admin->hasTwoFactorEnabled());
        $this->assertNull($admin->two_factor_secret);
        $this->assertNull($admin->two_factor_confirmed_at);
    }
}
