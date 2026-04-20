<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPronunciationUpload;
use App\Models\PronunciationExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PronunciationUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'exercise_id' => 'required|integer|exists:pronunciation_exercises,id',
            'sentence_number' => 'required|integer|min:1',
            'audio' => 'required|file|max:30720',
            'duration_seconds' => 'nullable|integer|min:0|max:300',
            'client_transcript' => 'nullable|string|max:5000',
            'provider' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $exercise = PronunciationExercise::query()->with(['lesson', 'courseLevel'])->findOrFail((int) $request->exercise_id);

        $courseId = $exercise->lesson?->course_id
                 ?? $exercise->courseLevel?->course_id;

        if (!$courseId || !$user->isEnrolledIn($courseId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sentenceNumber = (int) $request->sentence_number;
        $expectedText = $exercise->sentences[$sentenceNumber] ?? null;

        if (!filled($expectedText)) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        $audioPath = $request->file('audio')->store('pronunciation/records', 'local');
        $uploadToken = (string) Str::uuid();
        $cacheKey = 'pron_upload_status:' . $uploadToken;

        Cache::put($cacheKey, [
            'status' => 'processing',
            'user_id' => (int) $user->id,
            'sentence_number' => $sentenceNumber,
        ], now()->addMinutes(20));

        ProcessPronunciationUpload::dispatchAfterResponse($cacheKey, [
            'user_id' => (int) $user->id,
            'exercise_id' => (int) $exercise->id,
            'sentence_number' => $sentenceNumber,
            'audio_path' => $audioPath,
            'duration_seconds' => (int) $request->input('duration_seconds', 0),
            'client_transcript' => trim((string) $request->input('client_transcript', '')),
            'expected_text' => trim((string) $expectedText),
            'locale' => app()->getLocale(),
            'provider' => (string) $request->input('provider', 'media_upload'),
        ]);

        return response()->json([
            'success' => true,
            'status' => 'processing',
            'upload_token' => $uploadToken,
        ], 202);
    }

    public function status(string $token)
    {
        $cacheKey = 'pron_upload_status:' . trim($token);
        $payload = Cache::get($cacheKey);

        if (!is_array($payload)) {
            return response()->json([
                'success' => false,
                'status' => 'expired',
                'error' => app()->getLocale() === 'ar'
                    ? 'انتهت مهلة التحليل. حاول مرة أخرى.'
                    : 'Analysis expired. Please try again.',
            ], 404);
        }

        if ((int) ($payload['user_id'] ?? 0) !== (int) auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $status = (string) ($payload['status'] ?? 'processing');

        if ($status === 'completed') {
            return response()->json(array_merge([
                'success' => true,
                'status' => 'completed',
            ], (array) ($payload['result'] ?? [])));
        }

        if ($status === 'failed') {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'error' => (string) ($payload['error'] ?? 'Analysis failed.'),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'status' => 'processing',
        ]);
    }
}
