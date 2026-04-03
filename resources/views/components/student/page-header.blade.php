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
    $colorMap = [
        'primary' => [
            'gradient' => 'from-sky-500 via-blue-600 to-indigo-600',
            'bg'       => 'bg-sky-500/10 dark:bg-sky-400/10',
            'text'     => 'text-sky-700 dark:text-sky-300',
            'glow'     => 'rgba(14,165,233,0.35)',
            'dot'      => 'rgba(14,165,233,0.08)',
            'dotDark'  => 'rgba(56,189,248,0.06)',
            'line'     => 'from-sky-500 via-blue-500 to-indigo-500',
        ],
        'amber' => [
            'gradient' => 'from-amber-400 via-orange-500 to-rose-500',
            'bg'       => 'bg-amber-500/10 dark:bg-amber-400/10',
            'text'     => 'text-amber-700 dark:text-amber-300',
            'glow'     => 'rgba(245,158,11,0.35)',
            'dot'      => 'rgba(245,158,11,0.08)',
            'dotDark'  => 'rgba(251,191,36,0.06)',
            'line'     => 'from-amber-400 via-orange-500 to-rose-500',
        ],
        'emerald' => [
            'gradient' => 'from-emerald-400 via-teal-500 to-cyan-500',
            'bg'       => 'bg-emerald-500/10 dark:bg-emerald-400/10',
            'text'     => 'text-emerald-700 dark:text-emerald-300',
            'glow'     => 'rgba(16,185,129,0.35)',
            'dot'      => 'rgba(16,185,129,0.08)',
            'dotDark'  => 'rgba(52,211,153,0.06)',
            'line'     => 'from-emerald-400 via-teal-500 to-cyan-500',
        ],
        'violet' => [
            'gradient' => 'from-violet-500 via-purple-500 to-fuchsia-500',
            'bg'       => 'bg-violet-500/10 dark:bg-violet-400/10',
            'text'     => 'text-violet-700 dark:text-violet-300',
            'glow'     => 'rgba(139,92,246,0.35)',
            'dot'      => 'rgba(139,92,246,0.08)',
            'dotDark'  => 'rgba(167,139,250,0.06)',
            'line'     => 'from-violet-500 via-purple-500 to-fuchsia-500',
        ],
        'rose' => [
            'gradient' => 'from-rose-400 via-pink-500 to-fuchsia-500',
            'bg'       => 'bg-rose-500/10 dark:bg-rose-400/10',
            'text'     => 'text-rose-700 dark:text-rose-300',
            'glow'     => 'rgba(244,63,94,0.35)',
            'dot'      => 'rgba(244,63,94,0.08)',
            'dotDark'  => 'rgba(251,113,133,0.06)',
            'line'     => 'from-rose-400 via-pink-500 to-fuchsia-500',
        ],
    ];
    $colors = $colorMap[$badgeColor] ?? $colorMap['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl sm:rounded-3xl border border-slate-200/60 dark:border-white/[0.06] bg-white/80 dark:bg-slate-900/60 backdrop-blur-md shadow-xl shadow-slate-900/[0.04] dark:shadow-black/20 ring-1 ring-black/[0.02] dark:ring-white/[0.03] ' . $mb]) }}
     style="animation: pageHeaderFadeIn 0.5s ease-out both;">

    {{-- Animated Gradient Accent Bar --}}
    <div class="absolute top-0 inset-x-0 h-[3px] bg-gradient-to-r {{ $colors['line'] }} opacity-90 dark:opacity-70"
         style="background-size: 200% 100%; animation: pageHeaderShimmer 3s ease-in-out infinite;"></div>

    {{-- Geometric Dot Pattern (subtle) --}}
    <div class="absolute inset-0 pointer-events-none opacity-60 dark:opacity-30"
         style="background-image: radial-gradient(circle, {{ $colors['dot'] }} 1px, transparent 1px);
                background-size: 20px 20px;
                mask-image: linear-gradient(135deg, black 0%, transparent 60%);
                -webkit-mask-image: linear-gradient(135deg, black 0%, transparent 60%);"></div>

    {{-- Soft ambient glow (bottom-right only) --}}
    <div class="absolute -bottom-16 -right-16 w-48 h-48 sm:w-64 sm:h-64 rounded-full pointer-events-none blur-3xl opacity-20 dark:opacity-10 bg-gradient-to-br {{ $colors['gradient'] }}"></div>

    {{-- Content --}}
    <div class="relative z-10 px-5 sm:px-8 lg:px-10 py-6 sm:py-8 lg:py-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
        <div class="flex-1 min-w-0 space-y-4">

            {{-- Badge --}}
            @if($badge)
                <div class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-widest {{ $colors['bg'] }} {{ $colors['text'] }} border border-current/10 backdrop-blur-sm">
                    @if($badgeIcon)
                        <span class="inline-flex items-center justify-center shrink-0 opacity-80">{!! $badgeIcon !!}</span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 animate-pulse"></span>
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            {{-- Title & Subtitle --}}
            <div class="space-y-2 sm:space-y-3">
                <h1 class="text-2xl sm:text-3xl lg:text-[2.25rem] font-extrabold text-slate-900 dark:text-white tracking-tight leading-[1.15] break-words">
                    {!! $title !!}
                </h1>

                @if($subtitle)
                    <p class="text-sm sm:text-[15px] lg:text-base text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed font-medium">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Actions slot --}}
        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>

{{-- Scoped keyframes (only rendered once) --}}
@once
@push('styles')
<style>
@keyframes pageHeaderFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes pageHeaderShimmer {
    0%, 100% { background-position: 0% 50%; }
    50%      { background-position: 100% 50%; }
}
</style>
@endpush
@endonce
