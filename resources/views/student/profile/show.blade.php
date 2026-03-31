@extends('layouts.app')

@section('title', __('Profile') . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen py-16 relative overflow-hidden bg-slate-50 dark:bg-[#020617] transition-colors duration-500">

@section('content')
<div class="min-h-screen py-16 relative overflow-hidden bg-slate-50 dark:bg-[#020617] transition-colors duration-500">
    {{-- Ambient Background Orbs --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-primary-500/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-accent-500/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10">
        
        {{-- Floating Profile Header Pill --}}
        <div class="mb-12" data-aos="fade-down">
            <div class="p-1 rounded-[1.5rem] lg:rounded-[2rem] bg-gradient-to-br from-white/60 to-white/20 dark:from-white/10 dark:to-white/5 backdrop-blur-2xl border border-white/50 dark:border-white/10 shadow-lg shadow-slate-200/20 dark:shadow-none overflow-hidden relative group">
                
                {{-- Animated subtle border glow --}}
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-primary-500/20 to-transparent group-hover:translate-x-full transition-transform duration-1000"></div>

                <div class="bg-white dark:bg-[#0f172a] rounded-[1.3rem] lg:rounded-[1.8rem] p-8 flex flex-col md:flex-row items-center gap-8 relative z-10">
                    
                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-[2rem] object-cover ring-2 ring-white/50 dark:ring-white/10 shadow-xl">
                        @else
                            <div class="w-32 h-32 rounded-[2rem] bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-5xl font-black text-white shadow-xl shadow-primary-500/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        {{-- Online indicator / Rank Badge --}}
                        <div class="absolute -bottom-3 -right-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-3 py-1.5 rounded-xl text-xs font-bold shadow-lg border-2 border-white dark:border-[#0f172a] flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ __('ui.profile.level') }} {{ $stats['rank'] }}
                        </div>
                    </div>

                    {{-- User Info --}}
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-2">{{ $user->name }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium mb-6">
                            {{ $user->email }}
                            @if($user->phone)
                                <span class="mx-2">&middot;</span>{{ $user->phone }}
                            @endif
                        </p>

                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ route('student.profile.edit') }}" class="px-6 py-2.5 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold hover:scale-105 transition-transform flex items-center gap-2">
                                <span>{{ __('Edit Profile') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <a href="{{ route('student.profile.change-password') }}" class="px-6 py-2.5 rounded-full bg-white dark:bg-white/5 text-slate-700 dark:text-white border border-slate-200 dark:border-white/10 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                                {{ __('Password & Security') }}
                            </a>
                        </div>
                    </div>

                    {{-- Quick Stats Right --}}
                    <div class="flex md:flex-col gap-6 md:pl-8 md:border-l border-slate-200 dark:border-white/10 shrink-0">
                        <div class="text-center md:text-right">
                            <div class="text-3xl font-black text-primary-500">{{ $stats['total_points'] }}</div>
                            <div class="text-xs font-bold tracking-wider text-slate-400 uppercase">{{ __('ui.profile.points') }}</div>
                        </div>
                        <div class="text-center md:text-right">
                            <div class="text-3xl font-black text-amber-500 flex items-center justify-center md:justify-end gap-1">
                                {{ $user->current_streak }}
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c1.2 2.2 1.8 3.8 1.8 4.8 0 1.7-1.1 2.8-2.2 3.8-1 1-2 2-2 3.7a4.4 4.4 0 0 0 8.8 0c0-3.1-2.1-5.2-6.4-12.3Z" />
                                </svg>
                            </div>
                            <div class="text-xs font-bold tracking-wider text-slate-400 uppercase">{{ __('ui.profile.day_streak') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Integrations & Details --}}
            <div class="space-y-8" data-aos="fade-up" data-aos-delay="100">
                
                {{-- Telegram Integration --}}
                <x-student.card class="relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#0088cc]/10 rounded-full blur-xl group-hover:bg-[#0088cc]/20 transition-colors"></div>
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-[#0088cc]/10 text-[#0088cc] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white mb-1">{{ __('Telegram Bot') }}</h3>
                            @if($user->telegram_chat_id)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ __('Connected') }}</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('ui.profile.telegram_receiving') }}</p>
                            @else
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="text-sm font-medium text-amber-600 dark:text-amber-400">{{ __('Not Connected') }}</span>
                                </div>
                                <a href="{{ route('student.onboarding') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0088cc] hover:underline">
                                    {{ __('Connect now') }}
                                    <svg class="h-3.5 w-3.5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.25" d="M5 12h14m-6-6 6 6-6 6" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </x-student.card>

                {{-- Referral System --}}
                <x-student.card class="bg-gradient-to-br from-primary-500/5 to-accent-500/5 border-primary-500/20 shadow-xl shadow-primary-500/5 text-center">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center justify-center gap-2">
                        <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.5v13m6.5-6.5h-13" />
                        </svg>
                        {{ __('Refer & Earn') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('ui.profile.referral_text') }}</p>
                    
                    <div class="bg-white dark:bg-black/20 p-3 rounded-xl mb-4 border border-slate-200 dark:border-white/5">
                        <div class="font-mono text-xl font-black tracking-widest text-primary-500">{{ $user->referral_code }}</div>
                    </div>
                    
                    <a href="{{ route('student.referrals.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-slate-700 dark:text-white hover:text-primary-500 transition-colors">
                        {{ __('View Referrals') }}
                        <svg class="h-3.5 w-3.5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.25" d="M5 12h14m-6-6 6 6-6 6" />
                        </svg>
                    </a>
                </x-student.card>
                
                {{-- Account Details --}}
                <div class="px-6 space-y-4">
                    <h3 class="text-xs font-bold tracking-widest text-slate-400 uppercase">{{ __('Details') }}</h3>
                    @if($user->address)
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Location') }}</div>
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->address }}</div>
                        </div>
                    @endif
                    @if($user->secondary_email)
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Backup Email') }}</div>
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->secondary_email }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Member Since') }}</div>
                        <div class="font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('F Y') }}</div>
                    </div>
                </div>

            </div>

            {{-- Right Column --}}
            <div class="lg:col-span-2 space-y-8" data-aos="fade-up" data-aos-delay="200">
                
                {{-- Horizontal Floating Stats --}}
                <div class="flex overflow-x-auto pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 gap-4 snap-x hide-scrollbar">
                    @php
                        $profileStats = [
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10" />', 'value' => $stats['total_enrollments'], 'label' => __('Enrolled'), 'color' => 'from-blue-500 to-indigo-500'],
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />', 'value' => $stats['completed_courses'], 'label' => __('Completed'), 'color' => 'from-emerald-500 to-teal-500'],
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6" />', 'value' => $stats['certificates'], 'label' => __('Certificates'), 'color' => 'from-purple-500 to-fuchsia-500'],
                            ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75-5.228 2.749 1-5.823-4.23-4.126 5.846-.849L12 4.5l2.612 5.201 5.846.849-4.23 4.126 1 5.823L12 17.75Z" />', 'value' => $user->achievements()->count(), 'label' => __('Achievements'), 'color' => 'from-amber-500 to-orange-500'],
                        ];
                    @endphp
                    @foreach($profileStats as $s)
                        <div class="snap-start shrink-0 w-32 p-4 rounded-[1.5rem] bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5 shadow-lg shadow-slate-200/20 dark:shadow-none hover:scale-105 transition-transform">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $s['color'] }} flex items-center justify-center mb-3 shadow-lg text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">{!! $s['icon'] !!}</svg>
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white mb-0.5">{{ $s['value'] }}</div>
                            <div class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $s['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- My Courses --}}
                <div>
                    <div class="flex justify-between items-end mb-4">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Active Courses') }}</h3>
                        <a href="{{ route('student.dashboard') }}" class="text-sm font-bold text-primary-500 hover:text-primary-600">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($user->enrollments()->with('course')->latest()->take(3)->get() as $enrollment)
                            <div class="p-4 rounded-[1.5rem] bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5 hover:border-primary-500/30 transition-colors flex items-center gap-4 group">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-black/20 flex flex-col items-center justify-center shrink-0 border border-slate-200 dark:border-white/5 group-hover:bg-primary-500/10 transition-colors">
                                    <span class="text-xs font-bold text-primary-500">{{ round($enrollment->progress_percentage) }}%</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-900 dark:text-white truncate group-hover:text-primary-500 transition-colors">{{ $enrollment->course->title }}</h4>
                                    <div class="w-full h-1.5 bg-slate-100 dark:bg-black/40 rounded-full mt-2 overflow-hidden">
                                        <div class="h-full rounded-full {{ $enrollment->is_completed ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary-500 to-accent-500' }}" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                </div>
                                @if($enrollment->is_completed)
                                    <div class="shrink-0 text-emerald-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center rounded-[1.5rem] border-2 border-dashed border-slate-300 dark:border-white/10">
                                <p class="text-slate-500 dark:text-slate-400 font-medium mb-2">{{ __("You are not enrolled in the course.") }}</p>
                                <a href="{{ route('student.courses.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-primary-500">
                                    {{ __('Browse Course') }}
                                    <svg class="h-3.5 w-3.5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.25" d="M5 12h14m-6-6 6 6-6 6" />
                                    </svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Achievements Showcase --}}
                @if($user->achievements()->count() > 0)
                <div>
                    <div class="flex justify-between items-end mb-4">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Showcase') }}</h3>
                        <a href="{{ route('student.profile.achievements') }}" class="text-sm font-bold text-primary-500 hover:text-primary-600">{{ __('All Trophies') }}</a>
                    </div>
                    <x-student.card padding="p-6">
                        <div class="flex flex-wrap gap-4">
                            @foreach($user->achievements()->take(6)->get() as $achievement)
                                <div class="relative group cursor-pointer" title="{{ $achievement->name }}">
                                    <div class="w-16 h-16 rounded-[1.25rem] bg-slate-50 dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 border-b-4 border-b-amber-500/30 flex items-center justify-center text-3xl shadow-sm group-hover:-translate-y-1 group-hover:border-b-amber-500 transition-all">
                                        {{ $achievement->icon ?? '★' }}
                                    </div>
                                </div>
                            @endforeach
                            @if($user->achievements()->count() > 6)
                                <a href="{{ route('student.profile.achievements') }}" class="w-16 h-16 rounded-[1.25rem] bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/5 flex items-center justify-center text-sm font-bold text-slate-500 hover:text-primary-500 transition-colors">
                                    +{{ $user->achievements()->count() - 6 }}
                                </a>
                            @endif
                        </div>
                    </x-student.card>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<style>
/* Hide scrollbar for the horizontal stats on webkit */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
@endsection
