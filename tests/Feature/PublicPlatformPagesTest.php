<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPlatformPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_hides_removed_course_stats_and_catalog_button(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSeeText('View All Courses');
        $response->assertDontSee('data-counter', false);
    }

    public function test_about_page_describes_platform_sections_and_telegram_bot(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSeeText('خريطة كاملة للمنصة');
        $response->assertSeeText('الصفحات العامة');
        $response->assertSeeText('البوت على تيليجرام');
        $response->assertSeeText('/status');
        $response->assertSeeText('لوحة الإدارة');
        $response->assertDontSeeText('ط¹ظ† ط§ظ„ظ…ظ†طµط©');
        $response->assertDontSeeText('ط®ط±ظٹط·ط© ظƒط§ظ…ظ„ط© ظ„ظ„ظ…ظ†طµط©');
    }
}
