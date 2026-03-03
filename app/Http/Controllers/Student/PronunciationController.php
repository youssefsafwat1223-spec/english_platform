<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PronunciationExercise;
use App\Services\SpeechRecognitionService;
use Illuminate\Http\Request;

class PronunciationController extends Controller
{
    private $speechService;

    public function __construct(SpeechRecognitionService $speechService)
    {
        $this->speechService = $speechService;
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

        $sentenceNumber = $request->sentence_number;
        $expectedText = $exercise->{"sentence_{$sentenceNumber}"};

        if (!$expectedText) {
            return response()->json(['error' => 'Invalid sentence number'], 400);
        }

        // Evaluate pronunciation using text comparison
        $result = $this->speechService->savePronunciationAttempt(
            $user->id,
            $exercise->id,
            $lesson->id,
            $sentenceNumber,
            $request->transcript,
            $expectedText
        );

        if (!$result['success']) {
            return response()->json(['error' => 'Evaluation failed'], 500);
        }

        return response()->json([
            'success' => true,
            'score' => $result['analysis']['overall_score'],
            'clarity' => $result['analysis']['clarity_score'],
            'pronunciation' => $result['analysis']['pronunciation_score'],
            'fluency' => $result['analysis']['fluency_score'],
            'feedback' => $result['analysis']['feedback'],
            'transcript' => $request->transcript,
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
}