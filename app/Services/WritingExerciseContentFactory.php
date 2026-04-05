<?php

namespace App\Services;

use App\Models\Lesson;

class WritingExerciseContentFactory
{
    public function buildForLesson(Lesson $lesson): array
    {
        [$arabicTitle, $englishTitle] = $this->splitBilingualTitle((string) $lesson->title);
        $topic = $this->classifyTopic($arabicTitle, $englishTitle);

        [$minWords, $maxWords] = $this->wordRangeForTopic($topic);

        $title = $englishTitle !== ''
            ? "Writing: {$englishTitle}"
            : 'Writing Practice';

        $prompt = $this->promptForTopic($topic, $englishTitle, $minWords, $maxWords);
        $instructions = $this->instructionsForTopic($topic, $minWords, $maxWords, $englishTitle);
        $modelAnswer = $this->modelAnswerForTopic($topic, $englishTitle, $minWords, $maxWords);

        return [
            'title' => $title,
            'prompt' => $prompt,
            'instructions' => $instructions,
            'min_words' => $minWords,
            'max_words' => $maxWords,
            'passing_score' => 70,
            'model_answer' => $modelAnswer,
            'rubric_json' => [
                'grammar' => 25,
                'vocabulary' => 25,
                'coherence' => 25,
                'task_completion' => 25,
            ],
        ];
    }

    private function splitBilingualTitle(string $title): array
    {
        $title = trim($title);

        foreach (['—', '-', '|'] as $sep) {
            if (str_contains($title, $sep)) {
                $parts = array_map('trim', explode($sep, $title, 2));
                if (count($parts) === 2) {
                    // Most lessons are "AR — EN"
                    return [$parts[0], $parts[1]];
                }
            }
        }

        return ['', $title];
    }

    private function classifyTopic(string $arabicTitle, string $englishTitle): string
    {
        $haystack = strtolower($englishTitle . ' ' . $arabicTitle);

        if (str_contains($haystack, 'start here') || str_contains($haystack, 'ابدأ هنا')) {
            return 'orientation';
        }
        if (str_contains($haystack, 'curriculum') || str_contains($haystack, 'المنهج')) {
            return 'orientation';
        }
        if (str_contains($haystack, 'contact') || str_contains($haystack, 'التواصل')) {
            return 'contact';
        }
        if (str_contains($haystack, 'how to solve') || str_contains($haystack, 'طريقة حل')) {
            return 'strategy';
        }
        if (str_contains($haystack, 'google translate') || str_contains($haystack, 'ترجمة قوقل')) {
            return 'strategy';
        }
        if (str_contains($haystack, 'placement test') || str_contains($haystack, 'تحديد المستوى')) {
            return 'exam_reflection';
        }
        if (str_contains($haystack, 'midterm') || str_contains($haystack, 'final exam') || str_contains($haystack, 'الاختبار')) {
            return 'exam_reflection';
        }
        if (str_contains($haystack, 'letters') || str_contains($haystack, 'الحروف')) {
            return 'letters';
        }
        if (str_contains($haystack, 'syllable') || str_contains($haystack, 'المقاطع')) {
            return 'syllables';
        }
        if (str_contains($haystack, 'verb forms') || str_contains($haystack, 'v1') || str_contains($haystack, 'v2') || str_contains($haystack, 'v3')) {
            return 'verb_forms';
        }
        if (str_contains($haystack, 'practice questions') || str_contains($haystack, 'أسئلة نموذجية')) {
            return 'practice';
        }
        if (str_contains($haystack, 'noun') || str_contains($haystack, 'الاسم')) {
            return 'grammar';
        }
        if (str_contains($haystack, 'pronoun') || str_contains($haystack, 'الضمير')) {
            return 'grammar';
        }
        if (str_contains($haystack, 'verb') || str_contains($haystack, 'الفعل')) {
            return 'grammar';
        }
        if (str_contains($haystack, 'adjective') || str_contains($haystack, 'الصفة')) {
            return 'grammar';
        }
        if (str_contains($haystack, 'adverb') || str_contains($haystack, 'الظرف')) {
            return 'grammar';
        }
        if (str_contains($haystack, 'preposition') || str_contains($haystack, 'حرف الجر')) {
            return 'prepositions';
        }
        if (str_contains($haystack, 'comma') || str_contains($haystack, 'full stop') || str_contains($haystack, 'apostrophe') || str_contains($haystack, 'question mark') || str_contains($haystack, 'exclamation') || str_contains($haystack, 'colon')
            || str_contains($haystack, 'الفاصلة') || str_contains($haystack, 'النقطة') || str_contains($haystack, 'الاستفهام') || str_contains($haystack, 'التعجب') || str_contains($haystack, 'النقطتان')) {
            return 'punctuation';
        }

        return 'generic';
    }

    private function wordRangeForTopic(string $topic): array
    {
        return match ($topic) {
            'letters' => [40, 70],
            'contact' => [40, 80],
            'orientation' => [60, 90],
            'strategy' => [60, 100],
            'syllables' => [50, 90],
            'verb_forms' => [70, 110],
            'practice' => [60, 100],
            'punctuation' => [70, 110],
            'prepositions' => [70, 110],
            'exam_reflection' => [90, 130],
            'grammar' => [80, 120],
            default => [70, 110],
        };
    }

    private function promptForTopic(string $topic, string $englishTitle, int $minWords, int $maxWords): string
    {
        $context = $englishTitle !== '' ? "Lesson topic: {$englishTitle}.\n\n" : '';

        return $context . match ($topic) {
            'orientation' => "Write {$minWords}-{$maxWords} words about your goals for this course and how you will study each week.",
            'contact' => "Write {$minWords}-{$maxWords} words: a short message to your teacher introducing yourself and asking one helpful question about the course.",
            'strategy' => "Write {$minWords}-{$maxWords} words explaining your personal strategy to learn English and solve questions effectively. Include examples of what you will do.",
            'letters' => "Write {$minWords}-{$maxWords} words using simple vocabulary. Include at least 6 words related to today's letters, then use 3 of them in full sentences.",
            'syllables' => "Write {$minWords}-{$maxWords} words. Include 6 words and show their syllable breaks using hyphens (example: com-pu-ter). Then write 3 sentences using any 3 of the words.",
            'verb_forms' => "Write {$minWords}-{$maxWords} words. Choose 4 common verbs and write sentences using V1, V2, and V3 forms (at least 8 sentences total).",
            'practice' => "Write {$minWords}-{$maxWords} words. Summarize what you practiced and write 5 short example sentences that follow the lesson rules.",
            'punctuation' => "Write {$minWords}-{$maxWords} words. Write a short paragraph and use the punctuation mark from this lesson correctly at least 5 times.",
            'prepositions' => "Write {$minWords}-{$maxWords} words. Write 10 sentences using different prepositions (in, on, at, to, by, etc.).",
            'exam_reflection' => "Write {$minWords}-{$maxWords} words reflecting on what you learned, what is still difficult, and what you will practice next. Add 2 examples.",
            'grammar' => "Write {$minWords}-{$maxWords} words. Write a clear paragraph that uses the grammar point from this lesson and includes examples.",
            default => "Write {$minWords}-{$maxWords} words. Explain the main idea of the lesson in your own words and add 3 examples.",
        };
    }

    private function instructionsForTopic(string $topic, int $minWords, int $maxWords, string $englishTitle): string
    {
        $topicLineAr = $englishTitle !== '' ? "موضوع الدرس: {$englishTitle}" : 'موضوع الدرس: هذا الدرس';
        $topicLineEn = $englishTitle !== '' ? "Lesson topic: {$englishTitle}" : 'Lesson topic: this lesson';

        $ar = [
            $topicLineAr,
            "اكتب بالإنجليزي من {$minWords} إلى {$maxWords} كلمة.",
            "لا تكتب عربي داخل الإجابة.",
            "استخدم جُمل كاملة وعلامات ترقيم.",
        ];
        $en = [
            $topicLineEn,
            "Write in English ({$minWords}-{$maxWords} words).",
            "Do not use Arabic in your answer.",
            "Use complete sentences and punctuation.",
        ];

        $extra = match ($topic) {
            'verb_forms' => [
                'ar' => ['استخدم أمثلة واضحة للفعل في V1 و V2 و V3.'],
                'en' => ['Use clear examples for V1, V2, and V3.'],
            ],
            'syllables' => [
                'ar' => ['اكتب 6 كلمات واظهر تقسيم المقاطع باستخدام (-).'],
                'en' => ['Write 6 words and show syllable breaks using hyphens (-).'],
            ],
            'punctuation' => [
                'ar' => ['ركز على استخدام علامة الترقيم بشكل صحيح.'],
                'en' => ['Focus on using the punctuation mark correctly.'],
            ],
            'prepositions' => [
                'ar' => ['استخدم حروف جر مختلفة وتجنب تكرار نفس الجملة.'],
                'en' => ['Use different prepositions and avoid repeating the same sentence pattern.'],
            ],
            default => ['ar' => [], 'en' => []],
        };

        $ar = array_merge($ar, $extra['ar']);
        $en = array_merge($en, $extra['en']);

        return "AR:\n- " . implode("\n- ", $ar) . "\n\nEN:\n- " . implode("\n- ", $en);
    }

    private function modelAnswerForTopic(string $topic, string $englishTitle, int $minWords, int $maxWords): ?string
    {
        // Keep it short and safe. Admin can overwrite later.
        $topicHint = $englishTitle !== '' ? $englishTitle : 'this lesson';

        $answer = match ($topic) {
            'orientation' => "My goal in this course is to improve my speaking, writing, and grammar. I will study five days a week for about 30 minutes. I will review new words, write short sentences, and practice with quizzes. Every weekend, I will summarize what I learned and repeat the difficult parts. This plan will help me stay consistent and make real progress.",
            'contact' => "Hello teacher, my name is Ahmed and I am excited to start this course. I want to improve my English for work and daily life. I will practice every day and review the lessons carefully. Could you please tell me the best way to practice writing and remember new vocabulary? Thank you for your help.",
            'strategy' => "To learn English faster, I will focus on understanding the idea first, then the details. I will read the question twice, underline keywords, and eliminate wrong choices. I will write short notes and make example sentences for every new rule. I will also review mistakes and repeat the same type of questions until I improve.",
            'letters' => "Today I practiced simple words and spelling. I wrote words like cat, map, sun, fish, chair, and phone. Then I used them in sentences: The cat is small. I sit on a chair. I use my phone every day. Writing sentences helps me remember words and improve my spelling.",
            'syllables' => "Syllables help me pronounce and spell words correctly. For example: com-pu-ter, in-for-ma-tion, pho-to-graph, a-mazing, dif-fi-cult, and re-mem-ber. I can practice by saying each part slowly and then saying the whole word. This makes my pronunciation clearer and my reading faster.",
            'verb_forms' => "I practiced verb forms today. I use go (go, went, gone): I go to school every day. Yesterday I went late. I have gone there many times. I use take (take, took, taken): I take the bus. I took it yesterday. I have taken many trips. This helps me use tenses correctly.",
            'practice' => "Today I practiced the lesson rules and wrote examples. I learned a new idea and I tried to use it in sentences. Example 1: I study English every day. Example 2: She reads a book after school. Example 3: They play football on Friday. Practice makes me more confident and accurate.",
            'punctuation' => "Punctuation makes writing clear and easy to read. In my paragraph, I use punctuation correctly. I stop sentences with a full stop. I ask questions with a question mark. I also use commas to separate ideas, and I check my writing before I submit it. Good punctuation improves meaning.",
            'prepositions' => "Prepositions help me talk about time and place. I study in the morning. My book is on the table. I arrive at 8 o'clock. I go to school by bus. I sit next to my friend. I walk into the room. I practice every day to use prepositions correctly.",
            'exam_reflection' => "This exam helped me review important rules. I understood many points, but I still need more practice in grammar and writing. I will review my mistakes and write new examples. Next, I will practice for 20 minutes daily and focus on the hardest topics. For example, I will write short paragraphs and check my errors.",
            'grammar' => "In {$topicHint}, I learned a grammar point and practiced it with examples. I try to write clear sentences and choose the correct structure. For example, I use the rule in a simple sentence first, then I write a longer sentence. This helps me understand the rule and use it in real writing.",
            default => "In {$topicHint}, I learned the main idea and practiced it with examples. First, I understand the rule, then I write sentences. For example, I write one simple sentence and one longer sentence. Finally, I review my mistakes and rewrite the sentence in a better way.",
        };

        $wc = count(preg_split('/\s+/', trim($answer)) ?: []);
        if ($wc < max(20, (int) floor($minWords * 0.6))) {
            return null;
        }

        // Keep within a reasonable range; model answers are guidance, not strict.
        if ($wc > $maxWords + 40) {
            return null;
        }

        return $answer;
    }
}

