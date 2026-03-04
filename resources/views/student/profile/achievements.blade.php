@extends('layouts.app')

@section('title', __('Achievements') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-12" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-sm font-bold mb-4 shadow-sm">
                        <svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        {{ __('Rewards') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('My') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-primary-500">{{ __('Achievements') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Track your progress, unlock rewards, and showcase your milestones.') }}
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

        {{-- Earned --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2" style="color: var(--color-text);">
                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                {{ __('Earned Achievements') }} ({{ $earned->count() }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($earned as $achievement)
                    <div class="glass-card p-6 text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="text-4xl mb-3 group-hover:scale-125 transition-transform">{{ $achievement->icon ?? '🏆' }}</div>
                        <h3 class="font-bold mb-1" style="color: var(--color-text);">{{ $achievement->name }}</h3>
                        <p class="text-sm mb-3 line-clamp-2" style="color: var(--color-text-muted);">{{ $achievement->description }}</p>
                        <div class="text-sm font-bold text-primary-500">+{{ $achievement->points }} {{ __('points') }}</div>
                        <div class="text-xs text-emerald-500 mt-2">{{ __('Earned') }} {{ optional($achievement->pivot->earned_at)->format('M d, Y') }}</div>
                    </div>
                @empty
                    <div class="col-span-4 glass-card p-8 text-center" style="color: var(--color-text-muted);">{{ __('No achievements earned yet.') }}</div>
                @endforelse
            </div>
        </div>

        {{-- Available --}}
        <div>
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2" style="color: var(--color-text);">
                <span class="w-2 h-6 bg-gray-400 rounded-full"></span>
                {{ __('Available Achievements') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($available as $achievement)
                    <div class="glass-card p-6 text-center opacity-60 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="text-4xl mb-3 grayscale group-hover:grayscale-0 transition-all">{{ $achievement->icon ?? '🏆' }}</div>
                        <h3 class="font-bold mb-1" style="color: var(--color-text);">{{ $achievement->name }}</h3>
                        <p class="text-sm mb-3 line-clamp-2" style="color: var(--color-text-muted);">{{ $achievement->description }}</p>
                        <div class="text-sm font-bold" style="color: var(--color-text-muted);">+{{ $achievement->points }} {{ __('points') }}</div>
                        <div class="text-xs mt-2" style="color: var(--color-text-muted);">🔒 {{ __('Locked') }}</div>
                    </div>
                @empty
                    <div class="col-span-4 glass-card p-8 text-center text-emerald-500 font-medium">🎉 {{ __('You have unlocked all achievements!') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
