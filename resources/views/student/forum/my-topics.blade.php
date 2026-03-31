@extends('layouts.app')

@section('title', __('My Topics') . ' - ' . __('Forum'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="student-container relative z-10">
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('My Topics') }}"
            subtitle="{{ __('Discussions and topics you have started in the community forum.') }}"
            mb="mb-10"
        >
            <x-slot name="actions">
                <a href="{{ route('student.forum.index') }}" class="btn-ghost flex items-center justify-center gap-2 group px-5 py-3 rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 font-bold text-slate-700 dark:text-slate-300 transition-all w-full sm:w-auto">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ __('Back to Forum') }}
                </a>
            </x-slot>
        </x-student.page-header>

        {{-- Topics List --}}
        <x-student.card padding="p-0" data-aos="fade-up">
            <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        📝
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Your Conversations') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Tracking') }} {{ $topics->total() ?? 0 }} {{ __('topics') }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-y border-slate-200/50 dark:border-white/5 text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">{{ __('Topic Details') }}</th>
                            <th class="px-6 py-4">{{ __('Category') }}</th>
                            <th class="px-6 py-4">{{ __('Engagement') }}</th>
                            <th class="px-6 py-4">{{ __('Created') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5">
                        @forelse($topics as $topic)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-start gap-3 w-48 sm:w-64 max-w-sm xl:w-auto whitespace-normal">
                                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0 border border-primary-500/20 font-bold text-sm">
                                            {{ strtoupper(substr($topic->title, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="{{ route('student.forum.topic', [$topic->category->slug, $topic->slug]) }}" class="font-bold text-slate-900 dark:text-white text-[15px] group-hover:text-primary-500 transition-colors line-clamp-2 leading-tight">
                                                    {{ $topic->title }}
                                                </a>
                                                @if($topic->is_locked)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded border border-rose-500/20 text-rose-500 bg-rose-500/10 text-[10px] shrink-0" title="Locked">🔒</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-5">
                                    <a href="{{ route('student.forum.category', $topic->category->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 hover:border-primary-500/30 hover:text-primary-500 transition-colors">
                                        {{ $topic->category->icon ?? '📁' }} {{ $topic->category->name }}
                                    </a>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4 text-sm font-bold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <span class="flex items-center gap-1.5" title="{{ $topic->reply_count }} Replies">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            {{ $topic->reply_count }}
                                        </span>
                                        <span class="flex items-center gap-1.5" title="{{ $topic->view_count }} Views">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            {{ $topic->view_count }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                        {{ $topic->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                                        {{ $topic->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('student.forum.topic', [$topic->category->slug, $topic->slug]) }}" class="btn-ghost inline-flex items-center justify-center p-2 rounded-xl text-primary-500 hover:bg-primary-500/10 hover:text-primary-600 transition-colors shadow-sm border border-transparent hover:border-primary-500/20" title="{{ __('View Topic') }}">
                                        <span class="mr-2 text-sm font-bold">{{ __('View') }}</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <x-student.empty-state
                                        title="{{ __('No Topics Yet') }}"
                                        message="{{ __('You haven\'t created any topics. Have a question or something to share? Start a conversation!') }}"
                                    >
                                        <x-slot name="icon">
                                            <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 6 1.4-4.2A8 8 0 1 1 20 12a8 8 0 0 1-8 8H4Z" />
                                            </svg>
                                        </x-slot>
                                        <x-slot name="actions">
                                            <a href="{{ route('student.forum.index') }}" class="btn-primary ripple-btn inline-flex">
                                                {{ __('Browse Forum Categories') }}
                                            </a>
                                        </x-slot>
                                    </x-student.empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($topics) && is_object($topics) && method_exists($topics, 'links') && $topics->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    {{ $topics->links() }}
                </div>
            @endif
        </x-student.card>
    </div>
</div>
@endsection







