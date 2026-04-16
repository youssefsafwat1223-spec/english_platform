@extends('layouts.admin')
@section('title', __('Edit Course'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Edit Course') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Update course details') }}</p>
        </div>

        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf @method('PUT')
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    @if($course->thumbnail)
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Current Thumbnail') }}</label>
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-48 h-32 object-cover rounded-xl">
                        </div>
                    @endif
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Course Title *') }}</label>
                        <input type="text" id="title" name="title" class="input-glass @error('title') border-red-500 @enderror" value="{{ old('title', $course->title) }}" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="short_description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Short Description') }}</label>
                        <input type="text" id="short_description" name="short_description" class="input-glass @error('short_description') border-red-500 @enderror" value="{{ old('short_description', $course->short_description) }}" maxlength="500">
                        @error('short_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Full Description') }}</label>
                        <textarea id="description" name="description" rows="6" class="input-glass @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Price (SAR) *') }}</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" class="input-glass @error('price') border-red-500 @enderror" value="{{ old('price', $course->price) }}" required>
                        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Payment Type') }}</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_type" value="full" class="accent-primary-500"
                                    {{ old('payment_type', $course->payment_type ?? 'full') === 'full' ? 'checked' : '' }}>
                                <span class="text-sm" style="color: var(--color-text);">دفعة واحدة</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="payment_type" value="installment" class="accent-primary-500"
                                    {{ old('payment_type', $course->payment_type ?? 'full') === 'installment' ? 'checked' : '' }}>
                                <span class="text-sm" style="color: var(--color-text);">3 أقساط شهرية</span>
                            </label>
                        </div>
                        @error('payment_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Update Thumbnail') }}</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="input-glass @error('thumbnail') border-red-500 @enderror">
                        @error('thumbnail')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="intro_video_url" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Intro Video URL') }}</label>
                        <input type="url" id="intro_video_url" name="intro_video_url" class="input-glass @error('intro_video_url') border-red-500 @enderror" value="{{ old('intro_video_url', $course->intro_video_url) }}" placeholder="{{ __('https://...') }}">
                        @error('intro_video_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="estimated_duration_weeks" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Estimated Study Duration (weeks)') }}</label>
                        <input type="number" id="estimated_duration_weeks" name="estimated_duration_weeks" min="1" class="input-glass @error('estimated_duration_weeks') border-red-500 @enderror" value="{{ old('estimated_duration_weeks', $course->estimated_duration_weeks) }}">
                        @error('estimated_duration_weeks')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Shown to students as a suggested pace only. It does not expire access.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl mb-4 border" style="background: var(--color-surface-mixed); border-color: var(--color-border); margin-top: 1.5rem;">
                        <h3 class="text-lg font-bold mb-3" style="color: var(--color-text);">{{ __('Exam & Prerequisite Settings') }}</h3>
                        
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="is_exam" name="is_exam" value="1" {{ old('is_exam', $course->is_exam) ? 'checked' : '' }} class="w-4 h-4 text-secondary-500 focus:ring-secondary-500 rounded" style="border-color: var(--color-border);">
                            <label for="is_exam" class="ml-2 font-semibold" style="color: var(--color-text);">{{ __('This content is an Exam / Test (Changes Student UI)') }}</label>
                        </div>
                        
                        <div>
                            <label for="prerequisite_course_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Prerequisite Course/Exam (Locks this course until prerequisite is passed)') }}</label>
                            <select id="prerequisite_course_id" name="prerequisite_course_id" class="input-glass @error('prerequisite_course_id') border-red-500 @enderror">
                                <option value="">{{ __('None (Open access)') }}</option>
                                @foreach($allCourses as $c)
                                    <option value="{{ $c->id }}" {{ old('prerequisite_course_id', $course->prerequisite_course_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }} {!! $c->is_exam ? '<span class="text-xs text-secondary-500">(Exam)</span>' : '' !!}
                                    </option>
                                @endforeach
                            </select>
                            @error('prerequisite_course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="p-4 rounded-xl mb-4 border" style="background: var(--color-surface-mixed); border-color: var(--color-border); margin-top: 1.5rem;">
                        <h3 class="text-lg font-bold mb-3" style="color: var(--color-text);">{{ __('Exam & Prerequisite Settings') }}</h3>
                        
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="is_exam" name="is_exam" value="1" {{ old('is_exam', $course->is_exam) ? 'checked' : '' }} class="w-4 h-4 text-secondary-500 focus:ring-secondary-500 rounded" style="border-color: var(--color-border);">
                            <label for="is_exam" class="ml-2 font-semibold" style="color: var(--color-text);">{{ __('This content is an Exam / Test (Changes Student UI)') }}</label>
                        </div>
                        
                        <div>
                            <label for="prerequisite_course_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Prerequisite Course/Exam (Locks this course until prerequisite is passed)') }}</label>
                            <select id="prerequisite_course_id" name="prerequisite_course_id" class="input-glass @error('prerequisite_course_id') border-red-500 @enderror">
                                <option value="">{{ __('None (Open access)') }}</option>
                                @foreach($allCourses as $c)
                                    <option value="{{ $c->id }}" {{ old('prerequisite_course_id', $course->prerequisite_course_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }} {!! $c->is_exam ? '<span class="text-xs text-secondary-500">(Exam)</span>' : '' !!}
                                    </option>
                                @endforeach
                            </select>
                            @error('prerequisite_course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                        <label for="is_active" class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Course is active') }}</label>
                    </div>
                </div>
                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.show', $course) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading">
                        <span x-show="!loading">{{ __('Update Course') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Updating...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
