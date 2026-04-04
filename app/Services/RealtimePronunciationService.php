<?php

namespace App\Services;

class RealtimePronunciationService
{
    /**
     * Compare spoken text with expected sentence and return diff + scores.
     */
    public function compare(string $expectedText, string $spokenText): array
    {
        $expectedTokens = $this->tokenize($expectedText);
        $spokenTokens = $this->tokenize($spokenText);
        $wordDiff = $this->buildWordDiff($expectedTokens, $spokenTokens);

        $counts = [
            'expected' => count($expectedTokens),
            'spoken' => count($spokenTokens),
            'correct' => 0,
            'wrong' => 0,
            'missing' => 0,
            'extra' => 0,
        ];

        foreach ($wordDiff as $item) {
            if (isset($counts[$item['status']])) {
                $counts[$item['status']]++;
            }
        }

        $expectedCount = max(1, $counts['expected']);
        $accuracy = (int) round(($counts['correct'] / $expectedCount) * 100);
        $completion = (int) round((($counts['correct'] + $counts['wrong']) / $expectedCount) * 100);
        $wrongPenalty = (int) round(($counts['wrong'] / $expectedCount) * 35);
        $extraPenalty = (int) round(($counts['extra'] / $expectedCount) * 20);

        $pronunciation = max(0, min(100, $accuracy - $wrongPenalty));
        $clarity = $accuracy;
        $fluency = max(0, min(100, $completion - (int) round($extraPenalty / 2)));
        $overall = max(0, min(100, (int) round(($pronunciation * 0.6) + ($completion * 0.4) - $extraPenalty)));

        return [
            'word_diff' => $wordDiff,
            'counts' => $counts,
            'scores' => [
                'overall' => $overall,
                'pronunciation' => $pronunciation,
                'clarity' => $clarity,
                'fluency' => $fluency,
                'completion' => max(0, min(100, $completion)),
                'accuracy' => max(0, min(100, $accuracy)),
            ],
            'feedback' => $this->buildFeedback($overall, $completion, $counts),
        ];
    }

    /**
     * Normalize and split text into lowercase tokens.
     */
    private function tokenize(string $text): array
    {
        $normalized = mb_strtolower($text, 'UTF-8');
        $normalized = preg_replace("/[^\p{L}\p{N}\s']/u", ' ', $normalized) ?? '';
        $normalized = trim(preg_replace('/\s+/u', ' ', $normalized) ?? '');

        if ($normalized === '') {
            return [];
        }

        return preg_split('/\s+/u', $normalized) ?: [];
    }

    /**
     * Build a word-level diff array with statuses:
     * correct | wrong | missing | extra
     */
    private function buildWordDiff(array $expectedTokens, array $spokenTokens): array
    {
        $n = count($expectedTokens);
        $m = count($spokenTokens);

        $dp = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

        for ($i = 0; $i <= $n; $i++) {
            $dp[$i][0] = $i;
        }
        for ($j = 0; $j <= $m; $j++) {
            $dp[0][$j] = $j;
        }

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $cost = $expectedTokens[$i - 1] === $spokenTokens[$j - 1] ? 0 : 1;
                $dp[$i][$j] = min(
                    $dp[$i - 1][$j] + 1,         // missing (delete expected)
                    $dp[$i][$j - 1] + 1,         // extra (insert spoken)
                    $dp[$i - 1][$j - 1] + $cost  // match/substitute
                );
            }
        }

        $i = $n;
        $j = $m;
        $diff = [];

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0) {
                $cost = $expectedTokens[$i - 1] === $spokenTokens[$j - 1] ? 0 : 1;
                if ($dp[$i][$j] === $dp[$i - 1][$j - 1] + $cost) {
                    if ($cost === 0) {
                        $diff[] = [
                            'status' => 'correct',
                            'expected' => $expectedTokens[$i - 1],
                            'actual' => $spokenTokens[$j - 1],
                            'display' => $expectedTokens[$i - 1],
                        ];
                    } else {
                        $diff[] = [
                            'status' => 'wrong',
                            'expected' => $expectedTokens[$i - 1],
                            'actual' => $spokenTokens[$j - 1],
                            'display' => $spokenTokens[$j - 1],
                        ];
                    }
                    $i--;
                    $j--;
                    continue;
                }
            }

            if ($i > 0 && $dp[$i][$j] === $dp[$i - 1][$j] + 1) {
                $diff[] = [
                    'status' => 'missing',
                    'expected' => $expectedTokens[$i - 1],
                    'actual' => null,
                    'display' => $expectedTokens[$i - 1],
                ];
                $i--;
                continue;
            }

            if ($j > 0 && $dp[$i][$j] === $dp[$i][$j - 1] + 1) {
                $diff[] = [
                    'status' => 'extra',
                    'expected' => null,
                    'actual' => $spokenTokens[$j - 1],
                    'display' => $spokenTokens[$j - 1],
                ];
                $j--;
                continue;
            }

            // Safety fallback (should not happen, but avoids infinite loop).
            if ($i > 0) {
                $i--;
            } elseif ($j > 0) {
                $j--;
            }
        }

        return array_reverse($diff);
    }

    private function buildFeedback(int $overall, int $completion, array $counts): string
    {
        if ($overall >= 90) {
            return 'Excellent pronunciation and word matching.';
        }
        if ($overall >= 75) {
            return 'Very good. Try reducing wrong substitutions for higher accuracy.';
        }
        if ($overall >= 60) {
            return 'Good attempt. Focus on missing words and clearer pronunciation.';
        }

        if ($completion < 60) {
            return 'You missed many words. Try speaking the full sentence at a steady pace.';
        }

        if ($counts['extra'] > $counts['missing']) {
            return 'You added extra words. Keep closer to the original sentence.';
        }

        return 'Keep practicing. Listen to the model sentence and repeat more clearly.';
    }
}

