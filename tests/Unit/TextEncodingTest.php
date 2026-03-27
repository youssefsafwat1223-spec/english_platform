<?php

namespace Tests\Unit;

use App\Support\TextEncoding;
use PHPUnit\Framework\TestCase;

class TextEncodingTest extends TestCase
{
    public function test_it_repairs_common_arabic_mojibake(): void
    {
        $this->assertSame('العربية', TextEncoding::repair('Ø§Ù„Ø¹Ø±Ø¨ÙŠØ©'));
        $this->assertSame('كورس جاهز', TextEncoding::repair('ÙƒÙˆØ±Ø³ Ø¬Ø§Ù‡Ø²'));
    }

    public function test_it_repairs_mojibake_emoji(): void
    {
        $this->assertSame('📖', TextEncoding::repair('ðŸ“–'));
    }

    public function test_it_leaves_normal_text_unchanged(): void
    {
        $this->assertSame('Simple English', TextEncoding::repair('Simple English'));
        $this->assertSame('نص عربي سليم', TextEncoding::repair('نص عربي سليم'));
    }
}
