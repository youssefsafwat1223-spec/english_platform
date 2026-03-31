@props([
    'variant' => 'primary',
])

@php
    $variants = [
        'primary' => 'badge-primary',
        'accent' => 'badge-accent',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
    ];
    $class = $variants[$variant] ?? $variants['primary'];
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
