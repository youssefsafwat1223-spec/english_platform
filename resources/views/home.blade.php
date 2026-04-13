@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'إتقان الإنجليزية | منصة لتعلّم اللغة الإنجليزية' : 'Master English | English Learning Platform')
@section('meta_description', app()->getLocale() === 'ar'
    ? 'منصة متقدمة لتعلّم اللغة الإنجليزية وتطوير مهارات التحدث والقواعد والطلاقة عبر كورسات تفاعلية واختبارات ومساعدات بالذكاء الاصطناعي.'
    : 'A premium platform for learning English and improving speaking, grammar, and fluency through interactive courses, quizzes, and AI-powered support.')
@section('meta_keywords', app()->getLocale() === 'ar'
    ? 'تعلم الإنجليزية, دورات إنجليزي, نطق اللغة الإنجليزية, الذكاء الاصطناعي, كورسات إنجليزية'
    : 'learn English, English courses, pronunciation, AI learning, fluency')

@section('content')

@php
    $isArabic = app()->getLocale() === 'ar';
    $primaryCtaUrl = route('pricing');
    $primaryCtaLabel = $isArabic ? 'ابدأ البرنامج' : 'Start the program';
    $secondaryCtaUrl = route('home') . '#how-it-works';
    $secondaryCtaLabel = $isArabic ? 'اعرف كيف تتعلم' : 'See how you learn';
    $heroBadge = $isArabic ? 'برنامج تأسيس عملي للإنجليزية' : 'Practical English foundation program';
    $heroTitle = $isArabic ? 'تخلص من الترجمة الحرفية' : 'Stop translating word by word';
    $heroSubtitle = $isArabic ? 'وتعلم بناء الجملة بثقة' : 'Build real sentences with confidence';
    $heroDescription = $isArabic
        ? 'منهج تأسيسي واضح يقودك خطوة بخطوة للتحدث بثقة، مع تدريب عملي، اختبارات، وشهادة حضور.'
        : 'A clear foundation path that takes you step by step to confident English, with practice, assessment, and an attendance certificate.';
    $heroHighlights = $isArabic
        ? ['منهج تأسيسي واضح', $liveSessionsEnabled ? 'تدريب عملي بالذكاء الاصطناعي وجلسات مباشرة' : 'تدريب عملي بالذكاء الاصطناعي', 'اختبارات + شهادة حضور']
        : ['Clear foundation curriculum', $liveSessionsEnabled ? 'Practical AI practice + live sessions' : 'Practical AI practice', 'Quizzes + attendance certificate'];
@endphp

{{-- Splash screen removed --}}

<div class="relative overflow-hidden">

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden -mt-20 lg:-mt-24 pt-20 lg:pt-24">
        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden bg-slate-900">
            <video autoplay loop muted playsinline preload="auto" class="absolute top-1/2 left-1/2 min-w-full min-h-full w-auto h-auto -translate-x-1/2 -translate-y-1/2 object-cover opacity-90">
                <source src="{{ asset('videos/Futuristic_Alphabet_Sphere_Animation.webm') }}" type="video/webm">
            </video>
            <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/60"></div>
            <div class="absolute inset-0 bg-primary-500/10 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-32 relative z-10 w-full flex flex-col items-center text-center mt-10">
            <div data-aos="fade-up" data-aos-duration="1000" class="relative z-20 max-w-4xl mx-auto flex flex-col items-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur border border-white/15 text-sm font-bold text-white/90 mb-6 shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    {{ $heroBadge }}
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-8xl font-black tracking-tighter leading-[1.15] mb-8 text-white drop-shadow-md">
                    {{ $heroTitle }}
                    <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-accent-400 pb-2 mt-4 inline-block">{{ $heroSubtitle }}</span>
                </h1>

                <p class="text-xl sm:text-2xl max-w-2xl mx-auto mb-10 leading-relaxed font-medium text-slate-200 drop-shadow-md">
                    {{ $heroDescription }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8 w-full sm:w-auto">
                    <a href="{{ $primaryCtaUrl }}"
                       class="btn-primary ripple-btn px-10 py-5 rounded-2xl shadow-[0_0_40px_-10px_rgba(99,102,241,0.6)] font-black text-lg flex items-center justify-center gap-2 group bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-500 hover:to-accent-500 text-white border border-white/20 transition-all hover:scale-105 w-full sm:w-auto">
                        <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $primaryCtaLabel }}
                    </a>
                    <a href="{{ $secondaryCtaUrl }}"
                       class="px-8 py-5 rounded-2xl font-black text-lg flex items-center justify-center gap-2 text-white bg-white/5 border border-white/15 backdrop-blur hover:bg-white/10 transition-all hover:scale-[1.02] w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $secondaryCtaLabel }}
                    </a>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 max-w-3xl">
                    @foreach($heroHighlights as $highlight)
                        <div class="px-4 py-2 rounded-full border border-white/10 bg-black/20 backdrop-blur text-sm font-bold text-slate-200 shadow-sm">
                            {{ $highlight }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="wave-divider">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path fill="var(--color-surface)" d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z"/>
        </svg>
    </div>
    <section id="features" class="py-24 relative overflow-hidden">
        {{-- Background decorations --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <div class="badge-primary mb-4">{{ $isArabic ? 'أدوات تدعم تقدّمك' : 'Tools that support your progress' }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ $isArabic ? 'كل ما يدعم' : 'Everything that supports' }}
                    <span class="text-gradient">{{ $isArabic ? 'تقدّمك الحقيقي' : 'your real progress' }}</span>
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'هذه الأدوات ليست الهدف، بل وسائل تساعدك على تثبيت الأساس وممارسة اللغة باستمرار.' : 'These tools are not the goal; they keep your foundation solid and your practice consistent.' }}
                </p>
            </div>

            {{-- Feature Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: AI Pronunciation --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500/20 to-primary-500/5 flex items-center justify-center mb-6 group-hover:shadow-neon-cyan transition-all duration-500">
                        <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'مدرب نطق ذكي' : 'AI Pronunciation Coach' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'راجع نطقك بصوت واضح وتغذية راجعة مباشرة تساعدك على الاقتراب من النطق الصحيح خطوة بخطوة.' : 'Get real-time feedback on pronunciation, stress, and clarity with AI-powered speech analysis.' }}
                    </p>

                </div>

                {{-- Feature 2: Structured Program Path --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500/20 to-accent-500/5 flex items-center justify-center mb-6 group-hover:shadow-neon-violet transition-all duration-500">
                        <svg class="w-7 h-7 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'مسار واضح من البداية للنهاية' : 'Structured Program Path' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'اتبع خطة مرتبة بخطوات واضحة بدل التعلم العشوائي والمحتوى المبعثر.' : 'Follow one clear curriculum with focused modules, progress tracking, and practical milestones.' }}
                    </p>

                </div>

                {{-- Feature 3: Gamified Progress --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'تعلم يحفزك كل يوم' : 'Gamified Learning' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'نقاط وسلاسل إنجاز وترتيب يساعدك على الاستمرار والحفاظ على تقدمك.' : 'Earn XP, build streaks, and stay engaged with a system that rewards consistency.' }}
                    </p>

                </div>

                {{-- Feature 4: Interactive Quizzes --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'اختبارات ذكية بعد كل مرحلة' : 'Smart Quizzes' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'اختبر فهمك أولًا بأول وتأكد أنك تبني أساسًا قويًا قبل الانتقال للخطوة التالية.' : 'Use targeted quizzes to measure retention and reinforce each lesson before moving on.' }}
                    </p>

                </div>

                {{-- Feature 5: Community Forum --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500/20 to-pink-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'مجتمع يساعدك تتقدم' : 'Community Forum' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'اسأل وناقش وشارك تجاربك مع طلاب لديهم الهدف نفسه بدل أن تتعلم وحدك.' : 'Ask questions, share tips, and learn alongside other students with the same goal.' }}
                    </p>

                </div>

                {{-- Feature 5B: AI Writing Coach --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="150">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500/20 to-sky-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-8-8h8m-8 4h8m-8 4h4m5-8l2-2 2 2-8 8H9v-2l8-8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'مدرب كتابة ذكي' : 'AI Writing Coach' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'تطبيقات كتابة مرتبطة بكل درس مع ملاحظات ذكية على القواعد والمفردات والترابط، واقتراحات عملية للتحسين.' : 'Practice lesson-based writing tasks with AI feedback for grammar, vocabulary, coherence, and actionable improvements.' }}
                    </p>
                </div>

                {{-- Feature 6: Certificates --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-indigo-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $isArabic ? 'شهادة حضور' : 'Attendance certificate' }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'بعد إنهاء الكورس يمكنك الحصول على شهادة حضور.' : 'After completing the course, you receive an attendance certificate.' }}
                    </p>

                </div>
            </div>
        </div>
    </section>

    {{-- Program section --}}
    <section id="featured-courses" class="py-24 relative overflow-hidden" style="background: var(--color-surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <div class="badge-primary mb-4">{{ $isArabic ? 'تفاصيل البرنامج' : 'Program Details' }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ $isArabic ? 'برنامج واحد' : 'One Program' }} <span class="text-gradient">{{ $isArabic ? 'بمسار تعليمي واضح' : 'with a clear learning path' }}</span>
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'المنصة مخصصة لبرنامج واحد متكامل يبدأ بالتأسيس ثم التطبيق ثم التقييم المستمر.' : 'This platform focuses on one complete program: foundation, practice, and ongoing assessment.' }}
                </p>
            </div>


            <div class="grid grid-cols-1 max-w-2xl mx-auto gap-8">
                @foreach($featuredCourses as $course)
                <div class="glass-card overflow-hidden group hover:-translate-y-2 transition-transform duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    {{-- Course Thumbnail --}}
                    <div class="relative h-48 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-500/20 group-hover:scale-110 transition-transform duration-500"></div>
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl bg-slate-100 dark:bg-white/5">
                                {{ substr($course->title, 0, 1) }}
                            </div>
                        @endif
                        @if($course->level)
                            <div class="absolute top-4 right-4 rtl:left-4 rtl:right-auto bg-white/90 dark:bg-black/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                {{ __($course->level) }}
                            </div>
                        @endif
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 line-clamp-1" style="color: var(--color-text);">{{ $course->title }}</h3>
                        <p class="text-sm line-clamp-2 mb-4" style="color: var(--color-text-muted);">{{ $course->short_description }}</p>
                        
                        {{-- Meta --}}
                        <div class="flex items-center justify-between text-xs font-medium mb-6" style="color: var(--color-text-muted);">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $course->estimated_duration_weeks ? ($isArabic ? 'مدة الكورس: ' . $course->estimated_duration_weeks . ' أسبوعًا' : 'Course duration: ' . $course->estimated_duration_weeks . ' weeks') : ($isArabic ? 'تعلّم بالوتيرة المناسبة لك' : 'Self-paced') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs font-semibold mb-5" style="color: var(--color-text-muted);">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                                {{ $isArabic ? 'منهج تأسيسي' : 'Foundation curriculum' }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent-500"></span>
                                {{ $isArabic ? 'تدريب ذكاء اصطناعي' : 'AI practice' }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $isArabic ? 'اختبارات قصيرة' : 'Short quizzes' }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                {{ $isArabic ? 'شهادة حضور' : 'Attendance certificate' }}
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-white/10">
                            <div class="text-lg font-bold text-primary-500">
                                {{ $course->price > 0 ? $course->price . ' ' . ($isArabic ? 'ر.س' : 'SAR') : __('Free') }}
                            </div>
                            <a href="{{ auth()->check() && auth()->user()->is_student ? route('student.courses.show', $course) : route('courses.show', $course) }}" class="btn-primary btn-sm rounded-lg">
                                {{ $isArabic ? 'تفاصيل البرنامج' : 'View program details' }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- Pricing strip --}}
    <section id="pricing" class="py-20 lg:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 via-transparent to-accent-500/10 pointer-events-none"></div>
        <div class="max-w-6xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="glass-card p-8 md:p-12 flex flex-col lg:flex-row items-center justify-between gap-8" data-aos="fade-up">
                <div class="max-w-2xl">
                    <div class="badge-primary mb-4">{{ $isArabic ? 'الأسعار والتقسيط' : 'Pricing & installments' }}</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold mb-4" style="color: var(--color-text);">
                        {{ $isArabic ? 'قسطها على دفعات' : 'Split it into installments' }}
                    </h2>
                    <p class="text-base sm:text-lg" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'تقدر تشترك بدفعة كاملة أو تقسطها على دفعات حسب وسيلة الدفع المتاحة داخل بوابة الدفع.' : 'You can subscribe with full payment or split it into installments, depending on the available payment method in checkout.' }}
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('pricing') }}" class="btn-primary ripple-btn px-8 py-4 rounded-2xl shadow-lg shadow-primary-500/25 inline-flex items-center gap-2 font-black">
                        {{ $isArabic ? 'اطّلع على الأسعار' : 'View pricing' }}
                    </a>
                    <a href="{{ route('home') }}#featured-courses" class="px-8 py-4 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors inline-flex items-center gap-2 font-black" style="color: var(--color-text);">
                        {{ $isArabic ? 'اطّلع على تفاصيل البرنامج' : 'View program details' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="py-24 relative" style="background: var(--color-surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <div class="badge-accent mb-4">{{ $isArabic ? 'كيف تتعلم فعليًا' : 'How you actually learn' }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ $isArabic ? '4 خطوات واضحة داخل المنصة' : '4 clear steps inside the program' }}
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'شاهد الدرس، طبّق مباشرة، اختبر نفسك، ثم تمرّن على المهارات حتى تثبت.' : 'Watch, apply, test, then practice until it sticks.' }}
                </p>
            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xl font-extrabold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'شاهد الدرس' : 'Watch the lesson' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'شرح واضح ومركّز لتأسيس القاعدة الصحيحة.' : 'Clear, focused explanation to build the right foundation.' }}</p>
                </div>

                <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white text-xl font-extrabold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'طبّق مباشرة' : 'Apply immediately' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'تمارين قصيرة تثبّت الفهم وتزيل الترجمة الذهنية.' : 'Short exercises to lock understanding and remove mental translation.' }}</p>
                </div>

                <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xl font-extrabold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'اختبر نفسك' : 'Test yourself' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'اختبارات ذكية تكشف نقاط الضعف قبل الانتقال للخطوة التالية.' : 'Smart quizzes reveal gaps before you move forward.' }}</p>
                </div>

                <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xl font-extrabold mx-auto mb-4">
                        4
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'تمرّن على المهارات' : 'Practice the skills' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $liveSessionsEnabled ? ($isArabic ? 'تدريب نطق وتطبيقات عملية وجلسات مباشرة للمراجعة.' : 'Pronunciation practice, real usage, and live sessions for review.') : ($isArabic ? 'تدريب نطق وتطبيقات عملية للمراجعة.' : 'Pronunciation practice and real usage for review.') }}</p>
                </div>
            </div>
        </div>
    </section>
    {{-- Who is it for --}}
    <section id="who-for" class="py-20 lg:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-accent-500/5 pointer-events-none"></div>
        <div class="max-w-6xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
                <div class="badge-primary mb-4">{{ $isArabic ? 'لمن هذا البرنامج؟' : 'Who this program is for' }}</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'مصمم للتأسيس والممارسة الفعلية' : 'Designed for foundation and real practice' }}
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'نوضح لك من يناسبه البرنامج حتى تكون التوقعات واضحة من البداية.' : 'We clarify fit upfront so expectations are clear.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="0">
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'مناسب للمبتدئ' : 'Great for beginners' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'إذا كنت تبدأ من الصفر وتريد مسارًا واضحًا.' : 'If you are starting from scratch and want a clear path.' }}</p>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'مناسب لمن يفهم ولا يتكلم' : 'Great if you understand but don’t speak' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'سننقلك من الفهم إلى الاستخدام بثقة.' : 'We take you from understanding to confident use.' }}</p>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'مناسب لمن يريد تأسيسًا + ممارسة' : 'Great for foundation + practice' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'تتعلّم القاعدة ثم تطبقها عمليًا باستمرار.' : 'Learn the rule, then apply it consistently.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Sample videos section --}}
    @if($promoVideos->count() > 0)
    <section id="preview" class="py-20 lg:py-28 relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-accent-500/5 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-500/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-sm font-bold mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $isArabic ? 'شاهد قبل الاشتراك' : 'Watch Before You Subscribe' }}
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'عينة من' : 'A Sneak Peek Into' }}
                    <span class="text-gradient">{{ $isArabic ? 'الشروحات' : 'The Lessons' }}</span>
                </h2>
                <p class="text-lg font-medium" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'اطّلع بنفسك على جودة المحتوى قبل أن تبدأ رحلتك معنا.' : 'See the quality of the content for yourself before starting your journey with us.' }}
                </p>
            </div>

            {{-- Video Grid --}}
            <div x-data="{ activeVideo: 0 }" class="space-y-8">
                {{-- Main Video Player --}}
                <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 bg-black aspect-video max-w-4xl mx-auto" data-aos="fade-up">
                    @foreach($promoVideos as $index => $video)
                        <div x-show="activeVideo === {{ $index }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <iframe
                                x-bind:src="activeVideo === {{ $index }} ? '{{ $video->embed_url }}' : ''"
                                class="w-full h-full absolute inset-0"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                loading="lazy">
                            </iframe>
                        </div>
                    @endforeach
                </div>

                {{-- Video Selector Tabs --}}
                @if($promoVideos->count() > 1)
                <div class="flex flex-wrap justify-center gap-4 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    @foreach($promoVideos as $index => $video)
                    <button
                        @click="activeVideo = {{ $index }}"
                        :class="activeVideo === {{ $index }} ? 'border-primary-500 bg-primary-500/10 shadow-lg shadow-primary-500/20' : 'border-white/10 hover:border-primary-500/50 hover:bg-white/5'"
                        class="flex items-center gap-3 px-5 py-3 rounded-2xl border-2 transition-all duration-300 text-start group">
                        <div :class="activeVideo === {{ $index }} ? 'bg-primary-500 text-white' : 'bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-slate-400'"
                             class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-all group-hover:scale-105 font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color: var(--color-text);">{{ $video->title }}</div>
                            @if($video->description)
                                <div class="text-xs mt-0.5 line-clamp-1" style="color: var(--color-text-muted);">{{ $video->description }}</div>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif


    {{-- Testimonials section --}}
    @if($testimonials->count() > 0 || $canSubmitTestimonial)
    <section id="reviews" class="py-20 lg:py-28 relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 bg-gradient-to-tl from-accent-500/5 via-transparent to-primary-500/5 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent-500/10 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent-500/10 border border-accent-500/20 text-accent-600 dark:text-accent-400 text-sm font-bold mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    {{ $isArabic ? 'تقييمات حقيقية' : 'Real Student Reviews' }}
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'ماذا قالوا' : 'What Students Say' }}
                    <span class="text-gradient">{{ $isArabic ? 'عنا' : 'About Us' }}</span>
                </h2>
                <p class="text-lg font-medium" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'اطّلع على آراء الطلاب الذين بدأوا رحلتهم معنا.' : 'See what students who started learning with us have to say.' }}
                </p>
                @if($canSubmitTestimonial)
                    <div class="mt-6 flex flex-col items-center gap-3">
                        <a href="{{ route('student.testimonial.edit') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m7-7H5"></path>
                            </svg>
                            {{ $studentTestimonial ? ($isArabic ? 'عدّل رأيك' : 'Edit Your Review') : ($isArabic ? 'اكتب رأيك' : 'Write Your Review') }}
                        </a>
                        @if($studentTestimonial && !$studentTestimonial->is_active)
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-black">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $isArabic ? 'رأيك الحالي قيد المراجعة' : 'Your current review is pending approval' }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Testimonials Grid --}}
            @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($testimonials as $index => $testimonial)
                <div class="glass-card p-6 lg:p-8 relative group hover:border-accent-500/30 transition-all duration-500 hover:-translate-y-1 hover:shadow-xl"
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    {{-- Quote icon --}}
                    <div class="absolute top-4 right-4 w-10 h-10 rounded-xl bg-accent-500/10 flex items-center justify-center text-accent-500 opacity-50 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>

                    {{-- Stars --}}
                    <div class="flex items-center gap-0.5 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-slate-300 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- Content --}}
                    <p class="text-sm leading-relaxed mb-6 font-medium" style="color: var(--color-text-muted);">
                        "{{ $testimonial->content }}"
                    </p>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200 dark:border-white/10">
                        @if($testimonial->avatar)
                            <img src="{{ Storage::url($testimonial->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-accent-500/30 shadow-sm" alt="{{ $testimonial->name }}">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-accent-500 to-primary-500 flex items-center justify-center text-white font-bold shadow-sm text-lg">
                                {{ mb_substr($testimonial->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-sm" style="color: var(--color-text);">{{ $testimonial->name }}</div>
                            @if($testimonial->role)
                                <div class="text-xs font-medium" style="color: var(--color-text-muted);">{{ $testimonial->role }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="glass-card max-w-3xl mx-auto p-8 md:p-10 text-center" data-aos="fade-up">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black mb-3" style="color: var(--color-text);">
                    {{ $isArabic ? 'ابدأ بأول رأي حقيقي هنا' : 'Be the first to share a real review here' }}
                </h3>
                <p class="text-base font-medium mb-6" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'لا توجد آراء منشورة بعد، ويمكنك أن تكون أول طالب يشارك تجربته بعد المراجعة.' : 'No reviews have been published yet, but you can be the first student to share your experience after review.' }}
                </p>
                <a href="{{ route('student.testimonial.edit') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 inline-flex items-center gap-2">
                    {{ $isArabic ? 'شارك تجربتك' : 'Share Your Experience' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

        {{-- FAQ --}}
    <section id="faq" class="py-20 lg:py-28 relative overflow-hidden" style="background: var(--color-surface);">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
                <div class="badge-accent mb-4">{{ $isArabic ? 'أسئلة شائعة' : 'FAQ' }}</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'إجابات سريعة قبل الاشتراك' : 'Quick answers before you enroll' }}
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'وضحنا أكثر الأسئلة التي يطرحها الطلاب قبل البدء.' : 'The most common questions we get before someone starts.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="0">
                    <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'هل هذا مناسب للمبتدئ؟' : 'Is this suitable for beginners?' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'نعم، البرنامج يبدأ من الأساس بخطوات واضحة.' : 'Yes. The program starts from the foundation with clear steps.' }}</p>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="50">
                    <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'كم يحتاج وقتًا؟' : 'How much time does it take?' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'المدة المقترحة موضحة لكل كورس، ويمكنك التعلّم بالوتيرة المناسبة لك.' : 'Each course shows a recommended duration, and you can learn at your pace.' }}</p>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'هل يوجد تقسيط؟' : 'Do you offer installment payments?' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'نعم، ستجد تفاصيل التقسيط في صفحة التسعير عند توفره.' : 'Yes. Installment details are available on the pricing page when offered.' }}</p>
                </div>
                @if($liveSessionsEnabled)
                    <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="150">
                        <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'هل توجد جلسات مباشرة؟' : 'Are there live sessions?' }}</h3>
                        <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'نعم، جلسات مباشرة دورية للمراجعة والتطبيق والإجابة عن الأسئلة.' : 'Yes. Periodic live sessions for review, practice, and Q&A.' }}</p>
                    </div>
                @endif
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'هل أحصل على شهادة؟' : 'Will I get a certificate?' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'بعد إكمال الكورس ستحصل على شهادة حضور.' : 'After completing the course, you receive an attendance certificate.' }}</p>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="250">
                    <h3 class="text-base font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'هل أحتاج التزامًا يوميًا؟' : 'Do I need daily commitment?' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'يفضّل التمرين المنتظم، حتى لو 20-30 دقيقة يوميًا.' : 'Regular practice helps, even 20–30 minutes a day.' }}</p>
                </div>
            </div>
        </div>
    </section>
<section class="py-20 lg:py-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-accent-500/10 pointer-events-none"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="glass-card rounded-[2.5rem] p-8 md:p-12 text-center border border-white/10 shadow-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 text-sm font-black mb-6">
                    {{ $isArabic ? 'جاهز تبدأ بشكل صحيح؟' : 'Ready to start the right way?' }}
                </div>
                <h2 class="text-3xl md:text-5xl font-black mb-5" style="color: var(--color-text);">
                    {{ $isArabic ? 'ابدأ التأسيس الصحيح وارتّب طريقك في الإنجليزية' : 'Start today with the right foundation' }}
                    <span class="text-gradient">{{ $isArabic ? 'بثقة واستمرارية' : 'With clarity and confidence' }}</span>
                </h2>
                <p class="text-lg max-w-3xl mx-auto mb-8" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'إن كنت تريد منهجًا واضحًا وتقدمًا ملموسًا بدل التشتت بين مصادر كثيرة، فهذه أفضل نقطة تبدأ منها.' : 'If you want focused content, a structured path, and visible progress instead of scattered resources, this is the right place to start.' }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ $primaryCtaUrl }}" class="btn-primary ripple-btn px-8 py-4 rounded-2xl shadow-lg shadow-primary-500/25 inline-flex items-center gap-2 font-black">
                        {{ $primaryCtaLabel }}
                    </a>
                    <a href="{{ route('about') }}" class="px-8 py-4 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors inline-flex items-center gap-2 font-black" style="color: var(--color-text);">
                        {{ $isArabic ? 'اعرف تفاصيل البرنامج' : 'Learn more about the program' }}
                    </a>
                </div>
            </div>
        </div>
    </section>



</div>
@endsection












