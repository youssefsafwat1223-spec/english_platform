@extends('layouts.app')

@section('title', ($writingExercise->title ?: __('Writing Practice')) . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        <x-student.page-header
            title="{{ $writingExercise->title ?: __('Writing Practice') }}"
            subtitle="{{ $writingExercise->lesson->title }}"
            badge="{{ __('Writing') }}"
            badgeColor="primary"
            badgeIcon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>'
        >
            <x-slot name="actions">
                <a href="{{ route('student.lessons.show', [$writingExercise->lesson->course, $writingExercise->lesson]) }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back to lesson') }}
                </a>
            </x-slot>
        </x-student.page-header>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8" x-data="writingPractice(@js([
            'submitUrl' => route('student.writing.submit', $writingExercise),
            'csrf' => csrf_token(),
            'minWords' => $writingExercise->min_words,
            'maxWords' => $writingExercise->max_words,
            'initialText' => old('answer_text', ''),
        ]))">
            <div class="xl:col-span-2 space-y-6">
                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20">
                        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ __('Task Prompt') }}</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <p class="text-base leading-8 text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $writingExercise->prompt }}</p>
                        @if($writingExercise->instructions)
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-200/70 dark:border-white/10 p-4">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Instructions') }}</div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $writingExercise->instructions }}</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ __('Minimum') }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->min_words }}</div>
                                <div class="text-xs text-slate-500">{{ __('words') }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ __('Maximum') }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->max_words }}</div>
                                <div class="text-xs text-slate-500">{{ __('words') }}</div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/70 dark:bg-white/[0.02]">
                                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold mb-1">{{ __('Passing Score') }}</div>
                                <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $writingExercise->passing_score }}%</div>
                                <div class="text-xs text-slate-500">{{ __('target') }}</div>
                            </div>
                        </div>
                    </div>
                </x-student.card>

                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between gap-4">
                        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ __('Your Writing') }}</h2>
                        <div class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold"
                             :class="wordCount < minWords ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : wordCount > maxWords ? 'bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'">
                            <span x-text="wordCount"></span>
                            <span>{{ __('words') }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <textarea
                            x-model="answerText"
                            rows="14"
                            class="input-glass min-h-[320px]"
                            placeholder="{{ __('Write your answer here...') }}"
                        ></textarea>
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Aim for') }} {{ $writingExercise->min_words }}-{{ $writingExercise->max_words }} {{ __('words and keep your answer focused on the prompt.') }}
                            </p>
                            <div class="flex gap-3">
                                <button type="button" class="btn-ghost" @click="resetDraft()" x-bind:disabled="submitting">{{ __('Clear Draft') }}</button>
                                <button type="button" class="btn-primary ripple-btn min-w-[170px]" @click="submit()" x-bind:disabled="submitting">
                                    <span x-show="!submitting">{{ __('Submit Writing') }}</span>
                                    <span x-show="submitting" x-cloak>{{ __('Analyzing your writing...') }}</span>
                                </button>
                            </div>
                        </div>
                        <p x-show="errorMessage" x-text="errorMessage" x-cloak class="text-sm font-semibold text-red-500"></p>
                    </div>
                </x-student.card>

                <x-student.card x-show="result" x-cloak padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ __('AI Writing Feedback') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400" x-text="result ? result.summary : ''"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl px-4 py-3 bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                <div class="text-xs uppercase tracking-wider font-bold">{{ __('Overall') }}</div>
                                <div class="text-2xl font-black" x-text="result ? result.overall_score + '%' : ''"></div>
                            </div>
                            <div class="rounded-2xl px-4 py-3"
                                 :class="result && result.passed ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400'">
                                <div class="text-xs uppercase tracking-wider font-bold">{{ __('Status') }}</div>
                                <div class="text-sm font-black" x-text="result && result.passed ? '{{ __('Passed') }}' : '{{ __('Needs more work') }}'"></div>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-2xl border border-slate-200/70 dark:border-white/10 p-5 bg-slate-50/80 dark:bg-white/[0.02]">
                                <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ __('Strengths') }}</h3>
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
                                <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ __('What To Improve') }}</h3>
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
                            <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ __('Grammar Alerts') }}</h3>
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
                            <h3 class="font-extrabold text-slate-900 dark:text-white mb-3">{{ __('Suggested Rewrite') }}</h3>
                            <p class="text-sm leading-7 text-slate-700 dark:text-slate-300 whitespace-pre-line" x-text="result ? result.rewrite_suggestion : ''"></p>
                        </div>
                    </div>
                </x-student.card>
            </div>

            <div class="space-y-6">
                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20">
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Recent Attempts') }}</h2>
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
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No writing attempts yet. Submit your first answer to see feedback here.') }}</p>
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
        metrics: [
            { key: 'grammar_score', label: 'Grammar' },
            { key: 'vocabulary_score', label: 'Vocabulary' },
            { key: 'coherence_score', label: 'Coherence' },
            { key: 'task_score', label: 'Task' },
        ],
        get wordCount() {
            return (this.answerText || '').trim()
                ? (this.answerText || '').trim().split(/\s+/).filter(Boolean).length
                : 0;
        },
        resetDraft() {
            this.answerText = '';
            this.errorMessage = '';
        },
        async submit() {
            if (!this.answerText.trim()) {
                this.errorMessage = '{{ __('Write your answer first.') }}';
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
                    this.errorMessage = data.message || data.error || '{{ __('Unable to evaluate your writing right now.') }}';
                    return;
                }

                this.result = data;
            } catch (error) {
                this.errorMessage = '{{ __('Unable to reach the writing evaluator right now.') }}';
            } finally {
                this.submitting = false;
            }
        },
    };
}
</script>
@endpush
