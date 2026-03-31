@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeIcon' => null,
    'badgeColor' => 'violet',
    'icon' => null,
    'iconColor' => 'violet',
])

<div {{ $attributes->merge(['class' => 'relative glass-card overflow-hidden rounded-[2rem] p-6 lg:p-8 mb-8 lg:mb-10']) }} data-aos="fade-down">
    <div class="absolute inset-0 bg-gradient-to-br from-{{ $badgeColor }}-500/10 via-transparent to-primary-500/10 opacity-50"></div>
    
    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="w-full lg:w-auto text-center rtl:lg:text-right ltr:lg:text-left flex-1">
            @if($badge)
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-{{ $badgeColor }}-500/10 border border-{{ $badgeColor }}-500/20 text-{{ $badgeColor }}-500 dark:text-{{ $badgeColor }}-400 text-sm font-bold mb-4 shadow-sm mx-auto lg:mx-0">
                @if($badgeIcon) {!! $badgeIcon !!} @endif
                {{ $badge }}
            </div>
            @endif
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-3 text-slate-900 dark:text-white tracking-tight flex flex-wrap items-center justify-center lg:justify-start gap-3">
                @if($icon) <span class="text-{{ $iconColor }}-500">{!! $icon !!}</span> @endif
                {!! $title !!}
            </h1>
            
            @if($subtitle)
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 font-medium max-w-2xl mx-auto lg:mx-0">
                {{ $subtitle }}
            </p>
            @endif
        </div>
        
        @if(isset($actions))
        <div class="shrink-0 flex items-center justify-center gap-3 w-full lg:w-auto mt-4 lg:mt-0">
            {{ $actions }}
        </div>
        @endif
    </div>
</div>
