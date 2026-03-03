@extends('layouts.app')

@section('title', __('Pricing') . ' — ' . config('app.name'))

@section('content')
{{-- Hero --}}
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="badge-primary mb-4" data-aos="fade-up">💎 {{ __('Pricing') }}</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);" data-aos="fade-up" data-aos-delay="100">
            {{ __('Invest in Your') }} <span class="text-gradient">{{ __('Future') }}</span>
        </h1>
        <p class="text-lg max-w-2xl mx-auto mb-12" style="color: var(--color-text-muted);" data-aos="fade-up" data-aos-delay="200">
            {{ __('Choose a plan that fits your learning goals. Every course comes with lifetime access, quizzes, certificates, and community support.') }}
        </p>
    </div>
</section>

{{-- Plans --}}
<section class="pb-24 relative -mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Highlight Features --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @php
                $features = [
                    ['icon' => '🎓', 'title' => __('Lifetime Access'), 'desc' => __('Pay once, learn forever. No recurring subscriptions.'), 'color' => 'primary'],
                    ['icon' => '📜', 'title' => __('Verified Certificates'), 'desc' => __('Earn certificates with unique verification codes.'), 'color' => 'accent'],
                    ['icon' => '🏆', 'title' => __('Achievements & XP'), 'desc' => __('Track your progress and compete on the leaderboard.'), 'color' => 'emerald'],
                ];
            @endphp

            @foreach($features as $f)
                <div class="glass-card p-6 text-center group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $f['color'] }}-500/10 flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 transition-transform">
                        {{ $f['icon'] }}
                    </div>
                    <h3 class="font-bold mb-2" style="color: var(--color-text);">{{ $f['title'] }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Course Pricing Cards --}}
        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ __('Available Courses') }}</h2>
            <p class="text-sm mt-2" style="color: var(--color-text-muted);">{{ __('Pick a course and start learning today') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
                <div class="glass-card overflow-hidden group relative" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    @if($course->price == 0)
                        <div class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold shadow-lg">{{ __('FREE') }}</div>
                    @endif

                    <div class="p-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xl font-bold mb-4 shadow-lg">
                            {{ substr($course->title, 0, 1) }}
                        </div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-primary-500 transition-colors" style="color: var(--color-text);">{{ $course->title }}</h3>
                        <p class="text-sm mb-6 line-clamp-2" style="color: var(--color-text-muted);">{{ $course->description }}</p>

                        <div class="flex items-end gap-2 mb-6">
                            @if($course->price > 0)
                                <span class="text-3xl font-extrabold" style="color: var(--color-text);">{{ number_format($course->price) }}</span>
                                <span class="text-sm mb-1" style="color: var(--color-text-muted);">{{ __('USD') }}</span>
                            @else
                                <span class="text-3xl font-extrabold text-emerald-500">{{ __('Free') }}</span>
                            @endif
                        </div>

                        <ul class="space-y-2 mb-6">
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $course->lessons_count ?? $course->lessons()->count() }} {{ __('Lessons') }}
                            </li>
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Quizzes & Assessments') }}
                            </li>
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Certificate of Completion') }}
                            </li>
                        </ul>

                        <a href="{{ route('student.courses.show', $course) }}" class="block w-full text-center py-3 rounded-xl font-bold transition-all {{ $course->price == 0 ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $course->price == 0 ? __('Start Free') : __('View Course') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <div class="text-5xl mb-4">📚</div>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">{{ __('Coming Soon') }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ __('New courses are being prepared. Stay tuned!') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-20 relative">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="badge-accent mb-4">❓ {{ __('FAQ') }}</span>
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ __('Common Questions') }}</h2>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            @php
                $faqs = [
                    ['q' => __('Can I try courses before buying?'), 'a' => __('Yes! We offer free courses that you can explore without any commitment. Just sign up and start learning.')],
                    ['q' => __('Is there a refund policy?'), 'a' => __('We offer a refund within the first 7 days of purchase if you are not satisfied with the course.')],
                    ['q' => __('Do certificates expire?'), 'a' => __('No. Once you earn a certificate, it is yours forever and can be verified online at any time.')],
                    ['q' => __('Can I access courses on mobile?'), 'a' => __('Absolutely! Our platform is fully responsive and works perfectly on all devices.')],
                ];
            @endphp

            @foreach($faqs as $i => $faq)
                <div class="glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                    <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold text-sm transition-colors"
                            style="color: var(--color-text);">
                        {{ $faq['q'] }}
                        <svg class="w-4 h-4 shrink-0 transition-transform" :class="active === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === {{ $i }}" x-collapse>
                        <div class="px-6 pb-4 text-sm" style="color: var(--color-text-muted);">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
