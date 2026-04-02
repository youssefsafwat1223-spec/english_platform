@extends('layouts.app')

@section('title', $lesson->title . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        <x-student.page-header
            title="{{ $lesson->title }}"
            subtitle="{{ $course->title }}"
            badge="{{ $isArabic ? 'الدرس' : 'Lesson' }}"
            badgeColor="primary"
            badgeIcon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>'
        >
            <x-slot name="actions">
                <a href="{{ route('student.courses.learn', $course) }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $isArabic ? 'العودة للكورس' : 'Back to course' }}
                </a>
            </x-slot>
        </x-student.page-header>
        
        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm font-medium" data-aos="fade-down">
            <ol class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <li>
                    <a href="{{ route('student.courses.my-courses') }}" class="hover:text-primary-500 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ __('كورساتي') }}
                    </a>
                </li>
                <li class="opacity-50">/</li>
                <li>
                    <a href="{{ route('student.courses.learn', $course) }}" class="hover:text-primary-500 transition-colors truncate max-w-[200px] sm:max-w-[250px] md:max-w-none inline-block align-bottom">
                        {{ $course->title }}
                    </a>
                </li>
                <li class="opacity-50">/</li>
                <li class="text-slate-900 dark:text-white font-bold truncate max-w-[200px] sm:max-w-[400px] md:max-w-none inline-block align-bottom">
                    {{ $lesson->title }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            {{-- Main content column --}}
            <div class="xl:col-span-3 space-y-8">
                
                {{-- Title & Video Section --}}
                <x-student.card padding="p-0" class="overflow-hidden border border-slate-200/50 dark:border-white/10" data-aos="fade-up">
                    <div class="p-6 md:p-8 md:pb-6 bg-slate-50/50 dark:bg-slate-900/20 border-b border-slate-200/50 dark:border-white/5">
                        <h1 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight mb-2 tracking-tight">
                            {{ $lesson->title }}
                        </h1>
                    </div>
                    
                    @if($lesson->isVdoCipherVideo() && $vdoCipherOtp && $vdoCipherPlaybackInfo)
                        {{-- VdoCipher DRM protected player works on all supported devices and browsers --}}
                        {{-- iOS Safari = FairPlay DRM | Android Chrome = Widevine DRM | Desktop = Widevine/FairPlay --}}
                        <div class="p-2 sm:p-4 bg-slate-900">
                            <div class="aspect-video bg-black rounded-[1.5rem] overflow-hidden shadow-2xl relative" id="vdo-player-container">
                                {{-- Loading spinner shown until the iframe loads --}}
                                <div id="vdo-loading" class="absolute inset-0 flex items-center justify-center bg-black z-10">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-10 h-10 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-white font-bold text-sm tracking-wider">{{ __('جاري تحميل الفيديو...') }}</span>
                                    </div>
                                </div>
                                <iframe
                                    id="vdo-iframe"
                                    src="https://player.vdocipher.com/v2/?otp={{ $vdoCipherOtp }}&playbackInfo={{ $vdoCipherPlaybackInfo }}"
                                    style="border: 0; width: 100%; height: 100%; position: absolute; top: 0; left: 0;"
                                    allow="encrypted-media; autoplay; fullscreen"
                                    allowfullscreen
                                    webkitallowfullscreen
                                    mozallowfullscreen
                                    playsinline
                                    onload="document.getElementById('vdo-loading').style.display='none';"
                                ></iframe>
                            </div>
                        </div>
                        <script>
                            // VdoCipher iframe progress tracking via postMessage API
                            document.addEventListener('DOMContentLoaded', function() {
                                let lastReportedTime = 0;
                                const progressUrl = '{{ route('student.lessons.update-progress', [$course, $lesson]) }}';

                                window.addEventListener('message', function(e) {
                                    // Accept messages from VdoCipher player only
                                    if (e.origin !== 'https://player.vdocipher.com') return;
                                    
                                    try {
                                        const data = typeof e.data === 'string' ? JSON.parse(e.data) : e.data;
                                        
                                        // Track progress every 15 seconds
                                        if (data.currentTime && data.currentTime - lastReportedTime > 15) {
                                            lastReportedTime = data.currentTime;
                                            axios.post(progressUrl, {
                                                position: Math.floor(data.currentTime),
                                                time_spent: 15,
                                                duration: data.duration || 0
                                            }).catch(function() {});
                                        }

                                        // Track video ended
                                        if ((data.event === 'ended' || data.ended) && (!lessonCompletionRules.requiresQuizPass || lessonCompletionRules.hasPassedQuiz)) {
                                            axios.post(progressUrl, {
                                                position: Math.floor(data.duration || data.currentTime || 0),
                                                time_spent: Math.floor((data.currentTime || 0) - lastReportedTime),
                                                duration: data.duration || 0,
                                                is_completed: true
                                            }).catch(function() {});
                                        }
                                    } catch(err) {
                                        // Ignore non-VdoCipher messages
                                    }
                                });
                            });
                        </script>
                    @elseif($lesson->video_url || $lesson->video_embed_url)
                        {{-- Legacy video player for YouTube, Vimeo, Drive, and direct links --}}
                        <div class="p-2 sm:p-4 bg-slate-900">
                            <div class="aspect-video bg-black rounded-[1.5rem] overflow-hidden shadow-2xl relative group">
                                @if($lesson->video_embed_url)
                                    @php
                                        // Obfuscate the embed URL with Base64 so it is not plainly visible in page source.
                                        $encodedUrl = base64_encode($lesson->video_embed_url);
                                    @endphp
                                    <div class="relative w-full h-full rounded-[1.5rem] overflow-hidden">
                                        {{-- Store the encoded URL in data-esrc instead of the iframe src --}}
                                        <iframe id="protected-iframe" class="w-full h-full absolute inset-0 bg-slate-900" title="{{ $lesson->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen data-esrc="{{ $encodedUrl }}"></iframe>
                                        
                                        {{-- Decode and assign the URL after a short delay to discourage simple scraping --}}
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                setTimeout(function() {
                                                    var iframe = document.getElementById('protected-iframe');
                                                    if(iframe) {
                                                        // Decode the protected URL and assign it to src.
                                                        iframe.src = atob(iframe.getAttribute('data-esrc'));
                                                        
                                                        // Block the context menu on the iframe when possible.
                                                        iframe.addEventListener('contextmenu', e => e.preventDefault());
                                                    }
                                                }, 300); // Small delay before setting the iframe source.
                                            });
                                        </script>

                                        {{-- Invisible overlay to block the top-right pop-out button (specifically for Google Drive embeds) ONLY. We must allow clicks on the rest of the video to play/pause --}}
                                        <div class="absolute top-0 right-0 w-20 h-20 bg-transparent z-10 hidden md:block" title="Pop-out disabled" oncontextmenu="return false;"></div>
                                        
                                        {{-- We REMOVED the full-screen transparent overlay because it blocks PLAY/PAUSE clicks. --}}
                                        
                                        {{-- Dynamic Watermark (Anti-Screen Record Deterrent) --}}
                                        <div id="dynamic-watermark" class="absolute z-20 pointer-events-none opacity-40 mix-blend-overlay text-white/50 text-sm md:text-base font-bold tracking-widest uppercase transition-all duration-[6000ms] ease-in-out" style="top: 10%; left: 10%; user-select: none; text-shadow: 0 0 4px rgba(0,0,0,0.8);">
                                            {{ auth()->user()->name }} 
                                            @if(auth()->user()->phone)
                                                &bull; {{ auth()->user()->phone }}
                                            @endif
                                            <br>
                                            <span class="text-xs opacity-50">{{ request()->ip() }}</span>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const watermark = document.getElementById('dynamic-watermark');
                                                if(watermark) {
                                                    setInterval(() => {
                                                        // Move watermark randomly within 10% to 80% of width/height to keep it on screen
                                                        const randomTop = Math.floor(Math.random() * 70) + 10;
                                                        const randomLeft = Math.floor(Math.random() * 70) + 10;
                                                        
                                                        watermark.style.top = randomTop + '%';
                                                        watermark.style.left = randomLeft + '%';
                                                    }, 5000); // Change position every 5 seconds
                                                }
                                            });
                                        </script>
                                    </div>
                                @else
                                    {{-- Video source hidden via Blob & right-click disabled --}}
                                    <video id="lessonVideo" class="w-full h-full object-contain absolute inset-0 rounded-[1.5rem]" 
                                           controls playsinline preload="metadata" 
                                           controlsList="nodownload" oncontextmenu="return false;"
                                           data-src="{{ $lesson->video_url }}">
                                        {{ __('المتصفح لا يدعم تشغيل الفيديو.') }}
                                    </video>
                                    
                                    {{-- Loading overlay while fetching Blob --}}
                                    <div id="videoLoaderOverlay" class="absolute inset-0 flex items-center justify-center bg-black/80 z-20">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-10 h-10 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span class="text-white font-bold text-sm tracking-wider">{{ __('جاري تجهيز الفيديو...') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($lesson->description || $lesson->text_content)
                        <div class="p-6 md:p-8 prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                            @if($lesson->description)
                                <div class="text-lg font-medium leading-relaxed mb-6">{!! nl2br(e($lesson->description)) !!}</div>
                            @endif
                            @if($lesson->text_content)
                                @php
                                    $safeContent = nl2br(e($lesson->text_content));
                                    $safeContent = preg_replace_callback(
                                        '/\[IMG:([a-zA-Z0-9_\-]+\.(png|jpg|jpeg|webp))\]/',
                                        function (array $matches) {
                                            $filename = $matches[1];
                                            $imageUrl = asset('images/features/' . $filename);

                                            return '<div class="my-6 rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10">'
                                                . '<img src="' . e($imageUrl) . '" alt="' . e($filename) . '" class="w-full h-auto" loading="lazy">'
                                                . '</div>';
                                        },
                                        $safeContent
                                    );
                                @endphp
                                <div class="leading-relaxed">{!! $safeContent !!}</div>
                            @endif
                        </div>
                    @endif

                    @php
                        $lessonVocabulary = is_array($lesson->pronunciationExercise?->vocabulary_json ?? null)
                            ? $lesson->pronunciationExercise->vocabulary_json
                            : [];
                    @endphp

                    @if(!empty($lessonVocabulary))
                        <div class="px-6 md:px-8 pb-8" data-aos="fade-up">
                            <div class="rounded-[1.5rem] border border-slate-200/70 dark:border-white/10 bg-slate-50/80 dark:bg-white/[0.03] overflow-hidden">
                                <div class="px-5 md:px-6 py-5 border-b border-slate-200/70 dark:border-white/5 flex flex-col sm:flex-row sm:items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h10"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">
                                                {{ app()->getLocale() === 'ar' ? 'كلمات الدرس' : 'Lesson Vocabulary' }}
                                            </h3>
                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                                {{ count($lessonVocabulary) }} {{ app()->getLocale() === 'ar' ? 'كلمات للمراجعة السريعة' : 'words for quick review' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="sm:ml-auto flex items-center gap-2">
                                        <button type="button" onclick="scrollLessonVocabulary(-1)" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 text-slate-600 dark:text-slate-300 hover:text-primary-500 hover:border-primary-500/30 transition-all">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="scrollLessonVocabulary(1)" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 text-slate-600 dark:text-slate-300 hover:text-primary-500 hover:border-primary-500/30 transition-all">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div id="lesson-vocabulary-track" class="flex gap-4 overflow-x-auto px-5 md:px-6 py-6 snap-x snap-mandatory" style="scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none;">
                                    @foreach($lessonVocabulary as $index => $vocab)
                                        <article class="min-w-[270px] sm:min-w-[320px] max-w-[320px] snap-start rounded-[1.5rem] border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/40 p-5 shadow-sm">
                                            <div class="flex items-start justify-between gap-4 mb-5">
                                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-primary-500/10 text-primary-500 font-black text-sm shrink-0">
                                                    {{ $index + 1 }}
                                                </span>
                                                <button type="button" onclick='speakVocabularyWord(@json($vocab['word'] ?? ""))' class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-primary-500 hover:border-primary-500/30 transition-all text-xs font-bold">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5L6 9H2v6h4l5 4V5zm5.54 3.46a5 5 0 010 7.08m2.83-9.91a9 9 0 010 12.74"></path>
                                                    </svg>
                                                    {{ app()->getLocale() === 'ar' ? 'استمع' : 'Listen' }}
                                                </button>
                                            </div>

                                            <div class="space-y-4">
                                                <div>
                                                    <div class="text-[11px] uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 font-black mb-2">
                                                        {{ app()->getLocale() === 'ar' ? 'الكلمة' : 'Word' }}
                                                    </div>
                                                    <div class="text-3xl font-black text-slate-900 dark:text-white tracking-wide" dir="ltr">
                                                        {{ $vocab['word'] ?? '' }}
                                                    </div>
                                                </div>

                                                @if(!empty($vocab['pronunciation']))
                                                    <div class="rounded-2xl bg-primary-500/5 border border-primary-500/10 px-4 py-3">
                                                        <div class="text-[11px] uppercase tracking-[0.2em] text-primary-400 font-black mb-1">
                                                            {{ app()->getLocale() === 'ar' ? 'النطق' : 'Pronunciation' }}
                                                        </div>
                                                        <div class="text-base font-bold text-primary-500" dir="ltr" style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;">
                                                            {{ $vocab['pronunciation'] }}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(!empty($vocab['meaning_ar']))
                                                    <div>
                                                        <div class="text-[11px] uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 font-black mb-2">
                                                            {{ app()->getLocale() === 'ar' ? 'المعنى' : 'Meaning' }}
                                                        </div>
                                                        <p class="text-base font-bold text-slate-700 dark:text-slate-200 leading-relaxed">
                                                            {{ $vocab['meaning_ar'] }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </x-student.card>

                {{-- Audio Player --}}
                @if($lesson->audio)
                    <x-student.card padding="p-0" class="overflow-hidden border border-primary-500/20 shadow-lg shadow-primary-500/5 group" x-data="createAudioPlayer('{{ Storage::url($lesson->audio->audio_path) }}')" data-aos="fade-up">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/5 to-transparent pointer-events-none"></div>
                        <div class="p-6 md:p-8 relative z-10 flex flex-col md:flex-row items-center gap-6">
                            
                            <div class="flex items-center gap-4 shrink-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 text-white flex items-center justify-center shadow-lg shadow-primary-500/30">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 18v-6a9 9 0 0118 0v6"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 14h3a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-5z"></path>
                                    </svg>
                                </div>
                                <div class="md:hidden text-lg font-bold text-slate-900 dark:text-white">{{ __('ملخص صوتي') }}</div>
                            </div>
                            
                            <div class="flex-1 w-full space-y-2">
                                <div class="flex items-center gap-4">
                                    <button @click="toggle()" class="btn-primary ripple-btn w-12 h-12 rounded-full p-0 flex items-center justify-center shrink-0 shadow-md">
                                        <svg x-show="!playing" class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                                        <svg x-show="playing" class="w-5 h-5" x-cloak fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    
                                    <div class="flex-1">
                                        <div class="relative w-full h-2 bg-slate-200 dark:bg-slate-700/50 rounded-full overflow-hidden cursor-pointer">
                                            <input type="range" :value="currentTime" :max="duration" @input="seek($event.target.value)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary-500 to-accent-500 pointer-events-none transition-all duration-150" :style="`width: ${(currentTime / duration) * 100}%`"></div>
                                        </div>
                                        <div class="flex justify-between text-xs font-bold text-slate-500 dark:text-slate-400 mt-2">
                                            <span x-text="Math.floor(currentTime / 60) + ':' + (Math.floor(currentTime % 60)).toString().padStart(2, '0')">0:00</span>
                                            <span x-text="Math.floor(duration / 60) + ':' + (Math.floor(duration % 60)).toString().padStart(2, '0')">0:00</span>
                                        </div>
                                    </div>
                                    
                                    <div class="shrink-0 relative">
                                        <select @change="setSpeed($event.target.value)" class="appearance-none bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl py-2 pl-4 pr-8 text-sm font-bold focus:ring-2 focus:ring-primary-500 cursor-pointer outline-none">
                                            <option value="0.75" class="text-slate-900">0.75x</option>
                                            <option value="1" selected class="text-slate-900">1.0x</option>
                                            <option value="1.25" class="text-slate-900">1.25x</option>
                                            <option value="1.5" class="text-slate-900">1.5x</option>
                                            <option value="2" class="text-slate-900">2.0x</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-student.card>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Attachments --}}
                    @if($lesson->attachments->count() > 0)
                        <x-student.card padding="p-0" class="overflow-hidden" data-aos="fade-up">
                            <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 6.5l-6.79 6.79a3 3 0 104.24 4.24L21 10.48a5 5 0 10-7.07-7.07l-8.13 8.13a7 7 0 109.9 9.9L20 17"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('المرفقات') }}</h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach($lesson->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->file_path) }}" download class="group flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/5 hover:border-blue-500/30 hover:shadow-md hover:bg-white dark:hover:bg-black/20 transition-all duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm text-slate-400 group-hover:text-blue-500 transition-colors border border-slate-200 dark:border-white/5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-sm text-slate-800 dark:text-slate-200 group-hover:text-blue-500 transition-colors line-clamp-1">{{ $attachment->title }}</div>
                                                <div class="text-[11px] font-medium text-slate-500 mt-0.5 uppercase tracking-wide">{{ $attachment->formatted_size }}</div>
                                            </div>
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center translate-x-2 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </x-student.card>
                    @endif

                    {{-- Actions (Quizzes / Pronunciation) --}}
                    <div class="space-y-6">
                        @if($lesson->pronunciationExercise)
                            <x-student.card padding="p-0" class="overflow-hidden relative group" data-aos="fade-up">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent pointer-events-none"></div>
                                <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3 relative z-10">
                                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a3 3 0 013 3v5a3 3 0 11-6 0V6a3 3 0 013-3z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10a7 7 0 01-14 0m7 7v4m-4 0h8"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('تمرين النطق') }}</h3>
                                </div>
                                <div class="p-6 relative z-10">
                                    <p class="text-sm mb-5 text-slate-600 dark:text-slate-400 font-medium">{{ __('حسّن مهاراتك في النطق مع تقييم فوري بالذكاء الاصطناعي.') }}</p>
                                    <a href="{{ route('student.pronunciation.show', $lesson->pronunciationExercise) }}" class="btn-primary ripple-btn w-full justify-center shadow-lg shadow-primary-500/25 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 border-0 flex items-center gap-2">
                                        {{ __('ابدأ التمرين') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                    </a>
                                </div>
                            </x-student.card>
                        @endif

                        @if($completionQuiz)
                            <x-student.card padding="p-0" class="overflow-hidden relative group" data-aos="fade-up">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent pointer-events-none"></div>
                                <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3 relative z-10">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-6 4h6m-7 6h8m-9 4h10a2 2 0 002-2V7.5L14.5 3H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('اختبار') }}</h3>
                                </div>
                                <div class="p-6 relative z-10">
                                    <p class="text-sm mb-5 text-slate-600 dark:text-slate-400 font-medium">{{ __('اجتز الاختبار أولًا حتى يتم احتساب الدرس كمكتمل.') }}</p>
                                    @if($hasPassedCompletionQuiz)
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-500/20 w-full sm:w-auto justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ __('ناجح') }}
                                            </span>
                                            <a href="{{ route('student.quizzes.start', $completionQuiz) }}" class="btn-ghost font-bold text-slate-600 dark:text-slate-300 w-full sm:w-auto text-center hover:text-amber-500">{{ __('إعادة الاختبار') }}</a>
                                        </div>
                                    @else
                                        <a href="{{ route('student.quizzes.start', $completionQuiz) }}" class="btn-primary ripple-btn w-full justify-center shadow-lg shadow-amber-500/25 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 border-0 flex items-center gap-2">
                                            {{ __('ابدأ الاختبار') }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </x-student.card>
                        @endif
                    </div>
                </div>

                {{-- Comments Section --}}
                <x-student.card padding="p-0" class="overflow-hidden border-t-4 border-t-primary-500" id="comments" data-aos="fade-up">
                    <div class="px-8 py-6 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m-9 6l2.4-2.4A2 2 0 016.8 17H18a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-xl text-slate-900 dark:text-white">{{ __('المناقشة') }}</h3>
                                <p class="text-xs font-medium text-slate-500 tracking-wide uppercase">{{ $lesson->comments()->count() }} {{ __('تعليق') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        {{-- New Comment Form --}}
                        <div class="flex items-start gap-4 mb-10">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-md">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <form action="{{ route('student.lessons.comments.store', [$course, $lesson]) }}" method="POST" x-data="{ loading: false, text: '' }" @submit="loading = true">
                                    @csrf
                                    <div class="relative group">
                                        <textarea x-model="text" name="comment_text" rows="3" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-2xl py-3 px-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none shadow-inner" placeholder="{{ __('عندك سؤال؟ شاركه مع الطلاب...') }}" required></textarea>
                                        <div class="absolute bottom-3 right-3 flex items-center gap-2 opacity-0 group-focus-within:opacity-100 transition-opacity">
                                            <button type="button" @click="text = ''" x-show="text.length > 0" class="btn-ghost btn-sm text-slate-400 hover:text-rose-500 h-8 px-3 rounded-lg">{{ __('إلغاء') }}</button>
                                            <button type="submit" class="btn-primary ripple-btn h-8 px-4 py-0 rounded-lg shadow-md flex items-center gap-1.5" :disabled="loading || text.length === 0">
                                                <span x-show="!loading" class="font-bold text-xs">{{ __('نشر') }}</span>
                                                <span x-show="loading" x-cloak class="font-bold text-xs">{{ __('جاري النشر...') }}</span>
                                                <svg x-show="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Comment List --}}
                        <div class="space-y-8">
                            @forelse($lesson->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                <div class="group relative">
                                    <div class="absolute top-10 bottom-0 left-5 w-px bg-slate-200 dark:bg-slate-700/50 -mb-6 hidden sm:block"></div>
                                    
                                    <div class="flex items-start gap-3 sm:gap-4 relative z-10">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full {{ $comment->is_admin_reply ? 'bg-primary-500' : 'bg-slate-200 dark:bg-slate-800' }} text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0 border-2 border-white dark:border-dark-bg z-10">
                                            @if($comment->is_admin_reply)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                            @else
                                                {{ substr($comment->user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5 rounded-2xl rounded-tl-none p-4 shadow-sm group-hover:border-primary-500/30 transition-colors">
                                                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 mb-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $comment->user->name }}</span>
                                                        @if($comment->is_admin_reply)
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-primary-500/10 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-wider border border-primary-500/20">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                                {{ __('المحاضر') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs font-medium text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $comment->comment_text }}</p>
                                            </div>

                                            {{-- Replies --}}
                                            @if($comment->replies->count() > 0)
                                                <div class="mt-4 space-y-4">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="flex items-start gap-3 sm:gap-4 relative">
                                                            {{-- Connector line --}}
                                                            <div class="absolute -left-6 sm:-left-8 top-4 w-4 sm:w-6 h-px bg-slate-200 dark:bg-slate-700/50 hidden sm:block"></div>
                                                            
                                                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full {{ $reply->is_admin_reply ? 'bg-primary-500' : 'bg-slate-200 dark:bg-slate-800' }} text-white flex items-center justify-center font-bold text-[10px] sm:text-xs shrink-0 border-2 border-white dark:border-dark-bg z-10">
                                                                @if($reply->is_admin_reply)
                                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                                                @else
                                                                    {{ substr($reply->user->name, 0, 1) }}
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="flex-1">
                                                                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200/50 dark:border-white/5 rounded-2xl rounded-tl-none p-3 sm:p-4">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">{{ $reply->user->name }}</span>
                                                                        @if($reply->is_admin_reply)
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-primary-500/10 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-wider border border-primary-500/20">{{ __('المحاضر') }}</span>
                                                                        @endif
                                                                        <span class="text-[10px] sm:text-xs font-medium text-slate-500 ml-auto">{{ $reply->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">{{ $reply->comment_text }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-student.empty-state
                                    title="{{ __('لا يوجد تعليقات') }}"
                                    message="{{ __('كن أول من يسأل سؤال أو يشارك رأيه!') }}"
                                >
                                    <x-slot name="icon">
                                        <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m-9 6l2.4-2.4A2 2 0 016.8 17H18a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11z"></path>
                                        </svg>
                                    </x-slot>
                                </x-student.empty-state>
                            @endforelse
                        </div>
                    </div>
                </x-student.card>

                {{-- Lesson Navigation (Bottom) --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4" data-aos="fade-up">
                    <div class="w-full sm:w-auto flex-1 flex justify-start">
                        @if($previousLesson)
                            <a href="{{ route('student.lessons.show', [$course, $previousLesson]) }}" class="btn-ghost flex items-center justify-center gap-2 px-6 py-3 font-bold text-slate-600 dark:text-slate-300 hover:text-primary-500 hover:bg-white dark:hover:bg-slate-800 rounded-xl transition-colors w-full sm:w-auto shadow-sm border border-slate-200 dark:border-white/5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                <div>
                                    <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">{{ __('السابق') }}</div>
                                    <div class="text-sm line-clamp-1 max-w-[150px]">{{ $previousLesson->title }}</div>
                                </div>
                            </a>
                        @endif
                    </div>

                    <div class="w-full sm:w-auto shrink-0 flex justify-center">
                        @if(!$progress->is_completed)
                            @if($requiresQuizPass && !$hasPassedCompletionQuiz)
                                <div class="flex flex-col items-stretch gap-2 w-full sm:w-auto">
                                    <a href="{{ route('student.quizzes.start', $completionQuiz) }}" class="btn-primary ripple-btn px-8 py-4 rounded-xl shadow-lg shadow-amber-500/25 font-bold flex items-center justify-center gap-2 w-full sm:w-auto bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 border-0">
                                        <span class="bg-black/20 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></span>
                                        {{ __('اجتز الاختبار أولًا') }}
                                    </a>
                                    <p class="text-center text-xs font-bold text-amber-600 dark:text-amber-400">{{ __('لن يتم اعتبار الدرس مكتملًا قبل نجاحك في الاختبار.') }}</p>
                                </div>
                            @else
                                <button type="button" onclick="markAsComplete(this)" class="btn-primary ripple-btn px-8 py-4 rounded-xl shadow-lg shadow-primary-500/25 font-bold flex items-center justify-center gap-2 w-full sm:w-auto bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 border-0">
                                    <span class="bg-black/20 rounded-full p-1"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></span>
                                    {{ __('تم الانتهاء') }}
                                </button>
                            @endif
                        @else
                            <div class="px-8 py-4 rounded-xl font-bold flex items-center justify-center gap-2 w-full sm:w-auto bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('مكتمل') }}
                            </div>
                        @endif
                    </div>

                    <div class="w-full sm:w-auto flex-1 flex justify-end">
                        @if($nextLesson)
                            <a href="{{ route('student.lessons.show', [$course, $nextLesson]) }}" class="btn-primary ripple-btn flex items-center justify-center gap-2 px-6 py-3 font-bold rounded-xl w-full sm:w-auto shadow-md">
                                <div class="text-right">
                                    <div class="text-[10px] uppercase tracking-wider text-white/70 font-bold mb-0.5">{{ __('التالي') }}</div>
                                    <div class="text-sm line-clamp-1 max-w-[150px]">{{ $nextLesson->title }}</div>
                                </div>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right sidebar (notes) --}}
            <div class="xl:col-span-1">
                <div class="glass-card sticky top-28 overflow-hidden rounded-[2rem] border-t-4 border-t-accent-500 flex flex-col h-[calc(100vh-8rem)]" x-data="notesManager({ id: @js($currentNote?->id), text: @js($currentNote?->note_text ?? '') })" data-aos="fade-left">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="text-accent-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4.75A2.75 2.75 0 019.75 2h6.19L21 7.06V19.25A2.75 2.75 0 0118.25 22h-8.5A2.75 2.75 0 017 19.25V4.75z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2.5V7h4.5M10 11h4m-4 3h6m-6 3h4"></path>
                                </svg>
                            </span>
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ __('ملاحظاتي') }}</h3>
                        </div>
                        
                        <div x-show="saving" x-cloak class="flex items-center gap-1.5 text-xs font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ __('جاري الحفظ') }}
                        </div>
                        <div x-show="saved" x-cloak class="flex items-center gap-1 text-xs font-bold text-emerald-500 bg-emerald-500/10 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('تم الحفظ') }}
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col min-h-0">
                        <div class="relative flex-1 flex flex-col mb-4">
                            <textarea x-model="noteText" @input="autoSave()" class="w-full flex-1 bg-yellow-50/50 dark:bg-yellow-900/10 border border-yellow-200/50 dark:border-yellow-900/30 rounded-2xl py-4 px-5 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all resize-none shadow-inner text-sm leading-relaxed" placeholder="{{ __('اكتب ملاحظاتك هنا... (بيتحفظ تلقائي)') }}" style="background-image: repeating-linear-gradient(transparent, transparent 31px, rgba(0,0,0,0.05) 31px, rgba(0,0,0,0.05) 32px); line-height: 32px; attachment: local;"></textarea>
                        </div>

                        @if($noteHistory->count() > 0)
                            <div class="shrink-0 pt-4 border-t border-slate-200 dark:border-white/10 max-h-[40%] overflow-y-auto pr-2 custom-scrollbar">
                                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-500 mb-3">{{ __('ملاحظات سابقة') }}</h4>
                                <div class="space-y-3">
                                    @foreach($noteHistory as $note)
                                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3 border border-slate-200 dark:border-white/5 hover:border-accent-500/30 transition-colors group cursor-pointer">
                                            <div class="text-[10px] font-bold text-accent-500 mb-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $note->created_at->format('M d, Y - h:ia') }}
                                            </div>
                                            <div class="text-xs text-slate-700 dark:text-slate-300 line-clamp-3 group-hover:line-clamp-none transition-all leading-relaxed">{{ $note->note_text }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
const lessonUiText = {
    confirmComplete: @json(__('تقدم ممتاز! هل تريد إنهاء الدرس والانتقال للخطوة التالية؟')),
    completing: @json(__('جاري الإنهاء...')),
    completed: @json(__('تم!')),
    completedSuccess: @json(__('تم إنهاء الدرس بنجاح!')),
    completeError: @json(__('حصل خطأ! حاول تاني.')),
    notesSaveError: @json(__('فشل حفظ الملاحظات. تأكد من الاتصال.')),
    progressUpdateIgnored: @json(__('Silently ignoring progress update fail')),
};

const lessonCompletionRules = {
    requiresQuizPass: @json($requiresQuizPass),
    hasPassedQuiz: @json($hasPassedCompletionQuiz),
};

function scrollLessonVocabulary(direction) {
    const track = document.getElementById('lesson-vocabulary-track');
    if (!track) return;

    const card = track.querySelector('article');
    const distance = card ? card.getBoundingClientRect().width + 16 : 320;

    track.scrollBy({
        left: direction * distance,
        behavior: 'smooth',
    });
}

function speakVocabularyWord(text) {
    if (!text || !('speechSynthesis' in window)) {
        return;
    }

    window.speechSynthesis.cancel();

    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'en-US';
    utterance.rate = 0.88;
    utterance.pitch = 1;

    const voices = window.speechSynthesis.getVoices();
    const englishVoice = voices.find((voice) => voice.lang === 'en-US' && voice.name.includes('Google'))
        || voices.find((voice) => voice.lang === 'en-US' && voice.name.includes('Microsoft'))
        || voices.find((voice) => voice.lang === 'en-US')
        || voices.find((voice) => voice.lang.startsWith('en'));

    if (englishVoice) {
        utterance.voice = englishVoice;
    }

    window.speechSynthesis.speak(utterance);
}

function markAsComplete(btn) {
    if (!btn) return;

    if (!confirm(lessonUiText.confirmComplete)) return;

    const originalContent = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${lessonUiText.completing}`;
    btn.disabled = true;

    axios.post('{{ route('student.lessons.complete', [$course, $lesson]) }}')
        .then(response => {
            if (window.showNotification) {
                showNotification(response.data.message || lessonUiText.completedSuccess, 'success');
            }
            
            // Success animation
            btn.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ${lessonUiText.completed}`;
            btn.classList.add('bg-primary-500', 'from-primary-500', 'to-primary-400');
            
            setTimeout(() => {
                @if($nextLesson)
                    window.location.href = '{{ route('student.lessons.show', [$course, $nextLesson]) }}';
                @else
                    window.location.href = '{{ route('student.courses.learn', $course) }}';
                @endif
            }, 1000);
        })
        .catch((error) => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            const message = error?.response?.data?.error || lessonUiText.completeError;
            if (window.showNotification) showNotification(message, 'error');
        });
}

function notesManager(initialNote) {
    initialNote = initialNote || {};

    return {
        noteId: initialNote.id || null,
        noteText: initialNote.text || '',
        saving: false, 
        saved: false, 
        saveTimeout: null,
        autoSave() {
            clearTimeout(this.saveTimeout);
            this.saved = false; 
            this.saving = true;
            this.saveTimeout = setTimeout(() => this.save(), 1500);
        },
        save() {
            if(!this.noteText.trim()) {
                this.saving = false;
                return;
            }
            axios.post('{{ route('student.notes.store') }}', { 
                note_id: this.noteId,
                lesson_id: {{ $lesson->id }}, 
                note_text: this.noteText 
            })
            .then((response) => { 
                this.noteId = response.data && response.data.note ? response.data.note.id : this.noteId;
                this.saving = false; 
                this.saved = true; 
                setTimeout(() => { this.saved = false; }, 3000); 
            })
            .catch(() => { 
                this.saving = false; 
                if (window.showNotification) showNotification(lessonUiText.notesSaveError, 'error');
            });
        }
    };
}

// Video Progress Tracking & Blob Protection
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('lessonVideo');
    if (video) {
        // Blob Video Protection
        const originalSrc = video.getAttribute('data-src');
        const loader = document.getElementById('videoLoaderOverlay');
        
        if (originalSrc) {
            fetch(originalSrc)
                .then(response => response.blob())
                .then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    video.src = blobUrl;
                    
                    // Revoke the original URL and blob URL right after it starts to make extraction extremely narrow-windowed
                    video.addEventListener('loadeddata', () => {
                        URL.revokeObjectURL(blobUrl);
                        video.removeAttribute('data-src');
                    });
                    
                    if(loader) loader.style.display = 'none';
                })
                .catch(err => {
                    console.error('Error loading video securely:', err);
                    // Fallback to direct src if fetch fails (e.g. CORS)
                    video.src = originalSrc;
                    if(loader) loader.style.display = 'none';
                });
        }
    
        // Disable Right Click & Keyboard shortcuts on video
        video.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            // Prevent common Developer Tools shortcuts if video is focused/hovered
            if(video.matches(':hover') && (
                e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
                (e.ctrlKey && e.key === 'U')
            )) {
                e.preventDefault();
            }
        });

        // Tracking
        let lastReportedTime = 0;
        
        video.addEventListener('timeupdate', function() {
            // Report progress every 15 seconds
            if (video.currentTime - lastReportedTime > 15) {
                lastReportedTime = video.currentTime;
                axios.post('{{ route('student.lessons.update-progress', [$course, $lesson]) }}', { 
                    position: Math.floor(video.currentTime), 
                    time_spent: 15, // Approx time since last update
                    duration: video.duration
                }).catch(() => console.log(lessonUiText.progressUpdateIgnored));
            }
        });
        
        // Ensure we record close to the end
        video.addEventListener('ended', function() {
            if (lessonCompletionRules.requiresQuizPass && !lessonCompletionRules.hasPassedQuiz) {
                return;
            }
            axios.post('{{ route('student.lessons.update-progress', [$course, $lesson]) }}', { 
                position: Math.floor(video.duration), 
                time_spent: Math.floor(video.currentTime - lastReportedTime),
                duration: video.duration,
                is_completed: true
            }).catch(() => console.log(lessonUiText.progressUpdateIgnored));
        });
    }
});

if ('speechSynthesis' in window) {
    window.speechSynthesis.getVoices();
    window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
}
</script>
<style>
/* Custom scrollbar for notes area */
.snap-x::-webkit-scrollbar {
    display: none;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.3);
    border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>
@endpush
@endsection






