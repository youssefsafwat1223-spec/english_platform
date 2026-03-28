<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\PromoVideo;
use App\Models\Testimonial;
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

    public function test_home_page_renders_arabic_sections_without_mojibake(): void
    {
        Course::create([
            'title' => 'English Basics',
            'slug' => 'english-basics',
            'short_description' => 'Foundations for beginners',
            'description' => 'A starter course',
            'price' => 149.00,
            'is_active' => true,
            'total_students' => 25,
        ]);

        PromoVideo::create([
            'title' => 'مقدمة سريعة',
            'description' => 'جولة قصيرة داخل المنصة',
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Testimonial::create([
            'name' => 'يوسف',
            'role' => 'طالب',
            'content' => 'تجربة ممتازة وسلسة.',
            'rating' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->withSession(['locale' => 'ar'])->get(route('home'));

        $response->assertOk();
        $response->assertSeeText('اتقن الإنجليزية');
        $response->assertSeeText('بطريقة عملية ممتعة');
        $response->assertSeeText('ابدأ رحلتك الآن');
        $response->assertSeeText('شاهد قبل ما تشترك');
        $response->assertSeeText('عينة من');
        $response->assertSeeText('الشروحات');
        $response->assertSeeText('تقييمات حقيقية');
        $response->assertSeeText('ماذا قالوا');
        $response->assertSeeText('ر.س');

        $content = $response->getContent();

        $this->assertStringNotContainsString('ط¥طھظ‚ط§ظ†', $content);
        $this->assertStringNotContainsString('ط´ط§ظ‡ط¯ ظ‚ط¨ظ„ ظ…ط§ طھط´طھط±ظƒ', $content);
        $this->assertStringNotContainsString('ط±.ط³', $content);
        $this->assertStringNotContainsString('â•', $content);
        $this->assertStringNotContainsString('â€”', $content);
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
