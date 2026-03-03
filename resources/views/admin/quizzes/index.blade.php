@extends('layouts.admin')
@section('title', __('Manage Quizzes'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Manage Quizzes') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('All quizzes across courses') }}</p>
            </div>
            <a href="{{ route('admin.quizzes.create') }}" class="btn-primary ripple-btn">{{ __('+ Create Quiz') }}</a>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Quiz') }}</th><th>{{ __('Course') }}</th><th>{{ __('Type') }}</th><th>{{ __('Questions') }}</th><th>{{ __('Duration') }}</th><th>{{ __('Attempts') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                        <tr>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $quiz->title }}</div>
                                @if($quiz->lesson)<div class="text-xs" style="color: var(--color-text-muted);">Lesson: {{ $quiz->lesson->title }}</div>@endif
                            </td>
                            <td>{{ $quiz->course->title }}</td>
                            <td>
                                @if($quiz->is_final_exam)<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Final Exam') }}</span>
                                @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ __('Lesson Quiz') }}</span>@endif
                            </td>
                            <td>{{ $quiz->total_questions }}</td>
                            <td>{{ $quiz->duration_minutes }} min</td>
                            <td>{{ $quiz->attempts_count ?? 0 }}</td>
                            <td>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a>
                                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('Edit') }}</a>
                                    <a href="{{ route('admin.quizzes.attempts', $quiz) }}" class="text-emerald-500 text-sm font-bold hover:underline">{{ __('Attempts') }}</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);">
                            <p class="mb-4">{{ __('No quizzes yet') }}</p>
                            <a href="{{ route('admin.quizzes.create') }}" class="btn-primary ripple-btn">{{ __('Create First Quiz') }}</a>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $quizzes->links() }}</div>
        </div>
    </div>
</div>
@endsection
