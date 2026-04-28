@extends('layouts.admin')

@section('title', __('Edit Level'))

@section('content')
@php
    $writingExercise = $level->writingExercise ?? null;
    $speakingExercise = $level->speakingExercise ?? null;
    $listeningExercise = $level->listeningExercise ?? null;

    $speakingVocabularyLines = old('speaking_vocabulary_lines');
    if ($speakingVocabularyLines === null && $speakingExercise?->vocabulary_json) {
        $speakingVocabularyLines = collect($speakingExercise->vocabulary_json)
            ->map(fn ($item) => implode(' | ', array_filter([
                $item['word'] ?? '',
                $item['pronunciation'] ?? '',
                $item['meaning_ar'] ?? '',
            ], fn ($value) => $value !== '')))
            ->implode("\n");
    }

    $listeningQuestionsJson = old(
        'listening_questions_json',
        $listeningExercise && is_array($listeningExercise->questions_json)
            ? json_encode($listeningExercise->questions_json, JSON_UNESCAPED_UNICODE)
            : '[]'
    );
@endphp

<div class="py-10 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[360px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <form action="{{ route('admin.courses.levels.update', [$course, $level]) }}"
              method="POST"
              enctype="multipart/form-data"
              x-data="{
                  hasWriting: {{ old('has_writing_exercise', $level->has_writing_exercise) ? 'true' : 'false' }},
                  hasSpeaking: {{ old('has_speaking_exercise', $level->has_speaking_exercise) ? 'true' : 'false' }},
                  hasListening: {{ old('has_listening_exercise', $level->has_listening_exercise) ? 'true' : 'false' }}
              }">
            @csrf
            @method('PUT')

            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between" data-aos="fade-down">
                <div>
                    <a href="{{ route('admin.courses.levels.index', $course) }}"
                       class="inline-flex items-center gap-2 text-sm font-bold hover:underline"
                       style="color: var(--color-text-muted);">
                        <span aria-hidden="true">&larr;</span>
                        {{ __('Back to Levels') }}
                    </a>
                    <h1 class="text-3xl font-extrabold mt-4">
                        <span class="text-gradient">{{ __('Edit Level') }}</span>
                    </h1>
                    <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                        {{ $course->title }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn-primary ripple-btn">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-6">
                <div class="space-y-6">
                    <section class="glass-card overflow-hidden" data-aos="fade-up">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Level Details') }}</h2>
                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">
                                {{ __('Edit the title, description, image, and order shown to students.') }}
                            </p>
                        </div>

                        <div class="glass-card-body space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                    {{ __('Level Title') }} <span class="text-red-500">*</span>
                                </label>
                                <input id="title" type="text" name="title" value="{{ old('title', $level->title) }}" required class="input-glass">
                                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                    {{ __('Level Description') }}
                                </label>
                                <textarea id="description" name="description" rows="4" class="input-glass">{{ old('description', $level->description) }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label for="thumbnail" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                        {{ __('Level Thumbnail') }}
                                    </label>
                                    @if($level->thumbnail)
                                        <div class="flex items-center gap-4 mb-3 rounded-2xl p-3 border" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                            <img src="{{ Storage::url($level->thumbnail) }}" class="w-28 h-20 rounded-xl object-cover border-2 border-primary-500/30" alt="{{ $level->title }}">
                                            <div>
                                                <p class="text-sm font-bold" style="color: var(--color-text);">{{ __('Current Image') }}</p>
                                                <p class="text-xs" style="color: var(--color-text-muted);">{{ __('Upload a new image only if you want to replace it.') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <input id="thumbnail" type="file" name="thumbnail" accept="image/*" class="input-glass">
                                    @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="order_index" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                        {{ __('Sort Order') }}
                                    </label>
                                    <input id="order_index" type="number" name="order_index" value="{{ old('order_index', $level->order_index) }}" min="0" class="input-glass">
                                    <p class="text-xs mt-2" style="color: var(--color-text-muted);">{{ __('Lower numbers appear first.') }}</p>
                                    @error('order_index') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="glass-card overflow-hidden" data-aos="fade-up">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Level Tests') }}</h2>
                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">
                                {{ __('Turn on only the tests that should appear at the end of this level.') }}
                            </p>
                        </div>

                        <div class="glass-card-body space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label for="has_writing_exercise" class="cursor-pointer rounded-2xl p-4 border transition-all"
                                       :class="hasWriting ? 'border-sky-400 bg-sky-500/10' : ''"
                                       style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="has_writing_exercise" id="has_writing_exercise" value="1" x-model="hasWriting" class="mt-1 w-4 h-4 text-sky-500 rounded">
                                        <div>
                                            <p class="text-sm font-extrabold" style="color: var(--color-text);">{{ __('Writing Test') }}</p>
                                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Prompt, instructions, word limits, and passing score.') }}</p>
                                        </div>
                                    </div>
                                </label>

                                <label for="has_speaking_exercise" class="cursor-pointer rounded-2xl p-4 border transition-all"
                                       :class="hasSpeaking ? 'border-emerald-400 bg-emerald-500/10' : ''"
                                       style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="has_speaking_exercise" id="has_speaking_exercise" value="1" x-model="hasSpeaking" class="mt-1 w-4 h-4 text-emerald-500 rounded">
                                        <div>
                                            <p class="text-sm font-extrabold" style="color: var(--color-text);">{{ __('Speaking Test') }}</p>
                                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Sentences, reference audio, vocabulary, and retakes.') }}</p>
                                        </div>
                                    </div>
                                </label>

                                <label for="has_listening_exercise" class="cursor-pointer rounded-2xl p-4 border transition-all"
                                       :class="hasListening ? 'border-amber-400 bg-amber-500/10' : ''"
                                       style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="has_listening_exercise" id="has_listening_exercise" value="1" x-model="hasListening" class="mt-1 w-4 h-4 text-amber-500 rounded">
                                        <div>
                                            <p class="text-sm font-extrabold" style="color: var(--color-text);">{{ __('Listening Test') }}</p>
                                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Audio script, question builder, and passing score.') }}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </section>

                    <section x-show="hasWriting" x-cloak class="glass-card overflow-hidden border border-sky-200 dark:border-sky-500/20" data-aos="fade-up">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Writing Test Settings') }}</h2>
                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('What the student sees before submitting the writing answer.') }}</p>
                        </div>

                        <div class="glass-card-body space-y-5">
                            <div>
                                <label for="writing_title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Test Title') }}</label>
                                <input id="writing_title" type="text" name="writing_title" class="input-glass" value="{{ old('writing_title', $writingExercise?->title ?? $level->title) }}">
                            </div>

                            <div>
                                <label for="writing_prompt" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Prompt') }}</label>
                                <textarea id="writing_prompt" name="writing_prompt" rows="4" class="input-glass" placeholder="Write 80 to 120 words about your daily routine.">{{ old('writing_prompt', $writingExercise?->prompt) }}</textarea>
                            </div>

                            <div>
                                <label for="writing_instructions" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Extra Instructions') }}</label>
                                <textarea id="writing_instructions" name="writing_instructions" rows="3" class="input-glass" placeholder="Use complete sentences and check punctuation.">{{ old('writing_instructions', $writingExercise?->instructions) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="writing_min_words" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Minimum Words') }}</label>
                                    <input id="writing_min_words" type="number" name="writing_min_words" min="1" class="input-glass" value="{{ old('writing_min_words', $writingExercise?->min_words ?? 50) }}">
                                </div>
                                <div>
                                    <label for="writing_max_words" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Maximum Words') }}</label>
                                    <input id="writing_max_words" type="number" name="writing_max_words" min="1" class="input-glass" value="{{ old('writing_max_words', $writingExercise?->max_words ?? 200) }}">
                                </div>
                                <div>
                                    <label for="writing_passing_score" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score') }}</label>
                                    <input id="writing_passing_score" type="number" name="writing_passing_score" min="0" max="100" class="input-glass" value="{{ old('writing_passing_score', $writingExercise?->passing_score ?? 70) }}">
                                </div>
                            </div>

                            <div>
                                <label for="writing_model_answer" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Model Answer') }}</label>
                                <textarea id="writing_model_answer" name="writing_model_answer" rows="5" class="input-glass" placeholder="Optional internal model answer.">{{ old('writing_model_answer', $writingExercise?->model_answer) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section x-show="hasSpeaking" x-cloak class="glass-card overflow-hidden border border-emerald-200 dark:border-emerald-500/20" data-aos="fade-up">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Speaking Test Settings') }}</h2>
                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Add up to three sentences and optional reference audio files.') }}</p>
                        </div>

                        <div class="glass-card-body space-y-5">
                            <div class="space-y-4">
                                @foreach([1, 2, 3] as $index)
                                    @php
                                        $sentenceField = "sentence_{$index}";
                                        $audioField = "reference_audio_{$index}";
                                    @endphp
                                    <div class="rounded-2xl p-4 border" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                        <label for="speaking_sentence_{{ $index }}" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">
                                            {{ __('Sentence') }} {{ $index }} @if($index === 1)<span class="text-red-500">*</span>@endif
                                        </label>
                                        <input id="speaking_sentence_{{ $index }}"
                                               type="text"
                                               name="speaking_sentence_{{ $index }}"
                                               class="input-glass"
                                               value="{{ old("speaking_sentence_{$index}", $speakingExercise?->{$sentenceField}) }}"
                                               placeholder="{{ $index === 1 ? 'The quick brown fox jumps over the lazy dog.' : 'Optional extra sentence.' }}">

                                        <label for="speaking_reference_audio_{{ $index }}" class="block text-xs font-bold mt-4 mb-2" style="color: var(--color-text-muted);">
                                            {{ __('Reference Audio') }} {{ $index }}
                                        </label>
                                        @if($speakingExercise?->{$audioField})
                                            <audio controls class="w-full h-9 mb-2" src="{{ Storage::url($speakingExercise->{$audioField}) }}"></audio>
                                        @endif
                                        <input id="speaking_reference_audio_{{ $index }}"
                                               type="file"
                                               name="speaking_reference_audio_{{ $index }}"
                                               accept="audio/*"
                                               class="input-glass text-xs">
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                <label for="speaking_vocabulary_lines" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Vocabulary') }}</label>
                                <p class="text-xs mb-2" style="color: var(--color-text-muted);">
                                    {{ __('Use one item per line in this format: word | pronunciation | meaning') }}
                                </p>
                                <textarea id="speaking_vocabulary_lines" name="speaking_vocabulary_lines" rows="5" class="input-glass font-mono text-xs" placeholder="quick | kwik | fast">{{ $speakingVocabularyLines }}</textarea>
                            </div>

                            <div>
                                <label for="speaking_sentence_explanation" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Sentence Explanation') }}</label>
                                <textarea id="speaking_sentence_explanation" name="speaking_sentence_explanation" rows="3" class="input-glass">{{ old('speaking_sentence_explanation', $speakingExercise?->sentence_explanation) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="speaking_passing_score" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Passing Score') }}</label>
                                    <input id="speaking_passing_score" type="number" name="speaking_passing_score" min="0" max="100" class="input-glass" value="{{ old('speaking_passing_score', $speakingExercise?->passing_score ?? 70) }}">
                                </div>
                                <div>
                                    <label for="speaking_max_duration" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Max Recording Duration') }}</label>
                                    <input id="speaking_max_duration" type="number" name="speaking_max_duration" min="5" class="input-glass" value="{{ old('speaking_max_duration', $speakingExercise?->max_duration_seconds ?? 15) }}">
                                </div>
                                <label for="speaking_allow_retake" class="flex items-center gap-3 mt-7">
                                    <input type="checkbox" name="speaking_allow_retake" id="speaking_allow_retake" value="1" {{ old('speaking_allow_retake', $speakingExercise?->allow_retake ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 rounded">
                                    <span class="text-sm font-semibold" style="color: var(--color-text);">{{ __('Allow Retake') }}</span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <section x-show="hasListening" x-cloak class="glass-card overflow-hidden border border-amber-200 dark:border-amber-500/20" data-aos="fade-up">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Listening Test Settings') }}</h2>
                            <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Write the TTS script and manage questions from the builder.') }}</p>
                        </div>

                        <div class="glass-card-body space-y-5">
                            <div>
                                <label for="listening_title" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Test Title') }}</label>
                                <input id="listening_title" type="text" name="listening_title" class="input-glass" value="{{ old('listening_title', $listeningExercise?->title ?? $level->title) }}">
                            </div>

                            <div>
                                <label for="listening_script_ar" class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('TTS Script') }}</label>
                                <p class="text-xs mb-2" style="color: var(--color-text-muted);">
                                    {{ __('Use Arabic or English text. For mixed speech, you can wrap English words with lang tags when needed.') }}
                                </p>
                                <textarea id="listening_script_ar" name="listening_script_ar" rows="7" class="input-glass font-mono text-sm" placeholder='Example: Today we will learn the word "comfortable".'>{{ old('listening_script_ar', $listeningExercise?->script_ar) }}</textarea>
                            </div>

                            @if($listeningExercise?->audio_url)
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('Current Audio') }}</label>
                                    <audio controls class="w-full h-9" src="{{ $listeningExercise->audio_url }}"></audio>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold mb-3" style="color: var(--color-text);">{{ __('Questions') }}</label>
                                <x-listening-question-builder
                                    :questionsJson="$listeningQuestionsJson"
                                    inputName="listening_questions_json"
                                    :passingScore="old('listening_passing_score', $listeningExercise?->passing_score ?? 70)"
                                    scoreInputName="listening_passing_score"
                                />
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="glass-card overflow-hidden sticky top-28" data-aos="fade-left">
                        <div class="glass-card-header">
                            <h2 class="font-extrabold" style="color: var(--color-text);">{{ __('Visibility') }}</h2>
                        </div>

                        <div class="glass-card-body space-y-4">
                            <label for="is_active" class="flex items-start gap-3 rounded-2xl p-4 border cursor-pointer" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $level->is_active) ? 'checked' : '' }} class="mt-1 w-4 h-4 text-primary-500 rounded">
                                <span>
                                    <span class="block text-sm font-extrabold" style="color: var(--color-text);">{{ __('Visible to students') }}</span>
                                    <span class="block text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Turn this off to hide the level without deleting it.') }}</span>
                                </span>
                            </label>

                            <label for="is_free" class="flex items-start gap-3 rounded-2xl p-4 border cursor-pointer" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                <input type="checkbox" name="is_free" id="is_free" value="1" {{ old('is_free', $level->is_free) ? 'checked' : '' }} class="mt-1 w-4 h-4 text-emerald-500 rounded">
                                <span>
                                    <span class="block text-sm font-extrabold" style="color: var(--color-text);">{{ __('Free level') }}</span>
                                    <span class="block text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Students can open this level before purchasing the course.') }}</span>
                                </span>
                            </label>

                            <div class="rounded-2xl p-4 border" style="border-color: var(--color-border); background: var(--color-surface-hover);">
                                <p class="text-xs font-bold uppercase tracking-wide" style="color: var(--color-text-muted);">{{ __('Enabled Tests') }}</p>
                                <div class="mt-3 space-y-2 text-sm font-bold" style="color: var(--color-text);">
                                    <p x-show="hasWriting">{{ __('Writing Test') }}</p>
                                    <p x-show="hasSpeaking">{{ __('Speaking Test') }}</p>
                                    <p x-show="hasListening">{{ __('Listening Test') }}</p>
                                    <p x-show="!hasWriting && !hasSpeaking && !hasListening" style="color: var(--color-text-muted);">{{ __('No level tests enabled.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card-footer space-y-3">
                            <button type="submit" class="btn-primary ripple-btn w-full">{{ __('Save Changes') }}</button>
                            <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn-secondary w-full text-center block">{{ __('Back to Levels') }}</a>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </div>
</div>
@endsection
