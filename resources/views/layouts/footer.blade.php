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
                        <span class="text-xl font-bold text-gradient">{{ config('app.name', 'English Platform') }}</span>
                    </a>
                    <p class="text-sm mb-6 leading-relaxed" style="color: var(--color-text-muted);">
                        {{ __('The premium platform for mastering English. AI-powered learning, structured courses, and real results.') }}
                    </p>

                    {{-- Social Icons --}}
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);" aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);" aria-label="GitHub">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text-muted);" aria-label="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Product Links --}}
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-6 text-gradient">{{ __('Product') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('student.courses.index') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Courses') }}</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Pricing') }}</a></li>
                        <li><a href="{{ route('student.forum.index') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Forum') }}</a></li>
                        <li><a href="{{ route('student.certificates.index') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Certificates') }}</a></li>
                    </ul>
                </div>

                {{-- Company Links --}}
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-6 text-gradient">{{ __('Company') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('About') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Contact') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Blog') }}</a></li>
                        <li><a href="{{ route('careers') }}" class="text-sm transition-colors duration-200 hover:text-primary-500" style="color: var(--color-text-muted);">{{ __('Careers') }}</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-6 text-gradient">{{ __('Stay Updated') }}</h3>
                    <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __('Get the latest courses and learning tips.') }}</p>
                    <form class="flex gap-2" onsubmit="event.preventDefault(); showNotification('{{ __('Subscribed successfully!') }}', 'success');">
                        <input type="email" placeholder="{{ __('your@email.com') }}" required
                               class="input-glass flex-1 text-sm py-2.5 px-3">
                        <button type="submit"
                                class="btn-primary btn-sm ripple-btn whitespace-nowrap">
                            {{ __('Subscribe') }}
                        </button>
                    </form>
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