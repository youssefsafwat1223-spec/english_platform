@extends('layouts.app')

@section('title', $category->name . ' - Forum')

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">

    <div class="student-container relative z-10">
        {{-- Breadcrumb Navigation --}}
        <nav class="mb-6 text-sm font-medium" data-aos="fade-down">
            <ol class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <li>
                    <a href="{{ route('student.forum.index') }}" class="hover:text-primary-500 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        {{ __('Forum') }}
                    </a>
                </li>
                <li class="opacity-50">›</li>
                <li class="text-slate-900 dark:text-white font-bold">{{ $category->name }}</li>
            </ol>
        </nav>

        {{-- Category Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10" data-aos="fade-down">
            <div class="flex items-start gap-4 md:gap-6">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-white dark:bg-slate-800 shadow-md border border-slate-200 dark:border-white/5 flex items-center justify-center text-3xl shrink-0">
                    {{ $category->icon ?? '📁' }}
                </div>
                <div>
                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-2">{{ $category->name }}</h1>
                    <p class="text-base md:text-lg text-slate-600 dark:text-slate-400 font-medium max-w-2xl">{{ $category->description }}</p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('student.forum.create-topic', $category->slug) }}" class="btn-primary ripple-btn flex items-center gap-2 shadow-lg shadow-primary-500/25 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('New Topic') }}
                </a>
            </div>
        </div>

        {{-- Topics List --}}
        <div class="space-y-4">
            @forelse($topics as $topic)
                <x-student.card padding="p-0" class="{{ $topic->is_pinned ? 'border-amber-500/30 bg-amber-50/50 dark:bg-amber-900/10' : 'hover:bg-white/80 dark:hover:bg-slate-900/80' }} hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 300) }}">
                    @if($topic->is_pinned)
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-amber-500/10 to-transparent rounded-tr-[1.5rem] pointer-events-none"></div>
                    @endif
                    
                    <a href="{{ route('student.forum.topic', [$category->slug, $topic->slug]) }}" class="absolute inset-0 z-0"></a>
                    
                    <div class="p-6 md:p-8 relative z-10 pointer-events-none">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                            
                            {{-- Topic Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 flex-wrap mb-2">
                                    <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors truncate max-w-full">
                                        {{ $topic->title }}
                                    </h2>
                                    @if($topic->is_pinned) 
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase text-amber-600 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20 tracking-wider shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            {{ __('Pinned') }}
                                        </span> 
                                    @endif
                                    @if($topic->is_locked) 
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase text-rose-600 dark:text-rose-400 bg-rose-500/10 border border-rose-500/20 tracking-wider shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            {{ __('Locked') }}
                                        </span> 
                                    @endif
                                </div>
                                
                                <p class="text-[13px] md:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 md:line-clamp-1 mb-4 font-medium leading-relaxed">
                                    {{ Str::limit(strip_tags($topic->content), 150) }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-3 md:gap-4 text-[11px] md:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <span class="flex items-center gap-1.5 text-primary-600 dark:text-primary-400 bg-primary-500/10 px-2 py-0.5 rounded-md">
                                        {{ $topic->user->name }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $topic->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        {{ number_format($topic->view_count) }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        {{ number_format($topic->reply_count) }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Topic Stats/Last Reply --}}
                            <div class="hidden md:flex flex-col items-end shrink-0 pl-6 border-l border-slate-200 dark:border-white/10">
                                @if($topic->lastReplyUser)
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ __('Last reply by') }}</div>
                                    <div class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2 mb-1">
                                        <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] uppercase font-black">
                                            {{ substr($topic->lastReplyUser->name, 0, 1) }}
                                        </div>
                                        {{ $topic->lastReplyUser->name }}
                                    </div>
                                    <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ $topic->last_reply_at->diffForHumans() }}</div>
                                @else
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ __('Posted via') }}</div>
                                    <div class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2 mb-1">
                                        <div class="w-5 h-5 rounded-full bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-[10px] uppercase font-black">
                                            {{ substr($topic->user->name, 0, 1) }}
                                        </div>
                                        {{ $topic->user->name }}
                                    </div>
                                @endif
                                
                                <div class="mt-4 pointer-events-auto">
                                    <span class="btn-ghost px-3 py-1.5 rounded-lg text-xs font-bold text-primary-500 bg-primary-500/10 border border-primary-500/20 flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ __('Read More') }}
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-student.card>
            @empty
                <x-student.empty-state
                    title="{{ __('No topics found here yet') }}"
                    message="{{ __('Be the first to start a conversation in this category!') }}"
                    data-aos="fade-up"
                >
                    <x-slot name="icon">
                        <div class='text-5xl'>💬</div>
                    </x-slot>
                    <x-slot name="actions">
                        <a href="{{ route('student.forum.create-topic', $category->slug) }}" class="btn-primary ripple-btn inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Create First Topic') }}
                        </a>
                    </x-slot>
                </x-student.empty-state>
            @endforelse
        </div>

        @if(isset($topics) && method_exists($topics, 'links') && $topics->hasPages())
            <div class="mt-10" data-aos="fade-up">
                {{ $topics->links() }}
            </div>
        @endif
    </div>
</div>
@endsection






