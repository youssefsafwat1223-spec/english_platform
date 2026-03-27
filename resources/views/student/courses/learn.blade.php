@extends('layouts.app')

@section('title', $course->title . ' — ' . config('app.name'))

@section('content')
@php
    $startedAt = $enrollment->started_at ? $enrollment->started_at->format('M d, Y') : '-';
    $lastAccessed = $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : __('لم تبدأ');
    $progress = $enrollment->progress_percentage ?? 0;
@endphp

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
                                    {{ __('رجوع لغرفتي') }}
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
                                <span>★</span> {{ number_format($course->average_rating, 1) }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4 lg:mb-6 leading-tight tracking-tight">
                        {{ $course->title }}
                    </h1>
                    
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl mb-8 font-medium">
                        {{ __('تابع تعلمك وراقب مستوى تقدمك في الكورس لضمان تحقيق أهدافك.') }}
                    </p>

                    @if($currentLesson)
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <a href="{{ route('student.lessons.show', [$course, $currentLesson]) }}" class="flex items-center justify-center w-full sm:w-auto px-8 py-3.5 rounded-[1.25rem] bg-primary-600 text-white text-base font-black shadow-lg shadow-primary-500/30 gap-2 hover:bg-primary-500 active:scale-95 transition-all border border-primary-500">
                                {{ __('متابعة الدروس') }}
                                <svg class="w-5 h-5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                            <span class="text-sm font-bold text-slate-500 dark:text-slate-400 px-2 line-clamp-1 w-full text-center sm:text-right">
                                {{ __('الدرس الحالي:') }} <span class="text-slate-800 dark:text-slate-200">{{ $currentLesson->title }}</span>
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Mobile Dashboard Block --}}
                <div class="lg:hidden w-full mb-8 relative z-20" data-aos="fade-up">
                    <div class="bg-[#0f172a] rounded-[2.5rem] p-8 border border-slate-800 shadow-2xl relative overflow-hidden">
                        {{-- Progress Ring --}}
                        <div class="text-center mb-8">
                            <div class="relative w-32 h-32 mx-auto mb-4">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="64" cy="64" r="56" stroke-width="8" fill="transparent" class="stroke-slate-800"/>
                                    <circle cx="64" cy="64" r="56" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - ($progress) / 100) }}"
                                        class="text-primary-500" stroke="currentColor" stroke-linecap="round"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-3xl font-black text-white">{{ round($progress) }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-slate-400">{{ __('نسبة إنجازك بطل') }}</p>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-3 text-sm mb-8">
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('الدروس') }}</span>
                                <span class="font-bold text-white">{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('تاريخ البدء') }}</span>
                                <span class="font-bold text-white">{{ $startedAt }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-800/50">
                                <span class="text-slate-400">{{ __('آخر نشاط') }}</span>
                                <span class="font-bold border-b border-dashed border-slate-600 text-white">{{ $lastAccessed }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        @if($enrollment->completed_at || $enrollment->progress_percentage >= 100)
                            <form action="{{ route('student.courses.certificate', $course) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center justify-center w-full py-4 mb-3 rounded-[1.25rem] bg-emerald-600 border border-emerald-500 text-white text-base font-black shadow-lg shadow-emerald-500/20 gap-2 active:scale-95 transition-transform"><span class="ml-1 rtl:ml-0 rtl:mr-1">🎓</span> {{ __('أرسل الشهادة لتليجرام') }}</button>
                            </form>
                        @endif

                        @if($enrollment->certificate)
                            <a href="{{ route('student.certificates.show', $enrollment->certificate) }}" class="flex items-center justify-center w-full py-3.5 rounded-[1.25rem] bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition-colors border border-slate-700">
                                {{ __('عرض الشهادة') }}
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Curriculum Section --}}
                <div data-aos="fade-up" data-aos-delay="100" class="pt-6">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-6 lg:mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-[0.8rem] bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-white/5 flex items-center justify-center text-lg">📑</span>
                        {{ __('المنهج الدراسي') }}
                    </h3>
                    
                    <div class="space-y-4 lg:space-y-5">
                        @forelse($course->levels()->active()->ordered()->with('lessons')->get() as $levelIndex => $level)
                        @php
                            $isLevelUnlocked = true;
                            $completionPercent = $level->getCompletionPercentageFor(auth()->user());
                            $isCompleted = $completionPercent === 100;
                        @endphp
                        <div class="bg-white dark:bg-[#0f172a] border {{ $isLevelUnlocked ? 'border-slate-200 hover:border-primary-200 dark:border-white/5 dark:hover:border-primary-900/50' : 'border-slate-200/60 dark:border-white/5' }} rounded-[1.5rem] lg:rounded-[2rem] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                            


                            {{-- Level Header --}}
                            <div class="w-full p-4 lg:p-6 flex flex-col gap-5 text-right relative z-20 bg-slate-50/50 dark:bg-white/[0.02]">
                                <div class="flex items-center justify-between gap-4">
                                    <h4 class="font-black text-lg lg:text-xl {{ $isLevelUnlocked ? 'text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400' : 'text-slate-500 dark:text-slate-400' }} break-words transition-colors leading-snug flex-1">
                                        {{ $level->title }}
                                    </h4>
                                    <div class="shrink-0 w-12 h-12 lg:w-14 lg:h-14 rounded-[1rem] {{ $isCompleted ? 'bg-emerald-50 text-emerald-500 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : ($isLevelUnlocked ? 'bg-primary-50 text-primary-600 border border-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:border-white/5') }} flex items-center justify-center font-black text-xl transition-all duration-300 shadow-sm">
                                        @if($isCompleted)
                                            <svg class="w-6 h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            {{ $levelIndex + 1 }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-2 text-xs lg:text-sm font-black">
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl border {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : ($isLevelUnlocked ? 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20' : 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800/50 dark:text-slate-400 dark:border-white/5') }}">
                                        {{ $isCompleted ? __('مكتمل') : ($isLevelUnlocked ? __('متاح الآن') : __('مغلق')) }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 dark:bg-slate-900/40 dark:border-white/5 dark:text-slate-300">
                                        {{ __('الدروس') }}: {{ $level->lessons->count() }}
                                    </span>
                                    @if($completionPercent > 0 && !$isCompleted)
                                        <span class="inline-flex items-center px-3 py-2 rounded-xl border border-amber-100 bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400">
                                            {{ __('التقدم') }}: {{ round($completionPercent) }}%
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Progress Bar (if started but not completed) --}}
                            @if($isLevelUnlocked && $completionPercent > 0 && !$isCompleted)
                                <div class="w-full bg-slate-100 dark:bg-slate-800/50 h-1.5 overflow-hidden relative z-20 mt-1">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $completionPercent }}%"></div>
                                </div>
                            @endif

                            {{-- Level Lessons List --}}
                            <div class="border-t border-slate-100 dark:border-white/5 relative z-20 bg-slate-50/30 dark:bg-slate-900/20">
                                <div class="divide-y divide-slate-100 dark:divide-white/5 p-2 lg:p-4">
                                    @foreach($level->lessons as $lessonIndex => $lesson)
                                    @php
                                        $lessonProgress = collect($enrollment->lessonProgress)->firstWhere('lesson_id', $lesson->id);
                                        $isLessonCompleted = $lessonProgress && $lessonProgress->is_completed;
                                        $isAccessible = true;
                                        $isCurrent = $currentLesson && $currentLesson->id === $lesson->id;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-3 lg:p-4 rounded-xl lg:rounded-2xl {{ $isAccessible ? 'hover:bg-white dark:hover:bg-[#0f172a] hover:shadow-sm cursor-pointer border border-transparent hover:border-slate-200 dark:hover:border-white/5' : 'opacity-60' }} {{ $isCurrent ? 'ring-2 ring-primary-500/50 bg-white dark:bg-[#0f172a]' : '' }} transition-all"
                                         @if($isAccessible) onclick="window.location='{{ route('student.lessons.show', [$course, $lesson]) }}'" @endif>
                                        
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="shrink-0 w-10 h-10 rounded-full {{ $isLessonCompleted ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 border border-emerald-100 dark:border-emerald-500/20 shadow-inner' : ($isAccessible ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-500/20 font-black' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-white/5') }} flex items-center justify-center text-sm shadow-inner transition-colors">
                                                @if($isLessonCompleted)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <h5 class="font-bold text-sm lg:text-base {{ $isAccessible ? 'text-slate-900 dark:text-slate-100 group-hover:text-primary-600 dark:group-hover:text-primary-400' : 'text-slate-500 dark:text-slate-500' }} line-clamp-2 transition-colors">{{ $lesson->title }}</h5>
                                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold mt-1 text-slate-500 dark:text-slate-400">
                                                    @if($lesson->video_duration)
                                                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span> {{ $lesson->formatted_duration }}</span>
                                                    @endif
                                                    @if($lesson->has_quiz)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 rounded bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400 ml-1">🧠 {{ __('اختبار') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($isAccessible)
                                            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                                <span class="flex w-full justify-center lg:justify-start items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 dark:text-slate-300 dark:bg-slate-800 {{ $isLessonCompleted ? 'hover:bg-slate-200 dark:hover:bg-slate-700' : 'hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500' }} transition-colors shadow-sm">
                                                    {{ $isLessonCompleted ? __('مراجعة الدرس') : ($isCurrent ? __('متابعة الدرس') : __('شاهد الدرس')) }}
                                                    <svg class="w-3.5 h-3.5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                </span>
                                            </div>
                                        @else
                                            <div class="hidden sm:flex shrink-0 w-8 h-8 items-center justify-center text-slate-300 dark:text-slate-600">
                                                {{-- locked placeholder --}}
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-12 text-slate-500 bg-white dark:bg-slate-800/20 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-white/10 shadow-sm">
                            <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-4xl mx-auto mb-4 border border-slate-100 dark:border-white/5">📭</div>
                            <div class="font-black text-xl mb-2 text-slate-700 dark:text-slate-300">{{ __('لا توجد دروس حالياً') }}</div>
                            <div class="text-sm font-semibold opacity-70">{{ __('المحاضر بيجهز محتوى عظيم، خليك قريب!') }}</div>
                        </div>
                        @endforelse

                        {{-- Fallback: Lessons NOT assigned to any level --}}
                        @php
                            $orphanLessons = $course->lessons->whereNull('course_level_id');
                        @endphp
                        @if($orphanLessons->count() > 0)
                        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 hover:border-primary-200 dark:border-white/5 dark:hover:border-primary-900/50 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                            {{-- Header --}}
                            <div class="w-full p-4 lg:p-6 flex flex-col gap-5 text-right relative z-20 bg-slate-50/50 dark:bg-white/[0.02]">
                                <div class="flex items-center justify-between gap-4">
                                    <h4 class="font-black text-lg lg:text-xl text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 break-words transition-colors leading-snug flex-1">
                                        {{ __('دروس إضافية') }}
                                    </h4>
                                    <div class="shrink-0 w-12 h-12 lg:w-14 lg:h-14 rounded-[1rem] bg-primary-50 text-primary-600 border border-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20 flex items-center justify-center font-black text-xl transition-all duration-300 shadow-sm">
                                        📚
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-xs lg:text-sm font-black">
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl border border-primary-100 bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:border-primary-500/20 dark:text-primary-400">
                                        {{ __('ظاهرة دائمًا') }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 dark:bg-slate-900/40 dark:border-white/5 dark:text-slate-300">
                                        {{ __('الدروس') }}: {{ $orphanLessons->count() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Orphan Lessons List --}}
                            <div class="border-t border-slate-100 dark:border-white/5 relative z-20 bg-slate-50/30 dark:bg-slate-900/20">
                                <div class="divide-y divide-slate-100 dark:divide-white/5 p-2 lg:p-4">
                                    @foreach($orphanLessons as $lesson)
                                    @php
                                        $lessonProgress = collect($enrollment->lessonProgress)->firstWhere('lesson_id', $lesson->id);
                                        $isLessonCompleted = $lessonProgress && $lessonProgress->is_completed;
                                        $isCurrent = $currentLesson && $currentLesson->id === $lesson->id;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-3 lg:p-4 rounded-xl lg:rounded-2xl hover:bg-white dark:hover:bg-[#0f172a] hover:shadow-sm cursor-pointer border border-transparent hover:border-slate-200 dark:hover:border-white/5 {{ $isCurrent ? 'ring-2 ring-primary-500/50 bg-white dark:bg-[#0f172a]' : '' }} transition-all"
                                         onclick="window.location='{{ route('student.lessons.show', [$course, $lesson]) }}'">
                                        
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="shrink-0 w-10 h-10 rounded-full {{ $isLessonCompleted ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 border border-emerald-100 dark:border-emerald-500/20 shadow-inner' : 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-500/20 font-black' }} flex items-center justify-center text-sm shadow-inner transition-colors">
                                                @if($isLessonCompleted)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <h5 class="font-bold text-sm lg:text-base text-slate-900 dark:text-slate-100 line-clamp-2 transition-colors">{{ $lesson->title }}</h5>
                                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold mt-1 text-slate-500 dark:text-slate-400">
                                                    @if($lesson->video_duration)
                                                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span> {{ $lesson->formatted_duration }}</span>
                                                    @endif
                                                    @if($lesson->has_quiz)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 rounded bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400 ml-1">🧠 {{ __('اختبار') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                            <span class="flex w-full justify-center lg:justify-start items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 dark:text-slate-300 dark:bg-slate-800 {{ $isLessonCompleted ? 'hover:bg-slate-200 dark:hover:bg-slate-700' : 'hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500' }} transition-colors shadow-sm">
                                                {{ $isLessonCompleted ? __('مراجعة الدرس') : ($isCurrent ? __('متابعة الدرس') : __('شاهد الدرس')) }}
                                                <svg class="w-3.5 h-3.5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- ─── RIGHT COLUMN: STICKY ENROLLMENT CARD (DESKTOP) ─── --}}
            <div class="hidden lg:block lg:col-span-5 xl:col-span-4 self-start sticky top-24 pt-8" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-[#0f172a] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl overflow-hidden relative z-20">
                    
                    {{-- Card Gradient Decoration --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary-500/10 dark:bg-primary-500/5 blur-[60px] rounded-full pointer-events-none z-0"></div>

                    <div class="relative z-10 w-full">
                        {{-- Progress Ring --}}
                        <div class="text-center mb-8">
                            <div class="relative w-36 h-36 mx-auto mb-4">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="72" cy="72" r="64" stroke-width="8" fill="transparent" class="stroke-slate-800"/>
                                    <circle cx="72" cy="72" r="64" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ 2 * 3.14159 * 64 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 64 * (1 - ($progress) / 100) }}"
                                        class="text-primary-500" stroke="currentColor" stroke-linecap="round"
                                        style="transition: stroke-dashoffset 1s ease;"/>
                                </svg>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <span class="text-4xl font-black text-white">{{ round($progress) }}%</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-slate-400">{{ __('نسبة إنجازك بطل') }}</p>
                        </div>

                        {{-- Stats --}}
                        <div class="space-y-3 text-sm mb-8 w-full">
                            <div class="flex justify-between items-center p-4 rounded-[1.25rem] bg-slate-800/50">
                                <span class="text-slate-400 font-bold">{{ __('الدروس المكتملة') }}</span>
                                <span class="font-black text-white text-base">{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 rounded-[1.25rem] bg-slate-800/50">
                                <span class="text-slate-400 font-bold">{{ __('تاريخ البدء') }}</span>
                                <span class="font-black text-white text-base">{{ $startedAt }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 rounded-[1.25rem] bg-slate-800/50">
                                <span class="text-slate-400 font-bold">{{ __('آخر نشاط لك') }}</span>
                                <span class="font-black border-b border-dashed border-slate-600 text-white text-base">{{ $lastAccessed }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        @if($enrollment->completed_at || $enrollment->progress_percentage >= 100)
                            <form action="{{ route('student.courses.certificate', $course) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center justify-center w-full py-4 mb-3 rounded-[1.25rem] bg-emerald-600 border border-emerald-500 text-white text-lg font-black shadow-[0_10px_30px_rgba(16,185,129,0.2)] hover:bg-emerald-500 gap-2 hover:scale-[1.02] active:scale-95 transition-all"><span class="ml-1 rtl:ml-0 rtl:mr-1">🎓</span> {{ __('أرسل الشهادة لتليجرام') }}</button>
                            </form>
                        @endif

                        @if($enrollment->certificate)
                            <a href="{{ route('student.certificates.show', $enrollment->certificate) }}" class="flex items-center justify-center w-full py-3.5 rounded-[1.25rem] bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition-colors border border-slate-700 hover:text-white">
                                {{ __('عرض الشهادة') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
