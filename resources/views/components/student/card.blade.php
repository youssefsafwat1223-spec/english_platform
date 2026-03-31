@props([
    'title' => null,
    'icon' => null,
    'iconColor' => 'primary',
    'headerBorder' => true,
    'padding' => 'p-6 lg:p-8',
    'rounded' => 'rounded-[1.5rem] lg:rounded-[2rem]',
    'mb' => 'mb-8'
])

<div {{ $attributes->merge(['class' => "glass-card overflow-hidden {$rounded} {$mb} relative bg-white/60 dark:bg-slate-900/60 shadow-lg shadow-slate-200/5 dark:shadow-slate-900/20 border border-slate-200/50 dark:border-white/5"]) }}>
    
    @if($title || isset($headerActions))
    <div class="px-6 lg:px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $headerBorder ? 'border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20' : '' }}">
        @if($title || $icon)
        <div class="flex items-center gap-3 w-full sm:w-auto">
            @if($icon)
            <div class="w-10 h-10 rounded-xl bg-{{ $iconColor }}-500/10 text-{{ $iconColor }}-500 flex items-center justify-center text-xl shrink-0 shadow-inner group-hover:bg-{{ $iconColor }}-500 group-hover:text-white transition-all">
                {!! $icon !!}
            </div>
            @endif
            <div class="min-w-0 flex-1">
                <h3 class="font-bold text-lg md:text-xl text-slate-900 dark:text-white tracking-tight leading-snug truncate">{{ $title }}</h3>
                @if(isset($subtitle))
                <div class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-0.5 truncate">{!! $subtitle !!}</div>
                @endif
            </div>
        </div>
        @endif
        
        @if(isset($headerActions))
        <div class="flex items-center gap-2 shrink-0">
            {{ $headerActions }}
        </div>
        @endif
    </div>
    @endif

    <div class="{{ $padding }} relative z-10 w-full">
        {{ $slot }}
    </div>

    @if(isset($footer))
    <div class="px-6 lg:px-8 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
        {{ $footer }}
    </div>
    @endif
</div>
