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
        $requiredVocabularyUsage = $this->requiredVocabularyUsage($minWords);

        $displayTopic = $englishTitle !== '' ? $englishTitle : ($arabicTitle !== '' ? $arabicTitle : 'this lesson');
        $lessonVocabulary = $this->buildLessonVocabulary($topic, $displayTopic);

        $title = $englishTitle !== ''
            ? "الكتابة - Writing: {$englishTitle}"
            : 'تدريب الكتابة - Writing Practice';

        $prompt = $this->promptForTopic($topic, $displayTopic, $minWords, $maxWords, $requiredVocabularyUsage);
        $instructions = $this->instructionsForTopic($topic, $displayTopic, $minWords, $maxWords, $requiredVocabularyUsage);
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
                'required_vocabulary_usage' => $requiredVocabularyUsage,
                'lesson_vocabulary' => $lessonVocabulary,
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

    private function promptForTopic(string $topic, string $lessonTopic, int $minWords, int $maxWords, int $requiredVocabularyUsage): string
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
        $arPrompt .= " استخدم على الأقل {$requiredVocabularyUsage} كلمات من قائمة مفردات الدرس.";

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
        $enPrompt .= " Use at least {$requiredVocabularyUsage} words from the lesson vocabulary list.";

        return "AR: {$arPrompt} (موضوع الدرس: {$lessonTopic})\nEN: {$enPrompt} (Lesson: {$lessonTopic})";
    }

    private function instructionsForTopic(string $topic, string $lessonTopic, int $minWords, int $maxWords, int $requiredVocabularyUsage): string
    {
        $ar = [
            "موضوع الدرس: {$lessonTopic}",
            "اكتب بالإنجليزية من {$minWords} إلى {$maxWords} كلمة.",
            "استخدم على الأقل {$requiredVocabularyUsage} كلمات من قائمة مفردات الدرس أدناه.",
            'لا تكتب العربية داخل إجابتك.',
            'استخدم جملًا كاملة وعلامات ترقيم صحيحة.',
        ];

        $en = [
            "Lesson topic: {$lessonTopic}",
            "Write in English ({$minWords}-{$maxWords} words).",
            "Use at least {$requiredVocabularyUsage} words from the lesson vocabulary list below.",
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

    private function requiredVocabularyUsage(int $minWords): int
    {
        if ($minWords <= 50) {
            return 4;
        }

        if ($minWords <= 85) {
            return 5;
        }

        if ($minWords <= 110) {
            return 6;
        }

        return 7;
    }

    private function buildLessonVocabulary(string $topic, string $lessonTopic, int $count = 15): array
    {
        $items = [];
        $combined = array_merge($this->vocabularyBankForTopic($topic), $this->vocabularySupportBank());

        foreach ($combined as $item) {
            $word = strtolower(trim((string) ($item['word'] ?? '')));
            if ($word === '' || isset($items[$word])) {
                continue;
            }

            $meaningAr = trim((string) ($item['meaning_ar'] ?? ''));
            if ($meaningAr === '') {
                continue;
            }

            $items[$word] = [
                'word' => $word,
                'meaning_ar' => $meaningAr,
                'explanation_en' => trim((string) ($item['explanation_en'] ?? '')) !== ''
                    ? trim((string) $item['explanation_en'])
                    : "Useful word when writing about {$lessonTopic}.",
                'explanation_ar' => trim((string) ($item['explanation_ar'] ?? '')) !== ''
                    ? trim((string) $item['explanation_ar'])
                    : "كلمة مفيدة عند الكتابة عن {$lessonTopic}.",
                'example' => trim((string) ($item['example'] ?? '')) !== ''
                    ? trim((string) $item['example'])
                    : "Try to use '{$word}' in a sentence about {$lessonTopic}.",
            ];
        }

        return array_slice(array_values($items), 0, $count);
    }

    private function vocabularySupportBank(): array
    {
        return [
            ['word' => 'goal', 'meaning_ar' => 'هدف', 'example' => 'My goal is to write clearly every day.'],
            ['word' => 'plan', 'meaning_ar' => 'خطة', 'example' => 'I follow a simple plan when I write.'],
            ['word' => 'example', 'meaning_ar' => 'مثال', 'example' => 'I add one example in each paragraph.'],
            ['word' => 'detail', 'meaning_ar' => 'تفصيل', 'example' => 'This detail makes my answer stronger.'],
            ['word' => 'reason', 'meaning_ar' => 'سبب', 'example' => 'I gave a clear reason for my opinion.'],
            ['word' => 'result', 'meaning_ar' => 'نتيجة', 'example' => 'The result was better after practice.'],
            ['word' => 'improve', 'meaning_ar' => 'يحسّن', 'example' => 'I want to improve my writing this month.'],
            ['word' => 'practice', 'meaning_ar' => 'تدريب', 'example' => 'Daily practice helps me learn faster.'],
            ['word' => 'clear', 'meaning_ar' => 'واضح', 'example' => 'I try to keep my ideas clear.'],
            ['word' => 'connect', 'meaning_ar' => 'يربط', 'example' => 'Linking words connect my sentences well.'],
            ['word' => 'explain', 'meaning_ar' => 'يشرح', 'example' => 'I explain my idea with simple words.'],
            ['word' => 'include', 'meaning_ar' => 'يتضمن', 'example' => 'I include useful words from the lesson.'],
            ['word' => 'organize', 'meaning_ar' => 'ينظم', 'example' => 'I organize my answer before writing.'],
            ['word' => 'sentence', 'meaning_ar' => 'جملة', 'example' => 'Each sentence should have a clear meaning.'],
            ['word' => 'paragraph', 'meaning_ar' => 'فقرة', 'example' => 'My paragraph has a main idea and details.'],
        ];
    }

    private function vocabularyBankForTopic(string $topic): array
    {
        return match ($topic) {
            'orientation' => [
                ['word' => 'course', 'meaning_ar' => 'دورة دراسية', 'example' => 'This course helps me reach my target.'],
                ['word' => 'routine', 'meaning_ar' => 'روتين', 'example' => 'I have a routine for daily study.'],
                ['word' => 'weekly', 'meaning_ar' => 'أسبوعي', 'example' => 'I write a weekly study report.'],
                ['word' => 'progress', 'meaning_ar' => 'تقدم', 'example' => 'I can see my progress every week.'],
                ['word' => 'target', 'meaning_ar' => 'هدف محدد', 'example' => 'My target is to write 100 words.'],
                ['word' => 'commit', 'meaning_ar' => 'يلتزم', 'example' => 'I commit to practicing every day.'],
                ['word' => 'habit', 'meaning_ar' => 'عادة', 'example' => 'Reading is a useful study habit.'],
                ['word' => 'review', 'meaning_ar' => 'مراجعة', 'example' => 'I review new words before sleep.'],
                ['word' => 'schedule', 'meaning_ar' => 'جدول', 'example' => 'My schedule includes writing time.'],
                ['word' => 'focus', 'meaning_ar' => 'تركيز', 'example' => 'I focus on one skill each day.'],
            ],
            'contact' => [
                ['word' => 'introduce', 'meaning_ar' => 'يعرّف بنفسه', 'example' => 'I introduce myself to the teacher.'],
                ['word' => 'message', 'meaning_ar' => 'رسالة', 'example' => 'I sent a short message yesterday.'],
                ['word' => 'polite', 'meaning_ar' => 'مهذب', 'example' => 'I use polite language in emails.'],
                ['word' => 'request', 'meaning_ar' => 'طلب', 'example' => 'I made a request for extra practice.'],
                ['word' => 'reply', 'meaning_ar' => 'رد', 'example' => 'I got a quick reply from my teacher.'],
                ['word' => 'support', 'meaning_ar' => 'دعم', 'example' => 'The teacher gave me support.'],
                ['word' => 'question', 'meaning_ar' => 'سؤال', 'example' => 'I asked one clear question.'],
                ['word' => 'teacher', 'meaning_ar' => 'معلم', 'example' => 'My teacher corrected my writing.'],
                ['word' => 'advice', 'meaning_ar' => 'نصيحة', 'example' => 'Her advice was very useful.'],
                ['word' => 'communicate', 'meaning_ar' => 'يتواصل', 'example' => 'I communicate in simple English.'],
            ],
            'strategy' => [
                ['word' => 'strategy', 'meaning_ar' => 'استراتيجية', 'example' => 'My strategy starts with reading carefully.'],
                ['word' => 'keyword', 'meaning_ar' => 'كلمة مفتاحية', 'example' => 'I underline each keyword in the question.'],
                ['word' => 'eliminate', 'meaning_ar' => 'يستبعد', 'example' => 'I eliminate wrong options first.'],
                ['word' => 'compare', 'meaning_ar' => 'يقارن', 'example' => 'I compare two possible answers.'],
                ['word' => 'summarize', 'meaning_ar' => 'يلخّص', 'example' => 'I summarize the idea in one sentence.'],
                ['word' => 'analyze', 'meaning_ar' => 'يحلّل', 'example' => 'I analyze my mistakes after each task.'],
                ['word' => 'check', 'meaning_ar' => 'يفحص', 'example' => 'I check spelling before submission.'],
                ['word' => 'mistake', 'meaning_ar' => 'خطأ', 'example' => 'I learn from every mistake.'],
                ['word' => 'solution', 'meaning_ar' => 'حل', 'example' => 'I found a better solution this time.'],
                ['word' => 'method', 'meaning_ar' => 'طريقة', 'example' => 'This method saves time in exams.'],
            ],
            'exam_reflection' => [
                ['word' => 'evaluate', 'meaning_ar' => 'يقيّم', 'example' => 'I evaluate my answer after each test.'],
                ['word' => 'weakness', 'meaning_ar' => 'نقطة ضعف', 'example' => 'Grammar is my main weakness now.'],
                ['word' => 'strength', 'meaning_ar' => 'نقطة قوة', 'example' => 'Vocabulary is one of my strengths.'],
                ['word' => 'revise', 'meaning_ar' => 'يراجع', 'example' => 'I revise difficult rules every weekend.'],
                ['word' => 'outcome', 'meaning_ar' => 'نتيجة', 'example' => 'The outcome improved this month.'],
                ['word' => 'reflect', 'meaning_ar' => 'يتأمل', 'example' => 'I reflect on what I learned today.'],
                ['word' => 'prepare', 'meaning_ar' => 'يستعد', 'example' => 'I prepare for the next quiz early.'],
                ['word' => 'effort', 'meaning_ar' => 'جهد', 'example' => 'My effort gave me better results.'],
                ['word' => 'improvement', 'meaning_ar' => 'تحسن', 'example' => 'I noticed improvement in writing.'],
                ['word' => 'confidence', 'meaning_ar' => 'ثقة', 'example' => 'Practice increased my confidence.'],
            ],
            'letters' => [
                ['word' => 'alphabet', 'meaning_ar' => 'الأبجدية', 'example' => 'I reviewed the alphabet sounds today.'],
                ['word' => 'sound', 'meaning_ar' => 'صوت', 'example' => 'This letter has a soft sound.'],
                ['word' => 'spelling', 'meaning_ar' => 'تهجئة', 'example' => 'My spelling became more accurate.'],
                ['word' => 'letter', 'meaning_ar' => 'حرف', 'example' => 'I wrote each letter clearly.'],
                ['word' => 'match', 'meaning_ar' => 'يطابق', 'example' => 'I match the letter with its sound.'],
                ['word' => 'repeat', 'meaning_ar' => 'يكرر', 'example' => 'I repeat new words three times.'],
                ['word' => 'pronounce', 'meaning_ar' => 'ينطق', 'example' => 'I pronounce this word slowly.'],
                ['word' => 'trace', 'meaning_ar' => 'يتتبع', 'example' => 'I trace the letters to remember them.'],
                ['word' => 'pattern', 'meaning_ar' => 'نمط', 'example' => 'I noticed a spelling pattern.'],
                ['word' => 'recognize', 'meaning_ar' => 'يتعرف على', 'example' => 'Now I recognize these letters quickly.'],
            ],
            'syllables' => [
                ['word' => 'syllable', 'meaning_ar' => 'مقطع صوتي', 'example' => 'This word has two syllables.'],
                ['word' => 'stress', 'meaning_ar' => 'نبرة', 'example' => 'The stress is on the first syllable.'],
                ['word' => 'segment', 'meaning_ar' => 'يقسم', 'example' => 'I segment long words to read them.'],
                ['word' => 'divide', 'meaning_ar' => 'يقسم', 'example' => 'I divide the word into parts.'],
                ['word' => 'rhythm', 'meaning_ar' => 'إيقاع', 'example' => 'Rhythm helps natural pronunciation.'],
                ['word' => 'vowel', 'meaning_ar' => 'حرف علة', 'example' => 'Every syllable has a vowel sound.'],
                ['word' => 'consonant', 'meaning_ar' => 'حرف ساكن', 'example' => 'The consonant comes before the vowel.'],
                ['word' => 'blend', 'meaning_ar' => 'دمج', 'example' => 'I blend the sounds smoothly.'],
                ['word' => 'accent', 'meaning_ar' => 'لكنة', 'example' => 'My accent is getting clearer.'],
                ['word' => 'clap', 'meaning_ar' => 'يصفق', 'example' => 'I clap to count syllables.'],
            ],
            'verb_forms' => [
                ['word' => 'base', 'meaning_ar' => 'صيغة أساسية', 'example' => 'The base form is easy to remember.'],
                ['word' => 'past', 'meaning_ar' => 'ماضٍ', 'example' => 'I used the past form correctly.'],
                ['word' => 'participle', 'meaning_ar' => 'تصريف ثالث', 'example' => 'The participle form is irregular.'],
                ['word' => 'regular', 'meaning_ar' => 'منتظم', 'example' => 'Regular verbs often end with -ed.'],
                ['word' => 'irregular', 'meaning_ar' => 'غير منتظم', 'example' => 'Irregular verbs need extra practice.'],
                ['word' => 'tense', 'meaning_ar' => 'زمن', 'example' => 'I chose the correct tense in my sentence.'],
                ['word' => 'action', 'meaning_ar' => 'فعل / حدث', 'example' => 'The action happened yesterday.'],
                ['word' => 'auxiliary', 'meaning_ar' => 'فعل مساعد', 'example' => 'I used an auxiliary verb here.'],
                ['word' => 'form', 'meaning_ar' => 'صيغة', 'example' => 'This form is used in present perfect.'],
                ['word' => 'transform', 'meaning_ar' => 'يحوّل', 'example' => 'Transform V1 into V2 and V3.'],
            ],
            'practice' => [
                ['word' => 'exercise', 'meaning_ar' => 'تمرين', 'example' => 'I completed one exercise today.'],
                ['word' => 'drill', 'meaning_ar' => 'تدريب مكثف', 'example' => 'This drill improved my speed.'],
                ['word' => 'attempt', 'meaning_ar' => 'محاولة', 'example' => 'My second attempt was better.'],
                ['word' => 'correct', 'meaning_ar' => 'يصحح', 'example' => 'I correct errors after each task.'],
                ['word' => 'repeat', 'meaning_ar' => 'يكرر', 'example' => 'Repeat hard questions twice.'],
                ['word' => 'feedback', 'meaning_ar' => 'تغذية راجعة', 'example' => 'Feedback helped me improve quickly.'],
                ['word' => 'score', 'meaning_ar' => 'درجة', 'example' => 'My score increased this week.'],
                ['word' => 'challenge', 'meaning_ar' => 'تحدٍ', 'example' => 'This challenge tested my grammar.'],
                ['word' => 'confidence', 'meaning_ar' => 'ثقة', 'example' => 'Practice gave me more confidence.'],
                ['word' => 'accuracy', 'meaning_ar' => 'دقة', 'example' => 'I focus on accuracy first.'],
            ],
            'grammar' => [
                ['word' => 'subject', 'meaning_ar' => 'فاعل', 'example' => 'The subject comes before the verb.'],
                ['word' => 'predicate', 'meaning_ar' => 'خبر / مسند', 'example' => 'The predicate gives more information.'],
                ['word' => 'article', 'meaning_ar' => 'أداة تعريف', 'example' => 'I used the article correctly.'],
                ['word' => 'pronoun', 'meaning_ar' => 'ضمير', 'example' => 'A pronoun replaces a noun.'],
                ['word' => 'adjective', 'meaning_ar' => 'صفة', 'example' => 'The adjective describes the noun.'],
                ['word' => 'adverb', 'meaning_ar' => 'ظرف', 'example' => 'The adverb describes the action.'],
                ['word' => 'clause', 'meaning_ar' => 'جملة فرعية', 'example' => 'This clause adds extra detail.'],
                ['word' => 'agreement', 'meaning_ar' => 'مطابقة', 'example' => 'Subject-verb agreement is important.'],
                ['word' => 'structure', 'meaning_ar' => 'تركيب', 'example' => 'This sentence structure is correct.'],
                ['word' => 'modifier', 'meaning_ar' => 'مُعدِّل', 'example' => 'The modifier gives clearer meaning.'],
            ],
            'prepositions' => [
                ['word' => 'position', 'meaning_ar' => 'موضع', 'example' => 'The preposition shows position.'],
                ['word' => 'direction', 'meaning_ar' => 'اتجاه', 'example' => 'The arrow points in one direction.'],
                ['word' => 'location', 'meaning_ar' => 'موقع', 'example' => 'I described the location clearly.'],
                ['word' => 'movement', 'meaning_ar' => 'حركة', 'example' => 'Movement words need the right preposition.'],
                ['word' => 'time', 'meaning_ar' => 'وقت', 'example' => 'Use at for a specific time.'],
                ['word' => 'place', 'meaning_ar' => 'مكان', 'example' => 'Use in for a large place.'],
                ['word' => 'before', 'meaning_ar' => 'قبل', 'example' => 'I study before dinner.'],
                ['word' => 'after', 'meaning_ar' => 'بعد', 'example' => 'I write after class.'],
                ['word' => 'between', 'meaning_ar' => 'بين', 'example' => 'The school is between two buildings.'],
                ['word' => 'through', 'meaning_ar' => 'من خلال', 'example' => 'We walked through the park.'],
            ],
            'punctuation' => [
                ['word' => 'comma', 'meaning_ar' => 'فاصلة', 'example' => 'Use a comma after an opening phrase.'],
                ['word' => 'period', 'meaning_ar' => 'نقطة', 'example' => 'End each statement with a period.'],
                ['word' => 'apostrophe', 'meaning_ar' => 'فاصلة علوية', 'example' => 'Use an apostrophe in contractions.'],
                ['word' => 'quotation', 'meaning_ar' => 'علامة اقتباس', 'example' => 'Put speech inside quotation marks.'],
                ['word' => 'colon', 'meaning_ar' => 'نقطتان رأسيتان', 'example' => 'Use a colon before a list.'],
                ['word' => 'semicolon', 'meaning_ar' => 'فاصلة منقوطة', 'example' => 'A semicolon links two related ideas.'],
                ['word' => 'exclamation', 'meaning_ar' => 'تعجب', 'example' => 'Use an exclamation mark for strong emotion.'],
                ['word' => 'question', 'meaning_ar' => 'استفهام', 'example' => 'A question needs a question mark.'],
                ['word' => 'pause', 'meaning_ar' => 'وقفة', 'example' => 'A comma creates a short pause.'],
                ['word' => 'mark', 'meaning_ar' => 'علامة', 'example' => 'Choose the correct punctuation mark.'],
            ],
            default => [
                ['word' => 'topic', 'meaning_ar' => 'موضوع', 'example' => 'I stayed focused on the topic.'],
                ['word' => 'idea', 'meaning_ar' => 'فكرة', 'example' => 'Each paragraph has one main idea.'],
                ['word' => 'support', 'meaning_ar' => 'يدعم', 'example' => 'Examples support my opinion.'],
                ['word' => 'opinion', 'meaning_ar' => 'رأي', 'example' => 'I shared my opinion clearly.'],
                ['word' => 'compare', 'meaning_ar' => 'يقارن', 'example' => 'I compare two different views.'],
                ['word' => 'describe', 'meaning_ar' => 'يصف', 'example' => 'I describe the lesson in detail.'],
                ['word' => 'choose', 'meaning_ar' => 'يختار', 'example' => 'I choose simple and clear words.'],
                ['word' => 'build', 'meaning_ar' => 'يبني', 'example' => 'I build each paragraph step by step.'],
                ['word' => 'develop', 'meaning_ar' => 'يطور', 'example' => 'I develop my point with examples.'],
                ['word' => 'revise', 'meaning_ar' => 'يراجع', 'example' => 'I revise my answer before submitting.'],
            ],
        };
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
