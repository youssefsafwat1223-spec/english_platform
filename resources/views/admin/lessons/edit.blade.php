@extends('layouts.admin')
@section('title', __('Edit Lesson'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Edit Lesson') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
        </div>

        <form action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf @method('PUT')
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Title *') }}</label>
                        <input type="text" name="title" class="input-glass" value="{{ old('title', $lesson->title) }}" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="course_level_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('العنوان') }} <span class="text-red-500">*</span></label>
                        <select id="course_level_id" name="course_level_id" class="input-glass" required>
                            <option value="">{{ __('— اختار العنوان —') }}</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('course_level_id', $lesson->course_level_id) == $level->id ? 'selected' : '' }}>{{ $level->title }}</option>
                            @endforeach
                        </select>
                        @error('course_level_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" class="input-glass">{{ old('description', $lesson->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Video URL') }}</label>
                        <input type="url" name="video_url" class="input-glass" value="{{ old('video_url', $lesson->video_url) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Text Content') }}</label>
                        <textarea name="text_content" rows="10" class="input-glass">{{ old('text_content', $lesson->text_content) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Order Index') }}</label>
                        <input type="number" name="order_index" class="input-glass" value="{{ old('order_index', $lesson->order_index) }}">
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_free" value="1" {{ $lesson->is_free ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                            <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Free preview lesson') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="has_quiz" name="has_quiz" value="1" {{ old('has_quiz', $lesson->has_quiz) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                            <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Has quiz') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="has_pronunciation_exercise" name="has_pronunciation_exercise" value="1" {{ old('has_pronunciation_exercise', $lesson->has_pronunciation_exercise) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                            <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Has pronunciation exercise') }}</label>
                        </div>
                    </div>

                    @php
                        $quizMode = old('quiz_mode', $lesson->quiz ? 'questions' : 'questions');
                        $selectedQuestions = old('question_ids', $selectedQuestionIds ?? []);
                    @endphp

                    <div id="quizOptions" class="hidden">
                        <div class="rounded-xl p-4 space-y-4" style="background: var(--color-surface-hover);">
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Quiz Setup') }}</label>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="radio" name="quiz_mode" value="existing" {{ $quizMode === 'existing' ? 'checked' : '' }}> {{ __('Use existing quiz') }}
                                    </label>
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="radio" name="quiz_mode" value="questions" {{ $quizMode === 'questions' ? 'checked' : '' }}> {{ __('Create from questions') }}
                                    </label>
                                </div>
                            </div>
                            <div id="existingQuizBlock" class="hidden">
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Select Quiz') }}</label>
                                <select name="quiz_id" class="input-glass">
                                    <option value="">{{ __('Select a quiz') }}</option>
                                    @foreach($availableQuizzes as $quiz)
                                        <option value="{{ $quiz->id }}" {{ old('quiz_id', $lesson->quiz?->id) == $quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="questionQuizBlock" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Quiz Title') }}</label>
                                    <input type="text" name="quiz_title" class="input-glass" value="{{ old('quiz_title', $lesson->quiz?->title) }}" placeholder="{{ __('Lesson Quiz') }}">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Duration (minutes)</label>
                                        <input type="number" name="quiz_duration_minutes" class="input-glass" min="1" value="{{ old('quiz_duration_minutes', $lesson->quiz?->duration_minutes ?? 10) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Passing Score (%)</label>
                                        <input type="number" name="quiz_passing_score" class="input-glass" min="0" max="100" value="{{ old('quiz_passing_score', $lesson->quiz?->passing_score ?? 70) }}">
                                    </div>
                                    <div class="flex items-center gap-2 pt-6">
                                        <input type="hidden" name="quiz_allow_retake" value="0">
                                        <input type="checkbox" name="quiz_allow_retake" value="1" {{ old('quiz_allow_retake', ($lesson->quiz?->allow_retake ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        <span class="text-sm" style="color: var(--color-text);">{{ __('Allow retake') }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="hidden" name="quiz_show_results" value="0">
                                        <input type="checkbox" name="quiz_show_results" value="1" {{ old('quiz_show_results', ($lesson->quiz?->show_results_immediately ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        {{ __('Show results immediately') }}
                                    </label>
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="hidden" name="quiz_enable_audio" value="0">
                                        <input type="checkbox" name="quiz_enable_audio" value="1" {{ old('quiz_enable_audio', ($lesson->quiz?->enable_audio ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        {{ __('Enable audio') }}
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Select Questions') }}</label>
                                    <div class="max-h-64 overflow-y-auto rounded-xl p-3 space-y-2" style="background: var(--color-surface); border: 1px solid var(--color-border);">
                                        @forelse($availableQuestions as $question)
                                            <label class="flex items-start gap-2 text-sm" style="color: var(--color-text);">
                                                <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" {{ in_array($question->id, $selectedQuestions, true) ? 'checked' : '' }} class="mt-1 w-4 h-4 text-primary-500 rounded">
                                                <span>{{ $question->question_text }} <span class="text-xs" style="color: var(--color-text-muted);">({{ ucfirst($question->difficulty) }})</span></span>
                                            </label>
                                        @empty
                                            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('No questions found.') }}</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pronunciationOptions" class="hidden">
                        <div class="rounded-xl p-4 space-y-4" style="background: var(--color-surface-hover);">
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Sentence 1 *') }}</label>
                                <input type="text" name="pronunciation_sentence_1" class="input-glass" value="{{ old('pronunciation_sentence_1', $pronunciationExercise?->sentence_1) }}">
                                @error('pronunciation_sentence_1')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Sentence 2') }}</label>
                                <input type="text" name="pronunciation_sentence_2" class="input-glass" value="{{ old('pronunciation_sentence_2', $pronunciationExercise?->sentence_2) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Sentence 3') }}</label>
                                <input type="text" name="pronunciation_sentence_3" class="input-glass" value="{{ old('pronunciation_sentence_3', $pronunciationExercise?->sentence_3) }}">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Passing Score (%)</label>
                                    <input type="number" name="pronunciation_passing_score" class="input-glass" min="0" max="100" value="{{ old('pronunciation_passing_score', $pronunciationExercise?->passing_score ?? 70) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">Max Duration (seconds)</label>
                                    <input type="number" name="pronunciation_max_duration" class="input-glass" min="1" value="{{ old('pronunciation_max_duration', $pronunciationExercise?->max_duration_seconds ?? 10) }}">
                                </div>
                                <div class="flex items-center gap-2 pt-6">
                                    <input type="hidden" name="pronunciation_allow_retake" value="0">
                                    <input type="checkbox" name="pronunciation_allow_retake" value="1" {{ old('pronunciation_allow_retake', ($pronunciationExercise?->allow_retake ?? true) ? '1' : '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                    <span class="text-sm" style="color: var(--color-text);">{{ __('Allow retake') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading">
                        <span x-show="!loading">{{ __('Update Lesson') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Updating...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hasQuiz = document.getElementById('has_quiz');
        const hasPronunciation = document.getElementById('has_pronunciation_exercise');
        const quizOptions = document.getElementById('quizOptions');
        const pronunciationOptions = document.getElementById('pronunciationOptions');
        const quizModeInputs = document.querySelectorAll('input[name="quiz_mode"]');
        const existingQuizBlock = document.getElementById('existingQuizBlock');
        const questionQuizBlock = document.getElementById('questionQuizBlock');

        const toggleQuizOptions = () => {
            quizOptions.classList.toggle('hidden', !hasQuiz.checked);
            const selectedMode = document.querySelector('input[name="quiz_mode"]:checked')?.value;
            existingQuizBlock.classList.toggle('hidden', selectedMode !== 'existing');
            questionQuizBlock.classList.toggle('hidden', selectedMode !== 'questions');
        };
        const togglePronunciationOptions = () => {
            pronunciationOptions.classList.toggle('hidden', !hasPronunciation.checked);
        };
        hasQuiz.addEventListener('change', toggleQuizOptions);
        hasPronunciation.addEventListener('change', togglePronunciationOptions);
        quizModeInputs.forEach(input => input.addEventListener('change', toggleQuizOptions));
        toggleQuizOptions();
        togglePronunciationOptions();
    });
</script>
@endpush
@endsection
