@extends('layouts.admin')
@section('title', $lesson->title . ' — Admin')
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ $lesson->title }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="btn-primary ripple-btn">{{ __('Edit Lesson') }}</a>
                <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" onsubmit="return confirm('Delete this lesson?');">@csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-red-500/10 text-red-500 text-sm font-bold hover:bg-red-500/20 transition-colors">{{ __('Delete') }}</button>
                </form>
                <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn-secondary">{{ __('← Back') }}</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Content') }}</h3></div>
                    <div class="glass-card-body">
                        @if($lesson->video_url)
                            <div class="aspect-video bg-black rounded-xl overflow-hidden mb-4">
                                @if($lesson->video_embed_url)
                                    <iframe class="w-full h-full" src="{{ $lesson->video_embed_url }}" title="{{ $lesson->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                @else
                                    <video src="{{ $lesson->video_url }}" controls class="w-full h-full"></video>
                                @endif
                            </div>
                        @endif
                        @if($lesson->text_content)
                            <div class="prose max-w-none" style="color: var(--color-text);"><p class="whitespace-pre-line">{{ $lesson->text_content }}</p></div>
                        @else
                            <p style="color: var(--color-text-muted);">{{ __('No text content added.') }}</p>
                        @endif
                    </div>
                </div>

                @if($lesson->attachments->count() > 0)
                    <div class="glass-card overflow-hidden" data-aos="fade-up">
                        <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Attachments') }}</h3></div>
                        <div class="glass-card-body divide-y" style="border-color: var(--color-border);">
                            @foreach($lesson->attachments as $attachment)
                                <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                    <div>
                                        <div class="font-bold text-sm" style="color: var(--color-text);">{{ $attachment->title }}</div>
                                        <div class="text-xs" style="color: var(--color-text-muted);">{{ strtoupper($attachment->file_type) }} - {{ $attachment->file_size }} KB</div>
                                    </div>
                                    <a href="{{ Storage::url($attachment->file_path) }}" class="text-primary-500 font-bold text-sm hover:underline" download>{{ __('Download') }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Related Resources') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Quiz') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->quiz ? $lesson->quiz->title : 'Not linked' }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Pronunciation') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->pronunciationExercise ? $lesson->pronunciationExercise->title : 'Not linked' }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Audio') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->audio ? 'Available' : 'Not available' }}</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card overflow-hidden" data-aos="fade-left">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Statistics') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Completion Rate') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ number_format($stats['completion_rate'] ?? 0, 1) }}%</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Comments') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['total_comments'] }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Notes') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $stats['total_notes'] }}</span></div>
                    </div>
                </div>

                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Lesson Details') }}</h3></div>
                    <div class="glass-card-body space-y-3">
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Order') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->order_index }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Free Preview') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->is_free ? 'Yes' : 'No' }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Has Quiz') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->has_quiz ? 'Yes' : 'No' }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Pronunciation') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->has_pronunciation_exercise ? 'Yes' : 'No' }}</span></div>
                        <div class="flex justify-between"><span class="text-sm" style="color: var(--color-text-muted);">{{ __('Duration') }}</span><span class="font-bold text-sm" style="color: var(--color-text);">{{ $lesson->formatted_duration ?? 'Not set' }}</span></div>
                    </div>
                </div>

                <div class="glass-card overflow-hidden" data-aos="fade-left" data-aos-delay="200">
                    <div class="glass-card-body space-y-2">
                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="btn-primary ripple-btn w-full text-center block">{{ __('Edit Lesson') }}</a>
                        <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" onsubmit="return confirm('Delete this lesson?');">@csrf @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-red-500/10 text-red-500 text-sm font-bold hover:bg-red-500/20 transition-colors">{{ __('Delete Lesson') }}</button>
                        </form>
                        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn-secondary w-full text-center block">{{ __('Back to Lessons') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
