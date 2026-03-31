@extends('layouts.app')

@section('title', __('My Courses') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="student-container relative z-10">
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('My') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500'>{{ __('Courses') }}</span>"
            subtitle="{{ __('Keep learning where you left off and finish your enrolled courses.') }}"
            badge="{{ __('Learning') }}"
            badgeColor="primary"
        >
            <x-slot name="actions">
                <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 flex items-center gap-2 font-bold transition-all transform hover:scale-105">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    {{ __('Browse All Courses') }}
                </a>
            </x-slot>
        </x-student.page-header>

        {{-- Filters --}}
        <x-student.card padding="p-0" class="mb-8" data-aos="fade-up">
            <form method="GET" action="{{ route('student.courses.my-courses') }}" class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input id="q" name="q" type="text" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 pl-12 pr-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-inner" value="{{ request('q') }}" placeholder="{{ __('Search your courses') }}">
                    </div>
                </div>
                <div>
                    <label for="status" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Status') }}</label>
                    <select id="status" name="status" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pr-10 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-inner appearance-none relative">
                        <option value="">{{ __('All') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200 dark:border-white/5">
                <button type="submit" class="btn-primary ripple-btn px-8 py-2.5 rounded-xl shadow-lg shadow-primary-500/25 w-full sm:w-auto font-bold">{{ __('Apply') }}</button>
                @if(request()->query())
                    <a href="{{ route('student.courses.my-courses') }}" class="btn-ghost btn-sm text-slate-500 hover:text-primary-500 font-bold">{{ __('Clear Filters') }}</a>
                @endif
            </div>
            </form>
        </x-student.card>

        {{-- Course Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($enrollments as $enrollment)
                @php $lastAccessed = $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Not yet'; @endphp
                <x-student.card padding="p-0" class="group overflow-hidden cursor-pointer hover:-translate-y-1 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
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
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2 group-hover:text-primary-500 transition-colors text-slate-900 dark:text-white">{{ $enrollment->course->title }}</h3>
                            <p class="text-sm mb-4 line-clamp-2 text-slate-600 dark:text-slate-400">{{ Str::limit($enrollment->course->short_description ?: $enrollment->course->description, 100) }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Progress') }}</span>
                                    <span class="font-bold text-primary-500">{{ round($enrollment->progress_percentage) }}%</span>
                                </div>
                                <div class="w-full rounded-full h-2 overflow-hidden bg-slate-100 dark:bg-slate-800 inset-shadow-sm">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-1000" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                </div>
                                <div class="text-xs mt-2 text-slate-500 dark:text-slate-400">{{ __('Last activity') }}: {{ $lastAccessed }}</div>
                            </div>
                        </div>
                    </a>

                    <div class="p-6 pt-0">
                        <div class="flex justify-between items-center pt-4 border-t border-slate-200 dark:border-white/10">
                            @if($enrollment->is_completed)
                                <span class="badge-success badge text-emerald-600 bg-emerald-100 px-2 py-1 rounded text-xs font-bold">✓ {{ __('Completed') }}</span>
                                <a href="{{ route('student.courses.certificate.info', $enrollment->course) }}" class="btn-primary btn-sm">🎓 {{ __('Certificate') }}</a>
                            @else
                                <a href="{{ route('student.courses.learn', $enrollment->course) }}" class="btn-primary btn-sm ripple-btn shadow-md shadow-primary-500/20 font-bold px-4">{{ __('Continue Learning') }}</a>
                            @endif
                        </div>
                    </div>
                </x-student.card>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <x-student.empty-state
                        title="{{ __('No courses yet') }}"
                        message="{{ __('Start your learning journey today and enroll in your first course to see it here!') }}"
                        :icon="\"<svg class='w-10 h-10 text-slate-500 dark:text-slate-300' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.8' d='M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'/></svg>\""
                        data-aos="fade-up"
                    >
                        <x-slot name="actions">
                            <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-6 py-2.5 font-bold shadow-lg shadow-primary-500/30">{{ __('Browse Courses') }}</a>
                        </x-slot>
                    </x-student.empty-state>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $enrollments->links() }}</div>
    </div>
</div>
@endsection






