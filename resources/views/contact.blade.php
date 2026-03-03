@extends('layouts.app')

@section('title', __('Contact') . ' — ' . config('app.name'))

@section('content')
<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-mesh"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 right-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="badge-primary mb-4">💬 {{ __('Get In Touch') }}</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4" style="color: var(--color-text);">
                {{ __('Contact Us') }}
            </h1>
            <p class="text-lg max-w-2xl mx-auto" style="color: var(--color-text-muted);">
                {{ __('Have a question about courses, certificates, or your account? Send us a message and our team will get back to you as soon as possible.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            {{-- Contact Info Sidebar --}}
            <div class="lg:col-span-2 space-y-6" data-aos="fade-right" data-aos-delay="200">
                {{-- Info Cards --}}
                <div class="glass-card p-6 group hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm" style="color: var(--color-text);">{{ __('Email') }}</h3>
                            <p class="text-sm mt-1" style="color: var(--color-text-muted);">{{ config('mail.from.address', 'support@platform.com') }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 group hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm" style="color: var(--color-text);">{{ __('Phone') }}</h3>
                            <p class="text-sm mt-1" style="color: var(--color-text-muted);">+1 (555) 123-4567</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 group hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm" style="color: var(--color-text);">{{ __('Working Hours') }}</h3>
                            <p class="text-sm mt-1" style="color: var(--color-text-muted);">{{ __('Sunday – Thursday, 9 AM – 6 PM') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="glass-card p-6">
                    <h3 class="font-bold text-sm mb-4" style="color: var(--color-text);">{{ __('Quick Links') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('student.courses.index') }}" class="flex items-center gap-2 text-sm transition-colors hover:text-primary-500" style="color: var(--color-text-muted);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            {{ __('Browse Courses') }}
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-2 text-sm transition-colors hover:text-primary-500" style="color: var(--color-text-muted);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            {{ __('Create Account') }}
                        </a>
                        <a href="{{ route('about') }}" class="flex items-center gap-2 text-sm transition-colors hover:text-primary-500" style="color: var(--color-text-muted);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            {{ __('About Us') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-3" data-aos="fade-left" data-aos-delay="300">
                <div class="glass-card p-8 gradient-border">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-3" style="color: var(--color-text);">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-lg">📩</span>
                        {{ __('Send a Message') }}
                    </h2>

                    @if ($errors->any())
                        <div class="glass-card p-4 mb-6 border-l-4 border-red-500" style="background: rgba(239, 68, 68, 0.05);">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <ul class="text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li style="color: var(--color-text);">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Full Name') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input id="name" name="name" type="text" required class="input-glass pl-12" value="{{ old('name') }}" placeholder="{{ __('Your name') }}">
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Email Address') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                    </div>
                                    <input id="email" name="email" type="email" required class="input-glass pl-12" value="{{ old('email') }}" placeholder="you@example.com">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Subject') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                </div>
                                <input id="subject" name="subject" type="text" required class="input-glass pl-12" value="{{ old('subject') }}" placeholder="{{ __('How can we help?') }}">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Message') }}</label>
                            <textarea id="message" name="message" rows="5" required class="input-glass" placeholder="{{ __('Type your message here...') }}">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn-primary w-full btn-lg ripple-btn" :disabled="loading">
                            <span x-show="!loading" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                {{ __('Send Message') }}
                            </span>
                            <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                {{ __('Sending...') }}
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
