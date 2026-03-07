@extends('layouts.app')

@section('title', '⚔️ ' . __('Battle Arena'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-12" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>⚔️</span> {{ __('Battle Arena') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Team') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Quiz Battle') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Join a real-time team battle! Get matched with other students, answer questions from your course, and compete for the win.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Active Room Banner --}}
        @if($activeRoom)
        <div class="glass-card p-6 mb-8 border-l-4 border-amber-500" data-aos="fade-up">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl animate-pulse">🔴</span>
                    <div>
                        <h3 class="font-bold text-lg" style="color: var(--color-text);">{{ __("You're in an active battle!") }}</h3>
                        <p class="text-sm" style="color: var(--color-text-muted);">{{ $activeRoom->course->title }} — {{ ucfirst($activeRoom->status) }}</p>
                    </div>
                </div>
                <a href="{{ $activeRoom->status === 'playing' ? route('student.battle.play', $activeRoom) : route('student.battle.lobby', $activeRoom) }}"
                   class="btn-primary btn-lg">
                    {{ $activeRoom->status === 'playing' ? '🎮 ' . __('Return to Battle') : '⏳ ' . __('Return to Lobby') }}
                </a>
            </div>
        </div>
        @endif

        {{-- Course Grid --}}
        @if($enrolledCourses->isEmpty())
        <div class="glass-card p-12 text-center" data-aos="fade-up">
            <span class="text-6xl mb-4 block">📚</span>
            <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">{{ __('Not Enrolled') }}</h3>
            <p class="mb-6" style="color: var(--color-text-muted);">{{ __('You are not enrolled in the course.') }}</p>
            <a href="{{ route('student.courses.index') }}" class="btn-primary">{{ __('Browse Course') }}</a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolledCourses as $course)
            <div class="glass-card overflow-hidden group hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                {{-- Course Header --}}
                <div class="relative h-40 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/30 to-blue-500/30"></div>
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl" style="background: linear-gradient(135deg, #ef4444, #3b82f6);">
                            ⚔️
                        </div>
                    @endif
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <h3 class="text-white font-bold text-lg">{{ $course->title }}</h3>
                    </div>
                </div>

                {{-- Course Body --}}
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4 text-sm" style="color: var(--color-text-muted);">
                        <div class="flex items-center gap-1.5">
                            <span>❓</span>
                            <span>{{ $course->questions_count }} {{ __('Questions') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span>👥</span>
                            <span>{{ __('5v5 Teams') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4 text-xs" style="color: var(--color-text-muted);">
                        <span class="px-2 py-1 rounded-full" style="background: var(--color-border);">⏱️ {{ __('30s per question') }}</span>
                        <span class="px-2 py-1 rounded-full" style="background: var(--color-border);">🎲 {{ __('Random questions') }}</span>
                    </div>

                    <form action="{{ route('student.battle.join', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn-primary py-3 text-center font-bold group-hover:shadow-lg transition-shadow"
                                @if($activeRoom) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                            ⚔️ {{ __('Join Battle') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- How it Works --}}
        <div class="mt-16 glass-card p-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-6 text-center" style="color: var(--color-text);">🎮 {{ __('How Battle Works') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">1</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ __('Join') }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Click "Join Battle" and wait for your turn to enter the battle') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">2</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ __('Match') }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Players are split into Red & Blue teams') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">3</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ __('Answer') }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Each player answers individually — points go to your team') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">4</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ __('Win') }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Team with the most points wins! Everyone earns XP') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
