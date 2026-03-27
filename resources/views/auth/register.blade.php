@extends('layouts.app')

@section('title', __('Create Account') . ' — ' . config('app.name'))

@section('content')
<div class="min-h-screen flex bg-slate-50 dark:bg-[#020617] transition-colors duration-500">
    
    {{-- Left Panel: Visual/Branding (Hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-900 border-r border-white/5">
        {{-- Background Image/Effect --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-primary-500/20 via-transparent to-transparent opacity-80"></div>
            <div class="absolute bottom-0 left-0 w-full h-[500px] bg-gradient-to-t from-slate-900 to-transparent"></div>
            {{-- Abstract Mesh --}}
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-30"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full">
            <a href="{{ route('home') }}" class="flex items-center gap-3 w-fit group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-2xl font-black text-white tracking-tight">{{ config('app.name') }}</span>
            </a>

            <div>
                <h2 class="text-5xl font-black text-white mb-6 leading-[1.1] tracking-tight">
                    {{ __('Start mastering English today.') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center shrink-0 border border-white/10">
                            <span class="text-emerald-400">✓</span>
                        </div>
                        <span class="text-slate-300 font-medium">{{ __('Free AI-powered pronunciation coaching') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center shrink-0 border border-white/10">
                            <span class="text-emerald-400">✓</span>
                        </div>
                        <span class="text-slate-300 font-medium">{{ __('Earn certificates for completed courses') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center shrink-0 border border-white/10">
                            <span class="text-emerald-400">✓</span>
                        </div>
                        <span class="text-slate-300 font-medium">{{ __('No credit card required to start') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                    <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-xs font-bold text-white">+10k</div>
                </div>
                <div class="text-sm font-bold text-slate-400">
                    {{ __('Trusted by students worldwide') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel: Register Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 relative z-10 overflow-y-auto min-h-screen">
        
        <div class="w-full max-w-md animate-fade-in-up py-10">
            
            {{-- Mobile Logo --}}
            <div class="lg:hidden text-center mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </a>
            </div>

            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-3">{{ __('Create Account') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">
                    {{ __('Already have an account?') }} 
                    <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline underline-offset-4 transition-all">{{ __('Log In') }}</a>
                </p>
                </p>
            </div>

            @if(session('referral_info'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-4 py-3 rounded-xl text-sm font-medium flex gap-3 text-emerald-800 dark:text-emerald-300">
                    <span class="text-xl shrink-0">🎁</span>
                    <div>
                        {!! __('Referred by :name. You get :discount% off your first course!', [
                            'name' => '<strong class="text-emerald-900 dark:text-emerald-100">' . e(session('referral_info.referrer_name')) . '</strong>',
                            'discount' => '<strong class="text-emerald-600 dark:text-emerald-400">' . e(session('referral_info.discount')) . '</strong>',
                        ]) !!}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Full Name') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input id="name" name="name" type="text" required
                               class="w-full bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-slate-900 dark:text-white font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-sm @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @enderror"
                               value="{{ old('name') }}"
                               placeholder="John Doe">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="w-full bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-slate-900 dark:text-white font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-sm @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="new-password" required
                               class="w-full bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-xl py-3.5 pl-12 pr-12 text-slate-900 dark:text-white font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-sm @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Confirm Password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password" required
                               class="w-full bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-xl py-3.5 pl-12 pr-12 text-slate-900 dark:text-white font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-sm"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center mt-2 mb-4">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 mt-0.5 rounded border-2 border-slate-300 dark:border-white/20 text-primary-500 bg-transparent focus:ring-primary-500 focus:ring-offset-0 transition-colors cursor-pointer">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors leading-snug">
                            {{ __('I agree to the') }} <a href="{{ route('terms') }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ __('Privacy Policy') }}</a>.
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-200 text-white dark:text-slate-900 font-black text-lg py-4 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-white/10 transition-all flex items-center justify-center border-0 gap-2 hover:scale-[1.02] mt-4" :disabled="loading" :class="loading ? 'opacity-70 cursor-not-allowed transform-none' : ''">
                    <span x-show="!loading" class="flex items-center justify-center gap-2">
                        {{ __('Create Account') }}
                    </span>
                    <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        {{ __('Creating account...') }}
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="mt-8 relative flex items-center justify-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-white/10"></div>
                </div>
                <div class="relative px-4 bg-slate-50 dark:bg-[#020617] text-xs font-bold text-slate-400 uppercase tracking-widest">
                    {{ __('Or continue with') }}
                </div>
            </div>

            {{-- Google Sign Up --}}
            <div class="mt-6">
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 w-full py-3.5 px-4 rounded-xl bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 text-slate-700 dark:text-slate-200 font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    {{ __('Sign up with Google') }}
                </a>
            </div>
            
        </div>
    </div>
</div>
@endsection
