@extends('layouts.app')

@section('title', $course->title . ' | ' . config('app.name', __('إتقان الإنجليزية')))
@section('meta_description', Str::limit(strip_tags($course->short_description ?: $course->description), 160))
@section('meta_keywords', __('كورس') . ' ' . $course->title . ', ' . __('تعلم الإنجليزية') . ', ' . __('كورسات إنجليزي') . ', ' . ($course->level ?? __('جميع المستويات')))
@section('og_title', $course->title)
@section('og_image', $course->thumbnail ? asset(Storage::url($course->thumbnail)) : asset('logo.jpg'))
@section('og_type', 'article')

@section('json_ld')
{
    "@context": "https://schema.org",
    "@type": "Course",
    "name": "{{ $course->title }}",
    "description": "{{ Str::limit(strip_tags($course->short_description ?: $course->description), 160) }}",
    "provider": {
        "@type": "Organization",
        "name": "Simple English",
        "sameAs": "{{ config('app.url') }}"
    }
}
@endsection

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
    $currencyLabel = $isArabic ? 'ر.س' : 'SAR';
    $durationWeeks = $course->estimated_duration_weeks;
    $headingCount = (int) ($headingCount ?? 0);
    $completedHeadingCount = (int) ($completedHeadingCount ?? 0);
    $expiresAt = $isEnrolled && isset($enrollment) ? $enrollment->expires_at : null;
    $remainingDays = null;
    if ($expiresAt) {
        $diffSeconds = now()->diffInSeconds($expiresAt, false);
        $remainingDays = (int) max(0, ceil($diffSeconds / 86400));
    }
@endphp

<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        <x-student.page-header
            title="{{ $course->title }}"
            subtitle="{{ $course->short_description ?: $course->description }}"
            badge="{{ $course->level ?? ($isArabic ? 'منهج منظم' : 'Structured course') }}"
            badgeColor="primary"
            badgeIcon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
        >
            <x-slot name="actions">
                <a href="{{ route('student.courses.my-courses') }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ $isArabic ? 'العودة للكورسات' : 'Back to courses' }}
                </a>
                @if($isEnrolled ?? false)
                    <a href="{{ route('student.courses.learn', $course) }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold flex items-center gap-2">
                        {{ $isArabic ? 'متابعة التعلم' : 'Continue learning' }}
                        <svg class="w-4 h-4 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <a href="{{ route('student.courses.enroll', $course) }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold">
                        {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                    </a>
                @endif
            </x-slot>
        </x-student.page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-student.card padding="p-0" class="overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-0">
                        <div class="md:col-span-2">
                            <div class="relative h-full min-h-[220px] md:min-h-full bg-slate-100 dark:bg-slate-900">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 dark:text-slate-600 text-4xl font-black">
                                        {{ Str::substr($course->title, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="md:col-span-3 p-6 md:p-8 space-y-5">
                            <div class="grid grid-cols-2 {{ ($showCourseStudentCount ?? true) ? 'md:grid-cols-3' : 'md:grid-cols-2' }} gap-3 text-sm">
                                <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-4 py-3">
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'العناوين' : 'Headings' }}</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $headingCount }}</div>
                                </div>
                                @if($showCourseStudentCount ?? true)
                                <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-4 py-3">
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'الطلاب' : 'Students' }}</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ (int) ($course->students_count ?? 0) }}</div>
                                </div>
                                @endif
                                <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-4 py-3">
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'المدة' : 'Duration' }}</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $durationWeeks ? ($durationWeeks . ($isArabic ? ' أسبوع' : ' weeks')) : '-' }}</div>
                                </div>
                            </div>

                            @if($isEnrolled ?? false)
                                <div class="pt-2">
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-2">
                                        <span>{{ $isArabic ? 'نسبة التقدم' : 'Progress' }}</span>
                                        <span class="text-primary-600 dark:text-primary-400">{{ round($progress ?? 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full" style="width: {{ round($progress ?? 0) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-student.card>

                <x-student.card padding="p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'وصف الكورس' : 'Course description' }}</h2>
                    <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                        @if($course->description)
                            {!! nl2br(e($course->description)) !!}
                        @else
                            <p class="text-slate-500 dark:text-slate-400">{{ $isArabic ? 'لا يوجد وصف متاح حالياً لهذا الكورس.' : 'No description available for this course yet.' }}</p>
                        @endif
                    </div>
                </x-student.card>
            </div>

            <div class="space-y-6">
                <x-student.card padding="p-6 md:p-8">
                    <div class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">{{ $isArabic ? 'السعر' : 'Price' }}</div>
                    @if($course->price == 0)
                        <div class="text-3xl font-black text-emerald-500">{{ $isArabic ? 'مجانا' : 'Free' }}</div>
                    @else
                        <div class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($course->price, 0) }} <span class="text-base font-bold text-slate-500">{{ $currencyLabel }}</span></div>
                    @endif

                    @if($isEnrolled ?? false)
                            <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                <div class="flex justify-between">
                                    <span>{{ $isArabic ? 'العناوين المكتملة' : 'Completed headings' }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $completedHeadingCount }}/{{ $headingCount }}</span>
                                </div>
                            <div class="flex justify-between">
                                <span>{{ $isArabic ? 'تاريخ البدء' : 'Started at' }}</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $enrollment->started_at ? $enrollment->started_at->format('M d, Y') : '-' }}</span>
                            </div>
                            @if(!is_null($remainingDays))
                                <div class="flex justify-between">
                                    <span>{{ $isArabic ? 'متبقي' : 'Remaining' }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $remainingDays }} {{ $isArabic ? 'يوم' : 'days' }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </x-student.card>

                <x-student.card padding="p-6 md:p-8">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'مميزات الكورس' : 'Course highlights' }}</h3>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $headingCount }} {{ $isArabic ? 'عنوان رئيسي' : 'main headings' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'وصول خلال مدة الاشتراك' : 'Access during subscription period' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'شهادة حضور' : 'Attendance certificate' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'يدعم الجوال والكمبيوتر' : 'Mobile and desktop friendly' }}</li>
                    </ul>
                </x-student.card>
            </div>
        </div>
    </div>
</div>
@endsection
