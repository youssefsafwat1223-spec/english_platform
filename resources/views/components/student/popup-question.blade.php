<div x-data="popupQuestionManager()" x-init="initTimer()" x-cloak>
    {{-- Audio file for popup notification --}}
    <audio id="popup-audio" src="{{ asset('sounds/popup.mp3') }}" preload="auto"></audio>

    {{-- Modal Overlay --}}
    <div x-show="showModal"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 transition-opacity duration-300"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-white dark:bg-slate-900 rounded-[1.5rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 pointer-events-auto"
            @click.away="!isSubmitting && !showResult && shakeModal()" x-ref="modalBox"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            {{-- Header --}}
            <div
                class="px-6 py-4 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-primary-50 to-primary-100/50 dark:from-primary-500/10 dark:to-accent-500/10 flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white">
                        {{ app()->getLocale() === 'ar' ? 'سؤال سريع!' : 'Quick Question!' }}</h3>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ app()->getLocale() === 'ar' ? 'نشّط ذهنك قبل ما تكمل.' : 'Refresh your mind before continuing.' }}
                    </p>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{-- Loading State --}}
                <div x-show="isLoading" class="flex flex-col items-center justify-center py-6 gap-3">
                    <svg class="w-8 h-8 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span
                        class="text-sm font-bold text-slate-500">{{ app()->getLocale() === 'ar' ? 'جاري تحميل السؤال...' : 'Loading question...' }}</span>
                </div>

                {{-- Question Content --}}
                <div x-show="!isLoading && questionText">
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-6 leading-relaxed"
                        x-text="questionText"></p>

                    <div class="space-y-3">
                        <template x-for="(option, index) in options" :key="index">
                            <label
                                class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                :class="{
                                       'border-primary-500 bg-primary-50 dark:bg-primary-500/10': selectedAnswer === option && !showResult,
                                       'border-slate-200 dark:border-white/10 hover:border-primary-200 dark:hover:border-white/20': selectedAnswer !== option && !showResult,
                                       'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10': showResult && resultCorrect && selectedAnswer === option,
                                       'border-rose-500 bg-rose-50 dark:bg-rose-500/10': showResult && !resultCorrect && selectedAnswer === option,
                                       'border-emerald-500/50 bg-emerald-50/50 dark:bg-emerald-500/5': showResult && (!resultCorrect && option === correctOptionText),
                                       'pointer-events-none opacity-60': isSubmitting || showResult
                                   }">
                                <div class="relative flex items-center justify-center shrink-0 w-5 h-5 rounded-full border-2 transition-colors"
                                    :class="selectedAnswer === option ? 'border-primary-500' : 'border-slate-300 dark:border-slate-600'">
                                    <div class="w-2.5 h-2.5 rounded-full bg-primary-500 transition-transform duration-200"
                                        :class="selectedAnswer === option ? 'scale-100' : 'scale-0'"></div>
                                </div>
                                <input type="radio" x-model="selectedAnswer" :value="option" class="hidden"
                                    :disabled="isSubmitting || showResult">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300"
                                    x-text="option"></span>

                                {{-- Icon for result feedback --}}
                                <div class="ml-auto flex items-center" x-show="showResult">
                                    <svg x-show="resultCorrect && selectedAnswer === option"
                                        class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <svg x-show="!resultCorrect && selectedAnswer === option"
                                        class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <svg x-show="!resultCorrect && option === correctOptionText"
                                        class="w-5 h-5 text-emerald-500/70" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </label>
                        </template>
                    </div>

                    {{-- Feedback Message --}}
                    <div x-show="showResult" x-collapse class="mt-4 p-4 rounded-xl flex items-start gap-3"
                        :class="resultCorrect ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'">
                        <svg x-show="resultCorrect" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg x-show="!resultCorrect" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm font-bold">
                            <p
                                x-text="resultCorrect ? '{{ app()->getLocale() === 'ar' ? 'إجابة صحيحة! أحسنت.' : 'Correct answer! Great job.' }}' : '{{ app()->getLocale() === 'ar' ? 'إجابة خاطئة. حاول التركيز أكثر.' : 'Incorrect answer. Try to focus more!' }}'">
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-6">
                    <button type="button" x-show="!showResult" @click="submitAnswer"
                        :disabled="!selectedAnswer || isSubmitting || isLoading || !questionText"
                        class="btn-primary ripple-btn w-full justify-center disabled:opacity-50 transition-all font-bold group">
                        <span
                            x-show="!isSubmitting">{{ app()->getLocale() === 'ar' ? 'تأكيد الإجابة' : 'Submit Answer' }}</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            {{ app()->getLocale() === 'ar' ? 'جاري التحقق...' : 'Checking...' }}
                        </span>
                    </button>
                    <button type="button" x-show="showResult" @click="closeModal"
                        class="btn-primary ripple-btn w-full justify-center transition-all font-bold shadow-md hover:shadow-lg">
                        {{ app()->getLocale() === 'ar' ? 'متابعة الدرس' : 'Continue Lesson' }}
                        <svg class="w-4 h-4 mr-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function popupQuestionManager() {
        return {
            showModal: false,
            isLoading: false,
            isSubmitting: false,
            showResult: false,

            questionId: null,
            questionText: '',
            options: [],

            selectedAnswer: null,
            resultCorrect: false,
            correctOptionText: '',

            timer: null,
            // Wait exactly 3 minutes (180,000 ms) before popping up the question
            intervalMs: 180000,

            initTimer() {
                // Start the countdown
                this.timer = setTimeout(() => {
                    this.fetchQuestion();
                }, this.intervalMs);
            },

            fetchQuestion() {
                this.isLoading = true;
                this.showModal = true;

                // Force exit full screen if active so the modal is visible
                if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen().catch(() => {});
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }

                // Play popup audio
                const audio = document.getElementById('popup-audio');
                if (audio) {
                    audio.play().catch(e => console.log('Audio autoplay blocked', e));
                }

                // Try pausing media on page (HTML5 Video)
                const video = document.getElementById('lessonVideo');
                if (video && !video.paused) {
                    video.pause();
                }

                // Try pausing VdoCipher iframe
                const vdoIframe = document.getElementById('vdo-iframe');
                if (vdoIframe && vdoIframe.contentWindow) {
                    // Post standard HTML5/VdoCipher pause messages
                    vdoIframe.contentWindow.postMessage('{"type":"pause"}', '*');
                    vdoIframe.contentWindow.postMessage('{"event":"command", "func":"pauseVideo"}', '*');
                }

                axios.get('{{ route("student.popup-question.random") }}')
                    .then(response => {
                        if (response.data.success) {
                            this.questionId = response.data.id;
                            this.questionText = response.data.text;
                            this.options = response.data.options;
                            this.isLoading = false;
                        } else {
                            // Failed to fetch or no questions available
                            this.closeModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching popup question', error);
                        this.closeModal();
                    });
            },

            submitAnswer() {
                if (!this.selectedAnswer || this.isSubmitting) return;

                this.isSubmitting = true;

                axios.post('{{ url("/student/popup-question/") }}/' + this.questionId + '/check', {
                    answer: this.selectedAnswer
                })
                    .then(response => {
                        if (response.data.success) {
                            this.resultCorrect = response.data.is_correct;
                            this.correctOptionText = response.data.correct_option_text;
                            this.showResult = true;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        if (window.showNotification) {
                            window.showNotification('{{ app()->getLocale() === "ar" ? "حدث خطأ غير متوقع." : "An unexpected error occurred." }}', 'error');
                        }
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
            },

            closeModal() {
                this.showModal = false;

                // Reset for the next time
                this.showResult = false;
                this.selectedAnswer = null;
                this.questionId = null;
                this.questionText = '';
                this.options = [];

                // Restart timer for next 3 minutes
                this.initTimer();
            },

            shakeModal() {
                // A visual cue that they must answer before closing
                const box = this.$refs.modalBox;
                box.classList.add('animate-shake');
                setTimeout(() => {
                    box.classList.remove('animate-shake');
                }, 500);

                if (window.showNotification) {
                    window.showNotification('{{ app()->getLocale() === "ar" ? "يجب الإجابة على السؤال أولاً!" : "You must answer the question first!" }}', 'warning');
                }
            }
        }
    }
</script>

<style>
    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        10%,
        30%,
        50%,
        70%,
        90% {
            transform: translateX(-5px);
        }

        20%,
        40%,
        60%,
        80% {
            transform: translateX(5px);
        }
    }

    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
    }
</style>