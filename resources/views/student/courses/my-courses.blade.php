@extends('layouts.app')

@section('title', __('My Courses') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-10" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-accent-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 dark:text-primary-400 text-sm font-bold mb-4 shadow-sm">
                        <span>📚</span> {{ __('Learning') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('My') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500">{{ __('Courses') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Keep learning where you left off and finish your enrolled courses.') }}
                    </p>
                </div>
                
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 flex items-center gap-2 font-bold transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        {{ __('Browse All Courses') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('student.courses.my-courses') }}" class="glass-card mb-8" data-aos="fade-up">
            <div class="glass-card-body grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input id="q" name="q" type="text" class="input-glass pl-12" value="{{ request('q') }}" placeholder="{{ __('Search your courses') }}">
                    </div>
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Status') }}</label>
                    <select id="status" name="status" class="input-glass">
                        <option value="">{{ __('All') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                </div>
            </div>
            <div class="glass-card-footer flex items-center justify-between">
                <button type="submit" class="btn-primary ripple-btn">{{ __('Apply') }}</button>
                @if(request()->query())
                    <a href="{{ route('student.courses.my-courses') }}" class="text-sm hover:text-primary-500 transition-colors" style="color: var(--color-text-muted);">{{ __('Clear Filters') }}</a>
                @endif
            </div>
        </form>

        {{-- Course Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($enrollments as $enrollment)
                @php $lastAccessed = $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Not yet'; @endphp
                <div class="glass-card group overflow-hidden cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('student.courses.learn', $enrollment->course) }}" class="block">
                        @if($enrollment->course->thumbnail)
                            <div class="relative overflow-hidden">
                                <img src="{{ Storage::url($enrollment->course->thumbnail) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $enrollment->course->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-dot-pattern opacity-20"></div>
                                <span class="text-white text-lg font-bold relative z-10">{{ Str::limit($enrollment->course->title, 20) }}</span>
                            </div>
                        @endif
                        <div class="glass-card-body">
                            <h3 class="text-lg font-bold mb-2 group-hover:text-primary-500 transition-colors" style="color: var(--color-text);">{{ $enrollment->course->title }}</h3>
                            <p class="text-sm mb-4 line-clamp-2" style="color: var(--color-text-muted);">{{ Str::limit($enrollment->course->short_description ?: $enrollment->course->description, 100) }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span style="color: var(--color-text-muted);">{{ __('Progress') }}</span>
                                    <span class="font-bold text-primary-500">{{ round($enrollment->progress_percentage) }}%</span>
                                </div>
                                <div class="w-full rounded-full h-2 overflow-hidden" style="background: var(--color-border);">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                </div>
                                <div class="text-xs mt-2" style="color: var(--color-text-muted);">{{ __('Last activity') }}: {{ $lastAccessed }}</div>
                            </div>
                        </div>
                    </a>

                    <div class="glass-card-body pt-0">
                        <div class="flex justify-between items-center pt-4" style="border-top: 1px solid var(--glass-border);">
                            @if($enrollment->is_completed)
                                <span class="badge-success">✓ {{ __('Completed') }}</span>
                                <a href="{{ route('student.courses.certificate.info', $enrollment->course) }}" class="btn-primary btn-sm">🎓 {{ __('Certificate') }}</a>
                            @else
                                <a href="{{ route('student.courses.learn', $enrollment->course) }}" class="btn-primary btn-sm ripple-btn">{{ __('Continue Learning') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16" data-aos="fade-up">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl" style="background: var(--color-surface-hover);">📚</div>
                    <p class="text-lg font-medium mb-2" style="color: var(--color-text);">{{ __('No courses yet') }}</p>
                    <p class="mb-6" style="color: var(--color-text-muted);">{{ __('Start your learning journey today!') }}</p>
                    <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn">{{ __('Browse Courses') }}</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $enrollments->links() }}</div>
    </div>
</div>
@endsection
