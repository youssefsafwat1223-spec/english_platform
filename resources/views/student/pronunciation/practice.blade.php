@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageTitle = $isArabic ? 'تمرين النطق' : 'Pronunciation Practice';
    $exerciseLabels = [
        1 => $isArabic ? 'الكلمة' : 'Word',
        2 => $isArabic ? 'الجملة' : 'Sentence',
        3 => $isArabic ? 'القطعة' : 'Passage',
    ];
    $exerciseHints = [
        1 => $isArabic ? 'انطق الكلمة بوضوح' : 'Say the word clearly',
        2 => $isArabic ? 'انطق الجملة بصوت واضح' : 'Say the sentence aloud',
        3 => $isArabic ? 'اقرأ القطعة بهدوء' : 'Read the passage naturally',
    ];
    $exerciseTextStyles = [
        1 => 'text-center text-4xl md:text-5xl tracking-wide',
        2 => 'text-center text-2xl md:text-3xl',
        3 => 'text-left text-lg md:text-xl',
    ];
    $messages = [
        'ios_title' => $isArabic ? 'وضع iPhone' : 'iPhone Mode',
        'ios_body' => $isArabic ? 'في iPhone يعمل التمرين بوضع الاستماع والتكرار لأن التعرف الصوتي التلقائي غير مستقر في المتصفح.' : 'On iPhone, this exercise uses listen-and-repeat mode because browser speech recognition is not reliable there.',
        'ios_cta' => $isArabic ? 'استمع أولًا ثم كرر بصوتك' : 'Listen first, then repeat aloud',
        'ios_scoring' => $isArabic ? 'التقييم التلقائي يعمل بشكل أفضل على الكمبيوتر أو Android Chrome.' : 'Automatic scoring works best on desktop or Android Chrome.',
        'badge' => $isArabic ? 'تمرين النطق' : 'Speech Exercise',
        'subtitle' => $isArabic ? 'تمرّن على النطق داخل هذا الدرس' : 'Practice speaking inside this lesson',
        'browser_title' => $isArabic ? 'المتصفح غير مدعوم' : 'Browser Not Supported',
        'browser_body' => $isArabic ? 'ميزة التعرف على الصوت تحتاج إلى Google Chrome أو Microsoft Edge.' : 'Speech recognition requires Google Chrome or Microsoft Edge.',
        'listen_example' => $isArabic ? 'استمع إلى المثال' : 'Listen to Example',
        'tap_start' => $isArabic ? 'اضغط هنا لبدء التحدث' : 'Tap to start speaking',
        'listening' => $isArabic ? 'جاري الاستماع...' : 'Listening...',
        'tap_stop' => $isArabic ? 'اضغط للإيقاف' : 'Tap to stop',
        'evaluating' => $isArabic ? 'جاري التقييم' : 'Evaluating',
        'explanation' => $isArabic ? 'شرح وتوضيح' : 'Explanation',
        'listen_correct' => $isArabic ? 'استمع إلى النطق الصحيح' : 'Listen to the correct pronunciation',
        'listen_correct_hint' => $isArabic ? 'لديك محاولتان غير ناجحتين. استمع ثم حاول مرة أخرى.' : 'You have had 2 attempts. Listen carefully and try again.',
        'hear_correct' => $isArabic ? 'استمع إلى النطق الصحيح' : 'Hear Correct Pronunciation',
        'speaking_now' => $isArabic ? 'جاري التشغيل...' : 'Speaking...',
        'you_said' => $isArabic ? 'ما قلته:' : 'You said:',
        'previous_best' => $isArabic ? 'أفضل نتيجة سابقة:' : 'Previous best:',
        'attempts' => $isArabic ? 'محاولات' : 'attempts',
        'back' => $isArabic ? 'العودة إلى الدرس' : 'Back to Lesson',
        'my_attempts' => $isArabic ? 'محاولاتي' : 'My Attempts',
        'no_speech' => $isArabic ? 'لم نلتقط صوتًا واضحًا. حاول مرة أخرى.' : 'No speech detected. Please try again.',
        'mic_denied' => $isArabic ? 'تم رفض الوصول إلى الميكروفون. فعّله من إعدادات المتصفح.' : 'Microphone access denied. Please enable it in your browser settings.',
        'start_failed' => $isArabic ? 'تعذر بدء الاستماع. حاول مرة أخرى.' : 'Could not start recognition. Please try again.',
        'evaluation_failed' => $isArabic ? 'تعذر تقييم النطق.' : 'Evaluation failed.',
        'great_prefix' => $isArabic ? 'نطق ممتاز! نتيجتك: ' : 'Great pronunciation! Score: ',
        'listen_below' => $isArabic ? 'استمع إلى النطق الصحيح أدناه.' : 'Listen to the correct pronunciation below.',
        'try_again' => $isArabic ? 'حاول مرة أخرى. بقيت محاولة واحدة قبل تشغيل النطق الصحيح.' : 'Try again! 1 more attempt before hearing the correct pronunciation.',
        'network_error' => $isArabic ? 'حدث خطأ في الاتصال. حاول مرة أخرى.' : 'Network error. Please try again.',
        'tts_unsupported' => $isArabic ? 'المتصفح لا يدعم تشغيل النطق الآلي.' : 'Text-to-speech is not supported in this browser.',
        'excellent' => $isArabic ? 'ممتاز' : 'Excellent!',
        'great' => $isArabic ? 'أحسنت' : 'Great job!',
        'good' => $isArabic ? 'محاولة جيدة' : 'Good try!',
        'keep' => $isArabic ? 'واصل التدريب' : 'Keep practicing!',
        'accuracy' => $isArabic ? 'الدقة' : 'Accuracy',
        'clarity' => $isArabic ? 'الوضوح' : 'Clarity',
        'fluency' => $isArabic ? 'السلاسة' : 'Fluency',
    ];
    $scoreLabels = [
        'pronunciation' => $messages['accuracy'],
        'clarity' => $messages['clarity'],
        'fluency' => $messages['fluency'],
    ];
@endphp

@section('title', $pageTitle . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden" x-data="pronunciationApp()">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="mb-10 text-center" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold mb-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a3 3 0 013 3v5a3 3 0 11-6 0V6a3 3 0 013-3zm6 8a6 6 0 01-12 0M8 21h8m-4-3v3"/></svg>
                {{ $messages['badge'] }}
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2" style="color: var(--color-text);">{{ $pageTitle }}</h1>
            <p class="text-base" style="color: var(--color-text-muted);">{{ $exercise->lesson->title ?? $messages['subtitle'] }}</p>
        </div>

        <div x-show="listenOnlyMode" x-cloak class="mb-8 p-5 rounded-2xl text-center" style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.18);">
            <div class="flex justify-center mb-3">
                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm8.5 7a6.5 6.5 0 01-3.5 5.76M16.5 8.24A6.5 6.5 0 0119.5 12"/></svg>
            </div>
            <h3 class="font-bold text-base mb-1 text-sky-400">{{ $messages['ios_title'] }}</h3>
            <p class="text-sm text-sky-200/80">{{ $messages['ios_body'] }}</p>
            <p class="text-xs mt-2 text-sky-200/60">{{ $messages['ios_scoring'] }}</p>
        </div>

        <div x-show="!listenOnlyMode && !recognitionSupported" x-cloak class="mb-8 p-5 rounded-2xl text-center" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2);">
            <div class="flex justify-center mb-3">
                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.38 12.8A2 2 0 004.62 20h14.76a2 2 0 001.71-3.34l-7.38-12.8a2 2 0 00-3.42 0z"/></svg>
            </div>
            <h3 class="font-bold text-base mb-1 text-amber-400">{{ $messages['browser_title'] }}</h3>
            <p class="text-sm text-amber-300/70">{{ $messages['browser_body'] }}</p>
        </div>

        @php
            try {
                $vocabList = is_array($exercise->vocabulary_json) ? $exercise->vocabulary_json : [];
            } catch (\Throwable $e) {
                $vocabList = [];
            }

            try {
                $explanations = [
                    2 => $exercise->sentence_explanation ?? null,
                    3 => $exercise->passage_explanation ?? null,
                ];
            } catch (\Throwable $e) {
                $explanations = [2 => null, 3 => null];
            }
        @endphp

        @if(!empty($vocabList))
            <div class="mb-10 rounded-2xl overflow-hidden" data-aos="fade-up">
                <div class="px-6 py-4 flex items-center gap-3" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border);">
                    <span class="text-base font-bold" style="color: var(--color-text);">{{ $isArabic ? 'كلمات الدرس' : 'Lesson Vocabulary' }}</span>
                    <span class="ml-auto text-xs px-2 py-1 rounded-lg" style="background: rgba(var(--color-primary-rgb,139,92,246),0.1); color: var(--color-primary,#a78bfa);">{{ count($vocabList) }} {{ $isArabic ? 'كلمات' : 'words' }}</span>
                </div>
                <div class="overflow-x-auto" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-top: none;">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background: rgba(var(--color-primary-rgb,139,92,246),0.08); border-bottom: 1px solid var(--glass-border);">
                                <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">#</th>
                                <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">{{ $isArabic ? 'الكلمة' : 'Word' }}</th>
                                <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">{{ $isArabic ? 'النطق' : 'Pronunciation' }}</th>
                                <th class="px-4 py-3 text-left font-semibold" style="color: var(--color-primary,#a78bfa);">{{ $isArabic ? 'المعنى' : 'Meaning' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vocabList as $i => $vocab)
                                <tr class="border-b" style="border-color: var(--glass-border);">
                                    <td class="px-4 py-3 text-xs font-bold" style="color: var(--color-text-muted);">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 font-bold text-base" style="color: var(--color-text);" dir="ltr">{{ $vocab['word'] ?? '' }}</td>
                                    <td class="px-4 py-3" style="color: var(--color-primary,#a78bfa); font-family: monospace;" dir="ltr">{{ $vocab['pronunciation'] ?? '' }}</td>
                                    <td class="px-4 py-3 font-semibold" style="color: var(--color-text-muted); direction: rtl;">{{ $vocab['meaning_ar'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @foreach($exercise->sentences as $num => $sentence)
            <div class="mb-8 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="{{ ($num - 1) * 100 }}" style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="px-6 py-4 flex items-center gap-3" style="border-bottom: 1px solid var(--glass-border);">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold" style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.15); color: var(--color-primary, #a78bfa);">{{ $num }}</span>
                    <span class="text-sm font-semibold" style="color: var(--color-text);">{{ $exerciseLabels[$num] ?? $messages['badge'] }}</span>
                    <span class="ml-auto text-xs px-2 py-1 rounded-lg" style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.1); color: var(--color-primary, #a78bfa);">
                        {{ $exerciseHints[$num] ?? $messages['tap_start'] }}
                    </span>
                </div>

                <div class="px-6 pt-6 pb-4">
                    <div class="p-5 rounded-xl mb-4" style="background: rgba(var(--color-primary-rgb, 139, 92, 246), 0.06); border: 1px solid rgba(var(--color-primary-rgb, 139, 92, 246), 0.12);">
                        <p dir="ltr" class="font-bold leading-relaxed whitespace-pre-line {{ $exerciseTextStyles[$num] ?? 'text-xl md:text-2xl text-left' }}" style="color: var(--color-text);">
                            {{ $sentence }}
                        </p>
                    </div>

                    @if(isset($exercise->reference_audio_urls[$num]))
                        <div class="flex justify-center mb-4">
                            <button type="button" x-on:click='playReferenceOrTts({{ $num }})' class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all hover:scale-105" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"/></svg>
                                {{ $messages['listen_example'] }}
                            </button>
                        </div>
                    @endif
                </div>

                <div class="px-6 pb-6">
                    <div class="flex flex-col items-center gap-4 py-6 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <button type="button" @click="togglePractice({{ $num }})" :disabled="(!recognitionSupported && !listenOnlyMode) || (isRecording && activeSentence !== {{ $num }})" class="w-20 h-20 rounded-full flex items-center justify-center transition-all duration-300 disabled:opacity-40" :class="isRecording && activeSentence === {{ $num }} ? 'bg-red-500 scale-110 animate-pulse shadow-lg shadow-red-500/30' : 'bg-gradient-to-br from-primary-500 to-accent-500 hover:scale-105 shadow-lg shadow-primary-500/30'">
                            <svg x-show="!listenOnlyMode && !(isRecording && activeSentence === {{ $num }})" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            <svg x-show="listenOnlyMode" x-cloak class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm8.5 7a6.5 6.5 0 01-3.5 5.76M16.5 8.24A6.5 6.5 0 0119.5 12"/></svg>
                            <svg x-show="isRecording && activeSentence === {{ $num }}" x-cloak class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        </button>

                        <p class="text-sm font-medium" :style="isRecording && activeSentence === {{ $num }} ? 'color: #ef4444' : 'color: var(--color-text-muted)'">
                            <span x-show="listenOnlyMode">{{ $messages['ios_cta'] }}</span>
                            <span x-show="!listenOnlyMode && !(isRecording && activeSentence === {{ $num }})">{{ $messages['tap_start'] }}</span>
                            <span x-show="isRecording && activeSentence === {{ $num }}" x-cloak>{{ $messages['listening'] }} {{ $messages['tap_stop'] }}</span>
                        </p>

                        <div x-show="listenOnlyMode" x-cloak class="mx-4 w-[calc(100%-2rem)] p-3 rounded-xl text-center text-xs" style="background: rgba(59, 130, 246, 0.08); color: var(--color-text-muted); border: 1px solid rgba(59, 130, 246, 0.14);">
                            {{ $messages['ios_scoring'] }}
                        </div>

                        <div x-show="activeSentence === {{ $num }} && liveTranscript" x-cloak dir="ltr" class="mx-4 w-[calc(100%-2rem)] p-3 rounded-xl text-left text-sm italic" style="background: rgba(0,0,0,0.2); color: var(--color-text-muted); border: 1px solid var(--glass-border);">
                            <span x-text="liveTranscript"></span>
                        </div>

                        <div x-show="isEvaluating && activeSentence === {{ $num }}" x-cloak class="flex items-center gap-2">
                            <div class="w-4 h-4 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-sm" style="color: var(--color-text-muted);">{{ $messages['evaluating'] }}...</span>
                        </div>
                    </div>

                    <div x-show="results[{{ $num }}]" x-cloak x-transition class="mt-4 p-5 rounded-xl" style="background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border);">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="relative w-24 h-24 shrink-0">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent" style="stroke: rgba(255,255,255,0.1);"/>
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent" :stroke-dasharray="2 * 3.14159 * 40" :stroke-dashoffset="2 * 3.14159 * 40 * (1 - (results[{{ $num }}]?.score || 0) / 100)" :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-400' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-400' : 'text-red-400'" stroke="currentColor" stroke-linecap="round" style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-2xl font-black text-white" x-text="Math.round(results[{{ $num }}]?.score || 0) + '%'"></span>
                                </div>
                            </div>

                            <div class="flex-1 text-center md:text-left">
                                <p class="font-bold text-lg mb-1" :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-400' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-400' : 'text-red-400'" x-text="scoreHeadline(results[{{ $num }}]?.score || 0)"></p>
                                <p class="text-sm mb-3" style="color: var(--color-text-muted);" x-text="results[{{ $num }}]?.feedback || ''"></p>

                                <div class="space-y-2">
                                    <template x-for="(label, key) in scoreLabels" :key="key">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-medium w-16 text-gray-400" x-text="label"></span>
                                            <div class="flex-1 h-2 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.08);">
                                                <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-1000" :style="'width: ' + (results[{{ $num }}]?.[key] || 0) + '%'"></div>
                                            </div>
                                            <span class="text-xs font-bold w-8 text-white" x-text="(results[{{ $num }}]?.[key] || 0) + '%'"></span>
                                        </div>
                                    </template>
                                </div>

                                <div dir="ltr" class="mt-3 text-xs text-left text-gray-500">
                                    {{ $messages['you_said'] }} <span class="text-gray-400" x-text="results[{{ $num }}]?.transcript || ''"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($explanations[$num]) && $explanations[$num])
                        <div x-show="results[{{ $num }}] && (results[{{ $num }}]?.score || 0) >= passingScore" x-cloak x-transition class="mt-4 p-5 rounded-xl" style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2);">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(16,185,129,0.12);">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-2 text-emerald-400">{{ $messages['explanation'] }}</h4>
                                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted); {{ $isArabic ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
                                        {{ $explanations[$num] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div x-show="failedAttempts[{{ $num }}] >= 2" x-cloak x-transition class="mt-4 p-5 rounded-xl" style="background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(245, 158, 11, 0.1);">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"/></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm mb-1 text-amber-400">{{ $messages['listen_correct'] }}</h4>
                                <p class="text-xs mb-3 text-amber-300/60">{{ $messages['listen_correct_hint'] }}</p>
                                <button type="button" x-on:click='speakCorrect({{ $num }}, @json($sentence))' class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:scale-105" style="background: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                    <span x-text="isSpeaking && speakingSentence === {{ $num }} ? messages.speaking_now : messages.hear_correct"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if(isset($attempts[$num]) && $attempts[$num]->count() > 0)
                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500 px-1">
                            <span>{{ $messages['previous_best'] }}</span>
                            <span class="font-bold text-primary-400">{{ $attempts[$num]->max('overall_score') }}%</span>
                            <span>&middot;</span>
                            <span>{{ $attempts[$num]->count() }} {{ $messages['attempts'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="flex flex-wrap justify-center gap-3 mt-2" data-aos="fade-up">
            @if(isset($exercise->lesson))
                <a href="{{ route('student.lessons.show', [$exercise->lesson->course, $exercise->lesson]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $messages['back'] }}
                </a>
            @endif
            <a href="{{ route('student.pronunciation.my-attempts') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                {{ $messages['my_attempts'] }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function pronunciationApp() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera || '';
    const recognitionSupported = 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window;
    const listenOnlyMode = /iPad|iPhone|iPod/.test(userAgent)
        || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

    return {
        recognitionSupported,
        listenOnlyMode,
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
        messages: @json($messages),
        scoreLabels: @json($scoreLabels),
        sentences: {
            @foreach($exercise->sentences as $num => $sentence)
                {{ $num }}: @json($sentence),
            @endforeach
        },
        referenceAudio: {
            @foreach($exercise->sentences as $num => $sentence)
                {{ $num }}: @json($exercise->reference_audio_urls[$num] ?? null),
            @endforeach
        },

        scoreHeadline(score) {
            if (score >= 90) return this.messages.excellent;
            if (score >= 70) return this.messages.great;
            if (score >= 50) return this.messages.good;
            return this.messages.keep;
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
                const transcript = this.liveTranscript.trim();
                const finishedSentence = this.activeSentence;
                this.isRecording = false;
                this.recognition = null;

                if (finishedSentence && transcript) {
                    this.submitTranscript(finishedSentence, transcript);
                }
            };

            recognition.onerror = (event) => {
                this.isRecording = false;
                this.recognition = null;

                if (event.error === 'aborted') {
                    return;
                }

                if (event.error === 'no-speech') {
                    if (window.showNotification) window.showNotification(this.messages.no_speech, 'warning');
                } else if (event.error === 'not-allowed') {
                    if (window.showNotification) window.showNotification(this.messages.mic_denied, 'error');
                } else {
                    if (window.showNotification) window.showNotification(this.messages.start_failed, 'error');
                }
            };

            return recognition;
        },

        togglePractice(sentenceNumber) {
            if (this.listenOnlyMode) {
                this.playReferenceOrTts(sentenceNumber);
                return;
            }

            this.toggleRecording(sentenceNumber);
        },

        toggleRecording(sentenceNumber) {
            if (this.isRecording && this.activeSentence === sentenceNumber) {
                this.stopRecording();
            } else {
                this.startRecording(sentenceNumber);
            }
        },

        startRecording(sentenceNumber) {
            if (!this.recognitionSupported || this.listenOnlyMode) {
                this.playReferenceOrTts(sentenceNumber);
                return;
            }

            if (this.isRecording) this.stopRecording();
            if (this.isSpeaking) window.speechSynthesis.cancel();

            this.recognition = this.initRecognition();
            if (!this.recognition) return;

            this.activeSentence = sentenceNumber;
            this.liveTranscript = '';
            this.isRecording = true;

            try {
                this.recognition.start();
            } catch (e) {
                this.isRecording = false;
                if (window.showNotification) window.showNotification(this.messages.start_failed, 'error');
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
                        if (window.showNotification) window.showNotification(this.messages.great_prefix + data.score + '%', 'success');
                    } else {
                        if (!this.failedAttempts[sentenceNumber]) {
                            this.failedAttempts[sentenceNumber] = 0;
                        }

                        this.failedAttempts[sentenceNumber]++;

                        if (this.failedAttempts[sentenceNumber] >= 2) {
                            if (window.showNotification) window.showNotification(this.messages.listen_below, 'info');
                            setTimeout(() => this.speakCorrect(sentenceNumber, this.sentences[sentenceNumber]), 1500);
                        } else {
                            if (window.showNotification) window.showNotification(this.messages.try_again, 'warning');
                        }
                    }
                } else if (window.showNotification) {
                    window.showNotification(data.error || this.messages.evaluation_failed, 'error');
                }
            } catch {
                if (window.showNotification) window.showNotification(this.messages.network_error, 'error');
            }

            this.isEvaluating = false;
        },

        playReferenceOrTts(sentenceNumber) {
            const url = this.referenceAudio[sentenceNumber];
            const text = this.sentences[sentenceNumber];

            if (url) {
                this.playAudio(url, text, sentenceNumber);
                return;
            }

            this.speakCorrect(sentenceNumber, text);
        },

        speakCorrect(sentenceNumber, text) {
            if (!('speechSynthesis' in window)) {
                if (window.showNotification) window.showNotification(this.messages.tts_unsupported, 'error');
                return;
            }

            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            utterance.pitch = 1;
            utterance.volume = 1;

            const voices = window.speechSynthesis.getVoices();
            const englishVoice = voices.find((voice) => voice.lang === 'en-US' && voice.name.includes('Google'))
                || voices.find((voice) => voice.lang === 'en-US' && voice.name.includes('Microsoft'))
                || voices.find((voice) => voice.lang === 'en-US')
                || voices.find((voice) => voice.lang.startsWith('en'));

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

        playAudio(url, fallbackText = null, sentenceNumber = null) {
            if (this.isSpeaking && 'speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            const audio = new Audio(url);
            audio.preload = 'auto';
            audio.playsInline = true;
            audio.play().catch(() => {
                if (fallbackText) {
                    this.speakCorrect(sentenceNumber, fallbackText);
                }
            });
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
