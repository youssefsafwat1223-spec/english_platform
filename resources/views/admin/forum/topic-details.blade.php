@extends('layouts.admin')
@section('title', __('Topic Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $topic->title }}</span></h1>
            <a href="{{ route('admin.forum.topics') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Topics') }}</a>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <div class="mb-4 text-sm flex flex-wrap gap-4" style="color: var(--color-text-muted);">
                    <span>{{ __('Category:') }}<strong style="color: var(--color-text);">{{ $topic->category->name }}</strong></span>
                    <span>{{ __('Author:') }}<strong style="color: var(--color-text);">{{ $topic->user->name }}</strong></span>
                    <span>{{ $topic->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm leading-relaxed" style="color: var(--color-text);">{{ $topic->content }}</div>
                <div class="flex items-center gap-6 text-xs font-bold mt-4 pt-4" style="border-top: 1px solid var(--color-border); color: var(--color-text-muted);">
                    <span>{{ $topic->view_count }} {{ __('views') }}</span>
                    <span>{{ $topic->reply_count }} {{ __('replies') }}</span>
                </div>
            </div>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Moderation Actions') }}</h3></div>
            <div class="glass-card-body">
                <div class="flex flex-wrap gap-2">
                    @if($topic->is_pinned)
                    <form action="{{ route('admin.forum.topics.unpin', $topic) }}" method="POST">@csrf<button type="submit" class="btn-secondary">{{ __('Unpin Topic') }}</button></form>
                    @else
                    <form action="{{ route('admin.forum.topics.pin', $topic) }}" method="POST">@csrf<button type="submit" class="btn-primary ripple-btn">{{ __('Pin Topic') }}</button></form>
                    @endif
                    @if($topic->is_locked)
                    <form action="{{ route('admin.forum.topics.unlock', $topic) }}" method="POST">@csrf<button type="submit" class="btn-secondary">{{ __('Unlock Topic') }}</button></form>
                    @else
                    <form action="{{ route('admin.forum.topics.lock', $topic) }}" method="POST">@csrf<button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-amber-500 rounded-xl border border-amber-500/20 bg-amber-500/10 hover:bg-amber-500/20 transition-all">{{ __('Lock Topic') }}</button></form>
                    @endif
                    <form action="{{ route('admin.forum.topics.delete', $topic) }}" method="POST" onsubmit="return confirm('Delete this topic?')">@csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-red-500 rounded-xl border border-red-500/20 bg-red-500/10 hover:bg-red-500/20 transition-all">{{ __('Delete Topic') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($topic->replies as $reply)
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body">
                    <p class="text-sm mb-3" style="color: var(--color-text);">{{ $reply->content }}</p>
                    <div class="flex justify-between items-center text-xs" style="color: var(--color-text-muted);">
                        <span class="font-bold">{{ $reply->user->name }} — {{ $reply->created_at->diffForHumans() }}</span>
                        <form action="{{ route('admin.forum.replies.delete', $reply) }}" method="POST" onsubmit="return confirm('Delete this reply?')">@csrf @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold hover:underline">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
