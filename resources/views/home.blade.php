@extends('layouts.app')

@section('title', 'إتقان الإنجليزية | منصة لتعليم اللغة الإنجليزية')
@section('meta_description', 'أفضل منصة لتعليم اللغة الإنجليزية وتطوير مهارات التحدث، القواعد، والطلاقة. كورسات تفاعلية مع تقييم النطق باستخدام الذكاء الاصطناعي.')
@section('meta_keywords', 'تعلم الانجليزية, دورات انجليزي, نطق اللغة الانجليزية, الذكاء الاصطناعي, English courses, learn English')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     ADVANCED 3D BOOK SPLASH SCREEN
     ═══════════════════════════════════════════════════════════ --}}
{{-- Removed 3D Splash Screen --}}

<div class="relative overflow-hidden">

    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION — 3D Elements, Gradient text, floating orbs, CTA
         ═══════════════════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden -mt-20 lg:-mt-24 pt-20 lg:pt-24">
        
        {{-- Video Background --}}
        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden bg-slate-900">
            <video autoplay loop muted playsinline preload="auto" class="absolute top-1/2 left-1/2 min-w-full min-h-full w-auto h-auto -translate-x-1/2 -translate-y-1/2 object-cover opacity-90">
                <source src="{{ asset('videos/Futuristic_Alphabet_Sphere_Animation.webm') }}" type="video/webm">
            </video>
            {{-- Dark/Gradient Overlay for readability --}}
            <div class="absolute inset-0 bg-slate-900/60 dark:bg-slate-900/80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/60"></div>
            {{-- Mesh gradient overlay for extra texture --}}
            <div class="absolute inset-0 bg-primary-500/10 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-32 relative z-10 w-full flex flex-col items-center text-center mt-10">
            
            <div data-aos="fade-up" data-aos-duration="1000" class="relative z-20 max-w-4xl mx-auto flex flex-col items-center">
                {{-- Status Badges --}}
                <div class="flex flex-wrap justify-center items-center gap-3 mb-8">
                    {{-- Live Students Counter --}}
                    @php
                        // Track activity for logged-in students visiting the home page
                        if (auth()->check() && auth()->user()->is_student) {
                            auth()->user()->updateActivity();
                        }

                        $activeStudentsCount = \Illuminate\Support\Facades\Cache::remember('active_students_count_home', 10, function () {
                            return \App\Models\User::where('role', 'student')
                                ->where('last_activity_at', '>=', now()->subMinutes(5))
                                ->count();
                        });
                    @endphp
                    <div class="inline-flex items-center gap-2 glass-card px-4 py-2 !rounded-full shadow-lg border-white/10 bg-white/5 backdrop-blur-md" title="{{ __('Active students right now') }}">
                        <span class="relative flex h-3 w-3 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        <span class="text-xs font-black uppercase tracking-widest text-white">
                            {{ $activeStudentsCount ?? 0 }} {{ __('Students Online') }}
                        </span>
                    </div>
                </div>

                {{-- Heading --}}
                <h1 class="text-5xl sm:text-6xl lg:text-8xl font-black tracking-tighter leading-[1.15] mb-8 text-white drop-shadow-md">
                    {{ __('Master English') }}
                    <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-accent-400 pb-2 mt-4 inline-block">{{ __('Like a Pro.') }}</span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-xl sm:text-2xl max-w-2xl mx-auto mb-10 leading-relaxed font-medium text-slate-200 drop-shadow-md">
                    {{ __('The premium AI-powered platform for immersive language learning. Elevate your fluency with interactive tools and real-time feedback.') }}
                </p>

                {{-- CTA Button --}}
                <div class="flex items-center justify-center mb-10 w-full sm:w-auto">
                    <a href="{{ route('student.courses.index') }}"
                       class="btn-primary ripple-btn px-10 py-5 rounded-2xl shadow-[0_0_40px_-10px_rgba(99,102,241,0.6)] font-black text-lg flex items-center justify-center gap-2 group bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-500 hover:to-accent-500 text-white border border-white/20 transition-all hover:scale-105 w-full sm:w-auto">
                        <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Explore Course') }}
                    </a>
                </div>
            </div>

            {{-- Floating Stats Pill (Ultra-Minimalist Design) --}}
            <div class="mt-20 relative z-20 w-full max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                <div class="relative p-1 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl shadow-2xl flex flex-col md:flex-row items-center justify-between overflow-hidden">
                    {{-- Ambient Glow --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 via-emerald-500/10 to-accent-500/10 opacity-50"></div>



                    {{-- Stat 2: Active Courses --}}
                    <div class="relative flex-1 flex items-center justify-center gap-4 p-6 group w-full md:w-auto">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center ring-1 ring-emerald-500/50 group-hover:scale-110 group-hover:bg-emerald-500/40 transition-all duration-300 shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                            <span class="text-xl">🎓</span>
                        </div>
                        <div class="text-left">
                            <div class="text-white font-black text-3xl leading-none mb-0.5">{{ collect($stats)->get('total_courses', 0) }}</div>
                            <div class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">{{ __('Courses') }}</div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="hidden md:block w-px h-16 bg-white/10"></div>
                    <div class="md:hidden w-3/4 h-px bg-white/10 my-2"></div>

                    {{-- Stat 3: Total Students --}}
                    <div class="relative flex-1 flex items-center justify-center gap-4 p-6 group w-full md:w-auto">
                        <div class="w-12 h-12 rounded-full bg-accent-500/20 flex items-center justify-center ring-1 ring-accent-500/50 group-hover:scale-110 group-hover:bg-accent-500/40 transition-all duration-300 shadow-[0_0_20px_rgba(139,92,246,0.3)]">
                            <span class="text-xl">👥</span>
                        </div>
                        <div class="text-left">
                            <div class="text-white font-black text-3xl leading-none mb-0.5">{{ collect($stats)->get('total_students', 0) }}</div>
                            <div class="text-[10px] font-bold text-accent-400 uppercase tracking-widest">{{ __('Learners') }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         WAVE DIVIDER
         ═══════════════════════════════════════════════════════════ --}}
    <div class="wave-divider">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path fill="var(--color-surface)" d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z"/>
        </svg>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         STATS SECTION — Animated counters
         ═══════════════════════════════════════════════════════════ --}}
    <section class="py-16 relative" style="background: var(--color-surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up">
                {{-- Stat 1 --}}
                <div class="text-center group">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gradient mb-2">
                        <span data-counter="{{ collect($stats)->get('total_courses', 0) }}">0</span>
                    </div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: var(--color-text-muted);">{{ __('Active Courses') }}</div>
                </div>
                {{-- Stat 2 --}}
                <div class="text-center group">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gradient mb-2">
                        <span data-counter="{{ collect($stats)->get('total_students', 0) }}">0</span>
                    </div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: var(--color-text-muted);">{{ __('Students') }}</div>
                </div>
                {{-- Stat 3 --}}
                <div class="text-center group">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gradient mb-2">
                        <span data-counter="{{ collect($stats)->get('total_enrollments', 0) }}">0</span>
                    </div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: var(--color-text-muted);">{{ __('Enrollments') }}</div>
                </div>
                {{-- Stat 4 --}}
                <div class="text-center group">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gradient mb-2">
                        <span data-counter="{{ collect($stats)->get('certificates_issued', 0) }}">0</span>
                    </div>
                    <div class="text-xs font-semibold uppercase tracking-widest" style="color: var(--color-text-muted);">{{ __('Certificates') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         FEATURES SECTION — 3D Tilt Glass Cards
         ═══════════════════════════════════════════════════════════ --}}
    <section class="py-24 relative overflow-hidden">
        {{-- Background decorations --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <div class="badge-primary mb-4">{{ __('Features') }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ __('Everything You Need to') }}
                    <span class="text-gradient">{{ __('Master English') }}</span>
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ __('A complete toolkit designed by language experts, powered by cutting-edge AI technology.') }}
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
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('AI Pronunciation Coach') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Real-time feedback on your accent and intonation using advanced speech recognition models.') }}
                    </p>

                </div>

                {{-- Feature 2: Structured Courses --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500/20 to-accent-500/5 flex items-center justify-center mb-6 group-hover:shadow-neon-violet transition-all duration-500">
                        <svg class="w-7 h-7 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Structured Learning Paths') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Expert-designed curriculum broken down into clear modules with progress tracking and achievements.') }}
                    </p>

                </div>

                {{-- Feature 3: Gamified Progress --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Gamified Learning') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Earn XP, maintain streaks, and unlock badges. Compete on leaderboards and stay motivated daily.') }}
                    </p>

                </div>

                {{-- Feature 4: Interactive Quizzes --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Smart Quizzes') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Adaptive assessments that adjust to your skill level, ensuring optimal challenge and retention.') }}
                    </p>

                </div>

                {{-- Feature 5: Community Forum --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500/20 to-pink-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Community Forum') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Connect with fellow learners, ask questions, share tips, and practice conversational English.') }}
                    </p>

                </div>

                {{-- Feature 6: Certificates --}}
                <div class="glass-card p-8 tilt-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-indigo-500/5 flex items-center justify-center mb-6 group-hover:shadow-lg transition-all duration-500">
                        <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Verified Certificates') }}</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('Earn verifiable certificates upon course completion. Share them on LinkedIn and boost your career.') }}
                    </p>

                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         FEATURED COURSES SECTION
         ═══════════════════════════════════════════════════════════ --}}
    <section class="py-24 relative overflow-hidden" style="background: var(--color-surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <div class="badge-primary mb-4">{{ __('Trending') }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ __('Popular') }} <span class="text-gradient">{{ __('Courses') }}</span>
                </h2>
                <p class="text-lg" style="color: var(--color-text-muted);">
                    {{ __('Join thousands of students in our most popular English courses.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                        <div class="absolute top-4 right-4 rtl:left-4 rtl:right-auto bg-white/90 dark:bg-black/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            {{ $course->level ? __($course->level) : __('All Levels') }}
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 line-clamp-1" style="color: var(--color-text);">{{ $course->title }}</h3>
                        <p class="text-sm line-clamp-2 mb-4" style="color: var(--color-text-muted);">{{ $course->short_description }}</p>
                        
                        {{-- Meta --}}
                        <div class="flex items-center justify-between text-xs font-medium mb-6" style="color: var(--color-text-muted);">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $course->duration ? __($course->duration) : __('4 Weeks') }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                {{ $course->total_students }} {{ __('Students') }}
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-white/10">
                            <div class="text-lg font-bold text-primary-500">
                                {{ $course->price > 0 ? $course->price . ' ر.س' : __('Free') }}
                            </div>
                            <a href="{{ route('student.courses.show', $course) }}" class="btn-primary btn-sm rounded-lg">
                                {{ __('View Course') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('student.courses.index') }}" class="btn-secondary btn-lg group">
                    {{ __('View All Courses') }}
                    <svg class="w-4 h-4 ml-2 rtl:mr-2 rtl:ml-0 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         HOW IT WORKS — Steps
         ═══════════════════════════════════════════════════════════ --}}
    <section class="py-24 relative" style="background: var(--color-surface);">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <div class="badge-accent mb-4">{{ __('How It Works') }}</div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ __('Start in') }} <span class="text-gradient">{{ __('3 Simple Steps') }}</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="relative text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-6 shadow-neon-cyan">
                        1
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Create Account') }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Register your account in seconds.') }}</p>
                    {{-- Connector line --}}
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-px bg-gradient-to-r from-primary-500 to-transparent"></div>
                </div>

                {{-- Step 2 --}}
                <div class="relative text-center" data-aos="fade-up" data-aos-delay="150">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-6 shadow-neon-violet">
                        2
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Choose Your Course') }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Subscribe to the comprehensive language course.') }}</p>
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-px bg-gradient-to-r from-accent-500 to-transparent"></div>
                </div>

                {{-- Step 3 --}}
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-6 shadow-lg">
                        3
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ __('Start Learning') }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Dive into lessons, take quizzes, practice pronunciation, and track your journey to fluency.') }}</p>
                </div>
            </div>
        </div>
    </section>




</div>
@endsection
