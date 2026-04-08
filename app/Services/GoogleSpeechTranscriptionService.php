<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class GoogleSpeechTranscriptionService
{
    public function transcribe(string $absoluteAudioPath, ?string $mimeType = null, ?string $expectedText = null, ?string $locale = 'en'): ?array
    {
        if (!(bool) config('services.google_speech.enabled', false)) {
            return null;
        }

        $apiKey = trim((string) config('services.google_speech.api_key', ''));
        $endpoint = trim((string) config('services.google_speech.endpoint', 'https://speech.googleapis.com/v1/speech:recognize'));

        if ($apiKey === '' || $endpoint === '' || !is_file($absoluteAudioPath)) {
            return null;
        }

        $prepared = $this->prepareAudio($absoluteAudioPath, $mimeType);
        if (!$prepared) {
            return null;
        }

        try {
            $content = @file_get_contents($prepared['path']);
            if ($content === false || $content === '') {
                return null;
            }

            $response = Http::timeout((int) config('services.google_speech.timeout_seconds', 45))
                ->acceptJson()
                ->post($endpoint . '?key=' . urlencode($apiKey), [
                    'config' => array_filter([
                        'encoding' => $prepared['encoding'],
                        'sampleRateHertz' => $prepared['sample_rate_hertz'],
                        'languageCode' => $this->resolveLanguage($locale),
                        'maxAlternatives' => 1,
                        'enableAutomaticPunctuation' => false,
                        'profanityFilter' => false,
                        'speechContexts' => $this->speechContexts($expectedText),
                    ], static fn ($value) => $value !== null),
                    'audio' => [
                        'content' => base64_encode($content),
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Google speech transcription failed', [
                    'status' => $response->status(),
                    'body' => mb_substr((string) $response->body(), 0, 1200),
                ]);

                return null;
            }

            $results = $response->json('results', []);
            if (!is_array($results) || $results === []) {
                return null;
            }

            $segments = [];
            $confidences = [];

            foreach ($results as $result) {
                $alternative = $result['alternatives'][0] ?? null;
                if (!is_array($alternative)) {
                    continue;
                }

                $segment = trim((string) ($alternative['transcript'] ?? ''));
                if ($segment !== '') {
                    $segments[] = $segment;
                }

                if (isset($alternative['confidence']) && is_numeric($alternative['confidence'])) {
                    $confidences[] = (float) $alternative['confidence'];
                }
            }

            $recognizedText = trim(preg_replace('/\s+/u', ' ', implode(' ', $segments)) ?? '');
            if ($recognizedText === '') {
                return null;
            }

            $confidence = $confidences !== []
                ? (int) max(0, min(100, round((array_sum($confidences) / count($confidences)) * 100)))
                : null;

            return [
                'recognized_text' => $recognizedText,
                'confidence' => $confidence,
                'raw_result' => $response->json(),
            ];
        } finally {
            if (!empty($prepared['temporary']) && is_file($prepared['path'])) {
                @unlink($prepared['path']);
            }
        }
    }

    private function prepareAudio(string $absoluteAudioPath, ?string $mimeType = null): ?array
    {
        $ffmpegBin = trim((string) config('services.google_speech.ffmpeg_bin', 'ffmpeg'));
        $tempPath = tempnam(sys_get_temp_dir(), 'gspeech_');
        if ($tempPath === false) {
            return null;
        }

        $wavPath = $tempPath . '.wav';
        @unlink($tempPath);

        $process = new Process([
            $ffmpegBin,
            '-y',
            '-i',
            $absoluteAudioPath,
            '-ac',
            '1',
            '-ar',
            '16000',
            '-f',
            'wav',
            $wavPath,
        ]);
        $process->setTimeout(max(10, (int) config('services.google_speech.timeout_seconds', 45)));
        $process->run();

        if (!$process->isSuccessful() || !is_file($wavPath)) {
            Log::warning('Google speech audio conversion failed', [
                'exit_code' => $process->getExitCode(),
                'stderr' => mb_substr(trim((string) $process->getErrorOutput()), 0, 1200),
            ]);
            @unlink($wavPath);

            return null;
        }

        return [
            'path' => $wavPath,
            'encoding' => 'LINEAR16',
            'sample_rate_hertz' => 16000,
            'temporary' => true,
        ];
    }

    private function resolveLanguage(?string $locale): string
    {
        $configured = trim((string) config('services.google_speech.language', ''));
        if ($configured !== '') {
            return $configured;
        }

        return str_starts_with(strtolower((string) $locale), 'ar') ? 'ar-SA' : 'en-US';
    }

    private function speechContexts(?string $expectedText): ?array
    {
        $expectedText = trim((string) $expectedText);
        if ($expectedText === '') {
            return null;
        }

        $words = preg_split('/\s+/u', $expectedText) ?: [];
        $phrases = array_values(array_unique(array_filter(array_merge([$expectedText], $words))));

        return [[
            'phrases' => array_slice($phrases, 0, 40),
            'boost' => 15,
        ]];
    }
}
