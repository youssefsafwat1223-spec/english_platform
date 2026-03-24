@extends('layouts.admin')
@section('title', __('Manage Courses'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Manage Courses') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Create and manage your English courses') }}</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn-primary ripple-btn">{{ __('+ Create New Course') }}</a>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Course') }}</th><th>{{ __('Price') }}</th><th>{{ __('Lessons') }}</th><th>{{ __('Students') }}</th><th>{{ __('Enrollments') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        @if($course->thumbnail)
                                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold mr-3 text-primary-500" style="background: var(--color-surface-hover);">{{ strtoupper(substr($course->title, 0, 1)) }}</div>
                                        @endif
                                        <div>
                                            <div class="font-bold flex items-center gap-2" style="color: var(--color-text);">
                                                {{ $course->title }}
                                                <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-white/10" style="color: var(--color-text-muted);">{{ __('ID:') }} {{ $course->id }}</span>
                                            </div>
                                            <div class="text-xs" style="color: var(--color-text-muted);">{{ $course->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-bold">{{ number_format($course->price, 2) }} {{ __('ر.س') }}</td>
                                <td>{{ $course->lessons_count }}</td>
                                <td>{{ $course->students_count }}</td>
                                <td>{{ $course->enrollments_count }}</td>
                                <td>
                                    @if($course->is_active)
                                        <span class="badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('Edit') }}</a>
                                        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('Lessons') }}</a>
                                        <form action="{{ route('admin.courses.toggle-status', $course) }}" method="POST" class="inline">@csrf
                                            <button type="submit" class="text-amber-500 text-sm font-bold hover:underline">{{ $course->is_active ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);">
                                    <div class="text-4xl mb-4">📚</div>
                                    <p class="mb-4">{{ __('No courses found') }}</p>
                                    <a href="{{ route('admin.courses.create') }}" class="btn-primary ripple-btn">{{ __('Create Your First Course') }}</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($courses) && method_exists($courses, 'links'))
                <div class="glass-card-footer">{{ $courses->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
