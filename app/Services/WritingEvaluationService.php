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
        $grammarIssues = $this->languageToolService->check($normalizedAnswer, 'en-US');

        $baseScores = $this->buildBaseScores($exercise, $normalizedAnswer, $wordCount, $grammarIssues, $locale);
        $aiFeedback = $this->localAiWritingCoachService->evaluate($exercise, $normalizedAnswer, $wordCount, $locale);
        $aiFeedback = $this->normalizeAiFeedbackLocale($aiFeedback, $locale);

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

    private function buildBaseScores(WritingExercise $exercise, string $answer, int $wordCount, array $grammarIssues, string $locale): array
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
            'summary' => $this->buildFallbackSummary($wordCount, $exercise, count($grammarIssues), $locale),
            'strengths' => $this->buildStrengths($wordCount, $exercise, $grammarIssues, $locale),
            'improvements' => $this->buildImprovements($wordCount, $exercise, $grammarIssues, $locale),
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

    private function buildFallbackSummary(int $wordCount, WritingExercise $exercise, int $grammarIssueCount, string $locale): string
    {
        $isArabic = $this->isArabicLocale($locale);

        if ($wordCount < $exercise->min_words) {
            return $isArabic
                ? 'إجابتك واضحة، لكنها تحتاج تفاصيل أكثر لإكمال المطلوب بالكامل.'
                : 'Your answer is clear, but it needs more detail to fully complete the task.';
        }

        if ($grammarIssueCount > 5) {
            return $isArabic
                ? 'أفكارك مفهومة، لكن الكتابة تحتاج ضبطًا أفضل للقواعد وبناء الجمل.'
                : 'Your ideas are understandable, but the writing needs cleaner grammar and sentence control.';
        }

        return $isArabic
            ? 'كتابتك تغطي المطلوب بشكل جيد. ركّز على تحسين القواعد والمفردات لتصبح أقوى.'
            : 'Your writing covers the task well. Focus on polishing grammar and vocabulary to make it stronger.';
    }

    private function buildStrengths(int $wordCount, WritingExercise $exercise, array $grammarIssues, string $locale): array
    {
        $isArabic = $this->isArabicLocale($locale);
        $strengths = [];

        if ($wordCount >= $exercise->min_words) {
            $strengths[] = $isArabic
                ? 'كتبت عدد كلمات مناسب لتغطية المطلوب.'
                : 'You wrote enough to address the task.';
        }

        if (count($grammarIssues) <= 3) {
            $strengths[] = $isArabic
                ? 'معظم الجمل واضحة وسهلة المتابعة.'
                : 'Most sentences are easy to follow.';
        }

        $strengths[] = $isArabic
            ? 'إجابتك مرتبطة بموضوع الدرس.'
            : 'Your answer stays connected to the lesson prompt.';

        return array_slice($strengths, 0, 3);
    }

    private function buildImprovements(int $wordCount, WritingExercise $exercise, array $grammarIssues, string $locale): array
    {
        $isArabic = $this->isArabicLocale($locale);
        $items = [];

        if ($wordCount < $exercise->min_words) {
            $items[] = $isArabic
                ? 'أضف تفاصيل داعمة أكثر حتى تصل للعدد المطلوب من الكلمات.'
                : 'Add more supporting details so your answer reaches the target length.';
        }

        if (!empty($grammarIssues)) {
            $items[] = $isArabic
                ? 'راجع أخطاء القواعد والإملاء قبل إعادة الإرسال.'
                : 'Revise grammar and spelling errors before submitting again.';
        }

        $items[] = $isArabic
            ? 'استخدم مجموعة أوسع من كلمات الربط والمفردات في المحاولة القادمة.'
            : 'Use a wider range of linking words and vocabulary in your next draft.';

        return array_slice($items, 0, 3);
    }

    private function countWords(string $text): int
    {
        return collect(preg_split('/\s+/', trim($text)) ?: [])
            ->filter(fn ($word) => $word !== '')
            ->count();
    }

    private function normalizeAiFeedbackLocale(?array $aiFeedback, string $locale): ?array
    {
        if ($aiFeedback === null) {
            return null;
        }

        if (!$this->isArabicLocale($locale)) {
            return $aiFeedback;
        }

        $textParts = array_filter([
            $aiFeedback['summary'] ?? '',
            ...($aiFeedback['strengths'] ?? []),
            ...($aiFeedback['improvements'] ?? []),
        ], fn ($item) => is_string($item) && trim($item) !== '');

        if (empty($textParts)) {
            return null;
        }

        $joined = implode(' ', $textParts);
        if ($this->containsArabic($joined)) {
            return $aiFeedback;
        }

        // If Arabic UI requested but model returned non-Arabic feedback,
        // fallback text will be used from localized base scores.
        $aiFeedback['summary'] = null;
        $aiFeedback['strengths'] = null;
        $aiFeedback['improvements'] = null;

        return $aiFeedback;
    }

    private function isArabicLocale(string $locale): bool
    {
        return str_starts_with(strtolower($locale), 'ar');
    }

    private function containsArabic(string $text): bool
    {
        return preg_match('/\p{Arabic}/u', $text) === 1;
    }
}
