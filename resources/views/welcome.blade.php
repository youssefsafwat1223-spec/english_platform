<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', __('إتقان الإنجليزية')) }} - {{ __('منصة تعليم لغة بطريقة سهلة ومبتكرة') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-dark-bg transition-colors duration-300 overflow-x-hidden selection:bg-primary-500 selection:text-white">
    
    <!-- 3D Background Container -->
    <div id="canvas-container" class="fixed inset-0 z-0 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.navigation')

        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="hero-section relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div class="text-center lg:text-left space-y-8 animate-on-scroll">
                            
                            {{-- Live Students Counter & Update Badge --}}
                            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                                <div class="inline-flex items-center px-4 py-2 rounded-full border border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-bold shadow-sm backdrop-blur-md">
                                    <span class="flex h-2.5 w-2.5 rounded-full bg-primary-500 mr-2.5 animate-pulse"></span>
                                    {{ __('New: 3D Interactive Lessons') }}
                                </div>
                                
                                {{-- Live Counter Badge --}}
                                @php
                                    $activeStudentsCount = \Illuminate\Support\Facades\Cache::remember('active_students_count_home', 60, function () {
                                        return \App\Models\User::where('role', 'student')
                                            ->where('last_activity_at', '>=', now()->subMinutes(5))
                                            ->count();
                                    });
                                @endphp
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-sm font-bold shadow-sm backdrop-blur-md" title=__('Active students right now')>
                                    <span class="relative flex h-3 w-3 shrink-0">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                    </span>
                                    <span>{{ $activeStudentsCount ?? 0 }} {{ __('Students Online Now') }}</span>
                                </div>
                            </div>
                            
                            <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-balance leading-tight">
                                {{ __('Master English in a') }} <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 via-accent-500 to-primary-600 animate-gradient-x">{{ __('New Dimension') }}</span>
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                                {{ __('Experience the future of language learning. Immerse yourself in our "Knowledge Galaxy", interact with 3D objects, and master English faster than ever.') }}
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                                <a href="{{ route('register') }}" class="btn-primary ripple-btn px-8 py-4 text-lg shadow-xl shadow-primary-500/30 hover:shadow-primary-500/50 transform hover:-translate-y-1 transition-all rounded-2xl font-bold flex items-center justify-center gap-2">
                                    {{ __('Start Learning Free') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </a>
                                <a href="#features" class="btn-ghost px-8 py-4 text-lg rounded-2xl font-bold border-2 border-slate-200 dark:border-slate-700 hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                    {{ __('Explore Features') }}
                                </a>
                            </div>

                            <div class="pt-6 flex flex-wrap items-center justify-center lg:justify-start gap-x-8 gap-y-4 text-sm font-bold text-slate-500 dark:text-slate-400">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    {{ __('100% Free') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    {{ __('Interactive 3D') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    {{ __('Community') }}
                                </div>
                            </div>
                        </div>

                        <div class="relative h-[400px] lg:h-[600px] w-full hidden lg:block">
                            <!-- Premium 3D AI Illustration -->
                            <div class="absolute inset-0 flex items-center justify-center animate-bounce-slow" style="animation: float 6s ease-in-out infinite;">
                                <!-- Glow Effect Behind Hero Image -->
                                <div class="absolute w-[400px] h-[400px] bg-primary-500/30 rounded-full blur-[100px] animate-pulse"></div>
                                
                                <img src="{{ asset('images/ai/welcome_hero_3d.png') }}" 
                                     alt="Mastering English 3D" 
                                     class="relative z-10 w-full h-auto max-w-[550px] object-contain drop-shadow-2xl"
                                     style="filter: drop-shadow(0 25px 35px rgba(0, 123, 181, 0.25));">
                            </div>

                            <style>
                                @keyframes float {
                                    0% { transform: translateY(0px) rotate(0deg); }
                                    50% { transform: translateY(-20px) rotate(2deg); }
                                    100% { transform: translateY(0px) rotate(0deg); }
                                }
                            </style>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="py-12 border-y border-gray-200 dark:border-gray-800 bg-white/50 dark:bg-dark-bg/50 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-200 dark:divide-gray-800">
                        @foreach([
                            ['0+', __('Active Students'), '1000'], 
                            ['0+', __('Interactive Courses'), '50'], 
                            ['0+', __('Hours of Content'), '120'], 
                            ['0', __('Average Rating'), '4.9']
                        ] as $stat)
                        <div class="p-4 animate-on-scroll">
                            <div class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500 mb-2 counter" data-target="{{ $stat[2] }}">{{ $stat[0] }}</div>
                            <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $stat[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-24 relative bg-gray-50/50 dark:bg-dark-bg/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16 animate-on-scroll">
                        <h2 class="text-sm font-bold text-primary-600 dark:text-primary-400 tracking-widest uppercase bg-primary-100 dark:bg-primary-900/30 inline-block px-4 py-1.5 rounded-full mb-4">{{ __('Why Choose Us') }}</h2>
                        <p class="mt-2 text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">{{ __('Learning Reimagined') }}</p>
                        <p class="mt-4 text-xl text-gray-500 dark:text-gray-400 font-medium">{{ __('Say goodbye to boring textbooks. Our platform combines cutting-edge technology with proven educational methods.') }}</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="glass-card p-8 tilt-card animate-on-scroll hover:border-blue-500/50 group">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center text-white mb-6 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-blue-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Immersive 3D Labs') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Interact with 3D objects and environments to learn vocabulary and concepts in a way that sticks.') }}</p>
                        </div>
                        <div class="glass-card p-8 tilt-card animate-on-scroll hover:border-purple-500/50 group" style="transition-delay: 150ms;">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-400 flex items-center justify-center text-white mb-6 transform group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 shadow-xl shadow-purple-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('AI-Powered Feedback') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Get instant, personalized feedback on your grammar and pronunciation from our advanced AI tutors.') }}</p>
                        </div>
                        <div class="glass-card p-8 tilt-card animate-on-scroll hover:border-amber-500/50 group" style="transition-delay: 300ms;">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center text-white mb-6 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-amber-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Global Community') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('Join thousands of learners worldwide. Compete in leaderboards and practice with peers.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-24 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-accent-900/10 dark:from-primary-900/20 dark:to-accent-900/20 skew-y-3 transform origin-top-left scale-110"></div>
                <div class="absolute inset-0 bg-white/20 dark:bg-black/20 backdrop-blur-3xl"></div>
                <div class="max-w-4xl mx-auto px-4 relative z-10 text-center animate-on-scroll">
                    <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight">{{ __('Ready to Start Your Journey?') }}</h2>
                    <p class="text-2xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto font-medium">{{ __('Join today and get unrestricted access to our introductory 3D courses. No credit card required.') }}</p>
                    <a href="{{ route('register') }}" class="btn-primary ripple-btn px-10 py-5 text-xl shadow-2xl shadow-primary-500/50 hover:scale-105 transition-transform inline-flex items-center gap-2 rounded-2xl font-bold">
                        <span>{{ __('Get Started Now') }}</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </section>
        </main>
        @include('layouts.footer')
    </div>
</body>
</html>
