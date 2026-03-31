@extends('layouts.app')

@php
    $currentUser = auth()->user();
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', ($isArabic ? 'لوحة المتصدرين' : 'Leaderboard') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <x-student.page-header
            title="{!! $isArabic ? 'أفضل' : 'Top' !!} <span class='text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500'>{{ $isArabic ? 'المتصدرين' : 'Leaderboard' }}</span>"
            subtitle="{{ $isArabic ? 'ترتيب أفضل الطلاب حسب النقاط. اعرف مكانك الحالي بين بقية الطلاب.' : 'Top students by points. See where you stand among your peers!' }}"
            badge="{{ $isArabic ? 'الترتيب' : 'Rankings' }}"
            badgeIcon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h8v3a4 4 0 0 1-8 0V4Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6H4a2 2 0 0 0 2 2h1m11-2h2a2 2 0 0 1-2 2h-1M12 11v4m-3 5h6"/></svg>'
            badgeColor="violet"
        >
            <x-slot name="actions">
                <a href="{{ route('student.profile.show') }}" class="btn-ghost flex items-center justify-center gap-2 px-6 py-3 font-bold rounded-xl w-full sm:w-auto transition-colors shadow-sm bg-white/10 hover:bg-white/20 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 group">
                    <svg class="w-5 h-5 {{ $isArabic ? 'ml-2 mr-0 group-hover:translate-x-1 rotate-180' : 'mr-2 group-hover:-translate-x-1' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ $isArabic ? 'العودة إلى الملف الشخصي' : 'Back to Profile' }}
                </a>
            </x-slot>
        </x-student.page-header>

        {{-- Your Rank --}}
        <x-student.card padding="p-6" class="text-center gradient-border" data-aos="fade-up">
            <div class="text-2xl font-extrabold text-primary-500 mb-1">{{ $isArabic ? 'ترتيبك الحالي' : 'Your Rank' }}: #{{ $userRank }}</div>
            <div class="text-slate-500 dark:text-slate-400 font-medium">{{ $currentUser->total_points }} {{ $isArabic ? 'نقطة' : 'points' }}</div>
        </x-student.card>

        {{-- Leaderboard Table --}}
        <x-student.card padding="p-0" data-aos="fade-up" data-aos-delay="100">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ $isArabic ? 'الترتيب' : 'Rank' }}</th>
                            <th>{{ $isArabic ? 'الطالب' : 'Student' }}</th>
                            <th>{{ $isArabic ? 'النقاط' : 'Points' }}</th>
                            <th>{{ $isArabic ? 'السلسلة' : 'Streak' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $index => $student)
                            <tr class="{{ $student->id === $currentUser->id ? 'bg-primary-500/10' : '' }}">
                                <td>
                                    @if($index < 3)
                                        @php
                                            $rankBadgeClasses = [
                                                'bg-amber-500/15 text-amber-500 border-amber-500/30',
                                                'bg-slate-400/15 text-slate-500 border-slate-400/30',
                                                'bg-orange-500/15 text-orange-500 border-orange-500/30',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border font-black text-sm {{ $rankBadgeClasses[$index] }}">
                                            {{ $index + 1 }}
                                        </span>
                                    @else
                                        <span class="font-bold text-slate-900 dark:text-white">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-sm font-bold">{{ substr($student->name, 0, 1) }}</div>
                                        <span class="font-semibold {{ $student->id === $currentUser->id ? 'text-primary-500' : 'text-slate-900 dark:text-white' }}">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="font-bold text-primary-500">{{ $student->total_points }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-slate-700 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c1.7 2 3 4 3 6a3 3 0 1 1-6 0c0-1.2.4-2.4 1.2-3.7M12 21a6 6 0 0 0 6-6c0-3.5-2.3-5.7-4.3-8.2-.3 2.1-1.1 3.4-2.7 5.2-1-1-1.6-2.1-1.8-3.5C7.1 10.2 6 12.4 6 15a6 6 0 0 0 6 6Z"/></svg>
                                        {{ $student->current_streak }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-student.card>
    </div>
</div>
@endsection
