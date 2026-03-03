@extends('layouts.admin')
@section('title', __('Create Question'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Create New Question') }}</span></h1>
            <a href="{{ route('admin.questions.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Questions') }}</a>
        </div>
        <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Course *') }}</label>
                            <select name="course_id" id="courseSelect" class="input-glass" required>
                                <option value="">{{ __('Select Course') }}</option>
                                @foreach($courses as $course)<option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach
                            </select>
                            @error('course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">Lesson (Optional)</label>
                            <select name="lesson_id" id="lessonSelect" class="input-glass">
                                <option value="">{{ __('No lesson') }}</option>
                                @foreach($lessons as $lesson)<option value="{{ $lesson->id }}" data-course="{{ $lesson->course_id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>@endforeach
                            </select>
                            @error('lesson_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Question Text *') }}</label>
                        <textarea name="question_text" rows="3" class="input-glass" required>{{ old('question_text') }}</textarea>
                        @error('question_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Question Type *') }}</label>
                            <select name="question_type" class="input-glass" required>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>{{ __('Multiple Choice') }}</option>
                                <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>{{ __('True/False') }}</option>
                                <option value="fill_blank" {{ old('question_type') == 'fill_blank' ? 'selected' : '' }}>{{ __('Fill in the Blank') }}</option>
                            </select>
                            @error('question_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Difficulty *') }}</label>
                            <select name="difficulty" class="input-glass" required>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                            </select>
                            @error('difficulty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @foreach(['option_a' => 'Option A *', 'option_b' => 'Option B *', 'option_c' => 'Option C', 'option_d' => 'Option D'] as $field => $label)
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ $label }}</label>
                        <input type="text" name="{{ $field }}" class="input-glass" value="{{ old($field) }}" {{ str_contains($label, '*') ? 'required' : '' }}>
                        @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Correct Answer *') }}</label>
                        <select name="correct_answer" class="input-glass" required>
                            @foreach(['A','B','C','D'] as $opt)<option value="{{ $opt }}" {{ old('correct_answer') == $opt ? 'selected' : '' }}>{{ $opt }}</option>@endforeach
                        </select>
                        @error('correct_answer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Explanation') }}</label>
                        <textarea name="explanation" rows="3" class="input-glass">{{ old('explanation') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">Points (Optional)</label>
                        <input type="number" name="points" class="input-glass" min="1" value="{{ old('points') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">Upload Custom Audio (MP3/WAV)</label>
                        <input type="file" name="audio_file" accept="audio/*" class="input-glass">
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Leave empty to generate TTS audio.') }}</p>
                        @error('audio_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="generate_tts" value="1" {{ old('generate_tts', true) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                        <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Generate TTS audio if no custom file') }}</label>
                    </div>
                </div>
                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.questions.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                        <span x-show="!loading">{{ __('Create Question') }}</span><span x-show="loading">{{ __('Creating...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
(function() {
    const courseSelect = document.getElementById('courseSelect');
    const lessonSelect = document.getElementById('lessonSelect');
    function filterLessons() {
        const courseId = courseSelect.value;
        lessonSelect.querySelectorAll('option[data-course]').forEach(o => { o.hidden = courseId && o.getAttribute('data-course') !== courseId; });
    }
    courseSelect.addEventListener('change', filterLessons);
    filterLessons();
})();
</script>
@endpush
@endsection
