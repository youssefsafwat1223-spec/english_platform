@extends('layouts.admin')
@section('title', __('Attempt Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Attempt Details') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $attempt->user->name }} — {{ $quiz->title }}</p>
            <a href="{{ route('admin.quizzes.attempts', $quiz) }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('? Back to Attempts') }}</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @php $detStats = [
                ['v' => $attempt->score.'%', 'c' => $attempt->passed ? 'text-emerald-500' : 'text-red-500', 'l' => 'Score'],
                ['v' => $attempt->correct_answers.'/'.$attempt->total_questions, 'c' => 'text-primary-500', 'l' => 'Correct'],
                ['v' => gmdate('i:s', $attempt->time_taken), 'c' => 'text-amber-500', 'l' => 'Time'],
                ['v' => $attempt->attempt_number, 'c' => '', 'l' => 'Attempt #'],
            ]; @endphp
            @foreach($detStats as $i => $ds)
            <div class="glass-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="glass-card-body text-center">
                    <div class="text-2xl font-extrabold {{ $ds['c'] }}" style="{{ !$ds['c'] ? 'color: var(--color-text);' : '' }}">{{ $ds['v'] }}</div>
                    <div class="text-xs" style="color: var(--color-text-muted);">{{ $ds['l'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Answers Review') }}</h3></div>
            <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                @foreach($attempt->answers as $index => $answer)
                <div class="py-6 first:pt-0 last:pb-0">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="font-bold" style="color: var(--color-text);">Question {{ $index + 1 }}</h4>
                        @if($answer->is_correct)<span class="badge-success">{{ __('Correct') }}</span>
                        @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Wrong') }}</span>@endif
                    </div>
                    <p class="mb-4 text-sm" style="color: var(--color-text);">{{ $answer->question->question_text }}</p>
                    <div class="space-y-2">
                        <div class="p-3 rounded-xl text-sm {{ $answer->user_answer == 'A' ? ($answer->is_correct ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20') : '' }}" style="{{ $answer->user_answer != 'A' ? 'background: var(--color-surface-hover);' : '' }}">
                            <strong>A.</strong> {{ $answer->question->option_a }}
                            @if($answer->question->correct_answer == 'A')<span class="text-emerald-500 ml-2">?</span>@endif
                        </div>
                        <div class="p-3 rounded-xl text-sm {{ $answer->user_answer == 'B' ? ($answer->is_correct ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20') : '' }}" style="{{ $answer->user_answer != 'B' ? 'background: var(--color-surface-hover);' : '' }}">
                            <strong>B.</strong> {{ $answer->question->option_b }}
                            @if($answer->question->correct_answer == 'B')<span class="text-emerald-500 ml-2">?</span>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
