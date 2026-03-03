@extends('layouts.admin')
@section('title', '⚔️ Battle Settings')
@section('content')
<div class="">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.settings.index') }}" class="text-sm" style="color: var(--color-text-muted);">
                    {{ __('← Settings') }}
                </a>
            </div>
            <h1 class="text-3xl font-extrabold text-gradient">{{ __('⚔️ Battle Arena Settings') }}</h1>
            <p class="mt-1 text-sm" style="color: var(--color-text-muted);">
                {{ __('Configure timers, team sizes, and question counts for the live battle quiz game.') }}
            </p>
        </div>

        {{-- Success --}}
        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-medium flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.battle.update') }}" data-aos="fade-up">
            @csrf

            {{-- Lobby Timer --}}
            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: var(--color-text);">
                    {{ __('⏳ Lobby & Timing') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ __('Lobby Wait Time') }}<span class="font-normal text-xs" style="color: var(--color-text-muted);">(seconds)</span>
                        </label>
                        <input type="number"
                               name="battle_lobby_timer"
                               value="{{ old('battle_lobby_timer', $settings['battle_lobby_timer']) }}"
                               min="30" max="600"
                               class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">How long players wait in lobby before game starts. Default: 120s (2 min)</p>
                        @error('battle_lobby_timer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ __('Question Time Limit') }}<span class="font-normal text-xs" style="color: var(--color-text-muted);">(seconds)</span>
                        </label>
                        <input type="number"
                               name="battle_question_timer"
                               value="{{ old('battle_question_timer', $settings['battle_question_timer']) }}"
                               min="10" max="120"
                               class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Time each player has to answer one question. Default: 30s') }}</p>
                        @error('battle_question_timer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Questions per Match --}}
            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: var(--color-text);">
                    {{ __('❓ Questions per Match') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('Minimum Questions') }}</label>
                        <input type="number"
                               name="battle_min_questions"
                               value="{{ old('battle_min_questions', $settings['battle_min_questions']) }}"
                               min="1" max="50"
                               class="input-glass">
                        @error('battle_min_questions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('Maximum Questions') }}</label>
                        <input type="number"
                               name="battle_max_questions"
                               value="{{ old('battle_max_questions', $settings['battle_max_questions']) }}"
                               min="1" max="50"
                               class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Questions are randomly selected. Default: 5–15') }}</p>
                        @error('battle_max_questions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Players --}}
            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: var(--color-text);">
                    {{ __('👥 Players per Room') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('Minimum Players to Start') }}</label>
                        <input type="number"
                               name="battle_min_players"
                               value="{{ old('battle_min_players', $settings['battle_min_players']) }}"
                               min="2" max="10"
                               class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('If fewer players join, the game is cancelled. Default: 2') }}</p>
                        @error('battle_min_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('Maximum Players per Room') }}</label>
                        <input type="number"
                               name="battle_max_players"
                               value="{{ old('battle_max_players', $settings['battle_max_players']) }}"
                               min="2" max="10"
                               class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Max 10 (5 per team). Default: 10') }}</p>
                        @error('battle_max_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Current Values Preview --}}
            <div class="glass-card p-5 mb-8 border-l-4 border-primary-500">
                <p class="text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('📋 Current Settings Summary') }}</p>
                <div class="flex flex-wrap gap-4 text-sm" style="color: var(--color-text-muted);">
                    <span>{{ __('⏳ Lobby:') }}<strong class="text-primary-500">{{ $settings['battle_lobby_timer'] }}s</strong></span>
                    <span>{{ __('⏱️ Per Question:') }}<strong class="text-primary-500">{{ $settings['battle_question_timer'] }}s</strong></span>
                    <span>{{ __('❓ Questions:') }}<strong class="text-primary-500">{{ $settings['battle_min_questions'] }}–{{ $settings['battle_max_questions'] }}</strong></span>
                    <span>{{ __('👥 Players:') }}<strong class="text-primary-500">{{ $settings['battle_min_players'] }}–{{ $settings['battle_max_players'] }}</strong></span>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary btn-lg">
                    {{ __('💾 Save Battle Settings') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
