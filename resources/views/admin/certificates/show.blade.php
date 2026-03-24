@extends('layouts.admin')
@section('title', __('Certificate Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Certificate Details') }}</span></h1>
            <a href="{{ route('admin.certificates.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Certificates') }}</a>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([[__('Certificate ID'), $certificate->certificate_id, true], ['Student', $certificate->user->name, false], ['Course', $certificate->course->title, false], ['Final Score', $certificate->final_score.'%', false], ['Grade', $certificate->grade, false], ['Issued', $certificate->issued_at->format('M d, Y'), false], ['Downloads', $certificate->download_count, false], ['Views', $certificate->view_count, false]] as [$label, $val, $mono])
                    <div>
                        <div class="text-xs font-bold mb-1" style="color: var(--color-text-muted);">{{ $label }}</div>
                        <div class="font-bold text-sm {{ $mono ? 'font-mono text-primary-500' : '' }}" style="{{ !$mono ? 'color: var(--color-text);' : '' }}">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Certificate File') }}</h3></div>
            <div class="glass-card-body">
                @if($certificate->pdf_path)
                <div class="mb-4 flex flex-wrap gap-2">
                    <a href="{{ Storage::url($certificate->pdf_path) }}" class="btn-primary ripple-btn" target="_blank">{{ __('Open PDF') }}</a>
                    <a href="{{ Storage::url($certificate->pdf_path) }}" class="btn-secondary" download>{{ __('Download PDF') }}</a>
                    <a href="{{ route('certificates.verify', $certificate->certificate_id) }}" class="btn-secondary" target="_blank">{{ __('Verify') }}</a>
                </div>
                <iframe src="{{ Storage::url($certificate->pdf_path) }}" class="w-full h-96 rounded-xl" style="border: 1px solid var(--color-border);"></iframe>
                @else
                <p class="mb-4" style="color: var(--color-text-muted);">{{ __('Certificate PDF not available.') }}</p>
                <a href="{{ route('certificates.verify', $certificate->certificate_id) }}" class="btn-secondary" target="_blank">{{ __('Verify') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
