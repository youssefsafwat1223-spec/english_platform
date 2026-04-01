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
    {{-- High-End Inner Banner Shape --}}
    <div class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-[12px] rounded-[2rem] sm:rounded-[3rem] border border-white/20 dark:border-white/10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] dark:shadow-[0_30px_70px_-10px_rgba(0,0,0,0.6)] overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
        
        <img src="{{ $bgImage }}" class="absolute inset-0 right-0 w-full h-full object-cover mix-blend-normal z-0 opacity-100 contrast-[1.10] saturate-[1.10]" style="-webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0) 10%, rgba(0,0,0,1) 90%); mask-image: linear-gradient(to left, rgba(0,0,0,0) 10%, rgba(0,0,0,1) 90%);">

        {{-- Animated Gradient Decoration --}}
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-primary-500/15 blur-[100px] rounded-full animate-pulse-soft pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-accent-500/15 blur-[100px] rounded-full animate-pulse-soft pointer-events-none" style="animation-delay: 3s"></div>
        
        {{-- Glossy Shine Effect --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent dark:from-white/5 pointer-events-none"></div>
    </div>

    {{-- Content Layout --}}
    <div class="relative z-10 px-8 sm:px-12 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div class="flex-1 min-w-0 space-y-6">
            @if($badge)
                <div class="inline-flex items-center gap-3 rounded-full px-5 py-2 text-[11px] font-black uppercase tracking-[0.15em] bg-white dark:bg-white/5 border border-slate-200/50 dark:border-white/10 text-{{ $badgeColor }}-600 dark:text-{{ $badgeColor }}-400 shadow-sm transition-all hover:scale-105 active:scale-95 cursor-default group/badge">
                    @if($badgeIcon)
                        <span class="inline-flex items-center justify-center shrink-0 group-hover/badge:rotate-12 transition-transform">{!! $badgeIcon !!}</span>
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1] drop-shadow-md">
                    {!! $title !!}
                </h1>
                
                @if($subtitle)
                    <p class="text-lg md:text-xl font-bold text-slate-600/80 dark:text-slate-400/80 max-w-2xl leading-relaxed">
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
