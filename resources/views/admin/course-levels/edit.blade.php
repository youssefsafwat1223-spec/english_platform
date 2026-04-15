@extends('layouts.admin')
@section('title', __('تعديل العنوان'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ route('admin.courses.levels.index', $course) }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">← {{ __('العودة لإدارة العناوين') }}</a>
            <h1 class="text-3xl font-extrabold mt-4"><span class="text-gradient">{{ __('تعديل العنوان') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            @php
            $writingExercise  = $level->writingExercise  ?? null;
            $speakingExercise = $level->speakingExercise ?? null;
            $listeningExercise = $level->listeningExercise ?? null;
        @endphp
        <form action="{{ route('admin.courses.levels.update', [$course, $level]) }}" method="POST" enctype="multipart/form-data" x-data="{
                hasWriting:   {{ old('has_writing_exercise',   $level->has_writing_exercise)   ? 'true' : 'false' }},
                hasSpeaking:  {{ old('has_speaking_exercise',  $level->has_speaking_exercise)  ? 'true' : 'false' }},
                hasListening: {{ old('has_listening_exercise', $level->has_listening_exercise) ? 'true' : 'false' }}
            }">
                @csrf @method('PUT')
                <div class="glass-card-body space-y-6">

                    {{-- Basic Info --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('اسم العنوان') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $level->title) }}" required class="input-glass">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('وصف العنوان') }}</label>
                        <textarea name="description" rows="3" class="input-glass">{{ old('description', $level->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('صورة العنوان (اختياري)') }}</label>
                        @if($level->thumbnail)
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ Storage::url($level->thumbnail) }}" class="w-24 h-16 rounded-xl object-cover border-2 border-primary-500/30" alt="{{ $level->title }}">
                                <span class="text-xs font-medium" style="color: var(--color-text-muted);">{{ __('الصورة الحالية') }}</span>
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="input-glass">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('الترتيب') }}</label>
                        <input type="number" name="order_index" value="{{ old('order_index', $level->order_index) }}" min="0" class="input-glass">
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $level->is_active) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                        <label for="is_active" class="text-sm font-semibold" style="color: var(--color-text);">{{ __('مفعّل (يظهر للطلاب)') }}</label>
                    </div>

                    {{-- ─── Section Tests ─── --}}
                    <div class="border-t pt-6" style="border-color: var(--color-border);">
                        <h3 class="text-base font-extrabold mb-4" style="color: var(--color-text);">🧪 {{ __('اختبارات العنوان') }}</h3>
                        <p class="text-xs mb-4" style="color: var(--color-text-muted);">{{ __('تظهر في آخر قائمة الدروس بعد إتمام الطالب لدروس العنوان') }}</p>

                        <div class="space-y-3">
                            {{-- Writing --}}
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="has_writing_exercise" id="has_writing_exercise" value="1"
                                    x-model="hasWriting"
                                    class="w-4 h-4 text-sky-500 rounded" style="border-color: var(--color-border);">
                                <label for="has_writing_exercise" class="text-sm font-semibold flex items-center gap-2" style="color: var(--color-text);">
                                    ✏️ {{ __('يتضمن اختبار كتابة') }}
                                </label>
                            </div>

                            {{-- Speaking --}}
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="has_speaking_exercise" id="has_speaking_exercise" value="1"
                                    x-model="hasSpeaking"
                                    class="w-4 h-4 text-emerald-500 rounded" style="border-color: var(--color-border);">
                                <label for="has_speaking_exercise" class="text-sm font-semibold flex items-center gap-2" style="color: var(--color-text);">
                                    🎙️ {{ __('يتضمن اختبار نطق') }}
                                </label>
                            </div>

                            {{-- Listening --}}
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="has_listening_exercise" id="has_listening_exercise" value="1"
                                    x-model="hasListening"
                                    class="w-4 h-4 text-accent-500 rounded" style="border-color: var(--color-border);">
                                <label for="has_listening_exercise" class="text-sm font-semibold flex items-center gap-2" style="color: var(--color-text);">
                                    🎧 {{ __('يتضمن اختبار استماع') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ─── Writing Exercise Form ─── --}}
                    <div x-show="hasWriting" x-collapse x-cloak
                         class="rounded-xl p-5 space-y-4 border border-sky-200 dark:border-sky-500/20"
                         style="background: var(--color-surface-hover);">
                        <h4 class="font-extrabold text-sm flex items-center gap-2" style="color: var(--color-text);">
                            ✏️ {{ __('إعداد اختبار الكتابة') }}
                        </h4>
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('عنوان الاختبار') }}</label>
                            <input type="text" name="writing_title" class="input-glass"
                                value="{{ old('writing_title', $writingExercise?->title ?? $level->title) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('التعليمات / السؤال *') }}</label>
                            <textarea name="writing_prompt" rows="3" class="input-glass"
                                placeholder="{{ __('Write 80 to 120 words about...') }}">{{ old('writing_prompt', $writingExercise?->prompt) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('إرشادات إضافية') }}</label>
                            <textarea name="writing_instructions" rows="2" class="input-glass"
                                placeholder="{{ __('Use complete sentences...') }}">{{ old('writing_instructions', $writingExercise?->instructions) }}</textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('أقل عدد كلمات') }}</label>
                                <input type="number" name="writing_min_words" min="1" class="input-glass"
                                    value="{{ old('writing_min_words', $writingExercise?->min_words ?? 50) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('أكبر عدد كلمات') }}</label>
                                <input type="number" name="writing_max_words" min="1" class="input-glass"
                                    value="{{ old('writing_max_words', $writingExercise?->max_words ?? 200) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('درجة النجاح (%)') }}</label>
                                <input type="number" name="writing_passing_score" min="0" max="100" class="input-glass"
                                    value="{{ old('writing_passing_score', $writingExercise?->passing_score ?? 70) }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('نموذج إجابة (للمراجعة الداخلية)') }}</label>
                            <textarea name="writing_model_answer" rows="4" class="input-glass"
                                placeholder="{{ __('Optional model answer...') }}">{{ old('writing_model_answer', $writingExercise?->model_answer) }}</textarea>
                        </div>
                    </div>

                    {{-- ─── Speaking Exercise Form ─── --}}
                    <div x-show="hasSpeaking" x-collapse x-cloak
                         class="rounded-xl p-5 space-y-4 border border-emerald-200 dark:border-emerald-500/20"
                         style="background: var(--color-surface-hover);">
                        <h4 class="font-extrabold text-sm flex items-center gap-2" style="color: var(--color-text);">
                            🎙️ {{ __('إعداد اختبار النطق') }}
                        </h4>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('الجملة الأولى *') }}</label>
                                <input type="text" name="speaking_sentence_1" class="input-glass"
                                    value="{{ old('speaking_sentence_1', $speakingExercise?->sentence_1) }}"
                                    placeholder="The quick brown fox jumps over the lazy dog.">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('الجملة الثانية') }}</label>
                                <input type="text" name="speaking_sentence_2" class="input-glass"
                                    value="{{ old('speaking_sentence_2', $speakingExercise?->sentence_2) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __('الجملة الثالثة') }}</label>
                                <input type="text" name="speaking_sentence_3" class="input-glass"
                                    value="{{ old('speaking_sentence_3', $speakingExercise?->sentence_3) }}">
                            </div>
                        </div>

                        {{-- Reference audios --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach([1,2,3] as $i)
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: var(--color-text);">{{ __("صوت مرجعي {$i}") }}</label>
                                @if($speakingExercise?->{"reference_audio_{$i}"})
                                    <audio controls class="w-full h-8 mb-1" src="{{ Storage::url($speakingExercise->{'reference_audio_'.$i}) }}"></audio>
                                @endif
                                <input type="file" name="speaking_reference_audio_{{ $i }}" accept="audio/*" class="input-glass text-xs">
                            </div>
                            @endforeach
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                {{ __('المفردات') }}
                                <span class="text-xs font-normal ms-2" style="color: var(--color-text-muted);">كل كلمة في سطر: الكلمة | النطق | المعنى بالعربي</span>
                            </label>
                            <textarea name="speaking_vocabulary_lines" rows="4" class="input-glass font-mono text-xs"
                                placeholder="quick | kwɪk | سريع
brown | braʊn | بني
lazy | ˈleɪzi | كسول">{{ old('speaking_vocabulary_lines', $speakingExercise?->vocabulary_json ? collect($speakingExercise->vocabulary_json)->map(fn($v) => "{$v['word']} | {$v['pronunciation']} | {$v['meaning_ar']}")->implode("\n") : '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('شرح الجمل') }}</label>
                            <textarea name="speaking_sentence_explanation" rows="2" class="input-glass">{{ old('speaking_sentence_explanation', $speakingExercise?->sentence_explanation) }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('درجة النجاح (%)') }}</label>
                                <input type="number" name="speaking_passing_score" min="0" max="100" class="input-glass"
                                    value="{{ old('speaking_passing_score', $speakingExercise?->passing_score ?? 70) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('مدة التسجيل (ث)') }}</label>
                                <input type="number" name="speaking_max_duration" min="5" class="input-glass"
                                    value="{{ old('speaking_max_duration', $speakingExercise?->max_duration_seconds ?? 15) }}">
                            </div>
                            <div class="flex items-center gap-2 mt-6">
                                <input type="checkbox" name="speaking_allow_retake" id="speaking_allow_retake" value="1"
                                    {{ old('speaking_allow_retake', $speakingExercise?->allow_retake ?? true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-500 rounded">
                                <label for="speaking_allow_retake" class="text-sm font-semibold" style="color: var(--color-text);">{{ __('يسمح بإعادة المحاولة') }}</label>
                            </div>
                        </div>
                    </div>

                    {{-- ─── Listening Exercise Form ─── --}}
                    <div x-show="hasListening" x-collapse x-cloak
                         class="rounded-xl p-5 space-y-5 border border-accent-200 dark:border-accent-500/20"
                         style="background: var(--color-surface-hover);">

                        <h4 class="font-extrabold text-sm flex items-center gap-2" style="color: var(--color-text);">
                            🎧 {{ __('إعداد اختبار الاستماع') }}
                        </h4>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('عنوان الاختبار') }}</label>
                            <input type="text" name="listening_title" class="input-glass"
                                value="{{ old('listening_title', $listeningExercise?->title ?? $level->title) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                {{ __('النص العربي للـ TTS (الصوت)') }}
                                <span class="text-xs font-normal ms-2" style="color: var(--color-text-muted);">استخدم <code>&lt;lang xml:lang="en-US"&gt;word&lt;/lang&gt;</code> للكلمات الإنجليزية</span>
                            </label>
                            <textarea name="listening_script_ar" rows="6" class="input-glass font-mono text-sm"
                                placeholder="في هذا الدرس سنتعلم... &lt;lang xml:lang=&quot;en-US&quot;&gt;Hello&lt;/lang&gt; ...">{{ old('listening_script_ar', $listeningExercise?->script_ar) }}</textarea>
                        </div>

                        @if($listeningExercise?->audio_url)
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('الصوت الحالي') }}</label>
                            <audio controls class="w-full h-9" src="{{ $listeningExercise->audio_url }}"></audio>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-semibold mb-3" style="color: var(--color-text);">{{ __('الأسئلة') }}</label>
                            <x-listening-question-builder
                                :questionsJson="old('listening_questions_json', $listeningExercise ? json_encode($listeningExercise->questions_json, JSON_UNESCAPED_UNICODE) : '[]')"
                                inputName="listening_questions_json"
                                :passingScore="old('listening_passing_score', $listeningExercise?->passing_score ?? 70)"
                                scoreInputName="listening_passing_score"
                            />
                        </div>
                    </div>

                </div>

                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn-secondary">{{ __('إلغاء') }}</a>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('حفظ التعديلات') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
