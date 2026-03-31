@extends('layouts.app')

@section('title', __('My Competitions') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <x-student.page-header
            title="{{ __('My') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-primary-500'>{{ __('Competitions') }}</span>"
            subtitle="{{ __('Join live multiplayer learning events and test your skills against fellow students.') }}"
            badge="{{ __('Live Knowledge Checks') }}"
            badgeColor="emerald"
            badgeIcon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z'></path><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg>"
        />

        @if(session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 font-bold flex items-center gap-3 shadow-sm" data-aos="fade-down">
                <div class="shrink-0 w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                {{ session('error') }}
            </div>
        @endif

        {{-- Battle Arena CTA Card --}}
        <x-student.card class="border-t-4 border-t-rose-500 shadow-xl mb-12 relative group" padding="p-6 md:p-8" data-aos="fade-up">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5 text-center md:text-left">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-500/30 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 border border-white/20">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168 11.555 9.036A1 1 0 0 0 10 9.868v4.264a1 1 0 0 0 1.555.832l3.197-2.132a1 1 0 0 0 0-1.664Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7.5h4m8 0h4M6 7.5l1-3h10l1 3M7 10.5l1 9h8l1-9" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-1">{{ __('Battle Arena') }}</h3>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('ui.games.battle_arena_desc') }}</p>
                    </div>
                </div>
                <a href="{{ route('student.battle.index') }}" class="btn-primary ripple-btn shrink-0 px-8 py-3.5 rounded-xl shadow-lg shadow-rose-500/25 font-bold flex items-center gap-2 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 border-rose-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    {{ __('Join a Match Now') }}
                </a>
            </div>
        </x-student.card>

        {{-- Competitions List --}}
        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3 mb-6" data-aos="fade-up">
            <span class="w-8 h-8 rounded-lg bg-primary-500/10 text-primary-500 flex items-center justify-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                </svg>
            </span>
            {{ __('ui.games.scheduled_competitions') }}
        </h2>

        <div class="space-y-4">
            @forelse($games as $game)
                @php
                    $statusColors = [
                        'active' => 'emerald',
                        'scheduled' => 'amber',
                        'completed' => 'slate',
                    ];
                    $statusColor = $statusColors[$game->status] ?? 'rose';
                @endphp
                <x-student.card padding="p-6" class="hover:bg-white/80 dark:hover:bg-slate-900/80 transition-all duration-300 group border-l-[6px] border-l-{{ $statusColor }}-500 overflow-hidden relative" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 300) }}">
                    
                    @if($game->status === 'active')
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-transparent pointer-events-none"></div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
                        <div class="flex items-start md:items-center gap-4 md:gap-6 flex-1 min-w-0 w-full">
                            {{-- Status Icon --}}
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                @if($game->status === 'active')
                                    <svg class="h-6 w-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168 11.555 9.036A1 1 0 0 0 10 9.868v4.264a1 1 0 0 0 1.555.832l3.197-2.132a1 1 0 0 0 0-1.664Z" />
                                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                                    </svg>
                                @elseif($game->status === 'scheduled')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @elseif($game->status === 'completed')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                            
                            {{-- Game Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white truncate max-w-full">{{ $game->title }}</h3>
                                    @if($game->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-black uppercase text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 tracking-wider animate-pulse-soft">
                                            LIVE
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate w-full mb-3">{{ $game->course->title }}</p>
                                
                                <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-500 dark:text-slate-400">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path></svg>
                                        {{ $game->start_time->format('M d, Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $game->start_time->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="sm:shrink-0 w-full sm:w-auto mt-2 sm:mt-0 text-right">
                            @if($game->status === 'active')
                                <a href="{{ route('student.games.room', $game) }}" class="btn-primary ripple-btn w-full sm:w-auto justify-center px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/25 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 border-0 text-white font-bold flex items-center gap-2">
                                    {{ __('Enter Now') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </a>
                            @elseif($game->status === 'scheduled')
                                <span class="inline-flex justify-center w-full sm:w-auto items-center gap-2 px-6 py-3 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-500 text-sm font-bold border border-amber-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Coming Soon') }}
                                </span>
                            @elseif($game->status === 'completed')
                                <a href="{{ route('student.games.room', $game) }}" class="btn-secondary w-full sm:w-auto justify-center px-6 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                                    {{ __('View Results') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @else
                                <span class="inline-flex justify-center w-full sm:w-auto items-center gap-2 px-6 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 text-sm font-bold border border-slate-200 dark:border-slate-700">
                                    {{ __('Closed') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </x-student.card>
            @empty
                <x-student.card padding="p-12" class="text-center relative overflow-hidden bg-white/50 dark:bg-slate-900/50" data-aos="fade-up">
                    <div class="relative z-10 w-24 h-24 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-6 shadow-inner text-primary-500">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2 relative z-10">{{ __('No Competitions Available') }}</h3>
            </div>
        @endif
    </div>
</div>
@endsection

