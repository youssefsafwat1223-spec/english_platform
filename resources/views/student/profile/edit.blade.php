@extends('layouts.app')

@section('title', __('Edit Profile') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-8" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 text-sm font-bold mb-4 shadow-sm">
                        <svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        {{ __('Settings') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Edit') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-primary-500">{{ __('Profile') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Update your personal information.') }}
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('student.profile.show') }}" class="inline-flex justify-center items-center px-6 py-3 bg-white/10 hover:bg-white/20 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-white font-bold rounded-xl transition-all duration-300 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 group">
                        <svg class="w-5 h-5 mr-2 rtl:ml-2 rtl:mr-0 group-hover:-translate-x-1 rtl:group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ __('Back to Profile') }}
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="glass-card p-4 mb-6 border-l-4 border-red-500" data-aos="fade-up">
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

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true" data-aos="fade-up">
            @csrf
            @method('PUT')

            <div class="glass-card overflow-hidden">
                <div class="glass-card-body space-y-6">

                    {{-- Personal Information --}}
                    <h3 class="text-lg font-bold" style="color: var(--color-text);">{{ __('Personal Information') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Full Name') }} *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="text" name="name" class="input-glass pl-12" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Email') }} *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input type="email" name="email" class="input-glass pl-12" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Phone') }} *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <input type="tel" name="phone" class="input-glass pl-12" value="{{ old('phone', $user->phone) }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Secondary Email') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="email" name="secondary_email" class="input-glass pl-12" value="{{ old('secondary_email', $user->secondary_email) }}" placeholder="{{ __('Optional backup email') }}">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Address') }}</label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <textarea name="address" rows="2" class="input-glass pl-12" placeholder="{{ __('Enter your address') }}">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    {{-- Telegram Section --}}
                    <hr style="border-color: var(--color-border);">
                    <h3 class="text-lg font-bold" style="color: var(--color-text);">{{ __('Telegram') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Telegram Username') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <input type="text" name="telegram_username" class="input-glass pl-12" value="{{ old('telegram_username', $user->telegram_username) }}" placeholder="@username">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Telegram Bot Status') }}</label>
                            @if($user->telegram_chat_id)
                                <div class="flex items-center gap-3 p-3 rounded-xl" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <div>
                                        <span class="text-sm font-semibold text-emerald-500">{{ __('Connected') }}</span>
                                        <p class="text-xs" style="color: var(--color-text-muted);">{{ __('Linked on') }}: {{ $user->telegram_linked_at ? $user->telegram_linked_at->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3 p-3 rounded-xl" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3);">
                                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                    <div>
                                        <span class="text-sm font-semibold text-amber-500">{{ __('Not Connected') }}</span>
                                        <p class="text-xs" style="color: var(--color-text-muted);">{{ __('Send /start to') }} @{{ config('services.telegram.bot_username', 'skdlk_bot') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Avatar Section --}}
                    <hr style="border-color: var(--color-border);">
                    <h3 class="text-lg font-bold" style="color: var(--color-text);">{{ __('Profile Picture') }}</h3>

                    <div>
                        <input type="file" name="avatar" accept="image/*" class="input-glass file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-500/10 file:text-primary-500 hover:file:bg-primary-500/20">
                        @if($user->avatar)
                            <div class="mt-4 flex items-center gap-4">
                                <img src="{{ Storage::url($user->avatar) }}" class="w-16 h-16 rounded-full ring-2 ring-primary-500/30" alt="{{ $user->name }}">
                                <span class="text-sm" style="color: var(--color-text-muted);">{{ __('Current avatar') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="glass-card-footer flex justify-between items-center">
                    <a href="{{ route('student.profile.show') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading">
                        <span x-show="!loading">{{ __('Update Profile') }}</span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            {{ __('Updating...') }}
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
