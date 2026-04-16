<?php

namespace App\Services;

use App\Models\WritingExercise;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalAiWritingCoachService
{
    public function evaluate(WritingExercise $exercise, string $answer, int $wordCount, string $locale = 'en', string $taskType = 'free_writing'): ?array
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
                    'prompt' => $this->buildPrompt($exercise, $answer, $wordCount, $locale, $taskType),
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

    private function buildPrompt(WritingExercise $exercise, string $answer, int $wordCount, string $locale, string $taskType = 'free_writing'): string
    {
        $isArabic = str_starts_with(strtolower($locale), 'ar');
        $feedbackLanguage = $isArabic ? 'Arabic' : 'English';
        $languageRule = $isArabic
            ? 'summary, strengths, and improvements MUST be written in Modern Standard Arabic (Fusha) only, without dialect. rewrite_suggestion MUST stay in English unless the task is translation_en_ar (Arabic target).'
            : 'summary, strengths, improvements, and rewrite_suggestion MUST be written in English unless the task is translation_en_ar (Arabic target), in which case rewrite_suggestion is Arabic.';
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

        $modelAnswer = trim((string) $exercise->model_answer);
        $modelAnswerLine = $modelAnswer === '' ? 'None provided' : $modelAnswer;

        $exerciseTypeBlock = $this->buildTaskTypeBlock($taskType);

        return <<<PROMPT
You are a writing coach for English learners.
Evaluate the student's answer and return JSON only.

Feedback language: {$feedbackLanguage}
Language rule: {$languageRule}
Task type: {$taskType}
Prompt title: {$exercise->title}
Task prompt: {$exercise->prompt}
Instructions: {$exercise->instructions}
Min words: {$exercise->min_words}
Max words: {$exercise->max_words}
Target lesson vocabulary: {$targetVocabularyLine}
Vocabulary rule: {$vocabularyRule}
Expected / model answer: {$modelAnswerLine}
Student word count: {$wordCount}
{$exerciseTypeBlock}
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

    private function buildTaskTypeBlock(string $taskType): string
    {
        switch ($taskType) {
            case 'pattern_mapping':
                return <<<BLOCK

Exercise type: Pronunciation / spelling pattern mapping.
The student lists phoneme or spelling patterns (e.g. "sh → ship", "ch → chair", "th → think").
- Do NOT treat digraph tokens (sh, ch, th, wh, ph, ck, ng, qu, bl, tr, sl, sp, st, etc.) as misspellings.
- Check that each pattern (left side of →) actually appears inside the example word (right side).
- grammar_score focuses on mapping correctness, not full-sentence grammar.
- task_score reflects coverage of the required patterns from the instructions.
- vocabulary_score reflects variety and correctness of the example words.
- rewrite_suggestion is a cleaned-up list using the SAME format "pattern → word".
- Do not demand full prose sentences.

BLOCK;

            case 'single_word':
            case 'identification':
                return <<<BLOCK

Exercise type: Single-word answer.
- The student is expected to reply with ONE correct word (or a very short phrase).
- Compare the answer directly against the expected / model answer. Ignore case and punctuation.
- If the answer matches, give 95-100 across all scores.
- If the answer is a near-match with only a minor spelling slip, give 70-85.
- If the answer is wrong, keep scores low (below 50) and set rewrite_suggestion to the correct answer.
- Do NOT penalise for short length — this task only needs one word.
- grammar_score and coherence_score should reflect correctness of the single word, not sentence grammar.

BLOCK;

            case 'fill_blank':
                return <<<BLOCK

Exercise type: Fill in the blank.
- The student provides the word(s) that complete the blank in the prompt.
- Compare against the expected / model answer. Accept case-insensitive matches.
- Correct answer -> near 100 across scores. Wrong answer -> low task_score.
- Do NOT penalise for short length or missing punctuation.
- rewrite_suggestion is the full sentence with the correct word filled in.

BLOCK;

            case 'word_transform':
                return <<<BLOCK

Exercise type: Word transformation (plural, singular, prefix, suffix, past tense, comparative, antonym, etc.).
- The student must produce the transformed form of a given word.
- Compare against the expected / model answer exactly. Spelling matters.
- Minor spelling typos -> partial credit (around 70).
- Wrong transformation -> low task_score and low overall_score.
- rewrite_suggestion is the correct transformation.
- Do NOT penalise for short length.

BLOCK;

            case 'translation_ar_en':
                return <<<BLOCK

Exercise type: Translation from Arabic to English.
- The student reads an Arabic sentence/phrase and writes its English translation.
- Evaluate: meaning accuracy, grammar, word choice, and natural English phrasing.
- Perfect meaning with minor grammar issues -> 80-90.
- Partial meaning -> 50-70. Wrong meaning -> below 40.
- grammar_score focuses on the English output only (NOT the Arabic source).
- vocabulary_score reflects appropriate English word choice for the Arabic meaning.
- rewrite_suggestion is a polished English translation.
- Do NOT penalise for not matching the Arabic word order — good translations reorder naturally.

BLOCK;

            case 'translation_en_ar':
                return <<<BLOCK

Exercise type: Translation from English to Arabic.
- The student reads an English sentence/phrase and writes its Arabic translation.
- Evaluate: meaning accuracy, Arabic grammar, vocabulary, and natural Fusha phrasing.
- Perfect meaning with minor Arabic grammar issues -> 80-90.
- Partial meaning -> 50-70. Wrong meaning -> below 40.
- grammar_score focuses on the Arabic output only.
- rewrite_suggestion is a polished Arabic translation (this is the ONLY case where rewrite_suggestion may be Arabic).

BLOCK;

            case 'short_sentence':
                return <<<BLOCK

Exercise type: Short sentence.
- The student writes a single short sentence (usually using a target word or structure).
- Check: the sentence is grammatical, uses the target word correctly, and makes sense.
- Accept short lengths (one sentence is enough). Do NOT demand multiple sentences.
- If the student just copied the prompt without producing a real sentence, give a low task_score.

BLOCK;

            case 'free_writing':
            default:
                return '';
        }
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
