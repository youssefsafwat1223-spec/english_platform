@extends('layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'شارك رأيك' : 'Share Your Feedback') . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

<div class="py-8 lg:py-12 relative min-h-screen z-10">
    <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b from-accent-500/10 via-primary-500/5 to-transparent pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-500 transition-colors">
                <svg class="w-4 h-4 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ $isArabic ? 'رجوع' : 'Back' }}
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-8 items-start">
            <x-student.card padding="p-0" class="overflow-hidden" data-aos="fade-up">
                <div class="p-6 md:p-8 border-b border-slate-200/60 dark:border-white/10 bg-slate-50/60 dark:bg-slate-900/20">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent-500/10 border border-accent-500/20 text-accent-600 dark:text-accent-400 text-sm font-black mb-5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.148 3.531a1 1 0 00.95.69h3.712c.969 0 1.371 1.24.588 1.81l-3.003 2.182a1 1 0 00-.364 1.118l1.147 3.531c.3.922-.755 1.688-1.539 1.118l-3.004-2.182a1 1 0 00-1.175 0l-3.004 2.182c-.784.57-1.838-.196-1.539-1.118l1.148-3.531a1 1 0 00-.364-1.118L2.65 8.958c-.783-.57-.38-1.81.588-1.81h3.712a1 1 0 00.95-.69l1.149-3.531z"></path>
                        </svg>
                        {{ $isArabic ? 'رأي حقيقي من طالب حقيقي' : 'Real feedback from a real student' }}
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight mb-3">
                        {{ $testimonial ? ($isArabic ? 'حدّث رأيك عن المنصة' : 'Update your feedback about the platform') : ($isArabic ? 'اكتب رأيك عن المنصة' : 'Write your feedback about the platform') }}
                    </h1>
                    <p class="text-slate-600 dark:text-slate-300 font-medium leading-relaxed max-w-2xl">
                        {{ $isArabic ? 'شارك تجربتك بصدق. رأيك يساعد طلابًا آخرين، وبعد مراجعة الإدارة يمكن عرضه في الصفحة الرئيسية.' : 'Share your experience honestly. Your feedback helps other students, and after admin review it may appear on the homepage.' }}
                    </p>
                </div>

                <form action="{{ route('student.testimonial.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-black text-slate-800 dark:text-slate-200 mb-2">{{ $isArabic ? 'اسمك' : 'Your name' }}</label>
                            <div class="h-12 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/30 px-4 flex items-center font-semibold text-slate-700 dark:text-slate-300">
                                {{ auth()->user()->name }}
                            </div>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-black text-slate-800 dark:text-slate-200 mb-2">{{ $isArabic ? 'الصفة' : 'Role' }}</label>
                            <input
                                id="role"
                                name="role"
                                type="text"
                                value="{{ old('role', $testimonial->role ?? '') }}"
                                placeholder="{{ $isArabic ? 'مثال: طالب جامعي أو خريج' : 'Example: university student or graduate' }}"
                                class="w-full h-12 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/30 px-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            >
                            @error('role')
                                <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="rating" class="block text-sm font-black text-slate-800 dark:text-slate-200 mb-2">{{ $isArabic ? 'التقييم' : 'Rating' }}</label>
                        <select
                            id="rating"
                            name="rating"
                            class="w-full h-12 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/30 px-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            required
                        >
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5) == $i)>{{ $i }} {{ $isArabic ? 'نجوم' : 'stars' }} {{ str_repeat('★', $i) }}</option>
                            @endfor
                        </select>
                        @error('rating')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-black text-slate-800 dark:text-slate-200 mb-2">{{ $isArabic ? 'رأيك' : 'Your feedback' }}</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="7"
                            required
                            maxlength="1000"
                            placeholder="{{ $isArabic ? 'احكِ باختصار: ما الذي أعجبك وما الذي استفدت منه؟' : 'Briefly tell us: what did you like and what did you benefit from?' }}"
                            class="w-full rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/30 px-4 py-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-y leading-relaxed"
                        >{{ old('content', $testimonial->content ?? '') }}</textarea>
                        <div class="mt-2 flex items-center justify-between gap-3 text-xs font-bold text-slate-500 dark:text-slate-400">
                            <span>{{ $isArabic ? 'يُفضّل أن يكون الرأي واضحًا وصادقًا ومفيدًا للطلاب الجدد.' : 'Keep it clear, honest, and useful for new students.' }}</span>
                            <span>{{ mb_strlen(old('content', $testimonial->content ?? '')) }}/1000</span>
                        </div>
                        @error('content')
                            <p class="mt-2 text-sm font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-2">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            {{ $isArabic ? 'عند إرسال التعديل، سيعود الرأي للمراجعة قبل ظهوره للعامة.' : 'After you submit changes, your feedback returns to review before it becomes public again.' }}
                        </p>

                        <button type="submit" class="btn-primary ripple-btn px-8 py-3.5 rounded-xl shadow-lg shadow-primary-500/25 w-full sm:w-auto justify-center font-black">
                            {{ $testimonial ? ($isArabic ? 'تحديث رأيي' : 'Update my feedback') : ($isArabic ? 'إرسال رأيي' : 'Submit my feedback') }}
                        </button>
                    </div>
                </form>
            </x-student.card>

            <div class="space-y-6">
                <x-student.card padding="p-6 md:p-7" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'حالة رأيك' : 'Your feedback status' }}</h2>

                    @if($testimonial)
                        @if($testimonial->is_active)
                            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4">
                                <div class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-black mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $isArabic ? 'منشور الآن' : 'Published now' }}
                                </div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $isArabic ? 'رأيك ظاهر حاليًا في الصفحة الرئيسية. أي تعديل جديد سيعود للمراجعة مرة أخرى.' : 'Your feedback is currently visible on the homepage. Any new edit will return to review again.' }}
                                </p>
                            </div>
                        @else
                            <div class="rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4">
                                <div class="inline-flex items-center gap-2 text-amber-600 dark:text-amber-400 font-black mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $isArabic ? 'قيد المراجعة' : 'Under review' }}
                                </div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $isArabic ? 'تم استلام رأيك وهو الآن بانتظار مراجعة الإدارة قبل النشر.' : 'Your feedback has been received and is awaiting admin review before publishing.' }}
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/30 p-4">
                            <div class="inline-flex items-center gap-2 text-slate-700 dark:text-slate-300 font-black mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ $isArabic ? 'لم ترسل رأيًا بعد' : 'No feedback submitted yet' }}
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                {{ $isArabic ? 'أول رأي منك يمكن أن يساعد طالبًا آخر على اتخاذ قرار صحيح.' : 'Your first feedback can help another student make a better decision.' }}
                            </p>
                        </div>
                    @endif
                </x-student.card>

                <x-student.card padding="p-6 md:p-7" data-aos="fade-up" data-aos-delay="180">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'نصائح لرأي قوي' : 'Tips for strong feedback' }}</h2>
                    <div class="space-y-3 text-sm font-medium text-slate-600 dark:text-slate-300">
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0 font-black">1</span>
                            <p>{{ $isArabic ? 'اذكر نتيجة أو فائدة حقيقية حصلت لك من المنصة أو الكورس.' : 'Mention a real benefit or result you gained from the platform or the course.' }}</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0 font-black">2</span>
                            <p>{{ $isArabic ? 'اكتب بأسلوب طبيعي وواضح، بدون مبالغة أو تكرار كثير.' : 'Write naturally and clearly, without exaggeration or repetition.' }}</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0 font-black">3</span>
                            <p>{{ $isArabic ? 'لو عدّلت رأيك لاحقًا، سيتم مراجعته مرة أخرى قبل النشر.' : 'If you edit your feedback later, it will be reviewed again before publishing.' }}</p>
                        </div>
                    </div>
                </x-student.card>
            </div>
        </div>
    </div>
</div>
@endsection
