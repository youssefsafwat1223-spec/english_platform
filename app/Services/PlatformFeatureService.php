<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class PlatformFeatureService
{
    public const LIVE_SESSIONS_KEY = 'live_sessions_enabled';

    public function enabled(string $key, bool $default = true): bool
    {
        return Cache::rememberForever($this->cacheKey($key), function () use ($key, $default) {
            return (bool) SystemSetting::get($key, $default);
        });
    }

    public function liveSessionsEnabled(): bool
    {
        return $this->enabled(self::LIVE_SESSIONS_KEY, true);
    }

    public function set(string $key, bool $enabled, string $group = 'features'): void
    {
        SystemSetting::set($key, $enabled, 'boolean', $group, true);
        Cache::forget($this->cacheKey($key));
    }

    private function cacheKey(string $key): string
    {
        return 'platform_feature:' . $key;
    }
}
