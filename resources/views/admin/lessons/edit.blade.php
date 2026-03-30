@extends('layouts.admin')
@section('title', __('Edit Lesson'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Edit Lesson') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($previousLesson)
                    <a href="{{ route('admin.courses.lessons.edit', [$course, $previousLesson]) }}" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2" title="{{ $previousLesson->title }}">
                        <i class="fas fa-chevron-right rtl:fa-chevron-left"></i> {{ __('Previous Lesson') }}
                    </a>
                @endif
                @if($nextLesson)
                    <a href="{{ route('admin.courses.lessons.edit', [$course, $nextLesson]) }}" class="btn-secondary py-2 px-4 text-sm flex items-center gap-2" title="{{ $nextLesson->title }}">
                        {{ __('Next Lesson') }} <i class="fas fa-chevron-left rtl:fa-chevron-right"></i>
                    </a>
                @endif
            </div>
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
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('VdoCipher Video ID') }}</label>
                        <input type="text" name="vdocipher_video_id" class="input-glass @error('vdocipher_video_id') border-red-500 @enderror" value="{{ old('vdocipher_video_id', $lesson->vdocipher_video_id) }}" placeholder="{{ __('e.g. 1234abcd5678efgh') }}">
                        @error('vdocipher_video_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('اختياري — لو عندك فيديو على VdoCipher، حط الـ Video ID هنا. لو موجود هيتم استخدامه بدل الـ Video URL.') }}</p>
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
                            <label class="ml-2 text-sm" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'يتضمن تمرين نطق' : 'Has pronunciation exercise' }}</label>
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
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Duration (minutes)') }}</label>
                                        <input type="number" name="quiz_duration_minutes" class="input-glass" min="1" value="{{ old('quiz_duration_minutes', $lesson->quiz?->duration_minutes ?? 10) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%)') }}</label>
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
                                <div x-data="inlineQuestionCreator()" x-init="init()">
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Select Questions') }}</label>
                                    <div class="max-h-64 overflow-y-auto rounded-xl p-3 space-y-2" id="questionsListContainer" style="background: var(--color-surface); border: 1px solid var(--color-border);">
                                        @forelse($availableQuestions as $question)
                                            <label class="flex items-start gap-2 text-sm" style="color: var(--color-text);">
                                                <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" {{ in_array($question->id, $selectedQuestions, true) ? 'checked' : '' }} class="mt-1 w-4 h-4 text-primary-500 rounded">
                                                <span>{{ $question->question_text }} <span class="text-xs" style="color: var(--color-text-muted);">({{ ucfirst($question->difficulty) }})</span></span>
                                            </label>
                                        @empty
                                            <p class="text-sm no-questions-msg" style="color: var(--color-text-muted);">{{ __('No questions found.') }}</p>
                                        @endforelse
                                    </div>

                                    {{-- Add Question Button --}}
                                    <button type="button" @click="showModal = true" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold text-white bg-primary-500 hover:bg-primary-600 transition-colors shadow-lg shadow-primary-500/30">
                                        <i class="fas fa-plus"></i> {{ __('إضافة سؤال جديد') }}
                                    </button>

                                    {{-- Modal --}}
                                    <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
                                        <div @click.away="showModal = false" class="glass-card w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                                            <div class="glass-card-body space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-lg font-bold" style="color: var(--color-text);">{{ __('إضافة سؤال جديد') }}</h3>
                                                    <button type="button" @click="showModal = false" class="text-xl" style="color: var(--color-text-muted);">&times;</button>
                                                </div>
                                                <input type="hidden" x-model="form.course_id">
                                                <div>
                                                    <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('نص السؤال *') }}</label>
                                                    <textarea x-model="form.question_text" rows="2" class="input-glass w-full"></textarea>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('نوع السؤال *') }}</label>
                                                        <select x-model="form.question_type" class="input-glass w-full" @change="onTypeChange()">
                                                            <option value="multiple_choice">{{ __('اختيار من متعدد') }}</option>
                                                            <option value="true_false">{{ __('صح / غلط') }}</option>
                                                            <option value="fill_blank">{{ __('أكمل الفراغ') }}</option>
                                                            <option value="drag_drop">{{ __('وصّل (سحب وإفلات)') }}</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الصعوبة *') }}</label>
                                                        <select x-model="form.difficulty" class="input-glass w-full">
                                                            <option value="easy">{{ __('سهل') }}</option>
                                                            <option value="medium">{{ __('متوسط') }}</option>
                                                            <option value="hard">{{ __('صعب') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Options A-D: shown for multiple_choice --}}
                                                <div class="grid grid-cols-2 gap-4" x-show="form.question_type === 'multiple_choice'">
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار A *') }}</label>
                                                        <input type="text" x-model="form.option_a" class="input-glass w-full">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار B *') }}</label>
                                                        <input type="text" x-model="form.option_b" class="input-glass w-full">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار C') }}</label>
                                                        <input type="text" x-model="form.option_c" class="input-glass w-full">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار D') }}</label>
                                                        <input type="text" x-model="form.option_d" class="input-glass w-full">
                                                    </div>
                                                </div>

                                                {{-- True/False: read-only options --}}
                                                <div x-show="form.question_type === 'true_false'" class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار A') }}</label>
                                                        <input type="text" x-model="form.option_a" class="input-glass w-full bg-gray-100" readonly>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الاختيار B') }}</label>
                                                        <input type="text" x-model="form.option_b" class="input-glass w-full bg-gray-100" readonly>
                                                    </div>
                                                </div>

                                                {{-- Fill Blank: show correct answer text input --}}
                                                <div x-show="form.question_type === 'fill_blank'">
                                                    <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الإجابة الصحيحة (نص) *') }}</label>
                                                    <input type="text" x-model="form.option_a" class="input-glass w-full" placeholder="{{ __('اكتب الإجابة الصحيحة هنا') }}">
                                                    <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('اكتب الكلمة أو العبارة الصحيحة التي تملأ الفراغ') }}</p>
                                                </div>

                                                {{-- Drag & Drop: pairs editor --}}
                                                <div x-show="form.question_type === 'drag_drop'" class="space-y-3">
                                                    <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الأزواج (وصّل العمود الأيسر بالأيمن) *') }}</label>
                                                    <template x-for="(pair, index) in form.matching_pairs" :key="index">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" x-model="pair.left" class="input-glass w-full" :placeholder="'{{ __('العنصر') }} ' + (index + 1)">
                                                            <span class="text-lg font-bold" style="color: var(--color-text-muted);">↔</span>
                                                            <input type="text" x-model="pair.right" class="input-glass w-full" :placeholder="'{{ __('المطابق') }} ' + (index + 1)">
                                                            <button type="button" @click="form.matching_pairs.splice(index, 1)" class="text-red-500 hover:text-red-700 text-lg shrink-0" x-show="form.matching_pairs.length > 2">&times;</button>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="form.matching_pairs.push({left: '', right: ''})" class="text-sm font-bold text-primary-500 hover:underline">+ {{ __('إضافة زوج جديد') }}</button>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4" x-show="form.question_type !== 'fill_blank' && form.question_type !== 'drag_drop'">
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الإجابة الصحيحة *') }}</label>
                                                        <select x-model="form.correct_answer" class="input-glass w-full">
                                                            <option value="A">A</option>
                                                            <option value="B">B</option>
                                                            <option value="C" x-show="form.question_type === 'multiple_choice'">C</option>
                                                            <option value="D" x-show="form.question_type === 'multiple_choice'">D</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('النقاط') }}</label>
                                                        <input type="number" x-model="form.points" min="1" class="input-glass w-full" placeholder="10">
                                                    </div>
                                                </div>
                                                <div x-show="form.question_type === 'fill_blank' || form.question_type === 'drag_drop'">
                                                    <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('النقاط') }}</label>
                                                    <input type="number" x-model="form.points" min="1" class="input-glass w-full" placeholder="10">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold mb-1" style="color: var(--color-text);">{{ __('الشرح (اختياري)') }}</label>
                                                    <textarea x-model="form.explanation" rows="2" class="input-glass w-full"></textarea>
                                                </div>
                                                <p x-show="errorMsg" x-text="errorMsg" class="text-red-500 text-sm font-bold"></p>
                                                <div class="flex justify-end gap-3 pt-2">
                                                    <button type="button" @click="showModal = false" class="btn-secondary">{{ __('إلغاء') }}</button>
                                                    <button type="button" @click="submitQuestion()" :disabled="saving" class="btn-primary ripple-btn">
                                                        <span x-show="!saving">{{ __('حفظ السؤال') }}</span>
                                                        <span x-show="saving">{{ __('جاري الحفظ...') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pronunciationOptions" class="hidden">
                        <div class="rounded-xl p-4 space-y-4" style="background: var(--color-surface-hover);">
                            @php
                                $existingVocabularyLines = old(
                                    'pronunciation_vocabulary_lines',
                                    collect($pronunciationExercise?->vocabulary_json ?? [])
                                        ->map(function ($item) {
                                            $parts = [
                                                $item['word'] ?? '',
                                                $item['pronunciation'] ?? '',
                                                $item['meaning_ar'] ?? '',
                                            ];

                                            return implode(' | ', array_filter($parts, fn ($value) => $value !== ''));
                                        })
                                        ->implode("\n")
                                );
                            @endphp
                            <p class="text-xs font-medium" style="color: var(--color-text-muted);">{{ app()->getLocale() === 'ar' ? 'استخدم الحقول بهذا الترتيب: كلمة، ثم جملة، ثم قطعة قصيرة.' : 'Use these fields in this order: word, then sentence, then a short passage.' }}</p>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'الكلمة *' : 'Word *' }}</label>
                                <input type="text" name="pronunciation_sentence_1" class="input-glass" value="{{ old('pronunciation_sentence_1', $pronunciationExercise?->sentence_1) }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: cake' : 'Example: cake' }}">
                                @error('pronunciation_sentence_1')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'الجملة' : 'Sentence' }}</label>
                                <input type="text" name="pronunciation_sentence_2" class="input-glass" value="{{ old('pronunciation_sentence_2', $pronunciationExercise?->sentence_2) }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: The cake tastes sweet.' : 'Example: The cake tastes sweet.' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'القطعة' : 'Passage' }}</label>
                                <input type="text" name="pronunciation_sentence_3" class="input-glass" value="{{ old('pronunciation_sentence_3', $pronunciationExercise?->sentence_3) }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: A short paragraph related to the lesson.' : 'Example: A short paragraph related to the lesson.' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'كلمات الدرس' : 'Lesson Vocabulary' }}</label>
                                <textarea name="pronunciation_vocabulary_lines" rows="6" class="input-glass" placeholder="{{ app()->getLocale() === 'ar' ? 'كل سطر بهذا الشكل: word | pronunciation | المعنى' : 'One item per line: word | pronunciation | Arabic meaning' }}">{{ $existingVocabularyLines }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ app()->getLocale() === 'ar' ? 'مثال: cake | /keik/ | كعكة' : 'Example: cake | /keik/ | Cake' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'شرح الجملة' : 'Sentence Explanation' }}</label>
                                <textarea name="pronunciation_sentence_explanation" rows="3" class="input-glass">{{ old('pronunciation_sentence_explanation', $pronunciationExercise?->sentence_explanation) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'شرح القطعة' : 'Passage Explanation' }}</label>
                                <textarea name="pronunciation_passage_explanation" rows="3" class="input-glass">{{ old('pronunciation_passage_explanation', $pronunciationExercise?->passage_explanation) }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ([1 => 'Word', 2 => 'Sentence', 3 => 'Passage'] as $audioIndex => $audioLabel)
                                    @php
                                        $audioColumn = "reference_audio_{$audioIndex}";
                                        $audioTitle = app()->getLocale() === 'ar'
                                            ? match ($audioIndex) {
                                                1 => 'صوت مرجعي للكلمة',
                                                2 => 'صوت مرجعي للجملة',
                                                default => 'صوت مرجعي للقطعة',
                                            }
                                            : "Reference Audio for {$audioLabel}";
                                    @endphp
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ $audioTitle }}</label>
                                        <input type="file" name="pronunciation_reference_audio_{{ $audioIndex }}" accept="audio/*" class="input-glass">
                                        @if($pronunciationExercise?->{$audioColumn})
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($pronunciationExercise->{$audioColumn}) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-xs font-semibold text-primary-500 hover:underline">
                                                <i class="fas fa-volume-up"></i>
                                                {{ app()->getLocale() === 'ar' ? 'تشغيل الملف الحالي' : 'Play current audio' }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%)') }}</label>
                                    <input type="number" name="pronunciation_passing_score" class="input-glass" min="0" max="100" value="{{ old('pronunciation_passing_score', $pronunciationExercise?->passing_score ?? 70) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Max Duration (seconds)') }}</label>
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

                    {{-- ─── Attachments Section ─── --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold" style="color: var(--color-text);">{{ __('المرفقات — Attachments') }}</label>

                        {{-- Existing Attachments --}}
                        @if($lesson->attachments && $lesson->attachments->count() > 0)
                        <div class="space-y-2">
                            <p class="text-xs font-semibold" style="color: var(--color-text-muted);">{{ __('المرفقات الحالية:') }}</p>
                            @foreach($lesson->attachments as $attachment)
                            <div class="flex items-center justify-between gap-3 p-3 rounded-xl" style="background: var(--color-surface-hover); border: 1px solid var(--color-border);">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg shrink-0" style="background: var(--color-primary-50); color: var(--color-primary);">
                                        📎
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm truncate" style="color: var(--color-text);">{{ $attachment->title }}</div>
                                        <div class="text-xs" style="color: var(--color-text-muted);">{{ strtoupper($attachment->file_type) }} — {{ $attachment->file_size }} KB</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ Storage::url($attachment->file_path) }}" class="text-primary-500 text-xs font-bold hover:underline" download>{{ __('تحميل') }}</a>
                                    <label class="flex items-center gap-1 text-xs text-red-500 font-bold cursor-pointer">
                                        <input type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}" class="w-3.5 h-3.5 rounded text-red-500">
                                        {{ __('حذف') }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Upload New Attachments --}}
                        <div>
                            <label for="attachments" class="block text-xs font-semibold mb-1" style="color: var(--color-text-muted);">{{ __('إضافة مرفقات جديدة (PDF, DOC, صور, إلخ)') }}</label>
                            <input type="file" id="attachments" name="attachments[]" multiple class="input-glass @error('attachments.*') border-red-500 @enderror">
                            @error('attachments.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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

    function inlineQuestionCreator() {
        return {
            showModal: false,
            saving: false,
            errorMsg: '',
            form: {
                course_id: '{{ $course->id }}',
                question_text: '',
                question_type: 'multiple_choice',
                difficulty: 'medium',
                option_a: '',
                option_b: '',
                option_c: '',
                option_d: '',
                correct_answer: 'A',
                explanation: '',
                points: 10,
                matching_pairs: [{left: '', right: ''}, {left: '', right: ''}],
            },
            init() {},
            onTypeChange() {
                if (this.form.question_type === 'true_false') {
                    this.form.option_a = 'True';
                    this.form.option_b = 'False';
                    this.form.option_c = '';
                    this.form.option_d = '';
                    this.form.correct_answer = 'A';
                } else if (this.form.question_type === 'fill_blank') {
                    this.form.option_a = '';
                    this.form.option_b = '';
                    this.form.option_c = '';
                    this.form.option_d = '';
                    this.form.correct_answer = 'A';
                } else if (this.form.question_type === 'drag_drop') {
                    this.form.option_a = '';
                    this.form.option_b = '';
                    this.form.option_c = '';
                    this.form.option_d = '';
                    this.form.correct_answer = 'A';
                    if (this.form.matching_pairs.length < 2) {
                        this.form.matching_pairs = [{left: '', right: ''}, {left: '', right: ''}];
                    }
                } else {
                    this.form.option_a = '';
                    this.form.option_b = '';
                    this.form.option_c = '';
                    this.form.option_d = '';
                }
            },
            resetForm() {
                this.form.question_text = '';
                this.form.question_type = 'multiple_choice';
                this.form.difficulty = 'medium';
                this.form.option_a = '';
                this.form.option_b = '';
                this.form.option_c = '';
                this.form.option_d = '';
                this.form.correct_answer = 'A';
                this.form.explanation = '';
                this.form.points = 10;
                this.form.matching_pairs = [{left: '', right: ''}, {left: '', right: ''}];
                this.errorMsg = '';
            },
            async submitQuestion() {
                this.saving = true;
                this.errorMsg = '';

                const payload = {...this.form};
                if (payload.question_type !== 'drag_drop') {
                    delete payload.matching_pairs;
                }

                try {
                    const res = await fetch('{{ route("admin.questions.ajax-store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        const errors = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || __('حدث خطأ'));
                        this.errorMsg = errors;
                        this.saving = false;
                        return;
                    }
                    if (data.success) {
                        const container = document.getElementById('questionsListContainer');
                        const noMsg = container.querySelector('.no-questions-msg');
                        if (noMsg) noMsg.remove();

                        const q = data.question;
                        const diffLabel = q.difficulty.charAt(0).toUpperCase() + q.difficulty.slice(1);
                        const label = document.createElement('label');
                        label.className = 'flex items-start gap-2 text-sm';
                        label.style.color = 'var(--color-text)';
                        label.innerHTML = `<input type="checkbox" name="question_ids[]" value="${q.id}" checked class="mt-1 w-4 h-4 text-primary-500 rounded"><span>${q.question_text} <span class="text-xs" style="color: var(--color-text-muted);">(${diffLabel})</span></span>`;
                        container.appendChild(label);
                        container.scrollTop = container.scrollHeight;

                        this.resetForm();
                        this.showModal = false;
                    }
                } catch(e) {
                    this.errorMsg = __('حدث خطأ في الاتصال بالسيرفر');
                }
                this.saving = false;
            }
        };
    }
</script>
@endpush
@endsection
