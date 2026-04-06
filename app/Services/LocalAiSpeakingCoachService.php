<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalAiSpeakingCoachService
{
    public function evaluate(string $expectedText, string $spokenText, array $comparison, string $locale = 'en'): ?array
    {
        if (!(bool) config('services.speaking_ai.enabled')) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.speaking_ai.ollama_url'), '/');
        $model = trim((string) config('services.speaking_ai.ollama_model'));

        if ($baseUrl === '' || $model === '' || trim($expectedText) === '' || trim($spokenText) === '') {
            return null;
        }

        try {
            $response = Http::timeout((int) config('services.speaking_ai.timeout_seconds', 30))
                ->post($baseUrl . '/api/generate', [
                    'model' => $model,
                    'stream' => false,
                    'format' => 'json',
                    'prompt' => $this->buildPrompt($expectedText, $spokenText, $comparison, $locale),
                ]);

            if (!$response->successful()) {
                Log::warning('Ollama speaking evaluation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $content = trim((string) ($response->json('response') ?? ''));
            if ($content === '') {
                return null;
            }

            $decoded = json_decode($content, true);
            if (!is_array($decoded)) {
                return null;
            }

            $normalized = [
                'scores' => [
                    'overall' => $this->normalizeScore($decoded['overall_score'] ?? null),
                    'pronunciation' => $this->normalizeScore($decoded['pronunciation_score'] ?? null),
                    'fluency' => $this->normalizeScore($decoded['fluency_score'] ?? null),
                    'grammar' => $this->normalizeScore($decoded['grammar_score'] ?? null),
                ],
                'title' => $this->sanitizeFeedbackText((string) ($decoded['title'] ?? ''), $locale),
                'summary' => $this->sanitizeFeedbackText((string) ($decoded['summary'] ?? ''), $locale),
                'tip' => $this->sanitizeFeedbackText((string) ($decoded['tip'] ?? ''), $locale),
                'retry_instruction' => $this->sanitizeFeedbackText((string) ($decoded['retry_instruction'] ?? ''), $locale),
                'focus_word' => $this->sanitizeWord((string) ($decoded['focus_word'] ?? '')),
                'strengths' => $this->sanitizeList($decoded['strengths'] ?? [], $locale),
                'improvements' => $this->sanitizeList($decoded['improvements'] ?? [], $locale),
                'corrected_sentence' => $this->sanitizeCorrectedSentence((string) ($decoded['corrected_sentence'] ?? ''), $expectedText),
                'short_coach_reply' => $this->sanitizeFeedbackText((string) ($decoded['short_coach_reply'] ?? ''), $locale),
            ];

            $hasText = $normalized['summary'] !== '' || $normalized['tip'] !== '' || $normalized['retry_instruction'] !== '';
            $hasScores = collect($normalized['scores'])->filter(fn ($value) => $value !== null)->isNotEmpty();

            if (!$hasText && !$hasScores) {
                return null;
            }

            return $normalized;
        } catch (\Throwable $e) {
            Log::warning('Ollama speaking evaluation exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function buildPrompt(string $expectedText, string $spokenText, array $comparison, string $locale): string
    {
        $isArabic = str_starts_with(strtolower($locale), 'ar');
        $feedbackLanguage = $isArabic ? 'Arabic' : 'English';
        $languageRule = $isArabic
            ? 'All feedback text fields MUST be in Arabic. corrected_sentence MUST stay in English.'
            : 'All feedback text fields MUST be in English. corrected_sentence MUST stay in English.';

        $scores = $comparison['scores'] ?? [];
        $counts = $comparison['counts'] ?? [];

        $wordDiffCompact = collect($comparison['word_diff'] ?? [])
            ->take(80)
            ->map(function (array $item): array {
                return [
                    'status' => $item['status'] ?? null,
                    'expected' => $item['expected'] ?? null,
                    'actual' => $item['actual'] ?? null,
                ];
            })
            ->values()
            ->all();

        $wordDiffJson = json_encode($wordDiffCompact, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($wordDiffJson === false) {
            $wordDiffJson = '[]';
        }

        return <<<PROMPT
You are an English speaking coach for language learners.
Analyze the student's spoken transcript against the expected sentence.
Return JSON only.

Feedback language: {$feedbackLanguage}
Language rule: {$languageRule}

Expected sentence:
{$expectedText}

Student transcript:
{$spokenText}

Deterministic base scores:
- overall: {$this->safeInt($scores['overall'] ?? null)}
- pronunciation: {$this->safeInt($scores['pronunciation'] ?? null)}
- fluency: {$this->safeInt($scores['fluency'] ?? null)}
- accuracy: {$this->safeInt($scores['accuracy'] ?? null)}
- completion: {$this->safeInt($scores['completion'] ?? null)}

Word counts:
- expected: {$this->safeInt($counts['expected'] ?? null)}
- spoken: {$this->safeInt($counts['spoken'] ?? null)}
- correct: {$this->safeInt($counts['correct'] ?? null)}
- wrong: {$this->safeInt($counts['wrong'] ?? null)}
- missing: {$this->safeInt($counts['missing'] ?? null)}
- extra: {$this->safeInt($counts['extra'] ?? null)}

Word diff JSON:
{$wordDiffJson}

Return strictly valid JSON with these keys:
overall_score, pronunciation_score, fluency_score, grammar_score,
title, summary, tip, retry_instruction, focus_word,
strengths, improvements, corrected_sentence, short_coach_reply

Rules:
- All scores are integers from 0 to 100
- strengths and improvements are arrays with 1-3 short items each
- corrected_sentence should be a corrected/clean spoken sentence in English
- short_coach_reply should be one short motivating line
- Do not include markdown
PROMPT;
    }

    private function sanitizeList(mixed $value, string $locale): array
    {
        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn ($item) => $this->sanitizeFeedbackText((string) $item, $locale))
            ->filter()
            ->take(3)
            ->values()
            ->all();
    }

    private function sanitizeFeedbackText(string $text, string $locale): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        if ($text === '') {
            return '';
        }

        if ($this->containsPromptLeak($text) || $this->containsCjk($text)) {
            return '';
        }

        $isArabic = str_starts_with(strtolower($locale), 'ar');
        if ($isArabic) {
            if (!$this->containsArabic($text)) {
                return '';
            }
        } else {
            if (!$this->containsLatin($text)) {
                return '';
            }
        }

        return $text;
    }

    private function sanitizeCorrectedSentence(string $text, string $fallback): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        if ($text === '' || !$this->containsLatin($text) || $this->containsCjk($text)) {
            return trim($fallback);
        }

        return $text;
    }

    private function sanitizeWord(string $text): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        if ($text === '' || mb_strlen($text, 'UTF-8') > 60) {
            return '';
        }

        return $text;
    }

    private function normalizeScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return max(0, min(100, (int) round((float) $value)));
    }

    private function safeInt(mixed $value): int
    {
        return (int) ($value ?? 0);
    }

    private function containsArabic(string $text): bool
    {
        return preg_match('/\p{Arabic}/u', $text) === 1;
    }

    private function containsLatin(string $text): bool
    {
        return preg_match('/[A-Za-z]/u', $text) === 1;
    }

    private function containsCjk(string $text): bool
    {
        return preg_match('/[\x{3040}-\x{30ff}\x{3400}-\x{4dbf}\x{4e00}-\x{9fff}\x{f900}-\x{faff}]/u', $text) === 1;
    }

    private function containsPromptLeak(string $text): bool
    {
        $lower = mb_strtolower($text, 'UTF-8');
        $needles = [
            'return strictly valid json',
            'json only',
            'word diff',
            'expected sentence',
            'student transcript',
            'language rule',
            'feedback language',
        ];

        foreach ($needles as $needle) {
            if (str_contains($lower, $needle)) {
                return true;
            }
        }

        return false;
    }
}

