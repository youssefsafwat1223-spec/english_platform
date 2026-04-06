@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $isStudent = auth()->check() && auth()->user()->is_student;
    $currencyLabel = $isArabic ? 'ر.س' : 'SAR';
@endphp

@section('title', $course->title . ' - ' . config('app.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($course->short_description ?: $course->description), 160))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <section class="glass-card p-6 md:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-5">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                        <a href="{{ route('courses.index') }}" class="hover:text-primary-500 transition-colors">
                            {{ $isArabic ? 'الكورسات' : 'Courses' }}
                        </a>
                        <span>/</span>
                        <span>{{ $course->title }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight" style="color: var(--color-text);">
                        {{ $course->title }}
                    </h1>

                    <p class="text-sm sm:text-base leading-7" style="color: var(--color-text-muted);">
                        {{ $course->short_description ?: $course->description }}
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 bg-white/70 dark:bg-white/5">
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'الدروس' : 'Lessons' }}</div>
                            <div class="text-lg font-black text-slate-900 dark:text-white">{{ $distinctLessonTitlesCount }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 bg-white/70 dark:bg-white/5">
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'الطلاب' : 'Students' }}</div>
                            <div class="text-lg font-black text-slate-900 dark:text-white">{{ $course->students_count }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 bg-white/70 dark:bg-white/5">
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'المدة' : 'Duration' }}</div>
                            <div class="text-lg font-black text-slate-900 dark:text-white">
                                {{ $course->estimated_duration_weeks ? ($course->estimated_duration_weeks . ($isArabic ? ' أسبوع' : ' weeks')) : '-' }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 bg-white/70 dark:bg-white/5">
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $isArabic ? 'السعر' : 'Price' }}</div>
                            <div class="text-lg font-black text-slate-900 dark:text-white">
                                @if((float) $course->price === 0.0)
                                    <span class="text-emerald-500">{{ $isArabic ? 'مجاني' : 'Free' }}</span>
                                @else
                                    {{ number_format((float) $course->price, 0) }} <span class="text-xs font-bold text-slate-500">{{ $currencyLabel }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 border border-slate-200 dark:border-white/10">
                    <h2 class="text-lg font-black mb-4" style="color: var(--color-text);">
                        {{ $isArabic ? 'ابدأ الآن' : 'Start now' }}
                    </h2>

                    <div class="space-y-3">
                        @if($isStudent)
                            <a href="{{ route('student.courses.show', $course) }}" class="btn-primary w-full text-center py-3 rounded-xl font-black">
                                {{ $isArabic ? 'افتح صفحة الاشتراك' : 'Open enrollment page' }}
                            </a>
                            <a href="{{ route('student.courses.enroll', $course) }}" class="btn-secondary w-full text-center py-3 rounded-xl font-bold">
                                {{ $isArabic ? 'الانتقال للدفع' : 'Go to checkout' }}
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary w-full text-center py-3 rounded-xl font-black">
                                {{ $isArabic ? 'أنشئ حساب وابدأ' : 'Create account and start' }}
                            </a>
                            <a href="{{ route('login') }}" class="btn-secondary w-full text-center py-3 rounded-xl font-bold">
                                {{ $isArabic ? 'لديك حساب؟ سجل دخول' : 'Already have an account? Log in' }}
                            </a>
                        @endif
                    </div>

                    <p class="text-xs mt-4 leading-6" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'بعد التسجيل تقدر تدخل صفحة الطالب وتكمل الكورس مع الاختبارات وتطبيقات النطق والكتابة.' : 'After signup, you can continue in the student area with quizzes, speaking, and writing practice.' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass-card p-6 md:p-8">
                <h3 class="text-xl font-black mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'ما الذي ستحصل عليه؟' : 'What is included?' }}
                </h3>

                <ul class="space-y-3 text-sm leading-6" style="color: var(--color-text-muted);">
                    <li class="flex items-start gap-2">
                        <span class="w-2 h-2 mt-2 rounded-full bg-primary-500"></span>
                        <span>{{ $isArabic ? 'مسار دراسي منظم من البداية حتى الإتقان.' : 'A structured learning path from foundation to confidence.' }}</span>
                    </li>
                    @if($hasPronunciationFeature)
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $isArabic ? 'تطبيق نطق عملي داخل الدروس.' : 'In-lesson speaking and pronunciation practice.' }}</span>
                        </li>
                    @endif
                    @if($hasWritingFeature)
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 mt-2 rounded-full bg-sky-500"></span>
                            <span>{{ $isArabic ? 'تدريبات كتابة مع تقييم ذكي ونصائح للتحسين.' : 'Writing exercises with AI-assisted feedback and improvement tips.' }}</span>
                        </li>
                    @endif
                    @if($hasQuizFeature)
                        <li class="flex items-start gap-2">
                            <span class="w-2 h-2 mt-2 rounded-full bg-amber-500"></span>
                            <span>{{ $isArabic ? 'اختبارات قصيرة بعد الدروس لقياس التقدم.' : 'Short lesson quizzes to measure progress.' }}</span>
                        </li>
                    @endif
                    <li class="flex items-start gap-2">
                        <span class="w-2 h-2 mt-2 rounded-full bg-indigo-500"></span>
                        <span>{{ $isArabic ? 'شهادة حضور.' : 'Attendance certificate.' }}</span>
                    </li>
                </ul>
            </div>

            <div class="glass-card p-6 md:p-8">
                <h3 class="text-xl font-black mb-4" style="color: var(--color-text);">
                    {{ $isArabic ? 'معاينة الدروس' : 'Lesson preview' }}
                </h3>

                <div class="space-y-2">
                    @forelse($previewLessons as $lesson)
                        <div class="rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 bg-white/60 dark:bg-white/5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">
                                        {{ $loop->iteration }}. {{ $lesson->title }}
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-1.5 text-xs">
                                        @if($lesson->has_quiz)
                                            <span class="px-2 py-0.5 rounded-full bg-primary-500/15 text-primary-500 font-bold">{{ $isArabic ? 'اختبار' : 'Quiz' }}</span>
                                        @endif
                                        @if($lesson->has_writing_exercise)
                                            <span class="px-2 py-0.5 rounded-full bg-sky-500/15 text-sky-500 font-bold">{{ $isArabic ? 'كتابة' : 'Writing' }}</span>
                                        @endif
                                        @if($lesson->has_pronunciation_exercise)
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-500 font-bold">{{ $isArabic ? 'نطق' : 'Speaking' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm" style="color: var(--color-text-muted);">
                            {{ $isArabic ? 'لا توجد دروس متاحة للمعاينة الآن.' : 'No lessons available for preview right now.' }}
                        </p>
                    @endforelse
                </div>

                @if($distinctLessonTitlesCount > $previewLessons->count())
                    <p class="text-xs mt-4" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'هذه معاينة فقط. بقية الدروس تظهر بعد الاشتراك.' : 'This is a preview only. Full lesson list appears after enrollment.' }}
                    </p>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
