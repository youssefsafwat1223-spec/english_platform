<?php

namespace App\Services;

class PronunciationRuleCoachService
{
    public function build(string $expectedText, string $spokenText, array $comparison, string $locale = 'en'): array
    {
        $wordDiff = $comparison['word_diff'] ?? [];
        $counts = $comparison['counts'] ?? [];
        $scores = $comparison['scores'] ?? [];

        $missingWords = $this->collectWordsByStatus($wordDiff, 'missing', 'expected');
        $wrongWords = $this->collectWrongPairs($wordDiff);
        $extraWords = $this->collectWordsByStatus($wordDiff, 'extra', 'actual');

        $patterns = $this->detectPatterns($wrongWords, $missingWords, $extraWords, $expectedText, $spokenText);
        $primaryPattern = $patterns[0] ?? null;
        $focusWord = $this->pickFocusWord($wrongWords, $missingWords, $extraWords);

        $title = $this->buildTitle($counts, $scores, $locale);
        $summary = $this->buildSummary($counts, $scores, $missingWords, $wrongWords, $extraWords, $focusWord, $locale);
        $tip = $this->buildTip($counts, $scores, $primaryPattern, $focusWord, $locale);
        $retryInstruction = $this->buildRetryInstruction($counts, $scores, $focusWord, $locale);

        return [
            'title' => $title,
            'summary' => $summary,
            'tip' => $tip,
            'retry_instruction' => $retryInstruction,
            'focus_word' => $focusWord,
            'patterns' => array_values(array_unique($patterns)),
        ];
    }

    private function collectWordsByStatus(array $wordDiff, string $status, string $key): array
    {
        $words = [];

        foreach ($wordDiff as $item) {
            if (($item['status'] ?? null) !== $status) {
                continue;
            }

            $value = trim((string) ($item[$key] ?? ''));
            if ($value !== '') {
                $words[] = $value;
            }
        }

        return $words;
    }

    private function collectWrongPairs(array $wordDiff): array
    {
        $pairs = [];

        foreach ($wordDiff as $item) {
            if (($item['status'] ?? null) !== 'wrong') {
                continue;
            }

            $expected = trim((string) ($item['expected'] ?? ''));
            $actual = trim((string) ($item['actual'] ?? ''));

            if ($expected === '' && $actual === '') {
                continue;
            }

            $pairs[] = [
                'expected' => $expected,
                'actual' => $actual,
            ];
        }

        return $pairs;
    }

    private function detectPatterns(array $wrongWords, array $missingWords, array $extraWords, string $expectedText, string $spokenText): array
    {
        $patterns = [];

        if (!empty($missingWords)) {
            $patterns[] = 'missing_words';
        }

        if (!empty($extraWords)) {
            $patterns[] = 'extra_words';
        }

        foreach ($wrongWords as $pair) {
            $expected = mb_strtolower($pair['expected'], 'UTF-8');
            $actual = mb_strtolower($pair['actual'], 'UTF-8');

            if (str_contains($expected, 'th')) {
                $patterns[] = 'th_sound';
            }

            if ((str_contains($expected, 'r') && str_contains($actual, 'l')) || (str_contains($expected, 'l') && str_contains($actual, 'r'))) {
                $patterns[] = 'r_l_sound';
            }

            if ((str_contains($expected, 'v') && str_contains($actual, 'f')) || (str_contains($expected, 'f') && str_contains($actual, 'v'))) {
                $patterns[] = 'v_f_sound';
            }

            if ((str_contains($expected, 'p') && str_contains($actual, 'b')) || (str_contains($expected, 'b') && str_contains($actual, 'p'))) {
                $patterns[] = 'p_b_sound';
            }

            if ((str_contains($expected, 's') && str_contains($actual, 'z')) || (str_contains($expected, 'z') && str_contains($actual, 's'))) {
                $patterns[] = 's_z_sound';
            }

            if ((str_contains($expected, 'ch') && str_contains($actual, 'sh')) || (str_contains($expected, 'sh') && str_contains($actual, 'ch'))) {
                $patterns[] = 'ch_sh_sound';
            }
        }

        $expectedTokens = preg_split('/\s+/u', trim(mb_strtolower($expectedText, 'UTF-8'))) ?: [];
        foreach ($expectedTokens as $token) {
            if (str_ends_with($token, 'ed') || str_ends_with($token, 's')) {
                if (in_array($token, $missingWords, true)) {
                    $patterns[] = 'word_ending';
                    break;
                }
            }
        }

        if (trim($spokenText) === '') {
            $patterns[] = 'no_speech';
        }

        return $patterns;
    }

    private function pickFocusWord(array $wrongWords, array $missingWords, array $extraWords): ?string
    {
        if (!empty($wrongWords[0]['expected'])) {
            return $wrongWords[0]['expected'];
        }

        if (!empty($missingWords[0])) {
            return $missingWords[0];
        }

        if (!empty($extraWords[0])) {
            return $extraWords[0];
        }

        return null;
    }

    private function buildTitle(array $counts, array $scores, string $locale): string
    {
        $overall = (int) ($scores['overall'] ?? 0);
        $missing = (int) ($counts['missing'] ?? 0);
        $extra = (int) ($counts['extra'] ?? 0);
        $wrong = (int) ($counts['wrong'] ?? 0);

        if ($missing > 0) {
            return $locale === 'ar' ? 'أكمل الجملة للنهاية' : 'Complete the full sentence';
        }

        if ($extra > 0 && $extra >= $wrong) {
            return $locale === 'ar' ? 'التزم بالنص المكتوب' : 'Stay close to the written sentence';
        }

        if ($wrong > 0) {
            return $locale === 'ar' ? 'ركز على نطق بعض الكلمات' : 'Focus on a few words';
        }

        if ($overall >= 85) {
            return $locale === 'ar' ? 'نطق ممتاز' : 'Excellent pronunciation';
        }

        return $locale === 'ar' ? 'محاولة جيدة' : 'Good attempt';
    }

    private function buildSummary(array $counts, array $scores, array $missingWords, array $wrongWords, array $extraWords, ?string $focusWord, string $locale): string
    {
        $overall = (int) ($scores['overall'] ?? 0);
        $completion = (int) ($scores['completion'] ?? 0);

        if (!empty($missingWords)) {
            $words = implode(', ', array_slice($missingWords, 0, 2));
            return $locale === 'ar'
                ? 'هناك كلمات ناقصة من الجملة مثل: ' . $words . '.'
                : 'You missed words from the sentence such as: ' . $words . '.';
        }

        if (!empty($extraWords) && count($extraWords) >= count($wrongWords)) {
            $words = implode(', ', array_slice($extraWords, 0, 2));
            return $locale === 'ar'
                ? 'أضفت كلمات ليست في الجملة مثل: ' . $words . '.'
                : 'You added words that are not in the sentence, such as: ' . $words . '.';
        }

        if (!empty($wrongWords)) {
            $pair = $wrongWords[0];
            return $locale === 'ar'
                ? 'الكلمة الأقرب للمشكلة هي "' . $pair['expected'] . '" وقد خرجت بشكل قريب من "' . $pair['actual'] . '".'
                : 'The main word to improve is "' . $pair['expected'] . '" and it sounded closer to "' . $pair['actual'] . '".';
        }

        if ($overall >= 85 && $completion >= 85) {
            return $locale === 'ar'
                ? 'النطق واضح والجملة مكتملة بشكل جيد.'
                : 'Your pronunciation is clear and the sentence is nearly complete.';
        }

        if ($focusWord) {
            return $locale === 'ar'
                ? 'أعد التركيز على كلمة "' . $focusWord . '" داخل الجملة.'
                : 'Focus again on the word "' . $focusWord . '" inside the sentence.';
        }

        return $locale === 'ar'
            ? 'تحتاج المحاولة إلى وضوح أكبر وسرعة أكثر ثباتًا.'
            : 'This attempt needs clearer speech and a steadier pace.';
    }

    private function buildTip(array $counts, array $scores, ?string $pattern, ?string $focusWord, string $locale): string
    {
        if (($counts['missing'] ?? 0) > 0) {
            return $locale === 'ar'
                ? 'اقرأ الجملة كاملة بدون توقف مبكر، وراقب الكلمات الأخيرة.'
                : 'Read the entire sentence without stopping early, especially the ending words.';
        }

        if (($counts['extra'] ?? 0) > 0 && ($counts['extra'] ?? 0) >= ($counts['wrong'] ?? 0)) {
            return $locale === 'ar'
                ? 'لا تضف كلمات من عندك، وامشِ مع النص كلمة بكلمة.'
                : 'Do not add your own words; follow the sentence word by word.';
        }

        return match ($pattern) {
            'th_sound' => $locale === 'ar'
                ? 'ركز على صوت th: أخرج طرف اللسان قليلًا بين الأسنان.'
                : 'Focus on the "th" sound: let the tip of your tongue come slightly between the teeth.',
            'r_l_sound' => $locale === 'ar'
                ? 'انتبه للفرق بين صوتي r و l وكرّر الكلمة ببطء.'
                : 'Pay attention to the difference between "r" and "l" and repeat the word slowly.',
            'v_f_sound' => $locale === 'ar'
                ? 'جرّب إبراز الفرق بين v و f مع هواء أوضح عند النطق.'
                : 'Make the difference between "v" and "f" clearer with sharper airflow.',
            'p_b_sound' => $locale === 'ar'
                ? 'فرّق بين p و b: الأولى هواؤها أقوى والثانية أخف.'
                : 'Separate "p" and "b": the first needs stronger air, the second is softer.',
            's_z_sound' => $locale === 'ar'
                ? 'فرّق بين s و z، وحافظ على صفير أوضح عند النطق.'
                : 'Separate "s" and "z" and keep the sound crisp.',
            'ch_sh_sound' => $locale === 'ar'
                ? 'انتبه للفرق بين ch و sh وكرّر الكلمة وحدها أولًا.'
                : 'Watch the difference between "ch" and "sh" and repeat the word by itself first.',
            'word_ending' => $locale === 'ar'
                ? 'لا تبتلع نهاية الكلمة، خاصة إذا كانت تنتهي بـ s أو ed.'
                : 'Do not swallow the word ending, especially when it ends with "s" or "ed".',
            default => $locale === 'ar'
                ? 'تحدث ببطء أكثر وافتح فمك بوضوح في كل كلمة.'
                : 'Speak a little slower and open your mouth clearly on each word.',
        };
    }

    private function buildRetryInstruction(array $counts, array $scores, ?string $focusWord, string $locale): string
    {
        $overall = (int) ($scores['overall'] ?? 0);

        if (($counts['missing'] ?? 0) > 0) {
            return $locale === 'ar'
                ? 'أعد الجملة من البداية للنهاية مرة واحدة بدون توقف.'
                : 'Try the full sentence again from beginning to end without stopping.';
        }

        if ($focusWord) {
            return $locale === 'ar'
                ? 'قل كلمة "' . $focusWord . '" وحدها أولًا، ثم أعد الجملة كاملة.'
                : 'Say "' . $focusWord . '" on its own first, then repeat the full sentence.';
        }

        if ($overall >= 85) {
            return $locale === 'ar'
                ? 'أعد المحاولة مرة أخيرة بنفس الهدوء لتحصل على نتيجة أعلى.'
                : 'Try once more with the same calm pace to push the score even higher.';
        }

        return $locale === 'ar'
            ? 'أعد المحاولة ببطء وثبات، ثم استمع للنطق الصحيح إذا احتجت.'
            : 'Try again slowly and steadily, then listen to the correct model if needed.';
    }
}
