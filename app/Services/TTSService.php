<?php

namespace App\Services;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TTSService
{
    private $client;

    public function __construct()
    {
        try {
            $clientConfig = [];
            $credentialsPath = config('services.google.credentials_path');
            $projectId = config('services.google.project_id');

            if ($credentialsPath) {
                $resolvedPath = $this->resolveCredentialsPath($credentialsPath);

                if (is_file($resolvedPath)) {
                    $clientConfig['credentials'] = $resolvedPath;
                } else {
                    Log::warning('TTS credentials file was not found', [
                        'path' => $credentialsPath,
                        'resolved_path' => $resolvedPath,
                    ]);
                }
            }

            if ($projectId) {
                $clientConfig['projectId'] = $projectId;
            }

            $this->client = new TextToSpeechClient($clientConfig);
        } catch (\Exception $e) {
            Log::error('TTS Client initialization failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate Arabic listening audio using SSML (Arabic فصحى with embedded English)
     * Uses ar-XA-Neural2-B voice; English words inside <lang xml:lang="en-US"> tags
     * are pronounced with an English accent automatically.
     */
    public function generateListeningAudio(string $scriptAr, string $storagePath = null): array
    {
        $ssml = '<speak>' . $scriptAr . '</speak>';

        $settings = [
            'ssml'        => true,
            'language'    => 'ar-XA',
            'voice_name'  => 'ar-XA-Neural2-B',
            'gender'      => 'male',
            'speed'       => 0.9,
            'storage_dir' => 'listening-audio',
        ];

        if ($storagePath) {
            $settings['storage_path'] = $storagePath;
        }

        return $this->generateSpeech($ssml, $settings);
    }

    /**
     * Generate speech from text or SSML
     */
    public function generateSpeech($text, $settings = [])
    {
        try {
            if (!$this->client) {
                return [
                    'success' => false,
                    'error' => 'TTS client is not configured.',
                ];
            }

            // Default settings
            $voiceGender  = $settings['gender'] ?? 'male';
            $languageCode = $settings['language'] ?? 'en-US';
            $voiceName    = $settings['voice_name'] ?? 'en-US-Neural2-D';
            $speed        = $settings['speed'] ?? 1.0;
            $isSsml       = $settings['ssml'] ?? false;
            $storageDir   = $settings['storage_dir'] ?? 'quiz-audio/tts-generated';

            // Create synthesis input
            $synthesisInput = new SynthesisInput();
            if ($isSsml) {
                $synthesisInput->setSsml($text);
            } else {
                $synthesisInput->setText($text);
            }

            // Configure voice
            $voice = new VoiceSelectionParams();
            $voice->setLanguageCode($languageCode);
            $voice->setName($voiceName);

            if ($voiceGender === 'male') {
                $voice->setSsmlGender(SsmlVoiceGender::MALE);
            } else {
                $voice->setSsmlGender(SsmlVoiceGender::FEMALE);
            }

            // Configure audio
            $audioConfig = new AudioConfig();
            $audioConfig->setAudioEncoding(AudioEncoding::MP3);
            $audioConfig->setSpeakingRate($speed);
            $audioConfig->setPitch(0);

            // Generate speech
            $response = $this->client->synthesizeSpeech(
                $synthesisInput,
                $voice,
                $audioConfig
            );

            $audioContent = $response->getAudioContent();

            // Save to storage
            $filename = 'tts-' . uniqid() . '.mp3';
            $path = isset($settings['storage_path'])
                ? $settings['storage_path']
                : "{$storageDir}/{$filename}";

            Storage::disk('public')->put($path, $audioContent);

            return [
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'duration' => $this->estimateDuration($text, $speed),
            ];

        } catch (\Exception $e) {
            Log::error('TTS generation failed', [
                'text' => $text,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate speech for question
     */
    public function generateQuestionAudio($question)
    {
        $text = $question->getTTSText();

        $settings = $question->tts_settings ?? [
            'gender' => 'male',
            'language' => 'en-US',
            'voice_name' => 'en-US-Neural2-D',
            'speed' => 1.0,
        ];

        $result = $this->generateSpeech($text, $settings);

        if ($result['success']) {
            $question->update([
                'has_audio' => true,
                'audio_path' => $result['path'],
                'audio_duration' => $result['duration'],
                'tts_settings' => $settings,
            ]);

            return $result;
        }

        return $result;
    }

    /**
     * Estimate audio duration
     */
    private function estimateDuration($text, $speed)
    {
        // Average speaking rate: 150 words per minute
        $words = str_word_count($text);
        $baseSeconds = ($words / 150) * 60;

        // Adjust for speed
        $adjustedSeconds = $baseSeconds / $speed;

        return (int) round($adjustedSeconds);
    }

    /**
     * Get available voices
     */
    public function getAvailableVoices($languageCode = 'en-US')
    {
        try {
            if (!$this->client) {
                return [];
            }

            $response = $this->client->listVoices($languageCode);

            $voices = [];

            foreach ($response->getVoices() as $voice) {
                $voices[] = [
                    'name' => $voice->getName(),
                    'language_codes' => iterator_to_array($voice->getLanguageCodes()),
                    'gender' => SsmlVoiceGender::name($voice->getSsmlGender()),
                ];
            }

            return $voices;

        } catch (\Exception $e) {
            Log::error('Failed to get available voices', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Delete audio file
     */
    public function deleteAudio($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return true;
        }

        return false;
    }

    /**
     * Batch generate audio for multiple questions
     */
    public function batchGenerateAudio($questions, $settings = [])
    {
        $results = [];

        foreach ($questions as $question) {
            if (!$question->has_audio) {
                $result = $this->generateQuestionAudio($question);
                $results[] = [
                    'question_id' => $question->id,
                    'success' => $result['success'],
                ];
            }
        }

        return $results;
    }

    private function resolveCredentialsPath(string $path): string
    {
        if (preg_match('/^[A-Za-z]:\\\\/', $path) || str_starts_with($path, '/') || str_starts_with($path, '\\')) {
            return $path;
        }

        return base_path($path);
    }
}
