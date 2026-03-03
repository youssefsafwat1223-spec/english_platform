@extends('layouts.admin')
@section('title', __('Question Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Question Details') }}</span></h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.questions.edit', $question) }}" class="btn-primary ripple-btn">{{ __('Edit') }}</a>
                <a href="{{ route('admin.questions.index') }}" class="btn-secondary">{{ __('Back') }}</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Question') }}</h3></div>
                    <div class="glass-card-body">
                        <p class="text-lg font-bold mb-4" style="color: var(--color-text);">{{ $question->question_text }}</p>
                        @if($question->has_audio && $question->audio_path)
                        <div class="p-4 rounded-xl mb-4" style="background: var(--color-surface-hover);">
                            <audio src="{{ Storage::url($question->audio_path) }}" controls class="w-full"></audio>
                        </div>
                        @endif
                        <div class="space-y-2 mt-4">
                            @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $letter => $opt)
                            @if($opt)
                            <div class="p-3 rounded-xl text-sm {{ $question->correct_answer == $letter ? 'bg-emerald-500/10 border border-emerald-500/20' : '' }}" style="{{ $question->correct_answer != $letter ? 'background: var(--color-surface-hover);' : '' }}">
                                <strong>{{ $letter }}.</strong> {{ $opt }}
                                @if($question->correct_answer == $letter)<span class="text-emerald-500 ml-2 font-bold">{{ __('✓ Correct') }}</span>@endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @if($question->explanation)
                        <div class="mt-4 p-4 rounded-xl bg-primary-500/10 border border-primary-500/20">
                            <div class="font-bold text-primary-500 text-sm mb-1">{{ __('Explanation') }}</div>
                            <p class="text-sm" style="color: var(--color-text);">{{ $question->explanation }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Details') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Course') }}</div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $question->course?->title ?? 'N/A' }}</div></div>
                        <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Lesson') }}</div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $question->lesson?->title ?? 'N/A' }}</div></div>
                        <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Type') }}</div><span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span></div>
                        <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Difficulty') }}</div>
                            @if($question->difficulty == 'easy')<span class="badge-success text-[10px]">{{ __('Easy') }}</span>
                            @elseif($question->difficulty == 'medium')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-bold">{{ __('Medium') }}</span>
                            @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Hard') }}</span>@endif
                        </div>
                        <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Audio') }}</div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $question->has_audio ? 'Enabled' : 'Disabled' }}</div></div>
                    </div>
                </div>
                @if($question->has_audio)
                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Audio Actions') }}</h3></div>
                    <div class="glass-card-body space-y-2">
                        <form action="{{ route('admin.questions.generate-audio', $question) }}" method="POST">@csrf<button type="submit" class="btn-primary ripple-btn w-full">{{ __('Regenerate TTS') }}</button></form>
                        @if($question->audio_path)
                        <form action="{{ route('admin.questions.delete-audio', $question) }}" method="POST" onsubmit="return confirm('Delete audio?')">@csrf @method('DELETE')
                            <button type="submit" class="btn-secondary w-full">{{ __('Delete Audio') }}</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
