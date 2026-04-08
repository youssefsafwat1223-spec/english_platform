<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class AzurePronunciationAssessmentService
{
    public function assess(string $absoluteAudioPath, string $expectedText, ?string $locale = 'en'): ?array
    {
        if (!(bool) config('services.azure_speech.enabled', false)) {
            return null;
        }

        if (!is_file($absoluteAudioPath) || trim($expectedText) === '') {
            return null;
        }

        $pythonBin = trim((string) config('services.azure_speech.python_bin', 'python3'));
        $speechKey = trim((string) config('services.azure_speech.key', ''));
        $speechRegion = trim((string) config('services.azure_speech.region', ''));
        $timeout = (int) config('services.azure_speech.timeout_seconds', 60);
        $scriptPath = base_path('scripts/pronunciation/assess_with_azure.py');
        $language = trim((string) config('services.azure_speech.language', 'en-US'));
        $gradingSystem = trim((string) config('services.azure_speech.grading_system', 'HundredMark'));
        $granularity = trim((string) config('services.azure_speech.granularity', 'Phoneme'));
        $enableMiscue = (bool) config('services.azure_speech.enable_miscue', true);
        $enableProsody = (bool) config('services.azure_speech.enable_prosody', false);

        if ($pythonBin === '' || $speechKey === '' || $speechRegion === '' || !is_file($scriptPath)) {
            return null;
        }

        $process = new Process([
            $pythonBin,
            $scriptPath,
            '--audio-file',
            $absoluteAudioPath,
            '--speech-key',
            $speechKey,
            '--speech-region',
            $speechRegion,
            '--language',
            $language !== '' ? $language : 'en-US',
            '--expected-text',
            trim($expectedText),
            '--grading-system',
            $gradingSystem !== '' ? $gradingSystem : 'HundredMark',
            '--granularity',
            $granularity !== '' ? $granularity : 'Phoneme',
            '--enable-miscue',
            $enableMiscue ? 'true' : 'false',
            '--enable-prosody',
            $enableProsody ? 'true' : 'false',
        ]);

        $process->setTimeout(max(10, $timeout + 10));
        $process->run();

        if (!$process->isSuccessful()) {
            Log::warning('Azure pronunciation assessment failed', [
                'exit_code' => $process->getExitCode(),
                'stderr' => mb_substr(trim((string) $process->getErrorOutput()), 0, 1200),
                'stdout' => mb_substr(trim((string) $process->getOutput()), 0, 1200),
            ]);

            return null;
        }

        $decoded = json_decode(trim((string) $process->getOutput()), true);
        if (!is_array($decoded) || !($decoded['success'] ?? false)) {
            return null;
        }

        return [
            'recognized_text' => trim((string) ($decoded['recognized_text'] ?? '')),
            'accuracy_score' => $this->normalizeScore($decoded['accuracy_score'] ?? null),
            'fluency_score' => $this->normalizeScore($decoded['fluency_score'] ?? null),
            'completeness_score' => $this->normalizeScore($decoded['completeness_score'] ?? null),
            'pronunciation_score' => $this->normalizeScore($decoded['pronunciation_score'] ?? null),
            'prosody_score' => $this->normalizeScore($decoded['prosody_score'] ?? null),
            'words' => is_array($decoded['words'] ?? null) ? $decoded['words'] : [],
            'raw_result' => is_array($decoded['raw_result'] ?? null) ? $decoded['raw_result'] : null,
        ];
    }

    private function normalizeScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return max(0, min(100, (int) round((float) $value)));
    }
}
