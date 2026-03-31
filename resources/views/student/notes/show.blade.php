@extends('layouts.app')

@section('title', ($note->title ?? __('Note')) . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 mb-6 uppercase tracking-wider flex-wrap" data-aos="fade-down">
            <a href="{{ route('student.notes.index') }}" class="hover:text-primary-500 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('My Notes') }}
            </a>
            <span class="opacity-50">/</span>
            <span>{{ $note->lesson->course->title ?? __('Course') }}</span>
            <span class="opacity-50">/</span>
            <span class="text-slate-700 dark:text-slate-300">{{ $note->lesson->title ?? __('Lesson') }}</span>
        </div>

        {{-- Header --}}
        <x-student.page-header
            title="{{ $note->title ?? __('Untitled Note') }}"
            subtitle="{{ __('Last Updated') }} {{ $note->updated_at->format('M d, Y') }}"
        >
            <x-slot name="actions">
                <form action="{{ route('student.notes.delete', $note) }}" method="POST" onsubmit="return confirm(__('Are you sure you want to delete this note? This action cannot be undone.'))">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ghost flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-500/20 text-rose-500 bg-rose-500/10 hover:bg-rose-500 text-sm font-bold hover:text-white transition-colors group">
                        <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('Delete') }}
                    </button>
                </form>
            </x-slot>
        </x-student.page-header>

        {{-- Note Paper Style Card --}}
        <x-student.card padding="p-0" class="shadow-2xl relative" data-aos="fade-up">
            
            {{-- Paper styling details --}}
            <div class="absolute left-0 top-0 bottom-0 w-12 border-r border-rose-200/50 dark:border-rose-900/30 bg-rose-50/30 dark:bg-rose-900/10 hidden sm:block pointer-events-none"></div>
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/5 rounded-bl-[4rem] pointer-events-none transition-transform group-hover:scale-110"></div>
            
            <div class="p-8 sm:p-12 sm:pl-20 min-h-[400px]">
                <div class="prose prose-slate dark:prose-invert max-w-none prose-lg font-medium text-slate-700 dark:text-slate-300 leading-relaxed custom-prose">
                    {!! nl2br(e($note->content)) !!}
                </div>
            </div>
        </x-student.card>

    </div>
</div>

<style>
/* Custom Prose styles for note-like appearance */
.custom-prose p {
    margin-bottom: 1.5em;
    position: relative;
    z-index: 10;
}
.custom-prose {
    background-image: linear-gradient(rgba(148, 163, 184, 0.1) 1px, transparent 1px);
    background-size: 100% 2em;
    background-position: 0 1.25em; /* Align text with lines */
    line-height: 2em;
}
.dark .custom-prose {
    background-image: linear-gradient(rgba(148, 163, 184, 0.05) 1px, transparent 1px);
}
</style>
@endsection
