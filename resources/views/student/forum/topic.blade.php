@extends('layouts.app')

@section('title', $topic->title . ' — Forum')

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    {{-- Ambient Background --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full blur-3xl pointer-events-none -mr-24 -mt-24 z-0 bg-gradient-to-br from-violet-500/10 to-cyan-500/5"></div>
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/5 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm font-medium" data-aos="fade-down">
            <ol class="flex flex-wrap items-center gap-2 text-slate-500 dark:text-slate-400">
                <li>
                    <a href="{{ route('student.forum.index') }}" class="hover:text-primary-500 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        {{ __('Forum') }}
                    </a>
                </li>
                <li class="opacity-50">›</li>
                <li>
                    <a href="{{ route('student.forum.category', $category->slug) }}" class="hover:text-primary-500 transition-colors">
                        {{ $category->name }}
                    </a>
                </li>
                <li class="opacity-50">›</li>
                <li class="text-slate-900 dark:text-white font-bold truncate max-w-[200px] sm:max-w-[400px]">
                    {{ $topic->title }}
                </li>
            </ol>
        </nav>

        {{-- Main Topic Post --}}
        <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/50 dark:border-white/5 bg-white/60 dark:bg-slate-900/60 shadow-xl mb-8 relative group" data-aos="fade-up">

            {{-- Thread Line Context Box Style --}}
            <div class="absolute left-10 md:left-[3.25rem] top-24 bottom-0 w-0.5 bg-gradient-to-b from-primary-500/20 to-transparent hidden sm:block"></div>

            {{-- Topic Header --}}
            <div class="px-6 md:px-8 py-6 md:py-8 border-b border-slate-200/50 dark:border-white/5 relative z-10">
                <div class="flex items-start gap-4 md:gap-6">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0 shadow-lg shadow-primary-500/20 bg-gradient-to-br from-violet-500 to-cyan-500 border-2 border-white dark:border-slate-800 z-10 transition-transform group-hover:scale-105 group-hover:rotate-3">
                        {{ strtoupper(substr($topic->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">{{ $topic->title }}</h1>
                            @if($topic->is_pinned)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-black text-amber-500 bg-amber-500/10 border border-amber-500/20 uppercase tracking-wider shrink-0 shadow-sm mt-1 sm:mt-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    {{ __('Pinned') }}
                                </span>
                            @endif
                            @if($topic->is_locked)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-black text-rose-500 bg-rose-500/10 border border-rose-500/20 uppercase tracking-wider shrink-0 shadow-sm mt-1 sm:mt-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    {{ __('Locked') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400">
                            <span class="inline-flex items-center gap-1.5 text-violet-600 dark:text-violet-400 font-bold bg-violet-500/10 px-2 py-0.5 rounded-md border border-violet-500/10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $topic->user->name }}
                            </span>
                            <span class="opacity-50">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $topic->created_at->diffForHumans() }}
                            </span>
                            <span class="opacity-50">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ $topic->view_count }}
                            </span>
                            <span class="opacity-50">•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                {{ $topic->reply_count }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Topic Body --}}
            <div class="px-6 md:px-8 py-6 md:py-8 pl-4 sm:pl-[5.25rem] relative z-10">
                <div class="prose prose-slate dark:prose-invert max-w-none text-[15px] md:text-base text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    {!! nl2br(e($topic->content)) !!}
                </div>
            </div>
        </div>

        {{-- Replies Header --}}
        <div class="flex items-center gap-4 mb-6" data-aos="fade-up">
            <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0">
                    💬
                </span>
                {{ $topic->reply_count }} {{ $topic->reply_count === 1 ? __('Reply') : __('Replies') }}
            </h2>
            <div class="flex-1 h-px bg-gradient-to-r from-slate-200/50 dark:from-white/10 to-transparent"></div>
        </div>

        {{-- Replies Thread --}}
        <div class="space-y-4 mb-10 relative">
            {{-- Global Thread Line --}}
            @if($topic->replies->count() > 0)
                <div class="absolute left-6 md:left-8 top-8 bottom-8 w-0.5 bg-slate-200/50 dark:bg-white/5 hidden sm:block z-0"></div>
            @endif

            @foreach($topic->replies as $reply)
                <div class="glass-card rounded-[1.5rem] transition-all duration-300 {{ $reply->is_solution ? 'ring-2 ring-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/10 shadow-lg shadow-emerald-500/10 border-emerald-500/30' : 'border border-slate-200/50 dark:border-white/5 bg-white/50 dark:bg-slate-900/40 hover:bg-white/80 dark:hover:bg-slate-900/60' }} relative z-10 group"
                     data-aos="fade-up" data-aos-delay="{{ min($loop->index * 30, 300) }}">

                    <div class="p-6 md:p-8">
                        {{-- Reply Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3 md:gap-4 relative">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center font-bold text-sm md:text-base shrink-0 border-2 border-white dark:border-slate-800 z-10" style="background: rgba(139, 92, 246, 0.15); color: #8b5cf6;">
                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                </div>
                                
                                {{-- Connector line inside reply card pointing to avatar --}}
                                <div class="absolute -left-6 md:-left-8 top-1/2 w-4 md:w-6 h-0.5 bg-slate-200/50 dark:bg-white/10 hidden sm:block -z-10 group-hover:bg-primary-500/50 transition-colors"></div>

                                <div>
                                    <h4 class="text-sm md:text-base font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors">{{ $reply->user->name }}</h4>
                                    <div class="text-[11px] md:text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $reply->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @if($reply->is_solution)
                                <div class="inline-flex items-center gap-1.5 text-[10px] md:text-xs font-black px-3 py-1.5 rounded-xl text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 uppercase tracking-wider relative overflow-hidden">
                                    <div class="absolute inset-0 bg-emerald-500 mix-blend-overlay opacity-20 animate-pulse"></div>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span> {{ __('Solution') }}
                                </div>
                            @endif
                        </div>

                        {{-- Reply Body --}}
                        <div class="text-[14px] md:text-[15px] leading-relaxed mb-6 sm:pl-16 text-slate-700 dark:text-slate-300 font-medium">
                            {!! nl2br(e($reply->content)) !!}
                        </div>

                        {{-- Reply Actions --}}
                        <div class="flex items-center gap-4 sm:pl-16">
                            <button type="button" data-reply-id="{{ $reply->id }}"
                                    class="like-button inline-flex items-center gap-2 text-xs md:text-sm font-bold px-3 py-1.5 rounded-xl transition-all border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-rose-500/30 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 dark:hover:text-rose-400 active:scale-95 group/btn shadow-sm">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover/btn:text-rose-500 group-hover/btn:fill-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span>{{ __('Like') }}</span>
                                <span class="bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded-md text-[10px] group-hover/btn:bg-rose-200 dark:group-hover/btn:bg-rose-800" id="like-count-{{ $reply->id }}">{{ $reply->like_count }}</span>
                            </button>
                            
                            {{-- Admin Mark as Solution could go here --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        @if(!$topic->is_locked)
            <div class="glass-card overflow-hidden rounded-[2rem] border-t-4 border-t-primary-500 border-x border-b border-slate-200/50 dark:border-white/5 bg-white/80 dark:bg-slate-900/80 shadow-xl" data-aos="fade-up">

                <div class="px-6 md:px-8 py-5 border-b border-slate-200/50 dark:border-white/5 flex items-center gap-3 bg-slate-50/50 dark:bg-black/20">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        ✍️
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Post a Reply') }}</h3>
                </div>

                <div class="p-6 md:p-8 relative">
                    {{-- Current User Avatar Context --}}
                    <div class="hidden sm:flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-white shrink-0 bg-gradient-to-br from-primary-500 to-accent-500 shadow-md">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="mt-2 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Join the conversation as') }} <span class="text-primary-600 dark:text-primary-400">{{ auth()->user()->name ?? 'You' }}</span></div>
                    </div>

                    <form action="{{ route('student.forum.store-reply', [$category->slug, $topic->slug]) }}" method="POST" x-data="{ loading: false, content: '' }" @submit="loading = true">
                        @csrf
                        <div class="relative group">
                            <textarea name="content" x-model="content" rows="4"
                                      class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-2xl p-5 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none shadow-inner text-base font-medium placeholder-slate-400 dark:placeholder-slate-500"
                                      placeholder="{{ __('Share your thoughts or answer the question...') }}" required>{{ old('content') }}</textarea>
                            
                            @error('content')
                                <div class="flex items-center gap-1.5 mt-2 text-rose-500 text-sm font-bold bg-rose-500/10 px-3 py-1.5 rounded-lg inline-block border border-rose-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-xl border border-slate-100 dark:border-slate-700/30">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                {{ __('Be respectful and keep discussions on topic.') }}
                            </div>
                            
                            <button type="submit"
                                    class="btn-primary ripple-btn px-8 py-3 rounded-xl text-sm font-bold shadow-lg shadow-primary-500/25 flex items-center justify-center gap-2 sm:w-auto w-full group/submit"
                                    :disabled="loading || content.trim() === ''">
                                <span x-show="!loading" class="flex items-center gap-2">
                                    {{ __('Post Reply') }}
                                    <svg class="w-4 h-4 transition-transform group-hover/submit:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                                <span x-show="loading" x-cloak class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    {{ __('Publishing...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="glass-card rounded-[2rem] text-center p-12 relative overflow-hidden border-t-4 border-t-slate-400 bg-slate-50 dark:bg-slate-800/50" data-aos="fade-up">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMTU2LCAxNjMsIDE3NSwgMC4yKSIvPjwvc3ZnPg==')] opacity-50 z-0"></div>
                <div class="relative z-10 w-20 h-20 mx-auto rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center mb-6 shadow-inner text-4xl">
                    🔒
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 relative z-10">{{ __('This topic is closed') }}</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto relative z-10">
                    {{ __('An administrator has locked this discussion, which means it can no longer accept new replies.') }}
                </p>
                <a href="{{ route('student.forum.category', $category->slug) }}" class="btn-ghost inline-flex items-center gap-2 mt-6 relative z-10 border border-slate-300 dark:border-slate-600 font-bold hover:bg-slate-200 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Category') }}
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            // Prevent multiple clicks
            if(this.classList.contains('opacity-50')) return;
            
            const replyId = this.getAttribute('data-reply-id');
            const icon = this.querySelector('svg');
            const countSpan = document.getElementById(`like-count-${replyId}`);
            
            // Optimistic UI update
            this.classList.add('opacity-50', 'pointer-events-none');
            icon.classList.add('fill-rose-500', 'text-rose-500');
            
            fetch(`/student/forum/replies/${replyId}/like`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                this.classList.remove('opacity-50', 'pointer-events-none');
                
                if (data && data.like_count !== undefined) {
                    if (countSpan) countSpan.textContent = data.like_count;
                    
                    // Add permanent active styles
                    this.classList.remove('text-slate-600', 'dark:text-slate-300', 'hover:border-rose-500/30', 'hover:bg-rose-50', 'bg-slate-50', 'dark:bg-slate-800');
                    this.classList.add('text-rose-600', 'dark:text-rose-400', 'border-rose-500/50', 'bg-rose-50', 'dark:bg-rose-900/20');
                    countSpan.classList.add('bg-rose-200', 'dark:bg-rose-800', 'text-rose-700', 'dark:text-rose-300');
                    
                    // Trigger a small animation
                    countSpan.classList.add('animate-ping-once');
                    setTimeout(() => countSpan.classList.remove('animate-ping-once'), 300);
                }
            })
            .catch(() => { 
                // Revert optimistic UI on failure
                this.classList.remove('opacity-50', 'pointer-events-none');
                icon.classList.remove('fill-rose-500', 'text-rose-500');
                if (window.showNotification) window.showNotification('{{ __('Failed to like reply.') }}', 'error'); 
            });
        });
    });
});
</script>
<style>
@keyframes ping-once {
    0% { transform: scale(1); }
    50% { transform: scale(1.5); }
    100% { transform: scale(1); }
}
.animate-ping-once {
    animation: ping-once 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
</style>
@endpush
@endsection
