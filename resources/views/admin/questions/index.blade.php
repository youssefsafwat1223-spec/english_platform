@extends('layouts.admin')
@section('title', __('Question Bank'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Question Bank') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Manage all quiz questions') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.questions.import') }}" class="btn-secondary">{{ __('Import') }}</a>
                <a href="{{ route('admin.questions.create') }}" class="btn-primary ripple-btn">{{ __('Add Question') }}</a>
            </div>
        </div>
        <div class="glass-card mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <input type="text" name="search" placeholder="{{ __('Search questions...') }}" class="input-glass md:col-span-2" value="{{ request('search') }}">
                    <select name="course_id" class="input-glass">
                        <option value="">{{ __('All Courses') }}</option>
                        @foreach($courses as $course)<option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach
                    </select>
                    <select name="lesson_id" class="input-glass">
                        <option value="">{{ __('All Lessons') }}</option>
                        @foreach($lessons as $lesson)<option value="{{ $lesson->id }}" {{ request('lesson_id') == $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>@endforeach
                    </select>
                    <select name="difficulty" class="input-glass">
                        <option value="">{{ __('All Difficulty') }}</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>{{ __('Easy') }}</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>{{ __('Hard') }}</option>
                    </select>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Question') }}</th><th>{{ __('Course') }}</th><th>{{ __('Type') }}</th><th>{{ __('Difficulty') }}</th><th>{{ __('Audio') }}</th><th>{{ __('Usage') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($questions as $question)
                        <tr>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ Str::limit($question->question_text, 60) }}</div>
                                @if($question->lesson)<div class="text-xs" style="color: var(--color-text-muted);">Lesson: {{ $question->lesson->title }}</div>@endif
                            </td>
                            <td>{{ $question->course?->title ?? 'N/A' }}</td>
                            <td><span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span></td>
                            <td>
                                @if($question->difficulty == 'easy')<span class="badge-success text-[10px]">{{ __('Easy') }}</span>
                                @elseif($question->difficulty == 'medium')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-bold">{{ __('Medium') }}</span>
                                @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Hard') }}</span>@endif
                            </td>
                            <td>{{ $question->has_audio ? '🔊' : '—' }}</td>
                            <td>{{ $question->quizzes_count }} quizzes</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.questions.show', $question) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a>
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Delete this question?')">@csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);"><p class="mb-4">{{ __('No questions yet') }}</p><a href="{{ route('admin.questions.create') }}" class="btn-primary ripple-btn">{{ __('Create First Question') }}</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $questions->links() }}</div>
        </div>
    </div>
</div>
@endsection
