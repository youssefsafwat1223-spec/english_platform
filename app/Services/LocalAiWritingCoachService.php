<?php

namespace App\Services;

use App\Models\WritingExercise;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalAiWritingCoachService
{
    public function evaluate(WritingExercise $exercise, string $answer, int $wordCount, string $locale = 'en'): ?array
    {
        if (!(bool) config('services.writing_ai.enabled')) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.writing_ai.ollama_url'), '/');
        $model = (string) config('services.writing_ai.ollama_model');

        if ($baseUrl === '' || $model === '' || trim($answer) === '') {
            return null;
        }

        try {
            $response = Http::timeout((int) config('services.writing_ai.timeout_seconds', 45))
                ->post($baseUrl . '/api/generate', [
                    'model' => $model,
                    'stream' => false,
                    'format' => 'json',
                    'prompt' => $this->buildPrompt($exercise, $answer, $wordCount, $locale),
                ]);

            if (!$response->successful()) {
                Log::warning('Ollama writing evaluation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $content = (string) ($response->json('response') ?? '');
            if ($content === '') {
                return null;
            }

            $decoded = json_decode($content, true);
            if (!is_array($decoded)) {
                return null;
            }

            return [
                'overall_score' => $this->normalizeScore($decoded['overall_score'] ?? null),
                'grammar_score' => $this->normalizeScore($decoded['grammar_score'] ?? null),
                'vocabulary_score' => $this->normalizeScore($decoded['vocabulary_score'] ?? null),
                'coherence_score' => $this->normalizeScore($decoded['coherence_score'] ?? null),
                'task_score' => $this->normalizeScore($decoded['task_score'] ?? null),
                'summary' => trim((string) ($decoded['summary'] ?? '')),
                'strengths' => $this->normalizeStringList($decoded['strengths'] ?? []),
                'improvements' => $this->normalizeStringList($decoded['improvements'] ?? []),
                'rewrite_suggestion' => trim((string) ($decoded['rewrite_suggestion'] ?? '')),
            ];
        } catch (\Throwable $e) {
            Log::warning('Ollama writing evaluation exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function buildPrompt(WritingExercise $exercise, string $answer, int $wordCount, string $locale): string
    {
        $isArabic = str_starts_with(strtolower($locale), 'ar');
        $feedbackLanguage = $isArabic ? 'Arabic' : 'English';
        $languageRule = $isArabic
            ? 'summary, strengths, and improvements MUST be written in Arabic only. rewrite_suggestion MUST stay in English.'
            : 'summary, strengths, improvements, and rewrite_suggestion MUST be written in English.';
        $rubric = is_array($exercise->rubric_json) ? $exercise->rubric_json : [];
        $requiredVocabularyUsage = max(0, (int) ($rubric['required_vocabulary_usage'] ?? 0));
        $targetVocabularyWords = collect($rubric['lesson_vocabulary'] ?? [])
            ->map(function (mixed $item): string {
                if (is_array($item)) {
                    return strtolower(trim((string) ($item['word'] ?? '')));
                }

                return strtolower(trim((string) $item));
            })
            ->filter()
            ->unique()
            ->take(15)
            ->values()
            ->all();
        $targetVocabularyLine = empty($targetVocabularyWords) ? 'None' : implode(', ', $targetVocabularyWords);
        $vocabularyRule = $requiredVocabularyUsage > 0 && !empty($targetVocabularyWords)
            ? "The student should naturally use at least {$requiredVocabularyUsage} words from this list when possible."
            : 'Evaluate vocabulary naturally based on range and appropriateness.';

        return <<<PROMPT
You are a writing coach for English learners.
Evaluate the student's answer and return JSON only.

Feedback language: {$feedbackLanguage}
Language rule: {$languageRule}
Prompt title: {$exercise->title}
Task prompt: {$exercise->prompt}
Instructions: {$exercise->instructions}
Min words: {$exercise->min_words}
Max words: {$exercise->max_words}
Target lesson vocabulary: {$targetVocabularyLine}
Vocabulary rule: {$vocabularyRule}
Student word count: {$wordCount}
Student answer:
{$answer}

Return strictly valid JSON with these keys:
overall_score, grammar_score, vocabulary_score, coherence_score, task_score,
summary, strengths, improvements, rewrite_suggestion

Rules:
- Scores are integers from 0 to 100
- summary is a short paragraph
- strengths is an array with up to 3 short bullet-style strings
- improvements is an array with up to 3 short bullet-style strings
- rewrite_suggestion is an improved version of the student's answer, but keep it close to the original level
- Follow the language rule strictly
- Do not include markdown
PROMPT;
    }

    private function normalizeScore(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return max(0, min(100, (int) round((float) $value)));
    }

    private function normalizeStringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->take(3)
            ->values()
            ->all();
    }
}
