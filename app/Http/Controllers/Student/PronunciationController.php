<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PronunciationAttempt;
use App\Models\PronunciationExercise;
use App\Services\LocalAiSpeakingCoachService;
use App\Services\PronunciationUploadTranscriptionService;
use App\Services\PronunciationRuleCoachService;
use App\Services\RealtimePronunciationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PronunciationController extends Controller
{
    public function __construct(
        private readonly RealtimePronunciationService $realtimeService,
        private readonly PronunciationRuleCoachService $ruleCoachService,
        private readonly LocalAiSpeakingCoachService $aiSpeakingCoachService,
        private readonly PronunciationUploadTranscriptionService $uploadTranscriptionService
    )
    {
    }

    public function show(PronunciationExercise $exercise)
    {
        $user = auth()->user();

        // Resolve course_id from lesson or courseLevel
        $courseId = $exercise->lesson?->course_id
                 ?? $exercise->courseLevel?->course_id;

        if (!$courseId || !$user->isEnrolledIn($courseId)) {
            abort(403);
        }

        $exercise->load(['lesson.course', 'courseLevel.course']);

        // Get user's previous attempts
        $attempts = $exercise->attempts()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('sentence_number');

        return view('student.pronunciation.practice', compact('exercise', 'attempts'));
    }

    /**
     * Evaluate pronunciation from browser Web Speech API transcript.
     */
    public function evaluate(Request $request, PronunciationExercise $exercise)
    {
        $request->validate([
            'transcript' => 'required|string',
            'sentence_number' => 'required|integer|min:1|max:3',
        ]);

        $user = auth()->user();
        $lesson = $exercise->lesson;

        // Check enrollment
        if (!$user->isEnrolledIn($lesson->course_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $transcript = trim((string) $request->transcript);
        [$comparison, $coach, $provider] = $this->buildComparisonAndCoach(
            $expectedText,
            $transcript,
            app()->getLocale(),
            'web_speech_fallback'
        );
        $analysis = $this->saveAttemptFromComparison(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            $coach,
            $provider,
            null,
            null,
            null
        );

        return response()->json([
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
        ]);
    }

    /**
     * Start a real-time pronunciation stream session.
     */
    public function startStream(Request $request, PronunciationExercise $exercise)
    {
        $request->validate([
            'sentence_number' => 'required|integer|min:1|max:3',
        ]);

        $user = auth()->user();
        $lesson = $exercise->lesson;

        if (!$user->isEnrolledIn($lesson->course_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $streamEnabled = (bool) config('services.pronunciation_stream.enabled', false);
        $sessionId = (string) Str::uuid();
        $expiresAt = now()->addMinutes(15);

        $payload = [
            'sid' => $sessionId,
            'uid' => $user->id,
            'eid' => $exercise->id,
            'sn' => $sentenceNumber,
            'iat' => now()->timestamp,
            'exp' => $expiresAt->timestamp,
        ];

        $encodedPayload = rtrim(strtr(base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encodedPayload, (string) config('app.key'));
        $streamToken = $encodedPayload . '.' . $signature;

        Cache::put('pron_stream:' . $sessionId, [
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
            'sentence_number' => $sentenceNumber,
            'expected_text' => $expectedText,
            'created_at' => now()->toIso8601String(),
        ], $expiresAt);

        return response()->json([
            'success' => true,
            'stream_enabled' => $streamEnabled,
            'session_id' => $sessionId,
            'expected_text' => $expectedText,
            'ws_url' => $streamEnabled ? config('services.pronunciation_stream.ws_url') : null,
            'ws_token' => $streamEnabled ? $streamToken : null,
            'chunk_ms' => (int) config('services.pronunciation_stream.chunk_ms', 1000),
        ]);
    }

    /**
     * Compare partial transcript against expected sentence in real-time.
     */
    public function compareStream(Request $request, PronunciationExercise $exercise)
    {
        $request->validate([
            'sentence_number' => 'required|integer|min:1|max:3',
            'transcript' => 'nullable|string|max:5000',
        ]);

        $user = auth()->user();
        $lesson = $exercise->lesson;

        if (!$user->isEnrolledIn($lesson->course_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $transcript = trim((string) $request->input('transcript', ''));
        $comparison = $this->realtimeService->compare($expectedText, $transcript);
        $coach = $this->ruleCoachService->build($expectedText, $transcript, $comparison, app()->getLocale());

        return response()->json([
            'success' => true,
            'transcript' => $transcript,
            'expected' => $expectedText,
            'word_diff' => $comparison['word_diff'],
            'counts' => $comparison['counts'],
            'scores' => $comparison['scores'],
            'feedback' => $comparison['feedback'],
            'coach' => $coach,
        ]);
    }

    /**
     * Finalize stream session and persist the attempt.
     */
    public function finalizeStream(Request $request, PronunciationExercise $exercise)
    {
        $request->validate([
            'session_id' => 'nullable|string|max:100',
            'sentence_number' => 'required|integer|min:1|max:3',
            'transcript' => 'required|string|max:5000',
            'duration_seconds' => 'nullable|integer|min:0|max:120',
            'latency_ms' => 'nullable|integer|min:0|max:60000',
            'provider' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $lesson = $exercise->lesson;

        if (!$user->isEnrolledIn($lesson->course_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $sessionId = $request->input('session_id');
        if ($sessionId) {
            $cached = Cache::get('pron_stream:' . $sessionId);
            if ($cached && ((int) $cached['user_id'] !== (int) $user->id || (int) $cached['exercise_id'] !== (int) $exercise->id)) {
                return response()->json(['error' => 'Invalid stream session'], 422);
            }
        }

        $transcript = trim((string) $request->transcript);
        [$comparison, $coach, $provider] = $this->buildComparisonAndCoach(
            $expectedText,
            $transcript,
            app()->getLocale(),
            (string) $request->input('provider', 'streaming')
        );

        $analysis = $this->saveAttemptFromComparison(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            $coach,
            $provider,
            $sessionId,
            $request->input('latency_ms'),
            $request->input('duration_seconds')
        );

        if ($sessionId) {
            Cache::forget('pron_stream:' . $sessionId);
        }

        return response()->json([
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
        ]);
    }

    public function upload(Request $request)
    {
        // Legacy endpoint — redirect to evaluate
        $request->validate([
            'exercise_id' => 'required|integer|exists:pronunciation_exercises,id',
            'sentence_number' => 'required|integer|min:1|max:3',
            'audio' => 'required|file|max:30720',
            'duration_seconds' => 'nullable|integer|min:0|max:300',
            'client_transcript' => 'nullable|string|max:5000',
            'provider' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $exercise = PronunciationExercise::query()->with('lesson')->findOrFail((int) $request->exercise_id);
        $lesson = $exercise->lesson;

        if (!$user->isEnrolledIn($lesson->course_id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $this->getExpectedSentence($exercise, $sentenceNumber);

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $audioFile = $request->file('audio');
        $audioPath = $audioFile->store('pronunciation/records', 'local');
        $absoluteAudioPath = Storage::disk('local')->path($audioPath);
        $mimeType = (string) ($audioFile->getClientMimeType() ?: 'audio/webm');

        $transcript = trim((string) $this->uploadTranscriptionService->transcribe($absoluteAudioPath, $mimeType, $expectedText));
        if ($transcript === '') {
            $transcript = trim((string) $request->input('client_transcript', ''));
        }

        if ($transcript === '') {
            return response()->json([
                'error' => app()->getLocale() === 'ar'
                    ? 'لم نتمكن من تحويل الصوت إلى نص. حاول مرة أخرى.'
                    : 'Could not transcribe audio. Please try again.',
            ], 422);
        }

        [$comparison, $coach, $provider] = $this->buildComparisonAndCoach(
            $expectedText,
            $transcript,
            app()->getLocale(),
            (string) $request->input('provider', 'media_upload')
        );

        $analysis = $this->saveAttemptFromComparison(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            $coach,
            $provider,
            null,
            null,
            $request->input('duration_seconds'),
            $audioPath
        );

        return response()->json([
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
        ]);
    }

    public function myAttempts()
    {
        $attempts = auth()->user()->pronunciationAttempts()
            ->with(['pronunciationExercise.lesson.course'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_attempts' => auth()->user()->pronunciationAttempts()->count(),
            'average_score' => auth()->user()->pronunciationAttempts()->avg('overall_score'),
            'best_score' => auth()->user()->pronunciationAttempts()->max('overall_score'),
        ];

        return view('student.pronunciation.my-attempts', compact('attempts', 'stats'));
    }

    private function getExpectedSentence(PronunciationExercise $exercise, int $sentenceNumber): ?string
    {
        $expectedText = $exercise->{"sentence_{$sentenceNumber}"} ?? null;
        return $expectedText ? trim((string) $expectedText) : null;
    }

    /**
     * Build deterministic comparison + rule coach, then merge optional AI coach feedback.
     *
     * @return array{0: array, 1: array, 2: string}
     */
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
        ?string $sessionId,
        ?int $latencyMs,
        ?int $durationSeconds,
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
            'recording_duration' => max(0, (int) ($durationSeconds ?? 0)),
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
            'recognition_latency_ms' => $latencyMs,
            'stream_session_id' => $sessionId,
        ]);

        $attempt->awardPoints();

        return [
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
