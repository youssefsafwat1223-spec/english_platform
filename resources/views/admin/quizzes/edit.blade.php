@extends('layouts.admin')
@section('title', __('Edit Quiz'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Edit Quiz') }}</span></h1>
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Quiz') }}</a>
        </div>
        @if ($errors->any())
        <div class="glass-card overflow-hidden mb-6"><div class="glass-card-body"><ul class="list-disc list-inside text-red-500 text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
        @endif
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf @method('PUT')
            <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label for="course_id" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Course *') }}</label>
                        <select id="course_id" name="course_id" class="input-glass" required>
                            <option value="">{{ __('Select Course') }}</option>
                            @foreach($courses as $course)<option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach
                        </select>
                        @error('course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lesson_id" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Lesson (Optional)') }}</label>
                        <select id="lesson_id" name="lesson_id" class="input-glass"><option value="">{{ __('None (Final Exam)') }}</option></select>
                        @error('lesson_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="quiz_type" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Quiz Type *') }}</label>
                        <select id="quiz_type" name="quiz_type" class="input-glass" required>
                            <option value="lesson" {{ old('quiz_type', $quiz->quiz_type) === 'lesson' ? 'selected' : '' }}>{{ __('Lesson Quiz') }}</option>
                            <option value="final_exam" {{ old('quiz_type', $quiz->quiz_type) === 'final_exam' ? 'selected' : '' }}>{{ __('Final Exam') }}</option>
                        </select>
                        @error('quiz_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Quiz Title *') }}</label>
                        <input id="title" type="text" name="title" class="input-glass" value="{{ old('title', $quiz->title) }}" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Description') }}</label>
                        <textarea id="description" name="description" rows="3" class="input-glass">{{ old('description', $quiz->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Duration (min) *') }}</label>
                            <input id="duration_minutes" type="number" name="duration_minutes" class="input-glass" value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" required>
                            @error('duration_minutes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="passing_score" class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%) *') }}</label>
                            <input id="passing_score" type="number" name="passing_score" class="input-glass" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                            @error('passing_score')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach([['is_active', 'Active quiz', $quiz->is_active], ['allow_retake', 'Allow retake', $quiz->allow_retake], ['show_results_immediately', 'Show results immediately', $quiz->show_results_immediately], ['enable_audio', 'Enable audio for questions', $quiz->enable_audio], ['audio_auto_play', 'Auto-play question audio', $quiz->audio_auto_play]] as $cb)
                        <div class="flex items-center">
                            <input type="hidden" name="{{ $cb[0] }}" value="0">
                            <input type="checkbox" id="{{ $cb[0] }}" name="{{ $cb[0] }}" value="1" class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);" {{ old($cb[0], $cb[2]) ? 'checked' : '' }}>
                            <label for="{{ $cb[0] }}" class="ml-2 text-sm" style="color: var(--color-text);">{{ $cb[1] }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
                <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Select Questions') }}</h3></div>
                <div class="glass-card-body">
                    <div id="questionsContainer" class="text-center py-8 text-sm" style="color: var(--color-text-muted);">{{ __('Select a course first to load questions') }}</div>
                    @error('questions')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-between">
                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                    <span x-show="!loading">{{ __('Update Quiz') }}</span><span x-show="loading">{{ __('Updating...') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lessons = @json($lessonsForJs);
    const selectedQuestions = @json(old('questions', $quiz->questions->pluck('id')->all()));
    const courseSelect = document.getElementById('course_id');
    const lessonSelect = document.getElementById('lesson_id');
    const quizTypeSelect = document.getElementById('quiz_type');
    const questionsContainer = document.getElementById('questionsContainer');
    const escapeHtml = (value) => {{ __('String(value).replace(/&/g, \'&amp;\').replace(/') }}</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    const renderLessons = (courseId) => {
        lessonSelect.innerHTML = '<option value="">{{ __('None (Final Exam)') }}</option>';
        lessons.filter((l) => String(l.course_id) === String(courseId)).forEach((l) => {
            const o = document.createElement('option'); o.value = l.id; o.textContent = l.title; lessonSelect.appendChild(o);
        });
    };
    const setQuizTypeFromLesson = () => { quizTypeSelect.value = lessonSelect.value ? 'lesson' : 'final_exam'; toggleLessonState(); };
    const toggleLessonState = () => { if (quizTypeSelect.value === 'final_exam') lessonSelect.value = ''; };
    const loadQuestions = (courseId, lessonId) => {
        if (!courseId) { questionsContainer.innerHTML = 'Select a course first to load questions'; return; }
        const url = lessonId ? `/admin/courses/${courseId}/lessons/${lessonId}/questions` : `/admin/courses/${courseId}/questions`;
        fetch(url).then(r => r.json()).then(questions => {
            if (!questions.length) { questionsContainer.innerHTML = 'No questions found for this course.'; return; }
            const selected = selectedQuestions.map(String);
            let html = '<div class="space-y-3">';
            questions.forEach(q => {
                const checked = selected.includes(String(q.id)) ? 'checked' : '';
                const diffColor = q.difficulty === 'easy' ? 'emerald' : (q.difficulty === 'medium' ? 'amber' : 'red');
                html += `<label class="flex items-start p-3 rounded-xl cursor-pointer transition-colors" style="background: var(--color-surface-hover);">
                    <input type="checkbox" name="questions[]" value="${q.id}" class="mt-1 mr-3 w-4 h-4 text-primary-500 rounded" ${checked}>
                    <div class="flex-1"><div class="font-bold text-sm" style="color: var(--color-text);">${escapeHtml(q.question_text)}</div>
                    <div class="text-xs mt-1"><span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-${diffColor}-500/10 text-${diffColor}-500 text-[10px] font-bold">${escapeHtml(q.difficulty)}</span>
                    ${q.has_audio ? '<span class="ml-2" style="color: var(--color-text-muted);">{{ __('🔊 Audio') }}</span>' : ''}</div></div></label>`;
            });
            html += '</div>'; questionsContainer.innerHTML = html;
        }).catch(() => { questionsContainer.innerHTML = 'Failed to load questions.'; });
    };
    courseSelect.addEventListener('change', () => { renderLessons(courseSelect.value); loadQuestions(courseSelect.value, lessonSelect.value); });
    lessonSelect.addEventListener('change', () => { setQuizTypeFromLesson(); loadQuestions(courseSelect.value, lessonSelect.value); });
    quizTypeSelect.addEventListener('change', toggleLessonState);
    const initialCourse = courseSelect.value;
    const initialLesson = @json(old('lesson_id', $quiz->lesson_id));
    if (initialCourse) { renderLessons(initialCourse); if (initialLesson) lessonSelect.value = initialLesson; setQuizTypeFromLesson(); loadQuestions(initialCourse, lessonSelect.value); } else { toggleLessonState(); }
});
</script>
@endpush
@endsection
