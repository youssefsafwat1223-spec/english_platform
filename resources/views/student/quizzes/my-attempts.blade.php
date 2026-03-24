@extends('layouts.app')

@section('title', __('My Quiz Attempts') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-500/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-10" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>📝</span> {{ __('Assessments') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('My Quiz') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Attempts') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Review your past quiz results, track your improvement, and identify areas for further study.') }}
                    </p>
                </div>
                
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('student.courses.my-courses') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-violet-500/25 flex items-center gap-2 font-bold bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 border-none text-white transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ __('Back to Learning') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Overview --}}
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @php
                $quizStats = [
                    ['icon' => '📝', 'value' => $stats['total_attempts'] ?? 0, 'label' => 'Total Attempts', 'color' => 'primary', 'desc' => __('Quizzes taken so far')],
                    ['icon' => '🎯', 'value' => round($stats['average_score'] ?? 0) . '%', 'label' => 'Average Score', 'color' => 'blue', 'desc' => __('Your overall accuracy')],
                    ['icon' => '🎓', 'value' => $stats['passed'] ?? 0, 'label' => 'Quizzes Passed', 'color' => 'emerald', 'desc' => __('Successfully completed')],
                ];
            @endphp
            
            @foreach($quizStats as $s)
                <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/50 dark:border-white/5 bg-white/50 dark:bg-slate-900/50 relative group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $s['color'] }}-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-6 md:p-8 flex items-center gap-6 relative z-10">
                        <div class="w-16 h-16 rounded-3xl bg-{{ $s['color'] }}-500/10 text-{{ $s['color'] }}-500 flex items-center justify-center text-3xl shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-inner">
                            {{ $s['icon'] }}
                        </div>
                        <div>
                            <div class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-{{ $s['color'] }}-600 to-{{ $s['color'] }}-400 tracking-tight mb-1">{{ $s['value'] }}</div>
                            <h3 class="font-bold text-slate-900 dark:text-white capitalize text-lg">{{ __($s['label']) }}</h3>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">{{ __($s['desc']) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Attempts Table --}}
        <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/50 dark:border-white/5 shadow-xl bg-white/80 dark:bg-slate-900/80" data-aos="fade-up">
            <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        📋
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Attempt History') }}</h3>
                </div>
                
                {{-- Quick Filter/Search could go here --}}
                <div class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    {{ $attempts->total() ?? 0 }} {{ __('Records Found') }}
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-y border-slate-200/50 dark:border-white/5 text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">{{ __('Quiz') }}</th>
                            <th class="px-6 py-4">{{ __('Score') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Date Taken') }}</th>
                            <th class="px-6 py-4">{{ __('Time') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5">
                        @forelse($attempts as $attempt)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3 w-48 sm:w-64 max-w-xs xl:w-auto">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 border border-slate-200 dark:border-white/5">
                                            @if($attempt->passed)
                                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white text-sm truncate group-hover:text-primary-500 transition-colors">
                                                {{ $attempt->quiz->title ?? 'Quiz' }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                                {{ $attempt->quiz->lesson->course->title ?? '' }} • {{ $attempt->quiz->lesson->title ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="text-lg font-black {{ $attempt->passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ round($attempt->score) }}%
                                        </div>
                                        <div class="w-16 h-1.5 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700">
                                            <div class="h-full rounded-full {{ $attempt->passed ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ min(100, max(0, $attempt->score)) }}%;"></div>
                                        </div>
                                    </div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400 mt-1">
                                        {{ $attempt->correct_answers ?? 0 }} / {{ $attempt->total_questions ?? 0 }} {{ __('Correct') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($attempt->passed)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider border border-emerald-500/20 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            {{ __('Passed') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 text-xs font-black uppercase tracking-wider border border-rose-500/20 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            {{ __('Failed') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                        {{ $attempt->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                                        {{ $attempt->created_at->format('h:i A') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($attempt->time_taken)
                                        <div class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ gmdate('i:s', $attempt->time_taken) }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('student.quizzes.result', $attempt) }}" class="btn-ghost inline-flex items-center justify-center p-2 rounded-xl text-primary-500 hover:bg-primary-500/10 hover:text-primary-600 transition-colors shadow-sm border border-transparent hover:border-primary-500/20" title="{{ __('View Details') }}">
                                        <span class="mr-2 text-sm font-bold">{{ __('Details') }}</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 mb-6 border border-slate-200 dark:border-white/5 shadow-inner">
                                        <span class="text-4xl">📝</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Quiz Attempts Yet') }}</h3>
                                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-sm mx-auto">
                                        {{ __('You haven\'t taken any quizzes yet. Complete lessons and take their knowledge checks to see your progress here.') }}
                                    </p>
                                    <a href="{{ route('student.courses.my-courses') }}" class="btn-primary ripple-btn inline-flex">
                                        {{ __('Start Learning') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($attempts) && method_exists($attempts, 'links') && $attempts->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
