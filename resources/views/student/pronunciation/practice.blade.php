@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $localizedTitle = function (?string $title) use ($isArabic): string {
        $value = trim((string) $title);
        if ($value === '') {
            return $value;
        }

        $segments = preg_split('/\s+(?:-|\x{2013}|\x{2014})\s+/u', $value);
        if (!is_array($segments) || count($segments) < 2) {
            return $value;
        }

        $segments = array_values(array_filter(array_map(static fn ($part) => trim((string) $part), $segments)));
        if ($segments === []) {
            return $value;
        }

        if ($isArabic) {
            foreach ($segments as $segment) {
                if (preg_match('/\p{Arabic}/u', $segment) === 1) {
                    return $segment;
                }
            }
        } else {
            foreach ($segments as $segment) {
                if (preg_match('/[A-Za-z]/', $segment) === 1) {
                    return $segment;
                }
            }
        }

        return $segments[0];
    };
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
        'stream_unavailable' => $isArabic ? 'وضع البث المباشر غير متاح الآن، تم التحويل للوضع البديل.' : 'Realtime streaming is unavailable now, switched to fallback mode.',
        'excellent' => $isArabic ? 'ممتاز' : 'Excellent!',
        'great' => $isArabic ? 'أحسنت' : 'Great job!',
        'good' => $isArabic ? 'محاولة جيدة' : 'Good try!',
        'keep' => $isArabic ? 'واصل التدريب' : 'Keep practicing!',
        'accuracy' => $isArabic ? 'الدقة' : 'Accuracy',
        'clarity' => $isArabic ? 'الوضوح' : 'Clarity',
        'fluency' => $isArabic ? 'السلاسة' : 'Fluency',
    ];
    $messages = array_merge($messages, [
        'azure_scores_title' => $isArabic ? 'تقييم Azure' : 'Azure Assessment',
        'azure_accuracy' => $isArabic ? 'دقة النطق' : 'Azure Accuracy',
        'azure_fluency' => $isArabic ? 'الطلاقة' : 'Azure Fluency',
        'azure_completeness' => $isArabic ? 'الاكتمال' : 'Azure Completeness',
        'azure_pronunciation' => $isArabic ? 'درجة النطق' : 'Azure Pronunciation',
        'correct_words_title' => $isArabic ? 'كلمات صحيحة' : 'Correct Words',
        'wrong_words_title' => $isArabic ? 'كلمات تحتاج تصحيح' : 'Wrong Words',
        'missing_words_title' => $isArabic ? 'كلمات ناقصة' : 'Missing Words',
        'speak_feedback' => $isArabic ? 'استمع للملاحظات' : 'Speak Feedback',
        'feedback_spoken' => $isArabic ? 'جارٍ قراءة الملاحظات' : 'Reading feedback aloud',
        'session_expired' => $isArabic ? 'انتهت جلستك. حدّث الصفحة وحاول مرة أخرى.' : 'Your session expired. Refresh the page and try again.',
        'server_error' => $isArabic ? 'حدث خطأ في الخادم أثناء تحليل الصوت.' : 'Server error while analyzing the audio.',
        'upload_too_large' => $isArabic ? 'ملف الصوت كبير جدًا. حاول تسجيل إجابة أقصر.' : 'Uploaded audio is too large. Please record a shorter answer.',
        'strengths_title' => $isArabic ? 'نقاط القوة' : 'Strengths',
        'improvements_title' => $isArabic ? 'ما الذي يحتاج تحسين' : 'Needs Improvement',
        'corrected_sentence_title' => $isArabic ? 'جملة مصححة' : 'Corrected Sentence',
        'coach_reply_title' => $isArabic ? 'رد المدرب' : 'Coach Reply',
    ]);

    $scoreLabels = [
        'pronunciation' => $messages['accuracy'],
        'clarity' => $messages['clarity'],
        'fluency' => $messages['fluency'],
        'completion' => $isArabic ? 'الإكمال' : 'Completion',
    ];
    $azureScoreLabels = [
        'azure_accuracy' => $messages['azure_accuracy'],
        'azure_fluency' => $messages['azure_fluency'],
        'azure_completeness' => $messages['azure_completeness'],
        'azure_pronunciation' => $messages['azure_pronunciation'],
    ];
    $lessonSubtitle = $localizedTitle($exercise->lesson->title ?? '') ?: $messages['subtitle'];
@endphp

@section('title', $pageTitle . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden" x-data="pronunciationApp()">

    <div class="student-container max-w-3xl relative z-10">
        <x-student.page-header
            title="{{ $pageTitle }}"
            subtitle="{{ $lessonSubtitle }}"
            badge="{{ $messages['badge'] }}"
            badgeColor="primary"
            badgeIcon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 3a3 3 0 013 3v5a3 3 0 11-6 0V6a3 3 0 013-3zm6 8a6 6 0 01-12 0M8 21h8m-4-3v3'/></svg>"
        />

        <div x-show="isEvaluating" x-cloak x-transition class="mb-6 rounded-2xl border border-primary-200/70 bg-primary-50/90 p-4 shadow-sm dark:border-primary-500/20 dark:bg-primary-500/10">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-500/15 text-primary-500">
                    <div class="h-5 w-5 rounded-full border-2 border-current border-t-transparent animate-spin"></div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-black text-slate-900 dark:text-white">
                        {{ $isArabic ? 'الذكاء الاصطناعي يحلل النطق الآن' : 'AI is analyzing your pronunciation now' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">
                        {{ $isArabic ? 'انتظر ثوانٍ قليلة حتى يظهر التقييم والتصحيح.' : 'Please wait a few seconds for the score and corrections to appear.' }}
                    </p>
                </div>
                <div x-show="evaluatingSentence" x-cloak class="rounded-xl bg-white/80 px-3 py-2 text-xs font-bold text-primary-600 dark:bg-slate-900/40 dark:text-primary-300">
                    <span>{{ $isArabic ? 'العنصر' : 'Item' }}</span>
                    <span x-text="evaluatingSentence"></span>
                </div>
            </div>
        </div>

        <div x-show="!recognitionSupported && !mediaRecorderSupported" x-cloak class="mb-8 p-5 rounded-2xl text-center" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2);">
            <div class="flex justify-center mb-3">
                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.38 12.8A2 2 0 004.62 20h14.76a2 2 0 001.71-3.34l-7.38-12.8a2 2 0 00-3.42 0z"/></svg>
            </div>
            <h3 class="font-bold text-base mb-1 text-amber-400">{{ $messages['browser_title'] }}</h3>
            <p class="text-sm text-amber-300/70">{{ $messages['browser_body'] }}</p>
        </div>

        <div x-show="safeRecordingMode" x-cloak class="mb-8 rounded-2xl border border-primary-200/60 bg-primary-50/80 p-4 text-sm text-primary-700 shadow-sm dark:border-primary-500/20 dark:bg-primary-500/10 dark:text-primary-200">
            <p class="font-bold">{{ $isArabic ? 'وضع تسجيل ثابت' : 'Stable Recording Mode' }}</p>
            <p class="mt-1">{{ $isArabic ? 'على iPhone Safari يبدأ التسجيل بضغطة واحدة ويتوقف بضغطة ثانية، ثم يظهر التقييم بعد الإيقاف.' : 'On iPhone Safari, recording starts with one tap and stops with the next tap. Your score appears after you stop.' }}</p>
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
            <x-student.card padding="p-0" class="mb-10 overflow-hidden" data-aos="fade-up">
                <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    <span class="text-base font-bold text-slate-900 dark:text-white">{{ $isArabic ? 'كلمات الدرس' : 'Lesson Vocabulary' }}</span>
                    <span class="ml-auto text-xs px-2 py-1 rounded-lg bg-primary-500/10 text-primary-500">{{ count($vocabList) }} {{ $isArabic ? 'كلمات' : 'words' }}</span>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-start whitespace-nowrap">
                        <thead>
                            <tr class="bg-primary-500/5 border-b border-slate-200/50 dark:border-white/5 text-primary-600 dark:text-primary-400">
                                <th class="px-4 py-3 text-start font-semibold">#</th>
                                <th class="px-4 py-3 text-start font-semibold">{{ $isArabic ? 'الكلمة' : 'Word' }}</th>
                                <th class="px-4 py-3 text-start font-semibold">{{ $isArabic ? 'النطق' : 'Pronunciation' }}</th>
                                <th class="px-4 py-3 text-start font-semibold">{{ $isArabic ? 'المعنى' : 'Meaning' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/50 dark:divide-white/5">
                            @foreach($vocabList as $i => $vocab)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="px-4 py-3 text-xs font-bold text-slate-500 dark:text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 font-bold text-base text-slate-900 dark:text-white" dir="ltr">{{ $vocab['word'] ?? '' }}</td>
                                    <td class="px-4 py-3 text-primary-500 font-mono" dir="ltr">{{ $vocab['pronunciation'] ?? '' }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-500 dark:text-slate-400" dir="rtl">{{ $vocab['meaning_ar'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-student.card>
        @endif

        @foreach($exercise->sentences as $num => $sentence)
            <x-student.card padding="p-0" class="mb-8" data-aos="fade-up" data-aos-delay="{{ ($num - 1) * 100 }}">
                <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold bg-primary-500/15 text-primary-500">{{ $num }}</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $exerciseLabels[$num] ?? $messages['badge'] }}</span>
                    <span class="ml-auto text-xs px-2 py-1 rounded-lg bg-primary-500/10 text-primary-500">
                        {{ $exerciseHints[$num] ?? $messages['tap_start'] }}
                    </span>
                </div>

                <div class="px-6 pt-6 pb-4">
                    <div class="p-5 rounded-xl mb-4 bg-primary-500/5 border border-primary-500/10">
                        <p dir="ltr" class="font-bold leading-relaxed whitespace-pre-line text-slate-900 dark:text-white {{ $exerciseTextStyles[$num] ?? 'text-xl md:text-2xl text-start' }}">
                            {{ $sentence }}
                        </p>
                    </div>

                    @if(isset($exercise->reference_audio_urls[$num]))
                        <div class="flex justify-center mb-4">
                            <button type="button" x-on:click='playReferenceOrTts({{ $num }})' class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all hover:scale-105 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"/></svg>
                                {{ $messages['listen_example'] }}
                            </button>
                        </div>
                    @endif
                </div>

                <div class="px-6 pb-6">
                    <div class="flex flex-col items-center gap-4 py-6 rounded-xl bg-slate-100 dark:bg-slate-800/50">
                        <button type="button" @click.stop.prevent="togglePractice({{ $num }})" :disabled="isStartingRecording || (!recognitionSupported && !mediaRecorderSupported) || (isRecording && activeSentence !== {{ $num }})" class="w-20 h-20 rounded-full flex items-center justify-center transition-all duration-300 disabled:opacity-40" :class="isRecording && activeSentence === {{ $num }} ? 'bg-rose-500 scale-110 animate-pulse shadow-lg shadow-rose-500/30' : 'bg-gradient-to-br from-primary-600 to-accent-500 hover:scale-105 shadow-lg shadow-primary-500/30'">
                            <svg x-show="!(isRecording && activeSentence === {{ $num }})" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            <svg x-show="isRecording && activeSentence === {{ $num }}" x-cloak class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        </button>

                        <p class="text-sm font-medium" :class="isRecording && activeSentence === {{ $num }} ? 'text-rose-500' : 'text-slate-500 dark:text-slate-400'">
                            <span x-show="!(isRecording && activeSentence === {{ $num }})" x-text="safeRecordingMode ? '{{ $isArabic ? 'اضغط مرة للتسجيل' : 'Tap once to record' }}' : messages.tap_start"></span>
                            <span x-show="isRecording && activeSentence === {{ $num }}" x-cloak x-text="safeRecordingMode ? '{{ $isArabic ? 'جارٍ التسجيل... سيتوقف تلقائيًا عندما تنتهي' : 'Recording... it will stop automatically when you finish' }}' : '{{ $isArabic ? 'جارٍ الاستماع... سيتوقف تلقائيًا عندما تنتهي' : 'Listening... it will stop automatically when you finish' }}'"></span>
                        </p>

                        <div x-show="!safeRecordingMode && activeSentence === {{ $num }} && liveTranscript" x-cloak dir="ltr" class="mx-4 w-[calc(100%-2rem)] p-3 rounded-xl text-left text-sm bg-slate-200 dark:bg-slate-900/50 border border-slate-300 dark:border-white/10 text-slate-600 dark:text-slate-300">
                            <template x-if="liveWordDiff[{{ $num }}] && liveWordDiff[{{ $num }}].length">
                                <div class="flex flex-wrap gap-1.5 leading-7">
                                    <template x-for="(token, index) in liveWordDiff[{{ $num }}]" :key="index">
                                        <span class="px-2 py-0.5 rounded-md border text-sm font-semibold"
                                              :class="{
                                                'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-300': token.status === 'correct',
                                                'bg-amber-500/10 border-amber-500/30 text-amber-600 dark:text-amber-300': token.status === 'wrong',
                                                'bg-rose-500/10 border-rose-500/30 text-rose-600 dark:text-rose-300 line-through': token.status === 'missing',
                                                'bg-sky-500/10 border-sky-500/30 text-sky-600 dark:text-sky-300': token.status === 'extra'
                                              }"
                                              x-text="token.display"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!(liveWordDiff[{{ $num }}] && liveWordDiff[{{ $num }}].length)">
                                <span class="italic" x-text="liveTranscript"></span>
                            </template>
                        </div>

                        <div x-show="isEvaluating && activeSentence === {{ $num }}" x-cloak class="flex items-center gap-2">
                            <div class="w-4 h-4 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $messages['evaluating'] }}...</span>
                        </div>
                    </div>

                    <div x-show="results[{{ $num }}]" x-cloak x-transition class="mt-4 p-5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/5">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="relative w-24 h-24 shrink-0">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent" class="stroke-slate-200 dark:stroke-slate-700"/>
                                    <circle cx="48" cy="48" r="40" stroke-width="6" fill="transparent" :stroke-dasharray="2 * 3.14159 * 40" :stroke-dashoffset="2 * 3.14159 * 40 * (1 - (results[{{ $num }}]?.score || 0) / 100)" :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-500' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-500' : 'text-rose-500'" stroke="currentColor" stroke-linecap="round" style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-2xl font-black text-slate-900 dark:text-white" x-text="Math.round(results[{{ $num }}]?.score || 0) + '%'"></span>
                                </div>
                            </div>

                            <div class="flex-1 text-center md:text-start w-full">
                                <p class="font-bold text-lg mb-1" :class="(results[{{ $num }}]?.score || 0) >= 70 ? 'text-emerald-500' : (results[{{ $num }}]?.score || 0) >= 50 ? 'text-amber-500' : 'text-rose-500'" x-text="scoreHeadline(results[{{ $num }}]?.score || 0)"></p>
                                <p class="text-sm mb-3 text-slate-500 dark:text-slate-400" x-text="results[{{ $num }}]?.feedback || ''"></p>

                                <div x-show="results[{{ $num }}]?.coach" x-cloak class="mb-4 rounded-xl border border-primary-200/70 dark:border-primary-500/20 bg-primary-50/70 dark:bg-primary-500/10 p-4 text-start">
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-primary-600 dark:text-primary-300">
                                        {{ $isArabic ? 'نصيحة المدرب' : 'Coach Tip' }}
                                    </p>
                                    <p class="mt-2 text-sm font-bold text-slate-900 dark:text-white" x-text="results[{{ $num }}]?.coach?.title || ''"></p>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300" x-text="results[{{ $num }}]?.coach?.summary || ''"></p>
                                    <div class="mt-3 grid gap-2 md:grid-cols-2">
                                        <div class="rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $isArabic ? 'ما الذي تحسنه الآن' : 'What to improve now' }}</p>
                                            <p class="mt-1 text-sm text-slate-700 dark:text-slate-200" x-text="results[{{ $num }}]?.coach?.tip || ''"></p>
                                        </div>
                                        <div class="rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $isArabic ? 'المحاولة التالية' : 'Next try' }}</p>
                                            <p class="mt-1 text-sm text-slate-700 dark:text-slate-200" x-text="results[{{ $num }}]?.coach?.retry_instruction || ''"></p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="(results[{{ $num }}]?.coach?.strengths || []).length" x-cloak class="mt-3 rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $messages['strengths_title'] }}</p>
                                    <div class="mt-2 space-y-1.5">
                                        <template x-for="(item, index) in (results[{{ $num }}]?.coach?.strengths || [])" :key="'strength-' + index">
                                            <p class="text-sm text-slate-700 dark:text-slate-200" x-text="'- ' + item"></p>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="(results[{{ $num }}]?.coach?.improvements || []).length" x-cloak class="mt-3 rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $messages['improvements_title'] }}</p>
                                    <div class="mt-2 space-y-1.5">
                                        <template x-for="(item, index) in (results[{{ $num }}]?.coach?.improvements || [])" :key="'improvement-' + index">
                                            <p class="text-sm text-slate-700 dark:text-slate-200" x-text="'- ' + item"></p>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="results[{{ $num }}]?.coach?.corrected_sentence" x-cloak dir="ltr" class="mt-3 rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400 text-start">{{ $messages['corrected_sentence_title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-700 dark:text-slate-200 text-start" x-text="results[{{ $num }}]?.coach?.corrected_sentence || ''"></p>
                                </div>

                                <div x-show="results[{{ $num }}]?.coach?.short_coach_reply" x-cloak class="mt-3 rounded-lg bg-white/80 dark:bg-slate-900/30 px-3 py-2">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $messages['coach_reply_title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-700 dark:text-slate-200" x-text="results[{{ $num }}]?.coach?.short_coach_reply || ''"></p>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button type="button" @click="speakFeedback({{ $num }})" class="inline-flex items-center gap-2 rounded-lg border border-primary-200 bg-white/80 px-3 py-2 text-xs font-bold text-primary-600 transition hover:scale-[1.02] dark:border-primary-500/20 dark:bg-slate-900/30 dark:text-primary-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"/></svg>
                                        {{ $messages['speak_feedback'] }}
                                    </button>
                                </div>

                                <div class="space-y-2 max-w-sm mx-auto md:mx-0">
                                    <template x-for="(label, key) in scoreLabels" :key="key">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-medium w-16 text-slate-500 dark:text-slate-400 text-start" x-text="label"></span>
                                            <div class="flex-1 h-2 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700">
                                                <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-1000" :style="'width: ' + (results[{{ $num }}]?.[key] || 0) + '%'"></div>
                                            </div>
                                            <span class="text-xs font-bold w-8 text-slate-900 dark:text-white text-end" x-text="(results[{{ $num }}]?.[key] || 0) + '%'"></span>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="results[{{ $num }}]?.azure_accuracy !== null || results[{{ $num }}]?.azure_fluency !== null || results[{{ $num }}]?.azure_completeness !== null || results[{{ $num }}]?.azure_pronunciation !== null" x-cloak class="mt-4 rounded-xl border border-sky-200/70 bg-sky-50/70 p-4 dark:border-sky-500/20 dark:bg-sky-500/10">
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-sky-600 dark:text-sky-300">{{ $messages['azure_scores_title'] }}</p>
                                    <div class="mt-3 space-y-2">
                                        <template x-for="(label, key) in azureScoreLabels" :key="'azure-' + key">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-medium w-28 text-slate-500 dark:text-slate-400 text-start" x-text="label"></span>
                                                <div class="flex-1 h-2 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700">
                                                    <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-cyan-400 transition-all duration-1000" :style="'width: ' + (results[{{ $num }}]?.[key] || 0) + '%'"></div>
                                                </div>
                                                <span class="text-xs font-bold w-8 text-slate-900 dark:text-white text-end" x-text="((results[{{ $num }}]?.[key] ?? 0)) + '%'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 lg:grid-cols-3">
                                    <div x-show="resultWords({{ $num }}, 'correct').length" x-cloak class="rounded-xl border border-emerald-200/70 bg-emerald-50/70 p-3 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-300">{{ $messages['correct_words_title'] }}</p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <template x-for="(item, index) in resultWords({{ $num }}, 'correct')" :key="'correct-' + index">
                                                <span class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-200" x-text="item.text"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <div x-show="resultWords({{ $num }}, 'wrong').length" x-cloak class="rounded-xl border border-amber-200/70 bg-amber-50/70 p-3 dark:border-amber-500/20 dark:bg-amber-500/10">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-amber-600 dark:text-amber-300">{{ $messages['wrong_words_title'] }}</p>
                                        <div class="mt-2 space-y-2">
                                            <template x-for="(item, index) in resultWords({{ $num }}, 'wrong')" :key="'wrong-' + index">
                                                <div class="rounded-lg bg-white/80 px-2.5 py-2 text-xs dark:bg-slate-900/30">
                                                    <p class="font-bold text-amber-700 dark:text-amber-200" x-text="item.text"></p>
                                                    <p x-show="item.hint" class="mt-1 text-slate-500 dark:text-slate-300" x-text="item.hint"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div x-show="resultWords({{ $num }}, 'missing').length" x-cloak class="rounded-xl border border-rose-200/70 bg-rose-50/70 p-3 dark:border-rose-500/20 dark:bg-rose-500/10">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-rose-600 dark:text-rose-300">{{ $messages['missing_words_title'] }}</p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <template x-for="(item, index) in resultWords({{ $num }}, 'missing')" :key="'missing-' + index">
                                                <span class="rounded-full bg-rose-500/15 px-2.5 py-1 text-xs font-bold text-rose-700 dark:text-rose-200" x-text="item.text"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div dir="ltr" class="mt-3 text-xs text-start text-slate-500">
                                    {{ $messages['you_said'] }} <span class="text-slate-600 dark:text-slate-300 font-medium italic" x-text="results[{{ $num }}]?.transcript || ''"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($explanations[$num]) && $explanations[$num])
                        <div x-show="results[{{ $num }}] && (results[{{ $num }}]?.score || 0) >= passingScore" x-cloak x-transition class="mt-4 p-5 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-emerald-500/20">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-2 text-emerald-600 dark:text-emerald-400">{{ $messages['explanation'] }}</h4>
                                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300 {{ $isArabic ? 'text-right' : 'text-left' }}">
                                        {{ $explanations[$num] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div x-show="failedAttempts[{{ $num }}] >= 2" x-cloak x-transition class="mt-4 p-5 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 bg-amber-500/20">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"/></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm mb-1 text-amber-600 dark:text-amber-400">{{ $messages['listen_correct'] }}</h4>
                                <p class="text-xs mb-3 text-amber-600/70 dark:text-amber-400/80">{{ $messages['listen_correct_hint'] }}</p>
                                <button type="button" x-on:click='speakCorrect({{ $num }}, @json($sentence))' class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:scale-105 bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                    <span x-text="isSpeaking && speakingSentence === {{ $num }} ? messages.speaking_now : messages.hear_correct"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if(isset($attempts[$num]) && $attempts[$num]->count() > 0)
                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-500 px-1">
                            <span>{{ $messages['previous_best'] }}</span>
                            <span class="font-bold text-primary-500">{{ $attempts[$num]->max('overall_score') }}%</span>
                            <span>&middot;</span>
                            <span>{{ $attempts[$num]->count() }} {{ $messages['attempts'] }}</span>
                        </div>
                    @endif
                </div>
            </x-student.card>
        @endforeach

        <div class="flex flex-wrap justify-center gap-3 mt-2" data-aos="fade-up">
            @if(isset($exercise->lesson))
                <a href="{{ route('student.lessons.show', [$exercise->lesson->course, $exercise->lesson]) }}" class="btn-ghost" >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $messages['back'] }}
                </a>
            @endif
            <a href="{{ route('student.pronunciation.my-attempts') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                {{ $messages['my_attempts'] }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function pronunciationApp() {
    const userAgent = navigator.userAgent || '';
    const isIOS = /iPad|iPhone|iPod/.test(userAgent)
        || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    const isSafari = /Safari/i.test(userAgent) && !/CriOS|Chrome|FxiOS|Firefox|EdgiOS|Edg|OPiOS|Opera|SamsungBrowser/i.test(userAgent);
    const recognitionSupported = 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window;
    const mediaRecorderSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia && window.MediaRecorder);
    const safeRecordingMode = isIOS && isSafari && mediaRecorderSupported;

    return {
        recognitionSupported,
        mediaRecorderSupported,
        safeRecordingMode,
        isRecording: false,
        isEvaluating: false,
        isSpeaking: false,
        speakingSentence: null,
        evaluatingSentence: null,
        processingToken: null,
        processingPollTimeoutId: null,
        currentAudio: null,
        isStartingRecording: false,
        lastToggleAt: 0,
        activeSentence: null,
        liveTranscript: '',
        liveWordDiff: {},
        results: {},
        failedAttempts: {},
        recognition: null,
        mediaRecorder: null,
        mediaStream: null,
        streamSocket: null,
        streamSessionId: null,
        streamEnabled: false,
        usingStream: false,
        usingDirectUpload: false,
        uploadChunks: [],
        uploadMimeType: '',
        parallelRecognition: null,
        parallelTranscript: '',
        streamChunkMs: 1000,
        compareInFlight: false,
        pendingCompareTranscript: null,
        latestLatencyMs: null,
        pendingFinalTranscript: '',
        recordingStartedAt: null,
        recordingTimeoutId: null,
        manualStop: false,
        autoStopTriggered: false,
        minStopDelayMs: safeRecordingMode ? 1500 : 0,
        minStopAt: 0,
        maxRecordMs: {{ max(30, min(300, (int) ($exercise->max_duration_seconds ?? 30))) * 1000 }},
        streamLiveCompare: !safeRecordingMode,
        streamStopResolver: null,
        audioContext: null,
        analyserNode: null,
        analyserData: null,
        analyserSource: null,
        silenceMonitorId: null,
        speechDetectedAt: null,
        silenceDetectedAt: null,
        speechDetected: false,
        autoStopSilenceMs: 4500,
        autoStopSilenceMsLong: 6500,
        autoStopThreshold: 0.012,
        autoStopMinSpeechMs: 1200,
        autoStopMinSpeechMsLong: 2200,
        stopPayloadTimeoutMs: 9000,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        passingScore: {{ $exercise->passing_score ?? 70 }},
        messages: @json($messages),
        scoreLabels: @json($scoreLabels),
        azureScoreLabels: @json($azureScoreLabels),
        endpoints: {
            upload: @json(route('student.pronunciation.upload')),
            uploadStatusBase: @json(url('/student/pronunciation/status')),
            evaluate: @json(route('student.pronunciation.evaluate', $exercise)),
            streamStart: @json(route('student.pronunciation.stream.start', $exercise)),
            streamCompare: @json(route('student.pronunciation.stream.compare', $exercise)),
            streamFinalize: @json(route('student.pronunciation.stream.finalize', $exercise)),
        },
        exerciseId: {{ (int) $exercise->id }},
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

        resultWords(sentenceNumber, status) {
            const tokens = Array.isArray(this.results?.[sentenceNumber]?.word_diff)
                ? this.results[sentenceNumber].word_diff
                : [];

            return tokens
                .filter((token) => token?.status === status)
                .map((token) => {
                    if (status === 'wrong') {
                        return {
                            text: token.actual || token.expected || '',
                            hint: token.expected && token.actual && token.expected !== token.actual
                                ? `→ ${token.expected}`
                                : '',
                        };
                    }

                    return {
                        text: token.display || token.expected || token.actual || '',
                        hint: '',
                    };
                })
                .filter((item) => item.text);
        },

        feedbackText(sentenceNumber) {
            const result = this.results?.[sentenceNumber] || {};
            const coach = result.coach || {};

            return [
                coach.summary || '',
                coach.tip || '',
                coach.retry_instruction || '',
                result.feedback || '',
            ].filter(Boolean).join('. ');
        },

        speakFeedback(sentenceNumber) {
            const feedback = this.feedbackText(sentenceNumber);
            if (!feedback) {
                return;
            }

            this.speakCorrect(sentenceNumber, feedback, this.isArabicLocale() ? 'ar-SA' : 'en-US');

            if (window.showNotification) {
                window.showNotification(this.messages.feedback_spoken, 'info');
            }
        },

        isArabicLocale() {
            return document.documentElement.lang === 'ar' || document.documentElement.dir === 'rtl';
        },

        getAutoStopConfig(sentenceNumber) {
            const text = String(this.sentences?.[sentenceNumber] || '').trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            const isLongAnswer = words >= 15;
            const isVeryLongAnswer = words >= 35;

            const silenceMs = isVeryLongAnswer
                ? Math.max(this.autoStopSilenceMsLong, 12000)
                : (isLongAnswer ? this.autoStopSilenceMsLong : this.autoStopSilenceMs);
            const minSpeechMs = isVeryLongAnswer
                ? Math.max(this.autoStopMinSpeechMsLong, 5000)
                : (isLongAnswer ? this.autoStopMinSpeechMsLong : this.autoStopMinSpeechMs);

            // For long prompts, don't auto-stop unless enough words were captured.
            const minSpokenWordsBeforeStop = words > 0
                ? (isVeryLongAnswer ? Math.max(12, Math.floor(words * 0.5)) : Math.max(6, Math.floor(words * 0.35)))
                : 0;

            return {
                expectedWords: words,
                silenceMs,
                minSpeechMs,
                minSpokenWordsBeforeStop,
            };
        },

        initRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) return null;

            const recognition = new SpeechRecognition();
            recognition.lang = 'en-US';
            recognition.interimResults = true;
            recognition.continuous = true;
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

                const transcript = (finalTranscript || interimTranscript).trim();
                this.liveTranscript = transcript;
                if (this.activeSentence && transcript) {
                    this.handleLiveTranscript(this.activeSentence, transcript);
                }
            };

            recognition.onend = () => {
                const transcript = this.liveTranscript.trim();
                const finishedSentence = this.activeSentence;
                const elapsed = this.recordingStartedAt ? (Date.now() - this.recordingStartedAt) : 0;
                const shouldRestart = !this.manualStop
                    && this.isRecording
                    && !transcript
                    && this.recordingStartedAt
                    && (this.maxRecordMs <= 0 || elapsed < (this.maxRecordMs - 500));

                if (shouldRestart) {
                    this.recognition = this.initRecognition();
                    if (this.recognition) {
                        setTimeout(() => {
                            try {
                                this.recognition.start();
                            } catch (e) {
                                console.error('Recognition restart error:', e);
                                this.isRecording = false;
                                this.isStartingRecording = false;
                                this.recognition = null;
                                this.activeSentence = null;
                                this.recordingStartedAt = null;
                                if (this.recordingTimeoutId) {
                                    clearTimeout(this.recordingTimeoutId);
                                    this.recordingTimeoutId = null;
                                }
                            }
                        }, 200);
                    } else {
                        this.isRecording = false;
                        this.isStartingRecording = false;
                        this.activeSentence = null;
                        this.recordingStartedAt = null;
                        if (this.recordingTimeoutId) {
                            clearTimeout(this.recordingTimeoutId);
                            this.recordingTimeoutId = null;
                        }
                    }
                    return;
                }

                this.isRecording = false;
                this.isStartingRecording = false;
                this.recognition = null;
                this.activeSentence = null;
                this.recordingStartedAt = null;
                if (this.recordingTimeoutId) {
                    clearTimeout(this.recordingTimeoutId);
                    this.recordingTimeoutId = null;
                }

                if (finishedSentence && transcript) {
                    this.submitTranscript(finishedSentence, transcript);
                }
            };

            recognition.onerror = (event) => {
                // 'aborted' fires when we call .stop() — don't touch state,
                // stopRecording() or onend already handles cleanup.
                if (event.error === 'aborted') {
                    return;
                }

                // For real errors, full cleanup
                this.isRecording = false;
                this.isStartingRecording = false;
                this.recognition = null;
                this.activeSentence = null;
                this.recordingStartedAt = null;
                if (this.recordingTimeoutId) {
                    clearTimeout(this.recordingTimeoutId);
                    this.recordingTimeoutId = null;
                }

                if (event.error === 'no-speech') {
                    if (window.showNotification) window.showNotification(this.messages.no_speech, 'warning');
                } else if (event.error === 'not-allowed') {
                    if (window.showNotification) window.showNotification(this.messages.mic_denied, 'error');
                } else {
                    console.error('SpeechRecognition error:', event.error);
                    if (window.showNotification) window.showNotification(this.messages.start_failed, 'error');
                }
            };

            return recognition;
        },

        stopExamplePlayback() {
            if (this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio.currentTime = 0;
                this.currentAudio = null;
            }

            if (this.isSpeaking && 'speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            this.isSpeaking = false;
            this.speakingSentence = null;
        },

        async togglePractice(sentenceNumber) {
            const now = Date.now();

            if (this.isStartingRecording) {
                return;
            }

            if ((now - this.lastToggleAt) < 900) {
                return;
            }

            this.lastToggleAt = now;

            if (this.isRecording && this.activeSentence === sentenceNumber) {
                if (this.safeRecordingMode && now < this.minStopAt) {
                    return;
                }
                this.stopRecording();
            } else {
                await this.startRecording(sentenceNumber);
            }
        },

        async startRecording(sentenceNumber) {
            this.isStartingRecording = true;

            // Stop any existing recognition before starting new one
            if (this.isRecording) {
                this.stopRecording();
                await new Promise(r => setTimeout(r, 350));
            }

            this.stopExamplePlayback();

            this.activeSentence = sentenceNumber;
            this.liveTranscript = '';
            this.liveWordDiff[sentenceNumber] = [];
            this.isRecording = true;
            this.manualStop = false;
            this.recordingStartedAt = Date.now();
            this.minStopAt = this.recordingStartedAt + this.minStopDelayMs;
            this.pendingFinalTranscript = '';
            this.autoStopTriggered = false;
            this.speechDetectedAt = null;
            this.silenceDetectedAt = null;
            this.speechDetected = false;
            if (this.recordingTimeoutId) {
                clearTimeout(this.recordingTimeoutId);
                this.recordingTimeoutId = null;
            }
            if (this.maxRecordMs > 0) {
                this.recordingTimeoutId = setTimeout(() => {
                    if (this.isRecording) {
                        this.stopRecording();
                    }
                }, this.maxRecordMs);
            }

            const startedDirectUpload = await this.tryStartDirectUpload(sentenceNumber);
            if (startedDirectUpload) {
                this.isStartingRecording = false;
                return;
            }

            if (!this.recognitionSupported) {
                this.stopRecording();
                this.isStartingRecording = false;
                if (window.showNotification) window.showNotification(this.messages.browser_title, 'error');
                return;
            }

            this.recognition = this.initRecognition();
            if (!this.recognition) {
                this.stopRecording();
                this.isStartingRecording = false;
                if (window.showNotification) window.showNotification(this.messages.browser_title, 'error');
                return;
            }

            try {
                this.recognition.start();
                setTimeout(() => {
                    this.isStartingRecording = false;
                }, 500);
            } catch (e) {
                console.error('Recognition start error:', e);
                this.stopRecording();
                this.isStartingRecording = false;
                if (window.showNotification) window.showNotification(this.messages.start_failed, 'error');
            }
        },

        async tryStartDirectUpload(sentenceNumber) {
            if (!this.mediaRecorderSupported) {
                return false;
            }

            try {
                this.mediaStream = await navigator.mediaDevices.getUserMedia({
                    audio: this.getRecordingAudioConstraints(),
                });

                const mimeType = this.getSupportedMimeType();
                this.mediaRecorder = mimeType
                    ? new MediaRecorder(this.mediaStream, { mimeType })
                    : new MediaRecorder(this.mediaStream);

                this.uploadChunks = [];
                this.uploadMimeType = mimeType || this.mediaRecorder.mimeType || 'audio/webm';
                this.usingDirectUpload = true;
                this.usingStream = false;
                this.parallelTranscript = '';

                this.mediaRecorder.ondataavailable = (event) => {
                    if (!event.data || event.data.size === 0 || !this.usingDirectUpload) {
                        return;
                    }
                    this.uploadChunks.push(event.data);
                };

                this.startSilenceMonitor(this.mediaStream, sentenceNumber);
                this.mediaRecorder.start(500);

                // Start Web Speech API in parallel for free transcription
                this.startParallelRecognition(sentenceNumber);

                return true;
            } catch (error) {
                console.error('Direct upload recorder start failed:', error);
                this.cleanupStreamingResources();
                return false;
            }
        },

        startParallelRecognition(sentenceNumber) {
            if (!this.recognitionSupported) {
                return;
            }

            try {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    return;
                }

                const recognition = new SpeechRecognition();
                recognition.lang = 'en-US';
                recognition.interimResults = true;
                recognition.continuous = true;
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

                    const transcript = (finalTranscript || interimTranscript).trim();
                    if (transcript) {
                        this.parallelTranscript = transcript;
                        this.liveTranscript = transcript;

                        // Update live word diff for visual feedback
                        if (this.activeSentence === sentenceNumber && !this.compareInFlight) {
                            this.handleLiveTranscript(sentenceNumber, transcript);
                        }
                    }
                };

                recognition.onend = () => {
                    // If still recording and no transcript yet, try to restart
                    if (this.usingDirectUpload && this.isRecording && !this.manualStop) {
                        const elapsed = this.recordingStartedAt ? (Date.now() - this.recordingStartedAt) : 0;
                        if (this.maxRecordMs <= 0 || elapsed < (this.maxRecordMs - 500)) {
                            try {
                                recognition.start();
                            } catch (e) {
                                // Ignore restart errors
                            }
                        }
                    }
                };

                recognition.onerror = (event) => {
                    // Silently ignore errors - we have the audio file as backup
                    if (event.error !== 'aborted' && event.error !== 'no-speech') {
                        console.warn('Parallel recognition error (non-fatal):', event.error);
                    }
                };

                this.parallelRecognition = recognition;
                recognition.start();
            } catch (error) {
                console.warn('Could not start parallel recognition:', error);
                this.parallelRecognition = null;
            }
        },

        stopParallelRecognition() {
            if (this.parallelRecognition) {
                try {
                    this.parallelRecognition.stop();
                } catch (e) {
                    // Ignore stop errors
                }
                this.parallelRecognition = null;
            }
        },

        async tryStartStreaming(sentenceNumber, options = {}) {
            if (!this.mediaRecorderSupported) {
                return false;
            }

            this.streamLiveCompare = options.liveCompare !== false;

            let streamSession = null;

            try {
                const controller = new AbortController();
                const timeout = setTimeout(() => controller.abort(), 5000);

                const startResponse = await fetch(this.endpoints.streamStart, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        sentence_number: sentenceNumber,
                    }),
                    signal: controller.signal,
                });

                clearTimeout(timeout);
                streamSession = await startResponse.json();
                if (!startResponse.ok || !streamSession.success) {
                    return false;
                }
            } catch (error) {
                console.error('Stream start failed:', error);
                return false;
            }

            this.streamEnabled = !!streamSession.stream_enabled;
            this.streamSessionId = streamSession.session_id || null;
            this.streamChunkMs = Number(streamSession.chunk_ms || 1000);

            if (!this.streamEnabled) {
                this.streamSessionId = null;
                // Don't show notification here — silently fall back to SpeechRecognition
                return false;
            }

            try {
                await this.startMediaStream(sentenceNumber, streamSession.ws_url, streamSession.ws_token);
                return true;
            } catch (error) {
                console.error('Stream init failed:', error);
                this.cleanupStreamingResources();
                this.streamSessionId = null;
                // Silently fall back — don't show confusing notification
                return false;
            }
        },

        async startMediaStream(sentenceNumber, wsUrl, wsToken) {
            this.mediaStream = await navigator.mediaDevices.getUserMedia({
                audio: this.getRecordingAudioConstraints(),
            });

            const mimeType = this.getSupportedMimeType();
            this.mediaRecorder = mimeType
                ? new MediaRecorder(this.mediaStream, { mimeType })
                : new MediaRecorder(this.mediaStream);
            this.usingStream = true;
            this.startSilenceMonitor(this.mediaStream, sentenceNumber);

            this.connectStreamSocket(wsUrl, wsToken, sentenceNumber);

            this.mediaRecorder.ondataavailable = async (event) => {
                if (!event.data || event.data.size === 0 || !this.usingStream) {
                    return;
                }

                try {
                    const audioBase64 = await this.blobToBase64(event.data);
                    this.sendSocketPayload({
                        type: 'audio_chunk',
                        session_id: this.streamSessionId,
                        sentence_number: sentenceNumber,
                        mime_type: event.data.type || mimeType || 'audio/webm',
                        chunk_ms: this.streamChunkMs,
                        audio_base64: audioBase64,
                        client_ts: Date.now(),
                    });
                } catch (error) {
                    console.error('Chunk encode failed:', error);
                }
            };

            this.mediaRecorder.start(this.streamChunkMs);
        },

        startSilenceMonitor(stream, sentenceNumber) {
            this.stopSilenceMonitor();
            const autoStopConfig = this.getAutoStopConfig(sentenceNumber);

            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) {
                    return;
                }

                this.audioContext = new AudioContextClass();
                this.analyserNode = this.audioContext.createAnalyser();
                this.analyserNode.fftSize = 2048;
                this.analyserNode.smoothingTimeConstant = 0.25;
                this.analyserSource = this.audioContext.createMediaStreamSource(stream);
                this.analyserSource.connect(this.analyserNode);
                this.analyserData = new Uint8Array(this.analyserNode.fftSize);

                const tick = () => {
                    if (!this.isRecording || this.activeSentence !== sentenceNumber || !this.analyserNode || !this.analyserData) {
                        this.silenceMonitorId = null;
                        return;
                    }

                    this.analyserNode.getByteTimeDomainData(this.analyserData);

                    let sumSquares = 0;
                    for (let i = 0; i < this.analyserData.length; i++) {
                        const normalized = (this.analyserData[i] - 128) / 128;
                        sumSquares += normalized * normalized;
                    }

                    const volume = Math.sqrt(sumSquares / this.analyserData.length);
                    const now = Date.now();

                    if (volume >= this.autoStopThreshold) {
                        this.speechDetected = true;
                        this.speechDetectedAt ??= now;
                        this.silenceDetectedAt = null;
                    } else if (this.speechDetected) {
                        this.silenceDetectedAt ??= now;

                        const speechDuration = this.speechDetectedAt ? (now - this.speechDetectedAt) : 0;
                        const silenceDuration = now - this.silenceDetectedAt;
                        const spokenWords = String(this.liveTranscript || '')
                            .trim()
                            .split(/\s+/)
                            .filter(Boolean)
                            .length;
                        const enoughContent = this.usingDirectUpload
                            || autoStopConfig.minSpokenWordsBeforeStop <= 0
                            || spokenWords >= autoStopConfig.minSpokenWordsBeforeStop;

                        if (!this.autoStopTriggered
                            && speechDuration >= autoStopConfig.minSpeechMs
                            && silenceDuration >= autoStopConfig.silenceMs
                            && enoughContent) {
                            this.autoStopTriggered = true;
                            this.stopRecording(true);
                            this.silenceMonitorId = null;
                            return;
                        }
                    }

                    this.silenceMonitorId = window.requestAnimationFrame(tick);
                };

                this.silenceMonitorId = window.requestAnimationFrame(tick);
            } catch (error) {
                console.error('Silence monitor start failed:', error);
                this.stopSilenceMonitor();
            }
        },

        stopSilenceMonitor() {
            if (this.silenceMonitorId) {
                window.cancelAnimationFrame(this.silenceMonitorId);
                this.silenceMonitorId = null;
            }

            if (this.analyserSource) {
                try {
                    this.analyserSource.disconnect();
                } catch (error) {
                    console.error('Analyser source disconnect failed:', error);
                }
            }

            if (this.analyserNode) {
                try {
                    this.analyserNode.disconnect();
                } catch (error) {
                    console.error('Analyser disconnect failed:', error);
                }
            }

            if (this.audioContext && this.audioContext.state !== 'closed') {
                this.audioContext.close().catch(() => {});
            }

            this.audioContext = null;
            this.analyserNode = null;
            this.analyserData = null;
            this.analyserSource = null;
            this.speechDetectedAt = null;
            this.silenceDetectedAt = null;
            this.speechDetected = false;
        },

        connectStreamSocket(wsUrl, wsToken, sentenceNumber) {
            if (!wsUrl) {
                return;
            }

            try {
                const separator = wsUrl.includes('?') ? '&' : '?';
                const socketUrl = wsToken ? `${wsUrl}${separator}token=${encodeURIComponent(wsToken)}` : wsUrl;
                this.streamSocket = new WebSocket(socketUrl);
            } catch (error) {
                console.error('Socket creation failed:', error);
                this.streamSocket = null;
                return;
            }

            this.streamSocket.onopen = () => {
                this.sendSocketPayload({
                    type: 'start',
                    session_id: this.streamSessionId,
                    sentence_number: sentenceNumber,
                    expected_text: this.sentences[sentenceNumber] || '',
                });
            };

            this.streamSocket.onmessage = async (event) => {
                try {
                    const payload = JSON.parse(event.data);
                    const transcript = (payload.partial_transcript ?? payload.transcript ?? '').trim();

                    if (transcript) {
                        this.liveTranscript = transcript;

                        if (payload.transcript) {
                            this.pendingFinalTranscript = transcript;
                            if (this.streamStopResolver) {
                                this.streamStopResolver(payload);
                                this.streamStopResolver = null;
                            }
                        }

                        if (this.streamLiveCompare) {
                            await this.handleLiveTranscript(sentenceNumber, transcript, payload.word_diff || null);
                        }
                    }

                    if (typeof payload.latency_ms === 'number') {
                        this.latestLatencyMs = payload.latency_ms;
                    }
                } catch (error) {
                    console.error('Socket message parse failed:', error);
                }
            };

            this.streamSocket.onerror = (error) => {
                console.error('Socket error:', error);
            };

            this.streamSocket.onclose = () => {
                if (this.streamStopResolver) {
                    this.streamStopResolver(null);
                    this.streamStopResolver = null;
                }
            };
        },

        sendSocketPayload(payload) {
            if (!this.streamSocket || this.streamSocket.readyState !== WebSocket.OPEN) {
                return;
            }

            this.streamSocket.send(JSON.stringify(payload));
        },

        getRecordingAudioConstraints() {
            return {
                channelCount: 1,
                echoCancellation: false,
                noiseSuppression: false,
                autoGainControl: false,
            };
        },

        getSupportedMimeType() {
            const candidates = [
                'audio/webm;codecs=opus',
                'audio/webm',
                'audio/mp4',
                'audio/aac',
            ];

            for (const candidate of candidates) {
                if (window.MediaRecorder && window.MediaRecorder.isTypeSupported(candidate)) {
                    return candidate;
                }
            }

            return '';
        },

        fileExtensionFromMime(mimeType) {
            const type = String(mimeType || '').toLowerCase();

            if (type.includes('webm')) return 'webm';
            if (type.includes('mp4') || type.includes('m4a')) return 'm4a';
            if (type.includes('wav')) return 'wav';
            if (type.includes('mpeg') || type.includes('mp3')) return 'mp3';
            if (type.includes('ogg')) return 'ogg';

            return 'webm';
        },

        async parseResponsePayload(response) {
            const contentType = String(response.headers.get('content-type') || '').toLowerCase();
            const rawText = await response.text();

            if (!rawText) {
                return { data: null, rawText: '', isJson: false };
            }

            if (contentType.includes('application/json')) {
                try {
                    return {
                        data: JSON.parse(rawText),
                        rawText,
                        isJson: true,
                    };
                } catch (error) {
                    console.error('JSON parse failed:', error);
                }
            }

            return {
                data: null,
                rawText,
                isJson: false,
            };
        },

        responseErrorMessage(response, payload) {
            const bodyError = payload?.data?.error
                || payload?.data?.message
                || '';

            if (bodyError) {
                return bodyError;
            }

            if (response.status === 413) {
                return this.messages.upload_too_large || 'Uploaded audio is too large. Please record a shorter answer.';
            }

            if (response.status === 419) {
                return this.messages.session_expired || 'Your session expired. Refresh the page and try again.';
            }

            if (response.status >= 500) {
                return this.messages.server_error || 'Server error while analyzing the audio.';
            }

            if (payload?.rawText) {
                return payload.rawText.slice(0, 180);
            }

            return this.messages.evaluation_failed;
        },

        uploadStatusUrl(token) {
            return `${this.endpoints.uploadStatusBase}/${encodeURIComponent(String(token || '').trim())}`;
        },

        clearProcessingPoll() {
            if (this.processingPollTimeoutId) {
                clearTimeout(this.processingPollTimeoutId);
                this.processingPollTimeoutId = null;
            }

            this.processingToken = null;
        },

        startProcessingPoll(sentenceNumber, token) {
            this.clearProcessingPoll();
            this.processingToken = token;
            this.isEvaluating = true;
            this.evaluatingSentence = sentenceNumber;

            const poll = async () => {
                try {
                    const response = await fetch(this.uploadStatusUrl(token), {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const payload = await this.parseResponsePayload(response);
                    const data = payload.data;

                    if (response.ok && data?.status === 'processing') {
                        this.processingPollTimeoutId = window.setTimeout(poll, 2000);
                        return;
                    }

                    this.clearProcessingPoll();

                    if (response.ok && data?.status === 'completed' && data?.success) {
                        this.applyResult(sentenceNumber, data);
                        this.isEvaluating = false;
                        this.evaluatingSentence = null;
                        return;
                    }

                    const errorMessage = this.responseErrorMessage(response, payload);
                    if (window.showNotification) {
                        window.showNotification(errorMessage, 'error');
                    }
                } catch (error) {
                    console.error('Pronunciation status poll failed:', error);
                    this.processingPollTimeoutId = window.setTimeout(poll, 2500);
                    return;
                }

                this.isEvaluating = false;
                this.evaluatingSentence = null;
            };

            this.processingPollTimeoutId = window.setTimeout(poll, 1500);
        },

        blobToBase64(blob) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    const result = String(reader.result || '');
                    const commaIndex = result.indexOf(',');
                    resolve(commaIndex >= 0 ? result.slice(commaIndex + 1) : result);
                };
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            });
        },

        async handleLiveTranscript(sentenceNumber, transcript, providedDiff = null) {
            if (this.activeSentence !== sentenceNumber || !transcript) {
                return;
            }

            this.liveTranscript = transcript;

            if (Array.isArray(providedDiff) && providedDiff.length) {
                this.liveWordDiff[sentenceNumber] = providedDiff;
                return;
            }

            if (this.compareInFlight) {
                this.pendingCompareTranscript = transcript;
                return;
            }

            this.compareInFlight = true;

            try {
                const response = await fetch(this.endpoints.streamCompare, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        sentence_number: sentenceNumber,
                        transcript: transcript,
                    }),
                });

                const data = await response.json();
                if (response.ok && data.success && Array.isArray(data.word_diff)) {
                    this.liveWordDiff[sentenceNumber] = data.word_diff;
                }
            } catch (error) {
                console.error('Live compare failed:', error);
            } finally {
                this.compareInFlight = false;
            }

            if (this.pendingCompareTranscript && this.pendingCompareTranscript !== transcript) {
                const queuedTranscript = this.pendingCompareTranscript;
                this.pendingCompareTranscript = null;
                await this.handleLiveTranscript(sentenceNumber, queuedTranscript);
            }
        },

        stopRecording(force = false) {
            this.manualStop = true;
            this.stopSilenceMonitor();

            if (this.usingDirectUpload) {
                this.stopDirectUploadMode();
                return;
            }

            if (this.usingStream) {
                this.stopStreamingMode();
                return;
            }

            const hadRecognition = !!this.recognition;
            const finishedSentence = this.activeSentence;

            if (this.recognition) {
                try {
                    this.recognition.stop();
                } catch (e) {
                    console.error('Recognition stop error:', e);
                }
            }

            // Always clean up state even if recognition.stop() throws
            this.isRecording = false;
            this.isStartingRecording = false;
            this.recognition = null;
            this.activeSentence = null;
            this.recordingStartedAt = null;
            if (this.recordingTimeoutId) {
                clearTimeout(this.recordingTimeoutId);
                this.recordingTimeoutId = null;
            }

            // If there was no recognition (e.g., failed to start), submit whatever we have
            if (!hadRecognition && finishedSentence) {
                const transcript = this.liveTranscript.trim();
                if (transcript) {
                    this.submitTranscript(finishedSentence, transcript);
                }
            }
            // If there was recognition, onend handler will handle transcript submission
        },

        async stopStreamingMode() {
            const sentenceNumber = this.activeSentence;
            const durationSeconds = this.recordingStartedAt
                ? Math.max(0, Math.round((Date.now() - this.recordingStartedAt) / 1000))
                : 0;

            await this.stopMediaRecorderGracefully();
            const finalPayload = await this.requestFinalStreamPayload();
            const transcript = String(finalPayload?.transcript || this.pendingFinalTranscript || this.liveTranscript || '').trim();

            this.cleanupStreamingResources();
            this.isRecording = false;
            this.isStartingRecording = false;
            this.activeSentence = null;
            this.recordingStartedAt = null;
            this.minStopAt = 0;
            if (this.recordingTimeoutId) {
                clearTimeout(this.recordingTimeoutId);
                this.recordingTimeoutId = null;
            }

            if (sentenceNumber && transcript) {
                await this.finalizeStreamTranscript(sentenceNumber, transcript, durationSeconds);
            } else if (window.showNotification) {
                window.showNotification(this.messages.no_speech, 'warning');
            }
        },

        async stopDirectUploadMode() {
            const sentenceNumber = this.activeSentence;
            const durationSeconds = this.recordingStartedAt
                ? Math.max(0, Math.round((Date.now() - this.recordingStartedAt) / 1000))
                : 0;

            // Stop parallel recognition and capture final transcript
            this.stopParallelRecognition();
            await this.stopMediaRecorderGracefully();

            const audioChunks = this.uploadChunks.slice();
            const audioMimeType = this.uploadMimeType || this.getSupportedMimeType() || 'audio/webm';

            this.cleanupStreamingResources();
            this.isRecording = false;
            this.isStartingRecording = false;
            this.activeSentence = null;
            this.recordingStartedAt = null;
            this.minStopAt = 0;
            if (this.recordingTimeoutId) {
                clearTimeout(this.recordingTimeoutId);
                this.recordingTimeoutId = null;
            }

            if (sentenceNumber && audioChunks.length) {
                const audioBlob = new Blob(audioChunks, { type: audioMimeType });
                await this.submitUploadedAudio(sentenceNumber, audioBlob, durationSeconds);
                return;
            }

            if (window.showNotification) {
                window.showNotification(this.messages.no_speech, 'warning');
            }
        },

        async submitUploadedAudio(sentenceNumber, audioBlob, durationSeconds) {
            this.isEvaluating = true;
            this.evaluatingSentence = sentenceNumber;
            let shouldKeepEvaluating = false;

            try {
                const formData = new FormData();
                formData.append('exercise_id', String(this.exerciseId));
                formData.append('sentence_number', String(sentenceNumber));
                formData.append('duration_seconds', String(durationSeconds || 0));
                formData.append('provider', 'media_upload');

                // Final scoring should use server-side transcription for better accuracy.
                // Browser transcript stays only for live visual hints while recording.

                const extension = this.fileExtensionFromMime(audioBlob.type || this.uploadMimeType || '');
                const fileName = `pronunciation-${Date.now()}.${extension}`;
                formData.append('audio', audioBlob, fileName);

                const response = await fetch(this.endpoints.upload, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const payload = await this.parseResponsePayload(response);
                const data = payload.data;
                if (!response.ok || !data?.success) {
                    const errorMessage = this.responseErrorMessage(response, payload);
                    console.error('Upload pronunciation request failed:', {
                        status: response.status,
                        payload: payload.rawText,
                    });
                    if (window.showNotification) {
                        window.showNotification(errorMessage, 'error');
                    }
                    return;
                }

                if (data?.status === 'processing' && data?.upload_token) {
                    shouldKeepEvaluating = true;
                    this.startProcessingPoll(sentenceNumber, data.upload_token);
                    return;
                }

                this.applyResult(sentenceNumber, data);
            } catch (error) {
                console.error('Upload pronunciation failed:', error);
                if (window.showNotification) {
                    window.showNotification(this.messages.network_error, 'error');
                }
            } finally {
                if (!shouldKeepEvaluating) {
                    this.isEvaluating = false;
                    this.evaluatingSentence = null;
                }
            }
        },

        stopMediaRecorderGracefully() {
            return new Promise((resolve) => {
                if (!this.mediaRecorder || this.mediaRecorder.state === 'inactive') {
                    resolve();
                    return;
                }

                const recorder = this.mediaRecorder;
                const finish = () => {
                    recorder.onstop = null;
                    resolve();
                };

                recorder.onstop = finish;

                try {
                    recorder.stop();
                } catch (error) {
                    console.error('MediaRecorder stop failed:', error);
                    finish();
                }
            });
        },

        requestFinalStreamPayload() {
            return new Promise((resolve) => {
                let settled = false;
                const finish = (payload = null) => {
                    if (settled) {
                        return;
                    }
                    settled = true;
                    if (timeoutId) {
                        clearTimeout(timeoutId);
                    }
                    this.streamStopResolver = null;
                    this.closeStreamSocket();
                    resolve(payload);
                };

                const timeoutMs = this.safeRecordingMode
                    ? Math.max(this.stopPayloadTimeoutMs, 12000)
                    : this.stopPayloadTimeoutMs;
                const timeoutId = setTimeout(() => finish(null), timeoutMs);
                this.streamStopResolver = finish;

                this.sendSocketPayload({
                    type: 'stop',
                    session_id: this.streamSessionId,
                });

                if (!this.streamSocket || this.streamSocket.readyState !== WebSocket.OPEN) {
                    finish(null);
                }
            });
        },

        closeStreamSocket() {
            if (!this.streamSocket) {
                return;
            }

            try {
                this.streamSocket.close();
            } catch (error) {
                console.error('Socket close failed:', error);
            }
        },

        cleanupStreamingResources() {
            this.stopSilenceMonitor();
            this.stopParallelRecognition();

            if (this.mediaStream) {
                this.mediaStream.getTracks().forEach((track) => track.stop());
            }

            this.mediaStream = null;
            this.mediaRecorder = null;
            this.streamSocket = null;
            this.usingStream = false;
            this.usingDirectUpload = false;
            this.uploadChunks = [];
            this.uploadMimeType = '';
            this.streamEnabled = false;
            this.streamLiveCompare = !this.safeRecordingMode;
            this.parallelTranscript = '';
        },

        async finalizeStreamTranscript(sentenceNumber, transcript, durationSeconds) {
            this.isEvaluating = true;
            this.evaluatingSentence = sentenceNumber;

            try {
                const response = await fetch(this.endpoints.streamFinalize, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        session_id: this.streamSessionId,
                        sentence_number: sentenceNumber,
                        transcript: transcript,
                        duration_seconds: durationSeconds,
                        latency_ms: this.latestLatencyMs,
                        provider: 'streaming',
                    }),
                });

                const payload = await this.parseResponsePayload(response);
                const data = payload.data;
                if (!response.ok || !data?.success) {
                    const errorMessage = this.responseErrorMessage(response, payload);
                    if (window.showNotification) {
                        window.showNotification(errorMessage, 'error');
                    }
                    return;
                }

                this.applyResult(sentenceNumber, data);
            } catch (error) {
                console.error('Finalize stream failed:', error);
                if (window.showNotification) window.showNotification(this.messages.network_error, 'error');
            } finally {
                this.isEvaluating = false;
                this.evaluatingSentence = null;
                this.streamSessionId = null;
                this.latestLatencyMs = null;
            }
        },

        async submitTranscript(sentenceNumber, transcript) {
            this.isEvaluating = true;
            this.evaluatingSentence = sentenceNumber;

            try {
                const res = await fetch(this.endpoints.evaluate, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        transcript: transcript,
                        sentence_number: sentenceNumber,
                    }),
                });

                const payload = await this.parseResponsePayload(res);
                const data = payload.data;

                if (data?.success) {
                    this.applyResult(sentenceNumber, data);
                } else if (window.showNotification) {
                    window.showNotification(this.responseErrorMessage(res, payload), 'error');
                }
            } catch {
                if (window.showNotification) window.showNotification(this.messages.network_error, 'error');
            }

            this.isEvaluating = false;
            this.evaluatingSentence = null;
        },

        applyResult(sentenceNumber, data) {
            this.results[sentenceNumber] = data;
            this.liveWordDiff[sentenceNumber] = Array.isArray(data.word_diff) ? data.word_diff : [];
            window.setTimeout(() => this.speakFeedback(sentenceNumber), 150);

            if (data.score >= this.passingScore) {
                this.failedAttempts[sentenceNumber] = 0;
                if (window.showNotification) window.showNotification(this.messages.great_prefix + data.score + '%', 'success');
                return;
            }

            if (!this.failedAttempts[sentenceNumber]) {
                this.failedAttempts[sentenceNumber] = 0;
            }

            this.failedAttempts[sentenceNumber]++;

            if (this.failedAttempts[sentenceNumber] >= 2) {
                if (window.showNotification) window.showNotification(this.messages.listen_below, 'info');
            } else if (window.showNotification) {
                window.showNotification(this.messages.try_again, 'warning');
            }
        },

        playReferenceOrTts(sentenceNumber) {
            this.stopExamplePlayback();

            const url = this.referenceAudio[sentenceNumber];
            const text = this.sentences[sentenceNumber];

            if (url) {
                this.playAudio(url, text, sentenceNumber);
                return;
            }

            this.speakCorrect(sentenceNumber, text);
        },

        speakCorrect(sentenceNumber, text, lang = 'en-US') {
            if (!('speechSynthesis' in window)) {
                if (window.showNotification) window.showNotification(this.messages.tts_unsupported, 'error');
                return;
            }

            this.stopExamplePlayback();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = lang;
            utterance.rate = 0.85;
            utterance.pitch = 1;
            utterance.volume = 1;

            const voices = window.speechSynthesis.getVoices();
            const preferredVoice = voices.find((voice) => voice.lang === lang && voice.name.includes('Google'))
                || voices.find((voice) => voice.lang === lang && voice.name.includes('Microsoft'))
                || voices.find((voice) => voice.lang === lang)
                || voices.find((voice) => voice.lang.startsWith(lang.split('-')[0]));

            if (preferredVoice) utterance.voice = preferredVoice;

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
            this.stopExamplePlayback();

            const audio = new Audio(url);
            audio.preload = 'auto';
            audio.playsInline = true;
            this.currentAudio = audio;
            audio.onended = () => {
                if (this.currentAudio === audio) {
                    this.currentAudio = null;
                }
            };
            audio.play().catch(() => {
                if (this.currentAudio === audio) {
                    this.currentAudio = null;
                }
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
