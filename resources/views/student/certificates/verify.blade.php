@extends('layouts.app')

@section('title', __('ui.certificates.verification_title') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="student-container max-w-4xl relative z-10">
        <x-student.card padding="p-8 md:p-10" class="shadow-xl bg-white/80 dark:bg-slate-900/80 text-center" data-aos="zoom-in">
                <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500 ring-8 ring-emerald-500/5">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-emerald-500 mb-4">{{ __('ui.certificates.verified_title') }}</h1>

                @php
                    $fields = [
                        [__('ui.certificates.student_name'), $user->name],
                        [__('ui.certificates.course'), $course->title],
                        [__('ui.certificates.final_score'), $certificate->final_score . '%'],
                        [__('ui.certificates.grade'), $certificate->grade],
                        [__('ui.certificates.issue_date'), $certificate->issued_at->format('F d, Y')],
                        [__('ui.certificates.certificate_id'), $certificate->certificate_id],
                    ];
                @endphp

                <div class="rounded-2xl p-6 mb-6 text-left bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($fields as [$label, $value])
                            <div class="rounded-xl border border-slate-200/70 dark:border-white/5 bg-white/70 dark:bg-slate-900/40 p-4">
                                <div class="text-xs font-medium mb-1 text-slate-500 dark:text-slate-400">{{ $label }}</div>
                                <div class="font-bold {{ $label === __('ui.certificates.certificate_id') ? 'font-mono text-primary-500 break-all' : 'text-slate-900 dark:text-white' }}">
                                    {{ $value }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="mb-6 text-slate-600 dark:text-slate-300">
                    {{ __('ui.certificates.verified_message', ['platform' => config('app.name')]) }}
                </p>

                <a href="{{ route('home') }}" class="btn-primary ripple-btn">
                    {{ __('ui.certificates.visit_platform') }}
                </a>
        </x-student.card>
    </div>
</div>
@endsection





