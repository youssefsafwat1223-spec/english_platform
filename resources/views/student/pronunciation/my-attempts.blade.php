@extends('layouts.app')

@section('title', __('ui.pronunciation.attempts_title') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <x-student.page-header
            title="<span class='text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500'>{{ __('ui.pronunciation.attempts_title') }}</span>"
            subtitle="{{ __('ui.pronunciation.attempts_subtitle') }}"
            badge="{{ __('ui.pronunciation.attempts_title') }}"
            badgeColor="violet"
            badgeIcon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'/></svg>"
        />

        {{-- Stats --}}
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $pronStats = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18a4 4 0 004-4V8a4 4 0 10-8 0v6a4 4 0 004 4Zm0 0v3m-4 0h8" />', 'value' => $stats['total_attempts'] ?? 0, 'label' => __('ui.pronunciation.total_practices'), 'color' => 'primary'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 012 0v1.07A8.002 8.002 0 0120 12h-3a5 5 0 10-5 5v3A8 8 0 0111 4.07V3Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l3-3" />', 'value' => round($stats['average_score'] ?? 0) . '%', 'label' => __('Average Score'), 'color' => 'emerald'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 17.75-5.228 2.749 1-5.823-4.23-4.126 5.846-.849L12 4.5l2.612 5.201 5.846.849-4.23 4.126 1 5.823L12 17.75Z" />', 'value' => $stats['best_score'] ?? 0, 'label' => __('ui.pronunciation.best_score'), 'color' => 'amber'],
                ];
            @endphp
            @foreach($pronStats as $s)
                <x-student.card padding="p-6" class="text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="mb-2 flex justify-center text-{{ $s['color'] }}-500 group-hover:scale-110 transition-transform">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">{!! $s['icon'] !!}</svg>
                    </div>
                    <div class="text-3xl font-extrabold text-{{ $s['color'] }}-500">{{ $s['value'] }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $s['label'] }}</div>
                </x-student.card>
            @endforeach
        </div>
        @endif

        {{-- Attempts --}}
        <x-student.card padding="p-0" class="overflow-hidden mb-12" data-aos="fade-up">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-start whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-y border-slate-200/50 dark:border-white/5 text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">{{ __('ui.pronunciation.exercise') }}</th>
                            <th class="px-6 py-4">{{ __('ui.pronunciation.lesson') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('ui.pronunciation.score') }}</th>
                            <th class="px-6 py-4">{{ __('ui.pronunciation.date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5">
                        @forelse($attempts as $attempt)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $attempt->exercise->word ?? $attempt->exercise->phrase ?? __('ui.pronunciation.exercise') }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $attempt->exercise->lesson->title ?? '' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-extrabold {{ ($attempt->score ?? 0) >= 70 ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ round($attempt->score ?? 0) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">
                                    <div class="mb-4 flex justify-center text-primary-500">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18a4 4 0 004-4V8a4 4 0 10-8 0v6a4 4 0 004 4Zm0 0v3m-4 0h8" />
                                        </svg>
                                    </div>
                                    <p>{{ __('ui.pronunciation.empty_message') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($attempts) && method_exists($attempts, 'links') && $attempts->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    {{ $attempts->links() }}
                </div>
            @endif
        </x-student.card>
    </div>
</div>
@endsection

