@extends('layouts.app')

@section('title', __('Terms of Service') . ' — ' . config('app.name'))

@section('content')
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="badge-primary mb-4">📋 {{ __('Legal') }}</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4" style="color: var(--color-text);">
                {{ __('Terms of Service') }}
            </h1>
            <p class="text-sm" style="color: var(--color-text-muted);">{{ __('Last updated') }}: {{ date('F j, Y') }}</p>
        </div>

        <div class="glass-card p-8 md:p-12 prose-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="space-y-8" style="color: var(--color-text-muted);">

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">1. Acceptance of Terms</h2>
                    <p class="leading-relaxed">By creating an account or using {{ config('app.name') }}, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you may not use our services.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">2. User Accounts</h2>
                    <ul class="list-disc list-inside space-y-2">
                        <li>You must provide accurate and complete information when creating an account</li>
                        <li>You are responsible for maintaining the security of your account credentials</li>
                        <li>You must be at least 13 years old to use this platform</li>
                        <li>One person may not maintain more than one account</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">3. Course Access & Payments</h2>
                    <p class="leading-relaxed">Paid courses grant you lifetime access to the content available at the time of purchase. We reserve the right to update course content. Refund requests may be submitted within 7 days of purchase if you have completed less than 25% of the course.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">4. Certificates</h2>
                    <p class="leading-relaxed">Certificates are issued upon successful completion of a course and its assessments. Each certificate includes a unique verification code. Certificates represent course completion and are not accredited academic degrees.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">5. Community Guidelines</h2>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Be respectful and constructive in forum discussions</li>
                        <li>Do not share answers or quiz solutions</li>
                        <li>Do not post spam, offensive, or inappropriate content</li>
                        <li>Do not impersonate other users or staff members</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">6. Intellectual Property</h2>
                    <p class="leading-relaxed">All course content, including text, videos, images, and quizzes, is the property of {{ config('app.name') }} and its content creators. You may not reproduce, distribute, or share course materials without explicit permission.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">7. Account Termination</h2>
                    <p class="leading-relaxed">We reserve the right to suspend or terminate accounts that violate these terms, engage in fraudulent activity, or abuse the platform. You may delete your account at any time through your profile settings.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">8. Changes to Terms</h2>
                    <p class="leading-relaxed">We may update these terms from time to time. Continued use of the platform after changes constitutes acceptance of the updated terms. We will notify users of significant changes via email or in-app notification.</p>
                </div>

                <div>
                    <h2 class="text-lg font-bold mb-3" style="color: var(--color-text);">9. Contact</h2>
                    <p class="leading-relaxed">For questions about these Terms of Service, please <a href="{{ route('contact') }}" class="text-primary-500 hover:text-primary-400 underline">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
