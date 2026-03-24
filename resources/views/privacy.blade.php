@extends('layouts.app')

@section('title', __('Privacy Policy') . ' — ' . config('app.name'))

@section('content')
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="badge-primary mb-4">🔒 {{ __('Legal') }}</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4" style="color: var(--color-text);">
                {{ __('Privacy Policy') }}
            </h1>
            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Last updated') }}: {{ date('F j, Y') }}</p>
        </div>

        <div class="glass-card p-8 md:p-12 prose-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="space-y-8" style="color: var(--color-text-muted);">

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">1. {{ __('Information We Collect') }}</h2>
                    <p class="leading-relaxed">We collect information you provide directly, including your name, email address, and profile data when you register. We also collect usage data such as courses accessed, quiz results, and learning progress to personalize your experience.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">2. {{ __('How We Use Your Information') }}</h2>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Provide, maintain, {{ __('and improve our platform and services') }}</li>
                        <li>{{ __('Track your learning progress and generate certificates') }}</li>
                        <li>Send you notifications about achievements, course updates, {{ __('and promotions') }}</li>
                        <li>{{ __('Personalize your learning experience and recommendations') }}</li>
                        <li>{{ __('Provide customer support and respond to inquiries') }}</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">3. {{ __('Data Security') }}</h2>
                    <p class="leading-relaxed">We implement industry-standard security measures to protect your personal information. Passwords are hashed and encrypted. We use HTTPS for all communications and regularly audit our security practices.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">4. Cookies</h2>
                    <p class="leading-relaxed">We use cookies and similar technologies to maintain your session, remember your preferences (such as dark mode), and analyze how our platform is used. You can control cookie settings through your browser.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">5. Third-Party Services</h2>
                    <p class="leading-relaxed">We may use third-party services for payment processing, email delivery, and analytics. These services have their own privacy policies and handle data in accordance with their terms.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">6. {{ __('Your Rights') }}</h2>
                    <ul class="list-disc list-inside space-y-2">
                        <li>{{ __('Access and download your personal data') }}</li>
                        <li>{{ __('Request correction of inaccurate information') }}</li>
                        <li>{{ __('Request deletion of your account and data') }}</li>
                        <li>{{ __('Opt out of promotional communications') }}</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">7. {{ __('Contact Us') }}</h2>
                    <p class="leading-relaxed">If you have any questions about this Privacy Policy, please <a href="{{ route('contact') }}" class="text-primary-500 hover:text-primary-400 underline">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
