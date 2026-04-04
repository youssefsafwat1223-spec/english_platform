<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PronunciationAttempt;
use App\Models\PronunciationExercise;
use App\Services\RealtimePronunciationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PronunciationController extends Controller
{
    public function __construct(private readonly RealtimePronunciationService $realtimeService)
    {
    }

    public function show(PronunciationExercise $exercise)
    {
        $user = auth()->user();
        $lesson = $exercise->lesson;

        // Check enrollment
        if (!$user->isEnrolledIn($lesson->course_id)) {
            abort(403);
        }

        $exercise->load('lesson.course');

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
        $comparison = $this->realtimeService->compare($expectedText, $transcript);
        $analysis = $this->saveAttemptFromComparison(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            'web_speech_fallback',
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

        return response()->json([
            'success' => true,
            'transcript' => $transcript,
            'expected' => $expectedText,
            'word_diff' => $comparison['word_diff'],
            'counts' => $comparison['counts'],
            'scores' => $comparison['scores'],
            'feedback' => $comparison['feedback'],
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
        $comparison = $this->realtimeService->compare($expectedText, $transcript);

        $analysis = $this->saveAttemptFromComparison(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $transcript,
            $expectedText,
            $comparison,
            (string) $request->input('provider', 'streaming'),
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
            'word_diff' => $comparison['word_diff'],
            'counts' => $comparison['counts'],
            'transcript' => $transcript,
            'expected' => $expectedText,
        ]);
    }

    public function upload(Request $request)
    {
        // Legacy endpoint — redirect to evaluate
        return response()->json(['error' => 'Please use the updated pronunciation interface.'], 400);
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

    private function saveAttemptFromComparison(
        int $userId,
        int $exerciseId,
        int $lessonId,
        int $sentenceNumber,
        string $transcript,
        string $expectedText,
        array $comparison,
        string $provider,
        ?string $sessionId,
        ?int $latencyMs,
        ?int $durationSeconds
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
            'audio_recording_path' => null,
            'recording_duration' => max(0, (int) ($durationSeconds ?? 0)),
            'overall_score' => $scores['overall'],
            'clarity_score' => $scores['clarity'],
            'pronunciation_score' => $scores['pronunciation'],
            'fluency_score' => $scores['fluency'],
            'feedback_text' => $comparison['feedback'],
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
        ];
    }
}
