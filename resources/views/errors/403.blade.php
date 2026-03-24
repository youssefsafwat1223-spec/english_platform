<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - {{ __('Access Denied') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 overflow-hidden relative selection:bg-primary-500 selection:text-white">

    <!-- Background Effects -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-red-500/10 rounded-full blur-3xl -ml-24 -mt-24 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-orange-500/10 rounded-full blur-3xl -mr-24 -mb-24 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="glass-card max-w-lg w-full p-8 md:p-12 text-center relative z-10 border-red-500/20" data-aos="zoom-in">
        <div class="text-9xl mb-4 transform hover:scale-110 transition-transform duration-300">🚫</div>
        <h1 class="text-6xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-red-500 to-orange-500">403</h1>
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">{{ __('Access Denied') }}</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
            You don't have permission to access this page. If you believe this is an error, please contact support.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url()->previous() }}" class="btn-secondary w-full sm:w-auto justify-center">
                ← Go Back
            </a>
            <a href="{{ route('home') }}" class="btn-primary ripple-btn w-full sm:w-auto justify-center">
                Go Home
            </a>
        </div>
    </div>
</body>
</html>