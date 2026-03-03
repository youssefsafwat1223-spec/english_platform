@extends('layouts.admin')
@section('title', __('Course & Lesson ID Reference'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('ID Reference Guide') }}</span></h1>
            <p class="mt-2 text-sm" style="color: var(--color-text-muted);">{{ __('Use these IDs in your CSV file when importing questions.') }}</p>
            <a href="{{ route('admin.questions.import') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Import') }}</a>
        </div>

        @forelse($courses as $course)
        <div class="glass-card mb-6 overflow-hidden" data-aos="fade-up">
            <div class="glass-card-header bg-slate-100 dark:bg-white/5 flex justify-between items-center py-3">
                <h3 class="font-bold text-lg flex items-center gap-2" style="color: var(--color-text);">
                    <span class="text-2xl">📚</span> {{ $course->title }}
                </h3>
                <span class="px-3 py-1 rounded bg-primary-500 text-white font-mono text-sm shadow font-bold">
                    {{ __('Course ID:') }} {{ $course->id }}
                </span>
            </div>
            <div class="glass-card-body p-0">
                @if($course->lessons->count() > 0)
                    <table class="table-glass w-full m-0">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider" style="color: var(--color-text-muted);">
                                <th class="px-6 py-3 border-b border-slate-200 dark:border-white/10">{{ __('Lesson Title') }}</th>
                                <th class="px-6 py-3 border-b border-slate-200 dark:border-white/10 w-32 text-right">{{ __('Lesson ID') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($course->lessons as $lesson)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 font-medium" style="color: var(--color-text);">{{ $lesson->title }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 py-1 rounded font-mono text-xs bg-slate-200 dark:bg-white/10" style="color: var(--color-text);">
                                        {{ $lesson->id }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-sm" style="color: var(--color-text-muted);">
                        {{ __('No lessons found for this course.') }}
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="glass-card p-12 text-center" data-aos="fade-up">
            <div class="text-4xl mb-4">📭</div>
            <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">{{ __('No Courses Available') }}</h3>
            <p style="color: var(--color-text-muted);">{{ __('Create a course first before importing questions.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
