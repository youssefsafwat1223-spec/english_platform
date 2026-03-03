@extends('layouts.admin')
@section('title', __('Forum Categories'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Forum Categories') }}</span></h1>
                <a href="{{ route('admin.forum.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Forum') }}</a>
            </div>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Create Category') }}</h3></div>
            <div class="glass-card-body">
                <form action="{{ route('admin.forum.categories.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <input type="text" name="name" class="input-glass" placeholder="{{ __('Category name') }}" required>
                    <input type="text" name="description" class="input-glass" placeholder="{{ __('Description') }}">
                    <input type="text" name="icon" class="input-glass" placeholder="Icon (optional)">
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Create') }}</button>
                </form>
            </div>
        </div>
        <div class="space-y-4">
            @forelse($categories as $category)
            <details class="glass-card overflow-hidden" data-aos="fade-up">
                <summary class="glass-card-body cursor-pointer flex items-center justify-between">
                    <div><div class="font-bold" style="color: var(--color-text);">{{ $category->name }}</div><div class="text-xs" style="color: var(--color-text-muted);">{{ $category->description }}</div></div>
                    <div class="text-xs font-bold" style="color: var(--color-text-muted);">{{ $category->topics_count }} topics, {{ $category->total_posts }} posts, order {{ $category->order_index }}</div>
                </summary>
                <div class="glass-card-body" style="border-top: 1px solid var(--color-border);">
                    <form action="{{ route('admin.forum.categories.update', $category) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf @method('PUT')
                        <input type="text" name="name" class="input-glass" value="{{ $category->name }}" required>
                        <input type="text" name="description" class="input-glass" value="{{ $category->description }}">
                        <input type="text" name="icon" class="input-glass" value="{{ $category->icon }}">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                            <span class="text-sm" style="color: var(--color-text);">{{ __('Active') }}</span>
                        </div>
                        <div class="md:col-span-4 flex flex-wrap gap-2">
                            <button type="submit" class="btn-primary ripple-btn">{{ __('Save') }}</button>
                        </div>
                    </form>
                    <form action="{{ route('admin.forum.categories.delete', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')" class="mt-3">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('Delete Category') }}</button>
                    </form>
                </div>
            </details>
            @empty
            <div class="glass-card overflow-hidden"><div class="glass-card-body text-center" style="color: var(--color-text-muted);">{{ __('No categories found.') }}</div></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
