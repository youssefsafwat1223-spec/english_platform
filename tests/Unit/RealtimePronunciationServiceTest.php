<?php

namespace Tests\Unit;

use App\Services\RealtimePronunciationService;
use PHPUnit\Framework\TestCase;

class RealtimePronunciationServiceTest extends TestCase
{
    public function test_it_marks_exact_sentence_as_fully_correct(): void
    {
        $service = new RealtimePronunciationService();

        $result = $service->compare(
            'I am learning English every day',
            'I am learning English every day'
        );

        $this->assertSame(6, $result['counts']['expected']);
        $this->assertSame(6, $result['counts']['correct']);
        $this->assertSame(0, $result['counts']['wrong']);
        $this->assertSame(0, $result['counts']['missing']);
        $this->assertSame(0, $result['counts']['extra']);
        $this->assertSame(100, $result['scores']['completion']);
    }

    public function test_it_tracks_wrong_missing_and_extra_words(): void
    {
        $service = new RealtimePronunciationService();

        $wrongResult = $service->compare(
            'I am learning English every day',
            'I am study English day quickly'
        );

        $missingResult = $service->compare(
            'I am learning English every day',
            'I am learning English'
        );

        $extraResult = $service->compare(
            'I am learning English every day',
            'I am learning English every day quickly'
        );

        $this->assertSame(6, $wrongResult['counts']['expected']);
        $this->assertGreaterThanOrEqual(1, $wrongResult['counts']['wrong']);
        $this->assertGreaterThanOrEqual(1, $missingResult['counts']['missing']);
        $this->assertGreaterThanOrEqual(1, $extraResult['counts']['extra']);
        $this->assertLessThan(100, $wrongResult['scores']['overall']);
        $this->assertIsArray($wrongResult['word_diff']);
        $this->assertNotEmpty($wrongResult['word_diff']);
    }

    public function test_it_handles_empty_transcript(): void
    {
        $service = new RealtimePronunciationService();

        $result = $service->compare(
            'I am learning English every day',
            ''
        );

        $this->assertSame(0, $result['counts']['spoken']);
        $this->assertSame(0, $result['counts']['correct']);
        $this->assertSame(6, $result['counts']['missing']);
        $this->assertSame(0, $result['scores']['completion']);
    }
}
