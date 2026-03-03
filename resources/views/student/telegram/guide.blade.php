@extends('layouts.app')

@section('title', __('Telegram Bot Guide') . ' — ' . config('app.name'))

@section('content')
<div class="min-h-screen py-12 relative flex items-center justify-center p-4">
    <div class="max-w-4xl w-full mx-auto relative z-10">
        
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-12" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                        {{ __('Telegram') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Telegram') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Bot Guide') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Learn how to use our Telegram bot to receive daily English challenges, track your progress, and earn points on the go!') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: How to Connect & Rules --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- How to Connect Card --}}
                <div class="glass-card p-8 border-t-4 border-[#0088cc]" data-aos="fade-right" data-aos-delay="100">
                    <h2 class="text-2xl font-bold flex items-center gap-3 mb-6" style="color: var(--color-text);">
                        <span class="w-10 h-10 rounded-full bg-[#0088cc]/20 text-[#0088cc] flex items-center justify-center">🔌</span>
                        {{ __('How to Connect') }}
                    </h2>
                    
                    @if(auth()->user()->is_telegram_linked)
                        <div class="mb-6 p-4 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 flex items-start gap-3 text-emerald-700 dark:text-emerald-300">
                            <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h3 class="font-bold mb-1">{{ __('You are already connected!') }}</h3>
                                <p class="text-sm opacity-90">{{ __('Your Telegram account is successfully linked. You can skip the setup steps and start using the commands below.') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-[#0088cc] text-white flex items-center justify-center font-bold shadow-md shadow-[#0088cc]/30">1</div>
                            <div>
                                <h3 class="font-bold text-lg mb-1" style="color: var(--color-text);">{{ __('Update Profile') }}</h3>
                                <p class="text-sm" style="color: var(--color-text-muted);">
                                    {{ __('Ensure your phone number is saved in your') }} <a href="{{ route('student.profile.show') }}" class="text-[#0088cc] hover:underline">{{ __('Profile Settings') }}</a>.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-[#0088cc] text-white flex items-center justify-center font-bold shadow-md shadow-[#0088cc]/30">2</div>
                            <div>
                                <h3 class="font-bold text-lg mb-1" style="color: var(--color-text);">{{ __('Open the Bot') }}</h3>
                                <p class="text-sm" style="color: var(--color-text-muted);">
                                    {{ __('Click the button below to open our official Telegram bot, or search for') }} <strong class="text-[#0088cc]">{{ '@' . config('services.telegram.bot_username', 'SimpleEnglishBot') }}</strong>.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-[#0088cc] text-white flex items-center justify-center font-bold shadow-md shadow-[#0088cc]/30">3</div>
                            <div>
                                <h3 class="font-bold text-lg mb-1" style="color: var(--color-text);">{{ __('Start & Share Contact') }}</h3>
                                <p class="text-sm" style="color: var(--color-text-muted);">
                                    {{ __('Tap the') }} <code class="bg-gray-100 dark:bg-white/10 px-2 py-1 rounded text-[#0088cc]">/start</code> {{ __('command, then share your phone number when prompted by the bot to link your account.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if(!auth()->user()->is_telegram_linked)
                        <div class="mt-8 text-center border-t border-gray-200 dark:border-white/10 pt-6">
                            <a href="https://t.me/{{ config('services.telegram.bot_username', 'YourBot') }}?start=1" target="_blank"
                               class="inline-flex items-center justify-center gap-2 bg-[#0088cc] hover:bg-[#0077b5] text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform hover:scale-105">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                                {{ __('Open Telegram Bot') }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Rules Card --}}
                <div class="glass-card p-8 border-t-4 border-amber-500" data-aos="fade-right" data-aos-delay="200">
                    <h2 class="text-2xl font-bold flex items-center gap-3 mb-6" style="color: var(--color-text);">
                        <span class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center">📜</span>
                        {{ __('Important Rules') }}
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                                <strong style="color: var(--color-text);">{{ __('One Account per Number:') }}</strong> {{ __('You can only link one Telegram account to one phone number registered on the platform.') }}
                            </p>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                                <strong style="color: var(--color-text);">{{ __('Daily Questions Timing:') }}</strong> {{ __('Once linked, you will receive a new English question. Daily quizzes are typically sent out automatically.') }}
                            </p>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <p class="text-sm leading-relaxed" style="color: var(--color-text-muted);">
                                <strong style="color: var(--color-text);">{{ __('Earning Points:') }}</strong> {{ __('Answering questions directly inside Telegram adds points to your main account leaderboard immediately.') }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Right Column: Commands --}}
            <div class="lg:col-span-1">
                <div class="glass-card p-8 border-t-4 border-primary-500 sticky-sidebar" data-aos="fade-left" data-aos-delay="300">
                    <h2 class="text-2xl font-bold flex items-center gap-3 mb-6" style="color: var(--color-text);">
                        <span class="w-10 h-10 rounded-full bg-primary-500/20 text-primary-500 flex items-center justify-center">⌨️</span>
                        {{ __('Bot Commands') }}
                    </h2>
                    
                    <div class="space-y-4">
                        
                        {{-- /start --}}
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-primary-500/30 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <code class="px-2 py-1 rounded bg-primary-100 dark:bg-primary-500/20 text-primary-700 dark:text-primary-300 font-bold text-sm">/start</code>
                            </div>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('Starts the bot and initiates the account linking process. Use this if the bot stops responding.') }}
                            </p>
                        </div>
                        
                        {{-- /today --}}
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-primary-500/30 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <code class="px-2 py-1 rounded bg-primary-100 dark:bg-primary-500/20 text-primary-700 dark:text-primary-300 font-bold text-sm">/today</code>
                            </div>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('Fetches today\'s daily question immediately if you haven\'t answered it yet.') }}
                            </p>
                        </div>

                        {{-- /status --}}
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-primary-500/30 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <code class="px-2 py-1 rounded bg-primary-100 dark:bg-primary-500/20 text-primary-700 dark:text-primary-300 font-bold text-sm">/status</code>
                            </div>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('Checks your current streak, total points, and ranking on the platform leaderboard.') }}
                            </p>
                        </div>

                        {{-- /help --}}
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-primary-500/30 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <code class="px-2 py-1 rounded bg-primary-100 dark:bg-primary-500/20 text-primary-700 dark:text-primary-300 font-bold text-sm">/help</code>
                            </div>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('Displays a menu of all available commands directly in the chat.') }}
                            </p>
                        </div>

                        {{-- A, B, C, D --}}
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 hover:border-primary-500/30 transition-colors">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <code class="px-2 py-1 rounded bg-accent-100 dark:bg-accent-500/20 text-accent-700 dark:text-accent-300 font-bold text-sm">A</code>
                                <code class="px-2 py-1 rounded bg-accent-100 dark:bg-accent-500/20 text-accent-700 dark:text-accent-300 font-bold text-sm">B</code>
                                <code class="px-2 py-1 rounded bg-accent-100 dark:bg-accent-500/20 text-accent-700 dark:text-accent-300 font-bold text-sm">C</code>
                                <code class="px-2 py-1 rounded bg-accent-100 dark:bg-accent-500/20 text-accent-700 dark:text-accent-300 font-bold text-sm">D</code>
                            </div>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('When a question is active, simply reply with the letter of your chosen answer. Or use the inline buttons if available.') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Prevent sidebar from scrolling with page on large screens */
    @media (min-width: 1024px) {
        .sticky-sidebar {
            position: sticky;
            top: 6rem;
        }
    }
</style>
@endsection
