@extends('layouts.admin')
@section('title', __('Student Enrollments'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $student->name }} — Enrollments</span></h1>
            <a href="{{ route('admin.students.show', $student) }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Student') }}</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($enrollments as $enrollment)
            @php
                $timeSpent = $enrollment->lessonProgress->sum('time_spent');
                $passedQuizzes = $enrollment->quizAttempts->where('passed', true)->count();
            @endphp
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-extrabold" style="color: var(--color-text);">{{ $enrollment->course->title }}</h3>
                            <p class="text-xs" style="color: var(--color-text-muted);">Enrolled {{ $enrollment->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($enrollment->is_completed)<span class="badge-success">{{ __('Completed') }}</span>
                        @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ __('In Progress') }}</span>@endif
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2"><span style="color: var(--color-text-muted);">{{ __('Progress') }}</span><span class="font-bold" style="color: var(--color-text);">{{ round($enrollment->progress_percentage) }}%</span></div>
                        <div class="w-full h-2 rounded-full" style="background: var(--color-surface-hover);"><div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all" style="width: {{ $enrollment->progress_percentage }}%"></div></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center text-sm">
                        <div><div class="font-bold text-primary-500">{{ $enrollment->completed_lessons }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Lessons') }}</div></div>
                        <div><div class="font-bold text-emerald-500">{{ $passedQuizzes }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Quizzes') }}</div></div>
                        <div><div class="font-bold text-amber-500">{{ $timeSpent ? gmdate('H:i', $timeSpent) : '0:00' }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Time') }}</div></div>
                    </div>
                    <div class="mt-4"><a href="{{ route('admin.students.progress', [$student, $enrollment]) }}" class="btn-secondary w-full text-center block">{{ __('View Detailed Progress') }}</a></div>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-12" style="color: var(--color-text-muted);">{{ __('No enrollments yet') }}</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
