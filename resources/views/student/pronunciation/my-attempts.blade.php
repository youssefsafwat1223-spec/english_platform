@extends('layouts.app')

@section('title', __('Pronunciation Attempts') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center justify-between mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">Pronunciation Practice</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">Track your speaking progress and improve your accent.</p>
            </div>
        </div>

        {{-- Stats --}}
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $pronStats = [
                    ['icon' => '🎤', 'value' => $stats['total_attempts'] ?? 0, 'label' => 'Total Practices', 'color' => 'primary'],
                    ['icon' => '🎯', 'value' => round($stats['average_score'] ?? 0) . '%', 'label' => 'Average Score', 'color' => 'emerald'],
                    ['icon' => '⭐', 'value' => $stats['best_score'] ?? 0, 'label' => 'Best Score', 'color' => 'amber'],
                ];
            @endphp
            @foreach($pronStats as $s)
                <div class="glass-card p-6 text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">{{ $s['icon'] }}</div>
                    <div class="text-3xl font-extrabold text-{{ $s['color'] }}-500">{{ $s['value'] }}</div>
                    <div class="text-sm font-medium" style="color: var(--color-text-muted);">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Attempts --}}
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Exercise</th>
                            <th>Lesson</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attempts as $attempt)
                            <tr>
                                <td>
                                    <div class="font-bold" style="color: var(--color-text);">{{ $attempt->exercise->word ?? $attempt->exercise->phrase ?? 'Exercise' }}</div>
                                </td>
                                <td style="color: var(--color-text-muted);">{{ $attempt->exercise->lesson->title ?? '' }}</td>
                                <td>
                                    <span class="font-extrabold {{ ($attempt->score ?? 0) >= 70 ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ round($attempt->score ?? 0) }}%
                                    </span>
                                </td>
                                <td style="color: var(--color-text-muted);">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12" style="color: var(--color-text-muted);">
                                    <div class="text-4xl mb-4">🎤</div>
                                    <p>No pronunciation attempts yet. Try a lesson with pronunciation exercises!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($attempts) && method_exists($attempts, 'links'))
                <div class="glass-card-footer">{{ $attempts->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
