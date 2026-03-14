@extends('layouts.app')

@section('title', 'Take Quiz: ' . $quiz->title . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10" x-data="quizController()" x-init="initQuiz()">
    {{-- Animated Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-500/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/20 rounded-full blur-[100px] pointer-events-none z-0 animate-pulse"></div>
    <div class="absolute top-40 -left-40 w-96 h-96 bg-accent-500/20 rounded-full blur-[100px] pointer-events-none z-0 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8" data-aos="fade-down">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 text-primary-600 dark:text-primary-400 text-sm font-bold mb-3 border border-primary-500/20 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="formatTime(timeLeft)"></span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $quiz->title }}
                </h1>
            </div>
            <div class="shrink-0">
                <button type="button" @click="$refs.submitModal.showModal()" class="btn-secondary ripple-btn flex items-center gap-2 group shadow-sm bg-white/50 dark:bg-slate-800/50 backdrop-blur-md">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Finish Early') }}
                </button>
            </div>
        </div>

        @if(session('error') || $errors->any())
            <div class="mb-8" data-aos="fade-in">
                <div class="glass-card bg-red-500/10 border-red-500/20 p-4">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center shrink-0">
                            <span class="text-red-500 text-xl">⚠️</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-red-500 dark:text-red-400">{{ __('Please review the following errors:') }}</h3>
                            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 mt-2">
                                @if(session('error')) <li>{{ session('error') }}</li> @endif
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Progress Bar --}}
        <div class="glass-card p-6 mb-8 shadow-xl border-white/20 dark:border-white/10" data-aos="fade-up">
            <div class="flex items-center justify-between text-sm font-bold mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 text-white flex items-center justify-center text-lg shadow-md">
                        <span x-text="currentQuestion + 1"></span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400">{{ __('Question') }}</span>
                        <div class="text-slate-900 dark:text-white">{{ __('of') }} {{ count($quiz->questions ?? []) }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-primary-600 dark:text-primary-400 text-lg" x-text="Math.round(((currentQuestion + 1) / {{ count($quiz->questions ?? []) }}) * 100) + '%'"></div>
                    <div class="text-slate-500 dark:text-slate-400">{{ __('Completed') }}</div>
                </div>
            </div>
            <div class="w-full h-3 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden shadow-inner flex relative">
                <div class="h-full bg-gradient-to-r from-primary-500 to-accent-500 rounded-full transition-all duration-500 ease-out relative flex-shrink-0" :style="`width: ${((currentQuestion + 1) / {{ count($quiz->questions ?? []) }}) * 100}%`">
                    <div class="absolute inset-0 bg-white/20 w-full h-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" @submit.prevent="submitQuiz" id="quizForm" x-ref="quizForm">
            @csrf
            <input type="hidden" name="started_at" x-model="startedAt">
            <input type="hidden" name="completed_at" x-model="completedAt">

            {{-- Questions Container --}}
            <div class="relative grid items-start">
                @foreach($quiz->questions as $qIndex => $question)
                    <div class="col-start-1 row-start-1 w-full transition-all duration-500 ease-in-out"
                         x-show="currentQuestion === {{ $qIndex }}"
                         x-transition:enter="transition ease-out duration-500 delay-100"
                         x-transition:enter-start="opacity-0 translate-x-8 lg:translate-x-16"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300 absolute"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-8 lg:-translate-x-16">
                        
                        <div class="glass-card overflow-hidden shadow-2xl border border-white/40 dark:border-white/10 relative p-8 md:p-10 rounded-[2rem]">
                            {{-- Fancy Top Accent --}}
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary-500 via-accent-500 to-violet-500"></div>
                            
                            @if($question->has_audio && $question->audio_url)
                                <div class="mb-8 p-1 rounded-2xl bg-gradient-to-r from-primary-500/20 to-accent-500/20 shadow-inner inline-block w-full sm:max-w-sm">
                                    <audio id="audio-{{ $qIndex }}" controls class="w-full h-12 rounded-[1rem] custom-audio-player focus:outline-none" style="background: transparent;">
                                        <source src="{{ $question->audio_url }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            @endif

                            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mb-10 leading-relaxed">
                                {{ $question->text ?? $question->question_text }}
                            </h2>

                            <div class="space-y-4">
                                <input type="hidden" name="answers[{{ $qIndex }}][question_id]" value="{{ $question->id }}">
                                
                                @if($question->question_type === 'drag_drop' && $question->matching_pairs)
                                    {{-- Drag & Drop Matching UI --}}
                                    <div x-data="matchingQuestion{{ $qIndex }}()" class="space-y-6">
                                        <input type="hidden" name="answers[{{ $qIndex }}][user_answer]" :value="getAnswerJSON()">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            {{-- Left Column (Items to match) --}}
                                            <div class="space-y-3">
                                                <h4 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ __('العناصر') }}</h4>
                                                <template x-for="(item, idx) in leftItems" :key="'left-'+idx">
                                                    <div class="flex items-center gap-3 p-4 rounded-2xl border-2 transition-all duration-300 cursor-pointer"
                                                         :class="selectedLeft === idx 
                                                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30 shadow-lg shadow-primary-500/10 scale-[1.02]' 
                                                            : (matches[idx] !== null 
                                                                ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' 
                                                                : 'border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800/50 hover:border-primary-400/50 hover:shadow-md')"
                                                         @click="selectLeft(idx)">
                                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 transition-all"
                                                             :class="matches[idx] !== null ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500'">
                                                            <span x-text="idx + 1"></span>
                                                        </div>
                                                        <span class="font-bold text-lg flex-1" style="color: var(--color-text);" x-text="item"></span>
                                                        <template x-if="matches[idx] !== null">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1 rounded-full" x-text="rightItems[matches[idx]]"></span>
                                                                <button type="button" @click.stop="clearMatch(idx)" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- Right Column (Choices - shuffled) --}}
                                            <div class="space-y-3">
                                                <h4 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ __('المطابقات') }}</h4>
                                                <template x-for="(item, idx) in rightItems" :key="'right-'+idx">
                                                    <div class="p-4 rounded-2xl border-2 transition-all duration-300 cursor-pointer"
                                                         :class="isRightUsed(idx)
                                                            ? 'border-emerald-400/50 bg-emerald-50/50 dark:bg-emerald-900/10 opacity-50'
                                                            : (selectedLeft !== null
                                                                ? 'border-accent-400 bg-accent-50 dark:bg-accent-900/20 hover:shadow-lg hover:scale-[1.02] animate-pulse-subtle'
                                                                : 'border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800/50 hover:border-primary-400/50')"
                                                         @click="matchRight(idx)">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shrink-0"
                                                                 :class="isRightUsed(idx) ? 'bg-emerald-200 dark:bg-emerald-800 text-emerald-600' : 'bg-accent-100 dark:bg-accent-900/40 text-accent-600'">
                                                                <span x-text="String.fromCharCode(65 + idx)"></span>
                                                            </div>
                                                            <span class="font-bold text-lg" style="color: var(--color-text);" x-text="item"></span>
                                                            <template x-if="isRightUsed(idx)">
                                                                <span class="ml-auto text-emerald-500">✓</span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <p class="text-sm text-center" style="color: var(--color-text-muted);">
                                            <span x-show="selectedLeft === null">{{ __('اضغط على عنصر من اليسار ثم اختر المطابق من اليمين') }}</span>
                                            <span x-show="selectedLeft !== null" class="text-primary-500 font-bold">{{ __('الآن اختر المطابق من العمود الأيمن') }}</span>
                                        </p>
                                    </div>
                                @else
                                    {{-- Standard Options (Radio Buttons) --}}
                                    @foreach($question->options as $oIndex => $option)
                                        @php $displayOption = (string)$option; @endphp
                                        @if(strlen(trim($displayOption)) > 0)
                                        <label class="group flex items-center p-5 rounded-2xl cursor-pointer transition-all duration-300 border-2 relative overflow-hidden focus-within:ring-4 focus-within:ring-primary-500/30"
                                            :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30 shadow-lg shadow-primary-500/10 scale-[1.02] z-10' : 'border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800/50 hover:border-primary-400/50 hover:bg-slate-50 dark:hover:bg-slate-800 hover:shadow-md'">
                                            
                                            <input type="radio" name="answers[{{ $qIndex }}][user_answer]" value="{{ $oIndex }}" class="sr-only" x-model="answers[{{ $qIndex }}]">
                                            
                                            <div class="shrink-0 mr-5 flex flex-col items-center justify-center">
                                                <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                                                    :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500' : 'border-slate-300 dark:border-slate-600 group-hover:border-primary-400'">
                                                    <div class="w-3.5 h-3.5 rounded-full bg-primary-500 transition-all duration-300 transform"
                                                        :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'scale-100 opacity-100 shadow-sm' : 'scale-0 opacity-0'"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm mr-5 transition-all duration-300 shadow-sm"
                                                :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'bg-gradient-to-br from-primary-500 to-accent-500 text-white shadow-primary-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-600'">
                                                {{ $oIndex }}
                                            </div>

                                            <span class="font-bold text-lg md:text-xl transition-all duration-300 flex-1"
                                                :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'text-primary-700 dark:text-primary-300' : 'text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white'">
                                                {{ $displayOption }}
                                            </span>
                                            
                                            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/5 via-accent-500/5 to-transparent opacity-0 transition-opacity duration-300 pointer-events-none" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'opacity-100' : 'group-hover:opacity-100'"></div>
                                        </label>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Post-Questions Navigation spacer --}}
            <div class="h-10"></div> 

            {{-- Footer Navigation Controls --}}
            <div class="mt-4 flex items-center justify-between glass-card px-6 py-5 shadow-xl border-white/20 dark:border-white/10" data-aos="fade-up" data-aos-delay="200">
                <button type="button" @click="currentQuestion = Math.max(0, currentQuestion - 1)" 
                    class="btn-secondary flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed border-0 hover:bg-slate-200 dark:hover:bg-slate-700 bg-white dark:bg-slate-800"
                    :disabled="currentQuestion === 0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span class="hidden sm:inline">{{ __('Previous') }}</span>
                </button>

                {{-- Dot Indicators --}}
                <div class="hidden md:flex items-center gap-2.5 px-6">
                    @foreach($quiz->questions as $i => $q)
                        <button type="button" @click="currentQuestion = {{ $i }}" 
                            class="w-3.5 h-3.5 rounded-full transition-all duration-300 shrink-0 border border-transparent hover:scale-125 focus:outline-none"
                            :class="{
                                'bg-primary-500 shadow-[0_0_12px_rgba(var(--color-primary-500),0.6)] scale-125 ring-2 ring-primary-500/30 ring-offset-2 dark:ring-offset-slate-900': currentQuestion === {{ $i }},
                                'bg-emerald-400': currentQuestion !== {{ $i }} && answers[{{ $i }}],
                                'bg-slate-200 dark:bg-slate-700 border-slate-300 dark:border-slate-600': currentQuestion !== {{ $i }} && !answers[{{ $i }}]
                            }"
                            title="Question {{ $i + 1 }}"></button>
                    @endforeach
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="currentQuestion = Math.min({{ count($quiz->questions ?? []) - 1 }}, currentQuestion + 1)" 
                        x-show="currentQuestion < {{ count($quiz->questions ?? []) - 1 }}" 
                        class="btn-primary flex items-center gap-2 px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary-500/25 transition-all transform hover:-translate-y-1">
                        <span class="hidden sm:inline">{{ __('Next') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    
                    <button type="button" @click="$refs.submitModal.showModal()"
                        x-show="currentQuestion === {{ count($quiz->questions ?? []) - 1 }}" 
                        class="btn-primary flex items-center gap-2 px-8 py-3 rounded-xl font-black shadow-xl shadow-accent-500/30 bg-gradient-to-r from-accent-500 to-primary-600 hover:from-accent-400 hover:to-primary-500 transition-all transform hover:-translate-y-1 border-0" 
                        :disabled="loading">
                        <span x-show="!loading" class="flex items-center gap-2">
                            {{ __('Submit Quiz') }}
                            <svg class="w-5 h-5 bg-white/20 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            {{ __('Submitting...') }}
                        </span>
                    </button>
                </div>
            </div>

            {{-- Submit Confirmation Modal --}}
            <dialog x-ref="submitModal" class="glass-card overflow-visible rounded-[2rem] p-0 backdrop:bg-slate-900/80 backdrop:backdrop-blur-sm open:animate-zoom-in bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl w-full max-w-md m-auto z-50">
                <div class="p-8 text-center relative overflow-hidden rounded-[2rem]">
                    <div class="absolute inset-0 bg-gradient-to-b from-primary-500/10 to-transparent pointer-events-none"></div>
                    
                    {{-- Avatar/Icon overlapping top --}}
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 text-white flex items-center justify-center text-4xl mx-auto mb-6 shadow-xl relative z-10 ring-4 ring-white dark:ring-slate-900">
                        🏆
                    </div>
                    
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-3 relative z-10">{{ __('Ready to submit?') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 font-medium mb-8 relative z-10 text-lg">
                        {{ __('You answered') }} <span class="font-bold text-primary-600 dark:text-primary-400" x-text="Object.keys(answers).length"></span> {{ __('out of') }} {{ count($quiz->questions ?? []) }}.
                        <span x-show="Object.keys(answers).length < {{ count($quiz->questions ?? []) }}" class="block mt-3 text-red-500 font-bold bg-red-500/10 rounded-xl py-2 px-4 shadow-sm mx-auto w-max">
                            ⚠️ {{ __('You have unanswered questions!') }}
                        </span>
                    </p>
                    
                    <div class="flex gap-4 relative z-10 mt-8">
                        <button type="button" @click="$refs.submitModal.close()" class="btn-secondary flex-1 justify-center rounded-xl py-3.5 font-bold shadow-sm bg-white dark:bg-slate-800">{{ __('Review') }}</button>
                        <button type="button" @click="$refs.submitModal.close(); submitQuiz()" class="btn-primary flex-1 justify-center rounded-xl py-3.5 shadow-xl shadow-primary-500/30 font-black">{{ __('Submit Now') }}</button>
                    </div>
                </div>
            </dialog>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quizController() {
    return {
        currentQuestion: 0,
        answers: {},
        loading: false,
        timeLeft: {{ ($quiz->time_limit ?? 0) * 60 }},
        timerInterval: null,
        startedAt: new Date().toISOString(),
        completedAt: '',
        
        initQuiz() {
            this.startTimer();
            this.$watch('currentQuestion', (val) => this.playAudio(val));
            // Play initial audio if allowed by browser
            this.$nextTick(() => {
                setTimeout(() => this.playAudio(0), 500); // slight delay to ensure render
            });
            
            // Listen for keyboard navigation
            window.addEventListener('keydown', (e) => {
                // Prevent intercepting arrow keys if interacting with input or audio controls
                const active = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
                if (['input', 'textarea', 'audio', 'video', 'select', 'button'].includes(active)) {
                    return;
                }
                
                if(e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    if (this.currentQuestion < {{ count($quiz->questions ?? []) - 1 }}) {
                        this.currentQuestion++;
                        e.preventDefault();
                    }
                } else if(e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                        e.preventDefault();
                    }
                }
            });
        },
        
        playAudio(index) {
            // Stop all playing audio
            document.querySelectorAll('audio').forEach(audio => {
                audio.pause();
                audio.currentTime = 0;
            });
            
            // Play target audio
            const audio = document.getElementById(`audio-${index}`);
            if (audio) {
                audio.play().catch(error => {
                    console.log("Audio play failed normal behavior:", error);
                });
            }
        },
        
        startTimer() {
            if (this.timeLeft <= 0) return;
            this.timerInterval = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    clearInterval(this.timerInterval);
                    this.submitQuiz();
                }
            }, 1000);
        },
        
        submitQuiz() {
            this.completedAt = new Date().toISOString();
            this.loading = true;
            this.$nextTick(() => {
                this.$refs.quizForm.submit();
            });
        },
        
        formatTime(s) {
            if(s < 0) return '00:00';
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        }
    };
}

{{-- Matching Question Components --}}
@foreach($quiz->questions as $qIndex => $question)
    @if($question->question_type === 'drag_drop' && $question->matching_pairs)
    function matchingQuestion{{ $qIndex }}() {
        const pairs = @json($question->matching_pairs);
        const leftItems = pairs.map(p => p.left);
        // Shuffle right items
        const rightItems = pairs.map(p => p.right).sort(() => Math.random() - 0.5);

        return {
            leftItems,
            rightItems,
            selectedLeft: null,
            matches: new Array(leftItems.length).fill(null), // matches[leftIdx] = rightIdx

            selectLeft(idx) {
                if (this.matches[idx] !== null) {
                    // Already matched, deselect
                    this.selectedLeft = null;
                    return;
                }
                this.selectedLeft = idx;
            },

            matchRight(rightIdx) {
                if (this.selectedLeft === null) return;
                if (this.isRightUsed(rightIdx)) return;

                this.matches[this.selectedLeft] = rightIdx;
                this.selectedLeft = null;

                // Mark as answered in parent quiz controller
                if (this.allMatched()) {
                    // Set a special marker in the parent answers
                    const quizCtrl = Alpine.closestDataStack(this.$el).find(d => d.answers !== undefined);
                    if (quizCtrl) {
                        quizCtrl.answers[{{ $qIndex }}] = 'matched';
                    }
                }
            },

            clearMatch(leftIdx) {
                this.matches[leftIdx] = null;
                // Clear parent answer marker
                const quizCtrl = Alpine.closestDataStack(this.$el).find(d => d.answers !== undefined);
                if (quizCtrl) {
                    delete quizCtrl.answers[{{ $qIndex }}];
                }
            },

            isRightUsed(rightIdx) {
                return this.matches.includes(rightIdx);
            },

            allMatched() {
                return this.matches.every(m => m !== null);
            },

            getAnswerJSON() {
                if (!this.allMatched()) return '';
                const result = this.leftItems.map((left, idx) => ({
                    left: left,
                    right: this.rightItems[this.matches[idx]]
                }));
                return JSON.stringify(result);
            }
        };
    }
    @endif
@endforeach
</script>
<style>
/* Custom animations & scrollbar */
@keyframes zoom-in {
    0% { opacity: 0; transform: scale(0.95) translateY(10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-zoom-in { animation: zoom-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

/* Subtle pulse for matching UI */
@keyframes pulse-subtle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.85; }
}
.animate-pulse-subtle { animation: pulse-subtle 1.5s ease-in-out infinite; }

/* Custom audio player styling */
audio.custom-audio-player::-webkit-media-controls-panel {
    background-color: transparent !important;
}
</style>
@endpush
@endsection