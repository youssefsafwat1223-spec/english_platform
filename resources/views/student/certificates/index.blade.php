@extends('layouts.app')

@section('title', __('My Certificates') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-500/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <x-student.page-header
            title="{{ __('Your') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500'>{{ __('Certificates') }}</span>"
            subtitle="{{ __('Download or share your earned certificates to showcase your English mastery.') }}"
            badge="{{ __('Certificates') }}"
            badgeColor="violet"
            badgeIcon="<svg class='h-4 w-4' fill='none' stroke='currentColor' viewBox='0 0 24 24' aria-hidden='true'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 14l9-5-9-5-9 5 9 5z'/>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'/>
            </svg>"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @php
                $certStats = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>', 'value' => $stats['total_certificates'] ?? 0, 'label' => 'Total Certificates', 'color' => 'primary'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>', 'value' => round($stats['average_score'] ?? 0) . '%', 'label' => 'Average Score', 'color' => 'emerald'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>', 'value' => $stats['total_downloads'] ?? 0, 'label' => 'Total Downloads', 'color' => 'accent'],
                ];
            @endphp
            @foreach($certStats as $index => $s)
                <x-student.card padding="p-6" class="flex flex-row items-center gap-6 group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border-t-2 border-t-{{ $s['color'] }}-500/50" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="w-16 h-16 rounded-2xl bg-{{ $s['color'] }}-500/10 text-{{ $s['color'] }}-500 flex items-center justify-center shrink-0 shadow-inner group-hover:bg-{{ $s['color'] }}-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $s['icon'] !!}</svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-1 group-hover:text-{{ $s['color'] }}-500 transition-colors">{{ $s['value'] }}</div>
                        <div class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __($s['label']) }}</div>
                    </div>
                </x-student.card>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($certificates as $index => $certificate)
                <x-student.card padding="p-0" class="group hover:shadow-2xl hover:shadow-primary-500/10 transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="p-6 md:p-8 flex-1 relative z-10">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 opacity-5 dark:opacity-10 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">
                            <svg class="h-32 w-32 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>

                        <div class="flex items-start justify-between mb-6 relative z-10">
                            <div class="flex-1 pr-4">
                                <h3 class="text-2xl font-bold mb-3 text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors">{{ $certificate->course->title }}</h3>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold text-slate-600 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $certificate->issued_at->format('M d, Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold text-slate-600 dark:text-slate-300">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        {{ $certificate->final_score }}%
                                    </span>
                                    @php $gradeColor = $certificate->final_score >= 90 ? 'emerald' : ($certificate->final_score >= 80 ? 'primary' : 'blue'); @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-{{ $gradeColor }}-500/10 border border-{{ $gradeColor }}-500/20 text-{{ $gradeColor }}-600 dark:text-{{ $gradeColor }}-400 text-sm font-extrabold uppercase tracking-widest">{{ $certificate->grade }}</span>
                                </div>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-primary-500/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        </div>

                        <div class="rounded-xl p-4 mb-6 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 relative z-10 flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ __('Certificate ID') }}</div>
                                <div class="font-mono font-bold text-slate-900 dark:text-white">{{ $certificate->certificate_id }}</div>
                            </div>
                            <button class="text-slate-400 hover:text-primary-500 transition-colors" title="{{ __('Copy ID') }}" onclick="navigator.clipboard.writeText('{{ $certificate->certificate_id }}'); window.showNotification(__('Certificate ID copied'), 'success');">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-6 text-sm font-semibold text-slate-500 dark:text-slate-400 mb-6 relative z-10">
                            <span class="flex items-center gap-2" title="{{ __('Views') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ $certificate->view_count }}
                            </span>
                            <span class="flex items-center gap-2" title="{{ __('Downloads') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ $certificate->download_count }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-3 relative z-10 w-full mt-auto">
                            <div class="flex gap-3">
                                <a href="{{ route('student.certificates.show', $certificate) }}" class="btn-ghost flex-1 py-3 justify-center border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-750 font-bold">{{ __('View') }}</a>
                                <a href="{{ route('student.certificates.download', $certificate) }}" class="btn-primary ripple-btn flex-1 py-3 justify-center shadow-lg shadow-primary-500/30 flex items-center gap-2 font-bold bg-gradient-to-r from-primary-600 to-accent-500 border-0 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    {{ __('Download') }}
                                </a>
                            </div>
                            <div class="flex gap-3">
                                @if(!$certificate->linkedin_shared)
                                    <a href="{{ route('student.certificates.share-linkedin', $certificate) }}" class="btn-secondary flex-1 py-3 justify-center text-[#0077b5] border-[#0077b5]/30 hover:bg-[#0077b5]/10 bg-white dark:bg-slate-800 font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                        {{ __('LinkedIn') }}
                                    </a>
                                @endif
                                <form action="{{ route('student.certificates.send-email', $certificate) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full btn-secondary py-3 justify-center text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 bg-white dark:bg-slate-800 font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ __('Email') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </x-student.card>
            @empty
                <div class="col-span-full" data-aos="fade-up">
                    <x-student.card padding="p-12 lg:p-16" class="text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-24 h-24 mb-6 rounded-3xl bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center border border-slate-200 dark:border-white/5 shadow-inner">
                            <svg class="h-14 w-14 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3">{{ __('No certificates yet') }}</h3>
                        <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-md font-medium leading-relaxed">{{ __('Complete a course and pass its final exam to earn your first prestigious verified certificate!') }}</p>
                        <a href="{{ route('student.courses.my-courses') }}" class="btn-primary ripple-btn px-8 py-3.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-primary-500/25">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ __('Explore Courses') }}
                        </a>
                    </x-student.card>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
