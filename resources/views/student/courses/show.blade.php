@extends('layouts.app')

@section('title', $course->title . ' | ' . config('app.name', 'إتقان الإنجليزية'))
@section('meta_description', Str::limit(strip_tags($course->short_description ?: $course->description), 160))
@section('meta_keywords', 'كورس ' . $course->title . ', تعلم الإنجليزية, كورسات إنجليزي, ' . ($course->level ?? 'جميع المستويات'))
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
                                <span>★</span> {{ number_format($course->average_rating, 1) }}
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
                            <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 text-xl border border-indigo-100 dark:border-indigo-500/20 shrink-0">📚</div>
                            <div>
                                <div class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-wider mb-1">{{ __('المحتوى') }}</div>
                                <div class="font-black text-slate-900 dark:text-white">{{ $course->levels()->active()->count() }} {{ __('عنوان') }}</div>
                                <div class="text-[11px] font-bold text-slate-500 mt-0.5">{{ $course->lessons->count() }} {{ __('درس فيديو') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-1 sm:flex-none bg-white dark:bg-slate-800/50 min-w-[140px] px-5 py-4 rounded-[1.25rem] border border-slate-200 dark:border-white/5 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center text-teal-500 text-xl border border-teal-100 dark:border-teal-500/20 shrink-0">⏱️</div>
                            <div>
                                <div class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-wider mb-1">{{ __('المدة الزمنية') }}</div>
                                <div class="font-black text-slate-900 dark:text-white">{{ __('حسب سرعتك') }}</div>
                                <div class="text-[11px] font-bold text-slate-500 mt-0.5">{{ __('وصول مدى الحياة') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Inline Image Placeholder --}}
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

                {{-- Curriculum Section --}}
                <div data-aos="fade-up" data-aos-delay="100" class="pt-6">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-6 lg:mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-[0.8rem] bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-white/5 flex items-center justify-center text-lg">📑</span>
                        {{ __('محتويات الكورس') }}
                    </h3>
                    
                    <div class="space-y-4 lg:space-y-5" x-data="{ openLevel: {{ ($isEnrolled ?? false) ? '0' : '0' }} }">
                        @forelse($course->levels()->active()->ordered()->with('lessons')->get() as $levelIndex => $level)
                        @php
                            $isLevelUnlocked = ($isEnrolled ?? false) ? $level->isUnlockedFor(auth()->user()) : ($levelIndex === 0);
                            $completionPercent = ($isEnrolled ?? false) ? $level->getCompletionPercentageFor(auth()->user()) : 0;
                            $isCompleted = $completionPercent === 100;
                        @endphp
                        <div class="bg-white dark:bg-[#0f172a] border {{ $isLevelUnlocked ? 'border-slate-200 hover:border-primary-200 dark:border-white/5 dark:hover:border-primary-900/50' : 'border-slate-200/60 dark:border-white/5' }} rounded-[1.5rem] lg:rounded-[2rem] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                            
                            {{-- Locked Overlay Effect --}}
                            @if(!$isLevelUnlocked)
                            <div class="absolute inset-0 bg-slate-50/60 dark:bg-[#0f172a]/80 backdrop-blur-[1px] z-10 pointer-events-none"></div>
                            @endif

                            {{-- Level Header --}}
                            <button @click="openLevel = openLevel === {{ $levelIndex }} ? -1 : {{ $levelIndex }}" class="w-full p-4 lg:p-6 flex items-center justify-between gap-4 text-right relative z-20 outline-none" :class="openLevel === {{ $levelIndex }} ? 'bg-slate-50/50 dark:bg-white/[0.02]' : ''">
                                <div class="flex items-center gap-4 lg:gap-5 flex-1 min-w-0">
                                    
                                    {{-- Level Number/Status Badge --}}
                                    <div class="shrink-0 w-12 h-12 lg:w-16 lg:h-16 rounded-[1rem] lg:rounded-[1.25rem] {{ $isCompleted ? 'bg-emerald-50 text-emerald-500 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : ($isLevelUnlocked ? 'bg-primary-50 text-primary-600 border border-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:border-primary-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:border-white/5') }} flex items-center justify-center font-black text-xl lg:text-2xl transition-all duration-300 shadow-sm">
                                        @if($isCompleted)
                                            <svg class="w-7 h-7 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @elseif($isLevelUnlocked)
                                            {{ $levelIndex + 1 }}
                                        @else
                                            <svg class="w-6 h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 text-right min-w-0">
                                        <h4 class="font-black text-base lg:text-xl {{ $isLevelUnlocked ? 'text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400' : 'text-slate-500 dark:text-slate-400' }} tracking-tight break-words transition-colors leading-snug">
                                            {{ $level->title }}
                                        </h4>
                                        <div class="flex flex-wrap items-center gap-2 lg:gap-3 text-xs lg:text-sm font-bold mt-1.5 lg:mt-2">
                                            <span class="text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/50 px-2 py-0.5 rounded-md">{{ $level->lessons->count() }} {{ __('دروس فيديو') }}</span>
                                            @if($isEnrolled ?? false)
                                                @if($isCompleted)
                                                    <span class="text-emerald-600 bg-emerald-50 border border-emerald-100 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-md">{{ __('مكتمل') }} ✓</span>
                                                @elseif($isLevelUnlocked && $completionPercent > 0)
                                                    <span class="text-primary-600 bg-primary-50 border border-primary-100 dark:text-primary-400 dark:bg-primary-500/10 dark:border-primary-500/20 px-2.5 py-0.5 rounded-md">{{ $completionPercent }}%</span>
                                                @elseif(!$isLevelUnlocked)
                                                    <span class="text-slate-500 bg-slate-100 border border-slate-200 dark:text-slate-400 dark:bg-slate-800 dark:border-white/5 px-2.5 py-0.5 rounded-md">🔒 {{ __('مغلق') }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Chevron --}}
                                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full flex items-center justify-center bg-white dark:bg-[#020617] border border-slate-200 dark:border-white/5 shrink-0 transition-all duration-300 shadow-sm" :class="openLevel === {{ $levelIndex }} ? 'rotate-180 bg-primary-50 border-primary-100 text-primary-600 dark:bg-primary-900/20 dark:border-primary-500/30 dark:text-primary-400' : 'text-slate-400 group-hover:border-slate-300 dark:group-hover:border-white/10'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            {{-- Progress Bar (if started but not completed) --}}
                            @if($isEnrolled ?? false && $isLevelUnlocked && $completionPercent > 0 && !$isCompleted)
                                <div class="w-full bg-slate-100 dark:bg-slate-800/50 h-1.5 overflow-hidden relative z-20">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $completionPercent }}%"></div>
                                </div>
                            @endif

                            {{-- Level Lessons Accordion --}}
                            <div x-show="openLevel === {{ $levelIndex }}" x-collapse x-cloak class="border-t border-slate-100 dark:border-white/5 relative z-20 bg-slate-50/30 dark:bg-slate-900/20">
                                <div class="divide-y divide-slate-100 dark:divide-white/5 p-2 lg:p-4">
                                    @foreach($level->lessons as $lessonIndex => $lesson)
                                    @php
                                        $isAccessible = ($lesson->is_free || ($isEnrolled ?? false)) && $isLevelUnlocked;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-3 lg:p-4 rounded-xl lg:rounded-2xl {{ $isAccessible ? 'hover:bg-white dark:hover:bg-[#0f172a] hover:shadow-sm cursor-pointer border border-transparent hover:border-slate-200 dark:hover:border-white/5' : 'opacity-60' }} transition-all"
                                         @if($isAccessible) onclick="window.location='{{ route('student.lessons.show', [$course, $lesson]) }}'" @endif>
                                        
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="shrink-0 w-10 h-10 rounded-full {{ $isAccessible ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-white/5' }} flex items-center justify-center font-black text-sm shadow-inner">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h5 class="font-bold text-sm lg:text-base {{ $isAccessible ? 'text-slate-900 dark:text-slate-100 group-hover:text-primary-600 dark:group-hover:text-primary-400' : 'text-slate-500 dark:text-slate-500' }} line-clamp-2 transition-colors">{{ $lesson->title }}</h5>
                                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold mt-1 text-slate-500 dark:text-slate-400">
                                                    @if($lesson->video_duration)
                                                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span> {{ $lesson->formatted_duration }}</span>
                                                    @endif
                                                    @if($lesson->is_free && !($isEnrolled ?? false))
                                                        <span class="text-emerald-600 bg-emerald-50 border border-emerald-100 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/20 px-1.5 py-0.5 rounded text-[10px] ml-1 uppercase tracking-wider">{{ __('متاح مجاناً') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($isAccessible)
                                            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                                <span class="flex w-full justify-center lg:justify-start items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 dark:text-slate-300 dark:bg-slate-800 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500 transition-colors shadow-sm">
                                                    {{ __('شاهد الدرس') }}
                                                    <svg class="w-3.5 h-3.5 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                </span>
                                            </div>
                                        @else
                                            <div class="hidden sm:flex shrink-0 w-8 h-8 items-center justify-center text-slate-300 dark:text-slate-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
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
                    </div>
                </div>
            </div>
            
            {{-- ─── RIGHT COLUMN: STICKY ENROLLMENT CARD (DESKTOP) ─── --}}
            <div class="hidden lg:block lg:col-span-5 xl:col-span-4 self-start sticky top-24 pt-8" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white dark:bg-[#0f172a] rounded-[2.5rem] p-8 border border-slate-200 dark:border-white/5 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden relative z-20">
                    
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
                                    <span class="text-xl font-bold text-slate-500 dark:text-slate-400 mb-1">ر.س</span>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-2">
                                    <span class="text-sm line-through text-slate-400 dark:text-slate-500 font-bold decoration-2">{{ number_format($course->price * 1.5, 0) }} ر.س</span>
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
                </div>
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
                    <span class="font-black text-xl text-slate-900 dark:text-white leading-none drop-shadow-sm">{{ number_format($course->price, 0) }}<span class="text-[10px] font-bold ml-1 text-slate-500">ر.س</span></span>
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
