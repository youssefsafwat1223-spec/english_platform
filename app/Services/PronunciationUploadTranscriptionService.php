<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class PronunciationUploadTranscriptionService
{
    public function transcribe(string $absoluteAudioPath, ?string $mimeType = null, ?string $expectedText = null): ?string
    {
        if (!(bool) config('services.pronunciation_upload.enabled', true)) {
            return null;
        }

        if (!is_file($absoluteAudioPath)) {
            return null;
        }

        $primaryEngine = strtolower(trim((string) config('services.pronunciation_upload.engine', 'faster_whisper')));
        $fallbackEngine = strtolower(trim((string) config('services.pronunciation_upload.fallback_engine', 'gateway')));

        $transcript = $this->transcribeWithEngine($primaryEngine, $absoluteAudioPath, $mimeType, $expectedText);

        if ($transcript === null && $fallbackEngine !== '' && $fallbackEngine !== $primaryEngine) {
            $transcript = $this->transcribeWithEngine($fallbackEngine, $absoluteAudioPath, $mimeType, $expectedText);
        }

        return $transcript;
    }

    private function transcribeWithEngine(string $engine, string $absoluteAudioPath, ?string $mimeType, ?string $expectedText): ?string
    {
        return match ($engine) {
            'faster_whisper' => $this->transcribeWithFasterWhisper($absoluteAudioPath, $expectedText),
            'gateway' => $this->transcribeWithGateway($absoluteAudioPath, $mimeType, $expectedText),
            default => null,
        };
    }

    private function transcribeWithFasterWhisper(string $absoluteAudioPath, ?string $expectedText): ?string
    {
        $pythonBin = trim((string) config('services.pronunciation_upload.python_bin', 'python3'));
        $timeout = (int) config('services.pronunciation_upload.timeout_seconds', 90);
        $scriptPath = base_path('scripts/pronunciation/transcribe_with_faster_whisper.py');

        if ($pythonBin === '' || !is_file($scriptPath)) {
            return null;
        }

        return $this->runProcess([
            $pythonBin,
            $scriptPath,
            '--audio-file',
            $absoluteAudioPath,
            '--model',
            (string) config('services.pronunciation_upload.faster_whisper_model', 'large-v3'),
            '--device',
            (string) config('services.pronunciation_upload.faster_whisper_device', 'cpu'),
            '--compute-type',
            (string) config('services.pronunciation_upload.faster_whisper_compute_type', 'int8'),
            '--beam-size',
            (string) max(1, (int) config('services.pronunciation_upload.faster_whisper_beam_size', 5)),
            '--language',
            'en',
            '--expected-text',
            trim((string) $expectedText),
        ], max(10, $timeout + 10), 'faster-whisper');
    }

    private function transcribeWithGateway(string $absoluteAudioPath, ?string $mimeType, ?string $expectedText): ?string
    {
        $pythonBin = trim((string) config('services.pronunciation_upload.python_bin', 'python3'));
        $gatewayWsUrl = trim((string) config('services.pronunciation_upload.gateway_ws_url', 'ws://127.0.0.1:8787/ws'));
        $timeout = (int) config('services.pronunciation_upload.timeout_seconds', 90);
        $scriptPath = base_path('scripts/pronunciation/transcribe_via_gateway.py');

        if ($pythonBin === '' || $gatewayWsUrl === '' || !is_file($scriptPath)) {
            return null;
        }

        return $this->runProcess([
            $pythonBin,
            $scriptPath,
            '--ws-url',
            $gatewayWsUrl,
            '--audio-file',
            $absoluteAudioPath,
            '--mime-type',
            (string) ($mimeType ?: 'audio/webm'),
            '--expected-text',
            trim((string) $expectedText),
            '--timeout-seconds',
            (string) max(10, $timeout),
        ], max(10, $timeout + 10), 'gateway');
    }

    private function runProcess(array $command, int $timeoutSeconds, string $engineLabel): ?string
    {
        $process = new Process($command);
        $process->setTimeout($timeoutSeconds);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::warning('Pronunciation upload transcription process failed', [
                'engine' => $engineLabel,
                'exit_code' => $process->getExitCode(),
                'stderr' => mb_substr(trim((string) $process->getErrorOutput()), 0, 600),
            ]);

            return null;
        }

        $stdout = trim((string) $process->getOutput());
        if ($stdout === '') {
            return null;
        }

        $decoded = json_decode($stdout, true);
        if (!is_array($decoded)) {
            return null;
        }

        $transcript = trim((string) ($decoded['transcript'] ?? ''));
        return $transcript !== '' ? $transcript : null;
    }
}
