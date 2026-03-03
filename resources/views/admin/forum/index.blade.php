@extends('layouts.admin')
@section('title', __('Forum Management'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Forum Management') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Moderate community discussions and reports') }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach([['Categories', $stats['total_categories'], 'from-primary-500 to-accent-500'], ['Topics', $stats['total_topics'], 'from-blue-500 to-cyan-400'], ['Replies', $stats['total_replies'], 'from-emerald-500 to-green-400'], ['Pending Reports', $stats['pending_reports'], 'from-orange-500 to-amber-400']] as [$label, $val, $grad])
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body text-center">
                    <div class="text-2xl font-black bg-gradient-to-r {{ $grad }} bg-clip-text text-transparent">{{ $val }}</div>
                    <div class="text-xs font-bold mt-1" style="color: var(--color-text-muted);">{{ $label }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach([['Categories', 'Manage forum categories', route('admin.forum.categories'), '📂'], ['Topics', 'View and moderate all topics', route('admin.forum.topics'), '💬'], ['Reports', 'Handle reported content', route('admin.forum.reports'), '⚠️']] as [$title, $desc, $link, $icon])
            <a href="{{ $link }}" class="glass-card overflow-hidden group hover:scale-[1.02] transition-transform" data-aos="fade-up">
                <div class="glass-card-body text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500/20 to-accent-500/20 flex items-center justify-center mx-auto mb-4 text-2xl group-hover:scale-110 transition-transform">{{ $icon }}</div>
                    <h3 class="text-lg font-bold mb-1" style="color: var(--color-text);">{{ $title }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $desc }}</p>
                    @if($title == 'Reports' && $stats['pending_reports'] > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-orange-500/10 text-orange-500 text-xs font-bold mt-2">{{ $stats['pending_reports'] }} pending</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Top Categories') }}</h3></div>
                <div class="glass-card-body">
                    @forelse($categories as $category)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b' : '' }}" style="border-color: var(--color-border);">
                        <div><div class="font-bold text-sm" style="color: var(--color-text);">{{ $category->name }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ $category->topics_count }} topics</div></div>
                        <span class="text-xs font-bold" style="color: var(--color-text-muted);">Order {{ $category->order_index }}</span>
                    </div>
                    @empty
                    <p style="color: var(--color-text-muted);">{{ __('No categories found.') }}</p>
                    @endforelse
                </div>
            </div>
            <div class="glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Recent Topics') }}</h3></div>
                <div class="glass-card-body">
                    @forelse($recentTopics as $topic)
                    <div class="py-3 {{ !$loop->last ? 'border-b' : '' }}" style="border-color: var(--color-border);">
                        <div class="font-bold text-sm" style="color: var(--color-text);">{{ $topic->title }}</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">{{ $topic->user->name }} in {{ $topic->category->name }} — {{ $topic->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <p style="color: var(--color-text-muted);">{{ __('No topics found.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
