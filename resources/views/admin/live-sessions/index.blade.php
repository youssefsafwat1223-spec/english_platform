@extends('layouts.admin')
@section('title', __('live_sessions.title'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('live_sessions.title') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('live_sessions.admin_subtitle') }}</p>
            </div>
            <a href="{{ route('admin.live-sessions.create') }}" class="btn-primary ripple-btn">{{ __('live_sessions.create_cta') }}</a>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <th class="px-6 py-4 text-left">{{ __('live_sessions.title_label') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('live_sessions.courses') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('live_sessions.starts') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('live_sessions.status') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('live_sessions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liveSessions as $liveSession)
                            <tr style="border-bottom: 1px solid var(--color-border);">
                                <td class="px-6 py-4 font-bold" style="color: var(--color-text);">{{ $liveSession->title }}</td>
                                <td class="px-6 py-4" style="color: var(--color-text-muted);">{{ $liveSession->courses->pluck('title')->implode(', ') }}</td>
                                <td class="px-6 py-4" style="color: var(--color-text-muted);">{{ $liveSession->starts_at?->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(14,165,233,0.12); color: #0284c7;">
                                        {{ __('live_sessions.statuses.' . $liveSession->display_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.live-sessions.show', $liveSession) }}" class="text-primary-500 font-bold hover:underline">{{ __('live_sessions.view') }}</a>
                                        <a href="{{ route('admin.live-sessions.edit', $liveSession) }}" class="text-primary-500 font-bold hover:underline">{{ __('live_sessions.edit') }}</a>
                                        <form method="POST" action="{{ route('admin.live-sessions.destroy', $liveSession) }}" onsubmit="return confirm('{{ __('live_sessions.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 font-bold hover:underline">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center" style="color: var(--color-text-muted);">{{ __('live_sessions.no_live_sessions') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $liveSessions->links() }}</div>
        </div>
    </div>
</div>
@endsection
