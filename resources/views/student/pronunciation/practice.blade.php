@extends('layouts.app')

@section('title', __('Pronunciation Practice') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden" x-data="pronunciationApp()">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header --}}
        <div class="mb-10 text-center" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold mb-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                🎤 Speech Exercise
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2" style="color: var(--color-text);">Pronunciation Practice</h1>
            <p class="text-base" style="color: var(--color-text-muted);">{{ $exercise->lesson->title ?? 'Practice your speaking' }}</p>
        </div>

        {{-- Browser Support Warning --}}
        <div x-show="!isSupported" x-cloak class="mb-8 p-5 rounded-2xl text-center" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2);">
            <div class="text-3xl mb-2">⚠️</div>
            <h3 class="font-bold text-base mb-1 text-amber-400">{{ __('Browser Not Supported') }}</h3>
            <p class="text-sm text-amber-300/70">
                Speech recognition requires <strong>Google Chrome</strong> or <strong>Microsoft Edge</strong>.
            </p>
        </div>

        {{-- Vocabulary Table --}}
        @php
            try { $vocabList = is_array($exercise->vocabulary_json) ? $exercise->vocabulary_json : []; } catch(\Throwable $e) { $vocabList = []; }
        @endphp
        @if(!empty($vocabList))
        <div class="mb-10 rounded-2xl overflow-hidden" data-aos="fade-up">
            <div class="px-6 py-4 flex items-center gap-3" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border);">
                <span class="text-xl">📚</span>
                <span class="text-base font-bold" style="color: var(--color-text);">كلمات الدرس — Lesson Vocabulary</span>
                <span class="ml-auto text-xs px-2 py-1 rounded-lg" style="background: rgba(var(--color-primary-rgb,139,92,246),0.1); color: var(--color-primary,#a78bfa);">{{ count($vocabList) }} كلمات</span>
            </div>
            <div class="overflow-x-auto" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-top: none;">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background: rgba(var(--color-primary-rgb,139,92,246),0.08); border-bottom: 1px solid var(--glass-border);">
                            <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">#</th>
                            <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">الكلمة</th>
                            <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">النطق</th>
                            <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">المعنى</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vocabList as $i => $vocab)
                        <tr class="border-b" style="border-color: var(--glass-border);">
                            <td class="px-4 py-3 text-xs font-bold" style="color: var(--color-text-muted);">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-base" style="color: var(--color-text);">{{ $vocab['word'] ?? '' }}</td>
                            <td class="px-4 py-3" style="color: var(--color-primary,#a78bfa); font-family: monospace;">{{ $vocab['pronunciation'] ?? '' }}</td>
                            <td class="px-4 py-3 font-semibold" style="color: var(--color-text-muted); direction: rtl;">{{ $vocab['meaning_ar'] ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Sentence Cards --}}
        @php
            try {
                $explanations = [
                    1 => $exercise->passage_explanation ?? null,
                    2 => $exercise->sentence_explanation ?? null,
                ];
            } catch(\Throwable $e) {
                $explanations = [1 => null, 2 => null];
            }
        @endphp
        @foreach($exercise->sentences as $num => $sentence)
            <div class="mb-8 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="{{ ($num - 1) * 100 }}"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">

                {{-- Card Header --}}
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom: 1px solid var(--glass-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold"
                          style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.15); color: var(--color-primary, #a78bfa);">{{ $num }}</span>
                    <span class="text-sm font-semibold" style="color: var(--color-text);">Sentence {{ $num }}</span>
                    <span class="ml-auto text-xs px-2 py-1 rounded-lg" style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.1); color: var(--color-primary, #a78bfa);">
                        Say it aloud
                    </span>
                </div>

                {{-- Sentence Display --}}
                <div class="px-6 pt-6 pb-4">
                    <div class="p-5 rounded-xl text-center mb-4" style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.06); border: 1px solid rgba(var(--color-primary-rgb, 139, 92, 246), 0.12);">
                        <p class="text-2xl md:text-3xl font-bold leading-relaxed" style="color: var(--color-text);">
                            "{{ $sentence }}"
                        </p>
                    </div>

                    @if(isset($exercise->reference_audio_urls[$num]))
                        <div class="flex justify-center mb-4">
                            <button type="button" @click="playAudio('{{ $exercise->reference_audio_urls[$num] }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all hover:scale-105"
                                    style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                                🔊 Listen to Example
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Recording Area --}}
                <div class="px-6 pb-6">
                    <div class="flex flex-col items-center gap-4 py-6 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        {{-- Record Button --}}
                        <button type="button" @click="toggleRecording({{ $num }})"
                                :disabled="!isSupported || (isRecording && activeSentence !== {{ $num }})"
                                class="w-20 h-20 rounded-full flex items-center justify-center transition-all duration-300 disabled:opacity-40"
                                :class="isRecording && activeSentence === {{ $num }}
                                    ? 'bg-red-500 scale-110 animate-pulse shadow-lg shadow-red-500/30'
                                    : 'bg-gradient-to-br from-primary-500 to-accent-500 hover:scale-105 shadow-lg shadow-primary-500/30'">
                            <svg x-show="!(isRecording && activeSentence === {{ $num }})" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <svg x-show="isRecording && activeSentence === {{ $num }}" x-cloak class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                        </button>

                        <p class="text-sm font-medium"
                           :style="isRecording && activeSentence === {{ $num }} ? 'color: #ef4444' : 'color: var(--color-text-muted)'">
                            <span x-show="!(isRecording && activeSentence === {{ $num }})">{{ __('Tap to start speaking') }}</span>
                            <span x-show="isRecording && activeSentence === {{ $num }}" x-cloak>🎙️ Listening... {{ __('Tap to stop') }}</span>
                        </p>

                        {{-- Live Transcript --}}
                        <div x-show="activeSentence === {{ $num }} && liveTranscript" x-cloak
                             class="mx-4 w-[calc(100%-2rem)] p-3 rounded-xl text-center text-sm italic"
                             style="background: rgba(0,0,0,0.2); color: var(--color-text-muted); border: 1px solid var(--glass-border);">
                            "<span x-text="liveTranscript"></span>"
                        </div>

                        {{-- Loading --}}
                        <div x-show="isEvaluating && activeSentence === {{ $num }}" x-cloak class="flex items-center gap-2">
                            <div class="w-4 h-4 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-sm" style="color: var(--color-text-muted);">{{ __('Evaluating') }}...</span>
                        </div>
                    </div>

                    {{-- Result Card --}}
                    <div x-show="results[{{ $num }}]" x-cloak x-transition class="mt-4 p-5 rounded-xl" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border);">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            {{-- Score Circle --}}
                            <div class="relative w-24 h-24 shrink-0">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent" style="stroke: rgba(255,255,255,0.1);"/>
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent"
                                        :stroke-dasharray="2 * 3.14159 * 40"
                                        :stroke-dashoffset="2 * 3.14159 * 40 * (1 - (results[{{ $num }}]?.score || 0) / 100)"
                                        :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-400' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-400' : 'text-red-400'"
                                        stroke="currentColor" stroke-linecap="round"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-2xl font-black text-white" x-text="Math.round(results[{{ $num }}]?.score || 0) + '%'"></span>
                                </div>
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 text-center md:text-left">
                                <p class="font-bold text-lg mb-1"
                                   :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-400' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-400' : 'text-red-400'"
                                   x-text="(results[{{ $num }}]?.score || 0) >= 90 ? 'Excellent! 🌟' : (results[{{ $num }}]?.score || 0) >= 70 ? 'Great job! 👏' : (results[{{ $num }}]?.score || 0) >= 50 ? 'Good try! 💪' : 'Keep practicing! 🎯'"></p>
                                <p class="text-sm mb-3" style="color: var(--color-text-muted);" x-text="results[{{ $num }}]?.feedback || ''"></p>

                                {{-- Score Bars --}}
                                <div class="space-y-2">
                                    <template x-for="(label, key) in {pronunciation: 'Accuracy', clarity: 'Clarity', fluency: 'Fluency'}">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-medium w-16 text-gray-400" x-text="label"></span>
                                            <div class="flex-1 h-2 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.08);">
                                                <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-1000"
                                                     :style="'width: ' + (results[{{ $num }}]?.[key] || 0) + '%'"></div>
                                            </div>
                                            <span class="text-xs font-bold w-8 text-white" x-text="(results[{{ $num }}]?.[key] || 0) + '%'"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- What you said --}}
                                <div class="mt-3 text-xs text-gray-500">
                                    You said: "<span class="text-gray-400" x-text="results[{{ $num }}]?.transcript || ''"></span>"
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Explanation Card (shown after passing) --}}
                    @if(isset($explanations[$num]) && $explanations[$num])
                    <div x-show="results[{{ $num }}] && (results[{{ $num }}]?.score || 0) >= passingScore" x-cloak x-transition
                         class="mt-4 p-5 rounded-xl" style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2);">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background: rgba(16,185,129,0.12);">💡</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm mb-2 text-emerald-400">شرح وتوضيح</h4>
                                <p class="text-sm leading-relaxed" style="color: var(--color-text-muted); direction: rtl; text-align: right;">
                                    {{ $explanations[$num] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Correct Pronunciation Card (after 2 failed attempts) --}}
                    <div x-show="failedAttempts[{{ $num }}] >= 2" x-cloak x-transition
                         class="mt-4 p-5 rounded-xl" style="background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(245, 158, 11, 0.1);">
                                <span class="text-xl">🔊</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm mb-1 text-amber-400">{{ __('Listen to the correct pronunciation') }}</h4>
                                <p class="text-xs mb-3 text-amber-300/60">
                                    You've had 2 attempts. Listen carefully and try again!
                                </p>
                                <button type="button" @click="speakCorrect({{ $num }}, '{{ addslashes($sentence) }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:scale-105"
                                        style="background: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                    </svg>
                                    <span x-text="isSpeaking && speakingSentence === {{ $num }} ? 'Speaking...' : 'Hear Correct Pronunciation'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Previous Attempts --}}
                    @if(isset($attempts[$num]) && $attempts[$num]->count() > 0)
                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500 px-1">
                            <span>Previous best:</span>
                            <span class="font-bold text-primary-400">{{ $attempts[$num]->max('overall_score') }}%</span>
                            <span>·</span>
                            <span>{{ $attempts[$num]->count() }} attempts</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Navigation --}}
        <div class="flex flex-wrap justify-center gap-3 mt-2" data-aos="fade-up">
            @if(isset($exercise->lesson))
                <a href="{{ route('student.lessons.show', [$exercise->lesson->course, $exercise->lesson]) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105"
                   style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                    ← Back to Lesson
                </a>
            @endif
            <a href="{{ route('student.pronunciation.my-attempts') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105"
               style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                📊 My Attempts
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function pronunciationApp() {
    return {
        isSupported: 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window,
        isRecording: false,
        isEvaluating: false,
        isSpeaking: false,
        speakingSentence: null,
        activeSentence: null,
        liveTranscript: '',
        results: {},
        failedAttempts: {},
        recognition: null,
        passingScore: {{ $exercise->passing_score ?? 70 }},

        sentences: {
            @foreach($exercise->sentences as $num => $sentence)
                {{ $num }}: @json($sentence),
            @endforeach
        },

        initRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) return null;

            const recognition = new SpeechRecognition();
            recognition.lang = 'en-US';
            recognition.interimResults = true;
            recognition.continuous = false;
            recognition.maxAlternatives = 1;

            recognition.onresult = (event) => {
                let finalTranscript = '';
                let interimTranscript = '';

                for (let i = event.resultIndex; i < event.results.length; i++) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    } else {
                        interimTranscript += event.results[i][0].transcript;
                    }
                }

                this.liveTranscript = finalTranscript || interimTranscript;
            };

            recognition.onend = () => {
                if (this.isRecording) {
                    this.isRecording = false;
                    if (this.liveTranscript.trim()) {
                        this.submitTranscript(this.activeSentence, this.liveTranscript.trim());
                    }
                }
            };

            recognition.onerror = (event) => {
                this.isRecording = false;
                if (event.error === 'no-speech') {
                    if (window.showNotification) window.showNotification('No speech detected. Please try again.', 'warning');
                } else if (event.error === 'not-allowed') {
                    if (window.showNotification) window.showNotification('Microphone access denied. Please enable it in your browser settings.', 'error');
                } else {
                    if (window.showNotification) window.showNotification('Speech recognition error: ' + event.error, 'error');
                }
            };

            return recognition;
        },

        toggleRecording(sentenceNumber) {
            if (this.isRecording && this.activeSentence === sentenceNumber) {
                this.stopRecording();
            } else {
                this.startRecording(sentenceNumber);
            }
        },

        startRecording(sentenceNumber) {
            if (this.isRecording) this.stopRecording();
            if (this.isSpeaking) window.speechSynthesis.cancel();

            this.recognition = this.initRecognition();
            if (!this.recognition) return;

            this.activeSentence = sentenceNumber;
            this.liveTranscript = '';
            this.isRecording = true;

            try {
                this.recognition.start();
            } catch(e) {
                this.isRecording = false;
                if (window.showNotification) window.showNotification('Could not start recognition. Please try again.', 'error');
            }
        },

        stopRecording() {
            if (this.recognition) {
                this.recognition.stop();
            }
            this.isRecording = false;
        },

        async submitTranscript(sentenceNumber, transcript) {
            this.isEvaluating = true;

            try {
                const res = await fetch(`/student/pronunciation/{{ $exercise->id }}/evaluate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        transcript: transcript,
                        sentence_number: sentenceNumber,
                    }),
                });

                const data = await res.json();

                if (data.success) {
                    this.results[sentenceNumber] = data;

                    if (data.score >= this.passingScore) {
                        this.failedAttempts[sentenceNumber] = 0;
                        if (window.showNotification) window.showNotification('Great pronunciation! Score: ' + data.score + '%', 'success');
                    } else {
                        if (!this.failedAttempts[sentenceNumber]) {
                            this.failedAttempts[sentenceNumber] = 0;
                        }
                        this.failedAttempts[sentenceNumber]++;

                        if (this.failedAttempts[sentenceNumber] >= 2) {
                            if (window.showNotification) window.showNotification('Listen to the correct pronunciation below! 🔊', 'info');
                            setTimeout(() => {
                                this.speakCorrect(sentenceNumber, this.sentences[sentenceNumber]);
                            }, 1500);
                        } else {
                            if (window.showNotification) window.showNotification('Try again! 1 more attempt before hearing the correct pronunciation.', 'warning');
                        }
                    }
                } else {
                    if (window.showNotification) window.showNotification(data.error || 'Evaluation failed.', 'error');
                }
            } catch {
                if (window.showNotification) window.showNotification('Network error. Please try again.', 'error');
            }

            this.isEvaluating = false;
        },

        speakCorrect(sentenceNumber, text) {
            if (!('speechSynthesis' in window)) {
                if (window.showNotification) window.showNotification('Text-to-speech is not supported in this browser.', 'error');
                return;
            }

            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            utterance.pitch = 1;
            utterance.volume = 1;

            const voices = window.speechSynthesis.getVoices();
            const englishVoice = voices.find(v => v.lang === 'en-US' && v.name.includes('Google'))
                || voices.find(v => v.lang === 'en-US' && v.name.includes('Microsoft'))
                || voices.find(v => v.lang === 'en-US')
                || voices.find(v => v.lang.startsWith('en'));
            if (englishVoice) utterance.voice = englishVoice;

            this.isSpeaking = true;
            this.speakingSentence = sentenceNumber;

            utterance.onend = () => {
                this.isSpeaking = false;
                this.speakingSentence = null;
            };

            utterance.onerror = () => {
                this.isSpeaking = false;
                this.speakingSentence = null;
            };

            window.speechSynthesis.speak(utterance);
        },

        playAudio(url) {
            new Audio(url).play();
        }
    };
}

if ('speechSynthesis' in window) {
    window.speechSynthesis.getVoices();
    window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
}
</script>
@endpush
@endsection
