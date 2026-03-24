@extends('layouts.admin')
@section('title', __('Game Arena'))
@section('content')
<div class="py-12 relative overflow-hidden" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Game Arena') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Manage Competitions') }}</p>
            </div>
            <a href="{{ route('admin.games.create') }}" class="btn-primary ripple-btn">{{ __('New Competition') }}</a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Game') }}</th>
                            <th>{{ __('Course') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Teams Count') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $session->title }}</div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ Str::limit($session->description, 40) }}</div>
                            </td>
                            <td>{{ $session->course->title }}</td>
                            <td>
                                @if($session->status === 'scheduled')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-yellow-500/10 text-yellow-500 text-xs font-bold">{{ __('Scheduled') }}</span>
                                @elseif($session->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-xs font-bold animate-pulse">{{ __('Active Now') }}</span>
                                @elseif($session->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ __('Completed') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Cancelled') }}</span>
                                @endif
                            </td>
                            <td>{{ $session->teams_count }}</td>
                            <td>
                                <div class="text-sm font-medium" style="color: var(--color-text);">{{ $session->start_time->format('Y-m-d') }}</div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ $session->start_time->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.games.show', $session) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('Control') }}</a>
                                    <form action="{{ route('admin.games.destroy', $session) }}" method="POST" onsubmit="return confirm(__('Confirm Delete Game'))">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 text-sm font-bold hover:underline">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">
                            <p class="text-4xl mb-4">🎮</p>
                            <p class="mb-4">{{ __('No competitions yet') }}</p>
                            <a href="{{ route('admin.games.create') }}" class="btn-primary ripple-btn">{{ __('Create First Competition') }}</a>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $sessions->links() }}</div>
        </div>
    </div>
</div>
@endsection
