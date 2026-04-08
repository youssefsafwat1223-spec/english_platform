<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $defaultTitle = __('ui.meta.default_title');
            $defaultDescription = __('ui.meta.default_description');
            $defaultKeywords = __('ui.meta.default_keywords');
            $defaultShortDescription = __('ui.meta.default_short_description');
            $defaultJsonLd = json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'EducationalOrganization',
                'name' => 'Simple English',
                'url' => config('app.url'),
                'logo' => asset('logo.jpg'),
                'description' => $defaultShortDescription,
                'sameAs' => [],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer service',
                    'availableLanguage' => ['Arabic', 'English'],
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        @endphp

        {{-- SEO Core --}}
        <title>@yield('title', config('app.name', $defaultTitle))</title>
        <meta name="description" content="@yield('meta_description', $defaultDescription)">
        <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
        <meta name="author" content="Simple English">
        <meta name="robots" content="index, follow">
        <meta name="google-site-verification" content="ry7DT25966tK8f2tVoyjXZ3qGsJNpLnR7TpbLhgLU44" />
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="theme-color" content="#6366f1">

        {{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:title" content="@yield('title', config('app.name', $defaultTitle))">
        <meta property="og:description" content="@yield('meta_description', $defaultDescription)">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="@yield('og_image', asset('logo.jpg'))">
        <meta property="og:site_name" content="Simple English">
        <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_SA' : 'en_US' }}">

        {{-- Twitter Cards --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', config('app.name', $defaultTitle))">
        <meta name="twitter:description" content="@yield('meta_description', $defaultShortDescription)">
        <meta name="twitter:image" content="@yield('og_image', asset('logo.jpg'))">

        {{-- JSON-LD Structured Data --}}
        <script type="application/ld+json">
        @yield('json_ld', $defaultJsonLd)
        </script>

        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo.jpg') }}">

        <!-- Prevent dark mode flash - runs before anything renders -->
        <script>
            (function() {
                var theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

        <!-- AOS (Animate On Scroll) -->
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Page-specific styles -->
        @stack('styles')

        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col">

        <!-- Premium Ambient Background -->
        @if(!request()->routeIs('student.onboarding*'))
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-50 dark:bg-[#020617] transition-colors duration-500">
            {{-- Glowing Modern Orbs --}}
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-primary-400/30 to-blue-300/30 dark:from-primary-600/20 dark:to-blue-600/20 blur-[100px] sm:blur-[150px] mix-blend-multiply dark:mix-blend-screen opacity-80"></div>
            <div class="absolute top-[10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-gradient-to-bl from-accent-400/20 to-purple-300/20 dark:from-accent-600/10 dark:to-purple-900/10 blur-[100px] sm:blur-[140px] mix-blend-multiply dark:mix-blend-screen opacity-70"></div>
            <div class="absolute bottom-[-10%] left-[20%] w-[60%] h-[60%] rounded-full bg-gradient-to-tr from-sky-300/30 to-indigo-300/30 dark:from-sky-800/10 dark:to-indigo-900/10 blur-[100px] sm:blur-[150px] mix-blend-multiply dark:mix-blend-screen opacity-70"></div>

            {{-- Dotted Pattern Overlay --}}
            <div class="absolute inset-0 dark:hidden" style="background-image: radial-gradient(rgba(0,0,0,0.06) 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>
            <div class="absolute inset-0 hidden dark:block" style="background-image: radial-gradient(rgba(255,255,255,0.04) 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>

            {{-- Soft Vignette Mask to blend edges nicely --}}
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-50/20 to-slate-50/80 dark:via-[#020617]/20 dark:to-[#020617]/90"></div>
        </div>
        @endif

        <!-- Navigation -->
        @if(!request()->routeIs('student.onboarding*'))
            @include('layouts.navigation')
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.showNotification('{{ session('success') }}', 'success');
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.showNotification('{{ session('error') }}', 'error');
                });
            </script>
        @endif

        @php
            $isStudentRoute = request()->routeIs('student.*');
            $hasStudentBottomBar = auth()->check()
                && auth()->user()->is_student
                && $isStudentRoute
                && !request()->routeIs('student.quizzes.start');
            $noTopPaddingRoute = request()->routeIs('home')
                || request()->routeIs('login')
                || request()->routeIs('register')
                || request()->routeIs('student.onboarding*')
                || request()->routeIs('password.*');
        @endphp

        <!-- Main Content -->
        <main class="flex-grow relative z-10 w-full {{ $noTopPaddingRoute ? 'pt-0' : 'pt-20 lg:pt-24' }} {{ $isStudentRoute ? 'student-page' : '' }} {{ $hasStudentBottomBar ? 'student-mobile-safe-area' : '' }}">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        <!-- Footer -->
        @if(!request()->routeIs('student.quizzes.start') && !request()->routeIs('student.onboarding*'))
            @include('layouts.footer')
        @endif

        <!-- CDN Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

        <!-- AOS Init -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-quart',
                    once: true,
                    offset: 50,
                });
            });
        </script>

        <!-- Page-specific scripts -->
        @stack('scripts')

    </body>
</html>

