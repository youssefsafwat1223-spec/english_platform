@extends('layouts.app')

@section('title', __('live_sessions.title') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        <x-student.page-header
            title="{{ __('live_sessions.title') }}"
            subtitle="{{ __('live_sessions.subtitle') }}"
            badge="{{ __('live_sessions.live_badge') }}"
            badgeColor="primary"
        />

        @php
            $sections = [
                __('live_sessions.live_now') => $liveSessions,
                __('live_sessions.upcoming') => $upcomingSessions,
                __('live_sessions.past_sessions') => $pastSessions,
            ];
        @endphp

        @foreach($sections as $sectionTitle => $items)
            <x-student.card title="{{ $sectionTitle }}" mb="mb-0">
                <div class="space-y-4">
                    @forelse($items as $liveSession)
                        <div class="rounded-2xl border p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="border-color: var(--color-border); background: var(--color-surface);">
                            <div class="space-y-2">
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">{{ $liveSession->title }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $liveSession->courses->pluck('title')->implode(', ') }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $liveSession->starts_at->format('M d, Y h:i A') }} - {{ $liveSession->ends_at->format('h:i A') }}</p>
                                @if($liveSession->description)
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($liveSession->description, 160) }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('student.live-sessions.show', $liveSession) }}" class="btn-secondary">{{ __('live_sessions.view_details') }}</a>
                                @if($liveSession->shouldShowJoinButton())
                                    <a href="{{ $liveSession->zoom_join_url }}" target="_blank" rel="noopener noreferrer" class="btn-primary ripple-btn">{{ __('live_sessions.join_zoom_session') }}</a>
                                @elseif($liveSession->shouldShowRecording())
                                    <a href="{{ $liveSession->recording_url }}" target="_blank" rel="noopener noreferrer" class="btn-primary ripple-btn">{{ __('live_sessions.watch_recording') }}</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm" style="color: var(--color-text-muted);">{{ __('live_sessions.no_sections_sessions') }}</p>
                    @endforelse
                </div>
            </x-student.card>
        @endforeach
    </div>
</div>
@endsection
