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

        $pythonBin = trim((string) config('services.pronunciation_upload.python_bin', 'python3'));
        $gatewayWsUrl = trim((string) config('services.pronunciation_upload.gateway_ws_url', 'ws://127.0.0.1:8787/ws'));
        $timeout = (int) config('services.pronunciation_upload.timeout_seconds', 90);
        $scriptPath = base_path('scripts/pronunciation/transcribe_via_gateway.py');

        if ($pythonBin === '' || $gatewayWsUrl === '' || !is_file($scriptPath)) {
            return null;
        }

        $process = new Process([
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
        ]);

        $process->setTimeout(max(10, $timeout + 10));
        $process->run();

        if (!$process->isSuccessful()) {
            Log::warning('Pronunciation upload transcription process failed', [
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
