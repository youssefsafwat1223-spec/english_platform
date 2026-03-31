@extends('layouts.app')

@section('title', __('ui.certificates.verification_title') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-student.card padding="py-12 px-6 sm:px-8" class="text-center shadow-xl bg-white/80 dark:bg-slate-900/80" data-aos="zoom-in">
                <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-rose-500/10 text-rose-500 ring-8 ring-rose-500/5">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h1 class="text-2xl font-extrabold mb-4 text-slate-900 dark:text-white">{{ __('ui.certificates.verification_failed_title') }}</h1>
                <p class="mb-6 text-slate-600 dark:text-slate-300">{{ $message ?? __('ui.certificates.failed_message') }}</p>

                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('home') }}" class="btn-primary ripple-btn">{{ __('ui.certificates.go_home') }}</a>
                    <a href="{{ route('student.courses.index') }}" class="btn-secondary">{{ __('ui.certificates.browse_courses') }}</a>
                </div>
        </x-student.card>
    </div>
</div>
@endsection

