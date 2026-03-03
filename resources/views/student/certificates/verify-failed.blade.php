@extends('layouts.app')

@section('title', __('Certificate Verification'))

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="glass-card overflow-hidden text-center" data-aos="zoom-in">
            <div class="glass-card-body py-12">
                <div class="text-6xl mb-4">❌</div>
                <h1 class="text-2xl font-extrabold mb-4" style="color: var(--color-text);">Certificate Verification Failed</h1>
                <p class="mb-6" style="color: var(--color-text-muted);">{{ $message ?? 'We could not verify this certificate.' }}</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('home') }}" class="btn-primary ripple-btn">Go to Home</a>
                    <a href="{{ route('student.courses.index') }}" class="btn-secondary">Browse Courses</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
