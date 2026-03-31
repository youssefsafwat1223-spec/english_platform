@extends('layouts.app')

@section('title', __('Achievements') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="student-container relative z-10">
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('My') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-primary-500'>{{ __('Achievements') }}</span>"
            subtitle="{{ __('Track your progress, unlock rewards, and showcase your milestones.') }}"
            badge="{{ __('Rewards') }}"
            badgeIcon='<svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>'
            badgeColor="amber"
            mb="mb-12"
        >
            <x-slot name="actions">
                <a href="{{ route('student.profile.show') }}" class="btn-ghost flex items-center justify-center gap-2 px-6 py-3 font-bold rounded-xl w-full sm:w-auto transition-colors shadow-sm bg-white/10 hover:bg-white/20 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 group">
                    <svg class="w-5 h-5 mr-0 rtl:ml-2 rtl:-mr-2 group-hover:-translate-x-1 rtl:group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Profile') }}
                </a>
            </x-slot>
        </x-student.page-header>

        {{-- Earned --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-slate-900 dark:text-white">
                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                {{ __('Earned Achievements') }} ({{ $earned->count() }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($earned as $achievement)
                    <x-student.card padding="p-6" class="text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="text-4xl mb-3 group-hover:scale-125 transition-transform">{{ $achievement->icon ?? '🏆' }}</div>
                        <h3 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $achievement->name }}</h3>
                        <p class="text-sm mb-3 line-clamp-2 text-slate-500 dark:text-slate-400">{{ $achievement->description }}</p>
                        <div class="text-sm font-bold text-primary-500">+{{ $achievement->points }} {{ __('points') }}</div>
                        <div class="text-xs text-emerald-500 mt-2">{{ __('Earned') }} {{ optional($achievement->pivot->earned_at)->format('M d, Y') }}</div>
                    </x-student.card>
                @empty
                    <div class="col-span-4">
                        <x-student.empty-state
                            title="{{ __('No achievements earned yet.') }}"
                            message="{{ __('Complete lessons, quizzes, and battles to unlock your first achievements.') }}"
                        >
                            <x-slot name="icon">
                                <svg class="h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8M12 17v4M7 4h10a1 1 0 0 1 1 1v2a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5V5a1 1 0 0 1 1-1Z"/>
                                </svg>
                            </x-slot>
                        </x-student.empty-state>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Available --}}
        <div>
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-slate-900 dark:text-white">
                <span class="w-2 h-6 bg-gray-400 rounded-full"></span>
                {{ __('Available Achievements') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($available as $achievement)
                    <x-student.card padding="p-6" class="text-center opacity-60 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="text-4xl mb-3 grayscale group-hover:grayscale-0 transition-all">{{ $achievement->icon ?? '🏆' }}</div>
                        <h3 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $achievement->name }}</h3>
                        <p class="text-sm mb-3 line-clamp-2 text-slate-500 dark:text-slate-400">{{ $achievement->description }}</p>
                        <div class="text-sm font-bold text-slate-500 dark:text-slate-400">+{{ $achievement->points }} {{ __('points') }}</div>
                        <div class="text-xs mt-2 text-slate-500 dark:text-slate-400">🔒 {{ __('Locked') }}</div>
                    </x-student.card>
                @empty
                    <div class="col-span-4">
                        <x-student.card padding="p-8" class="text-center text-emerald-500 font-medium">🎉 {{ __('You have unlocked all achievements!') }}</x-student.card>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection






