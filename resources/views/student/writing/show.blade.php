@extends('layouts.app')

@section('title', ($writingExercise->title ?: (app()->getLocale() === 'ar' ? 'تدريب الكتابة' : 'Writing Practice')) . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        @php
            $isArabic = app()->getLocale() === 'ar';
            $rubric = is_array($writingExercise->rubric_json) ? $writingExercise->rubric_json : [];

            $promptText = (string) $writingExercise->prompt;
            $instructionsText = (string) ($writingExercise->instructions ?? '');

            $localizedPrompt = $promptText;
            if (preg_match('/AR:\s*(.*?)\s*EN:\s*(.*)$/su', $promptText, $matches) === 1) {
                $localizedPrompt = $isArabic ? trim($matches[1]) : trim($matches[2]);
            }

            $localizedInstructions = $instructionsText;
            if (preg_match('/AR:\s*(.*?)\s*EN:\s*(.*)$/su', $instructionsText, $matches) === 1) {
                $localizedInstructions = $isArabic ? trim($matches[1]) : trim($matches[2]);
            }

            $lessonVocabulary = collect($rubric['lesson_vocabulary'] ?? [])
                ->map(function ($item) {
                    if (!is_array($item)) {
                        return null;
                    }

                    $word = strtolower(trim((string) ($item['word'] ?? '')));
                    if ($word === '') {
                        return null;
                    }

                    return [
                        'word' => $word,
                        'meaning_ar' => trim((string) ($item['meaning_ar'] ?? '')),
                        'explanation_en' => trim((string) ($item['explanation_en'] ?? '')),
                        'explanation_ar' => trim((string) ($item['explanation_ar'] ?? '')),
                        'example' => trim((string) ($item['example'] ?? '')),
                    ];
                })
                ->filter()
                ->take(15)
                ->values();

            $requiredVocabularyUsage = max(0, (int) ($rubric['required_vocabulary_usage'] ?? 0));
        @endphp

        @php
            $context = $writingExercise->lesson ?? $writingExercise->courseLevel;
            $contextCourse = $context?->course;
        @endphp
        <x-student.page-header
            title="{{ $writingExercise->title ?: ($isArabic ? 'تدريب الكتابة' : 'Writing Practice') }}"
            subtitle="{{ $context?->title ?? '' }}"
            badge="{{ $isArabic ? 'كتابة' : 'Writing' }}"
            badgeColor="primary"
            badgeIcon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>'
        >
            <x-slot name="actions">
                @if($writingExercise->lesson && $contextCourse)
                <a href="{{ route('student.lessons.show', [$contextCourse, $writingExercise->lesson]) }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $isArabic ? 'العودة للدرس' : 'Back to lesson' }}
                </a>
                @elseif($contextCourse)
                <a href="{{ route('student.courses.learn', $contextCourse) }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $isArabic ? 'العودة للكورس' : 'Back to course' }}
                </a>
                @endif
            </x-slot>
        </x-student.page-header>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8" x-data="writingPractice(@js([
            'submitUrl' => route('student.writing.submit', $writingExercise),
            'csrf' => csrf_token(),
            'minWords' => $writingExercise->min_words,
            'maxWords' => $writingExercise->max_words,
            'initialText' => old('answer_text', ''),
            'lessonVocabularyWords' => $lessonVocabulary->pluck('word')->values()->all(),
            'requiredVocabularyUsage' => $requiredVocabularyUsage,
            'evaluationType' => $writingExercise->evaluation_type ?? 'ai',
            'exactMatchQuestions' => is_string($writingExercise->questions_json) ? json_decode($writingExercise->questions_json, true) : ($writingExercise->questions_json ?? []),
        ]))">
            <div class="xl:col-span-2 space-y-6">
                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20">
                        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'المطلوب' : 'Task Prompt' }}</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <p class="text-base leading-8 text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $localizedPrompt }}</p>
                        @if($writingExercise->instructions)
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-200/70 dark:border-white/10 p-4">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">{{ $isArabic ? 'التعليمات' : 'Instructions' }}</div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $localizedInstructions }}</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ $isArabic ? 'الحد الأدنى' : 'Minimum' }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->min_words }}</div>
                                <div class="text-xs text-slate-500">{{ $isArabic ? 'كلمة' : 'words' }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ $isArabic ? 'الحد الأقصى' : 'Maximum' }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->max_words }}</div>
                                <div class="text-xs text-slate-500">{{ $isArabic ? 'كلمة' : 'words' }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ $isArabic ? 'درجة النجاح' : 'Passing Score' }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->passing_score }}%</div>
                                <div class="text-xs text-slate-500">{{ $isArabic ? 'المطلوب' : 'target' }}</div>
                            </div>
                        </div>
                    </div>
                </x-student.card>

                @if($lessonVocabulary->isNotEmpty())
                    <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                        <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'مفردات الدرس المطلوبة' : 'Required Lesson Vocabulary' }}</h2>
                            @if($requiredVocabularyUsage > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                    {{ $isArabic ? "استخدم {$requiredVocabularyUsage} كلمات على الأقل" : "Use at least {$requiredVocabularyUsage} words" }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($lessonVocabulary as $wordItem)
                                    @php
                                        $localizedExplanation = $isArabic
                                            ? ($wordItem['explanation_ar'] !== '' ? $wordItem['explanation_ar'] : 'كلمة مفيدة للكتابة في هذا الدرس.')
                                            : ($wordItem['explanation_en'] !== '' ? $wordItem['explanation_en'] : 'Useful vocabulary for this lesson writing task.');
                                        $exampleText = $wordItem['example'] !== '' ? $wordItem['example'] : ($isArabic ? 'استخدم الكلمة داخل جملة صحيحة.' : 'Use the word in one correct sentence.');
                                    @endphp
                                    <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02] space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="text-base font-black text-slate-900 dark:text-white">{{ $wordItem['word'] }}</div>
                                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $wordItem['meaning_ar'] }}</span>
                                        </div>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-6">{{ $localizedExplanation }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-5">
                                            <span class="font-bold">{{ $isArabic ? 'مثال:' : 'Example:' }}</span>
                                            {{ $exampleText }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-student.card>
                @endif

                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between gap-4">
                        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'كتابتك' : 'Your Writing' }}</h2>
                        <div class="flex items-center gap-2">
                            <div class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold"
                                :class="wordCount < minWords ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : wordCount > maxWords ? 'bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'">
                                <span x-text="wordCount"></span>
                                <span>{{ $isArabic ? 'كلمة' : 'words' }}</span>
                            </div>
                            <div x-show="requiredVocabularyUsage > 0" x-cloak
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold"
                                :class="vocabularyTargetMet ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-primary-500/10 text-primary-600 dark:text-primary-400'">
                                <span x-text="usedVocabularyCount"></span>
                                <span>/</span>
                                <span x-text="requiredVocabularyUsage"></span>
                                <span>{{ $isArabic ? 'مفردات' : 'vocab' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        @if(($writingExercise->evaluation_type ?? 'ai') === 'exact_match')
                            <div class="space-y-6" x-show="!result">
                                <template x-for="(q, index) in exactMatchQuestions" :key="index">
                                    <div x-show="currentQuestion === index" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-6 rounded-2xl border border-slate-200 dark:border-white/10 bg-white/50 dark:bg-slate-900/40">
                                        <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-3">{{ $isArabic ? 'السؤال' : 'Question' }} <span x-text="index + 1"></span> / <span x-text="exactMatchQuestions.length"></span></div>
                                        <div class="text-2xl font-black text-slate-900 dark:text-white mb-6" x-text="q.question"></div>
                                        <input type="text" x-model="answers[index]" @keydown.enter.prevent="nextQuestion()" class="input-glass w-full text-lg py-4 px-5 font-bold" placeholder="{{ $isArabic ? 'اكتب إجابتك هنا...' : 'Type your answer here...' }}" :id="'answer-input-' + index">
                                        <div class="mt-6 flex justify-end">
                                            <button type="button" @click="nextQuestion()" class="btn-primary" x-show="index < exactMatchQuestions.length - 1">{{ $isArabic ? 'التالي' : 'Next' }}</button>
                                            <button type="button" @click="submitExactMatch()" class="btn-primary" x-show="index === exactMatchQuestions.length - 1">
                                                <span x-show="!submitting">{{ $isArabic ? 'إنهاء وإرسال' : 'Finish & Submit' }}</span>
                                                <span x-show="submitting">{{ $isArabic ? 'جاري...' : 'Submitting...' }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @else
                            <textarea
                                x-model="answerText"
                                rows="14"
                                class="input-glass min-h-[320px]"
                                placeholder="{{ $isArabic ? 'اكتب إجابتك هنا...' : 'Write your answer here...' }}"
                            ></textarea>
                            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between mt-4">
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    @if($isArabic)
                                        استهدف {{ $writingExercise->min_words }} إلى {{ $writingExercise->max_words }} كلمة، وخلي إجابتك مرتبطة بالمطلوب.
                                        @if($requiredVocabularyUsage > 0 && $lessonVocabulary->isNotEmpty())
                                            استخدم على الأقل {{ $requiredVocabularyUsage }} كلمات من قائمة مفردات الدرس.
                                        @endif
                                    @else
                                        Aim for {{ $writingExercise->min_words }}-{{ $writingExercise->max_words }} words and keep your answer focused on the prompt.
                                        @if($requiredVocabularyUsage > 0 && $lessonVocabulary->isNotEmpty())
                                            Use at least {{ $requiredVocabularyUsage }} words from the lesson vocabulary list.
                                        @endif
                                    @endif
                                </p>
                                <div class="flex gap-3">
                                    <button type="button" class="btn-ghost" @click="resetDraft()" x-bind:disabled="submitting">{{ $isArabic ? 'مسح المسودة' : 'Clear Draft' }}</button>
                                    <button type="button" class="btn-primary ripple-btn min-w-[170px]" @click="submit()" x-bind:disabled="submitting">
                                        <span x-show="!submitting">{{ $isArabic ? 'إرسال الكتابة' : 'Submit Writing' }}</span>
                                        <span x-show="submitting" x-cloak>{{ $isArabic ? 'جارٍ تحليل الكتابة...' : 'Analyzing your writing...' }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <p x-show="errorMessage" x-text="errorMessage" x-cloak class="text-sm font-semibold text-red-500 mt-2"></p>
                    </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                @if($isArabic)
                                    استهدف {{ $writingExercise->min_words }} إلى {{ $writingExercise->max_words }} كلمة، وخلي إجابتك مرتبطة بالمطلوب.
                                    @if($requiredVocabularyUsage > 0 && $lessonVocabulary->isNotEmpty())
                                        استخدم على الأقل {{ $requiredVocabularyUsage }} كلمات من قائمة مفردات الدرس.
                                    @endif
                                @else
                                    Aim for {{ $writingExercise->min_words }}-{{ $writingExercise->max_words }} words and keep your answer focused on the prompt.
                                    @if($requiredVocabularyUsage > 0 && $lessonVocabulary->isNotEmpty())
                                        Use at least {{ $requiredVocabularyUsage }} words from the lesson vocabulary list.
                                    @endif
                                @endif
                            </p>
                            <div class="flex gap-3">
                                <button type="button" class="btn-ghost" @click="resetDraft()" x-bind:disabled="submitting">{{ $isArabic ? 'مسح المسودة' : 'Clear Draft' }}</button>
                                <button type="button" class="btn-primary ripple-btn min-w-[170px]" @click="submit()" x-bind:disabled="submitting">
                                    <span x-show="!submitting">{{ $isArabic ? 'إرسال الكتابة' : 'Submit Writing' }}</span>
                                    <span x-show="submitting" x-cloak>{{ $isArabic ? 'جارٍ تحليل الكتابة...' : 'Analyzing your writing...' }}</span>
                                </button>
                            </div>
                        </div>
                        <p x-show="errorMessage" x-text="errorMessage" x-cloak class="text-sm font-semibold text-red-500"></p>
                    </div>
                </x-student.card>

                <x-student.card x-show="result" x-cloak padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'ملاحظات الذكاء الاصطناعي على الكتابة' : 'AI Writing Feedback' }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400" x-text="result ? result.summary : ''"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl px-4 py-3 bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                <div class="text-xs uppercase tracking-wider font-bold">{{ $isArabic ? 'النتيجة العامة' : 'Overall' }}</div>
                                <div class="text-2xl font-black" x-text="result ? result.overall_score + '%' : ''"></div>
                            </div>
                            <div class="rounded-2xl px-4 py-3"
                                 :class="result && result.passed ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400'">
                                <div class="text-xs uppercase tracking-wider font-bold">{{ $isArabic ? 'الحالة' : 'Status' }}</div>
                                <div class="text-sm font-black" x-text="result && result.passed ? '{{ $isArabic ? 'ناجح' : 'Passed' }}' : '{{ $isArabic ? 'تحتاج تحسين' : 'Needs more work' }}'"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <template x-for="metric in metrics" :key="metric.key">
                                <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/80 dark:bg-white/[0.02]">
                                    <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-2" x-text="metric.label"></div>
                                    <div class="text-2xl font-black text-slate-900 dark:text-white" x-text="result ? result[metric.key] + '%' : ''"></div>
                                </div>
                            </template>
                        </div>

                        <div x-show="result && result.required_vocabulary_usage > 0" x-cloak class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02] space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'استخدام مفردات الدرس' : 'Lesson Vocabulary Usage' }}</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                      :class="result && result.vocabulary_target_met ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400'"
                                      x-text="result && result.vocabulary_target_met ? '{{ $isArabic ? 'محقق' : 'Met' }}' : '{{ $isArabic ? 'غير مكتمل' : 'Not met' }}'"></span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                <span x-text="result ? result.used_vocabulary_count : 0"></span>
                                <span>{{ $isArabic ? 'من' : 'of' }}</span>
                                <span x-text="result ? result.required_vocabulary_usage : 0"></span>
                                <span>{{ $isArabic ? 'كلمات مطلوبة من قائمة الدرس' : 'required words from the lesson list' }}</span>
                            </p>
                            <div x-show="result && result.missing_vocabulary_words && result.missing_vocabulary_words.length" class="flex flex-wrap gap-2">
                                <template x-for="missingWord in (result ? result.missing_vocabulary_words : [])" :key="missingWord">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-500/10 text-primary-600 dark:text-primary-400" x-text="missingWord"></span>
                                </template>
                            </div>
                        </div>

                        <div x-show="result && result.exact_match_results" x-cloak class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                            <h3 class="font-extrabold text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'تفاصيل الإجابات' : 'Answer Details' }}</h3>
                            <div class="space-y-3">
                                <template x-for="(res, idx) in (result ? result.exact_match_results : [])" :key="idx">
                                    <div class="flex items-start gap-4 p-4 rounded-xl" :class="res.is_correct ? 'bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30' : 'bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800/30'">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" :class="res.is_correct ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'">
                                            <svg x-show="res.is_correct" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            <svg x-show="!res.is_correct" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-slate-900 dark:text-white mb-2" x-text="res.question"></div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-0.5">{{ $isArabic ? 'إجابتك' : 'Your Answer' }}</div>
                                                    <div class="text-sm font-semibold" :class="res.is_correct ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400'" x-text="res.user_answer || '-'"></div>
                                                </div>
                                                <div x-show="!res.is_correct">
                                                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-0.5">{{ $isArabic ? 'الإجابة الصحيحة' : 'Correct Answer' }}</div>
                                                    <div class="text-sm font-semibold text-emerald-700 dark:text-emerald-400" x-text="res.expected"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="result && result.strengths && !result.exact_match_results">
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                                <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ $isArabic ? 'نقاط القوة' : 'Strengths' }}</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <template x-for="item in (result ? result.strengths : [])" :key="item">
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1 w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                                <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ $isArabic ? 'ما الذي يحتاج تحسين' : 'What To Improve' }}</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <template x-for="item in (result ? result.improvements : [])" :key="item">
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1 w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div x-show="result && result.grammar_issues && result.grammar_issues.length" x-cloak class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                            <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ $isArabic ? 'تنبيهات القواعد' : 'Grammar Alerts' }}</h3>
                            <div class="space-y-3">
                                <template x-for="issue in (result ? result.grammar_issues : [])" :key="issue.offset + '-' + issue.message">
                                    <div class="rounded-xl border border-slate-200/70 dark:border-white/10 p-4 bg-white dark:bg-slate-900/40">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white" x-text="issue.message"></div>
                                        <div class="text-xs text-slate-500 mt-1" x-text="issue.context"></div>
                                        <div class="flex flex-wrap gap-2 mt-3" x-show="issue.replacements && issue.replacements.length">
                                            <template x-for="replacement in issue.replacements" :key="replacement">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-500/10 text-primary-600 dark:text-primary-400" x-text="replacement"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="result && result.rewrite_suggestion" x-cloak class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                            <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ $isArabic ? 'صياغة مقترحة' : 'Suggested Rewrite' }}</h3>
                            <p class="text-sm leading-7 text-slate-700 dark:text-slate-300 whitespace-pre-line" x-text="result ? result.rewrite_suggestion : ''"></p>
                        </div>
                    </div>
                </x-student.card>
            </div>

            <div class="space-y-6">
                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20">
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $isArabic ? 'آخر المحاولات' : 'Recent Attempts' }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($attempts as $attempt)
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $attempt->submitted_at?->diffForHumans() }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $attempt->overall_score >= $writingExercise->passing_score ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                                        {{ $attempt->overall_score }}%
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-4">{{ $attempt->answer_text }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $isArabic ? 'لا توجد محاولات كتابة حتى الآن. أرسل أول إجابة لترى التقييم هنا.' : 'No writing attempts yet. Submit your first answer to see feedback here.' }}</p>
                        @endforelse
                    </div>
                </x-student.card>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function writingPractice(config) {
    return {
        answerText: config.initialText || '',
        submitting: false,
        errorMessage: '',
        result: null,
        minWords: config.minWords,
        maxWords: config.maxWords,
        evaluationType: config.evaluationType,
        exactMatchQuestions: config.exactMatchQuestions,
        currentQuestion: 0,
        answers: [],
        lessonVocabularyWords: (config.lessonVocabularyWords || [])
            .map((word) => String(word || '').trim().toLowerCase())
            .filter(Boolean),
        requiredVocabularyUsage: Number(config.requiredVocabularyUsage || 0),
        metrics: config.evaluationType === 'exact_match' ? [] : [
            { key: 'grammar_score', label: @js($isArabic ? 'القواعد' : 'Grammar') },
            { key: 'vocabulary_score', label: @js($isArabic ? 'المفردات' : 'Vocabulary') },
            { key: 'coherence_score', label: @js($isArabic ? 'الترابط' : 'Coherence') },
            { key: 'task_score', label: @js($isArabic ? 'تحقيق المطلوب' : 'Task') },
        ],
        init() {
            if (this.evaluationType === 'exact_match') {
                this.answers = new Array(this.exactMatchQuestions.length).fill('');
                this.$nextTick(() => { document.getElementById('answer-input-0')?.focus(); });
            }
        },
        nextQuestion() {
            if (this.currentQuestion < this.exactMatchQuestions.length - 1) {
                this.currentQuestion++;
                this.$nextTick(() => { document.getElementById('answer-input-' + this.currentQuestion)?.focus(); });
            } else {
                this.submitExactMatch();
            }
        },
        async submitExactMatch() {
            this.submitting = true;
            this.errorMessage = '';
            try {
                const response = await fetch(config.submitUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrf },
                    body: JSON.stringify({ answers: this.answers }),
                });
                const data = await response.json();
                if (!response.ok) { this.errorMessage = data.message || data.error || @js($isArabic ? 'خطأ' : 'Error'); return; }
                this.result = data;
            } catch (error) { this.errorMessage = @js($isArabic ? 'خطأ اتصال' : 'Connection error'); } finally { this.submitting = false; }
        },
        get wordCount() {
            return (this.answerText || '').trim()
                ? (this.answerText || '').trim().split(/\s+/).filter(Boolean).length
                : 0;
        },
        get usedVocabularyCount() {
            if (!this.lessonVocabularyWords.length || !(this.answerText || '').trim()) {
                return 0;
            }

            const matches = (this.answerText || '').toLowerCase().match(/[a-z]+(?:['-][a-z]+)*/g) || [];
            const answerWords = new Set(matches);
            let used = 0;

            for (const word of this.lessonVocabularyWords) {
                if (answerWords.has(word)) {
                    used += 1;
                }
            }

            return used;
        },
        get vocabularyTargetMet() {
            if (this.requiredVocabularyUsage <= 0) {
                return true;
            }

            return this.usedVocabularyCount >= this.requiredVocabularyUsage;
        },
        resetDraft() {
            this.answerText = '';
            this.errorMessage = '';
        },
        async submit() {
            if (!this.answerText.trim()) {
                this.errorMessage = @js($isArabic ? 'اكتب إجابتك أولًا.' : 'Write your answer first.');
                return;
            }

            this.submitting = true;
            this.errorMessage = '';

            try {
                const response = await fetch(config.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': config.csrf,
                    },
                    body: JSON.stringify({
                        answer_text: this.answerText,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    this.errorMessage = data.message || data.error || @js($isArabic ? 'تعذر تقييم الكتابة الآن.' : 'Unable to evaluate your writing right now.');
                    return;
                }

                this.result = data;
            } catch (error) {
                this.errorMessage = @js($isArabic ? 'تعذر الاتصال بخدمة تقييم الكتابة الآن.' : 'Unable to reach the writing evaluator right now.');
            } finally {
                this.submitting = false;
            }
        },
    };
}
</script>
@endpush
