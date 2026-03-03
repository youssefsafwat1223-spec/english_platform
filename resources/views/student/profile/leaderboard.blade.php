@extends('layouts.app')

@section('title', __('Leaderboard') . ' — ' . config('app.name'))

@section('content')
@php $currentUser = auth()->user(); @endphp
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-8" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>🏆</span> {{ __('Rankings') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Top') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Leaderboard') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Top students by points. See where you stand among your peers!') }}
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

        {{-- Your Rank --}}
        <div class="glass-card p-6 mb-8 text-center gradient-border" data-aos="fade-up">
            <div class="text-2xl font-extrabold text-primary-500 mb-1">{{ __('Your Rank') }}: #{{ $userRank }}</div>
            <div style="color: var(--color-text-muted);">{{ $currentUser->total_points }} {{ __('points_label') }}</div>
        </div>

        {{-- Leaderboard Table --}}
        <div class="glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Rank') }}</th>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Points') }}</th>
                            <th>{{ __('Streak') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $index => $student)
                            <tr class="{{ $student->id === $currentUser->id ? 'bg-primary-500/10' : '' }}">
                                <td>
                                    @if($index < 3)
                                        <span class="text-xl">{{ ['🥇','🥈','🥉'][$index] }}</span>
                                    @else
                                        <span class="font-bold" style="color: var(--color-text);">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-sm font-bold">{{ substr($student->name, 0, 1) }}</div>
                                        <span class="font-semibold {{ $student->id === $currentUser->id ? 'text-primary-500' : '' }}" style="{{ $student->id !== $currentUser->id ? 'color: var(--color-text);' : '' }}">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="font-bold text-primary-500">{{ $student->total_points }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1">
                                        🔥 {{ $student->current_streak }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
