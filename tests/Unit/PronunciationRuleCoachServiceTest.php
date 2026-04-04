<?php

namespace Tests\Unit;

use App\Services\PronunciationRuleCoachService;
use App\Services\RealtimePronunciationService;
use PHPUnit\Framework\TestCase;

class PronunciationRuleCoachServiceTest extends TestCase
{
    public function test_it_highlights_missing_words_as_the_primary_issue(): void
    {
        $compareService = new RealtimePronunciationService();
        $coachService = new PronunciationRuleCoachService();

        $comparison = $compareService->compare(
            'Think about each option carefully',
            'Think about each option'
        );

        $coach = $coachService->build(
            'Think about each option carefully',
            'Think about each option',
            $comparison,
            'en'
        );

        $this->assertSame('Complete the full sentence', $coach['title']);
        $this->assertContains('missing_words', $coach['patterns']);
        $this->assertSame('carefully', $coach['focus_word']);
        $this->assertStringContainsString('missed words', $coach['summary']);
    }

    public function test_it_detects_common_sound_patterns_for_wrong_words(): void
    {
        $compareService = new RealtimePronunciationService();
        $coachService = new PronunciationRuleCoachService();

        $comparison = $compareService->compare(
            'Think very fast',
            'sink ferry fast'
        );

        $coach = $coachService->build(
            'Think very fast',
            'sink ferry fast',
            $comparison,
            'en'
        );

        $this->assertContains('th_sound', $coach['patterns']);
        $this->assertContains('v_f_sound', $coach['patterns']);
        $this->assertSame('think', $coach['focus_word']);
        $this->assertStringContainsString('tongue', $coach['tip']);
    }
}
