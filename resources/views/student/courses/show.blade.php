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
<div class="relative min-h-screen">
    {{-- Cinematic Hero Background --}}
    <div class="absolute top-0 left-0 w-full h-[60vh] z-0 overflow-hidden bg-slate-100 dark:bg-slate-900">
        @if($course->thumbnail)
            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-slate-200 to-slate-100 dark:from-slate-900 dark:to-[#020617] flex items-center justify-center">
                <span class="text-slate-300 dark:text-white/5 text-9xl font-black">{{ substr($course->title, 0, 1) }}</span>
            </div>
        @endif
        {{-- Deep Gradient Overlays for SaaS look --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50/80 via-slate-50/95 to-slate-50 dark:from-[#020617]/80 dark:via-[#020617]/95 dark:to-[#020617] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-50 via-slate-50/80 to-transparent dark:from-[#020617] dark:via-[#020617]/80"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-24 lg:pt-32 pb-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- ─── LEFT COLUMN: HERO CONTENT & CURRICULUM ─── --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-12">
                
                {{-- Hero Content --}}
                <div data-aos="fade-up">
                    <a href="{{ route('student.courses.my-courses') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white transition-colors mb-8 group">
                        <span class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center mr-3 group-hover:bg-slate-300 dark:group-hover:bg-white/10 transition-colors border border-slate-300 dark:border-white/10 text-slate-600 dark:text-slate-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </span>
                        {{ __('Back to Courses') }}
                    </a>

                    <div class="flex items-center gap-3 mb-6">
                        <span class="badge bg-primary-500/10 text-primary-600 dark:text-primary-400 border border-primary-500/20 px-3 py-1 text-xs uppercase tracking-wider font-bold rounded-full">
                            {{ $course->level ?? __('Beginner') }}
                        </span>
                        @if($course->average_rating)
                            <div class="flex items-center gap-1.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 px-3 py-1 rounded-full text-xs font-bold">
                                <span>★</span> {{ number_format($course->average_rating, 1) }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-6 leading-[1.1] tracking-tight drop-shadow-sm dark:drop-shadow-lg">
                        {{ $course->title }}
                    </h1>
                    
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl mb-8">
                        {{ $course->short_description ?: $course->description }}
                    </p>

                    <div class="flex flex-wrap items-center gap-6 text-sm font-medium text-slate-600 dark:text-slate-300">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center border border-slate-300 dark:border-white/10 text-slate-700 dark:text-white">📚</div>
                            <div>
                                <div class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">{{ __('العناوين') }}</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $course->levels()->active()->count() }} {{ __('عنوان') }} — {{ $course->lessons->count() }} {{ __('درس') }}</div>
                            </div>
                        </div>
                        <div class="w-px h-10 bg-slate-300 dark:bg-white/10 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center border border-slate-300 dark:border-white/10 text-slate-700 dark:text-white">⏱️</div>
                            <div>
                                <div class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">{{ __('Duration') }}</div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ __('Self-paced') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200 dark:border-white/5 my-8">

                {{-- Curriculum Section --}}
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-primary-500/10 text-primary-600 dark:text-primary-400 flex items-center justify-center text-sm border border-primary-500/20">📋</span>
                        {{ __('Course Syllabus') }}
                    </h3>
                    
                    <div class="space-y-4" x-data="{ openLevel: 0 }">
                        @forelse($course->levels()->active()->ordered()->with('lessons')->get() as $levelIndex => $level)
                        @php
                            $isLevelUnlocked = ($isEnrolled ?? false) ? $level->isUnlockedFor(auth()->user()) : ($levelIndex === 0);
                            $completionPercent = ($isEnrolled ?? false) ? $level->getCompletionPercentageFor(auth()->user()) : 0;
                            $isCompleted = $completionPercent === 100;
                        @endphp
                        <div class="group relative bg-white/50 dark:bg-[#0f172a]/50 backdrop-blur-sm border {{ $isLevelUnlocked ? 'border-slate-200 dark:border-white/10' : 'border-slate-200/60 dark:border-white/5' }} rounded-2xl shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 overflow-hidden">
                            {{-- Level Header --}}
                            <button @click="openLevel = openLevel === {{ $levelIndex }} ? -1 : {{ $levelIndex }}" class="w-full p-5 flex items-center justify-between gap-4 text-right">
                                <div class="flex items-center gap-4 flex-1">
                                    {{-- Level Thumbnail / Number Badge --}}
                                    @if($level->thumbnail)
                                        <div class="shrink-0 w-14 h-14 rounded-2xl overflow-hidden shadow-lg relative">
                                            <img src="{{ Storage::url($level->thumbnail) }}" alt="{{ $level->title }}" class="w-full h-full object-cover {{ !$isLevelUnlocked ? 'opacity-40 grayscale' : '' }} transition-all duration-300">
                                            @if($isCompleted)
                                                <div class="absolute inset-0 bg-emerald-500/60 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            @elseif(!$isLevelUnlocked)
                                                <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="shrink-0 w-14 h-14 rounded-2xl {{ $isCompleted ? 'bg-gradient-to-br from-emerald-500 to-green-500' : ($isLevelUnlocked ? 'bg-gradient-to-br from-primary-500 to-accent-500' : 'bg-slate-200 dark:bg-slate-800') }} flex items-center justify-center font-black text-lg text-white shadow-lg transition-all duration-300">
                                            @if($isCompleted)
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($isLevelUnlocked)
                                                {{ $levelIndex + 1 }}
                                            @else
                                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex-1 text-right">
                                        <h4 class="font-bold text-lg {{ $isLevelUnlocked ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500' }} transition-colors">
                                            {{ $level->title }}
                                        </h4>
                                        <div class="flex items-center gap-3 text-xs font-semibold {{ $isLevelUnlocked ? 'text-slate-500' : 'text-slate-400 dark:text-slate-600' }} mt-1">
                                            <span>{{ $level->lessons->count() }} {{ __('Lessons') }}</span>
                                            @if($isEnrolled ?? false)
                                                @if($isCompleted)
                                                    <span class="text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">{{ __('Completed') }} ✓</span>
                                                @elseif($isLevelUnlocked && $completionPercent > 0)
                                                    <span class="text-primary-500 bg-primary-500/10 px-2 py-0.5 rounded border border-primary-500/20">{{ $completionPercent }}%</span>
                                                @elseif(!$isLevelUnlocked)
                                                    <span class="text-slate-400 bg-slate-200/50 dark:bg-slate-800/50 px-2 py-0.5 rounded">🔒 {{ __('Locked') }}</span>
                                                @endif
                                            @endif
                                        </div>
                                        @if($isEnrolled ?? false && $isLevelUnlocked && $completionPercent > 0 && !$isCompleted)
                                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-2 overflow-hidden">
                                                <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-700" style="width: {{ $completionPercent }}%"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Chevron --}}
                                <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 shrink-0" :class="openLevel === {{ $levelIndex }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            {{-- Level Lessons --}}
                            <div x-show="openLevel === {{ $levelIndex }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="border-t border-slate-200 dark:border-white/5">
                                <div class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($level->lessons as $lessonIndex => $lesson)
                                    @php
                                        $isAccessible = ($lesson->is_free || ($isEnrolled ?? false)) && $isLevelUnlocked;
                                    @endphp
                                    <div class="px-5 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 {{ $isAccessible ? 'hover:bg-slate-50 dark:hover:bg-white/[0.02]' : '' }} transition-colors">
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="shrink-0 w-8 h-8 rounded-lg {{ $isAccessible ? 'bg-primary-500/10 text-primary-600 dark:text-primary-400' : 'bg-slate-100 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500' }} flex items-center justify-center font-bold text-sm">
                                                {{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div>
                                                <span class="font-semibold text-sm {{ $isAccessible ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400 dark:text-slate-500' }}">{{ $lesson->title }}</span>
                                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                                    @if($lesson->video_duration)
                                                        <span>{{ $lesson->formatted_duration }}</span>
                                                    @endif
                                                    @if($lesson->is_free)
                                                        <span class="text-emerald-500 bg-emerald-500/10 px-1.5 py-0.5 rounded text-[10px] font-bold">{{ __('Preview') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($isAccessible)
                                            <a href="{{ route('student.lessons.show', [$course, $lesson]) }}" class="px-4 py-2 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-500/10 hover:bg-primary-500 hover:text-white transition-all flex items-center gap-1.5 border border-primary-500/20">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                {{ __('Play') }}
                                            </a>
                                        @else
                                            <div class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 text-xs font-bold flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                {{ __('Locked') }}
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-8 text-slate-500 italic bg-white/50 dark:bg-[#0f172a]/50 rounded-2xl border border-slate-200 dark:border-white/5">
                            {{ __('No lessons available for this course yet.') }}
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT COLUMN: STICKY ENROLLMENT CARD ─── --}}
            <div class="lg:col-span-5 xl:col-span-4 mt-8 lg:mt-0">
                <div class="sticky top-28" data-aos="fade-left" data-aos-delay="200">
                    <div class="relative bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-1 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 overflow-hidden group">
                        {{-- Animated border gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 via-transparent to-accent-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <div class="bg-white dark:bg-[#020617] rounded-[1.8rem] p-8 relative z-10 h-full border border-slate-100 dark:border-white/5 flex flex-col justify-between">
                            
                            <div>
                                {{-- Thumbnail Preview inside card --}}
                                <div class="w-full h-48 rounded-xl overflow-hidden mb-8 relative border border-slate-200 dark:border-white/10 shadow-inner">
                                    @if($course->thumbnail)
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="Course Preview" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center">
                                            <span class="text-3xl text-slate-300 dark:text-white/20 font-black">{{ __('PREVIEW') }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-slate-900/20 dark:bg-black/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer backdrop-blur-[2px]">
                                        <div class="w-12 h-12 rounded-full bg-white/90 dark:bg-white/20 backdrop-blur-md flex items-center justify-center text-primary-600 dark:text-white border border-white/50 dark:border-white/30 shadow-lg">
                                            <svg class="w-5 h-5 ml-1" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price Block --}}
                                <div class="mb-8">
                                    <div class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-widest">{{ __('Investment') }}</div>
                                    @if($course->price == 0)
                                        <div class="text-5xl font-black text-emerald-500 dark:text-emerald-400">{{ __('Free') }}</div>
                                    @else
                                        <div class="flex items-start text-5xl font-black text-slate-900 dark:text-white mb-2">
                                            {{ number_format($course->price, 0) }}
                                            <span class="text-2xl mt-1.5 mr-1 text-slate-400 dark:text-slate-500">ر.س</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm line-through text-slate-400 dark:text-slate-500 font-bold">{{ number_format($course->price * 1.5, 0) }} ر.س</span>
                                            <span class="px-2 py-0.5 rounded bg-amber-500/10 dark:bg-primary-500/20 text-amber-600 dark:text-primary-400 text-xs font-black uppercase tracking-wider border border-amber-500/20 dark:border-primary-500/30">{{ __('SAVE 33%') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- CTA Button --}}
                                @if($isEnrolled ?? false)
                                    <div class="mb-6">
                                        <div class="flex justify-between items-center text-xs font-bold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wider">
                                            <span>{{ __('Course Progress') }}</span>
                                            <span class="text-primary-600 dark:text-primary-400">{{ $progress ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 mb-6 overflow-hidden border border-slate-200 dark:border-white/5 shadow-inner">
                                            <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress ?? 0 }}%"></div>
                                        </div>
                                        <a href="{{ route('student.courses.learn', $course) }}" class="flex items-center justify-center w-full py-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-lg font-black hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors shadow-xl dark:shadow-[0_0_20px_rgba(255,255,255,0.1)] gap-2">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                            {{ __('Resume Learning') }}
                                        </a>
                                    </div>
                                @else
                                    <div class="mb-6">
                                        <a href="{{ route('student.courses.enroll', $course) }}" class="flex items-center justify-center w-full py-4 rounded-xl bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-500 hover:to-accent-500 text-white text-lg font-black transition-all shadow-lg dark:shadow-[0_0_20px_rgba(2,132,199,0.3)] gap-2 hover:scale-[1.02]">
                                            {{ __('Enroll Now') }}
                                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                        <p class="text-xs text-center mt-4 font-semibold text-slate-500 flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            {{ __('30-day money-back guarantee') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Features List --}}
                            <div class="pt-6 border-t border-slate-100 dark:border-white/10 mt-6">
                                <h4 class="font-bold mb-4 text-slate-800 dark:text-white text-sm">{{ __('This course includes:') }}</h4>
                                <ul class="space-y-4 text-sm font-semibold text-slate-600 dark:text-slate-400">
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-primary-500 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ $course->lessons->count() }} {{ __('high-quality video lessons') }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-amber-500 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ __('Certificate of completion') }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span>{{ __('Full lifetime access') }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        <span>{{ __('Access on mobile and TV') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
