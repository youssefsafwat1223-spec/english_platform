@extends('layouts.admin')
@section('title', __('Forum Topics'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('All Forum Topics') }}</span></h1>
            <a href="{{ route('admin.forum.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Forum') }}</a>
        </div>
        <div class="glass-card mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="search" placeholder="{{ __('Search topics...') }}" class="input-glass" value="{{ request('search') }}">
                    <select name="category" class="input-glass">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)<option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>@endforeach
                    </select>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Topic') }}</th><th>{{ __('Category') }}</th><th>{{ __('Author') }}</th><th>{{ __('Replies') }}</th><th>{{ __('Created') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($topics as $topic)
                        <tr>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $topic->title }}</div>
                                <div class="flex gap-1 mt-1">
                                    @if($topic->is_pinned)<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-[10px] font-bold">{{ __('Pinned') }}</span>@endif
                                    @if($topic->is_locked)<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-[10px] font-bold">{{ __('Locked') }}</span>@endif
                                </div>
                            </td>
                            <td>{{ $topic->category->name }}</td>
                            <td>{{ $topic->user->name }}</td>
                            <td>{{ $topic->reply_count }}</td>
                            <td>{{ $topic->created_at->diffForHumans() }}</td>
                            <td><a href="{{ route('admin.forum.topics.show', $topic) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No topics found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $topics->links() }}</div>
        </div>
    </div>
</div>
@endsection
