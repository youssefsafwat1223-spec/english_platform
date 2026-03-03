<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Maintenance Mode</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 overflow-hidden relative selection:bg-primary-500 selection:text-white">

    <!-- Background Effects -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary-900/10 to-dark-bg"></div>
        <div class="absolute top-1/2 left-1/2 w-[600px] h-[600px] bg-accent-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 animate-pulse"></div>
    </div>

    <div class="glass-card max-w-lg w-full p-8 md:p-12 text-center relative z-10 border-accent-500/20" data-aos="zoom-in">
        <div class="text-9xl mb-6 transform hover:rotate-180 transition-transform duration-700">🔧</div>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-accent-500 to-primary-500">We'll Be Right Back</h1>
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Scheduled Maintenance</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
            We're currently performing scheduled maintenance to improve your experience. We'll be back online shortly. Thank you for your patience!
        </p>
        
        <div class="glass-card-body bg-primary-500/5 rounded-xl border border-primary-500/10 p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Need immediate assistance?</p>
            <a href="mailto:support@platform.com" class="font-bold text-primary-500 hover:text-primary-400 mt-1 inline-block transition-colors">support@platform.com</a>
        </div>
    </div>
</body>
</html>