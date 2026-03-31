@props([
    'title',
    'message' => null,
    'icon' => null,
])

<x-student.card padding="p-12 lg:p-16" class="text-center">
    @if($icon)
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300">
            {!! $icon !!}
        </div>
    @endif
    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $title }}</h3>
    @if($message)
        <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 mb-6">{{ $message }}</p>
    @endif
    @if(isset($actions))
        <div class="flex items-center justify-center gap-2">
            {{ $actions }}
        </div>
    @endif
</x-student.card>
