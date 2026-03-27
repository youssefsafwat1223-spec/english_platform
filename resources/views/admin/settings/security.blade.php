@extends('layouts.admin')

@section('title', __('Security Settings'))

@php($admin = auth()->user())

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-6">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Security Settings') }}</span></h1>
            <a href="{{ route('admin.settings.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('Back to Settings') }}</a>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-primary-500/20 bg-primary-500/10 px-5 py-4 text-sm font-semibold text-primary-700 dark:text-primary-300">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 px-5 py-4 text-sm font-semibold text-rose-700 dark:text-rose-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 px-5 py-4 text-sm font-semibold text-rose-700 dark:text-rose-300">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($freshRecoveryCodes)
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h2 class="text-xl font-black mb-2" style="color: var(--color-text);">{{ __('Recovery Codes') }}</h2>
                            <p class="text-sm max-w-2xl" style="color: var(--color-text-muted);">
                                {{ __('Store these recovery codes somewhere safe. Each code can only be used once if you lose access to your authenticator app.') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-amber-500/10 px-3 py-1 text-xs font-black text-amber-600 dark:text-amber-300">
                            {{ __('Show Once') }}
                        </span>
                    </div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($freshRecoveryCodes as $recoveryCode)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 px-4 py-3 font-mono text-sm font-bold tracking-[0.2em] text-slate-900 dark:text-white">
                                {{ $recoveryCode }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[1.2fr_0.8fr] gap-6">
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h2 class="text-2xl font-black" style="color: var(--color-text);">{{ __('Administrator 2FA') }}</h2>
                            <p class="mt-2 text-sm max-w-2xl" style="color: var(--color-text-muted);">
                                {{ __('Protect your admin account with a time-based code from Google Authenticator, 1Password, Authy, or any compatible app.') }}
                            </p>
                        </div>

                        @if ($admin->hasTwoFactorEnabled())
                            <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-black text-emerald-600 dark:text-emerald-300">
                                {{ __('Enabled') }}
                            </span>
                        @elseif ($pendingSetup)
                            <span class="inline-flex items-center rounded-full bg-amber-500/10 px-3 py-1 text-xs font-black text-amber-600 dark:text-amber-300">
                                {{ __('Pending Confirmation') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-500/10 px-3 py-1 text-xs font-black text-slate-600 dark:text-slate-300">
                                {{ __('Disabled') }}
                            </span>
                        @endif
                    </div>

                    @if ($admin->hasTwoFactorEnabled())
                        <div class="rounded-3xl border border-emerald-500/15 bg-emerald-500/5 p-5">
                            <h3 class="text-lg font-black mb-2 text-emerald-700 dark:text-emerald-300">{{ __('Two-factor authentication is active.') }}</h3>
                            <p class="text-sm" style="color: var(--color-text-muted);">
                                {{ __('Confirmed on') }} {{ optional($admin->two_factor_confirmed_at)->format('Y-m-d H:i') ?? __('Unknown') }}
                            </p>
                            <p class="text-sm mt-2" style="color: var(--color-text-muted);">
                                {{ __('Recovery codes remaining: :count', ['count' => count($admin->two_factor_recovery_codes ?? [])]) }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <form action="{{ route('admin.settings.security.two-factor.recovery-codes') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                                @csrf
                                <button type="submit" class="btn-secondary" :disabled="loading" :class="{ 'opacity-60': loading }">
                                    <span x-show="!loading">{{ __('Regenerate Recovery Codes') }}</span>
                                    <span x-show="loading" x-cloak>{{ __('Generating...') }}</span>
                                </button>
                            </form>
                        </div>

                        <form action="{{ route('admin.settings.security.two-factor.disable') }}" method="POST" class="space-y-4 rounded-3xl border border-rose-500/15 bg-rose-500/5 p-5" x-data="{ loading: false }" @submit="loading = true">
                            @csrf
                            @method('DELETE')
                            <div>
                                <h3 class="text-lg font-black text-rose-700 dark:text-rose-300">{{ __('Disable 2FA') }}</h3>
                                <p class="mt-1 text-sm" style="color: var(--color-text-muted);">
                                    {{ __('Enter your current password to disable two-factor authentication for this admin account.') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Current Password') }}</label>
                                <input type="password" name="password" class="input-glass" autocomplete="current-password" required>
                            </div>
                            <button type="submit" class="inline-flex items-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-black text-white hover:bg-rose-500 transition-colors" :disabled="loading" :class="{ 'opacity-60': loading }">
                                <span x-show="!loading">{{ __('Disable 2FA') }}</span>
                                <span x-show="loading" x-cloak>{{ __('Disabling...') }}</span>
                            </button>
                        </form>
                    @elseif ($pendingSetup)
                        <div class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.1fr] gap-6 items-start">
                            <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 p-5">
                                <h3 class="text-lg font-black mb-4" style="color: var(--color-text);">{{ __('Step 1: Scan the QR code') }}</h3>
                                <div class="inline-flex items-center justify-center rounded-3xl bg-white p-4 shadow-sm">
                                    {!! $qrCodeSvg !!}
                                </div>
                                <p class="mt-4 text-sm" style="color: var(--color-text-muted);">
                                    {{ __('If scanning is unavailable, enter this secret manually in your authenticator app:') }}
                                </p>
                                <div class="mt-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 px-4 py-3 font-mono text-sm font-bold tracking-[0.2em] text-slate-900 dark:text-white break-all">
                                    {{ $pendingSetup['secret'] }}
                                </div>
                            </div>

                            <div class="rounded-3xl border border-primary-500/15 bg-primary-500/5 p-5 space-y-5">
                                <div>
                                    <h3 class="text-lg font-black mb-2" style="color: var(--color-text);">{{ __('Step 2: Confirm setup') }}</h3>
                                    <p class="text-sm" style="color: var(--color-text-muted);">
                                        {{ __('Enter the 6-digit code from your authenticator app to finish enabling two-factor authentication.') }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.settings.security.two-factor.confirm') }}" method="POST" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Authenticator Code') }}</label>
                                        <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" class="input-glass font-mono tracking-[0.3em]" placeholder="123456" required>
                                    </div>
                                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading" :class="{ 'opacity-60': loading }">
                                        <span x-show="!loading">{{ __('Confirm and Enable') }}</span>
                                        <span x-show="loading" x-cloak>{{ __('Confirming...') }}</span>
                                    </button>
                                </form>

                                <form action="{{ route('admin.settings.security.two-factor.setup') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary">{{ __('Generate a New Secret') }}</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black/20 p-6">
                            <h3 class="text-xl font-black mb-3" style="color: var(--color-text);">{{ __('Enable 2FA for this admin account') }}</h3>
                            <ul class="space-y-2 text-sm" style="color: var(--color-text-muted);">
                                <li>{{ __('Adds a second step after password login.') }}</li>
                                <li>{{ __('Supports standard authenticator apps.') }}</li>
                                <li>{{ __('Includes one-time recovery codes for emergencies.') }}</li>
                            </ul>
                            <form action="{{ route('admin.settings.security.two-factor.setup') }}" method="POST" class="mt-6" x-data="{ loading: false }" @submit="loading = true">
                                @csrf
                                <button type="submit" class="btn-primary ripple-btn" :disabled="loading" :class="{ 'opacity-60': loading }">
                                    <span x-show="!loading">{{ __('Start 2FA Setup') }}</span>
                                    <span x-show="loading" x-cloak>{{ __('Preparing...') }}</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card-body space-y-5">
                    <h2 class="text-xl font-black" style="color: var(--color-text);">{{ __('Recommended Practice') }}</h2>
                    <div class="space-y-4 text-sm" style="color: var(--color-text-muted);">
                        <p>{{ __('Use a dedicated authenticator app on a device you control.') }}</p>
                        <p>{{ __('Store recovery codes offline and away from your main password manager if possible.') }}</p>
                        <p>{{ __('After enabling 2FA, rotate any secrets you previously exposed in screenshots or messages.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
