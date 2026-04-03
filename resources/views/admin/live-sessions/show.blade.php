@extends('layouts.admin')
@section('title', $liveSession->title)
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $liveSession->title }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $liveSession->courses->pluck('title')->implode(', ') }}</p>
            </div>
            <a href="{{ route('admin.live-sessions.edit', $liveSession) }}" class="btn-primary ripple-btn">{{ __('live_sessions.edit') }}</a>
        </div>

        <div class="glass-card">
            <div class="glass-card-body space-y-4">
                <div><span class="font-bold">{{ __('live_sessions.status') }}:</span> {{ __('live_sessions.statuses.' . $liveSession->display_status) }}</div>
                <div><span class="font-bold">{{ __('live_sessions.starts_at') }}:</span> {{ $liveSession->starts_at?->format('M d, Y h:i A') }}</div>
                <div><span class="font-bold">{{ __('live_sessions.ends_at') }}:</span> {{ $liveSession->ends_at?->format('M d, Y h:i A') }}</div>
                <div><span class="font-bold">{{ __('live_sessions.eligible_students') }}:</span> {{ $eligibleStudentsCount }}</div>
                <div><span class="font-bold">{{ __('live_sessions.zoom_link') }}:</span> <a class="text-primary-500 hover:underline" href="{{ $liveSession->zoom_join_url }}" target="_blank" rel="noopener noreferrer">{{ __('live_sessions.open_zoom') }}</a></div>
                @if($liveSession->recording_url)
                    <div><span class="font-bold">{{ __('live_sessions.recording') }}:</span> <a class="text-primary-500 hover:underline" href="{{ $liveSession->recording_url }}" target="_blank" rel="noopener noreferrer">{{ __('live_sessions.open_recording') }}</a></div>
                @endif
                @if($liveSession->description)
                    <div><span class="font-bold">{{ __('live_sessions.description') }}:</span><div class="mt-2" style="color: var(--color-text-muted);">{!! nl2br(e($liveSession->description)) !!}</div></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
