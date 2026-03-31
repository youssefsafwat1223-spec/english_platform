<?php

namespace App\Services;

use App\Models\DeviceReplacementRequest;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceAccessService
{
    public const COOKIE_NAME = 'se_device_token';
    public const MAX_ALLOWED_DEVICES = 3;

    public function authorizeDevice(User $user, Request $request): array
    {
        $token = $this->resolveDeviceToken($request);
        $tokenHash = $this->hashToken($token);
        $details = $this->buildDeviceDetails($request);

        $result = DB::transaction(function () use ($user, $tokenHash, $details) {
            $existingDevice = UserDevice::where('user_id', $user->id)
                ->where('device_token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if ($existingDevice && $existingDevice->is_active) {
                $existingDevice->update([
                    'device_label' => $details['device_label'],
                    'device_type' => $details['device_type'],
                    'platform' => $details['platform'],
                    'browser' => $details['browser'],
                    'user_agent' => $details['user_agent'],
                    'ip_address' => $details['ip_address'],
                    'last_seen_at' => now(),
                    'last_login_at' => now(),
                ]);

                return [
                    'allowed' => true,
                    'pending_request_id' => null,
                ];
            }

            if ($existingDevice && !$existingDevice->is_active) {
                $requestRecord = $this->createOrRefreshReplacementRequest($user, $tokenHash, $details);

                return [
                    'allowed' => false,
                    'pending_request_id' => $requestRecord->id,
                ];
            }

            $activeDevicesCount = UserDevice::where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->lockForUpdate()
                ->count();

            if ($activeDevicesCount < self::MAX_ALLOWED_DEVICES) {
                UserDevice::create([
                    'user_id' => $user->id,
                    'device_token_hash' => $tokenHash,
                    'device_label' => $details['device_label'],
                    'device_type' => $details['device_type'],
                    'platform' => $details['platform'],
                    'browser' => $details['browser'],
                    'user_agent' => $details['user_agent'],
                    'ip_address' => $details['ip_address'],
                    'approved_at' => now(),
                    'last_seen_at' => now(),
                    'last_login_at' => now(),
                ]);

                return [
                    'allowed' => true,
                    'pending_request_id' => null,
                ];
            }

            $requestRecord = $this->createOrRefreshReplacementRequest($user, $tokenHash, $details);

            return [
                'allowed' => false,
                'pending_request_id' => $requestRecord->id,
            ];
        });

        $this->queueDeviceTokenCookie($token);

        return $result + ['device_token' => $token];
    }

    public function hasApprovedDevice(User $user, Request $request): bool
    {
        $token = $request->cookie(self::COOKIE_NAME);

        if (!is_string($token) || $token === '') {
            return false;
        }

        return UserDevice::where('user_id', $user->id)
            ->where('device_token_hash', $this->hashToken($token))
            ->whereNull('revoked_at')
            ->exists();
    }

    public function approveReplacementRequest(
        DeviceReplacementRequest $requestRecord,
        ?UserDevice $replacementDevice,
        User $reviewer
    ): void {
        DB::transaction(function () use ($requestRecord, $replacementDevice, $reviewer) {
            $requestRecord->refresh();

            if ($requestRecord->status !== DeviceReplacementRequest::STATUS_PENDING) {
                return;
            }

            if ($replacementDevice) {
                $replacementDevice->update([
                    'revoked_at' => now(),
                ]);
            }

            $device = UserDevice::where('user_id', $requestRecord->user_id)
                ->where('device_token_hash', $requestRecord->requested_device_token_hash)
                ->lockForUpdate()
                ->first();

            $payload = [
                'device_label' => $requestRecord->device_label,
                'device_type' => $requestRecord->device_type,
                'platform' => $requestRecord->platform,
                'browser' => $requestRecord->browser,
                'user_agent' => $requestRecord->user_agent,
                'ip_address' => $requestRecord->ip_address,
                'approved_at' => now(),
                'last_seen_at' => now(),
                'last_login_at' => now(),
                'revoked_at' => null,
            ];

            if ($device) {
                $device->update($payload);
            } else {
                UserDevice::create([
                    'user_id' => $requestRecord->user_id,
                    'device_token_hash' => $requestRecord->requested_device_token_hash,
                ] + $payload);
            }

            $requestRecord->update([
                'status' => DeviceReplacementRequest::STATUS_APPROVED,
                'replacement_for_device_id' => $replacementDevice?->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
            ]);
        });
    }

    public function rejectReplacementRequest(DeviceReplacementRequest $requestRecord, User $reviewer): void
    {
        if ($requestRecord->status !== DeviceReplacementRequest::STATUS_PENDING) {
            return;
        }

        $requestRecord->update([
            'status' => DeviceReplacementRequest::STATUS_REJECTED,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    private function createOrRefreshReplacementRequest(User $user, string $tokenHash, array $details): DeviceReplacementRequest
    {
        $existingRequest = DeviceReplacementRequest::where('user_id', $user->id)
            ->where('requested_device_token_hash', $tokenHash)
            ->where('status', DeviceReplacementRequest::STATUS_PENDING)
            ->first();

        $payload = [
            'device_label' => $details['device_label'],
            'device_type' => $details['device_type'],
            'platform' => $details['platform'],
            'browser' => $details['browser'],
            'user_agent' => $details['user_agent'],
            'ip_address' => $details['ip_address'],
        ];

        if ($existingRequest) {
            $existingRequest->update($payload);

            return $existingRequest;
        }

        return DeviceReplacementRequest::create([
            'user_id' => $user->id,
            'requested_device_token_hash' => $tokenHash,
            'status' => DeviceReplacementRequest::STATUS_PENDING,
        ] + $payload);
    }

    private function resolveDeviceToken(Request $request): string
    {
        $token = $request->cookie(self::COOKIE_NAME);

        if (is_string($token) && Str::length($token) >= 32) {
            return $token;
        }

        return Str::random(80);
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    private function queueDeviceTokenCookie(string $token): void
    {
        Cookie::queue(Cookie::make(
            self::COOKIE_NAME,
            $token,
            60 * 24 * 365 * 5,
            config('session.path', '/'),
            config('session.domain'),
            (bool) config('session.secure'),
            true,
            false,
            config('session.same_site', 'lax')
        ));
    }

    private function buildDeviceDetails(Request $request): array
    {
        $userAgent = (string) $request->userAgent();
        $browser = $this->detectBrowser($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $deviceType = $this->detectDeviceType($userAgent);

        return [
            'device_label' => trim($browser . ' on ' . $platform),
            'device_type' => $deviceType,
            'platform' => $platform,
            'browser' => $browser,
            'user_agent' => $userAgent,
            'ip_address' => $request->ip(),
        ];
    }

    private function detectDeviceType(string $userAgent): string
    {
        $value = Str::lower($userAgent);

        if (str_contains($value, 'ipad') || str_contains($value, 'tablet')) {
            return 'tablet';
        }

        if (str_contains($value, 'mobile') || str_contains($value, 'iphone') || str_contains($value, 'android')) {
            return 'mobile';
        }

        if ($value !== '') {
            return 'desktop';
        }

        return 'unknown';
    }

    private function detectPlatform(string $userAgent): string
    {
        $value = Str::lower($userAgent);

        return match (true) {
            str_contains($value, 'windows') => 'Windows',
            str_contains($value, 'iphone') || str_contains($value, 'ios') => 'iPhone',
            str_contains($value, 'ipad') => 'iPad',
            str_contains($value, 'android') => 'Android',
            str_contains($value, 'mac os') || str_contains($value, 'macintosh') => 'macOS',
            str_contains($value, 'linux') => 'Linux',
            default => __('ui.devices.unknown_device'),
        };
    }

    private function detectBrowser(string $userAgent): string
    {
        $value = Str::lower($userAgent);

        return match (true) {
            str_contains($value, 'edg') => 'Edge',
            str_contains($value, 'opr') || str_contains($value, 'opera') => 'Opera',
            str_contains($value, 'chrome') && !str_contains($value, 'edg') && !str_contains($value, 'opr') => 'Chrome',
            str_contains($value, 'firefox') => 'Firefox',
            str_contains($value, 'safari') && !str_contains($value, 'chrome') => 'Safari',
            default => __('ui.devices.unknown_browser'),
        };
    }
}
