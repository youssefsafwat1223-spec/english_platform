@extends('layouts.app')

@section('title', __('My Replies') . ' — ' . __('Forum'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('My Replies') }}"
            subtitle="{{ __('Your recent contributions and answers across the community forum.') }}"
            mb="mb-10"
        >
            <x-slot name="actions">
                <a href="{{ route('student.forum.index') }}" class="btn-ghost flex items-center justify-center gap-2 group px-5 py-3 rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 font-bold text-slate-700 dark:text-slate-300 transition-all w-full sm:w-auto">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ __('Back to Forum') }}
                </a>
            </x-slot>
        </x-student.page-header>

        {{-- Replies List --}}
        <div class="space-y-6">
            @forelse($replies as $reply)
                <x-student.card padding="p-0" class="{{ $reply->is_solution ? 'border-emerald-500/30 bg-emerald-50/50 dark:bg-emerald-900/10 ring-1 ring-emerald-500/30' : '' }} overflow-hidden relative group" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 300) }}">
                    @if($reply->is_solution)
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/10 to-transparent rounded-tr-[1.5rem] pointer-events-none"></div>
                    @endif
                    
                    <div class="p-6 md:p-8">
                        {{-- Context Header --}}
                        <div class="flex items-center gap-2 mb-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider relative z-10">
                            <span class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                {{ __('Reply added to topic') }}
                            </span>
                            <span class="opacity-50">•</span>
                            <a href="{{ route('student.forum.topic', [$reply->topic->category->slug, $reply->topic->slug]) }}" class="text-slate-700 dark:text-slate-300 hover:text-primary-500 transition-colors truncate max-w-[200px] sm:max-w-[400px]">
                                {{ $reply->topic->title }}
                            </a>
                        </div>
                        
                        {{-- Reply Content --}}
                        <div class="pl-4 sm:pl-6 border-l-2 border-slate-200 dark:border-white/10 {{ $reply->is_solution ? 'border-emerald-300 dark:border-emerald-500/50' : '' }} relative z-10">
                            <div class="text-[14px] md:text-[15px] leading-relaxed text-slate-700 dark:text-slate-300 font-medium mb-4">
                                {{ Str::limit(strip_tags($reply->content), 250) }}
                            </div>
                            
                            {{-- Reply Stats --}}
                            <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $reply->created_at->diffForHumans() }}
                                </span>
                                
                                @if($reply->like_count > 0)
                                    <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded-md border border-rose-500/20">
                                        <svg class="w-3.5 h-3.5 fill-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        {{ $reply->like_count }} {{ $reply->like_count === 1 ? __('Like') : __('Likes') }}
                                    </span>
                                @endif
                                
                                @if($reply->is_solution)
                                    <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-md border border-emerald-500/20 uppercase tracking-wider text-[10px] shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        {{ __('Marked as Solution') }}
                                    </span>
                                @endif

                                <div class="ml-auto pointer-events-auto">
                                    <a href="{{ route('student.forum.topic', [$reply->topic->category->slug, $reply->topic->slug]) }}#reply-{{ $reply->id }}" class="btn-ghost px-3 py-1.5 rounded-lg text-primary-500 bg-primary-500/10 border border-primary-500/20 hover:bg-primary-500 hover:text-white transition-colors flex items-center gap-1.5 shadow-sm">
                                        {{ __('View Thread') }}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-student.card>
            @empty
                <x-student.card padding="p-12" class="text-center relative overflow-hidden bg-slate-50 dark:bg-slate-800/50" data-aos="fade-up">
                    <div class="relative z-10 w-24 h-24 mx-auto rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center mb-6 shadow-inner text-5xl">
                        💬
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 relative z-10">{{ __('No Replies Yet') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto relative z-10 mb-8">
                        {{ __('You haven\'t posted any replies. Help others by sharing your knowledge or joining ongoing discussions!') }}
                    </p>
                    <a href="{{ route('student.forum.index') }}" class="btn-primary ripple-btn inline-flex items-center gap-2 relative z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        {{ __('Find Topics to Answer') }}
                    </a>
                </x-student.card>
            @endforelse
        </div>

        @if(isset($replies) && method_exists($replies, 'links') && $replies->hasPages())
            <div class="mt-8" data-aos="fade-up">
                {{ $replies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
