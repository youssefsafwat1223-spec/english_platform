@extends('layouts.app')

@section('title', __('Create Topic') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">

    <div class="student-container max-w-4xl relative z-10">
        {{-- Header Section --}}
        <div class="mb-10 text-center" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('New Conversation') }}
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-3">
                {{ __('Create New Topic') }}
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 font-medium max-w-2xl mx-auto flex items-center justify-center gap-2">
                {{ __('Posting in') }} 
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-bold border border-slate-200 dark:border-slate-700">
                    {{ $category->icon ?? '📁' }} {{ $category->name }}
                </span>
            </p>
        </div>

        {{-- Form Card --}}
        <form action="{{ route('student.forum.store-topic') }}" method="POST" x-data="{ loading: false, title: '{{ old('title', '') }}', content: '{{ old('content', '') }}' }" @submit="loading = true" data-aos="fade-up">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">

            <x-student.card padding="p-0" class="relative shadow-xl">
                {{-- Decorative Line --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 via-accent-500 to-primary-500 z-10"></div>

                <div class="p-6 md:p-10 space-y-8">
                    {{-- Title Input --}}
                    <div>
                        <label for="title" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 ml-1">
                            {{ __('Topic Title') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="title" type="text" name="title" x-model="title"
                                   class="w-full bg-slate-50 dark:bg-white/5 border @error('title') border-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-2xl px-5 py-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-inner text-lg font-bold placeholder-slate-400 dark:placeholder-slate-500" 
                                   required placeholder="{{ __('E.g. How to use phrasal verbs effectively?') }}">
                            
                            @error('title')
                                <div class="absolute -bottom-6 left-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-2 ml-1">{{ __('Make it clear and descriptive to attract helpful answers.') }}</p>
                    </div>

                    {{-- Content Input --}}
                    <div>
                        <label for="content" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 ml-1">
                            {{ __('Detailed Content') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative group">
                            <textarea id="content" name="content" x-model="content" rows="10" 
                                      class="w-full bg-slate-50 dark:bg-white/5 border @error('content') border-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-2xl px-5 py-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-inner text-base font-medium placeholder-slate-400 dark:placeholder-slate-500 resize-y min-h-[200px]" 
                                      required placeholder="{{ __('Describe your question, share your knowledge, or explain the context...') }}"></textarea>
                            
                            @error('content')
                                <div class="absolute -bottom-6 left-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="px-6 md:px-10 py-5 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/80 dark:bg-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('student.forum.category', $category->slug) }}" class="btn-ghost w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/5 font-bold text-slate-700 dark:text-slate-300 text-center transition-all">
                        {{ __('Cancel') }}
                    </a>
                    
                    <button type="submit" 
                            class="btn-primary ripple-btn w-full sm:w-auto px-8 py-3.5 rounded-xl shadow-lg shadow-primary-500/25 font-bold flex items-center justify-center gap-2 group/btn" 
                            :disabled="loading || title.trim() === '' || content.trim() === ''">
                        <span x-show="!loading" class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover/btn:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            {{ __('Post Topic') }}
                        </span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            {{ __('Publishing...') }}
                        </span>
                    </button>
                </div>
            </x-student.card>
        </form>
    </div>
</div>
@endsection






