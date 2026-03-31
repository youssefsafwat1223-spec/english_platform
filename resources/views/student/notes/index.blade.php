@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', ($isArabic ? 'ملاحظاتي' : 'My Notes') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="student-container relative z-10">
        <x-student.page-header
            title="{{ $isArabic ? 'ملاحظاتي' : 'My' }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500'>{{ $isArabic ? 'الدراسية' : 'Notes' }}</span>"
            subtitle="{{ $isArabic ? 'هنا تجد كل الملاحظات التي كتبتها أثناء مشاهدة الدروس ومتابعة الكورسات.' : 'Your personal collection of notes taken during courses and lessons.' }}"
            badge="{{ $isArabic ? 'الدراسة' : 'Study' }}"
            badgeColor="primary"
            badgeIcon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24' aria-hidden='true'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 3h6l4 4v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 9h6M9 13h6M9 17h4'/></svg>"
        >
            <x-slot name="actions">
                @if(isset($notes) && $notes->count() > 0)
                    <a href="{{ route('student.notes.export-pdf') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 flex items-center gap-2 font-bold bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 border-none text-white transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        {{ $isArabic ? 'تصدير PDF' : 'Export PDF' }}
                    </a>
                @endif
            </x-slot>
        </x-student.page-header>

        {{-- Filters --}}
        @if(isset($courses) && $courses->count() > 0)
            <x-student.card padding="p-5 md:p-6" class="mb-8" data-aos="fade-up">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                    <div class="flex-1 min-w-[200px] max-w-md">
                        <label class="block text-sm font-bold text-slate-900 dark:text-white mb-2 ml-1">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                {{ $isArabic ? 'تصفية حسب الكورس' : 'Filter by Course' }}
                            </span>
                        </label>
                        <div class="relative">
                            <select name="course_id" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all font-medium appearance-none shadow-inner" onchange="this.form.submit()">
                                <option value="">{{ $isArabic ? 'كل الكورسات' : 'All Courses' }}</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center flex-col justify-center px-4 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </form>
            </x-student.card>
        @endif

        {{-- Notes Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($notes as $note)
                <x-student.card padding="p-0" class="hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col relative h-[280px]" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 300) }}">
                    
                    {{-- Note Header --}}
                    <div class="p-6 border-b border-slate-200/50 dark:border-white/5 bg-gradient-to-br from-primary-50/50 to-transparent dark:from-primary-900/5 dark:to-transparent shrink-0">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6l4 4v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6M9 13h6M9 17h4"/></svg>
                            </div>
                            <div class="bg-white/80 dark:bg-black/20 border border-slate-200 dark:border-slate-700/50 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                {{ $note->updated_at->format('M d, Y') }}
                            </div>
                        </div>
                        
                        <a href="{{ route('student.notes.show', $note) }}" class="inline-block text-lg font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors line-clamp-1 truncate w-full mb-1">
                            {{ $note->title ?? Str::limit(strip_tags($note->content), 30) }}
                        </a>
                        
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 truncate">
                            {{ $note->lesson->course->title ?? ($isArabic ? 'بدون تصنيف' : 'Uncategorized') }}
                        </div>
                    </div>

                    {{-- Note Content Snippet --}}
                    <div class="p-6 flex-1 relative min-h-0">
                        <div class="text-[13px] md:text-[14px] leading-relaxed font-medium text-slate-600 dark:text-slate-300 line-clamp-3">
                            {{ strip_tags($note->content) }}
                        </div>
                        
                        {{-- Fade out bottom --}}
                        <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white/95 to-transparent dark:from-slate-900/95 dark:to-transparent"></div>
                    </div>

                    {{-- Note Footer --}}
                    <div class="px-6 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between shrink-0 relative z-10">
                        <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $note->updated_at->diffForHumans() }}
                        </div>
                        
                        <a href="{{ route('student.notes.show', $note) }}" class="btn-ghost px-3 py-1.5 rounded-lg text-primary-500 text-xs font-bold bg-primary-500/10 border border-primary-500/20 group-hover:bg-primary-500 group-hover:text-white transition-all flex items-center gap-1 relative z-20">
                            {{ $isArabic ? 'فتح' : 'Open' }}
                            <svg class="w-3.5 h-3.5 transition-transform {{ $isArabic ? 'rotate-180 group-hover:-translate-x-0.5' : 'group-hover:translate-x-0.5' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    {{-- Clickable entire card area --}}
                    <a href="{{ route('student.notes.show', $note) }}" class="absolute inset-0 z-0"></a>
                </x-student.card>
            @empty
                <x-student.empty-state
                    title="{{ $isArabic ? 'لا توجد ملاحظات بعد' : 'No Notes Available' }}"
                    message="{{ $isArabic ? 'لم تكتب أي ملاحظات حتى الآن. أثناء مشاهدة الدروس يمكنك استخدام قسم الملاحظات لتسجيل أهم النقاط.' : 'You haven\'t taken any notes yet. While watching lessons, use the notes section to jot down important information.' }}"
                    data-aos="fade-up"
                >
                    <x-slot name="icon">
                        <svg class="w-10 h-10 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4.75A2.75 2.75 0 0 1 9.75 2h6.19L21 7.06V19.25A2.75 2.75 0 0 1 18.25 22h-8.5A2.75 2.75 0 0 1 7 19.25V4.75z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 2.5V7h4.5M10 11h4m-4 3h6m-6 3h4"></path></svg>
                    </x-slot>
                    <x-slot name="actions">
                        <a href="{{ route('student.courses.my-courses') }}" class="btn-primary ripple-btn inline-flex items-center gap-2 shadow-lg shadow-primary-500/25">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ $isArabic ? 'اذهب إلى كورساتي' : 'Go to My Courses' }}
                        </a>
                    </x-slot>
                </x-student.empty-state>
            @endforelse
        </div>

        @if(isset($notes) && method_exists($notes, 'links') && $notes->hasPages())
            <div class="mt-10" data-aos="fade-up">
                {{ $notes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection






