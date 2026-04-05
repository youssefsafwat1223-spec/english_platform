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

        $displayTopic = $englishTitle !== '' ? $englishTitle : ($arabicTitle !== '' ? $arabicTitle : 'this lesson');

        $title = $englishTitle !== ''
            ? "الكتابة - Writing: {$englishTitle}"
            : 'تدريب الكتابة - Writing Practice';

        $prompt = $this->promptForTopic($topic, $displayTopic, $minWords, $maxWords);
        $instructions = $this->instructionsForTopic($topic, $displayTopic, $minWords, $maxWords);
        $modelAnswer = $this->modelAnswerForTopic($topic, $displayTopic, $minWords, $maxWords);

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

        foreach ([' — ', ' – ', ' | ', 'â€”', '—', '–', '|'] as $separator) {
            if (!str_contains($title, $separator)) {
                continue;
            }

            $parts = array_map('trim', explode($separator, $title, 2));
            if (count($parts) !== 2) {
                continue;
            }

            return [$parts[0], $parts[1]];
        }

        if (preg_match('/^(.+?)\s-\s(.+)$/u', $title, $matches) === 1) {
            $left = trim($matches[1]);
            $right = trim($matches[2]);

            if ($this->containsArabic($left) && $this->containsLatin($right)) {
                return [$left, $right];
            }

            if ($this->containsLatin($left) && $this->containsArabic($right)) {
                return [$right, $left];
            }
        }

        if ($this->containsArabic($title)) {
            return [$title, ''];
        }

        return ['', $title];
    }

    private function classifyTopic(string $arabicTitle, string $englishTitle): string
    {
        $haystack = strtolower($englishTitle) . ' ' . $arabicTitle;

        if (str_contains($haystack, 'start here') || str_contains($haystack, 'ابدأ هنا') || str_contains($haystack, 'المنهج') || str_contains($haystack, 'curriculum')) {
            return 'orientation';
        }

        if (str_contains($haystack, 'contact') || str_contains($haystack, 'التواصل')) {
            return 'contact';
        }

        if (str_contains($haystack, 'how to solve') || str_contains($haystack, 'طريقة حل') || str_contains($haystack, 'google translate') || str_contains($haystack, 'ترجمة قوقل')) {
            return 'strategy';
        }

        if (str_contains($haystack, 'placement test') || str_contains($haystack, 'تحديد المستوى') || str_contains($haystack, 'midterm') || str_contains($haystack, 'final exam') || str_contains($haystack, 'الاختبار')) {
            return 'exam_reflection';
        }

        if (str_contains($haystack, 'letters') || str_contains($haystack, 'الحروف')) {
            return 'letters';
        }

        if (str_contains($haystack, 'syllable') || str_contains($haystack, 'المقاطع')) {
            return 'syllables';
        }

        if (str_contains($haystack, 'verb forms') || str_contains($haystack, 'v1') || str_contains($haystack, 'v2') || str_contains($haystack, 'v3') || str_contains($haystack, 'تصريف')) {
            return 'verb_forms';
        }

        if (str_contains($haystack, 'practice questions') || str_contains($haystack, 'أسئلة نموذجية')) {
            return 'practice';
        }

        if (
            str_contains($haystack, 'noun') ||
            str_contains($haystack, 'pronoun') ||
            str_contains($haystack, 'verb') ||
            str_contains($haystack, 'adjective') ||
            str_contains($haystack, 'adverb') ||
            str_contains($haystack, 'الاسم') ||
            str_contains($haystack, 'الضمير') ||
            str_contains($haystack, 'الفعل') ||
            str_contains($haystack, 'الصفة') ||
            str_contains($haystack, 'الظرف')
        ) {
            return 'grammar';
        }

        if (str_contains($haystack, 'preposition') || str_contains($haystack, 'حرف الجر') || str_contains($haystack, 'حروف جر')) {
            return 'prepositions';
        }

        if (
            str_contains($haystack, 'comma') ||
            str_contains($haystack, 'full stop') ||
            str_contains($haystack, 'apostrophe') ||
            str_contains($haystack, 'question mark') ||
            str_contains($haystack, 'exclamation') ||
            str_contains($haystack, 'colon') ||
            str_contains($haystack, 'الفاصلة') ||
            str_contains($haystack, 'النقطة') ||
            str_contains($haystack, 'الاستفهام') ||
            str_contains($haystack, 'التعجب') ||
            str_contains($haystack, 'النقطتان')
        ) {
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

    private function promptForTopic(string $topic, string $lessonTopic, int $minWords, int $maxWords): string
    {
        $arPrompt = match ($topic) {
            'orientation' => "اكتب من {$minWords} إلى {$maxWords} كلمة عن أهدافك في هذا الكورس وكيف ستذاكر كل أسبوع.",
            'contact' => "اكتب من {$minWords} إلى {$maxWords} كلمة: رسالة قصيرة لمعلمك تعرّف فيها بنفسك وتسأل سؤالًا مفيدًا عن الكورس.",
            'strategy' => "اكتب من {$minWords} إلى {$maxWords} كلمة تشرح فيها استراتيجيتك لتعلم الإنجليزية وحل الأسئلة بفعالية، مع أمثلة.",
            'letters' => "اكتب من {$minWords} إلى {$maxWords} كلمة باستخدام مفردات بسيطة. اذكر 6 كلمات على الأقل مرتبطة بحروف الدرس، ثم استخدم 3 كلمات منها في جمل كاملة.",
            'syllables' => "اكتب من {$minWords} إلى {$maxWords} كلمة. اذكر 6 كلمات وبيّن المقاطع الصوتية باستخدام الشرطة (-)، ثم اكتب 3 جمل باستخدام 3 كلمات منها.",
            'verb_forms' => "اكتب من {$minWords} إلى {$maxWords} كلمة. اختر 4 أفعال شائعة واكتب جملًا باستخدام V1 وV2 وV3 (8 جمل على الأقل).",
            'practice' => "اكتب من {$minWords} إلى {$maxWords} كلمة تلخص فيها ما تدربت عليه، ثم اكتب 5 جمل قصيرة تطبق قواعد الدرس.",
            'punctuation' => "اكتب من {$minWords} إلى {$maxWords} كلمة. اكتب فقرة قصيرة واستخدم علامة الترقيم الخاصة بالدرس بشكل صحيح 5 مرات على الأقل.",
            'prepositions' => "اكتب من {$minWords} إلى {$maxWords} كلمة. اكتب 10 جمل باستخدام حروف جر مختلفة (in, on, at, to, by...).",
            'exam_reflection' => "اكتب من {$minWords} إلى {$maxWords} كلمة عن ما تعلمته وما يزال صعبًا عليك وما الذي ستتدرب عليه لاحقًا، مع مثالين.",
            'grammar' => "اكتب من {$minWords} إلى {$maxWords} كلمة في فقرة واضحة تستخدم نقطة القواعد الخاصة بالدرس مع أمثلة.",
            default => "اكتب من {$minWords} إلى {$maxWords} كلمة تشرح فيها الفكرة الأساسية للدرس بأسلوبك، مع 3 أمثلة.",
        };

        $enPrompt = match ($topic) {
            'orientation' => "Write {$minWords}-{$maxWords} words about your goals for this course and how you will study each week.",
            'contact' => "Write {$minWords}-{$maxWords} words: a short message to your teacher introducing yourself and asking one useful question about the course.",
            'strategy' => "Write {$minWords}-{$maxWords} words explaining your strategy for learning English and solving questions effectively. Include examples.",
            'letters' => "Write {$minWords}-{$maxWords} words with simple vocabulary. Include at least 6 words related to this lesson's letters, then use 3 of them in full sentences.",
            'syllables' => "Write {$minWords}-{$maxWords} words. Include 6 words and show syllable breaks using hyphens (-), then write 3 sentences using any 3 words.",
            'verb_forms' => "Write {$minWords}-{$maxWords} words. Choose 4 common verbs and write sentences using V1, V2, and V3 (at least 8 sentences).",
            'practice' => "Write {$minWords}-{$maxWords} words summarizing what you practiced, then write 5 short sentences applying the lesson rules.",
            'punctuation' => "Write {$minWords}-{$maxWords} words. Write a short paragraph and use the lesson punctuation mark correctly at least 5 times.",
            'prepositions' => "Write {$minWords}-{$maxWords} words. Write 10 sentences using different prepositions (in, on, at, to, by, etc.).",
            'exam_reflection' => "Write {$minWords}-{$maxWords} words reflecting on what you learned, what is still difficult, and what you will practice next. Add 2 examples.",
            'grammar' => "Write {$minWords}-{$maxWords} words in a clear paragraph using the grammar point from this lesson with examples.",
            default => "Write {$minWords}-{$maxWords} words explaining the main lesson idea in your own words and add 3 examples.",
        };

        return "AR: {$arPrompt} (موضوع الدرس: {$lessonTopic})\nEN: {$enPrompt} (Lesson: {$lessonTopic})";
    }

    private function instructionsForTopic(string $topic, string $lessonTopic, int $minWords, int $maxWords): string
    {
        $ar = [
            "موضوع الدرس: {$lessonTopic}",
            "اكتب بالإنجليزية من {$minWords} إلى {$maxWords} كلمة.",
            'لا تكتب العربية داخل إجابتك.',
            'استخدم جملًا كاملة وعلامات ترقيم صحيحة.',
        ];

        $en = [
            "Lesson topic: {$lessonTopic}",
            "Write in English ({$minWords}-{$maxWords} words).",
            'Do not use Arabic in your answer.',
            'Use complete sentences and correct punctuation.',
        ];

        $extra = match ($topic) {
            'verb_forms' => [
                'ar' => ['استخدم أمثلة واضحة للفعل في V1 وV2 وV3.'],
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
                'ar' => ['استخدم حروف جر مختلفة وتجنب تكرار نفس نمط الجملة.'],
                'en' => ['Use different prepositions and avoid repeating the same sentence pattern.'],
            ],
            default => ['ar' => [], 'en' => []],
        };

        $ar = array_merge($ar, $extra['ar']);
        $en = array_merge($en, $extra['en']);

        return "AR:\n- " . implode("\n- ", $ar) . "\n\nEN:\n- " . implode("\n- ", $en);
    }

    private function modelAnswerForTopic(string $topic, string $lessonTopic, int $minWords, int $maxWords): ?string
    {
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
            'grammar' => "In {$lessonTopic}, I learned a grammar point and practiced it with examples. I try to write clear sentences and choose the correct structure. For example, I use the rule in a simple sentence first, then I write a longer sentence. This helps me understand the rule and use it in real writing.",
            default => "In {$lessonTopic}, I learned the main idea and practiced it with examples. First, I understand the rule, then I write sentences. For example, I write one simple sentence and one longer sentence. Finally, I review my mistakes and rewrite the sentence in a better way.",
        };

        $wordCount = count(preg_split('/\s+/', trim($answer)) ?: []);

        if ($wordCount < max(20, (int) floor($minWords * 0.6))) {
            return null;
        }

        if ($wordCount > $maxWords + 40) {
            return null;
        }

        return $answer;
    }

    private function containsArabic(string $value): bool
    {
        return preg_match('/\p{Arabic}/u', $value) === 1;
    }

    private function containsLatin(string $value): bool
    {
        return preg_match('/[A-Za-z]/', $value) === 1;
    }
}
