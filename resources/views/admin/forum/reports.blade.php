@extends('layouts.admin')
@section('title', __('Forum Reports'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Reported Content') }}</span></h1>
            <a href="{{ route('admin.forum.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Forum') }}</a>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-body divide-y" style="--tw-divide-opacity: 0.1;">
                @forelse($reports as $report)
                <div class="py-5 {{ $loop->first ? '' : 'pt-5' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 flex items-center gap-2">
                            @if($report->status == 'pending')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-bold">{{ __('Pending') }}</span>
                            @elseif($report->status == 'resolved')<span class="badge-success text-[10px]">{{ __('Resolved') }}</span>
                            @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ ucfirst($report->status) }}</span>@endif
                            <span class="text-sm" style="color: var(--color-text-muted);">{{ $report->reason }}</span>
                        </div>
                        <span class="text-xs font-bold" style="color: var(--color-text-muted);">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    @php $reportableContent = optional($report->reportable)->content ?? optional($report->reportable)->title ?? ''; @endphp
                    <p class="text-sm mb-3" style="color: var(--color-text);">{{ Str::limit(strip_tags($reportableContent), 200) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold" style="color: var(--color-text-muted);">Reported by {{ $report->user->name }}</span>
                        @if($report->status == 'pending')
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.forum.reports.review', $report) }}" method="POST">@csrf<button type="submit" class="btn-secondary text-xs py-1.5 px-3">{{ __('Reviewed') }}</button></form>
                            <form action="{{ route('admin.forum.reports.resolve', $report) }}" method="POST">@csrf<button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-emerald-500 rounded-xl border border-emerald-500/20 bg-emerald-500/10 hover:bg-emerald-500/20 transition-all">{{ __('Resolve') }}</button></form>
                            <form action="{{ route('admin.forum.reports.dismiss', $report) }}" method="POST">@csrf<button type="submit" class="btn-secondary text-xs py-1.5 px-3">{{ __('Dismiss') }}</button></form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No reports') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
