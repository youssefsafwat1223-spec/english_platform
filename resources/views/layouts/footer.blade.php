{{-- ═══════════════════════════════════════════════════════════════
     FOOTER — Animated Gradient with Glassmorphism
     ═══════════════════════════════════════════════════════════════ --}}
<footer class="relative z-10 mt-auto overflow-hidden">

    {{-- Animated gradient border top --}}
    <div class="h-px w-full bg-gradient-to-r from-transparent via-primary-500 to-transparent opacity-50"></div>

    {{-- Floating orbs behind footer --}}
    <div class="absolute bottom-0 left-1/4 w-96 h-96 rounded-full bg-primary-500/5 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full bg-accent-500/5 blur-3xl pointer-events-none"></div>

    <div class="relative" style="background: var(--glass-bg);">
        <div class="max-w-7xl mx-auto py-16 px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                {{-- Brand Column --}}
                <div class="lg:col-span-1" data-aos="fade-up" data-aos-delay="0">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg group-hover:shadow-neon-cyan transition-shadow duration-500">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gradient">Simple English</span>
                    </a>
                    <p class="text-sm mb-6 leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('The premium platform for mastering English. AI-powered learning, structured courses, and real results.') }}
                    </p>
                </div>

                {{-- Product Links --}}
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-6 text-gradient">{{ __('Product') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('student.courses.index') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Footer Courses') }}</a></li>
                    </ul>
                </div>

                {{-- Company Links --}}
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-6 text-gradient">{{ __('Company') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('About') }}</a></li>
                    </ul>
                </div>

            </div>

            {{-- Bottom Bar --}}
            <div class="border-t pt-8 flex flex-col md:flex-row justify-between items-center gap-4" style="border-color: var(--glass-border);">
                <p class="text-sm" style="color: var(--color-text-muted);">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
                </p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('privacy') }}" class="text-xs transition-colors hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Privacy Policy') }}</a>
                    <a href="{{ route('terms') }}" class="text-xs transition-colors hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Terms of Service') }}</a>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs" style="color: var(--color-text-muted);">{{ __('All systems operational') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>