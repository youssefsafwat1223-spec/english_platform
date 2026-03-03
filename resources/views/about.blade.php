@extends('layouts.app')

@section('title', __('About') . ' — ' . config('app.name'))

@section('content')
{{-- Hero Section --}}
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Text --}}
            <div data-aos="fade-right" data-aos-duration="800">
                <span class="badge-primary mb-4">✨ {{ __('About Us') }}</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                    {{ __('Master English with') }}
                    <span class="text-gradient"> {{ __('real confidence') }}</span>
                </h1>
                <p class="text-lg leading-relaxed mb-8" style="color: var(--color-text-muted);">
                    {{ __('We help learners build real English confidence with structured courses, practical practice, and measurable progress. Our system combines lessons, quizzes, pronunciation practice, and community support so students can improve consistently and earn certificates they can share.') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="btn-primary btn-lg ripple-btn">
                        {{ __('Get Started Free') }}
                    </a>
                    <a href="{{ route('student.courses.index') }}" class="btn-secondary btn-lg">
                        {{ __('Browse Courses') }}
                    </a>
                </div>
            </div>

            {{-- Features Card --}}
            <div class="glass-card p-8 tilt-card" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-3" style="color: var(--color-text);">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-lg">🎯</span>
                    {{ __('What You Get') }}
                </h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4 group">
                        <span class="mt-1 w-8 h-8 rounded-lg bg-primary-500/10 flex items-center justify-center text-primary-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <h4 class="font-semibold text-sm" style="color: var(--color-text);">{{ __('Structured Courses') }}</h4>
                            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Step-by-step paths designed for real progress') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <span class="mt-1 w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <h4 class="font-semibold text-sm" style="color: var(--color-text);">{{ __('Interactive Practice') }}</h4>
                            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Quizzes and pronunciation to reinforce lessons') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <span class="mt-1 w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <h4 class="font-semibold text-sm" style="color: var(--color-text);">{{ __('Verified Certificates') }}</h4>
                            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Earn certificates with online verification') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <span class="mt-1 w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <h4 class="font-semibold text-sm" style="color: var(--color-text);">{{ __('Referral Rewards') }}</h4>
                            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Invite friends and earn bonus points') }}</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="badge-accent mb-4">🚀 {{ __('Simple Process') }}</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold" style="color: var(--color-text);">
                {{ __('How It Works') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $steps = [
                    ['num' => '01', 'icon' => '📖', 'title' => __('Pick a Course'), 'desc' => __('Choose a course that matches your level and goals, then follow the lesson path at your pace.'), 'color' => 'primary', 'delay' => 0],
                    ['num' => '02', 'icon' => '🧠', 'title' => __('Practice & Test'), 'desc' => __('Complete lessons, answer quizzes, and practice pronunciation to lock in what you learn.'), 'color' => 'accent', 'delay' => 150],
                    ['num' => '03', 'icon' => '🏆', 'title' => __('Earn Certificate'), 'desc' => __('Finish the course, pass the final assessment, and receive a verified certificate.'), 'color' => 'emerald', 'delay' => 300],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="glass-card p-8 text-center tilt-card group relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $step['delay'] }}">
                    {{-- Step Number Background --}}
                    <div class="absolute -top-4 -right-4 text-8xl font-black opacity-5 select-none" style="color: var(--color-text);">
                        {{ $step['num'] }}
                    </div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-{{ $step['color'] }}-500/10 flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 transition-transform">
                            {{ $step['icon'] }}
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest mb-2 block" style="color: var(--color-primary);">
                            {{ __('Step') }} {{ $step['num'] }}
                        </span>
                        <h3 class="text-xl font-bold mb-3" style="color: var(--color-text);">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats Bar --}}
<section class="py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-90"></div>
    <div class="absolute inset-0 bg-dot-pattern opacity-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            @php
                $stats = [
                    ['label' => __('Active Students'), 'value' => '2K+', 'icon' => '👥'],
                    ['label' => __('Courses Available'), 'value' => '50+', 'icon' => '📚'],
                    ['label' => __('Certificates Issued'), 'value' => '800+', 'icon' => '🎓'],
                    ['label' => __('Satisfaction Rate'), 'value' => '98%', 'icon' => '⭐'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-4xl mb-2">{{ $stat['icon'] }}</div>
                    <div class="text-3xl font-extrabold">{{ $stat['value'] }}</div>
                    <div class="text-sm opacity-80 mt-1">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
        <h2 class="text-3xl sm:text-4xl font-extrabold mb-4" style="color: var(--color-text);">
            {{ __('Ready to start your journey?') }}
        </h2>
        <p class="text-lg mb-8" style="color: var(--color-text-muted);">
            {{ __('Create your account and begin learning in minutes.') }}
        </p>
        <a href="{{ route('register') }}" class="btn-primary btn-lg ripple-btn inline-flex items-center gap-2">
            {{ __('Get Started Free') }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>
@endsection
