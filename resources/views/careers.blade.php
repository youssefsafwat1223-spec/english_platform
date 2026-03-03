@extends('layouts.app')

@section('title', __('Careers') . ' — ' . config('app.name'))

@section('content')
{{-- Hero --}}
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
            <span class="badge-accent mb-4">🚀 {{ __('Careers') }}</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);">
                {{ __('Join Our') }} <span class="text-gradient">{{ __('Mission') }}</span>
            </h1>
            <p class="text-lg" style="color: var(--color-text-muted);">
                {{ __("We're building the future of English learning. Join our team and make an impact on thousands of students worldwide.") }}
            </p>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ __('Our Values') }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $values = [
                    ['icon' => '💡', 'title' => __('Innovation First'), 'desc' => __('We challenge the status quo and push boundaries in edtech every day.'), 'color' => 'amber'],
                    ['icon' => '🤝', 'title' => __('Student Obsessed'), 'desc' => __('Everything we build starts and ends with the learner experience.'), 'color' => 'primary'],
                    ['icon' => '🌍', 'title' => __('Impact Driven'), 'desc' => __('We measure success by the real-world impact we create for students.'), 'color' => 'emerald'],
                ];
            @endphp
            @foreach($values as $v)
                <div class="glass-card p-8 text-center group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-16 h-16 rounded-2xl bg-{{ $v['color'] }}-500/10 flex items-center justify-center text-3xl mx-auto mb-5 group-hover:scale-110 transition-transform">
                        {{ $v['icon'] }}
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ $v['title'] }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $v['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Open Positions --}}
<section class="py-20 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="badge-primary mb-4">🔍 {{ __('Open Positions') }}</span>
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ __('Current Openings') }}</h2>
        </div>

        @php
            $positions = [
                ['title' => __('Content Creator — English Language'), 'type' => __('Full-time / Remote'), 'desc' => __('Create engaging English lessons, quizzes, and multimedia content for our platform.')],
                ['title' => __('Full-Stack Developer (Laravel + Vue)'), 'type' => __('Full-time / Remote'), 'desc' => __('Build and maintain features on our learning platform using Laravel, Blade, and modern JavaScript.')],
                ['title' => __('Community Manager'), 'type' => __('Part-time / Remote'), 'desc' => __('Manage our student community, moderate the forum, and create engagement campaigns.')],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($positions as $pos)
                <div class="glass-card p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg group-hover:text-primary-500 transition-colors" style="color: var(--color-text);">{{ $pos['title'] }}</h3>
                        <p class="text-sm mt-1" style="color: var(--color-text-muted);">{{ $pos['desc'] }}</p>
                        <span class="inline-block mt-2 text-xs font-bold px-3 py-1 rounded-full bg-primary-500/10 text-primary-500">{{ $pos['type'] }}</span>
                    </div>
                    <a href="{{ route('contact') }}" class="btn-primary btn-sm shrink-0">{{ __('Apply Now') }}</a>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12 glass-card p-8" data-aos="fade-up">
            <div class="text-3xl mb-3">📬</div>
            <h3 class="font-bold mb-2" style="color: var(--color-text);">{{ __("Don't see a fit?") }}</h3>
            <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __("We're always open to hearing from talented people. Send us your CV!") }}</p>
            <a href="{{ route('contact') }}" class="btn-secondary btn-sm">{{ __('Contact Us') }}</a>
        </div>
    </div>
</section>
@endsection
