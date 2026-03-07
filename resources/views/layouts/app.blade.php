<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'English Platform'))</title>
        <meta name="description" content="@yield('meta_description', 'Premium AI-Powered English Learning Platform')">
        <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo.jpg') }}">

        <!-- Prevent dark mode flash — runs BEFORE anything renders -->
        <script>
            (function() {
                var theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
        @if(app()->getLocale() === 'ar')
            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
            <style>
                body, * { font-family: 'Cairo', 'Outfit', sans-serif !important; }
                code, pre, .font-mono { font-family: 'JetBrains Mono', monospace !important; }
            </style>
        @endif

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
        <main class="flex-grow relative z-10 w-full {{ request()->routeIs('home') ? 'pt-0' : 'pt-28 lg:pt-32' }}">
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
