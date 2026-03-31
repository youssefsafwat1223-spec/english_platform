@props([
    'title' => null,
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'student-section']) }}>
    @if($title)
        <div class="space-y-1">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    {{ $slot }}
</section>
