@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeIcon' => null,
    'badgeColor' => 'primary',
    'mb' => null,
])

@php
    $headerClass = trim('student-header ' . ($mb ?? ''));
@endphp

<div {{ $attributes->merge(['class' => $headerClass]) }}>
    <div class="space-y-1">
        @if($badge)
            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold bg-{{ $badgeColor }}-500/10 text-{{ $badgeColor }}-600 dark:text-{{ $badgeColor }}-400">
                @if($badgeIcon)
                    <span class="inline-flex h-4 w-4 items-center justify-center">{!! $badgeIcon !!}</span>
                @endif
                <span>{{ $badge }}</span>
            </div>
        @endif
        <h1 class="student-header-title">{!! $title !!}</h1>
        @if($subtitle)
            <p class="student-header-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
