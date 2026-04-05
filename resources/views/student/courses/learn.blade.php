@extends('layouts.app')

@section('title', $course->title . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
    $startedAt = $enrollment->started_at ? $enrollment->started_at->format('M d, Y') : '-';
    $lastAccessed = $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : __('ui.learn.not_started');
    $progress = (float) ($enrollment->progress_percentage ?? 0);
    $expiresAt = $enrollment->expires_at;
    $remainingDays = null;
    if ($expiresAt) {
        $diffSeconds = now()->diffInSeconds($expiresAt, false);
        $remainingDays = (int) max(0, ceil($diffSeconds / 86400));
    }

    $levels = $course->levels()
        ->active()
        ->ordered()
        ->with([
            'lessons' => fn ($query) => $query->orderBy('order_index'),
            'lessons.quiz',
            'lessons.pronunciationExercise',
            'lessons.writingExercise',
        ])
        ->get();

    $orphanLessons = $course->lessons()
        ->whereNull('course_level_id')
        ->orderBy('order_index')
        ->with(['quiz', 'pronunciationExercise', 'writingExercise'])
        ->get();
@endphp

<div class="min-h-screen bg-slate-50 dark:bg-[#020617]">
    <div class="student-container max-w-7xl pt-10 pb-16">
        <x-student.page-header
            :title="$course->title"
            :subtitle="__('ui.learn.keep_progress_text')"
            :badge="__($course->level ?? 'Beginner')"
        >
            <x-slot name="actions">
                <a href="{{ route('student.courses.my-courses') }}" class="btn-ghost btn-sm">
                    {{ __('ui.learn.back_to_my_courses') }}
                </a>
                @if($currentLesson)
                    <a href="{{ route('student.lessons.show', [$course, $currentLesson]) }}" class="btn-primary btn-sm">
                        {{ __('ui.learn.continue_lessons') }}
                    </a>
                @endif
            </x-slot>
        </x-student.page-header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-6">
                <x-student.card
                    title="{{ __('ui.learn.curriculum_title') }}"
                    icon='<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'
                >
                    <div class="space-y-4">
                        @forelse($levels as $levelIndex => $level)
                            @php
                                $completionPercent = $level->getCompletionPercentageFor(auth()->user());
                                $isCompleted = $completionPercent === 100;
                                $isUnlocked = true;
                            @endphp
                            <div x-data="{ openLevel: false }" class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-slate-900/40 overflow-hidden">
                                <button type="button" @click="openLevel = !openLevel" class="w-full flex items-start sm:items-center justify-between gap-4 px-5 py-4 text-left">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3">
                                            <span class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm border {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-primary-50 text-primary-600 border-primary-200 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20' }}">
                                                {{ $levelIndex + 1 }}
                                            </span>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white line-clamp-2">
                                                {{ $level->title }}
                                            </h3>
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full border border-slate-200 dark:border-white/10 bg-white/80 dark:bg-white/5">
                                                {{ __('ui.learn.lessons') }}: {{ $level->lessons->count() }}
                                            </span>
                                            @if($completionPercent > 0)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full border border-primary-200 dark:border-primary-500/20 bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                                    {{ __('ui.learn.progress') }}: {{ round($completionPercent) }}%
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full border {{ $isCompleted ? 'border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'border-slate-200 dark:border-white/10 bg-white/80 dark:bg-white/5 text-slate-500 dark:text-slate-400' }}">
                                                {{ $isCompleted ? __('ui.learn.completed') : __('ui.learn.available_now') }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="shrink-0 text-slate-400 dark:text-slate-500">
                                        <svg class="w-5 h-5 transition-transform" :class="openLevel ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                @if($isUnlocked && $completionPercent > 0 && !$isCompleted)
                                    <div class="h-1 bg-slate-100 dark:bg-slate-800">
                                        <div class="h-full bg-gradient-to-r from-primary-500 to-accent-500" style="width: {{ $completionPercent }}%"></div>
                                    </div>
                                @endif
                                <div x-show="openLevel" x-collapse x-cloak class="border-t border-slate-200 dark:border-white/10 bg-slate-50/60 dark:bg-slate-900/60">
                                    <div class="divide-y divide-slate-200/60 dark:divide-white/10">
                                        @foreach($level->lessons as $lesson)
                                            @php
                                                $lessonProgress = collect($enrollment->lessonProgress)->firstWhere('lesson_id', $lesson->id);
                                                $isLessonCompleted = $lessonProgress && $lessonProgress->is_completed;
                                                $isCurrent = $currentLesson && $currentLesson->id === $lesson->id;
                                                $titleForMatch = mb_strtolower((string) $lesson->title, 'UTF-8');
                                                $hasQuizFeature = (bool) (
                                                    $lesson->has_quiz
                                                    || $lesson->quiz
                                                    || str_contains($titleForMatch, 'quiz')
                                                    || str_contains($titleForMatch, 'test')
                                                    || str_contains($titleForMatch, 'exam')
                                                    || str_contains($titleForMatch, 'اختبار')
                                                    || str_contains($titleForMatch, 'امتحان')
                                                );
                                                $hasWritingFeature = (bool) (
                                                    $lesson->has_writing_exercise
                                                    || $lesson->writingExercise
                                                    || str_contains($titleForMatch, 'writing')
                                                    || str_contains($titleForMatch, 'كتابة')
                                                );
                                                $hasPronunciationFeature = (bool) (
                                                    $lesson->has_pronunciation_exercise
                                                    || $lesson->pronunciationExercise
                                                    || str_contains($titleForMatch, 'pronunciation')
                                                    || str_contains($titleForMatch, 'speaking')
                                                    || str_contains($titleForMatch, 'نطق')
                                                );
                                            @endphp
                                            <a href="{{ route('student.lessons.show', [$course, $lesson]) }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 hover:bg-white/80 dark:hover:bg-slate-900 transition-colors">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black border {{ $isLessonCompleted ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-white/10' }}">
                                                        {{ $isLessonCompleted ? '✓' : '▶' }}
                                                    </span>
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-2">
                                                            {{ $lesson->title }}
                                                        </div>
                                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                                                            @if($lesson->video_duration)
                                                                <span>{{ $lesson->formatted_duration }}</span>
                                                            @endif
                                                            @if($hasQuizFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                                                                    {{ $isArabic ? 'اختبار' : 'Quiz' }}
                                                                </span>
                                                            @endif
                                                            @if($hasWritingFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                                                                    {{ $isArabic ? 'كتابة' : 'Writing' }}
                                                                </span>
                                                            @endif
                                                            @if($hasPronunciationFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                                                    {{ $isArabic ? 'نطق' : 'Speaking' }}
                                                                </span>
                                                            @endif
                                                            @if($isCurrent)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-accent-50 text-accent-600 dark:bg-accent-500/10 dark:text-accent-400">
                                                                    {{ __('ui.learn.continue_lesson') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-black text-slate-500 dark:text-slate-400">
                                                    {{ $isLessonCompleted ? __('ui.learn.review_lesson') : __('ui.learn.watch_lesson') }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <x-student.empty-state
                                title="{{ __('ui.learn.no_lessons_title') }}"
                                message="{{ __('ui.learn.no_lessons_text') }}"
                            >
                                <x-slot name="icon">
                                    <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </x-slot>
                            </x-student.empty-state>
                        @endforelse

                        @if($orphanLessons->count() > 0)
                            <div x-data="{ openOrphan: false }" class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-slate-900/40 overflow-hidden">
                                <button type="button" @click="openOrphan = !openOrphan" class="w-full flex items-start sm:items-center justify-between gap-4 px-5 py-4 text-left">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3">
                                            <span class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm border bg-primary-50 text-primary-600 border-primary-200 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20">
                                                +
                                            </span>
                                            <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white line-clamp-2">
                                                {{ __('ui.learn.bonus_lessons') }}
                                            </h3>
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full border border-slate-200 dark:border-white/10 bg-white/80 dark:bg-white/5">
                                                {{ __('ui.learn.lessons') }}: {{ $orphanLessons->count() }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full border border-primary-200 dark:border-primary-500/20 bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400">
                                                {{ __('ui.learn.always_available') }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="shrink-0 text-slate-400 dark:text-slate-500">
                                        <svg class="w-5 h-5 transition-transform" :class="openOrphan ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <div x-show="openOrphan" x-collapse x-cloak class="border-t border-slate-200 dark:border-white/10 bg-slate-50/60 dark:bg-slate-900/60">
                                    <div class="divide-y divide-slate-200/60 dark:divide-white/10">
                                        @foreach($orphanLessons as $lesson)
                                            @php
                                                $lessonProgress = collect($enrollment->lessonProgress)->firstWhere('lesson_id', $lesson->id);
                                                $isLessonCompleted = $lessonProgress && $lessonProgress->is_completed;
                                                $titleForMatch = mb_strtolower((string) $lesson->title, 'UTF-8');
                                                $hasQuizFeature = (bool) (
                                                    $lesson->has_quiz
                                                    || $lesson->quiz
                                                    || str_contains($titleForMatch, 'quiz')
                                                    || str_contains($titleForMatch, 'test')
                                                    || str_contains($titleForMatch, 'exam')
                                                    || str_contains($titleForMatch, 'اختبار')
                                                    || str_contains($titleForMatch, 'امتحان')
                                                );
                                                $hasWritingFeature = (bool) (
                                                    $lesson->has_writing_exercise
                                                    || $lesson->writingExercise
                                                    || str_contains($titleForMatch, 'writing')
                                                    || str_contains($titleForMatch, 'كتابة')
                                                );
                                                $hasPronunciationFeature = (bool) (
                                                    $lesson->has_pronunciation_exercise
                                                    || $lesson->pronunciationExercise
                                                    || str_contains($titleForMatch, 'pronunciation')
                                                    || str_contains($titleForMatch, 'speaking')
                                                    || str_contains($titleForMatch, 'نطق')
                                                );
                                            @endphp
                                            <a href="{{ route('student.lessons.show', [$course, $lesson]) }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 hover:bg-white/80 dark:hover:bg-slate-900 transition-colors">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black border {{ $isLessonCompleted ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-white/10' }}">
                                                        {{ $isLessonCompleted ? '✓' : '▶' }}
                                                    </span>
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-2">
                                                            {{ $lesson->title }}
                                                        </div>
                                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                                                            @if($lesson->video_duration)
                                                                <span>{{ $lesson->formatted_duration }}</span>
                                                            @endif
                                                            @if($hasQuizFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                                                                    {{ $isArabic ? 'اختبار' : 'Quiz' }}
                                                                </span>
                                                            @endif
                                                            @if($hasWritingFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                                                                    {{ $isArabic ? 'كتابة' : 'Writing' }}
                                                                </span>
                                                            @endif
                                                            @if($hasPronunciationFeature)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                                                    {{ $isArabic ? 'نطق' : 'Speaking' }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-black text-slate-500 dark:text-slate-400">
                                                    {{ $isLessonCompleted ? __('ui.learn.review_lesson') : __('ui.learn.watch_lesson') }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-student.card>
            </div>

            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <x-student.card title="{{ __('ui.learn.progress_badge') }}">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between text-sm font-bold text-slate-500 dark:text-slate-400 mb-2">
                                    <span>{{ __('ui.learn.progress') }}</span>
                                    <span class="text-slate-900 dark:text-white">{{ round($progress) }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-primary-500 to-accent-500" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10">
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('ui.learn.completed_lessons') }}</span>
                                    <span class="font-black text-slate-900 dark:text-white">{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10">
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('ui.learn.start_date') }}</span>
                                    <span class="font-black text-slate-900 dark:text-white">{{ $startedAt }}</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10">
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('ui.learn.last_activity') }}</span>
                                    <span class="font-black text-slate-900 dark:text-white">{{ $lastAccessed }}</span>
                                </div>
                                @if(!is_null($remainingDays))
                                    <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10">
                                        <span class="text-slate-500 dark:text-slate-400">{{ $isArabic ? 'المتبقي' : 'Remaining' }}</span>
                                        <span class="font-black text-slate-900 dark:text-white">{{ $remainingDays }} {{ $isArabic ? 'يوم' : 'days' }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($enrollment->completed_at || $enrollment->progress_percentage >= 100)
                                <form action="{{ route('student.courses.certificate', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full">
                                        {{ __('ui.learn.send_certificate_to_telegram') }}
                                    </button>
                                </form>
                            @endif

                            @if($enrollment->certificate)
                                <a href="{{ route('student.certificates.show', $enrollment->certificate) }}" class="btn-secondary w-full">
                                    {{ __('ui.learn.view_certificate') }}
                                </a>
                            @endif
                        </div>
                    </x-student.card>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
