@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $questionCount = count($quiz->questions ?? []);
    $audioEnabled = (bool) $quiz->enable_audio;
    $audioAutoPlay = $audioEnabled && (bool) $quiz->audio_auto_play;
    $questionSpeechTexts = $quiz->questions->map(fn ($question) => $question->getTTSText())->values();
@endphp

@section('title', ($isArabic ? 'حل الاختبار' : 'Take Quiz') . ': ' . $quiz->title . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#020617] pb-28 md:pb-16" x-data="quizController()" x-init="initQuiz()">
    <div class="student-container max-w-5xl pt-8 lg:pt-12">

        {{-- Sticky Quiz Top Bar --}}
        <div class="sticky top-3 z-40">
            <div class="rounded-2xl border border-slate-200/80 dark:border-white/10 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl shadow-lg px-5 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-black uppercase tracking-widest text-slate-400">{{ $isArabic ? 'اختبار' : 'Quiz' }}</div>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white truncate">{{ $quiz->title }}</h1>
                        <div class="text-sm text-slate-500 dark:text-slate-400 font-semibold mt-1">
                            {{ $isArabic ? 'السؤال' : 'Question' }} <span x-text="currentQuestion + 1"></span>
                            {{ $isArabic ? 'من' : 'of' }} {{ $questionCount }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 rounded-full border border-slate-200 dark:border-white/10 px-3 py-2 text-xs font-black text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/60">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $isArabic ? 'الوقت المستغرق' : 'Elapsed' }}:</span>
                            <span x-text="formatTime(elapsedSeconds)"></span>
                        </div>
                        <button type="button" @click="$refs.submitModal.showModal()" class="btn-secondary btn-sm">
                            {{ $isArabic ? 'إنهاء مبكر' : 'Finish Early' }}
                        </button>
                    </div>
                </div>
                <div class="mt-4 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-300 ease-out" :style="`width: ${((currentQuestion + 1) / {{ $questionCount }}) * 100}%`"></div>
                </div>
            </div>
            <div class="sm:hidden mt-2 inline-flex items-center gap-1.5 rounded-full border border-slate-200 dark:border-white/10 px-3 py-1.5 text-xs font-black text-slate-600 dark:text-slate-300 bg-white/80 dark:bg-slate-900/70 backdrop-blur">
                <svg class="w-3.5 h-3.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="formatTime(elapsedSeconds)"></span>
            </div>
        </div>

        @if(session('error') || $errors->any())
            <div class="mt-6 mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h3 class="font-bold">{{ $isArabic ? 'يرجى مراجعة الأخطاء التالية:' : 'Please review the following errors:' }}</h3>
                        <ul class="list-disc list-inside text-sm opacity-90 mt-1">
                            @if(session('error'))<li>{{ session('error') }}</li>@endif
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" @submit.prevent="submitQuiz" x-ref="quizForm">
            @csrf
            <input type="hidden" name="started_at" x-model="startedAt">
            <input type="hidden" name="completed_at" x-model="completedAt">
            <input type="hidden" name="time_taken" x-model="elapsedSeconds">

            @foreach($quiz->questions as $qIndex => $question)
                <div x-show="currentQuestion === {{ $qIndex }}" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8">
                    <x-student.card padding="p-6 md:p-8" rounded="rounded-3xl" class="border border-slate-200/80 dark:border-white/10">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-3 py-1.5 rounded-full bg-primary-500/10 text-primary-600 dark:text-primary-400 text-xs font-black">
                                {{ $isArabic ? 'السؤال' : 'Question' }} {{ $qIndex + 1 }}
                            </span>
                            @if($question->question_type === 'drag_drop')
                                <span class="px-3 py-1.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-black">
                                    {{ $isArabic ? 'تطابق' : 'Matching' }}
                                </span>
                            @endif
                        </div>

                        @if($audioEnabled)
                            <div class="mb-8 mx-auto max-w-xl space-y-3">
                                @if($question->audio_url)
                                    <div class="rounded-[1.25rem] p-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-inner">
                                        <audio id="audio-{{ $qIndex }}" controls preload="none" class="w-full h-12 custom-audio-player">
                                            <source src="{{ $question->audio_url }}" type="audio/mpeg">
                                        </audio>
                                    </div>
                                @endif
                                <div class="flex justify-center">
                                    <button type="button" @click="playQuestionAudio({{ $qIndex }})" class="inline-flex items-center gap-2 rounded-full border border-primary-500/30 bg-primary-500/10 px-5 py-2.5 text-sm font-bold text-primary-600 hover:bg-primary-500/20 dark:text-primary-300">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.5 5.5A1.5 1.5 0 0 1 9 6.7v6.6a1.5 1.5 0 0 1-2.5 1.2L3 11.4a1.5 1.5 0 0 1 0-2.8l3.5-3.1Zm6.22-.72a.75.75 0 0 1 1.06 0 7.25 7.25 0 0 1 0 10.25.75.75 0 1 1-1.06-1.06 5.75 5.75 0 0 0 0-8.13.75.75 0 0 1 0-1.06Zm-2.12 2.12a.75.75 0 0 1 1.06 0 4.25 4.25 0 0 1 0 6.01.75.75 0 1 1-1.06-1.06 2.75 2.75 0 0 0 0-3.89.75.75 0 0 1 0-1.06Z"/></svg>
                                        <span>{{ $isArabic ? 'استمع للسؤال' : 'Listen to the question' }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <h2 class="text-xl md:text-2xl font-extrabold text-center mb-8 leading-relaxed text-slate-900 dark:text-white">
                            {{ $question->text ?? $question->question_text }}
                        </h2>

                        <div class="space-y-4 max-w-3xl mx-auto">
                            <input type="hidden" name="answers[{{ $qIndex }}][question_id]" value="{{ $question->id }}">
                            <input type="hidden" name="answers[{{ $qIndex }}][audio_played]" :value="audioStats[{{ $qIndex }}]?.played ? 1 : 0">
                            <input type="hidden" name="answers[{{ $qIndex }}][audio_replay_count]" :value="audioStats[{{ $qIndex }}]?.replays ?? 0">

                            @if($question->question_type === 'drag_drop' && $question->matching_pairs)
                                <div x-data="matchingQuestion{{ $qIndex }}()" class="space-y-8">
                                    <input type="hidden" name="answers[{{ $qIndex }}][user_answer]" :value="getAnswerJSON()">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400">{{ $isArabic ? 'العناصر' : 'Items' }}</h4>
                                            <template x-for="(item, idx) in leftItems" :key="'left-'+idx">
                                                <div class="flex items-center gap-3 p-4 rounded-2xl border-2 transition-all cursor-pointer" :class="selectedLeft === idx ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 shadow-sm scale-[1.01]' : (matches[idx] !== null ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/10' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600')" @click="selectLeft(idx)">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-colors" :class="matches[idx] !== null ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-slate-700 text-slate-500 shadow-sm'"><span x-text="idx + 1"></span></div>
                                                    <span class="font-bold text-lg flex-1" x-text="item"></span>
                                                    <template x-if="matches[idx] !== null">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs font-black text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1 rounded-full truncate max-w-[120px]" x-text="rightItems[matches[idx]]"></span>
                                                            <button type="button" @click.stop="clearMatch(idx)" class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 hover:bg-rose-200 flex items-center justify-center transition-colors" :aria-label="@js($isArabic ? 'إلغاء الربط' : 'Clear match')">
                                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-400">{{ $isArabic ? 'المطابقات' : 'Matches' }}</h4>
                                            <template x-for="(item, idx) in rightItems" :key="'right-'+idx">
                                                <div class="flex items-center gap-3 p-4 rounded-2xl border-2 transition-all cursor-pointer" :class="isRightUsed(idx) ? 'border-emerald-200 dark:border-emerald-800/30 bg-slate-50 dark:bg-slate-900 opacity-50' : (selectedLeft !== null ? 'border-primary-300 dark:border-primary-700 bg-white dark:bg-slate-800 shadow-md animate-pulse-subtle' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600')" @click="matchRight(idx)">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-sm" :class="isRightUsed(idx) ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300'"><span x-text="String.fromCharCode(65 + idx)"></span></div>
                                                    <span class="font-bold text-lg flex-1" x-text="item"></span>
                                                    <template x-if="isRightUsed(idx)"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach($question->options as $oIndex => $option)
                                    @php $displayOption = (string) $option; @endphp
                                    @if(strlen(trim($displayOption)) > 0)
                                        <label class="group flex items-center p-4 md:p-5 rounded-2xl cursor-pointer transition-all duration-200 border-2" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-900/20' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-600'">
                                            <input type="radio" name="answers[{{ $qIndex }}][user_answer]" value="{{ $oIndex }}" class="sr-only" x-model="answers[{{ $qIndex }}]">
                                            <div class="shrink-0 mr-4 md:mr-6 flex items-center justify-center w-6 h-6 rounded-full border-2 transition-colors" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'border-primary-500' : 'border-slate-300 dark:border-slate-600 group-hover:border-slate-400'"><div class="w-2.5 h-2.5 rounded-full bg-primary-500 transition-transform transform" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'scale-100' : 'scale-0'"></div></div>
                                            <div class="hidden md:flex shrink-0 w-8 h-8 rounded-full items-center justify-center font-bold text-xs mr-4 transition-colors" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400' : 'bg-white dark:bg-slate-700 shadow-sm text-slate-500'">{{ $oIndex }}</div>
                                            <span class="font-bold text-lg transition-colors flex-1" :class="answers[{{ $qIndex }}] === '{{ $oIndex }}' ? 'text-primary-700 dark:text-primary-400' : 'text-slate-700 dark:text-slate-300'">{{ $displayOption }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </x-student.card>
                </div>
            @endforeach

            {{-- Bottom Navigation --}}
            <div class="fixed bottom-0 md:bottom-6 left-0 right-0 z-40 px-0 md:px-6 pointer-events-none">
                <div class="student-container max-w-5xl pointer-events-auto bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-t md:border border-slate-200 dark:border-slate-800 rounded-t-[2rem] md:rounded-[2rem] shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] p-4 sm:p-5 flex items-center justify-between gap-4">
                    <button type="button" @click="currentQuestion = Math.max(0, currentQuestion - 1)" class="btn-secondary flex-1 sm:flex-none flex justify-center items-center gap-2 px-6 py-3.5 sm:py-3 rounded-xl font-bold transition-all disabled:opacity-30 disabled:cursor-not-allowed bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-950 border border-slate-200 dark:border-slate-700" :disabled="currentQuestion === 0">
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="hidden sm:inline text-sm">{{ $isArabic ? 'السابق' : 'Previous' }}</span>
                    </button>
                    <div class="hidden md:flex flex-wrap items-center justify-center gap-1.5 flex-1 p-2">
                        @foreach($quiz->questions as $i => $q)
                            <button type="button" @click="currentQuestion = {{ $i }}" class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none" :class="{'bg-primary-500 scale-125 ring-4 ring-primary-500/20': currentQuestion === {{ $i }},'bg-slate-800 dark:bg-slate-300': currentQuestion !== {{ $i }} && answers[{{ $i }}],'bg-slate-200 dark:bg-slate-700': currentQuestion !== {{ $i }} && !answers[{{ $i }}]}" title="{{ $isArabic ? 'السؤال' : 'Question' }} {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="flex-1 sm:flex-none flex justify-end">
                        <button type="button" @click="currentQuestion = Math.min({{ $questionCount - 1 }}, currentQuestion + 1)" x-show="currentQuestion < {{ $questionCount - 1 }}" class="btn-primary w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3.5 sm:py-3 rounded-xl font-bold shadow-md shadow-primary-500/20">
                            <span class="text-sm">{{ $isArabic ? 'السؤال التالي' : 'Next Question' }}</span>
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="button" @click="$refs.submitModal.showModal()" x-show="currentQuestion === {{ $questionCount - 1 }}" class="btn-primary w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3.5 sm:py-3 rounded-xl font-bold bg-slate-900 border-slate-900 hover:bg-slate-800 dark:bg-white dark:border-white dark:text-slate-900 dark:hover:bg-slate-200 shadow-md" :disabled="loading">
                            <span x-show="!loading" class="flex items-center gap-2 text-sm">{{ $isArabic ? 'إرسال الاختبار' : 'Submit Quiz' }}<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
                            <span x-show="loading" x-cloak class="flex items-center gap-2 text-sm"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>{{ $isArabic ? 'جاري الإرسال...' : 'Submitting...' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <dialog x-ref="submitModal" class="overflow-visible rounded-3xl p-0 backdrop:bg-slate-900/70 backdrop:backdrop-blur-md bg-transparent shadow-none w-full max-w-sm mx-auto z-50 animate-zoom-in">
                <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-white/10 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.25)] dark:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] relative z-10">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-cyan-500 text-white flex items-center justify-center mx-auto mb-6 shadow-lg shadow-primary-500/30">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-2 text-slate-900 dark:text-white">{{ $isArabic ? 'هل أنت متأكد؟' : 'Are you sure?' }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 font-medium text-sm">{{ $isArabic ? 'أجبت عن' : 'You answered' }} <strong class="text-slate-900 dark:text-white text-base" x-text="Object.keys(answers).length"></strong> {{ $isArabic ? 'من أصل' : 'out of' }} <strong>{{ $questionCount }}</strong>.</p>
                    <div x-show="Object.keys(answers).length < {{ $questionCount }}" class="bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-sm font-bold p-4 rounded-2xl mb-6 border border-amber-200 dark:border-amber-500/20">
                        <span class="inline-flex items-center gap-2"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-7.938 4h15.876c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>{{ $isArabic ? 'لديك أسئلة لم تُجب عنها.' : 'You have unanswered questions!' }}</span></span>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="$refs.submitModal.close()" class="flex-1 py-3.5 px-6 rounded-2xl font-bold text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all active:scale-95">{{ $isArabic ? 'مراجعة' : 'Review' }}</button>
                        <button type="button" @click="$refs.submitModal.close(); submitQuiz()" class="flex-1 py-3.5 px-6 rounded-2xl font-bold text-sm bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:from-primary-500 hover:to-primary-400 transition-all active:scale-95">{{ $isArabic ? 'إرسال' : 'Submit' }}</button>
                    </div>
                </div>
            </dialog>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quizController(){return{currentQuestion:0,answers:{},audioStats:{},loading:false,timeLeft:{{ ($quiz->time_limit ?? 0) * 60 }},elapsedSeconds:0,timerInterval:null,startedAt:new Date().toISOString(),completedAt:'',audioEnabled:@js($audioEnabled),audioAutoPlay:@js($audioAutoPlay),questionSpeechTexts:@json($questionSpeechTexts),initQuiz(){this.startTimer();this.registerAudioTracking();this.$watch('currentQuestion',(val)=>{if(this.audioAutoPlay){this.playQuestionAudio(val);}else{this.stopAllAudio();}});this.$nextTick(()=>{if(this.audioAutoPlay){setTimeout(()=>this.playQuestionAudio(0),500);}});if('speechSynthesis' in window){window.speechSynthesis.getVoices();window.speechSynthesis.onvoiceschanged=()=>window.speechSynthesis.getVoices();}window.addEventListener('keydown',(e)=>{const active=document.activeElement?document.activeElement.tagName.toLowerCase():'';if(['input','textarea','audio','video','select','button'].includes(active)){return;}if(e.key==='ArrowRight'||e.key==='ArrowDown'){if(this.currentQuestion<{{ $questionCount - 1 }}){this.currentQuestion++;e.preventDefault();}}else if(e.key==='ArrowLeft'||e.key==='ArrowUp'){if(this.currentQuestion>0){this.currentQuestion--;e.preventDefault();}}});},registerAudioTracking(){if(!this.audioEnabled){return;}document.querySelectorAll('audio[id^=\"audio-\"]').forEach((audio)=>{const index=Number(audio.id.replace('audio-',''));audio.addEventListener('play',()=>this.markAudioPlayback(index));});},stopAllAudio(){document.querySelectorAll('audio').forEach(audio=>{audio.pause();audio.currentTime=0;});if('speechSynthesis' in window){window.speechSynthesis.cancel();}},markAudioPlayback(index){const current=this.audioStats[index]??{played:false,replays:0};this.audioStats[index]={played:true,replays:current.played?current.replays+1:current.replays};},detectSpeechLanguage(text){return /[\u0600-\u06FF]/.test(text)?'ar-SA':'en-US';},speakQuestionText(index){if(!('speechSynthesis' in window)){return;}const text=this.questionSpeechTexts[index]??'';if(!text.trim()){return;}this.markAudioPlayback(index);const utterance=new SpeechSynthesisUtterance(text);utterance.lang=this.detectSpeechLanguage(text);utterance.rate=0.95;const voices=window.speechSynthesis.getVoices();const preferredVoice=voices.find((voice)=>voice.lang.toLowerCase().startsWith(utterance.lang.toLowerCase().slice(0,2)));if(preferredVoice){utterance.voice=preferredVoice;}window.speechSynthesis.cancel();window.speechSynthesis.speak(utterance);},playQuestionAudio(index){if(!this.audioEnabled){return;}this.stopAllAudio();const audio=document.getElementById(`audio-${index}`);if(audio){audio.play().catch(error=>{console.log('Audio playback fallback triggered:',error);this.speakQuestionText(index);});return;}this.speakQuestionText(index);},startTimer(){this.timerInterval=setInterval(()=>{this.elapsedSeconds++;if(this.timeLeft>0){this.timeLeft--;if(this.timeLeft<=0){clearInterval(this.timerInterval);this.submitQuiz();}}},1000);},submitQuiz(){this.completedAt=new Date().toISOString();this.loading=true;this.$nextTick(()=>{this.$refs.quizForm.submit();});},formatTime(s){if(s<0)return '00:00';const m=Math.floor(s/60);const sec=s%60;return String(m).padStart(2,'0')+':'+String(sec).padStart(2,'0');}}}
@foreach($quiz->questions as $qIndex => $question)
@if($question->question_type === 'drag_drop' && $question->matching_pairs)
function matchingQuestion{{ $qIndex }}(){const pairs=@json($question->matching_pairs);const leftItems=pairs.map(p=>p.left);const rightItems=pairs.map(p=>p.right).sort(()=>Math.random()-0.5);return{leftItems,rightItems,selectedLeft:null,matches:new Array(leftItems.length).fill(null),selectLeft(idx){if(this.matches[idx]!==null){this.selectedLeft=null;return;}this.selectedLeft=idx;},matchRight(rightIdx){if(this.selectedLeft===null||this.isRightUsed(rightIdx))return;this.matches[this.selectedLeft]=rightIdx;this.selectedLeft=null;if(this.allMatched()){const quizCtrl=Alpine.closestDataStack(this.$el).find(d=>d.answers!==undefined);if(quizCtrl){quizCtrl.answers[{{ $qIndex }}]='matched';}}},clearMatch(leftIdx){this.matches[leftIdx]=null;const quizCtrl=Alpine.closestDataStack(this.$el).find(d=>d.answers!==undefined);if(quizCtrl){delete quizCtrl.answers[{{ $qIndex }}];}},isRightUsed(rightIdx){return this.matches.includes(rightIdx);},allMatched(){return this.matches.every(m=>m!==null);},getAnswerJSON(){if(!this.allMatched())return '';return JSON.stringify(this.leftItems.map((left,idx)=>({left:left,right:this.rightItems[this.matches[idx]]})));}}}
@endif
@endforeach
</script>
<style>
@keyframes zoom-in{0%{opacity:0;transform:scale(.95) translateY(10px)}100%{opacity:1;transform:scale(1) translateY(0)}}
.animate-zoom-in{animation:zoom-in .4s cubic-bezier(.16,1,.3,1) forwards}
@keyframes pulse-subtle{0%,100%{opacity:1}50%{opacity:.85}}
.animate-pulse-subtle{animation:pulse-subtle 1.5s ease-in-out infinite}
audio.custom-audio-player::-webkit-media-controls-panel{background-color:transparent!important}
</style>
@endpush
@endsection
