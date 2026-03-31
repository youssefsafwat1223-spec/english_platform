@props([
    'title' => null,
    'icon' => null,
    'iconColor' => 'primary',
    'headerBorder' => true,
    'padding' => 'p-6 lg:p-8',
    'rounded' => 'rounded-[2rem]',
    'mb' => 'mb-8'
])

<div {{ $attributes->merge(['class' => "overflow-hidden {$rounded} {$mb} relative bg-white/70 dark:bg-slate-900/40 backdrop-blur-[30px] border border-white/60 dark:border-white/10 shadow-[0_15px_40px_-20px_rgba(0,0,0,0.08)] dark:shadow-[0_20px_50px_-20px_rgba(0,0,0,0.5)] ring-1 ring-black/5 dark:ring-white/5 group/card transition-all duration-500 hover:shadow-[0_20px_50px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] hover:-translate-y-1 hover:bg-white/80 dark:hover:bg-slate-900/50"]) }}>
    
    {{-- High-End Inner Gradient Glow --}}
    <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-transparent dark:from-white/5 dark:to-transparent pointer-events-none z-0 transition-opacity duration-500 opacity-100 group-hover/card:opacity-60"></div>
    
    {{-- Animated subtle border glow on hover (Dark mode mostly) --}}
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-500/20 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-700 pointer-events-none z-[1]"></div>

    @if($title || isset($headerActions))
    <div class="relative z-10 px-7 lg:px-9 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 {{ $headerBorder ? 'border-b border-white/60 dark:border-white/5 bg-white/20 dark:bg-black/10' : '' }}">
        @if($title || $icon)
        <div class="flex items-center gap-5 w-full sm:w-auto">
            @if($icon)
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-{{ $iconColor }}-500/15 via-{{ $iconColor }}-500/5 to-transparent text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400 flex items-center justify-center text-2xl shrink-0 border border-white dark:border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)] dark:shadow-none transition-all duration-500 group-hover/card:scale-110 group-hover/card:rotate-2">
                {!! $icon !!}
            </div>
            @endif
            <div class="min-w-0 flex-1">
                <h3 class="font-black text-lg md:text-xl text-slate-900 dark:text-white tracking-tight leading-tight group-hover/card:text-primary-600 dark:group-hover/card:text-primary-400 transition-colors">{{ $title }}</h3>
                @if(isset($subtitle))
                <div class="text-[0.85rem] font-bold text-slate-500/80 dark:text-slate-400 mt-1">{!! $subtitle !!}</div>
                @endif
            </div>
        </div>
        @endif
        
        @if(isset($headerActions))
        <div class="flex items-center gap-3 shrink-0">
            {{ $headerActions }}
        </div>
        @endif
    </div>
    @endif

    <div class="{{ $padding }} relative z-10 w-full backdrop-blur-[2px]">
        {{ $slot }}
    </div>

    @if(isset($footer))
    <div class="relative z-10 px-7 lg:px-9 py-5 border-t border-white/60 dark:border-white/5 bg-white/30 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full backdrop-blur-md">
        {{ $footer }}
    </div>
    @endif
</div>
