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
        $rawGrammarIssues = $this->languageToolService->check($normalizedAnswer, 'en-US');
        $grammarIssues = $this->formatGrammarIssuesForUi($rawGrammarIssues, $normalizedAnswer, $locale);

        $baseScores = $this->buildBaseScores($exercise, $normalizedAnswer, $wordCount, $rawGrammarIssues, $locale);
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

        $textParts = array_filter([
            $aiFeedback['summary'] ?? '',
            ...($aiFeedback['strengths'] ?? []),
            ...($aiFeedback['improvements'] ?? []),
        ], fn ($item) => is_string($item) && trim($item) !== '');

        if (empty($textParts)) {
            return null;
        }

        $joined = implode(' ', $textParts);
        $cleanSummary = $this->cleanFeedbackText((string) ($aiFeedback['summary'] ?? ''));
        $cleanStrengths = collect($aiFeedback['strengths'] ?? [])
            ->map(fn ($item) => $this->cleanFeedbackText((string) $item))
            ->filter()
            ->take(3)
            ->values()
            ->all();
        $cleanImprovements = collect($aiFeedback['improvements'] ?? [])
            ->map(fn ($item) => $this->cleanFeedbackText((string) $item))
            ->filter()
            ->take(3)
            ->values()
            ->all();

        $isCorrupted = $this->containsCjk($joined) || $this->containsInstructionLeakage($joined);
        $isArabic = $this->isArabicLocale($locale);
        $letters = $this->countLetters($joined);
        $arabicLetters = $this->countArabicLetters($joined);
        $latinLetters = $this->countLatinLetters($joined);

        if ($isArabic) {
            if ($letters > 0 && $arabicLetters < max(8, (int) floor($letters * 0.35))) {
                $isCorrupted = true;
            }
        } else {
            if ($letters > 0 && $latinLetters < max(8, (int) floor($letters * 0.45))) {
                $isCorrupted = true;
            }
        }

        if (!$isCorrupted && $cleanSummary !== '' && mb_strlen($cleanSummary, 'UTF-8') >= 12) {
            $aiFeedback['summary'] = $cleanSummary;
            $aiFeedback['strengths'] = $cleanStrengths;
            $aiFeedback['improvements'] = $cleanImprovements;

            return $aiFeedback;
        }

        // Corrupted or wrong-language AI text: keep numeric scores,
        // but fallback to deterministic localized text for feedback blocks.
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

    private function containsCjk(string $text): bool
    {
        return preg_match('/[\x{3040}-\x{30ff}\x{3400}-\x{4dbf}\x{4e00}-\x{9fff}\x{f900}-\x{faff}]/u', $text) === 1;
    }

    private function containsInstructionLeakage(string $text): bool
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $needles = [
            'return strictly valid json',
            'json only',
            'keys:',
            'student answer',
            'feedback language',
            'language rule',
            'format',
            'json格式',
            '严格',
            '键',
            'must be written',
        ];

        foreach ($needles as $needle) {
            if (str_contains($lower, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function cleanFeedbackText(string $text): string
    {
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text) ?? '';
        $text = preg_replace('/\s+/u', ' ', trim($text)) ?? '';

        return $text;
    }

    private function countLetters(string $text): int
    {
        return preg_match_all('/\p{L}/u', $text);
    }

    private function countArabicLetters(string $text): int
    {
        return preg_match_all('/\p{Arabic}/u', $text);
    }

    private function countLatinLetters(string $text): int
    {
        return preg_match_all('/[A-Za-z]/u', $text);
    }

    private function formatGrammarIssuesForUi(array $issues, string $answer, string $locale): array
    {
        $isArabic = $this->isArabicLocale($locale);

        return collect($issues)
            ->map(function (array $issue) use ($answer, $isArabic): ?array {
                $offset = max(0, (int) ($issue['offset'] ?? 0));
                $length = max(0, (int) ($issue['length'] ?? 0));

                $fragment = $this->safeSubstr($answer, $offset, max(1, $length));
                $context = $this->extractContext($answer, $offset, max(1, $length));
                $replacements = $this->sanitizeReplacements((array) ($issue['replacements'] ?? []), $fragment);

                $message = (string) ($issue['message'] ?? '');
                if ($message === '') {
                    return null;
                }

                $category = strtolower((string) ($issue['category'] ?? ''));
                $ruleId = strtolower((string) ($issue['rule_id'] ?? ''));
                $messageOut = $isArabic
                    ? $this->localizedGrammarMessage($category, $ruleId)
                    : $message;

                return [
                    'message' => $messageOut,
                    'offset' => $offset,
                    'length' => $length,
                    'context' => $context !== '' ? $context : $fragment,
                    'replacements' => $replacements,
                ];
            })
            ->filter()
            ->take(6)
            ->values()
            ->all();
    }

    private function localizedGrammarMessage(string $category, string $ruleId): string
    {
        if (str_contains($category, 'typo') || str_contains($ruleId, 'spell') || str_contains($ruleId, 'typo')) {
            return 'يوجد خطأ إملائي محتمل في هذا الجزء.';
        }

        if (str_contains($category, 'punct')) {
            return 'تحقق من علامة الترقيم في هذا الجزء.';
        }

        if (str_contains($category, 'grammar') || str_contains($ruleId, 'grammar')) {
            return 'يوجد خطأ نحوي محتمل في هذا الجزء.';
        }

        return 'يوجد تنبيه لغوي يحتاج مراجعة.';
    }

    private function sanitizeReplacements(array $replacements, string $source): array
    {
        $sourceNorm = mb_strtolower(trim($source), 'UTF-8');

        return collect($replacements)
            ->map(fn ($item) => trim((string) $item))
            ->filter(function (string $candidate) use ($sourceNorm): bool {
                if ($candidate === '') {
                    return false;
                }

                if (mb_strlen($candidate, 'UTF-8') > 32) {
                    return false;
                }

                if (preg_match('/\d/u', $candidate) === 1) {
                    return false;
                }

                if (preg_match('/^[A-Z]{2,3}$/', $candidate) === 1) {
                    return false;
                }

                if (preg_match('/^[\p{L}\s\'-]+$/u', $candidate) !== 1) {
                    return false;
                }

                return mb_strtolower($candidate, 'UTF-8') !== $sourceNorm;
            })
            ->unique(fn ($item) => mb_strtolower($item, 'UTF-8'))
            ->take(3)
            ->values()
            ->all();
    }

    private function extractContext(string $text, int $offset, int $length): string
    {
        $textLength = mb_strlen($text, 'UTF-8');
        if ($textLength === 0) {
            return '';
        }

        $safeOffset = min(max(0, $offset), $textLength);
        $safeLength = min(max(1, $length), max(1, $textLength - $safeOffset));

        $start = max(0, $safeOffset - 20);
        $end = min($textLength, $safeOffset + $safeLength + 20);

        return trim(mb_substr($text, $start, $end - $start, 'UTF-8'));
    }

    private function safeSubstr(string $text, int $offset, int $length): string
    {
        $textLength = mb_strlen($text, 'UTF-8');
        if ($textLength === 0) {
            return '';
        }

        $safeOffset = min(max(0, $offset), $textLength);
        $safeLength = min(max(1, $length), max(1, $textLength - $safeOffset));

        return trim(mb_substr($text, $safeOffset, $safeLength, 'UTF-8'));
    }
}
