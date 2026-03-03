@extends('layouts.admin')
@section('title', __('Enrollment Progress'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $student->name }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $enrollment->course->title }}</p>
            <a href="{{ route('admin.students.enrollments', $student) }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Enrollments') }}</a>
        </div>
        @php $totalTime = $enrollment->lessonProgress->sum('time_spent'); @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php $progressStats = [
                ['v' => round($enrollment->progress_percentage).'%', 'l' => 'Overall Progress', 'c' => 'text-primary-500'],
                ['v' => $enrollment->completed_lessons.'/'.$enrollment->total_lessons, 'l' => 'Lessons', 'c' => 'text-emerald-500'],
                ['v' => $enrollment->quizAttempts()->count(), 'l' => 'Quiz Attempts', 'c' => 'text-blue-500'],
                ['v' => $totalTime ? gmdate('H:i', $totalTime) : '0:00', 'l' => 'Time Spent', 'c' => 'text-amber-500'],
            ]; @endphp
            @foreach($progressStats as $i => $ps)
            <div class="glass-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="glass-card-body text-center">
                    <div class="text-3xl font-extrabold {{ $ps['c'] }}">{{ $ps['v'] }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ $ps['l'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Lesson Progress') }}</h3></div>
            <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                @foreach($enrollment->course->lessons as $lesson)
                @php $progress = $enrollment->lessonProgress->where('lesson_id', $lesson->id)->first(); @endphp
                <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4 {{ $progress && $progress->is_completed ? 'bg-emerald-500/10 text-emerald-500' : '' }}" style="{{ !($progress && $progress->is_completed) ? 'background: var(--color-surface-hover); color: var(--color-text-muted);' : '' }}">
                            {{ $progress && $progress->is_completed ? '✓' : $loop->iteration }}
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->title }}</div>
                            @if($progress)
                                <div class="text-xs" style="color: var(--color-text-muted);">
                                    @if($progress->is_completed) Completed {{ $progress->completed_at->diffForHumans() }}
                                    @else In progress - {{ $progress->time_spent ? gmdate('i:s', $progress->time_spent) : '0:00' }} spent @endif
                                </div>
                            @else
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ __('Not started') }}</div>
                            @endif
                        </div>
                    </div>
                    @if($progress && $progress->is_completed)<span class="badge-success">{{ __('Completed') }}</span>@endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
