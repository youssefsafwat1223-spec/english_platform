@extends('layouts.admin')
@section('title', __('Question Analytics'))
@section('content')
<div class="py-12 relative overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Question Analytics') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Track the most failed questions to identify learning gaps') }}</p>
            </div>
            <div class="flex gap-4">
                <div class="glass-card px-4 py-2 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center font-bold text-lg">⚠️</div>
                    <div>
                        <div class="text-sm font-bold" style="color: var(--color-text-muted);">{{ __('Total Monitored') }}</div>
                        <div class="text-xl font-black" style="color: var(--color-text);">{{ $questions->total() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Question Context') }}</th>
                            <th>{{ __('Error Rate') }}</th>
                            <th>{{ __('Attempts') }}</th>
                            <th>{{ __('Question Text') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                        @php
                            $errorRate = ($question->total_failed / $question->total_attempts) * 100;
                            // Color coding based on error severity
                            if($errorRate >= 70) $badgeClass = 'bg-rose-500/10 text-rose-500 border-rose-500/20';
                            elseif($errorRate >= 40) $badgeClass = 'bg-amber-500/10 text-amber-500 border-amber-500/20';
                            else $badgeClass = 'bg-blue-500/10 text-blue-500 border-blue-500/20';
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-sm truncate max-w-[200px]" style="color: var(--color-text);" title="{{ $question->course ? $question->course->title : __('Unassigned') }}">
                                    {{ $question->course ? $question->course->title : __('Unassigned') }}
                                </div>
                                <div class="text-xs truncate max-w-[200px]" style="color: var(--color-text-muted);" title="{{ $question->lesson ? $question->lesson->title : __('General Bank') }}">
                                    {{ $question->lesson ? $question->lesson->title : __('General Bank') }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded border font-bold text-sm {{ $badgeClass }}">
                                        {{ number_format($errorRate, 1) }}%
                                    </span>
                                </div>
                                <div class="w-24 bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full {{ str_replace('/10 text-', ' bg-', explode(' border-', $badgeClass)[0]) }}" style="width: {{ $errorRate }}%"></div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-bold" style="color: var(--color-text);">{{ number_format($question->total_failed) }} {{ __('Failed') }}</div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ __('Out of') }} {{ number_format($question->total_attempts) }}</div>
                            </td>
                            <td>
                                <div class="max-w-md">
                                    <div class="text-sm font-medium line-clamp-2" style="color: var(--color-text);">
                                        {!! strip_tags($question->question_text) !!}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-accent text-xs">{{ ucfirst($question->question_type) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.questions.edit', $question) }}" class="btn-primary ripple-btn px-4 py-1 text-sm shadow-none" target="_blank">
                                    {{ __('Review Question') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">
                                <div class="text-4xl mb-4">📊</div>
                                <p class="mb-4">{{ __('No quiz data available yet to generate analytics.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
