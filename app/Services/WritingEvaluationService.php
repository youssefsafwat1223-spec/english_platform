<?php

namespace App\Services;

use App\Models\WritingExercise;

class WritingEvaluationService
{
    public function __construct(
        private readonly LanguageToolService $languageToolService,
        private readonly LocalAiWritingCoachService $localAiWritingCoachService
    ) {
    }

    public function evaluate(WritingExercise $exercise, string $answer, string $locale = 'en'): array
    {
        $normalizedAnswer = trim(preg_replace('/\s+/', ' ', $answer) ?? '');
        $wordCount = $this->countWords($normalizedAnswer);
        $grammarIssues = $this->languageToolService->check($normalizedAnswer, $locale);

        $baseScores = $this->buildBaseScores($exercise, $normalizedAnswer, $wordCount, $grammarIssues);
        $aiFeedback = $this->localAiWritingCoachService->evaluate($exercise, $normalizedAnswer, $wordCount, $locale);

        return [
            'word_count' => $wordCount,
            'overall_score' => $aiFeedback['overall_score'] ?? $baseScores['overall_score'],
            'grammar_score' => $aiFeedback['grammar_score'] ?? $baseScores['grammar_score'],
            'vocabulary_score' => $aiFeedback['vocabulary_score'] ?? $baseScores['vocabulary_score'],
            'coherence_score' => $aiFeedback['coherence_score'] ?? $baseScores['coherence_score'],
            'task_score' => $aiFeedback['task_score'] ?? $baseScores['task_score'],
            'summary' => $aiFeedback['summary'] ?? $baseScores['summary'],
            'strengths' => $aiFeedback['strengths'] ?? $baseScores['strengths'],
            'improvements' => $aiFeedback['improvements'] ?? $baseScores['improvements'],
            'rewrite_suggestion' => $aiFeedback['rewrite_suggestion'] ?? '',
            'grammar_issues' => $grammarIssues,
            'passed' => ($aiFeedback['overall_score'] ?? $baseScores['overall_score']) >= $exercise->passing_score,
        ];
    }

    private function buildBaseScores(WritingExercise $exercise, string $answer, int $wordCount, array $grammarIssues): array
    {
        $lengthPenalty = 0;
        if ($wordCount < $exercise->min_words) {
            $lengthPenalty += min(35, ($exercise->min_words - $wordCount));
        }

        if ($wordCount > $exercise->max_words) {
            $lengthPenalty += min(15, (int) floor(($wordCount - $exercise->max_words) / 5));
        }

        $grammarPenalty = min(35, count($grammarIssues) * 4);
        $grammarScore = max(35, 90 - $grammarPenalty);
        $taskScore = max(30, 90 - $lengthPenalty);
        $vocabularyScore = $this->estimateVocabularyScore($answer);
        $coherenceScore = $this->estimateCoherenceScore($answer);
        $overallScore = (int) round(($grammarScore + $taskScore + $vocabularyScore + $coherenceScore) / 4);

        return [
            'overall_score' => $overallScore,
            'grammar_score' => $grammarScore,
            'vocabulary_score' => $vocabularyScore,
            'coherence_score' => $coherenceScore,
            'task_score' => $taskScore,
            'summary' => $this->buildFallbackSummary($wordCount, $exercise, count($grammarIssues)),
            'strengths' => $this->buildStrengths($wordCount, $exercise, $grammarIssues),
            'improvements' => $this->buildImprovements($wordCount, $exercise, $grammarIssues),
        ];
    }

    private function estimateVocabularyScore(string $answer): int
    {
        $uniqueWords = collect(preg_split('/\s+/', strtolower($answer)) ?: [])
            ->map(fn ($word) => preg_replace('/[^a-z]/', '', $word) ?? '')
            ->filter()
            ->unique()
            ->count();

        return max(40, min(92, 45 + ($uniqueWords * 2)));
    }

    private function estimateCoherenceScore(string $answer): int
    {
        $sentenceCount = max(1, preg_match_all('/[.!?]+/', $answer));
        $avgSentenceLength = max(1, (int) round($this->countWords($answer) / $sentenceCount));

        if ($avgSentenceLength < 4) {
            return 50;
        }

        if ($avgSentenceLength > 28) {
            return 62;
        }

        return 78;
    }

    private function buildFallbackSummary(int $wordCount, WritingExercise $exercise, int $grammarIssueCount): string
    {
        if ($wordCount < $exercise->min_words) {
            return 'Your answer is clear, but it needs more detail to fully complete the task.';
        }

        if ($grammarIssueCount > 5) {
            return 'Your ideas are understandable, but the writing needs cleaner grammar and sentence control.';
        }

        return 'Your writing covers the task well. Focus on polishing grammar and vocabulary to make it stronger.';
    }

    private function buildStrengths(int $wordCount, WritingExercise $exercise, array $grammarIssues): array
    {
        $strengths = [];

        if ($wordCount >= $exercise->min_words) {
            $strengths[] = 'You wrote enough to address the task.';
        }

        if (count($grammarIssues) <= 3) {
            $strengths[] = 'Most sentences are easy to follow.';
        }

        $strengths[] = 'Your answer stays connected to the lesson prompt.';

        return array_slice($strengths, 0, 3);
    }

    private function buildImprovements(int $wordCount, WritingExercise $exercise, array $grammarIssues): array
    {
        $items = [];

        if ($wordCount < $exercise->min_words) {
            $items[] = 'Add more supporting details so your answer reaches the target length.';
        }

        if (!empty($grammarIssues)) {
            $items[] = 'Revise grammar and spelling errors before submitting again.';
        }

        $items[] = 'Use a wider range of linking words and vocabulary in your next draft.';

        return array_slice($items, 0, 3);
    }

    private function countWords(string $text): int
    {
        return collect(preg_split('/\s+/', trim($text)) ?: [])
            ->filter(fn ($word) => $word !== '')
            ->count();
    }
}
