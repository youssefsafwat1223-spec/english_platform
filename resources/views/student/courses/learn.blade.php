@extends('layouts.app')

@section('title', $course->title . ' — ' . config('app.name'))

@section('content')
@php
    $startedAt = $enrollment->started_at ? $enrollment->started_at->format('M d, Y') : '-';
    $lastAccessed = $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Not yet';
@endphp

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ $course->title }}</h1>
                <p class="mt-1" style="color: var(--color-text-muted);">Continue learning and track your progress.</p>
            </div>
            @if($currentLesson)
                <a href="{{ route('student.lessons.show', [$course, $currentLesson]) }}" class="btn-primary ripple-btn inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Continue: {{ $currentLesson->title }}
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Lessons List --}}
            <div class="lg:col-span-3">
                <div class="space-y-4">
                    @foreach($course->lessons as $lesson)
                        @php
                            $progress = $enrollment->lessonProgress->where('lesson_id', $lesson->id)->first();
                            $isCompleted = $progress && $progress->is_completed;
                            $isCurrent = $currentLesson && $currentLesson->id === $lesson->id;
                        @endphp
                        <div class="glass-card group transition-all duration-300 {{ $isCurrent ? 'ring-2 ring-primary-500 shadow-lg shadow-primary-500/10' : '' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="glass-card-body">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 shrink-0 font-bold text-sm transition-transform group-hover:scale-110 {{ $isCompleted ? 'bg-emerald-500/10 text-emerald-500' : ($isCurrent ? 'bg-primary-500/10 text-primary-500' : '') }}" style="{{ !$isCompleted && !$isCurrent ? 'background: var(--color-surface-hover); color: var(--color-text-muted);' : '' }}">
                                            @if($isCompleted)
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-lg truncate group-hover:text-primary-500 transition-colors" style="color: var(--color-text);">{{ $lesson->title }}</h3>
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                @if($lesson->video_url)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-lg bg-blue-500/10 text-blue-500">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                        Video
                                                    </span>
                                                @endif
                                                @if($lesson->has_quiz)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-lg bg-violet-500/10 text-violet-500">🧠 Quiz</span>
                                                @endif
                                                @if($lesson->has_pronunciation_exercise)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500">🎤 Pronunciation</span>
                                                @endif
                                                @if($lesson->is_free)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-500">Free</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.lessons.show', [$course, $lesson]) }}" class="{{ $isCompleted ? 'btn-secondary' : 'btn-primary' }} btn-sm ripple-btn shrink-0">
                                        {{ $isCompleted ? 'Review' : ($isCurrent ? 'Continue' : 'Start') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                <div class="glass-card sticky top-24 overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-body">
                        {{-- Progress Ring --}}
                        <div class="text-center mb-6">
                            <div class="relative w-28 h-28 mx-auto mb-3">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="56" cy="56" r="48" stroke-width="8" fill="transparent" style="stroke: var(--color-border);"/>
                                    <circle cx="56" cy="56" r="48" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ 2 * 3.14159 * 48 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 48 * (1 - $enrollment->progress_percentage / 100) }}"
                                        class="text-primary-500" stroke="currentColor" stroke-linecap="round"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-2xl font-black" style="color: var(--color-text);">{{ round($enrollment->progress_percentage) }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-medium" style="color: var(--color-text-muted);">Course Progress</p>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between p-3 rounded-xl" style="background: var(--color-surface-hover);">
                                <span style="color: var(--color-text-muted);">Lessons</span>
                                <span class="font-bold" style="color: var(--color-text);">{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }}</span>
                            </div>
                            <div class="flex justify-between p-3 rounded-xl" style="background: var(--color-surface-hover);">
                                <span style="color: var(--color-text-muted);">Started</span>
                                <span class="font-bold" style="color: var(--color-text);">{{ $startedAt }}</span>
                            </div>
                            <div class="flex justify-between p-3 rounded-xl" style="background: var(--color-surface-hover);">
                                <span style="color: var(--color-text-muted);">Last Activity</span>
                                <span class="font-bold" style="color: var(--color-text);">{{ $lastAccessed }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        @if($enrollment->completed_at || $enrollment->progress_percentage >= 100)
                            <form action="{{ route('student.courses.certificate', $course) }}" method="POST" class="mt-6">
                                @csrf
                                <button type="submit" class="btn-primary w-full ripple-btn">🎓 Send Certificate to Telegram</button>
                            </form>
                        @endif

                        @if($enrollment->certificate)
                            <a href="{{ route('student.certificates.show', $enrollment->certificate) }}" class="btn-secondary w-full mt-3 block text-center">
                                View Certificate
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
