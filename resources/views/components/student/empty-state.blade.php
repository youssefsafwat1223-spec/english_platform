@props([
    'title',
    'message' => null,
    'icon' => null,
])

<x-student.card padding="p-12 lg:p-16" class="text-center group overflow-hidden">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-slate-300 dark:via-slate-700 to-transparent opacity-50"></div>
    
    @if($icon)
        <div class="relative mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 dark:bg-slate-800/50 text-slate-400 border border-slate-100 dark:border-white/5 shadow-inner group-hover:scale-110 group-hover:text-primary-500 transition-all duration-500">
            <div class="absolute inset-0 rounded-full bg-primary-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
            <div class="relative z-10 shrink-0">
                {!! $icon !!}
            </div>
        </div>
    @endif
    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-3 tracking-tight">{{ $title }}</h3>
    @if($message)
        <p class="text-sm sm:text-base font-medium text-slate-500 dark:text-slate-400 mb-8 max-w-md mx-auto leading-relaxed">{{ $message }}</p>
    @endif
    @if(isset($actions))
        <div class="flex flex-wrap items-center justify-center gap-3">
            {{ $actions }}
        </div>
    @endif
</x-student.card>
