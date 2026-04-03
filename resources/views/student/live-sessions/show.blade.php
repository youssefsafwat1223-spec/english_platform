@extends('layouts.app')

@section('title', $liveSession->title . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container space-y-8">
        <x-student.page-header
            title="{{ $liveSession->title }}"
            subtitle="{{ $liveSession->courses->pluck('title')->implode(', ') }}"
            badge="{{ __('live_sessions.single') }}"
            badgeColor="primary"
        >
            <x-slot name="actions">
                <a href="{{ route('student.live-sessions.index') }}" class="btn-ghost btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('live_sessions.back_to_index') }}
                </a>
            </x-slot>
        </x-student.page-header>

        <x-student.card mb="mb-0">
            <div class="space-y-5">
                <div class="inline-flex px-3 py-1 rounded-full text-xs font-black uppercase tracking-[0.2em]" style="background: rgba(14,165,233,0.12); color: #0284c7;">
                    {{ __('live_sessions.statuses.' . $liveSession->display_status) }}
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ __('live_sessions.starts') }}</div>
                        <div class="text-slate-500 dark:text-slate-400">{{ $liveSession->starts_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ __('live_sessions.ends') }}</div>
                        <div class="text-slate-500 dark:text-slate-400">{{ $liveSession->ends_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>

                @if($liveSession->description)
                    <div class="text-slate-600 dark:text-slate-300 leading-relaxed">{!! nl2br(e($liveSession->description)) !!}</div>
                @endif

                <div class="flex flex-wrap gap-3">
                    @if($liveSession->shouldShowJoinButton())
                        <a href="{{ $liveSession->zoom_join_url }}" target="_blank" rel="noopener noreferrer" class="btn-primary ripple-btn">{{ __('live_sessions.join_zoom_session') }}</a>
                    @endif
                    @if($liveSession->shouldShowRecording())
                        <a href="{{ $liveSession->recording_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">{{ __('live_sessions.watch_recording') }}</a>
                    @endif
                </div>
            </div>
        </x-student.card>
    </div>
</div>
@endsection
