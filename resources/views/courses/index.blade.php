@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $currencyLabel = $isArabic ? 'ر.س' : 'SAR';
@endphp

@section('title', ($isArabic ? 'الكورسات المتاحة' : 'Available Courses') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <section class="glass-card p-6 md:p-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="max-w-3xl">
                    <span class="badge-primary mb-4">{{ $isArabic ? 'تعلم بخطة واضحة' : 'Learn with a clear plan' }}</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold mb-3" style="color: var(--color-text);">
                        {{ $isArabic ? 'كل الكورسات في مكان واحد' : 'All courses in one place' }}
                    </h1>
                    <p class="text-sm sm:text-base" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'اختر الكورس المناسب لمستواك، وراجع التفاصيل قبل الاشتراك. ستجد تدريبًا عمليًا على النطق والكتابة والاختبارات داخل مسار واضح.' : 'Pick the course that fits your level and review details before enrolling. You get practical speaking, writing, and quiz training in a structured path.' }}
                    </p>
                </div>
                <div class="text-sm font-bold text-slate-500 dark:text-slate-300">
                    {{ $isArabic ? 'عدد الكورسات:' : 'Total courses:' }}
                    <span class="text-primary-500">{{ $courses->total() }}</span>
                </div>
            </div>
        </section>

        <section class="glass-card p-6">
            <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-bold mb-2" style="color: var(--color-text);">
                        {{ $isArabic ? 'ابحث عن كورس' : 'Search courses' }}
                    </label>
                    <input
                        id="q"
                        name="q"
                        type="text"
                        value="{{ request('q') }}"
                        placeholder="{{ $isArabic ? 'اسم الكورس أو كلمة مفتاحية' : 'Course title or keyword' }}"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                    >
                </div>
                <div>
                    <label for="sort" class="block text-sm font-bold mb-2" style="color: var(--color-text);">
                        {{ $isArabic ? 'الترتيب' : 'Sort by' }}
                    </label>
                    <select
                        id="sort"
                        name="sort"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">{{ $isArabic ? 'افتراضي' : 'Default' }}</option>
                        <option value="popular" @selected(request('sort') === 'popular')>{{ $isArabic ? 'الأكثر شيوعًا' : 'Most popular' }}</option>
                        <option value="rating" @selected(request('sort') === 'rating')>{{ $isArabic ? 'الأعلى تقييمًا' : 'Top rated' }}</option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>{{ $isArabic ? 'السعر: الأقل أولًا' : 'Price: low to high' }}</option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>{{ $isArabic ? 'السعر: الأعلى أولًا' : 'Price: high to low' }}</option>
                        <option value="newest" @selected(request('sort') === 'newest')>{{ $isArabic ? 'الأحدث' : 'Newest' }}</option>
                    </select>
                </div>

                <div class="md:col-span-3 flex flex-wrap gap-3 pt-1">
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl font-bold">
                        {{ $isArabic ? 'عرض النتائج' : 'Apply' }}
                    </button>
                    @if(request()->query())
                        <a href="{{ route('courses.index') }}" class="btn-secondary px-6 py-2.5 rounded-xl font-bold">
                            {{ $isArabic ? 'إعادة تعيين' : 'Reset' }}
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                @php
                    $detailsUrl = auth()->check() && auth()->user()->is_student
                        ? route('student.courses.show', $course)
                        : route('courses.show', $course);
                @endphp
                <article class="glass-card overflow-hidden group hover:-translate-y-1 transition-all duration-300 border border-slate-200/70 dark:border-white/10">
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-xl font-bold leading-snug" style="color: var(--color-text);">{{ $course->title }}</h2>
                            @if((float) $course->price === 0.0)
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-500/15 text-emerald-500">{{ $isArabic ? 'مجاني' : 'FREE' }}</span>
                            @endif
                        </div>

                        <p class="text-sm leading-6 line-clamp-3" style="color: var(--color-text-muted);">
                            {{ \Illuminate\Support\Str::limit($course->short_description ?: $course->description, 140) }}
                        </p>

                        <div class="grid {{ ($showCourseStudentCount ?? true) ? 'grid-cols-3' : 'grid-cols-2' }} gap-2 text-xs font-bold">
                            <div class="rounded-lg px-3 py-2 bg-slate-100 dark:bg-white/5 text-center" style="color: var(--color-text-muted);">
                                <div>{{ $isArabic ? 'العناوين' : 'Headings' }}</div>
                                <div class="text-slate-900 dark:text-white text-sm">{{ $course->headings_count ?? 0 }}</div>
                            </div>
                            @if($showCourseStudentCount ?? true)
                            <div class="rounded-lg px-3 py-2 bg-slate-100 dark:bg-white/5 text-center" style="color: var(--color-text-muted);">
                                <div>{{ $isArabic ? 'الطلاب' : 'Students' }}</div>
                                <div class="text-slate-900 dark:text-white text-sm">{{ $course->students_count }}</div>
                            </div>
                            @endif
                            <div class="rounded-lg px-3 py-2 bg-slate-100 dark:bg-white/5 text-center" style="color: var(--color-text-muted);">
                                <div>{{ $isArabic ? 'المدة' : 'Duration' }}</div>
                                <div class="text-slate-900 dark:text-white text-sm">{{ $course->estimated_duration_weeks ? ($course->estimated_duration_weeks . ($isArabic ? ' أ' : 'w')) : '-' }}</div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between gap-3">
                            <div class="text-2xl font-black text-slate-900 dark:text-white">
                                @if((float) $course->price === 0.0)
                                    <span class="text-emerald-500">{{ $isArabic ? 'مجاني' : 'Free' }}</span>
                                @else
                                    {{ number_format((float) $course->price, 0) }}
                                    <span class="text-sm text-slate-500">{{ $currencyLabel }}</span>
                                @endif
                            </div>
                            <a href="{{ $detailsUrl }}" class="btn-primary px-4 py-2 rounded-xl text-sm font-bold whitespace-nowrap">
                                {{ $isArabic ? 'تفاصيل الكورس' : 'Course details' }}
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 glass-card p-10 text-center">
                    <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">
                        {{ $isArabic ? 'لا توجد كورسات مطابقة حاليًا' : 'No matching courses found' }}
                    </h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'غيّر كلمات البحث أو أعد الترتيب وحاول مرة أخرى.' : 'Try a different search keyword or sorting option.' }}
                    </p>
                </div>
            @endforelse
        </section>

        @if($courses->hasPages())
            <div class="glass-card p-4 flex justify-center">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
