@extends('layouts.app')

@section('title', __('Forgot your password?') . ' — ' . config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-12 relative overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 bg-mesh"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>

    {{-- Floating orbs --}}
    <div class="absolute top-20 left-20 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-20 right-20 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10" data-aos="fade-up" data-aos-duration="800">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight mb-2" style="color: var(--color-text);">
                {{ __('Forgot your password?') }}
            </h1>
            <p class="text-sm" style="color: var(--color-text-muted);">
                {{ __('Enter your email to reset password') }}
            </p>
        </div>

        {{-- Status --}}
        @if (session('status'))
            <div class="glass-card p-4 mb-6 border-l-4 border-emerald-500">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm" style="color: var(--color-text);">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="glass-card p-4 mb-6 border-l-4 border-red-500">
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

        {{-- Form --}}
        <div class="glass-card p-8 gradient-border">
            <form action="{{ route('password.email') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                        {{ __('Email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required autofocus
                               class="input-glass pl-12 @error('email') !border-red-500 @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com">
                    </div>
                </div>

                <button type="submit"
                        class="btn-primary w-full btn-lg ripple-btn"
                        :disabled="loading">
                    <span x-show="!loading" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ __('Send Password Reset Link') }}
                    </span>
                    <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        {{ __('Sending...') }}
                    </span>
                </button>
            </form>
        </div>

        {{-- Back to login --}}
        <p class="text-center mt-8 text-sm" style="color: var(--color-text-muted);">
            <a href="{{ route('login') }}" class="hover:text-primary-500 transition-colors inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Back to login') }}
            </a>
        </p>
    </div>
</div>
@endsection