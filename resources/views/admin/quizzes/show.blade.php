@extends('layouts.admin')
@section('title', $quiz->title)
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $quiz->title }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $quiz->course->title }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn-primary ripple-btn">{{ __('Edit Quiz') }}</a>
                <a href="{{ route('admin.quizzes.attempts', $quiz) }}" class="btn-secondary">{{ __('View Attempts') }}</a>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Quiz Details') }}</h3></div>
                    <div class="glass-card-body">
                        @if($quiz->description)<p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ $quiz->description }}</p>@endif
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @php $qStats = [
                                ['v' => $quiz->total_questions, 'l' => 'Questions', 'c' => 'text-primary-500'],
                                ['v' => $quiz->duration_minutes, 'l' => 'Minutes', 'c' => 'text-emerald-500'],
                                ['v' => $quiz->passing_score.'%', 'l' => 'Pass Score', 'c' => 'text-amber-500'],
                            ]; @endphp
                            @foreach($qStats as $qs)
                            <div class="text-center p-4 rounded-xl" style="background: var(--color-surface-hover);">
                                <div class="text-2xl font-extrabold {{ $qs['c'] }}">{{ $qs['v'] }}</div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ $qs['l'] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-sm space-y-1" style="color: var(--color-text-muted);">
                            <div><span class="font-bold" style="color: var(--color-text);">{{ __('Type:') }}</span> {{ $quiz->is_final_exam ? 'Final Exam' : 'Lesson Quiz' }}</div>
                            @if($quiz->lesson)<div><span class="font-bold" style="color: var(--color-text);">{{ __('Lesson:') }}</span> {{ $quiz->lesson->title }}</div>@endif
                        </div>
                    </div>
                </div>
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">Questions ({{ $quiz->questions->count() }})</h3></div>
                    <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                        @foreach($quiz->questions as $index => $question)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center mb-2">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 bg-primary-500/10 text-primary-500 text-sm">{{ $index + 1 }}</span>
                                <span class="font-bold text-sm" style="color: var(--color-text);">{{ $question->question_text }}</span>
                            </div>
                            <div class="ml-11 flex items-center space-x-2">
                                @if($question->difficulty == 'easy')<span class="badge-success text-[10px]">{{ __('Easy') }}</span>
                                @elseif($question->difficulty == 'medium')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-[10px] font-bold">{{ __('Medium') }}</span>
                                @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-[10px] font-bold">{{ __('Hard') }}</span>@endif
                                @if($question->has_audio)<span class="text-xs" style="color: var(--color-text-muted);">{{ __('🔊 Audio') }}</span>@endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Statistics') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Total Attempts') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['total_attempts'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Unique Students') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['unique_students'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Average Score') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ round($stats['average_score']) }}%</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Pass Rate') }}</span><span class="font-bold text-sm text-emerald-500">{{ round($stats['pass_rate']) }}%</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Average Time') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['average_time'] ? round($stats['average_time']) . ' sec' : '0 sec' }}</span></div>
                    </div>
                </div>
                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Settings') }}</h3></div>
                    <div class="glass-card-body space-y-2 text-sm">
                        <div class="flex items-center justify-between"><span style="color: var(--color-text-muted);">{{ __('Status') }}</span>@if($quiz->is_active)<span class="badge-success">{{ __('Active') }}</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive') }}</span>@endif</div>
                        <div class="flex items-center justify-between"><span style="color: var(--color-text-muted);">{{ __('Allow Retake') }}</span><span class="font-bold" style="color: var(--color-text);">{{ $quiz->allow_retake ? 'Yes' : 'No' }}</span></div>
                        <div class="flex items-center justify-between"><span style="color: var(--color-text-muted);">{{ __('Show Results') }}</span><span class="font-bold" style="color: var(--color-text);">{{ $quiz->show_results_immediately ? 'Immediately' : 'After Review' }}</span></div>
                        <div class="flex items-center justify-between"><span style="color: var(--color-text-muted);">{{ __('Audio Enabled') }}</span><span class="font-bold" style="color: var(--color-text);">{{ $quiz->enable_audio ? 'Yes' : 'No' }}</span></div>
                        <div class="flex items-center justify-between"><span style="color: var(--color-text-muted);">{{ __('Auto-play Audio') }}</span><span class="font-bold" style="color: var(--color-text);">{{ $quiz->audio_auto_play ? 'Yes' : 'No' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
