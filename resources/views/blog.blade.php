@extends('layouts.app')

@section('title', __('Blog') . ' — ' . config('app.name'))

@section('content')
<section class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div data-aos="fade-up">
            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-5xl mx-auto mb-8 shadow-2xl animate-float">
                ✍️
            </div>
            <span class="badge-primary mb-4">📝 {{ __('Blog') }}</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                {{ __('Our Blog is') }} <span class="text-gradient">{{ __('Coming Soon') }}</span>
            </h1>
            <p class="text-lg max-w-2xl mx-auto mb-10" style="color: var(--color-text-muted);">
                {{ __("We're crafting insightful articles on English learning tips, grammar guides, study techniques, and success stories. Stay tuned!") }}
            </p>
        </div>

        {{-- Topics Preview --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12" data-aos="fade-up" data-aos-delay="200">
            @php
                $topics = [
                    ['icon' => '📖', 'title' => __('Learning Tips'), 'desc' => __('Proven strategies to learn English faster')],
                    ['icon' => '🎯', 'title' => __('Grammar Guides'), 'desc' => __('Master the rules that matter most')],
                    ['icon' => '🌟', 'title' => __('Success Stories'), 'desc' => __('Real students, real transformations')],
                ];
            @endphp
            @foreach($topics as $topic)
                <div class="glass-card p-6 text-center">
                    <div class="text-3xl mb-3">{{ $topic['icon'] }}</div>
                    <h3 class="font-bold text-sm mb-1" style="color: var(--color-text);">{{ $topic['title'] }}</h3>
                    <p class="text-xs" style="color: var(--color-text-muted);">{{ $topic['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Notify Me --}}
        <div class="glass-card p-8 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="300">
            <h3 class="font-bold mb-4" style="color: var(--color-text);">{{ __('Get Notified When We Launch') }}</h3>
            <form class="flex gap-2" onsubmit="event.preventDefault(); showNotification(__('You will be notified when the blog launches!'), 'success');">
                <input type="email" placeholder="{{ __('your@email.com') }}" required class="input-glass flex-1 text-sm py-2.5 px-3">
                <button type="submit" class="btn-primary btn-sm ripple-btn whitespace-nowrap">{{ __('Notify Me') }}</button>
            </form>
        </div>
    </div>
</section>
@endsection
