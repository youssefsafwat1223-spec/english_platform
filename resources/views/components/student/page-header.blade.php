@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeIcon' => null,
    'badgeColor' => 'primary',
    'mb' => 'mb-10 lg:mb-12',
    'image' => null,
])

@php
    $bgImage = $image ?? asset('images/ai/dashboard_wide_bg.png');
@endphp

<div {{ $attributes->merge(['class' => 'relative pt-10 pb-12 sm:pt-12 sm:pb-16 ' . $mb]) }}>
    {{-- Premium Dark Glass Container --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-primary-950 to-indigo-950 rounded-[2rem] sm:rounded-[3rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] dark:shadow-[0_30px_70px_-10px_rgba(0,0,0,0.7)] ring-1 ring-white/10">
        
        {{-- Background image with dark overlay --}}
        <img src="{{ $bgImage }}" class="absolute inset-0 w-full h-full object-cover z-0 opacity-25 mix-blend-luminosity" style="-webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0) 5%, rgba(0,0,0,1) 80%); mask-image: linear-gradient(to left, rgba(0,0,0,0) 5%, rgba(0,0,0,1) 80%);">

        {{-- Animated mesh pattern --}}
        <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.3) 1px, transparent 1px), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.2) 1px, transparent 1px); background-size: 50px 50px;"></div>

        {{-- Floating gradient orbs --}}
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-primary-500/25 blur-[100px] rounded-full animate-pulse-soft pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/15 blur-[100px] rounded-full animate-pulse-soft pointer-events-none" style="animation-delay: 3s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-accent-500/5 rounded-full blur-[120px] pointer-events-none"></div>
        
        {{-- Bottom shine line --}}
        <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-primary-400/30 to-transparent"></div>
    </div>

    {{-- Content Layout --}}
    <div class="relative z-10 px-8 sm:px-12 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div class="flex-1 min-w-0 space-y-6">
            @if($badge)
                <div class="inline-flex items-center gap-3 rounded-full px-5 py-2 text-[11px] font-black uppercase tracking-[0.15em] bg-white/10 backdrop-blur-sm border border-white/15 text-white/80 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-default group/badge">
                    @if($badgeIcon)
                        <span class="inline-flex items-center justify-center shrink-0 text-{{ $badgeColor }}-400 group-hover/badge:rotate-12 transition-transform">{!! $badgeIcon !!}</span>
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1] drop-shadow-lg">
                    {!! $title !!}
                </h1>
                
                @if($subtitle)
                    <p class="text-lg md:text-xl font-medium text-white/50 max-w-2xl leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-4 shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
