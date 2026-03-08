<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'sa']) ? 'rtl' : 'ltr' }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel — ' . config('app.name'))</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .admin-sidebar-link.active {
            background: rgba(139, 92, 246, 0.15); /* violet-500/15 */
            border-right: 3px solid #a78bfa; /* violet-400 */
            color: #fff;
        }
        [dir="rtl"] .admin-sidebar-link.active {
            border-right: none;
            border-left: 3px solid #a78bfa;
        }
        /* Gradient Text */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(135deg, #a78bfa 0%, #38bdf8 100%);
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased h-screen flex overflow-hidden bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : (document.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')" 
        class="fixed inset-y-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-2xl ltr:border-r rtl:border-l border-slate-800 ltr:left-0 rtl:right-0">
        
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-center border-b border-slate-800 px-6 bg-slate-900/50">
            <div class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto">
                <span class="font-bold text-lg tracking-wide group-hover:text-primary-400 transition-colors">{{ __('Admin Panel') }}</span>
            </div>
        </div>

        <!-- Sidebar Links -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Management') }}</p>

            <a href="{{ route('admin.courses.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">📚</span>
                <span class="font-medium text-sm">{{ __('Courses') }}</span>
            </a>

            <a href="{{ route('admin.students.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">👥</span>
                <span class="font-medium text-sm">{{ __('Students') }}</span>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">💳</span>
                <span class="font-medium text-sm">{{ __('Payments') }}</span>
            </a>

            <a href="{{ route('admin.promo-codes.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.promo-codes.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">🏷️</span>
                <span class="font-medium text-sm">{{ __('Promo Codes') }}</span>
            </a>

            <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">{{ __('Examination') }}</p>

            <a href="{{ route('admin.analytics.questions') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">📊</span>
                <span class="font-medium text-sm">{{ __('Question Analytics') }}</span>
            </a>

            <a href="{{ route('admin.quizzes.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">📝</span>
                <span class="font-medium text-sm">{{ __('Quizzes') }}</span>
            </a>
            
            <a href="{{ route('admin.questions.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">❓</span>
                <span class="font-medium text-sm">{{ __('Questions') }}</span>
            </a>

            <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">{{ __('Engagement') }}</p>

            <a href="{{ route('admin.games.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">🎮</span>
                <span class="font-medium text-sm">{{ __('Games') }}</span>
            </a>

            <a href="{{ route('admin.forum.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.forum.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">💬</span>
                <span class="font-medium text-sm">{{ __('Forum') }}</span>
            </a>

            <a href="{{ route('admin.email-campaigns.index') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.email-campaigns.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">📧</span>
                <span class="font-medium text-sm">{{ __('Email Campaigns') }}</span>
            </a>

            <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">{{ __('Settings') }}</p>

            <a href="{{ route('admin.settings.general') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">⚙️</span>
                <span class="font-medium text-sm">{{ __('General') }}</span>
            </a>

            <a href="{{ route('admin.settings.telegram') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.settings.telegram') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">🤖</span>
                <span class="font-medium text-sm">{{ __('Telegram Bot') }}</span>
            </a>

            <a href="{{ route('admin.settings.payment') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.settings.payment') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">💳</span>
                <span class="font-medium text-sm">{{ __('Payment Settings') }}</span>
            </a>
            
             <a href="{{ route('admin.settings.points') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.settings.points') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">🏆</span>
                <span class="font-medium text-sm">{{ __('Points & Rewards') }}</span>
            </a>

            <a href="{{ route('admin.certificates.settings') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">📜</span>
                <span class="font-medium text-sm">{{ __('Certificates') }}</span>
            </a>

            <a href="{{ route('admin.settings.battle') }}" class="admin-sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all group {{ request()->routeIs('admin.settings.battle') ? 'active' : '' }}">
                <span class="text-xl group-hover:scale-110 transition-transform">⚔️</span>
                <span class="font-medium text-sm">{{ __('Battle Settings') }}</span>
            </a>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ __('Administrator') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative transition-all duration-300">
        <!-- Top Header (Mobile Toggle & Utilities) -->
        <header class="h-16 flex items-center justify-between px-6 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800 z-20">
            <div class="flex items-center gap-4">
                <!-- Mobile Toggle -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <!-- Page Title Placeholder (Optional) -->
                <h1 class="text-lg font-bold text-slate-800 dark:text-white hidden sm:block">
                    @yield('title')
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <!-- View Site -->
                <a href="{{ route('home') }}" class="p-2 text-slate-500 hover:text-primary-500 transition-colors" title="{{ __('View Site') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <!-- Theme Toggle -->
                <button type="button" onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'" class="p-2 text-slate-500 hover:text-amber-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                <!-- Lang Switcher -->
                <a href="{{ route('switch-lang', app()->getLocale() == 'en' ? 'ar' : 'en') }}" class="flex items-center gap-1 font-bold text-slate-700 dark:text-slate-300 hover:text-primary-500 transition-colors">
                    {{ app()->getLocale() == 'en' ? '🇪🇬 AR' : '🇺🇸 EN' }}
                </a>
            </div>
        </header>
        
        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900 p-6 relative">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-500/10 rounded-full blur-3xl pointer-events-none -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-3xl pointer-events-none -ml-20 -mb-20"></div>

            <div class="relative z-10">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts Stack -->
    @stack('scripts')
</body>
</html>
