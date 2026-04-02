@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeIcon' => null,
    'badgeColor' => 'primary',
    'mb' => 'mb-10 lg:mb-12',
    'image' => null,
])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-[1.5rem] sm:rounded-[2rem] border border-slate-200/70 dark:border-white/10 bg-white/80 dark:bg-slate-900/70 backdrop-blur-sm shadow-[0_24px_48px_-24px_rgba(15,23,42,0.22)] dark:shadow-[0_28px_60px_-28px_rgba(2,6,23,0.8)] ring-1 ring-black/5 dark:ring-white/5 ' . $mb]) }}>
    {{-- Lightweight CSS background (no image payload) --}}
    <div class="absolute inset-0 opacity-80 dark:opacity-30 pointer-events-none"
         style="background-image:
            linear-gradient(to right, rgba(2,132,199,0.12) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(2,132,199,0.12) 1px, transparent 1px),
            radial-gradient(circle at 20% 20%, rgba(2,132,199,0.18), transparent 40%),
            radial-gradient(circle at 80% 80%, rgba(245,158,11,0.14), transparent 42%);
            background-size: 24px 24px, 24px 24px, 100% 100%, 100% 100%;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-white/70 via-white/40 to-transparent dark:from-slate-900/80 dark:via-slate-900/45 dark:to-transparent pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-primary-500/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 rounded-full bg-accent-500/10 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 px-5 sm:px-8 lg:px-10 py-6 sm:py-8 lg:py-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 sm:gap-8">
        <div class="flex-1 min-w-0 space-y-4 sm:space-y-5">
            @if($badge)
                <div class="inline-flex items-center gap-2.5 rounded-full px-4 py-2 text-[10px] sm:text-[11px] font-black uppercase tracking-[0.15em] bg-white/90 dark:bg-white/5 border border-slate-200/70 dark:border-white/10 text-{{ $badgeColor }}-700 dark:text-{{ $badgeColor }}-300 shadow-sm">
                    @if($badgeIcon)
                        <span class="inline-flex items-center justify-center shrink-0">{!! $badgeIcon !!}</span>
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <div class="space-y-3">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight break-words">
                    {!! $title !!}
                </h1>
                
                @if($subtitle)
                    <p class="text-sm sm:text-base lg:text-lg font-semibold text-slate-700 dark:text-slate-200 max-w-3xl leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
