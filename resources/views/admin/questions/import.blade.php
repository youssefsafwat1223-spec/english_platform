@extends('layouts.admin')
@section('title', __('Import Questions'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Import Questions') }}</span></h1>
            <a href="{{ route('admin.questions.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('? Back to Questions') }}</a>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('CSV Format') }}</h3></div>
            <div class="glass-card-body">
                <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __('Upload a CSV file with the following columns:') }}</p>
                <div class="p-4 rounded-xl font-mono text-xs" style="background: var(--color-surface-hover); color: var(--color-text);">{{ __('course_id,lesson_id,question_text,question_type,difficulty,option_a,option_b,option_c,option_d,correct_answer,explanation') }}</div>
                <p class="text-xs mt-2" style="color: var(--color-text-muted);">* {{ __('If course_id and lesson_id are empty in the CSV, the questions will be assigned to the course/lesson you select below (if any).') }}</p>
                <div class="flex gap-4 mt-4">
                    <a href="/sample-questions.csv" class="btn-secondary inline-block" download>{{ __('Download Sample CSV') }}</a>
                    <a href="{{ route('admin.questions.reference') }}" class="btn-primary inline-flex items-center" target="_blank">{{ __('View ID Reference Guide') }} ↗</a>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.questions.process-import') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body">
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('CSV File *') }}</label>
                    <input type="file" name="csv_file" accept=".csv" class="input-glass" required>
                    @error('csv_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.questions.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                        <span x-show="!loading">{{ __('Import Questions') }}</span><span x-show="loading">{{ __('Importing...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
