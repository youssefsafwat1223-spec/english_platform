@extends('layouts.app')

@section('title', __('Certificate') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">Certificate</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $certificate->course->title }}</p>
        </div>

        <div class="glass-card overflow-hidden gradient-border mb-6" data-aos="fade-up">
            <div class="glass-card-body space-y-4">
                <div class="flex flex-wrap gap-6 text-sm">
                    <div>
                        <span class="font-bold" style="color: var(--color-text);">Certificate ID:</span>
                        <span class="font-mono text-primary-500 ml-1">{{ $certificate->certificate_id }}</span>
                    </div>
                    <div>
                        <span class="font-bold" style="color: var(--color-text);">Issued:</span>
                        <span style="color: var(--color-text-muted);" class="ml-1">{{ $certificate->issued_at?->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="font-bold" style="color: var(--color-text);">Final Score:</span>
                        <span class="text-emerald-500 font-bold ml-1">{{ $certificate->final_score }}%</span>
                    </div>
                    <div>
                        <span class="font-bold" style="color: var(--color-text);">Grade:</span>
                        <span class="badge-primary ml-1">{{ $certificate->grade }}</span>
                    </div>
                </div>

                @if($certificate->pdf_url)
                    <iframe src="{{ $certificate->pdf_url }}" class="w-full h-96 rounded-xl" style="border: 1px solid var(--glass-border);"></iframe>
                @else
                    <div class="text-center py-12 rounded-xl" style="background: var(--color-surface-hover);">
                        <div class="text-4xl mb-3">📜</div>
                        <p style="color: var(--color-text-muted);">Certificate PDF is not available yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-3" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('student.certificates.download', $certificate) }}" class="btn-primary ripple-btn" target="_blank">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </a>
            @if(!$certificate->linkedin_shared)
                <a href="{{ route('student.certificates.share-linkedin', $certificate) }}" class="btn-secondary">Share on LinkedIn</a>
            @endif
            <button type="button" onclick="copyVerificationLink()" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Copy Verification Link
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
        if (window.showNotification) window.showNotification('Verification link copied!', 'success');
    } else {
        prompt('Copy this link:', link);
    }
}
</script>
@endpush
@endsection
