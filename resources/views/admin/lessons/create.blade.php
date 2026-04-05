@extends('layouts.admin')
@section('title', __('Create Lesson'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Create Lesson') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Add a new lesson to') }} {{ $course->title }}</p>
        </div>

        <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Lesson Title *') }}</label>
                        <input type="text" id="title" name="title" class="input-glass @error('title') border-red-500 @enderror" value="{{ old('title') }}" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="course_level_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('العنوان') }} <span class="text-red-500">*</span></label>
                        <select id="course_level_id" name="course_level_id" class="input-glass @error('course_level_id') border-red-500 @enderror" required>
                            <option value="">{{ __('— اختار العنوان —') }}</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ old('course_level_id') == $level->id ? 'selected' : '' }}>{{ $level->title }}</option>
                            @endforeach
                        </select>
                        @error('course_level_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @if($levels->isEmpty())
                            <p class="text-xs mt-1 text-amber-500 font-bold">⚠️ {{ __('لازم تضيف عنوان للكورس الأول') }} — <a href="{{ route('admin.courses.levels.create', $course) }}" class="text-primary-500 hover:underline">{{ __('إضافة عنوان') }}</a></p>
                        @endif
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Description') }}</label>
                        <textarea id="description" name="description" rows="3" class="input-glass @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="video_url" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Video URL') }}</label>
                        <input type="url" id="video_url" name="video_url" class="input-glass @error('video_url') border-red-500 @enderror" value="{{ old('video_url') }}" placeholder="{{ __('https://...') }}">
                        @error('video_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="vdocipher_video_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('VdoCipher Video ID') }}</label>
                        <input type="text" id="vdocipher_video_id" name="vdocipher_video_id" class="input-glass @error('vdocipher_video_id') border-red-500 @enderror" value="{{ old('vdocipher_video_id') }}" placeholder="{{ __('e.g. 1234abcd5678efgh') }}">
                        @error('vdocipher_video_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('اختياري — لو عندك فيديو على VdoCipher، حط الـ Video ID هنا. لو موجود هيتم استخدامه بدل الـ Video URL.') }}</p>
                    </div>
                    <div>
                        <label for="text_content" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Text Content') }}</label>
                        <textarea id="text_content" name="text_content" rows="10" class="input-glass @error('text_content') border-red-500 @enderror">{{ old('text_content') }}</textarea>
                        @error('text_content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="attachments" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Attachments (PDF, DOC, etc.)') }}</label>
                        <input type="file" id="attachments" name="attachments[]" multiple class="input-glass @error('attachments.*') border-red-500 @enderror">
                        @error('attachments.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Max 100MB per file') }}</p>
                    </div>
                    <div>
                        <label for="order_index" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Order Index') }}</label>
                        <input type="number" id="order_index" name="order_index" min="0" class="input-glass @error('order_index') border-red-500 @enderror" value="{{ old('order_index') }}">
                        @error('order_index')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Leave blank to add at the end') }}</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_free" name="is_free" value="1" {{ old('is_free') ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                            <label for="is_free" class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Free preview lesson') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="has_quiz" name="has_quiz" value="1" {{ old('has_quiz') ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                            <label for="has_quiz" class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Has quiz') }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="has_pronunciation_exercise" name="has_pronunciation_exercise" value="1" {{ old('has_pronunciation_exercise') ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                            <label for="has_pronunciation_exercise" class="ml-2 text-sm" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'يتضمن تمرين نطق' : 'Has pronunciation exercise' }}</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="has_writing_exercise" name="has_writing_exercise" value="1" {{ old('has_writing_exercise') ? 'checked' : '' }} class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                            <label for="has_writing_exercise" class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Has writing exercise') }}</label>
                        </div>
                    </div>

                    @php $quizMode = old('quiz_mode', 'questions'); @endphp

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
                                        <option value="{{ $quiz->id }}" {{ old('quiz_id') == $quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Only quizzes not linked to a lesson are shown.') }}</p>
                            </div>

                            <div id="questionQuizBlock" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Quiz Title') }}</label>
                                    <input type="text" name="quiz_title" class="input-glass" value="{{ old('quiz_title') }}" placeholder="{{ __('Lesson Quiz') }}">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Duration (minutes)') }}</label>
                                        <input type="number" name="quiz_duration_minutes" class="input-glass" min="1" value="{{ old('quiz_duration_minutes', 10) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%)') }}</label>
                                        <input type="number" name="quiz_passing_score" class="input-glass" min="0" max="100" value="{{ old('quiz_passing_score', 70) }}">
                                    </div>
                                    <div class="flex items-center gap-2 pt-6">
                                        <input type="hidden" name="quiz_allow_retake" value="0">
                                        <input type="checkbox" name="quiz_allow_retake" value="1" {{ old('quiz_allow_retake', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        <span class="text-sm" style="color: var(--color-text);">{{ __('Allow retake') }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="hidden" name="quiz_show_results" value="0">
                                        <input type="checkbox" name="quiz_show_results" value="1" {{ old('quiz_show_results', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        {{ __('Show results immediately') }}
                                    </label>
                                    <label class="flex items-center gap-2 text-sm" style="color: var(--color-text);">
                                        <input type="hidden" name="quiz_enable_audio" value="0">
                                        <input type="checkbox" name="quiz_enable_audio" value="1" {{ old('quiz_enable_audio', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                        {{ __('Enable audio') }}
                                    </label>
                                </div>
                                <div x-data="inlineQuestionCreator()" x-init="init()">
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Select Questions') }}</label>
                                    <div class="max-h-64 overflow-y-auto rounded-xl p-3 space-y-2" id="questionsListContainer" style="background: var(--color-surface); border: 1px solid var(--color-border);">
                                        @forelse($availableQuestions as $question)
                                            <label class="flex items-start gap-2 text-sm" style="color: var(--color-text);">
                                                <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" {{ in_array($question->id, old('question_ids', [])) ? 'checked' : '' }} class="mt-1 w-4 h-4 text-primary-500 rounded">
                                                <span>{{ $question->question_text }} <span class="text-xs" style="color: var(--color-text-muted);">({{ ucfirst($question->difficulty) }})</span></span>
                                            </label>
                                        @empty
                                            <p class="text-sm no-questions-msg" style="color: var(--color-text-muted);">{{ __('No questions found for this course.') }}</p>
                                        @endforelse
                                    </div>
                                    <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Selected questions will be linked to this lesson.') }}</p>

                                    {{-- Add Question Button --}}
                                    <button type="button" @click="showModal = true" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold text-white bg-primary-500 hover:bg-primary-600 transition-colors shadow-lg shadow-primary-500/30">
                                        <i class="fas fa-plus"></i> {{ __('إضافة سؤال جديد') }}
                                    </button>

                                    {{-- Modal --}}
                                    <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
                                        <div @click.away="showModal = false" class="glass-card w-full max-w-2xl max-h-[90vh] overflow-y-auto" data-aos="fade-up">
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
                            <p class="text-xs font-medium" style="color: var(--color-text-muted);">{{ app()->getLocale() === 'ar' ? 'استخدم الحقول بهذا الترتيب: كلمة، ثم جملة، ثم قطعة قصيرة.' : 'Use these fields in this order: word, then sentence, then a short passage.' }}</p>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'الكلمة *' : 'Word *' }}</label>
                                <input type="text" name="pronunciation_sentence_1" class="input-glass" value="{{ old('pronunciation_sentence_1') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: cake' : 'Example: cake' }}">
                                @error('pronunciation_sentence_1')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'الجملة' : 'Sentence' }}</label>
                                <input type="text" name="pronunciation_sentence_2" class="input-glass" value="{{ old('pronunciation_sentence_2') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: The cake tastes sweet.' : 'Example: The cake tastes sweet.' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'القطعة' : 'Passage' }}</label>
                                <input type="text" name="pronunciation_sentence_3" class="input-glass" value="{{ old('pronunciation_sentence_3') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: A short paragraph related to the lesson.' : 'Example: A short paragraph related to the lesson.' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'كلمات الدرس' : 'Lesson Vocabulary' }}</label>
                                <textarea name="pronunciation_vocabulary_lines" rows="6" class="input-glass" placeholder="{{ app()->getLocale() === 'ar' ? 'كل سطر بهذا الشكل: word | pronunciation | المعنى' : 'One item per line: word | pronunciation | Arabic meaning' }}">{{ old('pronunciation_vocabulary_lines') }}</textarea>
                                <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ app()->getLocale() === 'ar' ? 'مثال: cake | /keik/ | كعكة' : 'Example: cake | /keik/ | كعكة' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'شرح الجملة' : 'Sentence Explanation' }}</label>
                                <textarea name="pronunciation_sentence_explanation" rows="3" class="input-glass">{{ old('pronunciation_sentence_explanation') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'شرح القطعة' : 'Passage Explanation' }}</label>
                                <textarea name="pronunciation_passage_explanation" rows="3" class="input-glass">{{ old('pronunciation_passage_explanation') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'صوت مرجعي للكلمة' : 'Reference Audio for Word' }}</label>
                                    <input type="file" name="pronunciation_reference_audio_1" accept="audio/*" class="input-glass">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'صوت مرجعي للجملة' : 'Reference Audio for Sentence' }}</label>
                                    <input type="file" name="pronunciation_reference_audio_2" accept="audio/*" class="input-glass">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ app()->getLocale() === 'ar' ? 'صوت مرجعي للقطعة' : 'Reference Audio for Passage' }}</label>
                                    <input type="file" name="pronunciation_reference_audio_3" accept="audio/*" class="input-glass">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%)') }}</label>
                                    <input type="number" name="pronunciation_passing_score" class="input-glass" min="0" max="100" value="{{ old('pronunciation_passing_score', 70) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Max Duration (seconds)') }}</label>
                                    <input type="number" name="pronunciation_max_duration" class="input-glass" min="1" value="{{ old('pronunciation_max_duration', 10) }}">
                                </div>
                                <div class="flex items-center gap-2 pt-6">
                                    <input type="hidden" name="pronunciation_allow_retake" value="0">
                                    <input type="checkbox" name="pronunciation_allow_retake" value="1" {{ old('pronunciation_allow_retake', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded">
                                    <span class="text-sm" style="color: var(--color-text);">{{ __('Allow retake') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="writingOptions" class="hidden">
                        <div class="rounded-xl p-4 space-y-4" style="background: var(--color-surface-hover);">
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Writing Title *') }}</label>
                                <input type="text" name="writing_title" class="input-glass" value="{{ old('writing_title') }}" placeholder="{{ __('Example: Daily Routine Writing Task') }}">
                                @error('writing_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Prompt *') }}</label>
                                <textarea name="writing_prompt" rows="4" class="input-glass" placeholder="{{ __('Write 80 to 120 words about your daily routine.') }}">{{ old('writing_prompt') }}</textarea>
                                @error('writing_prompt')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Instructions') }}</label>
                                <textarea name="writing_instructions" rows="3" class="input-glass" placeholder="{{ __('Use complete sentences and include clear supporting details.') }}">{{ old('writing_instructions') }}</textarea>
                                @error('writing_instructions')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Minimum Words') }}</label>
                                    <input type="number" name="writing_min_words" class="input-glass" min="1" value="{{ old('writing_min_words', 30) }}">
                                    @error('writing_min_words')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Maximum Words') }}</label>
                                    <input type="number" name="writing_max_words" class="input-glass" min="1" value="{{ old('writing_max_words', 180) }}">
                                    @error('writing_max_words')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score (%)') }}</label>
                                    <input type="number" name="writing_passing_score" class="input-glass" min="0" max="100" value="{{ old('writing_passing_score', 70) }}">
                                    @error('writing_passing_score')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Model Answer') }}</label>
                                <textarea name="writing_model_answer" rows="5" class="input-glass" placeholder="{{ __('Optional: add a strong sample answer for internal guidance or future display.') }}">{{ old('writing_model_answer') }}</textarea>
                                @error('writing_model_answer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary ripple-btn" :disabled="loading">
                        <span x-show="!loading">{{ __('Create Lesson') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Creating...') }}</span>
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
        const hasWriting = document.getElementById('has_writing_exercise');
        const quizOptions = document.getElementById('quizOptions');
        const pronunciationOptions = document.getElementById('pronunciationOptions');
        const writingOptions = document.getElementById('writingOptions');
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
        const toggleWritingOptions = () => {
            writingOptions.classList.toggle('hidden', !hasWriting.checked);
        };
        hasQuiz.addEventListener('change', toggleQuizOptions);
        hasPronunciation.addEventListener('change', togglePronunciationOptions);
        hasWriting.addEventListener('change', toggleWritingOptions);
        quizModeInputs.forEach(input => input.addEventListener('change', toggleQuizOptions));
        toggleQuizOptions();
        togglePronunciationOptions();
        toggleWritingOptions();
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

                // Build payload — only send matching_pairs for drag_drop
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
