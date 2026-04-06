<?php

namespace App\Services;

use App\Models\PronunciationAttempt;
use App\Models\PronunciationExercise;
use Illuminate\Support\Facades\Storage;

class PronunciationUploadAnalysisService
{
    public function __construct(
        private readonly RealtimePronunciationService $realtimeService,
        private readonly PronunciationRuleCoachService $ruleCoachService,
        private readonly LocalAiSpeakingCoachService $aiSpeakingCoachService,
        private readonly PronunciationUploadTranscriptionService $uploadTranscriptionService
    )
    {
    }

    public function processStoredUpload(
        int $userId,
        int $exerciseId,
        int $sentenceNumber,
        string $audioPath,
        int $durationSeconds = 0,
        ?string $clientTranscript = null,
        string $locale = 'en',
        string $provider = 'media_upload'
    ): array {
        $exercise = PronunciationExercise::query()->with('lesson')->findOrFail($exerciseId);
        $lesson = $exercise->lesson;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if ($expectedText === null) {
            throw new \RuntimeException('Invalid sentence number.');
        }

        $absoluteAudioPath = Storage::disk('local')->path($audioPath);
        $mimeType = $this->guessMimeTypeFromPath($audioPath);
        $transcript = trim((string) $clientTranscript);

        if ($transcript === '') {
            $transcript = trim((string) $this->uploadTranscriptionService->transcribe($absoluteAudioPath, $mimeType));
        }

        if ($transcript === '') {
            throw new \RuntimeException($locale === 'ar'
                ? 'لم نتمكن من تحويل الصوت إلى نص. حاول مرة أخرى.'
                : 'Could not transcribe audio. Please try again.');
        }

        [$comparison, $coach, $resolvedProvider] = $this->buildComparisonAndCoach(
            $expectedText,
            $transcript,
            $locale,
            $provider
        );

        $analysis = $this->saveAttemptFromComparison(
            $userId,
            $exerciseId,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            $coach,
            $resolvedProvider,
            $durationSeconds,
            $audioPath
        );

        return [
            'success' => true,
            'score' => $analysis['overall_score'],
            'clarity' => $analysis['clarity_score'],
            'pronunciation' => $analysis['pronunciation_score'],
            'fluency' => $analysis['fluency_score'],
            'completion' => $analysis['completion_percent'],
            'feedback' => $analysis['feedback'],
            'coach' => $analysis['coach'],
            'word_diff' => $comparison['word_diff'],
            'counts' => $comparison['counts'],
            'transcript' => $transcript,
            'expected' => $expectedText,
            'attempt_id' => $analysis['attempt_id'],
        ];
    }

    private function getExpectedSentence(PronunciationExercise $exercise, int $sentenceNumber): ?string
    {
        $expectedText = $exercise->{"sentence_{$sentenceNumber}"} ?? null;
        return $expectedText ? trim((string) $expectedText) : null;
    }

    private function guessMimeTypeFromPath(string $audioPath): string
    {
        $extension = strtolower((string) pathinfo($audioPath, PATHINFO_EXTENSION));

        return match ($extension) {
            'm4a', 'mp4' => 'audio/mp4',
            'wav' => 'audio/wav',
            'mp3' => 'audio/mpeg',
            'ogg' => 'audio/ogg',
            default => 'audio/webm',
        };
    }

    private function buildComparisonAndCoach(string $expectedText, string $transcript, string $locale, string $baseProvider): array
    {
        $comparison = $this->realtimeService->compare($expectedText, $transcript);
        $ruleCoach = $this->ruleCoachService->build($expectedText, $transcript, $comparison, $locale);
        $aiCoach = $this->aiSpeakingCoachService->evaluate($expectedText, $transcript, $comparison, $locale);

        if (!$aiCoach) {
            return [$comparison, $ruleCoach, $baseProvider];
        }

        $comparison['scores'] = $this->blendScores($comparison['scores'] ?? [], $aiCoach['scores'] ?? []);

        $coach = array_merge($ruleCoach, array_filter([
            'title' => $aiCoach['title'] ?? null,
            'summary' => $aiCoach['summary'] ?? null,
            'tip' => $aiCoach['tip'] ?? null,
            'retry_instruction' => $aiCoach['retry_instruction'] ?? null,
            'focus_word' => $aiCoach['focus_word'] ?? null,
            'strengths' => $aiCoach['strengths'] ?? null,
            'improvements' => $aiCoach['improvements'] ?? null,
            'corrected_sentence' => $aiCoach['corrected_sentence'] ?? null,
            'short_coach_reply' => $aiCoach['short_coach_reply'] ?? null,
            'ai_scores' => $aiCoach['scores'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '' && $value !== []));

        if (!empty($coach['summary'])) {
            $comparison['feedback'] = (string) $coach['summary'];
        }

        return [$comparison, $coach, $baseProvider . '+ollama'];
    }

    private function blendScores(array $baseScores, array $aiScores): array
    {
        $ratio = (float) config('services.speaking_ai.score_blend_ratio', 0.35);
        $ratio = max(0.0, min(1.0, $ratio));

        if ($ratio <= 0.0) {
            return $baseScores;
        }

        $blend = function (?int $base, ?int $ai) use ($ratio): ?int {
            if ($base === null) {
                return $ai;
            }

            if ($ai === null) {
                return $base;
            }

            return max(0, min(100, (int) round(($base * (1 - $ratio)) + ($ai * $ratio))));
        };

        $baseOverall = $this->normalizeScore($baseScores['overall'] ?? null);
        $basePronunciation = $this->normalizeScore($baseScores['pronunciation'] ?? null);
        $baseFluency = $this->normalizeScore($baseScores['fluency'] ?? null);
        $baseClarity = $this->normalizeScore($baseScores['clarity'] ?? null);
        $baseCompletion = $this->normalizeScore($baseScores['completion'] ?? null);
        $baseAccuracy = $this->normalizeScore($baseScores['accuracy'] ?? null);

        $aiOverall = $this->normalizeScore($aiScores['overall'] ?? null);
        $aiPronunciation = $this->normalizeScore($aiScores['pronunciation'] ?? null);
        $aiFluency = $this->normalizeScore($aiScores['fluency'] ?? null);
        $aiGrammar = $this->normalizeScore($aiScores['grammar'] ?? null);

        $blendedPronunciation = $blend($basePronunciation, $aiPronunciation);
        $blendedFluency = $blend($baseFluency, $aiFluency);
        $blendedClarity = $blend($baseClarity, $aiGrammar);

        return [
            'overall' => $blend($baseOverall, $aiOverall) ?? ($baseOverall ?? 0),
            'pronunciation' => $blendedPronunciation ?? ($basePronunciation ?? 0),
            'fluency' => $blendedFluency ?? ($baseFluency ?? 0),
            'clarity' => $blendedClarity ?? ($baseClarity ?? 0),
            'completion' => $baseCompletion ?? 0,
            'accuracy' => $baseAccuracy ?? ($blendedClarity ?? 0),
        ];
    }

    private function normalizeScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return max(0, min(100, (int) round((float) $value)));
    }

    private function saveAttemptFromComparison(
        int $userId,
        int $exerciseId,
        int $lessonId,
        int $sentenceNumber,
        string $transcript,
        string $expectedText,
        array $comparison,
        array $coach,
        string $provider,
        int $durationSeconds,
        ?string $audioPath = null
    ): array {
        $attemptNumber = PronunciationAttempt::query()
            ->where('user_id', $userId)
            ->where('pronunciation_exercise_id', $exerciseId)
            ->where('sentence_number', $sentenceNumber)
            ->count() + 1;

        $scores = $comparison['scores'];

        $attempt = PronunciationAttempt::create([
            'user_id' => $userId,
            'pronunciation_exercise_id' => $exerciseId,
            'lesson_id' => $lessonId,
            'attempt_number' => $attemptNumber,
            'sentence_number' => $sentenceNumber,
            'audio_recording_path' => $audioPath,
            'recording_duration' => max(0, $durationSeconds),
            'overall_score' => $scores['overall'],
            'clarity_score' => $scores['clarity'],
            'pronunciation_score' => $scores['pronunciation'],
            'fluency_score' => $scores['fluency'],
            'feedback_text' => $coach['summary'] ?? $comparison['feedback'],
            'ai_provider' => $provider,
            'transcript_text' => $transcript,
            'expected_text' => $expectedText,
            'word_diff_json' => $comparison['word_diff'],
            'completion_percent' => $scores['completion'],
            'recognition_latency_ms' => null,
            'stream_session_id' => null,
        ]);

        $attempt->awardPoints();

        return [
            'attempt_id' => (int) $attempt->id,
            'overall_score' => (int) $attempt->overall_score,
            'clarity_score' => (int) $attempt->clarity_score,
            'pronunciation_score' => (int) $attempt->pronunciation_score,
            'fluency_score' => (int) $attempt->fluency_score,
            'completion_percent' => (int) $attempt->completion_percent,
            'feedback' => (string) $attempt->feedback_text,
            'coach' => $coach,
        ];
    }
}
