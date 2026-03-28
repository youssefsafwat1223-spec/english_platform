@extends('layouts.admin')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', $isArabic ? 'إعدادات الباتل' : 'Battle Settings')

@section('content')
<div class="">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8" data-aos="fade-down">
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.settings.index') }}" class="text-sm" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'العودة إلى الإعدادات' : 'Back to settings' }}
                </a>
            </div>
            <h1 class="text-3xl font-extrabold text-gradient">
                {{ $isArabic ? 'إعدادات الباتل' : 'Battle Arena Settings' }}
            </h1>
            <p class="mt-1 text-sm" style="color: var(--color-text-muted);">
                {{ $isArabic
                    ? 'تحكم في توقيت اللوبي، زمن السؤال، وعدد اللاعبين والأسئلة، وإغلاق الرومات الخاملة.'
                    : 'Control lobby timing, question timing, player/question counts, and stale-room cleanup.' }}
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.battle.update') }}" data-aos="fade-up">
            @csrf

            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'التوقيت' : 'Timing' }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'مدة انتظار اللوبي' : 'Lobby wait time' }}
                        </label>
                        <input type="number" name="battle_lobby_timer" value="{{ old('battle_lobby_timer', $settings['battle_lobby_timer']) }}" min="30" max="600" class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">
                            {{ $isArabic ? 'عدد الثواني قبل بدء الباتل أو إلغائه.' : 'Number of seconds before the lobby starts or is cancelled.' }}
                        </p>
                        @error('battle_lobby_timer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'زمن كل سؤال' : 'Question time limit' }}
                        </label>
                        <input type="number" name="battle_question_timer" value="{{ old('battle_question_timer', $settings['battle_question_timer']) }}" min="10" max="120" class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">
                            {{ $isArabic ? 'المدة المتاحة للإجابة على كل سؤال.' : 'How long each player has to answer one question.' }}
                        </p>
                        @error('battle_question_timer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'إغلاق الروم بسبب عدم النشاط' : 'Inactivity timeout' }}
                        </label>
                        <input type="number" name="battle_inactivity_timeout" value="{{ old('battle_inactivity_timeout', $settings['battle_inactivity_timeout']) }}" min="30" max="900" class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">
                            {{ $isArabic
                                ? 'إذا توقفت المباراة ولم يعد اللاعبون يجيبون، تُغلق الغرفة تلقائيًا بعد هذه المدة بالثواني.'
                                : 'If the match stalls and players stop answering, the room is closed automatically after this many seconds.' }}
                        </p>
                        @error('battle_inactivity_timeout') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'عدد الأسئلة في كل مباراة' : 'Questions per match' }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'الحد الأدنى للأسئلة' : 'Minimum questions' }}
                        </label>
                        <input type="number" name="battle_min_questions" value="{{ old('battle_min_questions', $settings['battle_min_questions']) }}" min="1" max="50" class="input-glass">
                        @error('battle_min_questions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'الحد الأقصى للأسئلة' : 'Maximum questions' }}
                        </label>
                        <input type="number" name="battle_max_questions" value="{{ old('battle_max_questions', $settings['battle_max_questions']) }}" min="1" max="50" class="input-glass">
                        @error('battle_max_questions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 mb-6">
                <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'عدد اللاعبين' : 'Players per room' }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'الحد الأدنى لبدء الباتل' : 'Minimum players to start' }}
                        </label>
                        <input type="number" name="battle_min_players" value="{{ old('battle_min_players', $settings['battle_min_players']) }}" min="2" max="10" class="input-glass">
                        @error('battle_min_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">
                            {{ $isArabic ? 'الحد الأقصى للاعبين' : 'Maximum players per room' }}
                        </label>
                        <input type="number" name="battle_max_players" value="{{ old('battle_max_players', $settings['battle_max_players']) }}" min="2" max="10" class="input-glass">
                        @error('battle_max_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="glass-card p-5 mb-8 border-l-4 border-primary-500">
                <p class="text-sm font-semibold mb-2" style="color: var(--color-text);">
                    {{ $isArabic ? 'ملخص الإعدادات الحالية' : 'Current settings summary' }}
                </p>
                <div class="flex flex-wrap gap-4 text-sm" style="color: var(--color-text-muted);">
                    <span>{{ $isArabic ? 'اللوبي:' : 'Lobby:' }} <strong class="text-primary-500">{{ $settings['battle_lobby_timer'] }}s</strong></span>
                    <span>{{ $isArabic ? 'السؤال:' : 'Question:' }} <strong class="text-primary-500">{{ $settings['battle_question_timer'] }}s</strong></span>
                    <span>{{ $isArabic ? 'عدم النشاط:' : 'Inactivity:' }} <strong class="text-primary-500">{{ $settings['battle_inactivity_timeout'] }}s</strong></span>
                    <span>{{ $isArabic ? 'الأسئلة:' : 'Questions:' }} <strong class="text-primary-500">{{ $settings['battle_min_questions'] }}-{{ $settings['battle_max_questions'] }}</strong></span>
                    <span>{{ $isArabic ? 'اللاعبون:' : 'Players:' }} <strong class="text-primary-500">{{ $settings['battle_min_players'] }}-{{ $settings['battle_max_players'] }}</strong></span>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary btn-lg">
                    {{ $isArabic ? 'حفظ إعدادات الباتل' : 'Save battle settings' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
