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
<div class="relative min-h-screen bg-slate-50 dark:bg-[#020617] pb-24 lg:pb-0 font-sans">
    
    {{-- Decorative Background Top --}}
    <div class="absolute top-0 left-0 w-full h-[50vh] bg-gradient-to-b from-primary-50/50 to-slate-50 dark:from-primary-900/10 dark:to-[#020617] pointer-events-none z-0"></div>
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-500/20 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-8 lg:pt-16 pb-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- ─── LEFT COLUMN: HERO & CURRICULUM ─── --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-10 lg:space-y-12">
                
                {{-- Breadcrumb & Top Navigation --}}
                <div data-aos="fade-up">
                    <nav class="flex mb-6 lg:mb-8" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 space-x-reverse md:space-x-3 text-sm font-bold text-slate-500 dark:text-slate-400">
                            <li class="inline-flex items-center">
                                <a href="{{ route('student.courses.my-courses') }}" class="inline-flex items-center bg-white dark:bg-slate-800/50 px-3.5 py-1.5 rounded-full border border-slate-200 dark:border-white/5 hover:text-primary-600 hover:border-primary-200 dark:hover:text-primary-400 dark:hover:border-primary-500/30 transition-all shadow-sm">
                                    <svg class="w-4 h-4 rtl:mr-0 rtl:ml-1.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    {{ __('رجوع للكورسات') }}
                                </a>
                            </li>
                        </ol>
                    </nav>

                    {{-- Hero Content --}}
                    <div class="flex flex-wrap items-center gap-3 mb-5">
                        <span class="px-3.5 py-1.5 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-xs font-black uppercase tracking-wider border border-primary-200 dark:border-primary-800/50 shadow-sm">
                            {{ $course->level ?? __('مبتدئ') }}
                        </span>
                        @if($course->average_rating)
                            <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-black border border-amber-200 dark:border-amber-800/50 shadow-sm">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 1.75l2.55 5.16 5.7.83-4.12 4.02.97 5.68L10 14.76l-5.1 2.68.97-5.68-4.12-4.02 5.7-.83L10 1.75Z"/></svg>
                                {{ number_format($course->average_rating, 1) }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4 lg:mb-6 leading-tight tracking-tight">
                        {{ $course->title }}
                    </h1>
                    
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl mb-8 font-medium">
                        {{ $course->short_description ?: $course->description }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-sm font-medium text-slate-700 dark:text-slate-300">
                        <div class="flex items-center gap-3 flex-1 sm:flex-none bg-white dark:bg-slate-800/50 min-w-[140px] px-5 py-4 rounded-[1.25rem] border border-slate-200 dark:border-white/5 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 border border-indigo-100 dark:border-indigo-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.25C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v11C4.17 16.48 5.75 16 7.5 16s3.33.48 4.5 1.25m0-11C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v11C19.83 16.48 18.25 16 16.5 16s-3.33.48-4.5 1.25"/></svg>
                            </div>
                            <div>
                                <div class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-wider mb-1">{{ __('المحتوى') }}</div>
                                <div class="font-black text-slate-900 dark:text-white">{{ $course->levels()->active()->count() }} {{ __('عنوان') }}</div>
                                <div class="text-[11px] font-bold text-slate-500 mt-0.5">{{ $course->lessons->count() }} {{ __('درس فيديو') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-1 sm:flex-none bg-white dark:bg-slate-800/50 min-w-[140px] px-5 py-4 rounded-[1.25rem] border border-slate-200 dark:border-white/5 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center text-teal-500 border border-teal-100 dark:border-teal-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-6-9h6m3 6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </div>
                            <div>
                                <div class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-wider mb-1">{{ __('مدة الدراسة') }}</div>
                                <div class="font-black text-slate-900 dark:text-white">{{ $course->estimated_duration_weeks ? $course->estimated_duration_weeks . ' ' . __('أسابيع تقريبًا') : __('حسب سرعتك') }}</div>
                                <div class="text-[11px] font-bold text-slate-500 mt-0.5">{{ __('مدة مقترحة فقط - الوصول مدى الحياة') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Inline Image Placeholder OR Dashboard for Enrolled --}}
                @if(isset($isEnrolled) && $isEnrolled && isset($enrollment))
                <div class="lg:hidden w-full mb-8 relative z-20" data-aos="fade-up">
                    <x-student.card rounded="rounded-[2.5rem]" padding="p-8" mb="mb-0">
                        {{-- Progress Ring --}}
                        <div class="text-center mb-8">
                            <div class="relative w-32 h-32 mx-auto mb-4">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="64" cy="64" r="56" stroke-width="8" fill="transparent" class="stroke-slate-800"/>
                                    <circle cx="64" cy="64" r="56" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - ($progress ?? 0) / 100) }}"
                                        class="text-primary-500" stroke="currentColor" stroke-linecap="round"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-3xl font-black text-white">{{ round($progress ?? 0) }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-slate-400">{{ __('تقدمك في الكورس') }}</p>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-3 text-sm mb-8">
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('الدروس') }}</span>
                                <span class="font-bold text-white">{{ $enrollment->completed_lessons ?? 0 }}/{{ $enrollment->total_lessons ?? $course->lessons->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('تاريخ البدء') }}</span>
                                <span class="font-bold text-white">{{ isset($enrollment->started_at) ? $enrollment->started_at->format('M d, Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('آخر نشاط') }}</span>
                                <span class="font-bold border-b border-dashed border-slate-600 text-white">{{ isset($enrollment->last_accessed_at) ? $enrollment->last_accessed_at->diffForHumans() : __('لم تبدأ') }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.courses.learn', $course) }}" class="flex items-center justify-center w-full py-4 rounded-[1.25rem] bg-primary-600 text-white text-lg font-black shadow-lg shadow-primary-500/30 gap-2 active:scale-95 transition-transform border border-primary-500">
                            {{ __('متابعة التعلم') }}
                            <svg class="w-5 h-5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </x-student.card>
                </div>
                @else
                <div class="lg:hidden w-full aspect-video rounded-3xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-lg relative group mb-8">
                     @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-100 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center">
                            <span class="text-5xl font-black text-slate-300 dark:text-white/10">PREVIEW</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-slate-900/30 flex items-center justify-center pointer-events-none">
                        <div class="w-16 h-16 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-primary-600 shadow-2xl">
                            <svg class="w-7 h-7 ml-1" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Course Description Section --}}
                <div data-aos="fade-up" data-aos-delay="100" class="pt-6">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-6 lg:mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-[0.8rem] bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-white/5 flex items-center justify-center text-primary-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6l4 4v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6M9 13h6M9 17h4"/></svg>
                        </span>
                        {{ __('وصف الكورس') }}
                    </h3>
                    
                    <x-student.card padding="p-6 lg:p-8" mb="mb-0">
                        <div class="prose dark:prose-invert max-w-none prose-slate prose-img:rounded-xl prose-headings:font-black prose-a:text-primary-600 dark:prose-a:text-primary-400">
                            @if($course->description)
                                {!! nl2br(e($course->description)) !!}
                            @else
                                <p class="text-slate-500 dark:text-slate-400 italic text-center py-8">{{ __('لا يوجد وصف متاح حالياً لهذا الكورس.') }}</p>
                            @endif
                        </div>
                    </x-student.card>
                </div>
            </div>
            
            {{-- ─── RIGHT COLUMN: STICKY ENROLLMENT CARD (DESKTOP) ─── --}}
            <div class="hidden lg:block lg:col-span-5 xl:col-span-4 self-start sticky top-24 pt-8" data-aos="fade-up" data-aos-delay="200">
                <x-student.card rounded="rounded-[2.5rem]" padding="p-8" class="shadow-2xl shadow-slate-200/50 dark:shadow-none relative z-20" mb="mb-0">
                    
                    {{-- Card Gradient Decoration --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary-500/10 dark:bg-primary-500/5 blur-[60px] rounded-full pointer-events-none z-0"></div>

                    <div class="relative z-10">
                        {{-- Thumbnail Preview inside card --}}
                        <div class="w-full aspect-[4/3] rounded-[1.5rem] overflow-hidden mb-8 relative bg-slate-100 dark:bg-[#020617] shadow-inner group border border-slate-100 dark:border-white/5 cursor-pointer">
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="Course Preview" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-slate-900/10 dark:bg-black/20 flex items-center justify-center transition-colors group-hover:bg-slate-900/30 dark:group-hover:bg-black/40">
                                <div class="w-16 h-16 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-md flex items-center justify-center text-primary-600 dark:text-primary-400 shadow-xl transform scale-100 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 ml-1" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Price Block --}}
                        <div class="mb-8 text-center bg-slate-50 dark:bg-white/[0.02] p-6 rounded-[1.5rem] border border-slate-100 dark:border-white/5 shadow-sm">
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('استثمارك') }}</div>
                            @if($course->price == 0)
                                <div class="text-4xl sm:text-5xl font-black text-emerald-500 drop-shadow-sm">{{ __('مجانًا') }}</div>
                            @else
                                <div class="flex items-end justify-center gap-1.5 mb-1.5">
                                    <span class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white leading-none tracking-tight drop-shadow-sm">{{ number_format($course->price, 0) }}</span>
                                    <span class="text-xl font-bold text-slate-500 dark:text-slate-400 mb-1">{{ __('ر.س') }}</span>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-2">
                                    <span class="text-sm line-through text-slate-400 dark:text-slate-500 font-bold decoration-2">{{ number_format($course->price * 1.5, 0) }} {{ __('ر.س') }}</span>
                                    <span class="px-2 py-0.5 rounded border border-amber-200 bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider">{{ __('وفر 33%') }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- CTA Button --}}
                        @if($isEnrolled ?? false)
                            <div class="mb-8">
                                <div class="flex justify-between items-center text-xs font-bold text-slate-500 mb-2.5 px-1">
                                    <span>{{ __('نسبة الإنجاز') }}</span>
                                    <span class="text-primary-600 dark:text-primary-400 font-black">{{ $progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 mb-6 overflow-hidden shadow-inner border border-slate-200 dark:border-white/5">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress ?? 0 }}%"></div>
                                </div>
                                <a href="{{ route('student.courses.learn', $course) }}" class="flex items-center justify-center w-full py-4 rounded-[1.25rem] bg-slate-900 border border-slate-800 dark:bg-white text-white dark:text-slate-900 text-lg font-black hover:bg-slate-800 dark:hover:bg-slate-100 hover:scale-[1.02] transition-all shadow-[0_10px_30px_rgba(15,23,42,0.2)] dark:shadow-[0_10px_30px_rgba(255,255,255,0.15)] gap-2">
                                    <svg class="w-5 h-5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    {{ __('متابعة التعلم') }}
                                </a>
                            </div>
                        @else
                            <div class="mb-8">
                                <a href="{{ route('student.courses.enroll', $course) }}" class="flex items-center justify-center w-full py-4 rounded-[1.25rem] bg-primary-600 hover:bg-primary-500 text-white text-lg font-black transition-all shadow-[0_10px_30px_rgba(2,132,199,0.3)] hover:shadow-[0_15px_40px_rgba(2,132,199,0.4)] gap-2 hover:scale-[1.02] active:scale-95 border border-primary-500">
                                    {{ __('أشترك وأبدأ التعلم الآن') }}
                                </a>
                                <p class="text-xs text-center mt-4 font-bold text-slate-400 dark:text-slate-500 flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ __('ضمان استرداد ذهبي 30 يوم') }}
                                </p>
                            </div>
                        @endif

                        {{-- Features List --}}
                        <div class="pt-6 border-t border-slate-100 dark:border-white/5">
                            <h4 class="font-black mb-5 text-slate-800 dark:text-white text-sm bg-slate-50 dark:bg-white/5 inline-block px-3 py-1 rounded-lg">{{ __('هذا الكورس يتضمن:') }}</h4>
                            <ul class="space-y-4 text-sm font-bold text-slate-600 dark:text-slate-400">
                                <li class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm"><svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>
                                    <span>{{ $course->lessons->count() }} {{ __('درس فيديو عالي الجودة') }}</span>
                                </li>
                                <li class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm"><svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>
                                    <span>{{ __('وصول كامل مدى الحياة') }}</span>
                                </li>
                                <li class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm"><svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>
                                    <span>{{ __('شهادة إتمام معتمدة') }}</span>
                                </li>
                                <li class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm"><svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>
                                    <span>{{ __('دعم عبر الجوال والكمبيوتر') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </x-student.card>
            </div>

        </div>
    </div>
</div>

{{-- ─── MOBILE FIXED BOTTOM CTA BAR ─── --}}
<div class="fixed bottom-0 left-0 w-full bg-white/95 dark:bg-[#0f172a]/95 border-t border-slate-200 dark:border-white/10 p-4 pb-safe lg:hidden z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.06)] dark:shadow-[0_-10px_40px_rgba(0,0,0,0.4)] backdrop-blur-xl">
    <div class="flex items-center justify-between gap-4 max-w-7xl mx-auto">
        <div class="shrink-0 flex flex-col justify-center">
            @if(isset($isEnrolled) && $isEnrolled)
                <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 mb-0.5 uppercase tracking-wider">{{ __('نسبة تقدمك') }}</span>
                <span class="font-black text-lg text-primary-600 dark:text-primary-400 drop-shadow-sm leading-none">{{ $progress ?? 0 }}%</span>
            @else
                <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">{{ __('استثمارك') }}</span>
                @if($course->price == 0)
                    <span class="font-black text-xl text-emerald-500 drop-shadow-sm leading-none">{{ __('مجانًا') }}</span>
                @else
                    <span class="font-black text-xl text-slate-900 dark:text-white leading-none drop-shadow-sm">{{ number_format($course->price, 0) }}<span class="text-[10px] font-bold ml-1 text-slate-500">{{ __('ر.س') }}</span></span>
                @endif
            @endif
        </div>
        
        <div class="flex-1">
            @if(isset($isEnrolled) && $isEnrolled)
                <a href="{{ route('student.courses.learn', $course) }}" class="flex items-center justify-center w-full py-3.5 rounded-xl bg-slate-900 border border-slate-800 dark:bg-white text-white dark:text-slate-900 font-black shadow-lg shadow-black/10 gap-2 text-sm active:scale-95 transition-transform">
                    <svg class="w-4 h-4 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    {{ __('متابعة التعلم') }}
                </a>
            @else
                <a href="{{ route('student.courses.enroll', $course) }}" class="flex items-center justify-center w-full py-3.5 rounded-xl bg-primary-600 text-white font-black shadow-[0_4px_15px_rgba(2,132,199,0.3)] gap-2 text-sm active:scale-95 transition-transform border border-primary-500">
                    {{ __('أشترك وأبدأ التعلم') }}
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
