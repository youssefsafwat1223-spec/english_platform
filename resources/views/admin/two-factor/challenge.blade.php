@extends('layouts.app')

@section('title', __('Admin Verification'))

@section('content')
<div class="min-h-screen py-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card overflow-hidden shadow-2xl">
            <div class="glass-card-body p-8 sm:p-10">
                <div class="text-center mb-8">
                    <div class="mx-auto mb-4 w-16 h-16 rounded-3xl bg-primary-500/10 text-primary-500 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ __('Admin Verification') }}</h1>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Enter the 6-digit authenticator code. You can also use one of your recovery codes.') }}
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-primary-500/20 bg-primary-500/10 px-4 py-3 text-sm font-semibold text-primary-700 dark:text-primary-300">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-700 dark:text-rose-300">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.two-factor.verify') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Authentication Code') }}</label>
                        <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" class="w-full bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 rounded-xl py-3.5 px-4 text-center text-slate-900 dark:text-white font-black tracking-[0.35em] focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-sm" placeholder="123456" value="{{ old('code') }}" required autofocus>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-200 text-white dark:text-slate-900 font-black text-lg py-4 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-white/10 transition-all flex items-center justify-center border-0 gap-2 hover:scale-[1.02]" :disabled="loading" :class="loading ? 'opacity-70 cursor-not-allowed transform-none' : ''">
                        <span x-show="!loading">{{ __('Verify and Continue') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Verifying...') }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                        {{ __('Log out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
