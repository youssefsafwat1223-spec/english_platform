@extends('layouts.app')

@section('title', __('Certificate') . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="student-container max-w-4xl relative z-10">
        <div class="text-center mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white"><span class="text-gradient">{{ __('Certificate') }}</span></h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">{{ $certificate->course->title }}</p>
        </div>

        <x-student.card padding="p-6 md:p-8" class="shadow-xl mb-6 space-y-5" data-aos="fade-up">
            <div class="flex flex-wrap gap-6 text-sm text-slate-600 dark:text-slate-300">
                <div>
                    <span class="font-bold text-slate-900 dark:text-white">{{ __('Certificate ID:') }}</span>
                    <span class="font-mono text-primary-500 ml-1">{{ $certificate->certificate_id }}</span>
                </div>
                <div>
                    <span class="font-bold text-slate-900 dark:text-white">{{ __('Issued:') }}</span>
                    <span class="ml-1 text-slate-500 dark:text-slate-400">{{ $certificate->issued_at?->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-bold text-slate-900 dark:text-white">{{ __('Final Score:') }}</span>
                    <span class="text-emerald-500 font-bold ml-1">{{ $certificate->final_score }}%</span>
                </div>
                <div>
                    <span class="font-bold text-slate-900 dark:text-white">{{ __('Grade:') }}</span>
                    <span class="ml-1 inline-flex items-center rounded-full bg-primary-500/10 px-3 py-1 text-xs font-black uppercase tracking-[0.2em] text-primary-600 dark:text-primary-400">{{ $certificate->grade }}</span>
                </div>
            </div>

            @if($certificate->pdf_url)
                <iframe src="{{ $certificate->pdf_url }}" class="w-full h-96 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900" title="{{ __('Certificate') }}"></iframe>
            @else
                <div class="text-center py-12 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/60">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3v5h5"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">{{ $isArabic ? 'ملف الشهادة غير متاح بعد.' : 'Certificate PDF is not available yet.' }}</p>
                </div>
            @endif
        </x-student.card>

        <div class="flex flex-wrap justify-center gap-3" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('student.certificates.download', $certificate) }}" class="btn-primary ripple-btn" target="_blank">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                {{ $isArabic ? 'تنزيل PDF' : 'Download PDF' }}
            </a>
            @if(!$certificate->linkedin_shared)
                <a href="{{ route('student.certificates.share-linkedin', $certificate) }}" class="btn-secondary">{{ __('Share on LinkedIn') }}</a>
            @endif
            <button type="button" onclick="copyVerificationLink()" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                {{ $isArabic ? 'نسخ رابط التحقق' : 'Copy Verification Link' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyVerificationLink() {
    const link = '{{ $certificate->verification_url }}';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(link);
        if (window.showNotification) window.showNotification(@js($isArabic ? 'تم نسخ رابط التحقق.' : 'Verification link copied!'), 'success');
    } else {
        prompt(@js($isArabic ? 'انسخ هذا الرابط:' : 'Copy this link:'), link);
    }
}
</script>
@endpush
@endsection





