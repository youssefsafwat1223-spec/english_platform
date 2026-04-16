@extends('layouts.admin')
@section('title', $course->title . ' — Admin')
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $course->title }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Course details and performance') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn-primary ripple-btn">{{ __('Edit Course') }}</a>
                <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn-secondary">📊 {{ __('العناوين') }}</a>
                <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn-secondary">{{ __('Manage Lessons') }}</a>
                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn-secondary">{{ __('+ Add Lesson') }}</a>
                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?');">@csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-red-500/10 text-red-500 text-sm font-bold hover:bg-red-500/20 transition-colors">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                @if($course->thumbnail)
                    <div class="glass-card overflow-hidden" data-aos="fade-up">
                        <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-64 object-cover" alt="{{ $course->title }}">
                    </div>
                @endif

                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Description') }}</h3></div>
                    <div class="glass-card-body">
                        @if($course->short_description)<p class="mb-4" style="color: var(--color-text);">{{ $course->short_description }}</p>@endif
                        <p class="whitespace-pre-line" style="color: var(--color-text);">{{ $course->description }}</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Course Details') }}</h3></div>
                    <div class="glass-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php $details = [
                                ['label' => 'Price', 'value' => number_format($course->price, 2) . ' ' . __('ر.س')],
                                ['label' => 'Status', 'badge' => true],
                                ['label' => 'Type / النوع', 'value' => $course->is_exam ? __('اختبار فقط / Exam Only') : __('كورس عام / General Course')],
                                ['label' => 'Prerequisite', 'value' => $course->prerequisite ? $course->prerequisite->title : __('بلا شروط مسبقة')],
                                ['label' => 'Suggested Pace', 'value' => $course->estimated_duration_weeks ? $course->estimated_duration_weeks . ' weeks (display only)' : 'Not set'],
                                ['label' => 'Created By', 'value' => $course->creator?->name ?? 'System'],
                                ['label' => 'Created At', 'value' => $course->created_at->format('M d, Y')],
                                ['label' => 'Intro Video', 'link' => true],
                            ]; @endphp
                            @foreach($details as $d)
                                <div>
                                    <div class="text-xs font-medium" style="color: var(--color-text-muted);">{{ $d['label'] }}</div>
                                    @if(isset($d['badge']))
                                        @if($course->is_active)<span class="badge-success">{{ __('Active') }}</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive') }}</span>@endif
                                    @elseif(isset($d['link']))
                                        @if($course->intro_video_url)<a href="{{ $course->intro_video_url }}" class="text-primary-500 font-bold text-sm hover:underline" target="_blank">{{ __('Open Video') }}</a>@else<span style="color: var(--color-text-muted);">{{ __('Not set') }}</span>@endif
                                    @else
                                        <div class="font-bold" style="color: var(--color-text);">{{ $d['value'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Levels & Lessons --}}
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header flex justify-between items-center">
                        <h3 class="font-bold" style="color: var(--color-text);">{{ __('العناوين والدروس') }} ({{ $course->levels->count() }} {{ __('عنوان') }})</h3>
                        <a href="{{ route('admin.courses.levels.index', $course) }}" class="text-primary-500 font-bold text-sm hover:underline">{{ __('إدارة العناوين') }}</a>
                    </div>
                    <div class="glass-card-body space-y-4">
                        @forelse($course->levels()->withCount('lessons')->ordered()->get() as $level)
                            <div class="rounded-xl border p-4" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm">{{ $level->order_index + 1 }}</div>
                                        <div>
                                            <div class="font-bold text-sm" style="color: var(--color-text);">{{ $level->title }}</div>
                                            <div class="text-xs" style="color: var(--color-text-muted);">{{ $level->lessons_count }} {{ __('درس') }}</div>
                                        </div>
                                    </div>
                                    @if(!$level->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('مخفي') }}</span>
                                    @endif
                                </div>
                                @if($level->lessons->count())
                                    <div class="space-y-1 mr-12">
                                        @foreach($level->lessons as $lesson)
                                            <div class="flex items-center justify-between py-1.5">
                                                <div class="text-sm" style="color: var(--color-text);">{{ $lesson->title }}</div>
                                                <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="text-primary-500 font-bold text-xs hover:underline">{{ __('View') }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs mr-12" style="color: var(--color-text-muted);">{{ __('لا توجد دروس بعد') }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm mb-3" style="color: var(--color-text-muted);">{{ __('لا توجد عناوين حتى الآن') }}</p>
                                <a href="{{ route('admin.courses.levels.create', $course) }}" class="btn-primary ripple-btn text-sm">{{ __('+ إضافة أول عنوان') }}</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Statistics') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        @php $statItems = [
                            ['l' => 'Total Students', 'v' => $stats['total_students']],
                            ['l' => 'Active Enrollments', 'v' => $stats['active_enrollments']],
                            ['l' => 'Completed', 'v' => $stats['completed_enrollments']],
                            ['l' => 'Avg Progress', 'v' => number_format($stats['average_progress'] ?? 0, 1) . '%'],
                            ['l' => 'Lessons', 'v' => $stats['total_lessons']],
                            ['l' => 'Questions', 'v' => $stats['total_questions']],
                            ['l' => 'Quizzes', 'v' => $stats['total_quizzes']],
                            ['l' => 'Revenue', 'v' => number_format($stats['revenue'], 2) . ' ' . __('ر.س'), 'color' => 'text-emerald-500'],
                        ]; @endphp
                        @foreach($statItems as $si)
                            <div class="flex justify-between">
                                <span class="text-sm" style="color: var(--color-text-muted);">{{ $si['l'] }}</span>
                                <span class="font-bold text-sm {{ $si['color'] ?? '' }}" style="{{ !isset($si['color']) ? 'color: var(--color-text);' : '' }}">{{ $si['v'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card-body space-y-2">
                        <form action="{{ route('admin.courses.toggle-status', $course) }}" method="POST">@csrf
                            <button type="submit" class="{{ $course->is_active ? 'btn-secondary' : 'btn-primary ripple-btn' }} w-full">{{ $course->is_active ? 'Deactivate Course' : 'Activate Course' }}</button>
                        </form>
                        <a href="{{ route('admin.courses.index') }}" class="btn-secondary w-full text-center block">{{ __('Back to Courses') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
