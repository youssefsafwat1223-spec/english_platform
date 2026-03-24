@extends('layouts.admin')
@section('title', __('Create Course'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Create New Course') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Fill in the details to create a new course') }}</p>
        </div>

        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Course Title *') }}</label>
                        <input type="text" id="title" name="title" class="input-glass @error('title') border-red-500 @enderror" value="{{ old('title') }}" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="short_description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Short Description') }}</label>
                        <input type="text" id="short_description" name="short_description" class="input-glass @error('short_description') border-red-500 @enderror" value="{{ old('short_description') }}" maxlength="500">
                        @error('short_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Brief overview (max 500 characters)') }}</p>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Full Description') }}</label>
                        <textarea id="description" name="description" rows="6" class="input-glass @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Price (SAR) *') }}</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" class="input-glass @error('price') border-red-500 @enderror" value="{{ old('price') }}" required>
                        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Course Thumbnail') }}</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="input-glass @error('thumbnail') border-red-500 @enderror">
                        @error('thumbnail')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Recommended size: 1200x630px') }}</p>
                    </div>
                    <div>
                        <label for="intro_video_url" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Intro Video URL') }}</label>
                        <input type="url" id="intro_video_url" name="intro_video_url" class="input-glass @error('intro_video_url') border-red-500 @enderror" value="{{ old('intro_video_url') }}" placeholder="{{ __('https://...') }}">
                        @error('intro_video_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="estimated_duration_weeks" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Estimated Duration (weeks)') }}</label>
                        <input type="number" id="estimated_duration_weeks" name="estimated_duration_weeks" min="1" class="input-glass @error('estimated_duration_weeks') border-red-500 @enderror" value="{{ old('estimated_duration_weeks') }}">
                        @error('estimated_duration_weeks')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                        <label for="is_active" class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Publish course immediately') }}</label>
                    </div>
                </div>
                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading">
                        <span x-show="!loading">{{ __('Create Course') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Creating...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection