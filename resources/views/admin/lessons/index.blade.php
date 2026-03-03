@extends('layouts.admin')
@section('title', __('Manage Lessons'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Manage Lessons') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.courses.show', $course) }}" class="btn-secondary">{{ __('? Back') }}</a>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn-primary ripple-btn">{{ __('+ Add Lesson') }}</a>
            </div>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                @forelse($lessons as $index => $lesson)
                    <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group">
                        <div class="flex items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-primary-500 font-bold mr-4" style="background: var(--color-surface-hover);">{{ $index + 1 }}</div>
                            <div class="flex-1">
                                <div class="font-bold flex items-center gap-2" style="color: var(--color-text);">
                                    {{ $lesson->title }}
                                    <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-white/10" style="color: var(--color-text-muted);">{{ __('ID:') }} {{ $lesson->id }}</span>
                                </div>
                                <div class="flex gap-2 text-xs mt-1" style="color: var(--color-text-muted);">
                                    @if($lesson->video_url)<span>{{ __('?? Video') }}</span>@endif
                                    @if($lesson->has_quiz)<span>{{ __('?? Quiz') }}</span>@endif
                                    @if($lesson->is_free)<span class="badge-success text-[10px]">{{ __('Free') }}</span>@endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a>
                            <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" onsubmit="return confirm('Delete this lesson?');">@csrf @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12" style="color: var(--color-text-muted);">
                        <div class="text-4xl mb-4">??</div>
                        <p class="mb-4">{{ __('No lessons yet') }}</p>
                        <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn-primary ripple-btn">{{ __('Create First Lesson') }}</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
