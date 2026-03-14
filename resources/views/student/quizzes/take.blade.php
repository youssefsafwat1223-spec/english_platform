@extends('layouts.app')

@section('title', 'Take Quiz: ' . $quiz->title . ' — ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white pb-24 md:pb-12" x-data="quizController()" x-init="initQuiz()">
    
    {{-- Top Sticky Navigation & Progress --}}
    <div class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-b border-slate-200 dark:border-white/10 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 flex items-center justify-between gap-4">
                {{-- Left: Title & Question Counter --}}
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg md:text-xl font-bold truncate">{{ $quiz->title }}</h1>
                    <div class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                        {{ __('Question') }} <span x-text="currentQuestion + 1"></span> {{ __('of') }} {{ count($quiz->questions ?? []) }}
                    </div>
                </div>
                
                {{-- Right: Timer & Finish --}}
                <div class="flex items-center gap-3 shrink-0 relative">
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-bold">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="formatTime(timeLeft)"></span>
                    </div>
                    <button type="button" @click="$refs.submitModal.showModal()" class="btn-secondary text-sm px-4 py-2 rounded-lg font-bold">
                        {{ __('Finish Early') }}
                    </button>
                    <!-- Mobile Time Badge Absolute -->
                    <div class="sm:hidden absolute -bottom-10 right-0 items-center gap-1.5 px-2 py-1 rounded-full bg-slate-900/10 dark:bg-slate-100/10 backdrop-blur shadow-sm text-xs font-bold rtl:-left-0">
                        <span x-text="formatTime(timeLeft)"></span> ⏳
                    </div>
                </div>
            </div>
            
            {{-- Slim Progress Bar --}}
            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1.5 rounded-t-lg overflow-hidden">
                <div class="bg-primary-500 h-full rounded-r-full transition-all duration-300 ease-out" :style="`width: ${((currentQuestion + 1) / {{ count($quiz->questions ?? []) }}) * 100}%`"></div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 md:pt-12">
        
        @if(session('error') || $errors->any())
            <div class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h3 class="font-bold">{{ __('Please review the following errors:') }}</h3>
                        <ul class="list-disc list-inside text-sm opacity-90 mt-1">
                            @if(session('error')) <li>{{ session('error') }}</li> @endif
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" @submit.prevent="submitQuiz" id="quizForm" x-ref="quizForm">
            @csrf
            <input type="hidden" name="started_at" x-model="startedAt">
            <input type="hidden" name="completed_at" x-model="completedAt">

            {{-- Sequential Questions Loop (Solid Blocks) --}}
            @foreach($quiz->questions as $qIndex => $question)
                <div class="w-full" x-show="currentQuestion === {{ $qIndex }}" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    {{-- Question Card --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 shadow-sm border border-slate-200 dark:border-slate-800 mb-8">
                        
                        @if($question->has_audio && $question->audio_url)
                            <div class="mb-8 mx-auto max-w-sm rounded-[1.5rem] p-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-inner">
                                <audio id="audio-{{ $qIndex }}" controls class="w-full h-12 focus:outline-none custom-audio-player">
                                    <source src="{{ $question->audio_url }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endif

                        <h2 class="text-2xl md:text-3xl font-extrabold text-center mb-10 leading-relaxed">
                            {{ $question->text ?? $question->question_text }}
                        </h2>

                        <div class="space-y-4 max-w-3xl mx-auto">
                            <input type="hidden" name="answers[{{ $qIndex }}][question_id]" value="{{ $question->id }}">
                            
                            @if($question->question_type === 'drag_drop' && $question->matching_pairs)
                                {{-- Drag & Drop Matching UI --}}
                                <div x-data="matchingQuestion{{ $qIndex }}()" class="space-y-8">
                                    <input type="hidden" name="answers[{{ $qIndex }}][user_answer]" :value="getAnswerJSON()">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                                        {{-- Left Column (Items to match) --}}
                                        <div class="space-y-3">
                                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2 px-2">{{ __('العناصر (اختر الأول)') }}</h4>
                                            <template x-for="(item, idx) in leftItems" :key="'left-'+idx">
                                                <div class="flex items-center gap-3 p-4 rounded-2xl border-2 transition-all cursor-pointer"
                                                     :class="selectedLeft === idx 
                                                        ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 shadow-sm scale-[1.01]' 
                                                        : (matches[idx] !== null 
                                                            ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/10' 
                                                            : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600')"
                                                     @click="selectLeft(idx)">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors"
                                                         :class="matches[idx] !== null ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-slate-500 shadow-sm'">
                                                        <span x-text="idx + 1"></span>
                                                    </div>
                                                    <span class="font-bold text-lg flex-1" x-text="item"></span>
                                                    
                                                    <template x-if="matches[idx] !== null">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1 rounded-full truncate max-w-[120px]" x-text="rightItems[matches[idx]]"></span>
                                                            <button type="button" @click.stop="clearMatch(idx)" class="w-6 h-6 rounded-full bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition-colors">✕</button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- Right Column (Choices - shuffled) --}}
                                        <div class="space-y-3">
                                            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2 px-2">{{ __('المطابقات (اختر الثاني)') }}</h4>
                                            <template x-for="(item, idx) in rightItems" :key="'right-'+idx">
                                                <div class="flex items-center gap-3 p-4 rounded-2xl border-2 transition-all cursor-pointer"
                                                     :class="isRightUsed(idx)
                                                        ? 'border-emerald-200 dark:border-emerald-800/30 bg-slate-50 dark:bg-slate-900 opacity-50'
                                                        : (selectedLeft !== null
                                                            ? 'border-primary-300 dark:border-primary-700 bg-white dark:bg-slate-800 shadow-md animate-pulse-subtle'
                                                            : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600')"
                                                     @click="matchRight(idx)">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-sm"
                                                         :class="isRightUsed(idx) ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300'">
                                                        <span x-text="String.fromCharCode(65 + idx)"></span>
                                                    </div>
                                                    <span class="font-bold text-lg flex-1" x-text="item"></span>
                                                    <template x-if="isRightUsed(idx)">
                                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Standard Options (Radio Buttons) --}}
                                @foreach($question->options as $oIndex => $option)
                                    @php $displayOption = (string)$option; @endphp
                                    @if(strlen(trim($displayOption)) > 0)
                                    <label class="group flex items-center p-4 md:p-5 rounded-2xl cursor-pointer transition-all duration-200 border-2"
                                        :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-900/20' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600'">
                                        
                                        <input type="radio" name="answers[{{ $qIndex }}][user_answer]" value="{{ $oIndex }}" class="sr-only" x-model="answers[{{ $qIndex }}]">
                                        
                                        {{-- Radio Circle Check --}}
                                        <div class="shrink-0 mr-4 md:mr-6 flex items-center justify-center w-6 h-6 rounded-full border-2 transition-colors"
                                            :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500' : 'border-slate-300 dark:border-slate-600 group-hover:border-slate-400'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-500 transition-transform transform"
                                                :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'scale-100' : 'scale-0'"></div>
                                        </div>
                                        
                                        {{-- Letter Circle --}}
                                        <div class="hidden md:flex shrink-0 w-8 h-8 rounded-full items-center justify-center font-bold text-xs mr-4 transition-colors"
                                            :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400' : 'bg-white dark:bg-slate-700 shadow-sm text-slate-500'">
                                            {{ $oIndex }}
                                        </div>

                                        {{-- Text --}}
                                        <span class="font-bold text-lg transition-colors flex-1"
                                            :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'text-primary-700 dark:text-primary-400' : 'text-slate-700 dark:text-slate-300'">
                                            {{ $displayOption }}
                                        </span>
                                    </label>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Floating Bottom Navigation --}}
            <div class="fixed bottom-0 md:bottom-6 left-0 right-0 z-40 px-0 md:px-6 pointer-events-none">
                <div class="max-w-4xl mx-auto pointer-events-auto bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl border-t md:border border-slate-200 dark:border-slate-700 rounded-t-[2rem] md:rounded-[2rem] shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] p-4 sm:p-5 flex items-center justify-between gap-4">
                    
                    <button type="button" @click="currentQuestion = Math.max(0, currentQuestion - 1)" 
                        class="btn-secondary flex-1 sm:flex-none flex justify-center items-center gap-2 px-6 py-3.5 sm:py-3 rounded-xl font-bold transition-all disabled:opacity-30 disabled:cursor-not-allowed bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-950 border border-slate-200 dark:border-slate-700"
                        :disabled="currentQuestion === 0">
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="hidden sm:inline text-sm">{{ __('Previous') }}</span>
                    </button>

                    <div class="hidden md:flex flex-wrap items-center justify-center gap-1.5 flex-1 p-2">
                        @foreach($quiz->questions as $i => $q)
                            <button type="button" @click="currentQuestion = {{ $i }}" 
                                class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none"
                                :class="{
                                    'bg-primary-500 scale-125 ring-4 ring-primary-500/20': currentQuestion === {{ $i }},
                                    'bg-slate-800 dark:bg-slate-300': currentQuestion !== {{ $i }} && answers[{{ $i }}],
                                    'bg-slate-200 dark:bg-slate-700': currentQuestion !== {{ $i }} && !answers[{{ $i }}]
                                }"
                                title="Question {{ $i + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="flex-1 sm:flex-none flex justify-end">
                        <button type="button" @click="currentQuestion = Math.min({{ count($quiz->questions ?? []) - 1 }}, currentQuestion + 1)" 
                            x-show="currentQuestion < {{ count($quiz->questions ?? []) - 1 }}" 
                            class="btn-primary w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3.5 sm:py-3 rounded-xl font-bold shadow-md shadow-primary-500/20">
                            <span class="text-sm">{{ __('Next Question') }}</span>
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        
                        <button type="button" @click="$refs.submitModal.showModal()"
                            x-show="currentQuestion === {{ count($quiz->questions ?? []) - 1 }}" 
                            class="btn-primary w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3.5 sm:py-3 rounded-xl font-bold bg-slate-900 border-slate-900 hover:bg-slate-800 dark:bg-white dark:border-white dark:text-slate-900 dark:hover:bg-slate-200 shadow-md" 
                            :disabled="loading">
                            <span x-show="!loading" class="flex items-center gap-2 text-sm">
                                {{ __('Submit Quiz') }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span x-show="loading" x-cloak class="flex items-center gap-2 text-sm">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit Confirmation Modal --}}
            <dialog x-ref="submitModal" class="overflow-visible rounded-3xl p-0 backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm bg-white dark:bg-slate-900 shadow-2xl w-full max-w-sm mx-auto z-50 animate-zoom-in">
                <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 relative z-10 m-2">
                    <div class="w-20 h-20 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-500 fill-primary-500 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor">
                           <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold mb-3">{{ __('Are you sure?') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 font-medium">
                        {{ __('You answered') }} <strong class="text-slate-900 dark:text-white" x-text="Object.keys(answers).length"></strong> {{ __('out of') }} {{ count($quiz->questions ?? []) }}.
                    </p>
                    
                    <div x-show="Object.keys(answers).length < {{ count($quiz->questions ?? []) }}" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm font-bold p-3 rounded-xl mb-6">
                        ⚠️ {{ __('You have unanswered questions!') }}
                    </div>
                    
                    <div class="flex gap-3 mt-8">
                        <button type="button" @click="$refs.submitModal.close()" class="btn-secondary flex-1 py-3 rounded-xl font-bold bg-slate-100 border-none">{{ __('Review') }}</button>
                        <button type="button" @click="$refs.submitModal.close(); submitQuiz()" class="btn-primary flex-1 py-3 rounded-xl font-bold">{{ __('Submit') }}</button>
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