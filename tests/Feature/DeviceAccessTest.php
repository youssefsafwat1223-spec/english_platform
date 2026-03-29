<?php

namespace Tests\Feature;

use App\Http\Middleware\RequireAdminTwoFactor;
use App\Models\DeviceReplacementRequest;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\DeviceAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeviceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_three_devices_are_allowed_and_fourth_device_creates_replacement_request(): void
    {
        $user = User::factory()->create();
        $service = app(DeviceAccessService::class);

        foreach ([1, 2, 3] as $index) {
            $token = str_repeat((string) $index, 40);
            $result = $service->authorizeDevice($user, $this->makeDeviceRequest($token, "Browser {$index}"));

            $this->assertTrue($result['allowed']);
        }

        $blockedToken = str_repeat('4', 40);
        $blocked = $service->authorizeDevice($user, $this->makeDeviceRequest($blockedToken, 'Browser 4'));

        $this->assertFalse($blocked['allowed']);
        $this->assertDatabaseCount('user_devices', 3);
        $this->assertDatabaseHas('device_replacement_requests', [
            'user_id' => $user->id,
            'requested_device_token_hash' => hash('sha256', $blockedToken),
            'status' => DeviceReplacementRequest::STATUS_PENDING,
        ]);
    }

    public function test_login_from_fourth_device_is_rejected_and_creates_pending_request(): void
    {
        $user = User::factory()->create([
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        foreach ([1, 2, 3] as $index) {
            UserDevice::create([
                'user_id' => $user->id,
                'device_token_hash' => hash('sha256', str_repeat((string) $index, 40)),
                'device_label' => "Known Device {$index}",
                'device_type' => 'desktop',
                'platform' => 'Windows',
                'browser' => 'Chrome',
                'approved_at' => now(),
                'last_seen_at' => now(),
                'last_login_at' => now(),
            ]);
        }

        $blockedToken = str_repeat('9', 40);

        $response = $this->from(route('login'))
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, $blockedToken)
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseHas('device_replacement_requests', [
            'user_id' => $user->id,
            'requested_device_token_hash' => hash('sha256', $blockedToken),
            'status' => DeviceReplacementRequest::STATUS_PENDING,
        ]);
    }

    public function test_admin_can_approve_device_replacement_request(): void
    {
        $user = User::factory()->create();

        $currentDevice = UserDevice::create([
            'user_id' => $user->id,
            'device_token_hash' => hash('sha256', str_repeat('1', 40)),
            'device_label' => 'Current Laptop',
            'device_type' => 'desktop',
            'platform' => 'Windows',
            'browser' => 'Chrome',
            'approved_at' => now(),
            'last_seen_at' => now(),
            'last_login_at' => now(),
        ]);

        UserDevice::create([
            'user_id' => $user->id,
            'device_token_hash' => hash('sha256', str_repeat('2', 40)),
            'device_label' => 'Current Tablet',
            'device_type' => 'tablet',
            'platform' => 'iPadOS',
            'browser' => 'Safari',
            'approved_at' => now(),
            'last_seen_at' => now(),
            'last_login_at' => now(),
        ]);

        UserDevice::create([
            'user_id' => $user->id,
            'device_token_hash' => hash('sha256', str_repeat('3', 40)),
            'device_label' => 'Current Mobile',
            'device_type' => 'mobile',
            'platform' => 'Android',
            'browser' => 'Chrome',
            'approved_at' => now(),
            'last_seen_at' => now(),
            'last_login_at' => now(),
        ]);

        $newToken = str_repeat('8', 40);
        $requestRecord = DeviceReplacementRequest::create([
            'user_id' => $user->id,
            'requested_device_token_hash' => hash('sha256', $newToken),
            'device_label' => 'New Laptop',
            'device_type' => 'desktop',
            'platform' => 'macOS',
            'browser' => 'Safari',
            'status' => DeviceReplacementRequest::STATUS_PENDING,
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->withoutMiddleware([RequireAdminTwoFactor::class])
            ->actingAs($admin)
            ->post(route('admin.device-requests.approve', $requestRecord), [
                'replacement_device_id' => $currentDevice->id,
            ]);

        $response->assertRedirect(route('admin.device-requests.show', $requestRecord));

        $requestRecord->refresh();
        $currentDevice->refresh();

        $this->assertSame(DeviceReplacementRequest::STATUS_APPROVED, $requestRecord->status);
        $this->assertNotNull($currentDevice->revoked_at);
        $this->assertDatabaseHas('user_devices', [
            'user_id' => $user->id,
            'device_token_hash' => hash('sha256', $newToken),
            'device_label' => 'New Laptop',
            'revoked_at' => null,
        ]);

        $service = app(DeviceAccessService::class);
        $allowed = $service->authorizeDevice($user, $this->makeDeviceRequest($newToken, 'Approved Laptop'));

        $this->assertTrue($allowed['allowed']);
    }

    private function makeDeviceRequest(string $token, string $userAgent): Request
    {
        return Request::create(
            '/student/dashboard',
            'GET',
            [],
            [DeviceAccessService::COOKIE_NAME => $token],
            [],
            [
                'HTTP_USER_AGENT' => $userAgent,
                'REMOTE_ADDR' => '127.0.0.1',
            ]
        );
    }
}
