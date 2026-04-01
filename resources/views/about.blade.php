@extends('layouts.app')

@section('title', __('About the Platform') . ' - ' . config('app.name'))

@section('content')
@php
    $publicPages = [
        ['title' => __('Home Page'), 'description' => __('Home Page Description'), 'url' => route('home'), 'label' => __('Open Page')],
        ['title' => __('Pricing'), 'description' => __('Pricing Description Detail'), 'url' => route('pricing'), 'label' => __('View Pricing')],
        ['title' => __('Contact Us'), 'description' => __('Contact Us Description Detail'), 'url' => route('contact'), 'label' => __('Open Page')],
        ['title' => __('Login'), 'description' => __('Login Description Detail'), 'url' => route('login'), 'label' => __('Open Page')],
        ['title' => __('Register'), 'description' => __('Register Description Detail'), 'url' => route('register'), 'label' => __('Open Page')],
        ['title' => __('Privacy & Terms'), 'description' => __('Privacy & Terms Description Detail'), 'url' => route('privacy'), 'label' => __('Open Page')],
    ];

    $studentPages = [
        ['title' => __('Dashboard'), 'description' => __('Dashboard Overview Description')],
        ['title' => __('All Courses'), 'description' => __('All Courses Description Detail')],
        ['title' => __('My Courses'), 'description' => __('My Courses Subtitle Detailed')],
        ['title' => __('Course Page'), 'description' => __('Course Info Page Description')],
        ['title' => __('Learning Page'), 'description' => __('Learning Section Description')],
        ['title' => __('Lesson Page'), 'description' => __('Lesson Interaction Description')],
        ['title' => __('Quizzes & Attempts'), 'description' => __('Quizzes & History Description')],
        ['title' => __('Pronunciation Training'), 'description' => __('Pronunciation AI Training Detailed')],
        ['title' => __('Certificates'), 'description' => __('Certificates Management Description')],
        ['title' => __('Notes'), 'description' => __('Personal Notes Repository')],
        ['title' => __('Notifications'), 'description' => __('Notification Center Summary')],
        ['title' => __('Student Profile'), 'description' => __('Profile Management & History')],
    ];

    $communityPages = [
        ['title' => __('Forum'), 'description' => __('Forum Interaction Summary')],
        ['title' => __('Leaderboard'), 'description' => __('Leaderboard Competition Detailed')],
        ['title' => __('Games'), 'description' => __('Interactive Games Description')],
        ['title' => __('Battle Arena'), 'description' => __('Battle Arena Feature Description')],
        ['title' => __('Referrals'), 'description' => __('Referrals Tracking Description')],
        ['title' => __('Student Feedback'), 'description' => __('Student Reviews Description Detailed')],
    ];

    $adminFeatures = [
        __('Manage courses, levels and order'),
        __('Manage quizzes and follow attempts'),
        __('Manage students, subscriptions and payments'),
        __('Manage Certificates, Forum and Bot'),
        __('Setup Security, Payment and Telegram'),
    ];

    $botCommands = [
        ['/start', __('Start bot chat and link account')],
        ['/today', __('Fetch today question or quiz')],
        ['/status', __('Display current streak and points')],
        ['/courses', __('Display enrolled courses and progress')],
        ['/leaderboard', __('Display top students leaderboard')],
        ['/streak', __('Display current and longest streak')],
        ['/certificate', __('Display available certificates')],
        ['/remind', __('Toggle bot notifications')],
        ['/unlink', __('Unlink telegram from platform')],
        ['/help', __('Display all bot commands info')],
    ];
@endphp

<section class="relative py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">
        <div class="glass-card rounded-[2rem] p-8 md:p-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 font-bold text-sm mb-6">
                <span>📘</span>
                <span>{{ __('About the Platform') }}</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 leading-tight">
                {{ __('Platform Sitemap') }}
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500">{{ __('Detailed guide to every page and tool.') }}</span>
            </h1>

            <p class="text-base md:text-lg leading-8 text-slate-600 dark:text-slate-300 max-w-4xl">
                {{ __('About Intro') }}
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-8">
                {{-- Journey Map --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-2xl">🧭</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('The Student Journey') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('From account creation to certification.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Account Creation & Login') }}</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Student Journey Step 1 Desc') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Course Selection & Payment') }}</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Student Journey Step 2 Desc') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Learning & Quizzes') }}</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Student Journey Step 3 Desc') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('Progress & Certification') }}</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Student Journey Step 4 Desc') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Public Pages Grid --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="150">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-accent-500/10 text-accent-500 flex items-center justify-center text-2xl">🌐</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Public Pages Title') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($publicPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <h3 class="font-bold text-slate-900 dark:text-white">{{ $page['title'] }}</h3>
                                    <a href="{{ $page['url'] }}" class="text-sm font-bold text-primary-500 hover:text-primary-400 whitespace-nowrap">
                                        {{ $page['label'] }}
                                    </a>
                                </div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Student Pages Grid --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-2xl">🎓</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Student Pages Title') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($studentPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ $page['title'] }}</h3>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Community Pages Grid --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="250">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl">⚔️</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Community & Interaction') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($communityPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ $page['title'] }}</h3>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quiz Feature --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="260">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl">📝</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('About Quizzes Title') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('About Quizzes Sub') }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5 mb-6">
                        <p class="text-sm leading-8 text-slate-700 dark:text-slate-200">
                            {{ __('Quizzes Description Body') }}
                        </p>
                    </div>

                    <div class="rounded-2xl overflow-hidden border border-amber-500/20 mb-6">
                        <img src="{{ asset('images/features/quiz.png') }}" alt="{{ __('Quiz Interface') }}" class="w-full h-auto" loading="lazy">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 font-bold text-sm mb-3">{{ __('Lesson Quiz') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Lesson Quiz Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 font-bold text-sm mb-3">{{ __('Retake') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Retake Description') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Pronunciation Practice Feature --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="270">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-2xl">🎤</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Pronunciation Practice Title') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Pronunciation Practice Sub') }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-indigo-500/20 bg-indigo-500/5 p-5 mb-6">
                        <p class="text-sm leading-8 text-slate-700 dark:text-slate-200">
                            {{ __('Pronunciation Practice Body') }}
                        </p>
                    </div>

                    <div class="rounded-2xl overflow-hidden border border-indigo-500/20 mb-6">
                        <img src="{{ asset('images/features/pronunciation.png') }}" alt="{{ __('Pronunciation Interface') }}" class="w-full h-auto" loading="lazy">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-indigo-500/10 text-indigo-500 font-bold text-sm mb-3">{{ __('Lesson Word Table') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Lesson Word Table Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-indigo-500/10 text-indigo-500 font-bold text-sm mb-3">{{ __('Three Progressive Exercises') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Three Progressive Exercises Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 font-bold text-sm mb-3">{{ __('Instant AI Evaluation') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Instant AI Evaluation Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 font-bold text-sm mb-3">{{ __('Audio Assistance') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Audio Assistance Description') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Battle Arena Feature --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="290">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center text-2xl">⚔️</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Ranking & Results') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Battle Arena Feature Description') }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-red-500/20 bg-red-500/5 p-5 mb-6">
                        <p class="text-sm leading-8 text-slate-700 dark:text-slate-200">
                            {{ __('Battle Arena Page Content') }}
                        </p>
                    </div>

                    <div class="rounded-2xl overflow-hidden border border-red-500/20 mb-6">
                        <img src="{{ asset('images/features/battle.png') }}" alt="{{ __('Battle Arena Interface') }}" class="w-full h-auto" loading="lazy">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-red-500/10 text-red-500 font-bold text-sm mb-3">{{ __('Multiplayer Mode') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Multiplayer Mode Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-red-500/10 text-red-500 font-bold text-sm mb-3">{{ __('Limited Time Questions') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Limited Time Questions Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-red-500/10 text-red-500 font-bold text-sm mb-3">{{ __('Team System') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Team System Description') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <div class="inline-flex px-3 py-1 rounded-lg bg-red-500/10 text-red-500 font-bold text-sm mb-3">{{ __('Ranking & Results') }}</div>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ __('Ranking & Results Description') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Bot Feature --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 text-violet-500 flex items-center justify-center text-2xl">🤖</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('The Telegram Bot') }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Bot Integration Intro') }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-violet-500/20 bg-violet-500/5 p-5 mb-6">
                        <p class="text-sm leading-8 text-slate-700 dark:text-slate-200">
                            {{ __('Bot Integration Description') }}
                            <span class="font-bold text-violet-500">@{{ config('services.telegram.bot_username', 'SimpleEnglishBot') }}</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($botCommands as [$command, $description])
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <div class="inline-flex px-3 py-1 rounded-lg bg-violet-500/10 text-violet-500 font-mono font-bold text-sm mb-3">{{ $command }}</div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('student.telegram.guide') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-500 hover:text-primary-400">
                            <span>{{ __('Open Full Telegram Guide') }}</span>
                            <span>←</span>
                        </a>
                    </div>
                </div>

                {{-- Admin Feature --}}
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="350">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center text-2xl">🛠️</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ __('Admin Panel Management') }}</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($adminFeatures as $feature)
                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4">
                                <span class="mt-1 text-sky-500">•</span>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8">
                <div class="glass-card p-8 sticky-sidebar" data-aos="fade-left" data-aos-delay="180">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">{{ __('Important Links') }}</h2>

                    <div class="space-y-3">
                        <a href="{{ route('pricing') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('Pricing') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('Support & Contact') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('Register') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('Login') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('student.courses.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('All Courses') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>{{ __('Dashboard') }}</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-left" data-aos-delay="240">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">{{ __('What Makes the Platform Special?') }}</h2>
                    <div class="space-y-4 text-sm leading-7 text-slate-600 dark:text-slate-300">
                        <p>{{ __('Platform Uniqueness 1') }}</p>
                        <p>{{ __('Platform Uniqueness 2') }}</p>
                        <p>{{ __('Platform Uniqueness 3') }}</p>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-left" data-aos-delay="300">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">{{ __('New Student Guide') }}</h2>
                    <ol class="space-y-3 text-sm leading-7 text-slate-600 dark:text-slate-300 list-decimal pr-5">
                        <li>{{ __('New Student Step 1') }}</li>
                        <li>{{ __('New Student Step 2') }}</li>
                        <li>{{ __('New Student Step 3') }}</li>
                        <li>{{ __('New Student Step 4') }}</li>
                        <li>{{ __('New Student Step 5') }}</li>
                        <li>{{ __('New Student Step 6') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
