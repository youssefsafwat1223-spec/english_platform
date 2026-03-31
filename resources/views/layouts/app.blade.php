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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Saudi+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-50 dark:bg-[#020617]" style="will-change: transform; transform: translateZ(0);">
            {{-- Background Mesh/Base --}}
            <div class="absolute inset-0 bg-mesh opacity-30 dark:opacity-50"></div>
            
            {{-- Grid Pattern --}}
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#0000000a_1px,transparent_1px),linear-gradient(to_bottom,#0000000a_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        </div>

        <!-- Navigation -->
        @include('layouts.navigation')

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

        <!-- Main Content -->
        <main class="flex-grow relative z-10 w-full {{ request()->routeIs('home') ? 'pt-0' : 'pt-28 lg:pt-32' }} {{ request()->routeIs('student.*') ? 'student-page' : '' }}">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        <!-- Footer -->
        @include('layouts.footer')

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

