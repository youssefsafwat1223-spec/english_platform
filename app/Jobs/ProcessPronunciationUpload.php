<?php

namespace App\Jobs;

use App\Services\PronunciationUploadAnalysisService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessPronunciationUpload
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public readonly string $cacheKey,
        public readonly array $payload
    )
    {
    }

    public function handle(PronunciationUploadAnalysisService $analysisService): void
    {
        $ttl = now()->addMinutes(20);

        try {
            $result = $analysisService->processStoredUpload(
                userId: (int) $this->payload['user_id'],
                exerciseId: (int) $this->payload['exercise_id'],
                sentenceNumber: (int) $this->payload['sentence_number'],
                audioPath: (string) $this->payload['audio_path'],
                durationSeconds: (int) ($this->payload['duration_seconds'] ?? 0),
                clientTranscript: $this->payload['client_transcript'] ?? null,
                expectedText: $this->payload['expected_text'] ?? null,
                locale: (string) ($this->payload['locale'] ?? 'en'),
                provider: (string) ($this->payload['provider'] ?? 'media_upload')
            );

            Cache::put($this->cacheKey, [
                'status' => 'completed',
                'user_id' => (int) $this->payload['user_id'],
                'result' => $result,
            ], $ttl);
        } catch (\Throwable $e) {
            Log::error('Async pronunciation upload processing failed', [
                'cache_key' => $this->cacheKey,
                'message' => $e->getMessage(),
            ]);

            Cache::put($this->cacheKey, [
                'status' => 'failed',
                'user_id' => (int) $this->payload['user_id'],
                'error' => (string) ($this->payload['locale'] ?? 'en') === 'ar'
                    ? 'تعذر تحليل النطق. حاول مرة أخرى.'
                    : 'Could not analyze pronunciation. Please try again.',
            ], $ttl);
        }
    }
}
