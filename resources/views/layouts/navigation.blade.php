{{-- ═══════════════════════════════════════════════════════════════
     NAVIGATION — Frosted Glass Navbar with Glassmorphism
     ═══════════════════════════════════════════════════════════════ --}}
<nav x-data="{ 
         open: false, 
         userOpen: false, 
         scrolled: false, 
         lastScroll: 0, 
         showNav: true,
         gtLoaded: false 
     }"
     @scroll.window.throttle.50ms="
        const currentScroll = window.pageYOffset;
        scrolled = currentScroll > 20;
        if (currentScroll > lastScroll && currentScroll > 100) {
            showNav = false;
        } else {
            showNav = true;
        }
        lastScroll = currentScroll;
     "
     @toggle-mobile-menu.window="open = !open"
     :class="[
        scrolled ? 'py-1 sm:py-0' : '',
        !showNav ? '-translate-y-24 sm:translate-y-0' : 'translate-y-0'
     ]"
     class="fixed top-3 left-3 right-3 sm:top-4 sm:left-4 sm:right-4 lg:left-8 lg:right-8 z-50 transition-all duration-500 ease-in-out rounded-2xl bg-white/95 dark:bg-[#020617]/95 backdrop-blur-xl"
     style="overflow: visible !important; box-shadow: none !important; border: none !important;">
    <div class="px-3 sm:px-4 md:px-6 lg:px-8 overflow-visible">
        <div class="flex justify-between h-14 sm:h-16 lg:h-20 items-center overflow-visible">

            {{-- ─── Logo — Centered on Mobile ─── --}}
            <div class="flex-1 flex lg:hidden items-center justify-center overflow-visible">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Simple English Logo" class="h-8 w-auto object-contain transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <div class="flex flex-col">
                        <span class="font-sans text-lg font-black tracking-tighter text-slate-900 dark:text-white whitespace-nowrap group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors leading-none">
                            Simple English
                        </span>
                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 tracking-wider uppercase mt-1 leading-none text-center">
                            {{ __('The easiest way to learn English') }}
                        </span>
                    </div>
                </a>
            </div>

            {{-- ─── Desktop Header Items ─── --}}
            <div class="hidden lg:flex items-center gap-2 sm:gap-4 lg:gap-8 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Simple English Logo" class="h-7 sm:h-10 w-auto object-contain transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <div class="hidden sm:flex flex-col">
                        <span class="font-sans text-lg lg:text-2xl font-black tracking-tighter text-slate-900 dark:text-white whitespace-nowrap group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors leading-none">
                            Simple English
                        </span>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 tracking-wider uppercase mt-0.5 leading-none">
                            {{ __('The easiest way to learn English') }}
                        </span>
                    </div>
                </a>

                {{-- ─── Desktop Links ─── --}}
                <div class="hidden lg:flex items-center gap-1">

                    @auth
                        @if(auth()->user()->is_student)
                            <a href="{{ route('student.dashboard') }}"
                               class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('student.courses.my-courses') }}"
                               class="nav-link {{ request()->routeIs('student.courses.my-courses') ? 'active' : '' }}">
                                {{ __('My Courses') }}
                            </a>
                            <a href="{{ route('student.quizzes.my-attempts') }}"
                               class="nav-link {{ request()->routeIs('student.quizzes.my-attempts') ? 'active' : '' }}">
                                {{ __('All Attempts') }}
                            </a>
                            <a href="{{ route('student.certificates.index') }}"
                               class="nav-link {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                                {{ __('Certificates') }}
                            </a>
                            <a href="{{ route('student.forum.index') }}"
                               class="nav-link {{ request()->routeIs('student.forum.*') ? 'active' : '' }}">
                                {{ __('Forum') }}
                            </a>
                            <a href="{{ route('student.games.index') }}"
                               class="nav-link {{ request()->routeIs('student.games.*') ? 'active' : '' }}">
                                {{ __('Games Nav') }}
                            </a>
                            <a href="{{ route('student.battle.index') }}"
                               class="nav-link {{ request()->routeIs('student.battle.*') ? 'active' : '' }}">
                                {{ __('Battle') }}
                            </a>
                            <a href="{{ route('student.telegram.guide') }}"
                               class="nav-link {{ request()->routeIs('student.telegram.guide') ? 'active' : '' }}">
                                {{ __('Telegram Bot') }}
                            </a>
                        @elseif(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('admin.courses.index') }}"
                               class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                                {{ __('Courses') }}
                            </a>
                            
                            <!-- Exams Dropdown -->
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="nav-link flex items-center gap-1 {{ request()->routeIs('admin.questions.*') || request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                                    {{ __('Exams') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <a href="{{ route('admin.quizzes.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.quizzes.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        {{ __('Quizzes') }}
                                    </a>
                                    <a href="{{ route('admin.questions.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.questions.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        {{ __('Questions Nav') }}
                                    </a>
                                    <a href="{{ route('admin.games.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.games.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        🎮 {{ __('Game Arena') }}
                                    </a>
                                    <a href="{{ route('admin.settings.battle') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.battle') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        ⚔️ {{ __('Battle Settings') }}
                                    </a>
                                </div>
                            </div>

                            <!-- Language Switcher -->
                            <div class="flex items-center">
                                <a href="{{ route('switch-lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}"
                                   class="flex items-center justify-center px-2.5 h-7 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 text-[11px] font-bold tracking-wide text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors"
                                   title="{{ __('Switch Language') }}">
                                    AR / EN
                                </a>
                            </div>

                            <!-- Users Dropdown -->
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="nav-link flex items-center gap-1 {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.email-campaigns.*') ? 'active' : '' }}">
                                    {{ __('Users') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <a href="{{ route('admin.students.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.students.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        {{ __('Students') }}
                                    </a>
                                    <a href="{{ route('admin.email-campaigns.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.email-campaigns.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        {{ __('Email Campaigns') }}
                                    </a>
                                    <a href="{{ route('admin.testimonials.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.testimonials.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        💬 {{ __('آراء الطلاب') }}
                                    </a>
                                    <a href="{{ route('admin.promo-videos.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.promo-videos.*') ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                        🎬 {{ __('عينة الشروحات') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- ─── Right Side ─── --}}
            <div class="flex items-center gap-1 sm:gap-2 md:gap-3">

                {{-- Theme Toggle --}}
                <button type="button"
                        onclick="toggleTheme()"
                        class="hidden lg:flex relative w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-xl items-center justify-center transition-all duration-300 hover:bg-gray-100 dark:hover:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10"
                        aria-label="{{ __('Toggle dark mode') }}">
                    {{-- Moon (show in dark mode) --}}
                    <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4 sm:w-5 sm:h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    {{-- Sun (show in light mode) --}}
                    <svg id="theme-toggle-light-icon" class="hidden w-4 h-4 sm:w-5 sm:h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314L7.05 7.05m9.9 9.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </button>

                {{-- WhatsApp Support Button moved to Settings Menu --}}

                {{-- Notification Bell (Desktop Only) --}}
                @auth
                    @if(auth()->user()->is_student)
                        <div class="hidden lg:block relative z-[70]" 
                             x-data="{
                                notifOpen: false,
                                unreadCount: {{ auth()->user()->unreadNotifications->count() }},
                                notifications: @js(auth()->user()->notifications()->orderBy('created_at','desc')->take(10)->get()->map(fn($n) => ['id'=>$n->id,'title'=>$n->title,'message'=>\Illuminate\Support\Str::limit($n->message,60),'is_read'=>$n->is_read,'action_url'=>route('student.notifications.mark-as-read',$n->id),'time_ago'=>$n->created_at->diffForHumans()])),
                                async fetchNotifications() {
                                    try {
                                        const res = await fetch('{{ route("student.notifications.recent-json") }}');
                                        const data = await res.json();
                                        this.notifications = data.notifications;
                                    } catch(e) {}
                                }
                             }"
                             @click.outside="notifOpen = false">
                            <button @click="notifOpen = !notifOpen; if(notifOpen) fetchNotifications();"
                                    class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:bg-gray-100 dark:hover:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10"
                                    aria-label="Notifications">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span x-show="unreadCount > 0" x-cloak
                                      class="absolute -top-0.5 -right-0.5 w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-black flex items-center justify-center shadow-lg animate-pulse">
                                    <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                                </span>
                            </button>

                            <div x-cloak x-show="notifOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 class="absolute top-full mt-3 w-96 ltr:right-0 rtl:left-0 glass-card overflow-hidden rounded-[1.5rem] shadow-2xl border border-white/20 dark:border-white/10 z-[120]">
                                <div class="px-5 py-4 border-b border-slate-200 dark:border-white/10">
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">{{ __('Notifications') }}</h3>
                                </div>
                                <div class="max-h-80 overflow-y-auto hide-scrollbar">
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="notif.action_url" class="flex items-start gap-3 px-5 py-3 hover:bg-slate-100 dark:hover:bg-white/5" :class="!notif.is_read ? 'bg-primary-500/5' : ''">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold" x-text="notif.title"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="notif.message"></p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-1" x-text="notif.time_ago"></p>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                <div class="border-t border-slate-200 dark:border-white/10 p-3 text-center">
                                    <a href="{{ route('student.notifications.index') }}" class="text-sm font-bold text-primary-500">{{ __('View All') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth

                {{-- Language Toggle --}}
                <a href="{{ route('switch-lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}"
                   class="hidden lg:flex relative items-center justify-center px-3 h-8 rounded-lg shadow-sm border border-slate-300 dark:border-slate-600 text-xs font-bold tracking-wide text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors"
                   title="{{ __('Switch Language') }}">
                    AR / EN
                </a>

                {{-- Desktop User Section --}}
                <div class="hidden lg:flex items-center gap-3 overflow-visible">
                    @auth
                        <div class="relative z-[70]" @click.outside="userOpen = false">
                            <button type="button" @click="userOpen = !userOpen"
                                    class="flex items-center gap-3 px-3 py-2 rounded-xl border transition-all duration-300 hover:-translate-y-0.5"
                                    :class="scrolled
                                        ? 'border-gray-200/50 dark:border-white/10 hover:border-primary-300 dark:hover:border-primary-500/30'
                                        : 'border-transparent hover:border-gray-200 dark:hover:border-white/10'">
                                {{-- Avatar --}}
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-semibold" style="color: var(--color-text);">{{ auth()->user()->name }}</div>
                                    <div class="text-xs" style="color: var(--color-text-muted);">{{ auth()->user()->is_admin ? __('Administrator') : __('Student') }}</div>
                                </div>
                                <svg class="w-4 h-4 transition-transform" :class="userOpen ? 'rotate-180' : ''" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div x-cloak x-show="userOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                                 class="absolute top-full mt-3 w-64 max-w-[calc(100vw-2rem)] max-h-[calc(100vh-7rem)] overflow-y-auto overflow-x-hidden hide-scrollbar glass-card rounded-[1.5rem] shadow-2xl border border-white/20 dark:border-white/10 ltr:right-0 ltr:left-auto rtl:left-0 rtl:right-auto ltr:origin-top-right rtl:origin-top-left z-[120]">
                                <div class="p-3 space-y-1.5 relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent pointer-events-none"></div>
                                    @if(auth()->user()->is_student)
                                        <a href="{{ route('student.profile.show') }}"
                                           class="relative z-10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:pl-4 group"
                                           style="color: var(--color-text);">
                                            <div class="w-9 h-9 rounded-lg bg-primary-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-primary-500 text-primary-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            {{ __('Profile') }}
                                        </a>
                                        <a href="{{ route('student.notifications.index') }}"
                                           class="relative z-10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:pl-4 group"
                                           style="color: var(--color-text);">
                                            <div class="w-9 h-9 rounded-lg bg-violet-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-violet-500 text-violet-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                            </div>
                                            {{ __('Notifications') }}
                                        </a>
                                        <a href="{{ route('student.profile.achievements') }}"
                                           class="relative z-10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:pl-4 group"
                                           style="color: var(--color-text);">
                                            <div class="w-9 h-9 rounded-lg bg-accent-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-accent-500 text-accent-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                            </div>
                                            {{ __('Achievements') }}
                                        </a>
                                    @elseif(auth()->user()->is_admin)
                                        <a href="{{ route('admin.dashboard') }}"
                                           class="relative z-10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:pl-4 group"
                                           style="color: var(--color-text);">
                                            <div class="w-9 h-9 rounded-lg bg-primary-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-primary-500 text-primary-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                            </div>
                                            {{ __('Admin Dashboard') }}
                                        </a>
                                        <a href="{{ route('admin.settings.index') }}"
                                           class="relative z-10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:pl-4 group"
                                           style="color: var(--color-text);">
                                            <div class="w-9 h-9 rounded-lg bg-slate-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-slate-500 text-slate-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            {{ __('Settings') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="border-t p-3 relative" style="border-color: var(--glass-border);">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="relative z-10 flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 hover:pr-4 transition-all group">
                                            <div class="w-9 h-9 rounded-lg bg-red-500/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            </div>
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-ghost text-sm font-medium">
                            {{ __('Log In') }}
                        </a>
                        <a href="{{ route('register') }}"
                           class="btn-primary btn-sm ripple-btn">
                            {{ __('Get Started') }}
                            <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Toggle --}}
                @if(!auth()->check() || !auth()->user()->is_student)
                <button @click="open = !open"
                        class="lg:hidden inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl transition-colors hover:bg-gray-100 dark:hover:bg-white/5"
                        aria-label="{{ __('Toggle navigation') }}">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" style="color: var(--color-text);">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ─── Mobile Menu (Guest & Admin) ─── --}}
    @if(!auth()->check() || (!auth()->user()->is_student))
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden mx-4 mt-2 glass-card overflow-y-auto overflow-x-hidden max-h-[85vh] hide-scrollbar border border-white/10 shadow-2xl pb-4">
        <div class="p-4 space-y-1">
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">{{ __('Dashboard') }}</a>
                    <a href="{{ route('admin.courses.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">{{ __('Courses') }}</a>
                    <a href="{{ route('admin.questions.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">{{ __('Questions') }}</a>
                    <a href="{{ route('admin.quizzes.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">{{ __('Quizzes') }}</a>
                    <a href="{{ route('admin.students.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">{{ __('Students') }}</a>
                    <a href="{{ route('admin.email-campaigns.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors hover:bg-primary-500/5" style="color: var(--color-text);">📧 {{ __('Email Campaigns') }}</a>
                @endif
            @endauth
        </div>
        @auth
            <div class="border-t p-4" style="border-color: var(--glass-border);">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold shadow-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm" style="color: var(--color-text);">{{ auth()->user()->name }}</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-sm text-red-500 hover:bg-red-500/5 transition-colors font-medium">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        @else
            <div class="border-t p-4 flex gap-3" style="border-color: var(--glass-border);">
                <a href="{{ route('login') }}" class="btn-secondary btn-sm flex-1 text-center">{{ __('Log In') }}</a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm flex-1 text-center">{{ __('Get Started') }}</a>
            </div>
        @endauth
    </div>
    @endif

</nav>

{{-- ─── Floating WhatsApp Support Button ─── --}}
@auth
    @if(auth()->user()->is_student)
        <a href="https://wa.me/966537191862" target="_blank"
           class="hidden lg:flex fixed bottom-6 right-6 lg:bottom-8 lg:right-8 z-50 w-14 h-14 rounded-full bg-[#25D366] text-white items-center justify-center shadow-2xl hover:scale-110 hover:shadow-[#25D366]/50 transition-all duration-300 group"
           aria-label="WhatsApp Support">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
            <span class="absolute right-0 top-1/2 -translate-y-1/2 mr-16 w-max opacity-0 lg:group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-bold py-1.5 px-3 rounded-xl shadow-lg pointer-events-none ltr:block rtl:hidden">
                {{ __('Need Help?') }}
            </span>
            <span class="absolute left-0 top-1/2 -translate-y-1/2 ml-16 w-max opacity-0 lg:group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-bold py-1.5 px-3 rounded-xl shadow-lg pointer-events-none rtl:block ltr:hidden">
                {{ __('Need Help?') }}
            </span>
        </a>
    @endif
@endauth

{{-- ─── Mobile Bottom Tab Bar & Drawer (Students Only) ─── --}}
@auth
    @if(auth()->user()->is_student && !request()->routeIs('student.quizzes.*') && !request()->routeIs('student.lessons.show') && !request()->routeIs('student.pronunciation.show') && !request()->routeIs('student.games.room') && !request()->routeIs('student.battle.play') && !request()->routeIs('student.battle.lobby'))
        <div x-data="{ 
            isDrawerOpen: false,
            lastScroll: 0,
            showNav: true,
            unreadCount: {{ auth()->user()->unreadNotifications->count() }},
            async pollUnread() {
                try {
                    const res = await fetch('{{ route("student.notifications.unread-count") }}');
                    const data = await res.json();
                    this.unreadCount = data.count;
                } catch(e) {}
            },
            init() {
                setInterval(() => this.pollUnread(), 30000); // Poll every 30s
            }
         }" 
         @scroll.window.throttle.50ms="
            const currentScroll = window.pageYOffset;
            if (currentScroll > lastScroll && currentScroll > 100) {
                showNav = false;
            } else {
                showNav = true;
            }
            lastScroll = currentScroll;
         "
         class="lg:hidden">
        
        {{-- ─── Student Settings Drawer (Consolidated Scope) ─── --}}
        <div x-data="{ 
                notifications: @js(auth()->user()->notifications()->orderBy('created_at','desc')->take(5)->get()->map(fn($n) => ['id'=>$n->id,'title'=>$n->title,'message'=>\Illuminate\Support\Str::limit($n->message,60),'is_read'=>$n->is_read,'action_url'=>route('student.notifications.mark-as-read',$n->id),'time_ago'=>$n->created_at->diffForHumans()])),
                async fetchNotifications() {
                    try {
                        const res = await fetch('{{ route("student.notifications.recent-json") }}');
                        const data = await res.json();
                        this.notifications = data.notifications;
                    } catch(e) {}
                }
             }" 
             x-show="isDrawerOpen" 
             x-cloak 
             @click.outside="isDrawerOpen = false"
             x-effect="if(isDrawerOpen) fetchNotifications()"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 translate-y-12 scale-90"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-12 scale-90"
             class="fixed bottom-[110px] left-6 right-6 sm:left-12 sm:right-12 bg-white/80 dark:bg-[#020617]/80 backdrop-blur-[40px] saturate-[2] overflow-y-auto overflow-x-hidden max-h-[75vh] hide-scrollbar border border-white/60 dark:border-white/10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] dark:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.9)] rounded-[2.5rem] z-[110] ring-1 ring-black/5 dark:ring-white/10">
            
            {{-- Drawer Glow Decoration --}}
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-500/10 blur-[60px] rounded-full pointer-events-none"></div>

            <div class="px-8 py-5 flex justify-between items-center sticky top-0 bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl z-20 border-b border-white/40 dark:border-white/5">
                <h3 class="font-black text-xl bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400 bg-clip-text text-transparent tracking-tight">{{ __('Settings') }}</h3>
                <button @click="isDrawerOpen = false" class="w-10 h-10 rounded-2xl bg-white/50 dark:bg-white/10 shadow-sm border border-white/60 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-slate-900 dark:hover:text-white transition-all hover:rotate-90 active:scale-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{-- Quick Toggle Tiles --}}
                <div class="grid grid-cols-3 gap-3">
                    {{-- Theme --}}
                    <button type="button" onclick="toggleTheme()" class="flex flex-col items-center justify-center gap-2.5 py-5 rounded-[2rem] bg-white dark:bg-white/5 border border-white/60 dark:border-white/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group/tile active:scale-95">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-indigo-500/10 flex items-center justify-center transition-colors group-hover/tile:scale-110">
                            <svg class="w-6 h-6 text-amber-500 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314L7.05 7.05m9.9 9.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            <svg class="w-6 h-6 text-indigo-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400">{{ __('Theme') }}</span>
                    </button>

                    {{-- Language --}}
                    <a href="{{ route('switch-lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="flex flex-col items-center justify-center gap-2.5 py-5 rounded-[2rem] bg-white dark:bg-white/5 border border-white/60 dark:border-white/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group/tile active:scale-95">
                        <div class="w-12 h-12 rounded-2xl bg-primary-500/10 flex items-center justify-center transition-colors group-hover/tile:scale-110">
                            <span class="text-xl font-black bg-gradient-to-br from-primary-600 to-primary-400 bg-clip-text text-transparent">{{ app()->getLocale() == 'ar' ? 'EN' : 'AR' }}</span>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400">{{ __('Language') }}</span>
                    </a>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/966537191862" target="_blank" class="flex flex-col items-center justify-center gap-2.5 py-5 rounded-[2rem] bg-white dark:bg-white/5 border border-white/60 dark:border-white/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group/tile active:scale-95">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center transition-colors group-hover/tile:scale-110">
                            <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400">Help</span>
                    </a>
                </div>

                {{-- Notifications Section --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <div class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Recent Notifications') }}</div>
                        <a href="{{ route('student.notifications.index') }}" class="text-[11px] font-black text-primary-500 uppercase tracking-wider">{{ __('See All') }}</a>
                    </div>
                    
                    <div class="space-y-2.5">
                        <template x-if="notifications.length === 0">
                            <div class="py-4 text-center text-sm text-slate-400">{{ __('No recent notifications') }}</div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <a :href="notif.action_url" class="flex items-start gap-4 p-4 rounded-2xl bg-white/40 dark:bg-white/5 border border-white/40 dark:border-white/5 hover:bg-white dark:hover:bg-white/10 transition-all group/notif active:scale-[0.98]">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors" :class="!notif.is_read ? 'bg-primary-500 text-white' : 'bg-slate-100 dark:bg-white/10 text-slate-400'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold truncate transition-colors" :class="!notif.is_read ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400'" x-text="notif.title"></h4>
                                    <p class="text-xs text-slate-400 line-clamp-1 mt-0.5" x-text="notif.message"></p>
                                    <span class="text-[10px] font-bold text-primary-500/60 uppercase mt-1 block" x-text="notif.time_ago"></span>
                                </div>
                                <div x-show="!notif.is_read" class="w-2 h-2 rounded-full bg-primary-500 mt-2"></div>
                            </a>
                        </template>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2">{{ __('Navigation') }}</div>
                    <div class="grid grid-cols-1 gap-2.5">
                        @php
                            $extraLinks = [
                                ['route' => 'student.certificates.index', 'icon' => '📜', 'label' => __('Certificates')],
                                ['route' => 'student.profile.achievements', 'icon' => '🌟', 'label' => __('Achievements')],
                                ['route' => 'student.quizzes.my-attempts', 'icon' => '📝', 'label' => __('All Attempts')],
                                ['route' => 'student.games.index', 'icon' => '🎮', 'label' => __('Games')],
                                ['route' => 'student.battle.index', 'icon' => '⚔️', 'label' => __('Battle')],
                                ['route' => 'student.leaderboard', 'icon' => '🏆', 'label' => __('Leaderboard')],
                                ['route' => 'student.referrals.index', 'icon' => '🎁', 'label' => __('Invite Friends')],
                                ['route' => 'student.telegram.guide', 'icon' => '🤖', 'label' => __('Telegram Bot')],
                            ];
                        @endphp
                        @foreach($extraLinks as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center justify-between px-5 py-4 rounded-2xl bg-white/40 dark:bg-white/5 border border-white/40 dark:border-white/5 hover:bg-white dark:hover:bg-white/10 transition-all group/link active:scale-[0.98]">
                                <div class="flex items-center gap-4">
                                    <span class="text-xl group-hover/link:scale-110 transition-transform">{{ $link['icon'] }}</span>
                                    <span class="text-[15px] font-bold text-slate-700 dark:text-slate-200">{{ $link['label'] }}</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover/link:translate-x-1 transition-transform ltr:block rtl:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <svg class="w-4 h-4 text-slate-400 group-hover/link:-translate-x-1 transition-transform rtl:block ltr:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="pt-2 pb-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-6 py-5 rounded-[2rem] text-[15px] font-black text-red-500 bg-red-500/5 dark:bg-red-500/10 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                        <svg class="w-5 h-5 transition-transform group-hover:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ─── Actual Bottom Bar ─── --}}
        <div class="fixed bottom-6 left-6 right-6 sm:bottom-8 sm:left-12 sm:right-12 z-[100] bg-white/80 dark:bg-[#020617]/80 backdrop-blur-[30px] saturate-[1.8] border border-white/60 dark:border-white/10 rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)] dark:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.8)] ring-1 ring-black/5 dark:ring-white/10 transition-all duration-500 overflow-visible"
             :class="!showNav ? 'translate-y-32' : 'translate-y-0'">
            
            {{-- High-End Inner Glow --}}
            <div class="absolute inset-x-10 -top-5 h-px bg-gradient-to-r from-transparent via-primary-500/40 to-transparent blur-sm"></div>
            
            <div class="relative flex justify-around items-center h-[76px] px-2">
                {{-- Home --}}
                <a href="{{ route('student.dashboard') }}" 
                   class="relative flex flex-col items-center justify-center w-full h-full gap-1.5 transition-all duration-300 group/nav active:scale-90 {{ request()->routeIs('student.dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    @if(request()->routeIs('student.dashboard'))
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-primary-500/15 dark:bg-primary-500/20 rounded-full blur-xl animate-pulse"></div>
                        </div>
                    @endif
                    <div class="relative z-10 flex flex-col items-center gap-1">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover/nav:-translate-y-0.5 {{ request()->routeIs('student.dashboard') ? 'drop-shadow-[0_0_8px_rgba(14,165,233,0.4)]' : '' }}" fill="{{ request()->routeIs('student.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="text-[10px] font-bold whitespace-nowrap uppercase">{{ __('Dashboard') }}</span>
                    </div>
                </a>
                
                {{-- My Courses --}}
                <a href="{{ route('student.courses.my-courses') }}" 
                   class="relative flex flex-col items-center justify-center w-full h-full gap-1.5 transition-all duration-300 group/nav active:scale-90 {{ request()->routeIs('student.courses.my-courses') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    @if(request()->routeIs('student.courses.my-courses'))
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-primary-500/15 dark:bg-primary-500/20 rounded-full blur-xl animate-pulse"></div>
                        </div>
                    @endif
                    <div class="relative z-10 flex flex-col items-center gap-1">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover/nav:-translate-y-0.5 {{ request()->routeIs('student.courses.my-courses') ? 'drop-shadow-[0_0_8px_rgba(14,165,233,0.4)]' : '' }}" fill="{{ request()->routeIs('student.courses.my-courses') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-[10px] font-bold whitespace-nowrap uppercase">{{ __('My Courses') }}</span>
                    </div>
                </a>
                
                {{-- Community/Forum --}}
                <a href="{{ route('student.forum.index') }}" 
                   class="relative flex flex-col items-center justify-center w-full h-full gap-1.5 transition-all duration-300 group/nav active:scale-90 {{ request()->routeIs('student.forum.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    @if(request()->routeIs('student.forum.*'))
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-primary-500/15 dark:bg-primary-500/20 rounded-full blur-xl animate-pulse"></div>
                        </div>
                    @endif
                    <div class="relative z-10 flex flex-col items-center gap-1">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover/nav:-translate-y-0.5 {{ request()->routeIs('student.forum.*') ? 'drop-shadow-[0_0_8px_rgba(14,165,233,0.4)]' : '' }}" fill="{{ request()->routeIs('student.forum.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        <span class="text-[10px] font-bold whitespace-nowrap uppercase">{{ __('Forum') }}</span>
                    </div>
                </a>
                
                {{-- Profile --}}
                <a href="{{ route('student.profile.show') }}" 
                   class="relative flex flex-col items-center justify-center w-full h-full gap-1.5 transition-all duration-300 group/nav active:scale-90 {{ request()->routeIs('student.profile.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    @if(request()->routeIs('student.profile.*'))
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 bg-primary-500/15 dark:bg-primary-500/20 rounded-full blur-xl animate-pulse"></div>
                        </div>
                    @endif
                    <div class="relative z-10 flex flex-col items-center gap-1">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover/nav:-translate-y-0.5 {{ request()->routeIs('student.profile.*') ? 'drop-shadow-[0_0_8px_rgba(14,165,233,0.4)]' : '' }}" fill="{{ request()->routeIs('student.profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-[10px] font-bold whitespace-nowrap uppercase">{{ __('Profile') }}</span>
                    </div>
                </a>

                {{-- Settings & More (Toggle Dropup) --}}
                <button @click.stop="isDrawerOpen = !isDrawerOpen" 
                        class="relative flex flex-col items-center justify-center w-full h-full gap-1.5 transition-all duration-300 group/nav active:scale-90 text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                        :class="isDrawerOpen ? 'text-primary-600 dark:text-primary-400' : ''">
                    
                    {{-- Dynamic Unread Badge --}}
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-4 right-1/4 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-[#020617] shadow-sm animate-pulse z-20"></span>
                    </template>

                    <div class="relative z-10 flex flex-col items-center gap-1">
                        <div class="w-6 h-6 flex items-center justify-center transition-transform duration-300 group-hover/nav:-translate-y-0.5">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold whitespace-nowrap uppercase">{{ __('Settings') }}</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    @endif
@endauth
