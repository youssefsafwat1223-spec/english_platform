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
        private readonly PronunciationUploadTranscriptionService $uploadTranscriptionService,
        private readonly GoogleSpeechTranscriptionService $googleSpeechTranscriptionService,
        private readonly AzurePronunciationAssessmentService $azurePronunciationAssessmentService
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
        ?string $expectedText = null,
        string $locale = 'en',
        string $provider = 'media_upload'
    ): array {
        $exercise = PronunciationExercise::query()->with(['lesson', 'courseLevel'])->findOrFail($exerciseId);
        $expectedText = trim((string) ($expectedText ?: $this->getExpectedSentence($exercise, $sentenceNumber)));

        if ($expectedText === '') {
            throw new \RuntimeException('Invalid sentence number.');
        }

        $absoluteAudioPath = Storage::disk('local')->path($audioPath);
        $mimeType = $this->guessMimeTypeFromPath($audioPath);
        $googleTranscription = $this->googleSpeechTranscriptionService->transcribe(
            $absoluteAudioPath,
            $mimeType,
            $expectedText,
            $locale
        );
        $transcript = trim((string) ($googleTranscription['recognized_text'] ?? ''));
        $speechMetrics = $this->speechMetricsFromGoogle($googleTranscription);
        $azureAssessment = null;

        if ($transcript === '') {
            $azureAssessment = $this->azurePronunciationAssessmentService->assess($absoluteAudioPath, $expectedText, $locale);
            $transcript = trim((string) ($azureAssessment['recognized_text'] ?? ''));
            $speechMetrics = $this->speechMetricsFromAzure($azureAssessment);
        }

        if ($transcript === '') {
            $transcript = trim((string) $this->uploadTranscriptionService->transcribe($absoluteAudioPath, $mimeType, $expectedText));
        }

        if ($transcript === '') {
            $transcript = trim((string) $clientTranscript);
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
            $provider,
            $speechMetrics,
            $azureAssessment
        );

        $analysis = $this->saveAttemptFromComparison(
            $userId,
            $exerciseId,
            $exercise->lesson_id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            $coach,
            $resolvedProvider,
            $durationSeconds,
            $audioPath,
            $azureAssessment,
            $speechMetrics
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
            'azure_accuracy' => $analysis['azure_accuracy_score'],
            'azure_fluency' => $analysis['azure_fluency_score'],
            'azure_completeness' => $analysis['azure_completeness_score'],
            'azure_pronunciation' => $analysis['azure_pronunciation_score'],
            'azure_prosody' => $analysis['azure_prosody_score'],
            'google_confidence' => $analysis['google_confidence'],
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

    private function buildComparisonAndCoach(string $expectedText, string $transcript, string $locale, string $baseProvider, array $speechMetrics = [], ?array $azureAssessment = null): array
    {
        $comparison = $this->realtimeService->compare($expectedText, $transcript);
        $comparison['scores'] = $this->mergeAzureScores($comparison['scores'] ?? [], $azureAssessment);
        $ruleCoach = $this->ruleCoachService->build($expectedText, $transcript, $comparison, $locale);
        $aiCoach = $this->aiSpeakingCoachService->evaluate($expectedText, $transcript, $comparison, $locale, $speechMetrics);

        if (!$aiCoach) {
            return [$comparison, $ruleCoach, $this->providerWithSpeechEngine($baseProvider, $speechMetrics, $azureAssessment)];
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

        return [$comparison, $coach, $this->providerWithSpeechEngine($baseProvider, $speechMetrics, $azureAssessment) . '+ollama'];
    }

    private function mergeAzureScores(array $baseScores, ?array $azureAssessment): array
    {
        if (!is_array($azureAssessment) || empty($azureAssessment)) {
            return $baseScores;
        }

        $accuracy = $this->normalizeScore($azureAssessment['accuracy_score'] ?? null);
        $fluency = $this->normalizeScore($azureAssessment['fluency_score'] ?? null);
        $completeness = $this->normalizeScore($azureAssessment['completeness_score'] ?? null);
        $pronunciation = $this->normalizeScore($azureAssessment['pronunciation_score'] ?? null);

        $overallCandidates = array_filter([
            $pronunciation,
            $accuracy,
            $fluency,
            $completeness,
        ], static fn ($value) => $value !== null);

        return [
            'overall' => !empty($overallCandidates) ? (int) round(array_sum($overallCandidates) / count($overallCandidates)) : ($baseScores['overall'] ?? 0),
            'pronunciation' => $pronunciation ?? ($accuracy ?? ($baseScores['pronunciation'] ?? 0)),
            'fluency' => $fluency ?? ($baseScores['fluency'] ?? 0),
            'clarity' => $accuracy ?? ($baseScores['clarity'] ?? 0),
            'completion' => $completeness ?? ($baseScores['completion'] ?? 0),
            'accuracy' => $accuracy ?? ($baseScores['accuracy'] ?? 0),
        ];
    }

    private function providerWithSpeechEngine(string $baseProvider, array $speechMetrics = [], ?array $azureAssessment = null): string
    {
        if (($speechMetrics['provider'] ?? null) === 'google' && !empty($speechMetrics['recognized_text'] ?? null)) {
            return $baseProvider . '+google';
        }

        if (is_array($azureAssessment) && !empty($azureAssessment['recognized_text'])) {
            return $baseProvider . '+azure';
        }

        return $baseProvider;
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
        ?int $lessonId,
        int $sentenceNumber,
        string $transcript,
        string $expectedText,
        array $comparison,
        array $coach,
        string $provider,
        int $durationSeconds,
        ?string $audioPath = null,
        ?array $azureAssessment = null,
        array $speechMetrics = []
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
            'azure_accuracy_score' => $this->normalizeScore($azureAssessment['accuracy_score'] ?? null),
            'azure_fluency_score' => $this->normalizeScore($azureAssessment['fluency_score'] ?? null),
            'azure_completeness_score' => $this->normalizeScore($azureAssessment['completeness_score'] ?? null),
            'azure_pronunciation_score' => $this->normalizeScore($azureAssessment['pronunciation_score'] ?? null),
            'azure_prosody_score' => $this->normalizeScore($azureAssessment['prosody_score'] ?? null),
            'azure_response_json' => is_array($azureAssessment['raw_result'] ?? null) ? $azureAssessment['raw_result'] : null,
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
            'azure_accuracy_score' => $attempt->azure_accuracy_score !== null ? (int) $attempt->azure_accuracy_score : null,
            'azure_fluency_score' => $attempt->azure_fluency_score !== null ? (int) $attempt->azure_fluency_score : null,
            'azure_completeness_score' => $attempt->azure_completeness_score !== null ? (int) $attempt->azure_completeness_score : null,
            'azure_pronunciation_score' => $attempt->azure_pronunciation_score !== null ? (int) $attempt->azure_pronunciation_score : null,
            'azure_prosody_score' => $attempt->azure_prosody_score !== null ? (int) $attempt->azure_prosody_score : null,
            'google_confidence' => $this->normalizeScore($speechMetrics['confidence'] ?? null),
        ];
    }

    private function speechMetricsFromGoogle(?array $googleTranscription): array
    {
        if (!is_array($googleTranscription) || empty($googleTranscription['recognized_text'])) {
            return [];
        }

        return [
            'provider' => 'google',
            'recognized_text' => trim((string) ($googleTranscription['recognized_text'] ?? '')),
            'confidence' => $this->normalizeScore($googleTranscription['confidence'] ?? null),
        ];
    }

    private function speechMetricsFromAzure(?array $azureAssessment): array
    {
        if (!is_array($azureAssessment) || empty($azureAssessment['recognized_text'])) {
            return [];
        }

        return [
            'provider' => 'azure',
            'recognized_text' => trim((string) ($azureAssessment['recognized_text'] ?? '')),
            'accuracy_score' => $this->normalizeScore($azureAssessment['accuracy_score'] ?? null),
            'fluency_score' => $this->normalizeScore($azureAssessment['fluency_score'] ?? null),
            'completeness_score' => $this->normalizeScore($azureAssessment['completeness_score'] ?? null),
            'pronunciation_score' => $this->normalizeScore($azureAssessment['pronunciation_score'] ?? null),
            'prosody_score' => $this->normalizeScore($azureAssessment['prosody_score'] ?? null),
        ];
    }
}
