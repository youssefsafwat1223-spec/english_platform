@extends('layouts.app')

@section('title', __('Community Forum') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('Community') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500'>{{ __('Forum') }}</span>"
            subtitle="{{ __('Connect with fellow learners, share your knowledge, ask questions, and grow together.') }}"
            badge="💬 {{ __('Discussion Board') }}"
            badgeColor="violet"
            mb="mb-12"
        >
            <x-slot name="actions">
                <a href="{{ route('student.forum.my-topics') }}" class="btn-primary ripple-btn flex items-center justify-center gap-2 px-6 py-3 rounded-xl shadow-lg shadow-violet-500/25 font-bold bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 border-none text-white transition-all transform hover:scale-105 w-full sm:w-auto">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('My Topics') }}
                </a>
            </x-slot>
        </x-student.page-header>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Main Categories --}}
            <div class="xl:col-span-2 space-y-5">
                @foreach($categories as $category)
                    <x-student.card padding="p-0" class="group hover:bg-white/80 dark:hover:bg-slate-900/80 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="p-6 md:p-8 relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                                <div class="flex items-start gap-5 flex-1 w-full">
                                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-white/5 flex items-center justify-center text-3xl shrink-0 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                                        {{ $category->icon ?? '💬' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('student.forum.category', $category->slug) }}" class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors inline-block mb-1">
                                            {{ $category->name }}
                                        </a>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium leading-relaxed mb-4">
                                            {{ $category->description }}
                                        </p>
                                        
                                        @if($category->latest_topic)
                                            <div class="inline-flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 w-full">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-none sm:animate-pulse"></span>
                                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 shrink-0">{{ __('Latest') }}</span>
                                                </div>
                                                <a href="{{ route('student.forum.topic', [$category->slug, $category->latest_topic->slug]) }}" class="text-sm font-bold text-slate-900 dark:text-white hover:text-primary-500 truncate transition-colors w-full">
                                                    {{ $category->latest_topic->title }}
                                                </a>
                                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 shrink-0 ml-auto hidden sm:block">
                                                    {{ $category->latest_topic->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex sm:flex-col items-center sm:items-end justify-start sm:justify-center gap-6 sm:gap-2 shrink-0 border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-white/10 pt-4 sm:pt-0 sm:pl-6">
                                    <div class="text-center sm:text-right">
                                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($category->topics_count) }}</div>
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Topics') }}</div>
                                    </div>
                                    <div class="text-center sm:text-right hidden sm:block">
                                        <div class="text-lg font-bold text-slate-600 dark:text-slate-300">{{ number_format($category->total_posts) }}</div>
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Posts') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-student.card>
                @endforeach
            </div>

            {{-- Sidebar --}}
            <div class="xl:col-span-1 space-y-6">
                {{-- Recent Topics --}}
                <x-student.card padding="p-0" class="border-t-4 border-t-primary-500 shadow-xl shadow-primary-500/5" data-aos="fade-left">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0">
                            ⏱️
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Recent Topics') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-5">
                            @foreach($recentTopics as $topic)
                                <div class="group relative pl-4">
                                    <div class="absolute top-2 bottom-0 left-0 w-1 rounded-full bg-slate-200 dark:bg-slate-700/50 group-hover:bg-primary-500 transition-colors"></div>
                                    <a href="{{ route('student.forum.topic', [$topic->category->slug, $topic->slug]) }}" class="block font-bold text-slate-900 dark:text-white hover:text-primary-500 transition-colors line-clamp-2 text-sm leading-tight mb-2">
                                        {{ $topic->title }}
                                    </a>
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                        <div class="w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[8px] font-black text-slate-700 dark:text-slate-300">
                                            {{ substr($topic->user->name, 0, 1) }}
                                        </div>
                                        <span>{{ $topic->user->name }}</span>
                                        <span>•</span>
                                        <span>{{ $topic->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($recentTopics->isEmpty())
                            <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('No recent topics found.') }}
                            </div>
                        @endif
                    </div>
                </x-student.card>

                {{-- Popular Topics --}}
                <x-student.card padding="p-0" class="border-t-4 border-t-amber-500 shadow-xl shadow-amber-500/5" data-aos="fade-left" data-aos-delay="100">
                    <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shrink-0">
                            🔥
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Popular Topics') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($popularTopics as $topic)
                                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 hover:border-amber-500/30 transition-colors group">
                                    <a href="{{ route('student.forum.topic', [$topic->category->slug, $topic->slug]) }}" class="block font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors line-clamp-2 text-sm leading-tight mb-3">
                                        {{ $topic->title }}
                                    </a>
                                    <div class="flex items-center gap-4 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> {{ number_format($topic->view_count) }}</span>
                                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586"></path></svg> {{ number_format($topic->reply_count) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($popularTopics->isEmpty())
                            <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('No popular topics yet.') }}
                            </div>
                        @endif
                    </div>
                </x-student.card>
            </div>
        </div>
    </div>
</div>
@endsection