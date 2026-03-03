@extends('layouts.admin')
@section('title', __('Quiz Attempts'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Quiz Attempts') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $quiz->title }}</p>
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('? Back to Quiz') }}</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php $attStats = [
                ['v' => $attempts->total(), 'l' => 'Total Attempts', 'c' => 'text-primary-500'],
                ['v' => round($attempts->avg('score') ?? 0).'%', 'l' => 'Avg Score', 'c' => 'text-emerald-500'],
                ['v' => $attempts->where('passed', true)->count(), 'l' => 'Passed', 'c' => 'text-blue-500'],
                ['v' => $attempts->avg('time_taken') ? gmdate('i:s', $attempts->avg('time_taken')) : '0:00', 'l' => 'Avg Time', 'c' => 'text-amber-500'],
            ]; @endphp
            @foreach($attStats as $i => $as)
            <div class="glass-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="glass-card-body text-center">
                    <div class="text-3xl font-extrabold {{ $as['c'] }}">{{ $as['v'] }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ $as['l'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Student') }}</th><th>{{ __('Attempt #') }}</th><th>{{ __('Score') }}</th><th>{{ __('Result') }}</th><th>{{ __('Time') }}</th><th>{{ __('Date') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $attempt->user->name }}</div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ $attempt->user->email }}</div>
                            </td>
                            <td>{{ $attempt->attempt_number }}</td>
                            <td><span class="text-lg font-extrabold {{ $attempt->passed ? 'text-emerald-500' : 'text-red-500' }}">{{ $attempt->score }}%</span></td>
                            <td>
                                @if($attempt->passed)<span class="badge-success">{{ __('Passed') }}</span>
                                @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Failed') }}</span>@endif
                            </td>
                            <td>{{ gmdate('i:s', $attempt->time_taken) }}</td>
                            <td>{{ $attempt->completed_at->format('M d, Y H:i') }}</td>
                            <td><a href="{{ route('admin.quizzes.attempt-details', [$quiz, $attempt]) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('Details') }}</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No attempts yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $attempts->links() }}</div>
        </div>
    </div>
</div>
@endsection
