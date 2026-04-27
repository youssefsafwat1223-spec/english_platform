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

    $prerequisite = $course->prerequisite ?? null;
    $isPrerequisiteMet = true;
    if ($prerequisite && auth()->check()) {
        $prereqEnrollment = auth()->user()->enrollments()->where('course_id', $prerequisite->id)->first();
        // Accept the prerequisite as met if the student has ever enrolled — even if they didn't finish.
        if (!$prereqEnrollment) {
            $isPrerequisiteMet = false;
        }
    }

    // Free levels (lessons accessible without enrollment)
    $freeLevels = $course->levels()
        ->where('is_free', true)
        ->where('is_active', true)
        ->orderBy('order_index')
        ->with(['lessons' => fn ($q) => $q->orderBy('order_index')])
        ->get();
    $hasFreeContent = $freeLevels->isNotEmpty();
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
                @if(!$isPrerequisiteMet)
                    <button disabled class="btn-secondary px-6 py-3 rounded-xl border border-red-500/30 text-red-500 opacity-80 cursor-not-allowed font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        {{ $isArabic ? 'مطلوب إتمام (' . $prerequisite->title . ') أولاً' : 'Must complete (' . $prerequisite->title . ') first' }}
                    </button>
                @elseif($isEnrolled ?? false)
                    <a href="{{ route('student.courses.learn', $course) }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold flex items-center gap-2">
                        {{ $isArabic ? 'متابعة التعلم' : 'Continue learning' }}
                        <svg class="w-4 h-4 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @elseif($course->is_installment)
                    {{-- Installment course: scroll to payment options in sidebar --}}
                    <a href="#payment-options" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold">
                        {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                    </a>
                @else
                    <a href="{{ route('student.courses.enroll', $course) }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold">
                        {{ $isArabic ? ($course->is_exam ? 'ابدأ الاختبار' : 'اشترك الآن') : ($course->is_exam ? 'Start Exam' : 'Enroll now') }}
                    </a>
                @endif
            </x-slot>
        </x-student.page-header>

        {{-- Free preview content (visible to non-enrolled students) --}}
        @if($hasFreeContent && !($isEnrolled ?? false))
            {{-- Top CTA: "Like the content? Subscribe!" --}}
            <div class="rounded-2xl border-2 border-emerald-300 dark:border-emerald-500/30 bg-gradient-to-r from-emerald-50 via-emerald-100/50 to-sky-50 dark:from-emerald-500/10 dark:via-emerald-500/5 dark:to-sky-500/10 p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🎁</span>
                    <div>
                        <h3 class="font-extrabold text-base sm:text-lg text-slate-900 dark:text-white">
                            {{ $isArabic ? 'جرّب الكورس مجاناً!' : 'Try the course for free!' }}
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 font-medium">
                            {{ $isArabic ? 'لو عجبك المحتوى، اشترك من هنا واحصل على الكورس كامل.' : 'If you like the content, subscribe and get full course access.' }}
                        </p>
                    </div>
                </div>
                @if($isPrerequisiteMet)
                    @if($course->is_installment)
                        <a href="#payment-options" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold whitespace-nowrap">
                            {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                        </a>
                    @else
                        <a href="{{ route('student.courses.enroll', $course) }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 font-bold whitespace-nowrap">
                            {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                        </a>
                    @endif
                @endif
            </div>

            {{-- Free levels with their lessons --}}
            <x-student.card padding="p-0" class="overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200/60 dark:border-white/10 bg-emerald-50/50 dark:bg-emerald-500/5 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">🆓</span>
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white">
                            {{ $isArabic ? 'محتوى مجاني للتجربة' : 'Free preview content' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isArabic ? 'ادخل أي درس من اللي تحت وجرّبه. ' : 'Click any lesson below to try it. ' }}
                            {{ $isArabic ? count($freeLevels) . ' عنوان مجاني متاح.' : count($freeLevels) . ' free section(s) available.' }}
                        </p>
                    </div>
                </div>

                <div class="divide-y divide-slate-200/60 dark:divide-white/10">
                    @foreach($freeLevels as $freeLevel)
                        <div class="p-5 sm:p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-black shrink-0">
                                    {{ $loop->iteration }}
                                </span>
                                <h3 class="font-extrabold text-base sm:text-lg text-slate-900 dark:text-white line-clamp-2">
                                    {{ $freeLevel->title }}
                                </h3>
                            </div>

                            @if($freeLevel->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                                    {{ $freeLevel->description }}
                                </p>
                            @endif

                            <div class="space-y-2">
                                @forelse($freeLevel->lessons as $freeLesson)
                                    <a href="{{ route('student.lessons.show', [$course, $freeLesson]) }}"
                                       class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/40 hover:border-emerald-400 dark:hover:border-emerald-500/40 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5 transition-all group">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-black shrink-0">
                                                ▶
                                            </span>
                                            <div class="min-w-0">
                                                <div class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                                    {{ $freeLesson->title }}
                                                </div>
                                                @if($freeLesson->video_duration)
                                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                        {{ $freeLesson->formatted_duration }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                            {{ $isArabic ? 'افتح الدرس' : 'Open lesson' }}
                                            <svg class="inline w-3 h-3 ml-1 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-500 dark:text-slate-400 italic">
                                        {{ $isArabic ? 'لا توجد دروس في هذا العنوان حالياً.' : 'No lessons in this section yet.' }}
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bottom CTA --}}
                <div class="px-6 py-5 border-t border-slate-200/60 dark:border-white/10 bg-slate-50/60 dark:bg-slate-900/40 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm text-slate-600 dark:text-slate-300 font-bold text-center sm:text-start">
                        {{ $isArabic ? 'عاجبك المحتوى؟ افتح باقي الكورس بالاشتراك.' : 'Liked it? Unlock the rest by enrolling.' }}
                    </p>
                    @if($isPrerequisiteMet)
                        @if($course->is_installment)
                            <a href="#payment-options" class="btn-primary ripple-btn px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap">
                                {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                            </a>
                        @else
                            <a href="{{ route('student.courses.enroll', $course) }}" class="btn-primary ripple-btn px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap">
                                {{ $isArabic ? 'اشترك الآن' : 'Enroll now' }}
                            </a>
                        @endif
                    @endif
                </div>
            </x-student.card>
        @endif

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
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">{{ $course->is_exam ? ($isArabic ? 'وصف الاختبار' : 'Exam description') : ($isArabic ? 'وصف الكورس' : 'Course description') }}</h2>
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
                <div id="payment-options" class="scroll-mt-24">
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

                            {{-- Installment plan status --}}
                            @if(isset($enrollment) && $enrollment->installmentPlan)
                                @php $plan = $enrollment->installmentPlan; @endphp
                                <div class="mt-3 pt-3 border-t border-slate-200 dark:border-white/10">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold">الأقساط</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                            {{ $plan->is_completed ? 'bg-emerald-100 text-emerald-700' : ($plan->is_suspended ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                            {{ $plan->is_completed ? 'مكتمل' : ($plan->is_suspended ? 'موقوف' : 'جاري') }}
                                        </span>
                                    </div>
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= $plan->installments_count; $i++)
                                            <div class="flex-1 h-2 rounded-full {{ $i <= $plan->installments_paid ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-white/10' }}"></div>
                                        @endfor
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $plan->installments_paid }} من {{ $plan->installments_count }} أقساط مدفوعة</div>
                                    @if(!$plan->is_completed && $plan->next_due_at)
                                        <div class="text-xs text-slate-500 mt-1">موعد القسط القادم: {{ $plan->next_due_at->format('Y/m/d') }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Suspended access warning --}}
                        @if(isset($enrollment) && $enrollment->is_suspended)
                            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-300">
                                🔒 وصولك موقوف بسبب تأخر سداد قسط. تواصل معنا لإعادة التفعيل.
                            </div>
                        @endif
                    @elseif($course->is_installment)
                        {{-- Installment purchase option --}}
                        <div class="mt-4 space-y-3">
                            <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">خيارات الدفع</div>

                            {{-- Full payment --}}
                            <a href="{{ route('student.courses.enroll', $course) }}"
                               class="flex items-center justify-between w-full p-3 rounded-xl border-2 border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 hover:bg-primary-100 transition">
                                <div>
                                    <div class="font-bold text-sm">دفع كامل</div>
                                    <div class="text-xs opacity-75">دفعة واحدة</div>
                                </div>
                                <div class="font-black text-lg">{{ number_format($course->price, 0) }} ريال</div>
                            </a>

                            {{-- Installment payment --}}
                            <form action="{{ route('student.courses.installment', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="flex items-center justify-between w-full p-3 rounded-xl border-2 border-amber-400 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition">
                                    <div>
                                        <div class="font-bold text-sm">دفع بالتقسيط</div>
                                        <div class="text-xs opacity-75">3 أقساط شهرية</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-black text-lg">{{ number_format($course->installment_amount, 0) }} ريال</div>
                                        <div class="text-xs opacity-75">/ قسط</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    @endif
                </x-student.card>
                </div>{{-- #payment-options --}}

                <x-student.card padding="p-6 md:p-8">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ $course->is_exam ? ($isArabic ? 'تفاصيل الاختبار' : 'Exam highlights') : ($isArabic ? 'مميزات الكورس' : 'Course highlights') }}</h3>
                    @if($course->is_exam)
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'تقييم شامل ومباشر لمهاراتك' : 'Comprehensive skills assessment' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'منهج تأسيسي وتدريب ذكي' : 'Structured curriculum & AI training' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'يحدد مستواك ومسارك التعليمي' : 'Determines your level & learning path' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'يدعم الجوال والكمبيوتر' : 'Mobile and desktop friendly' }}</li>
                    </ul>
                    @else
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $headingCount }} {{ $isArabic ? 'عنوان رئيسي' : 'main headings' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'وصول خلال مدة الاشتراك' : 'Access during subscription period' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'شهادة حضور' : 'Attendance certificate' }}</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $isArabic ? 'يدعم الجوال والكمبيوتر' : 'Mobile and desktop friendly' }}</li>
                    </ul>
                    @endif
                </x-student.card>
            </div>
        </div>
    </div>
</div>
@endsection
