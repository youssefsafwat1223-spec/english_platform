@extends('layouts.admin')
@section('title', __('Student Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $student->name }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Student Profile and Activity') }}</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Student Information') }}</h3></div>
                    <div class="glass-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Email') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $student->email }}</div></div>
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Phone') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $student->phone ?? '-' }}</div></div>
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Joined') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $student->created_at->format('M d, Y') }}</div></div>
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Last Active') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $student->last_activity_at ? $student->last_activity_at->diffForHumans() : 'Never' }}</div></div>
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Telegram') }}</div>@if($student->is_telegram_linked)<span class="badge-success">{{ __('Linked') }}</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Not Linked') }}</span>@endif</div>
                            <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Status') }}</div>@if($student->is_active)<span class="badge-success">{{ __('Active') }}</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive') }}</span>@endif</div>
                        </div>
                    </div>
                </div>
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Performance Overview') }}</h3></div>
                    <div class="glass-card-body">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            @php $perfStats = [
                                ['v' => $stats['total_enrollments'], 'l' => 'Enrollments', 'c' => 'text-primary-500'],
                                ['v' => $stats['completed_courses'], 'l' => 'Completed', 'c' => 'text-emerald-500'],
                                ['v' => $stats['active_courses'], 'l' => 'Active', 'c' => 'text-blue-500'],
                                ['v' => $stats['certificates_earned'], 'l' => 'Certificates', 'c' => 'text-amber-500'],
                                ['v' => $stats['quizzes_taken'], 'l' => 'Quizzes Taken', 'c' => ''],
                                ['v' => $stats['quizzes_passed'], 'l' => 'Passed', 'c' => 'text-emerald-500'],
                                ['v' => $stats['total_points'], 'l' => 'Points', 'c' => 'text-primary-500'],
                                ['v' => '#'.$stats['rank'], 'l' => 'Rank', 'c' => ''],
                            ]; @endphp
                            @foreach($perfStats as $ps)
                            <div><div class="text-2xl font-extrabold {{ $ps['c'] }}">{{ $ps['v'] }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ $ps['l'] }}</div></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header flex justify-between items-center"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Recent Enrollments') }}</h3><a href="{{ route('admin.students.enrollments', $student) }}" class="text-primary-500 font-bold text-sm hover:underline">{{ __('View All') }}</a></div>
                    <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                        @forelse($student->enrollments()->latest()->take(5)->get() as $enrollment)
                        <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                            <div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $enrollment->course->title }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ $enrollment->created_at->diffForHumans() }}</div></div>
                            <div class="text-right"><div class="text-sm font-bold text-primary-500">{{ round($enrollment->progress_percentage) }}%</div>@if($enrollment->is_completed)<span class="badge-success text-[10px]">{{ __('Completed') }}</span>@endif</div>
                        </div>
                        @empty<p class="text-center py-4 text-sm" style="color: var(--color-text-muted);">{{ __('No enrollments') }}</p>@endforelse
                    </div>
                </div>
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Recent Activity') }}</h3></div>
                    <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                        @forelse($student->pointsHistory()->latest()->take(10)->get() as $activity)
                        <div class="flex justify-between items-center py-2 first:pt-0 last:pb-0">
                            <div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $activity->description }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ $activity->created_at->diffForHumans() }}</div></div>
                            <span class="font-bold text-emerald-500">+{{ $activity->points_earned }}</span>
                        </div>
                        @empty<p class="text-center py-4 text-sm" style="color: var(--color-text-muted);">{{ __('No activity') }}</p>@endforelse
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Actions') }}</h3></div>
                    <div class="glass-card-body space-y-2">
                        <form action="{{ route('admin.students.toggle-status', $student) }}" method="POST">@csrf<button type="submit" class="{{ $student->is_active ? 'btn-secondary' : 'btn-primary ripple-btn' }} w-full">{{ $student->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                        <a href="{{ route('admin.students.enrollments', $student) }}" class="btn-secondary w-full text-center block">{{ __('View Enrollments') }}</a>
                        <a href="{{ route('admin.students.index') }}" class="btn-secondary w-full text-center block">{{ __('← Back') }}</a>
                    </div>
                </div>

                @php
                    $enrolledCourseIds = $student->enrollments()->pluck('course_id');
                    $availableCourses  = \App\Models\Course::active()
                        ->whereNotIn('id', $enrolledCourseIds)
                        ->orderBy('title')
                        ->get();
                @endphp
                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="50">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">🎁 منح وصول مجاني</h3></div>
                    <div class="glass-card-body">
                        @if($availableCourses->isEmpty())
                            <p class="text-sm text-center" style="color: var(--color-text-muted);">الطالب مسجل في جميع الكورسات</p>
                        @else
                            <form action="{{ route('admin.students.grant-access', $student) }}" method="POST" class="space-y-3">
                                @csrf
                                <select name="course_id" class="input-glass w-full" required>
                                    <option value="">اختر الكورس...</option>
                                    @foreach($availableCourses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary ripple-btn w-full" onclick="return confirm('فتح الكورس للطالب مجاناً؟')">
                                    فتح الكورس
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @if($student->is_telegram_linked)
                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Send Telegram Message') }}</h3></div>
                    <div class="glass-card-body">
                        <form action="{{ route('admin.students.send-message', $student) }}" method="POST" class="space-y-3">@csrf
                            <textarea name="message" rows="4" class="input-glass" placeholder="{{ __('Write a message...') }}" required></textarea>
                            <button type="submit" class="btn-primary ripple-btn w-full">{{ __('Send Message') }}</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="200">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Streaks') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Current') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['current_streak'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Longest') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['longest_streak'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Daily Answered') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['daily_questions_answered'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Daily Correct') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['daily_questions_correct'] }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
