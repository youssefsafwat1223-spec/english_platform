@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', ($isArabic ? 'نتيجة الاختبار' : 'Quiz Result') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b {{ $attempt->passed ? 'from-emerald-500/10 via-teal-500/5' : 'from-rose-500/10 via-red-500/5' }} to-transparent pointer-events-none z-0"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Breadcrumb Navigation (Optional but good UX) --}}
        @if(isset($attempt->quiz) && isset($attempt->quiz->lesson))
            <nav class="mb-6 text-sm font-medium" data-aos="fade-down">
                <ol class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('student.courses.my-courses') }}" class="hover:text-primary-500 transition-colors">{{ $isArabic ? 'كورساتي' : 'My Courses' }}</a></li>
                    <li class="opacity-50">/</li>
                    <li><a href="{{ route('student.courses.learn', $attempt->quiz->lesson->course) }}" class="hover:text-primary-500 transition-colors truncate max-w-[150px] sm:max-w-none inline-block align-bottom">{{ $attempt->quiz->lesson->course->title }}</a></li>
                    <li class="opacity-50">/</li>
                    <li class="text-slate-900 dark:text-white font-bold truncate max-w-[150px] sm:max-w-none inline-block align-bottom">{{ $attempt->quiz->lesson->title }}</li>
                </ol>
            </nav>
        @endif

        {{-- Result Hero Card --}}
        <div class="glass-card overflow-hidden rounded-[2.5rem] mb-10 text-center border-t-8 shadow-2xl relative {{ $attempt->passed ? 'border-t-emerald-500 shadow-emerald-500/10' : 'border-t-rose-500 shadow-rose-500/10' }}" data-aos="zoom-in">
            {{-- Decorative Background Elements --}}
            <div class="absolute -top-32 -left-32 w-64 h-64 rounded-full mix-blend-multiply filter blur-[64px] opacity-50 {{ $attempt->passed ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
            <div class="absolute -bottom-32 -right-32 w-64 h-64 rounded-full mix-blend-multiply filter blur-[64px] opacity-50 {{ $attempt->passed ? 'bg-teal-400' : 'bg-orange-400' }}"></div>
            
            <div class="p-8 md:p-14 relative z-10">
                <div class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-white dark:bg-slate-800 shadow-xl relative {{ $attempt->passed ? 'text-emerald-500 shadow-emerald-500/20' : 'text-rose-500 shadow-rose-500/20' }}">
                    <div class="absolute inset-0 rounded-full animate-ping opacity-20 {{ $attempt->passed ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    @if($attempt->passed)
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @else
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-black mb-3 tracking-tight bg-clip-text text-transparent {{ $attempt->passed ? 'bg-gradient-to-r from-emerald-600 to-teal-400' : 'bg-gradient-to-r from-rose-600 to-red-400' }}">
                    {{ $attempt->passed ? ($isArabic ? 'أحسنت!' : 'Congratulations!') : ($isArabic ? 'حاول مرة أخرى' : 'Keep Trying!') }}
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-300 mb-10 font-medium">
                    {{ $isArabic ? 'أنهيت' : 'You have completed the' }} <span class="font-bold text-slate-900 dark:text-white">{{ $attempt->quiz->title ?? ($isArabic ? 'الاختبار' : 'Quiz') }}</span>
                </p>

                {{-- Score Ring --}}
                <div class="relative w-40 h-40 mx-auto mb-10 group">
                    <svg class="w-full h-full transform -rotate-90 drop-shadow-lg">
                        <circle cx="80" cy="80" r="70" stroke-width="12" fill="transparent" class="stroke-slate-200 dark:stroke-slate-700/50"/>
                        <circle cx="80" cy="80" r="70" stroke-width="12" fill="transparent"
                            stroke-dasharray="{{ 2 * 3.14159 * 70 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 70 * (1 - ($attempt->score / 100)) }}"
                            class="{{ $attempt->passed ? 'stroke-emerald-500' : 'stroke-rose-500' }}" stroke-linecap="round"
                            style="transition: stroke-dashoffset 1.5s cubic-bezier(0.16, 1, 0.3, 1);"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">{{ round($attempt->score) }}<span class="text-2xl opacity-50">%</span></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-1">{{ $isArabic ? 'النتيجة' : 'Score' }}</span>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 min-w-[130px] border border-slate-200 dark:border-white/5 shadow-sm">
                        <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $attempt->correct_answers ?? 0 }}</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-emerald-500">{{ $isArabic ? 'صحيح' : 'Correct' }}</div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 min-w-[130px] border border-slate-200 dark:border-white/5 shadow-sm">
                        <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $attempt->total_questions ?? 0 }}</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $isArabic ? 'الأسئلة' : 'Questions' }}</div>
                    </div>

                    @if($attempt->time_taken)
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 min-w-[130px] border border-slate-200 dark:border-white/5 shadow-sm">
                        <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ gmdate('i:s', $attempt->time_taken) }}</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-blue-500">{{ $isArabic ? 'الوقت المستغرق' : 'Time Taken' }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap justify-center gap-4 mb-16" data-aos="fade-up">
            @if(isset($attempt->quiz) && isset($attempt->quiz->lesson))
                @if($attempt->passed)
                    {{-- Continue to next action --}}
                    @php 
                        $lesson = $attempt->quiz->lesson;
                        $nextLesson = $lesson->next_lesson;
                    @endphp
                    @if($nextLesson)
                        <a href="{{ route('student.lessons.show', [$lesson->course, $nextLesson]) }}" class="btn-primary ripple-btn px-8 py-4 rounded-xl shadow-lg font-bold flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 border-0 text-white text-lg">
                            {{ $isArabic ? 'الدرس التالي' : 'Next Lesson' }}
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('student.courses.learn', $attempt->quiz->lesson->course) }}" class="btn-primary ripple-btn px-8 py-4 rounded-xl shadow-lg font-bold flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 border-0 text-white text-lg">
                            {{ $isArabic ? 'العودة إلى الكورس' : 'Back to Course' }}
                        </a>
                    @endif
                @else
                    {{-- Retake Option --}}
                    <a href="{{ route('student.quizzes.start', $attempt->quiz) }}" class="btn-primary ripple-btn px-8 py-4 rounded-xl shadow-lg shadow-primary-500/25 font-bold flex items-center gap-2 text-lg">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        {{ $isArabic ? 'إعادة الاختبار' : 'Retake Quiz' }}
                    </a>
                @endif
            @endif
            <a href="{{ route('student.quizzes.my-attempts') }}" class="btn-secondary px-8 py-4 rounded-xl font-bold flex items-center gap-2 text-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600">
                <svg class="w-5 h-5 opacity-70 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                {{ $isArabic ? 'كل المحاولات' : 'All Attempts' }}
            </a>
        </div>

        {{-- Questions Review Section --}}
        @if(isset($attempt->answers) || isset($questions))
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-8" data-aos="fade-up">
                <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $isArabic ? 'مراجعة الأسئلة' : 'Questions Review' }}</h2>
            </div>
            
            <div class="space-y-6">
                @php $questionList = $questions ?? $attempt->answers ?? collect(); @endphp
                @foreach($questionList as $index => $answer)
                    @php 
                        $isCorrect = $answer->is_correct ?? false; 
                        $isDragDrop = ($answer->question->question_type ?? '') === 'drag_drop';
                        if ($isDragDrop) {
                            $userPairs = json_decode($answer->user_answer, true) ?? [];
                            $correctPairs = $answer->question->matching_pairs ?? [];
                        } else {
                            $userAnswerText = $answer->question->options[$answer->user_answer] ?? $answer->user_answer ?? '-';
                            $correctOption = $answer->question->correct_answer ?? null;
                            $correctAnswerText = $correctOption ? ($answer->question->options[$correctOption] ?? $correctOption) : '-';
                        }
                    @endphp
                    <div class="glass-card overflow-hidden rounded-3xl border {{ $isCorrect ? 'border-emerald-500/30 bg-emerald-50/30 dark:bg-emerald-900/10' : 'border-rose-500/30 bg-rose-50/30 dark:bg-rose-900/10' }}" data-aos="fade-up" data-aos-delay="{{ min($index * 50, 500) }}">
                        <div class="p-6 md:p-8">
                            <div class="flex items-start gap-4">
                                {{-- Status Icon --}}
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-inner 
                                    {{ $isCorrect ? 'bg-gradient-to-br from-emerald-500 to-teal-400 text-white' : 'bg-gradient-to-br from-rose-500 to-red-400 text-white' }}">
                                    @if($isCorrect)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1 tracking-wider uppercase">{{ $isArabic ? 'السؤال' : 'Question' }} {{ $index + 1 }}</div>
                                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-6 leading-relaxed">{{ $answer->question->text ?? $answer->question_text ?? (($isArabic ? 'السؤال' : 'Question') . ' ' . ($index + 1)) }}</h3>
                                    
                                    <div class="space-y-3">
                                        @if($isDragDrop)
                                            {{-- Drag & Drop Answer Display --}}
                                            <div class="p-4 rounded-xl border {{ $isCorrect ? 'bg-emerald-100/50 dark:bg-emerald-500/20 border-emerald-200 dark:border-emerald-500/30' : 'bg-rose-100/50 dark:bg-rose-500/20 border-rose-200 dark:border-rose-500/30' }}">
                                                <div class="text-xs font-bold uppercase tracking-wider mb-3 {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">{{ $isArabic ? 'إجابتك' : 'Your Answer' }}</div>
                                                <div class="space-y-2">
                                                    @foreach($userPairs as $pair)
                                                        <div class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                                                            <span class="bg-white dark:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600">{{ $pair['left'] ?? '' }}</span>
                                                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                                                            <span class="bg-white dark:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600">{{ $pair['right'] ?? '' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if(!$isCorrect)
                                                <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">{{ $isArabic ? 'الإجابة الصحيحة' : 'Correct Answer' }}</div>
                                                    <div class="space-y-2">
                                                        @foreach($correctPairs as $pair)
                                                            <div class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                                                                <span class="bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-700">{{ $pair['left'] ?? '' }}</span>
                                                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14m-5-5 5 5-5 5"/></svg>
                                                                <span class="bg-emerald-100 dark:bg-emerald-900/40 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-700">{{ $pair['right'] ?? '' }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            {{-- Standard Answer Display --}}
                                            <div class="flex items-center gap-3 p-4 rounded-xl border {{ $isCorrect ? 'bg-emerald-100/50 dark:bg-emerald-500/20 border-emerald-200 dark:border-emerald-500/30' : 'bg-rose-100/50 dark:bg-rose-500/20 border-rose-200 dark:border-rose-500/30' }}">
                                                <div class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                                    @if($isCorrect)
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold uppercase tracking-wider {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">{{ $isArabic ? 'إجابتك' : 'Your Answer' }}</div>
                                                    <div class="font-bold text-slate-900 dark:text-white text-base mt-0.5">{{ $userAnswerText }}</div>
                                                </div>
                                            </div>

                                            @if(!$isCorrect)
                                                <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                                    <div class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500 text-white opacity-80">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $isArabic ? 'الإجابة الصحيحة' : 'Correct Answer' }}</div>
                                                        <div class="font-bold text-slate-900 dark:text-white text-base mt-0.5">{{ $correctAnswerText }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
