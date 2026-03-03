<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SpeechRecognitionService
{
    /**
     * Evaluate pronunciation by comparing transcript with expected text.
     * Uses browser-side Web Speech API — no external service needed.
     */
    public function evaluateTranscript(string $transcript, string $expectedText): array
    {
        $scores = $this->calculatePronunciationScores($transcript, $expectedText);

        return [
            'success' => true,
            'transcript' => $transcript,
            'expected_text' => $expectedText,
            'overall_score' => $scores['overall'],
            'clarity_score' => $scores['clarity'],
            'pronunciation_score' => $scores['pronunciation'],
            'fluency_score' => $scores['fluency'],
            'feedback' => $this->generateFeedback($scores),
        ];
    }

    /**
     * Calculate pronunciation scores by comparing transcribed text to expected text.
     */
    private function calculatePronunciationScores(string $transcript, string $expectedText): array
    {
        // Normalize texts
        $transcript = strtolower(trim(preg_replace('/[^\w\s]/', '', $transcript)));
        $expectedText = strtolower(trim(preg_replace('/[^\w\s]/', '', $expectedText)));

        // Pronunciation accuracy via text similarity
        $pronunciationScore = $this->calculateSimilarity($transcript, $expectedText);

        // Word-level accuracy
        $expectedWords = preg_split('/\s+/', $expectedText);
        $actualWords = preg_split('/\s+/', $transcript);

        $matchedWords = 0;
        foreach ($expectedWords as $word) {
            if (in_array($word, $actualWords)) {
                $matchedWords++;
            }
        }
        $wordAccuracy = count($expectedWords) > 0
            ? (int) round(($matchedWords / count($expectedWords)) * 100)
            : 0;

        // Clarity score based on word accuracy
        $clarityScore = $wordAccuracy;

        // Fluency score based on word count ratio
        $expectedCount = count($expectedWords);
        $actualCount = count($actualWords);
        $wordRatio = $expectedCount > 0
            ? min(100, ($actualCount / $expectedCount) * 100)
            : 0;
        $fluencyScore = (int) round(($wordRatio + $wordAccuracy) / 2);

        // Overall score (weighted average)
        $overallScore = (int) round(
            ($pronunciationScore * 0.4) +
            ($clarityScore * 0.3) +
            ($fluencyScore * 0.3)
        );

        return [
            'overall' => min(100, $overallScore),
            'clarity' => min(100, $clarityScore),
            'pronunciation' => min(100, $pronunciationScore),
            'fluency' => min(100, $fluencyScore),
        ];
    }

    /**
     * Calculate text similarity using Levenshtein distance.
     */
    private function calculateSimilarity(string $str1, string $str2): int
    {
        $maxLength = max(strlen($str1), strlen($str2));

        if ($maxLength === 0) {
            return 100;
        }

        $distance = levenshtein($str1, $str2);
        $similarity = (1 - ($distance / $maxLength)) * 100;

        return (int) round(max(0, $similarity));
    }

    /**
     * Generate feedback based on scores.
     */
    private function generateFeedback(array $scores): string
    {
        $feedback = [];

        if ($scores['overall'] >= 90) {
            $feedback[] = "Excellent pronunciation! Keep up the great work! 🌟";
        } elseif ($scores['overall'] >= 80) {
            $feedback[] = "Very good! Your pronunciation is clear and accurate. 👏";
        } elseif ($scores['overall'] >= 70) {
            $feedback[] = "Good effort! Practice more to improve your pronunciation. 💪";
        } elseif ($scores['overall'] >= 50) {
            $feedback[] = "Not bad! Focus on pronouncing each word more clearly. 🎯";
        } else {
            $feedback[] = "Keep practicing! Listen carefully and try to match the correct pronunciation. 📖";
        }

        if ($scores['clarity'] < 70) {
            $feedback[] = "Tip: Speak more clearly and enunciate each word.";
        }

        if ($scores['pronunciation'] < 70) {
            $feedback[] = "Tip: Listen to the reference audio and try to match the pronunciation.";
        }

        if ($scores['fluency'] < 70) {
            $feedback[] = "Tip: Try to speak at a natural pace, not too fast or too slow.";
        }

        return implode(' ', $feedback);
    }

    /**
     * Save pronunciation attempt.
     */
    public function savePronunciationAttempt(
        int $userId,
        int $exerciseId,
        int $lessonId,
        int $sentenceNumber,
        string $transcript,
        string $expectedText
    ): array {
        $analysis = $this->evaluateTranscript($transcript, $expectedText);

        if (!$analysis['success']) {
            return $analysis;
        }

        // Get attempt number
        $attemptNumber = \App\Models\PronunciationAttempt::where('user_id', $userId)
            ->where('pronunciation_exercise_id', $exerciseId)
            ->where('sentence_number', $sentenceNumber)
            ->count() + 1;

        // Create attempt record
        $attempt = \App\Models\PronunciationAttempt::create([
            'user_id' => $userId,
            'pronunciation_exercise_id' => $exerciseId,
            'lesson_id' => $lessonId,
            'attempt_number' => $attemptNumber,
            'sentence_number' => $sentenceNumber,
            'audio_recording_path' => null,
            'recording_duration' => 0,
            'overall_score' => $analysis['overall_score'],
            'clarity_score' => $analysis['clarity_score'],
            'pronunciation_score' => $analysis['pronunciation_score'],
            'fluency_score' => $analysis['fluency_score'],
            'feedback_text' => $analysis['feedback'],
            'ai_provider' => 'Web Speech API',
        ]);

        // Award points if passed
        $attempt->awardPoints();

        return [
            'success' => true,
            'attempt' => $attempt,
            'analysis' => $analysis,
        ];
    }
}