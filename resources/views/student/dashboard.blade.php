@extends('layouts.app')

@section('title', __('Dashboard') . ' - ' . config('app.name'))

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('content')
<div class="pt-2 pb-12 sm:pt-4 sm:pb-12 relative min-h-screen z-10 px-3 sm:px-0">
    <div class="student-container space-y-4 sm:space-y-8">

        @if($featuredBanner && $featuredBanner['type'] === 'promo')
            <div class="relative overflow-hidden rounded-[1.9rem] border border-white/10 shadow-[0_18px_60px_-30px_rgba(14,165,233,0.45)] backdrop-blur-xl"
                 data-aos="fade-down"
                 style="background:
                    radial-gradient(circle at 8% 30%, rgba(34,211,238,0.20), transparent 24%),
                    radial-gradient(circle at 82% 24%, rgba(59,130,246,0.16), transparent 28%),
                    linear-gradient(135deg, rgba(8,15,33,0.96), rgba(18,34,64,0.94) 48%, rgba(9,20,40,0.96));">
                <div class="absolute inset-0 opacity-25 pointer-events-none"
                     style="background-image:
                        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px),
                        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
                        background-size: 28px 28px;"></div>
                <div class="absolute inset-y-0 start-0 w-28 bg-gradient-to-r from-cyan-400/20 to-transparent pointer-events-none"></div>

                <div class="relative z-10 px-4 sm:px-6 py-4 sm:py-5 flex flex-col xl:flex-row xl:items-center gap-4 xl:gap-6">
                    <div class="flex items-start sm:items-center gap-3 sm:gap-4 min-w-0 flex-1">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white/10 border border-white/10 text-cyan-200 shadow-lg shadow-cyan-950/30 flex items-center justify-center shrink-0 backdrop-blur-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M6 4h12a2 2 0 0 1 2 2v12l-4-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] sm:text-[11px] font-black uppercase tracking-[0.24em] bg-cyan-300/12 text-cyan-200 border border-cyan-300/15">
                                    {{ __('live_sessions.promo.special_offer') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    {{ __('live_sessions.promo.available_now') }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-1.5">
                                <h3 class="text-base sm:text-lg lg:text-[1.15rem] font-black text-white leading-tight truncate">
                                    {{ $featuredBanner['title'] }}
                                </h3>
                                <p class="text-sm text-slate-300/95 font-medium leading-relaxed max-w-3xl">
                                    {{ $featuredBanner['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($featuredBanner['action_label'] && $featuredBanner['action_url'])
                        <div class="shrink-0 xl:self-center">
                            <a href="{{ $featuredBanner['action_url'] }}"
                               class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-3 rounded-2xl bg-gradient-to-r from-cyan-400 to-sky-500 text-slate-950 hover:from-cyan-300 hover:to-sky-400 font-black text-sm transition-all shadow-lg shadow-cyan-950/35 min-w-[148px]">
                                {{ $featuredBanner['action_label'] }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($featuredBanner && $featuredBanner['type'] === 'live-session')
            <div class="rounded-[2rem] border overflow-hidden shadow-xl"
                 data-aos="fade-down"
                 style="background: linear-gradient(135deg, rgba(14,165,233,0.18), rgba(59,130,246,0.08)); border-color: rgba(14,165,233,0.25);">
                <div class="relative px-5 py-5 sm:px-6 md:px-8 md:py-6">
                    <div class="absolute inset-0 opacity-30 pointer-events-none"
                         style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.18) 1px, transparent 0); background-size: 22px 22px;"></div>
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center gap-5 lg:gap-6">
                        @if($featuredBanner['action_label'] && $featuredBanner['action_url'])
                            <div class="shrink-0 order-2 lg:order-1">
                                <a href="{{ $featuredBanner['action_url'] }}"
                                   @if($featuredBanner['action_label'] === __('live_sessions.join_zoom_session')) target="_blank" rel="noopener noreferrer" @endif
                                   class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-2xl font-bold text-white bg-sky-500 hover:bg-sky-400 shadow-lg shadow-sky-500/25 transition-colors min-w-[170px]">
                                    <span>{{ $featuredBanner['action_label'] }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0 order-1 lg:order-2 lg:text-right space-y-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-[0.22em] bg-white/80 text-sky-700">
                                {{ $featuredBanner['eyebrow'] }}
                            </div>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white leading-tight">{{ $featuredBanner['title'] }}</h2>
                            <p class="text-sm sm:text-base text-sky-50/90 font-semibold">{{ $featuredBanner['message'] }}</p>
                            @if($featuredBanner['course'])
                                <div class="text-[11px] sm:text-xs font-black uppercase tracking-[0.22em] text-sky-100/80">
                                    {{ $featuredBanner['course'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Hero Section --}}
        <x-student.card class="relative" padding="p-4 sm:p-8 md:p-12" mb="mb-0" :headerBorder="false" data-aos="fade-down">
            {{-- Dedicated Wide Background Image --}}
            <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none z-0">
                <img src="{{ asset('images/ai/dashboard_wide_bg.png') }}" alt="Dashboard Background" class="absolute inset-0 right-0 w-full h-full object-cover mix-blend-normal z-0 opacity-100 contrast-125 saturate-125" style="-webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0) 40%, rgba(0,0,0,1) 100%); mask-image: linear-gradient(to left, rgba(0,0,0,0) 40%, rgba(0,0,0,1) 100%);">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-accent-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-8">
                {{-- Left: Welcome Text --}}
                <div class="w-full md:w-3/5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 sm:py-1.5 rounded-full bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 text-amber-500 text-xs sm:text-sm font-bold mb-3 sm:mb-6 hover:scale-105 transition-transform backdrop-blur-md shadow-lg shadow-amber-500/10">
                        <span class="inline-flex h-5 w-5 items-center justify-center text-amber-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M11.983 1.904a.75.75 0 0 0-1.35.001l-1.166 2.38a9.17 9.17 0 0 0-.951 4.035c0 .298.014.593.042.884A3.748 3.748 0 0 0 6 12.75c0 2.071 1.679 3.75 3.75 3.75h.5A3.75 3.75 0 0 0 14 12.75c0-1.016-.404-1.938-1.06-2.612.54-1.278.81-2.655.81-4.14 0-1.42-.255-2.801-.767-4.094Z" />
                            </svg>
                        </span>
                        {{ $stats['current_streak'] ?? 0 }} {{ __('Day Streak') }}
                    </div>
                    
                    <h1 class="text-xl sm:text-3xl md:text-5xl font-extrabold mb-2 sm:mb-4 leading-tight tracking-tight text-slate-900 dark:text-white drop-shadow-sm">
                        {{ __('Welcome back,') }}<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500 filter drop-shadow-md">
                            {{ explode(' ', $user->name)[0] }}!
                        </span>
                    </h1>
                    
                    <p class="text-xs sm:text-base md:text-lg text-slate-600 dark:text-slate-300 mb-4 sm:mb-8 max-w-xl leading-relaxed font-medium">
                        {{ __('Ready to crush your goals today? Pick up where you left off or challenge others in the arena.') }}
                    </p> 
                    
                    <div class="flex flex-row gap-2 sm:gap-4">
                        <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-3 sm:px-6 md:px-8 py-2.5 sm:py-3.5 rounded-xl shadow-xl shadow-primary-500/30 text-xs sm:text-sm md:text-base flex-1 md:flex-none text-center justify-center font-bold whitespace-nowrap">
                            {{ __('Resume Learning') }}
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-1 sm:ml-2 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('student.battle.index') }}" class="btn-secondary px-3 sm:px-6 md:px-8 py-2.5 sm:py-3.5 rounded-xl border-2 border-accent-500/30 text-accent-600 dark:text-accent-400 hover:bg-accent-500/10 hover:border-accent-500 font-bold flex-1 md:flex-none text-center justify-center transition-all bg-white/5 backdrop-blur-md text-xs sm:text-sm md:text-base whitespace-nowrap">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0 0 10 9.868v4.264a1 1 0 0 0 1.555.832l3.197-2.132a1 1 0 0 0 0-1.664Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7.5h4m8 0h4M6 7.5l1-3h10l1 3M7 10.5l1 9h8l1-9"/>
                                </svg>
                                {{ __('Enter Battle') }}
                            </span>
                        </a>
                    </div>
                </div>
                
                {{-- Right: Rank Badge (3D Effect) --}}
                <div class="w-full md:w-2/5 hidden sm:flex justify-center md:justify-end perspective-1000">
                    <div class="relative w-40 h-40 sm:w-64 sm:h-64 group transform-gpu transition-transform duration-700 hover:rotate-y-12 hover:-rotate-x-12 cursor-pointer">
                        {{-- Glow --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-500 to-accent-500 rounded-full blur-3xl opacity-30 group-hover:opacity-50 animate-pulse transition-opacity"></div>
                        
                        {{-- Metal Circle --}}
                        <div class="absolute inset-2 rounded-full border border-white/20 dark:border-white/10 shadow-2xl overflow-hidden bg-gradient-to-br from-white/40 to-white/5 dark:from-white/10 dark:to-white/0 flex flex-col items-center justify-center p-8 text-center backdrop-blur-xl">
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer pointer-events-none"></div>
                            
                            <div class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-1 sm:mb-2">{{ __('Current Level') }}</div>
                            <div class="text-3xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-primary-600 to-accent-500 drop-shadow-lg mb-1 sm:mb-2">
                                {{ $stats['level_label'] ?? 'ROOKIE' }}
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/10 dark:bg-black/30 text-sm font-mono text-primary-600 dark:text-primary-400 font-bold border border-primary-500/20">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 01.832.445l2.454 3.682 4.39.638a1 1 0 01.554 1.706l-3.176 3.097.75 4.373a1 1 0 01-1.451 1.054L10 14.73l-3.927 2.064a1 1 0 01-1.451-1.054l.75-4.373-3.176-3.097a1 1 0 01.554-1.706l4.39-.638 2.454-3.682A1 1 0 0110 2z" clip-rule="evenodd"/></svg>
                                {{ __('Rank') }} #{{ $stats['rank'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-student.card>

        {{-- Stats Grid Removed --}}


        {{-- Pending Payments Alert --}}
        @if(isset($pendingPayments) && $pendingPayments->count() > 0)
            <div class="space-y-4 mb-2" data-aos="fade-up" data-aos-delay="150">
                @foreach($pendingPayments as $payment)
                    <div class="backdrop-blur-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10 bg-amber-50/80 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 p-5 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-transparent pointer-events-none"></div>
                        <div class="flex items-center gap-4 relative z-10 w-full sm:w-auto">
                            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-2xl shrink-0 text-amber-600 dark:text-amber-500 shadow-inner">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900 dark:text-white text-lg">{{ __('Pending Payment') }}</h4>
                                <p class="text-sm text-slate-600 dark:text-amber-200/80 font-medium">
                                    {{ __('You have an incomplete checkout for') }} <span class="font-bold text-slate-800 dark:text-white">"{{ $payment->course->title }}"</span>.
                                </p>
                            </div>
                        </div>
                        <div class="relative z-10 w-full sm:w-auto flex-shrink-0 mt-4 sm:mt-0">
                            <a href="{{ route('student.courses.enroll', $payment->course) }}" class="btn-primary w-full sm:w-auto px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-lg shadow-amber-500/30 font-bold whitespace-nowrap inline-flex items-center justify-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5 12 5l9 5.5M4.5 9.75V18a1.5 1.5 0 0 0 1.5 1.5h12A1.5 1.5 0 0 0 19.5 18V9.75M9 13.5h6" />
                                </svg>
                                {{ __('Complete Purchase') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Quick Actions Row --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('student.forum.index') }}" class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-[2rem] p-4 flex items-center justify-center gap-3 hover:bg-primary-500 hover:text-white group transition-colors shadow-sm relative overflow-hidden">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary-500/10 text-primary-500 group-hover:bg-white/15 group-hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 6 1.4-4.2A8 8 0 1 1 20 12a8 8 0 0 1-8 8H4Z" />
                    </svg>
                </span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Community Forum') }}</span>
            </a>
            <a href="{{ route('student.games.index') }}" class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-[2rem] p-4 flex items-center justify-center gap-3 hover:bg-accent-500 hover:text-white group transition-colors shadow-sm relative overflow-hidden">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-accent-500/10 text-accent-500 group-hover:bg-white/15 group-hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9V5a3 3 0 1 0-6 0v4m9 0h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1m-10-5H6a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1m2 0h6m-6 0v2a3 3 0 1 0 6 0v-2" />
                    </svg>
                </span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Mini Games') }}</span>
            </a>
            <a href="{{ route('student.referrals.index') }}" class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-[2rem] p-4 flex items-center justify-center gap-3 hover:bg-emerald-500 hover:text-white group transition-colors shadow-sm relative overflow-hidden">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500 group-hover:bg-white/15 group-hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM3 11a4 4 0 1 1 8 0 4 4 0 0 1-8 0Zm12 9v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1m16 0v-1a4 4 0 0 0-3-3.87" />
                    </svg>
                </span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Invite Friends') }}</span>
            </a>
            <a href="{{ route('student.telegram.guide') }}" class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-[2rem] p-4 flex items-center justify-center gap-3 hover:bg-[#0088cc] hover:text-white group transition-colors shadow-sm relative overflow-hidden">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#0088cc]/10 text-[#0088cc] group-hover:bg-white/15 group-hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m21.6 4.8-3 14.15c-.23 1-.84 1.24-1.7.77l-4.7-3.46-2.27 2.19c-.25.25-.46.46-.95.46l.34-4.82 8.77-7.92c.38-.34-.08-.53-.59-.19L6.66 12.83 2 11.37c-1-.31-1.02-1 .23-1.49L20.4 2.87c.84-.31 1.58.19 1.2 1.93Z" />
                    </svg>
                </span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Telegram Bot') }}</span>
            </a>
            @if(auth()->user()->enrollments()->exists())
                <a href="{{ route('student.testimonial.edit') }}" class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-[2rem] p-4 flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white group transition-colors shadow-sm relative overflow-hidden">
                    <span class="w-8 h-8 rounded-full bg-amber-500/10 group-hover:bg-white/15 text-amber-500 group-hover:text-white flex items-center justify-center transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.148 3.531a1 1 0 00.95.69h3.712c.969 0 1.371 1.24.588 1.81l-3.003 2.182a1 1 0 00-.364 1.118l1.147 3.531c.3.922-.755 1.688-1.539 1.118l-3.004-2.182a1 1 0 00-1.175 0l-3.004 2.182c-.784.57-1.838-.196-1.539-1.118l1.148-3.531a1 1 0 00-.364-1.118L2.65 8.958c-.783-.57-.38-1.81.588-1.81h3.712a1 1 0 00.95-.69l1.149-3.531z"></path>
                        </svg>
                    </span>
                    <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ app()->getLocale() === 'ar' ? 'شارك رأيك' : 'Share your feedback' }}</span>
                </a>
            @endif
        </div>

        {{-- Main Content Split --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column (2/3) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Continue Learning --}}
                <x-student.card 
                    title="{{ __('Continue Learning') }}" 
                    icon='<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0 0 10 9.868v4.264a1 1 0 0 0 1.555.832l3.197-2.132a1 1 0 0 0 0-1.664Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5.75A1.75 1.75 0 0 1 6.75 4h10.5A1.75 1.75 0 0 1 19 5.75v12.5A1.75 1.75 0 0 1 17.25 20H6.75A1.75 1.75 0 0 1 5 18.25V5.75Z" /></svg>'
                    iconColor="primary"
                    data-aos="fade-up" 
                    data-aos-delay="300"
                >
                    
                    <div class="space-y-5">
                        @forelse($activeEnrollments->take(3) as $enrollment)
                            @php
                                $courseLessons = $enrollment->course->lessons ?? collect();
                                $totalLessonTitles = $courseLessons
                                    ->pluck('title')
                                    ->map(fn ($title) => trim((string) $title))
                                    ->filter()
                                    ->map(fn ($title) => mb_strtolower($title, 'UTF-8'))
                                    ->unique()
                                    ->count();

                                $completedLessonIds = $enrollment->lessonProgress
                                    ->where('is_completed', true)
                                    ->pluck('lesson_id')
                                    ->all();

                                $completedLessonTitles = $courseLessons
                                    ->whereIn('id', $completedLessonIds)
                                    ->pluck('title')
                                    ->map(fn ($title) => trim((string) $title))
                                    ->filter()
                                    ->map(fn ($title) => mb_strtolower($title, 'UTF-8'))
                                    ->unique()
                                    ->count();

                                if ($totalLessonTitles === 0) {
                                    $completedLessonTitles = (int) ($enrollment->completed_lessons ?? 0);
                                    $totalLessonTitles = (int) ($enrollment->total_lessons ?? 0);
                                }

                                $progressValue = $totalLessonTitles > 0
                                    ? (int) round(min(100, max(0, ($completedLessonTitles / $totalLessonTitles) * 100)))
                                    : 0;
                                $expiresAt = $enrollment->expires_at;
                                if (!$expiresAt && (int) ($enrollment->course->estimated_duration_weeks ?? 0) > 0) {
                                    $baseDate = $enrollment->started_at ?? $enrollment->created_at;
                                    if ($baseDate) {
                                        $expiresAt = $baseDate->copy()->addWeeks((int) $enrollment->course->estimated_duration_weeks);
                                    }
                                }
                                $daysLeftRaw = $expiresAt ? (now()->diffInSeconds($expiresAt, false) / 86400) : null;
                                $daysLeft = $daysLeftRaw !== null ? (int) ($daysLeftRaw >= 0 ? ceil($daysLeftRaw) : floor($daysLeftRaw)) : null;
                            @endphp
                            <div class="group relative rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 p-5 hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 flex flex-col sm:flex-row gap-6 items-center">
                                
                                {{-- Icon 3D --}}
                                <div class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-3xl shadow-lg border border-white/50 dark:border-white/10 overflow-hidden group-hover:rotate-6 transition-transform">
                                    @if($enrollment->course->thumbnail)
                                        <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="w-full h-full object-cover">
                                    @else
                                        {{ \Illuminate\Support\Str::substr($enrollment->course->title, 0, 1) }}
                                    @endif
                                </div>
                                
                                <div class="flex-1 w-full text-center sm:text-left">
                                    <h4 class="font-bold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-primary-500 transition-colors">
                                        {{ $enrollment->course->title }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5">
                                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : __('Just started') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5">
                                            <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            {{ $completedLessonTitles }}/{{ $totalLessonTitles }} {{ __('Lessons') }}
                                        </span>
                                    </div>
                                    
                                    {{-- Progress Bar --}}
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-200/50 dark:border-white/5 shadow-inner relative">
                                            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary-500 to-accent-500 rounded-full w-0 transition-all duration-1000 ease-out" style="width: {{ $progressValue }}%"></div>
                                        </div>
                                        <span class="text-lg font-black text-primary-500 w-14 text-right">{{ $progressValue }}%</span>
                                    </div>
                                    @if($daysLeft !== null)
                                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-bold">
                                            @if($daysLeft >= 0)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300">
                                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ $isArabic ? 'الوقت المتبقي' : 'Time left' }}</span>
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-300">
                                                    <svg class="w-3.5 h-3.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                    <span class="course-expiry-countdown"
                                                          data-expires-at="{{ $expiresAt?->toIso8601String() }}"
                                                          data-label-days="days"
                                                          data-label-hours="hours"
                                                          data-label-minutes="minutes"
                                                          data-label-seconds="seconds">
                                                        {{ $daysLeft }} {{ $isArabic ? 'يوم' : 'days' }}
                                                    </span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-500">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $isArabic ? 'انتهت مدة الاشتراك' : 'Subscription expired' }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="shrink-0 w-full sm:w-auto">
                                    <a href="{{ route('student.courses.learn', $enrollment->course) }}" class="flex sm:inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black font-bold shadow-lg shadow-slate-900/20 dark:shadow-white/20 hover:bg-primary-600 dark:hover:bg-primary-500 dark:hover:text-white transition-all transform group-hover:scale-105">
                                        {{ __('Play') }}
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <x-student.empty-state
                                title="{{ __('No Active Courses') }}"
                                message="{{ __('You haven\'t started any courses yet. Browse our catalog and start learning today!') }}"
                            >
                                <x-slot name="icon">
                                    <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75Zm4 2.75h8m-8 4h5" />
                                    </svg>
                                </x-slot>
                                <x-slot name="actions">
                                    <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-8 shadow-lg shadow-primary-500/30">
                                        {{ __('Explore Courses') }}
                                    </a>
                                </x-slot>
                            </x-student.empty-state>
                        @endforelse
                    </div>
                </x-student.card>

            </div>

            {{-- Right Column (1/3) --}}
            <div class="space-y-8">
                
                {{-- Next Up --}}
                @if($nextLesson)
                <div class="relative rounded-2xl p-1 shadow-2xl overflow-hidden group" data-aos="fade-left" data-aos-delay="500">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 via-accent-500 to-primary-500 background-size-200 animate-gradient-slow pointer-events-none"></div>
                    <div class="relative bg-white dark:bg-[#001c2e] rounded-[15px] p-6 h-full flex flex-col justify-between z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                                <span class="text-xs font-black uppercase tracking-widest text-primary-600 dark:text-primary-400">{{ __('Up Next') }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3 leading-tight">{{ $nextLesson->title }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 line-clamp-3 leading-relaxed">{{ Str::limit($nextLesson->description, 100) }}</p>
                        </div>
                        <a href="{{ route('student.lessons.show', [$nextLesson->course, $nextLesson]) }}" class="block w-full text-center py-4 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-bold hover:shadow-lg hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all">
                            {{ __('Start Lesson') }}
                        </a>
                    </div>
                </div>
                @endif

                {{-- Leaderboard Widget --}}
                <x-student.card 
                    title="{{ __('Top Rank') }}" 
                    icon='<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 2a1 1 0 0 1 .832.445l1.393 2.09 2.494.363a1 1 0 0 1 .554 1.706l-1.804 1.76.426 2.485a1 1 0 0 1-1.451 1.055L10 10.73 7.556 11.92a1 1 0 0 1-1.451-1.055l.426-2.485-1.804-1.76a1 1 0 0 1 .554-1.706l2.494-.363 1.393-2.09A1 1 0 0 1 10 2Z" /><path d="M4 13.5a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4V12h-1.5v1.5A2.5 2.5 0 0 1 12 16H8a2.5 2.5 0 0 1-2.5-2.5V12H4v1.5Z" /></svg>'
                    iconColor="amber"
                    padding="p-4"
                    data-aos="fade-left" 
                    data-aos-delay="600"
                >
                    <x-slot name="headerActions">
                        <a href="{{ route('student.leaderboard') }}" class="btn-ghost btn-sm text-xs font-bold text-slate-500">{{ __('View All') }}</a>
                    </x-slot>
                    
                    <div class="space-y-2">
                        @foreach($topLearners as $index => $learner)
                        @php
                            $isTop3 = $index < 3;
                            $bgClass = $isTop3 ? 'bg-gradient-to-r from-slate-50 to-white dark:from-white/10 dark:to-white/5 border border-slate-200 dark:border-white/10' : 'hover:bg-slate-50 dark:hover:bg-white/5';
                            $numberColor = $index == 0 ? 'text-amber-500' : ($index == 1 ? 'text-slate-400' : ($index == 2 ? 'text-amber-700 dark:text-amber-600' : 'text-slate-400 dark:text-slate-600'));
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl {{ $bgClass }} transition-colors">
                            <div class="w-6 font-black text-lg text-center {{ $numberColor }}">{{ $index + 1 }}</div>
                            <div class="relative w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-sm font-bold text-slate-700 dark:text-white shadow-inner">
                                {{ substr($learner->name, 0, 1) }}
                                @if($index == 0)
                                    <span class="absolute -top-2 -right-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-white shadow-sm">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M10 2a1 1 0 0 1 .832.445l1.393 2.09 2.494.363a1 1 0 0 1 .554 1.706l-1.804 1.76.426 2.485a1 1 0 0 1-1.451 1.055L10 10.73 7.556 11.92a1 1 0 0 1-1.451-1.055l.426-2.485-1.804-1.76a1 1 0 0 1 .554-1.706l2.494-.363 1.393-2.09A1 1 0 0 1 10 2Z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ explode(' ', $learner->name)[0] }}</div>
                            </div>
                            <div class="text-sm font-black text-primary-600 dark:text-primary-400">{{ number_format($learner->total_points) }}</div>
                        </div>
                        @endforeach
                        
                        {{-- Current User Rank --}}
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-white/10">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-primary-500/10 border border-primary-500/30">
                                <div class="w-6 font-black text-lg text-center text-primary-500">{{ $stats['rank'] }}</div>
                                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center text-sm text-white font-bold shadow-md shadow-primary-500/30">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ __('You') }}</div>
                                </div>
                                <div class="text-sm font-black text-primary-600 dark:text-primary-400">{{ number_format($stats['total_points']) }}</div>
                            </div>
                        </div>
                    </div>
                </x-student.card>

                {{-- Daily Tip / Recommendation --}}
                <x-student.card class="bg-gradient-to-br from-primary-500/10 to-accent-500/10 border-primary-500/20" mb="mb-0" padding="p-6" data-aos="fade-left" data-aos-delay="700">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-500/20 flex items-center justify-center text-2xl shrink-0 text-primary-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M6 4h12a2 2 0 0 1 2 2v12l-4-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-lg mb-2">{{ __('Pro Tip') }}</h4>
                            <p class="text-sm text-slate-600 dark:text-primary-200/80 leading-relaxed font-medium">
                                {{ __('Consistency is key! Spending just 30 minutes a day practicing yields better results than 2 hours once a week.') }}
                            </p>
                        </div>
                    </div>
                </x-student.card>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .background-size-200 { background-size: 200% 200%; }
    .animate-gradient-slow { animation: gradient 8s ease infinite; }
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const nodes = document.querySelectorAll('.course-expiry-countdown[data-expires-at]');
        if (!nodes.length) return;

        function pluralizeAr(number, one, two, many) {
            if (number === 1) return one;
            if (number === 2) return two;
            return many;
        }

        function formatCountdown(node, diffMs) {
            const isArabic = document.documentElement.lang === 'ar';
            const total = Math.max(0, Math.floor(diffMs / 1000));
            const days = Math.floor(total / 86400);
            const hours = Math.floor((total % 86400) / 3600);
            const minutes = Math.floor((total % 3600) / 60);
            const seconds = total % 60;

            if (isArabic) {
                if (days > 0) return `${days} ${pluralizeAr(days, 'يوم', 'يومين', 'أيام')} ${hours} ${pluralizeAr(hours, 'ساعة', 'ساعتين', 'ساعات')}`;
                if (hours > 0) return `${hours} ${pluralizeAr(hours, 'ساعة', 'ساعتين', 'ساعات')} ${minutes} ${pluralizeAr(minutes, 'دقيقة', 'دقيقتين', 'دقائق')}`;
                return `${minutes} ${pluralizeAr(minutes, 'دقيقة', 'دقيقتين', 'دقائق')} ${seconds} ${pluralizeAr(seconds, 'ثانية', 'ثانيتين', 'ثوانٍ')}`;
            }

            if (days > 0) return `${days} ${node.dataset.labelDays} ${hours} ${node.dataset.labelHours}`;
            if (hours > 0) return `${hours} ${node.dataset.labelHours} ${minutes} ${node.dataset.labelMinutes}`;
            return `${minutes} ${node.dataset.labelMinutes} ${seconds} ${node.dataset.labelSeconds}`;
        }

        function tick() {
            const now = Date.now();
            nodes.forEach((node) => {
                const expiresAt = Date.parse(node.dataset.expiresAt || '');
                if (Number.isNaN(expiresAt)) return;

                const diffMs = expiresAt - now;
                if (diffMs <= 0) {
                    node.textContent = document.documentElement.lang === 'ar' ? 'انتهت مدة الاشتراك' : 'Subscription expired';
                    node.classList.remove('text-primary-500');
                    node.classList.add('text-rose-500');
                    return;
                }

                node.textContent = formatCountdown(node, diffMs);
            });
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
@endpush


@endsection
