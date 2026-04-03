<?php

namespace Database\Seeders;

use App\Models\LiveSession;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class BreakingNewsTickerSeeder extends Seeder
{
    public function run(): void
    {
        // Disable dashboard priority for live-session banners so the promo ticker can appear clearly in local testing.
        LiveSession::query()->update([
            'banner_enabled' => false,
        ]);

        SystemSetting::set('dashboard_promo_title', 'خصم خاص على الكورسات', 'string', 'general');
        SystemSetting::set('dashboard_promo_message', 'احصل الآن على عرض خاص لفترة محدودة وجرب شريط Breaking News الجديد على الداشبورد.', 'string', 'general');
        SystemSetting::set('dashboard_promo_url', 'https://example.com/local-breaking-news', 'string', 'general');
    }
}
