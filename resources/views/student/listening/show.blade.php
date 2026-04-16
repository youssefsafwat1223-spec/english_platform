@extends('layouts.app')

@section('title', ($exercise->title ?: (app()->getLocale() === 'ar' ? 'تدريب الاستماع' : 'Listening Practice')) . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
    $questions = $exercise->questions_json ?? [];
    $lastAttempt = $lastAttempt ?? null;
@endphp

<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">

        {{-- Header --}}
        <x-student.page-header
            title="{{ $exercise->title ?: ($isArabic ? 'تدريب الاستماع' : 'Listening Practice') }}"
            subtitle="{{ $context->title ?? '' }}"
            badge="{{ $isArabic ? 'استماع' : 'Listening' }}"
            badgeColor="accent"
            badgeIcon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 18.364A8 8 0 1112 5.636"/></svg>'
        >
            <x-slot name="actions">
                @if($context instanceof \App\Models\Lesson)
                    <a href="{{ route('student.lessons.show', [$context->course, $context]) }}"
                       class="btn-ghost btn-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isArabic ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
                        </svg>
                        {{ $isArabic ? 'العودة للدرس' : 'Back to lesson' }}
                    </a>
                @elseif($context instanceof \App\Models\CourseLevel)
                    <a href="{{ route('student.courses.learn', $context->course) }}"
                       class="btn-ghost btn-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isArabic ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
                        </svg>
                        {{ $isArabic ? 'العودة للمنهج' : 'Back to course' }}
                    </a>
                @endif
            </x-slot>
        </x-student.page-header>

        {{-- Main Alpine component --}}
        <div x-data="listeningPractice({
                submitUrl: '{{ route('student.listening.submit', $exercise) }}',
                questions: {{ json_encode($questions) }},
                passingScore: {{ $exercise->passing_score }},
                audioUrl: '{{ $exercise->audio_url ?? '' }}',
                csrfToken: '{{ csrf_token() }}'
             })"
             x-init="initTTS()"
             x-cloak>

            @if($exercise->audio_url)
            {{-- Global Audio Player Card (only if audio file exists) --}}
            <div class="card p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-accent-100 dark:bg-accent-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 18.364A8 8 0 1112 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg" style="color:var(--color-text)">
                            {{ $isArabic ? 'استمع للمقطع' : 'Listen to the passage' }}
                        </h3>
                        <p class="text-sm" style="color:var(--color-text-muted)">
                            {{ $isArabic ? 'يمكنك الاستماع أكثر من مرة قبل الإجابة' : 'You can listen multiple times before answering' }}
                        </p>
                    </div>
                </div>

                <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl p-4">
                    <audio x-ref="audioEl"
                           src="{{ $exercise->audio_url }}"
                           @timeupdate="currentTime = $event.target.currentTime"
                           @loadedmetadata="duration = $event.target.duration"
                           @ended="isPlaying = false"
                           class="hidden">
                    </audio>

                    <div class="flex items-center gap-4">
                        <button @click="togglePlay()"
                                class="w-12 h-12 rounded-full bg-accent-500 hover:bg-accent-600 text-white flex items-center justify-center transition-all hover:scale-105 shadow-md flex-shrink-0">
                            <svg x-show="!isPlaying" class="w-5 h-5 ms-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg x-show="isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>

                        <div class="flex-1">
                            <input type="range" min="0" :max="duration || 100" :value="currentTime"
                                   @input="seekTo($event.target.value)"
                                   class="w-full h-2 rounded-full accent-accent-500 cursor-pointer">
                            <div class="flex justify-between text-xs mt-1" style="color:var(--color-text-muted)">
                                <span x-text="formatTime(currentTime)">0:00</span>
                                <span x-text="formatTime(duration)">0:00</span>
                            </div>
                        </div>

                        <div class="text-sm font-bold px-3 py-1 rounded-full bg-white dark:bg-slate-700 shadow-sm flex-shrink-0" style="color:var(--color-text-muted)">
                            <svg class="w-4 h-4 inline me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-text="listenCount"></span>
                        </div>
                    </div>
                </div>
            </div>
            @else
            {{-- Instructions Card (no global audio — each question has its own TTS) --}}
            <div class="card p-6 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent-100 dark:bg-accent-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 18.364A8 8 0 1112 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg" style="color:var(--color-text)">
                            {{ $isArabic ? 'تدريب الاستماع' : 'Listening Practice' }}
                        </h3>
                        <p class="text-sm" style="color:var(--color-text-muted)">
                            {{ $isArabic ? 'اضغط على زر 🔊 بجانب كل سؤال للاستماع، ثم أجب' : 'Press the 🔊 button next to each question to listen, then answer' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Questions --}}
            <div x-show="!submitted" class="card p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-lg" style="color:var(--color-text)">
                        {{ $isArabic ? 'أجب على الأسئلة' : 'Answer the questions' }}
                    </h3>
                    {{-- Mute/Unmute toggle --}}
                    <button type="button" @click="toggleMute()"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 dark:border-white/10 px-3 py-2 text-xs font-black bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/70 transition-colors">
                        <svg x-show="!audioMuted" class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zM15.54 8.46a5 5 0 010 7.07M19.07 4.93a10 10 0 010 14.14"/>
                        </svg>
                        <svg x-show="audioMuted" x-cloak class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l6 6M15 9l-6 6M11 5L6 9H2v6h4l5 4V5z"/>
                        </svg>
                        <span x-text="audioMuted ? '{{ $isArabic ? 'تشغيل الصوت' : 'Unmute' }}' : '{{ $isArabic ? 'كتم الصوت' : 'Mute' }}'"></span>
                    </button>
                </div>

                <template x-for="(q, i) in questions" :key="i">
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-5 space-y-3">
                        {{-- Question text + Listen button --}}
                        <div class="flex items-start gap-3">
                            <button type="button" @click="speakText(q.question || q.prompt, i)"
                                    class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-sm"
                                    :class="speakingIndex === i
                                        ? 'bg-accent-500 text-white scale-110 animate-pulse'
                                        : 'bg-accent-100 dark:bg-accent-900/30 text-accent-600 dark:text-accent-400 hover:bg-accent-200 dark:hover:bg-accent-800/40 hover:scale-105'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.5 5.5A1.5 1.5 0 0 1 9 6.7v6.6a1.5 1.5 0 0 1-2.5 1.2L3 11.4a1.5 1.5 0 0 1 0-2.8l3.5-3.1Zm6.22-.72a.75.75 0 0 1 1.06 0 7.25 7.25 0 0 1 0 10.25.75.75 0 1 1-1.06-1.06 5.75 5.75 0 0 0 0-8.13.75.75 0 0 1 0-1.06Zm-2.12 2.12a.75.75 0 0 1 1.06 0 4.25 4.25 0 0 1 0 6.01.75.75 0 1 1-1.06-1.06 2.75 2.75 0 0 0 0-3.89.75.75 0 0 1 0-1.06Z"/>
                                </svg>
                            </button>
                            <p class="font-semibold pt-2" style="color:var(--color-text)" x-text="(i+1) + '. ' + (q.question || q.prompt)"></p>
                        </div>

                        {{-- MCQ --}}
                        <template x-if="q.type === 'mcq'">
                            <div class="space-y-2">
                                <template x-for="(opt, oi) in q.options" :key="oi">
                                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                           :class="answers[i] == oi
                                               ? 'border-accent-500 bg-accent-50 dark:bg-accent-900/20'
                                               : 'border-slate-200 dark:border-slate-700 hover:border-accent-300'">
                                        <input type="radio" :name="'q_'+i" :value="oi"
                                               x-model="answers[i]" class="accent-accent-500">
                                        <span style="color:var(--color-text)" x-text="opt"></span>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- True / False --}}
                        <template x-if="q.type === 'truefalse'">
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all"
                                       :class="answers[i] === 'true'
                                           ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                           : 'border-slate-200 dark:border-slate-700 hover:border-emerald-300'">
                                    <input type="radio" :name="'q_'+i" value="true" x-model="answers[i]" class="accent-emerald-500">
                                    <span class="font-bold" style="color:var(--color-text)">{{ $isArabic ? 'صح' : 'True' }}</span>
                                </label>
                                <label class="flex-1 flex items-center justify-center gap-2 p-3 rounded-xl border cursor-pointer transition-all"
                                       :class="answers[i] === 'false'
                                           ? 'border-red-500 bg-red-50 dark:bg-red-900/20'
                                           : 'border-slate-200 dark:border-slate-700 hover:border-red-300'">
                                    <input type="radio" :name="'q_'+i" value="false" x-model="answers[i]" class="accent-red-500">
                                    <span class="font-bold" style="color:var(--color-text)">{{ $isArabic ? 'خطأ' : 'False' }}</span>
                                </label>
                            </div>
                        </template>

                        {{-- Dictation --}}
                        <template x-if="q.type === 'dictation'">
                            <div>
                                <p class="text-xs mb-2 font-medium" style="color:var(--color-text-muted);">
                                    {{ $isArabic ? '✍ استمع واكتب الإجابة' : '✍ Listen and write the answer' }}
                                </p>
                                <input
                                    type="text"
                                    x-model="answers[i]"
                                    :placeholder="'{{ $isArabic ? 'اكتب إجابتك هنا...' : 'Type your answer here...' }}'"
                                    class="w-full px-4 py-3 rounded-xl border-2 font-mono text-lg text-center transition-all outline-none"
                                    :class="answers[i]
                                        ? 'border-violet-400 bg-violet-50 dark:bg-violet-900/20'
                                        : 'border-slate-200 dark:border-slate-700'"
                                    style="color:var(--color-text);"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitalize="off"
                                    spellcheck="false"
                                >
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Submit button --}}
                <button @click="submitAnswers()"
                        :disabled="!allAnswered || loading"
                        class="btn-primary w-full py-4 rounded-xl font-black text-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span x-text="loading ? '{{ $isArabic ? 'جارٍ التصحيح...' : 'Checking...' }}' : '{{ $isArabic ? 'إرسال الإجابات' : 'Submit Answers' }}'"></span>
                </button>
            </div>

            {{-- Results --}}
            <div x-show="submitted" x-transition class="card p-6 space-y-6">
                {{-- Score --}}
                <div class="text-center py-6">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-3xl font-black text-white mb-4 shadow-lg"
                         :class="passed ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : 'bg-gradient-to-br from-red-400 to-red-600'">
                        <span x-text="score + '%'"></span>
                    </div>
                    <h3 class="text-2xl font-black mb-1" style="color:var(--color-text)"
                        x-text="passed ? '{{ $isArabic ? 'أحسنت! اجتزت الاختبار' : 'Well done! You passed' }}' : '{{ $isArabic ? 'حاول مرة أخرى' : 'Try again' }}'">
                    </h3>
                    <p class="text-sm" style="color:var(--color-text-muted)"
                       x-text="correctCount + ' / ' + totalQuestions + ' {{ $isArabic ? 'إجابات صحيحة' : 'correct answers' }}'">
                    </p>
                </div>

                {{-- Per-question feedback --}}
                <div class="space-y-3">
                    <template x-for="(r, i) in results" :key="i">
                        <div class="rounded-xl p-4 border"
                             :class="r.correct
                                 ? 'border-emerald-200 bg-emerald-50 dark:bg-emerald-900/10 dark:border-emerald-800'
                                 : 'border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-800'">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                     :class="r.correct ? 'bg-emerald-500' : 'bg-red-500'">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <template x-if="r.correct">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </template>
                                        <template x-if="!r.correct">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                        </template>
                                    </svg>
                                </div>
                                <div class="flex-1 text-sm">
                                    <p class="font-bold mb-1" style="color:var(--color-text)"
                                       x-text="(i+1) + '. ' + (questions[i].question || questions[i].prompt)"></p>
                                    <template x-if="!r.correct">
                                        <div>
                                            <template x-if="questions[i].type === 'dictation'">
                                                <p class="text-slate-500 dark:text-slate-400 text-xs">
                                                    {{ $isArabic ? 'كتبت:' : 'You wrote:' }}
                                                    <span class="font-mono line-through" x-text="r.student_answer || '—'"></span>
                                                </p>
                                            </template>
                                            <p class="text-red-600 dark:text-red-400">
                                                {{ $isArabic ? 'الإجابة الصحيحة:' : 'Correct answer:' }}
                                                <strong x-text="r.correct_answer"></strong>
                                            </p>
                                        </div>
                                    </template>
                                    <template x-if="r.explanation">
                                        <p class="mt-1 text-slate-500 dark:text-slate-400" x-text="r.explanation"></p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Retry --}}
                <button @click="resetExercise()"
                        class="btn-ghost w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ $isArabic ? 'حاول مرة أخرى' : 'Try again' }}
                </button>
            </div>

        </div>{{-- end alpine --}}
    </div>
</div>

@push('scripts')
<script>
function listeningPractice(config) {
    return {
        questions:    config.questions,
        passingScore: config.passingScore,
        audioUrl:     config.audioUrl,
        submitUrl:    config.submitUrl,
        csrfToken:    config.csrfToken,

        // audio state
        isPlaying:   false,
        currentTime: 0,
        duration:    0,
        listenCount: 0,

        // TTS state
        audioMuted:    false,
        speakingIndex: null,

        // quiz state
        answers:      {},
        loading:      false,
        submitted:    false,
        score:        0,
        correctCount: 0,
        totalQuestions: 0,
        passed:       false,
        results:      [],

        initTTS() {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.getVoices();
                window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
            }
        },

        get allAnswered() {
            return Object.keys(this.answers).length === this.questions.length
                && Object.values(this.answers).every(v => v !== null && v !== undefined && v !== '');
        },

        // ─── TTS (same as quiz system) ───
        detectLang(text) {
            return /[\u0600-\u06FF]/.test(text) ? 'ar-SA' : 'en-US';
        },

        speakText(text, index) {
            if (!('speechSynthesis' in window) || this.audioMuted) return;
            if (!text || !text.trim()) return;

            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = this.detectLang(text);
            utterance.rate = 0.9;

            const voices = window.speechSynthesis.getVoices();
            const preferred = voices.find(v =>
                v.lang.toLowerCase().startsWith(utterance.lang.toLowerCase().slice(0, 2))
            );
            if (preferred) utterance.voice = preferred;

            this.speakingIndex = index;
            utterance.onend = () => { this.speakingIndex = null; };
            utterance.onerror = () => { this.speakingIndex = null; };

            window.speechSynthesis.speak(utterance);
        },

        toggleMute() {
            this.audioMuted = !this.audioMuted;
            if (this.audioMuted && 'speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                this.speakingIndex = null;
            }
        },

        // ─── Global audio player (when audio_url exists) ───
        togglePlay() {
            const el = this.$refs.audioEl;
            if (!el) return;
            if (this.isPlaying) {
                el.pause();
                this.isPlaying = false;
            } else {
                el.play();
                this.isPlaying = true;
                if (this.currentTime === 0 || this.currentTime >= this.duration) {
                    this.listenCount++;
                }
            }
        },

        seekTo(val) {
            const el = this.$refs.audioEl;
            if (el) el.currentTime = val;
        },

        formatTime(secs) {
            if (!secs || isNaN(secs)) return '0:00';
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60).toString().padStart(2, '0');
            return m + ':' + s;
        },

        // ─── Submit / Reset ───
        async submitAnswers() {
            if (!this.allAnswered || this.loading) return;
            this.loading = true;
            try {
                const res = await fetch(this.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ answers: this.answers }),
                });
                const data = await res.json();
                this.score          = data.score;
                this.correctCount   = data.correct;
                this.totalQuestions = data.total;
                this.passed         = data.passed;
                this.results        = data.results;
                this.submitted      = true;
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        resetExercise() {
            this.answers   = {};
            this.submitted = false;
            this.results   = [];
            this.score     = 0;
            const el = this.$refs.audioEl;
            if (el) { el.pause(); el.currentTime = 0; }
            this.isPlaying   = false;
            this.currentTime = 0;
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
            this.speakingIndex = null;
        },
    };
}
</script>
@endpush
@endsection
