@extends('layouts.admin')
@section('title', __('Settings'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Settings') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Configure your platform settings') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['General', 'Site name, URL, timezone, contact info', route('admin.settings.general'), '⚙️'],
                ['Telegram Bot', 'Configure bot, webhook, and notifications', route('admin.settings.telegram'), '🤖'],
                ['Payment', 'Gateway, currency, and tax settings', route('admin.settings.payment'), '💳'],
                ['Points and Rewards', 'Configure points and referral rewards', route('admin.settings.points'), '🏆'],
                ['Certificates', 'Template design and configuration', route('admin.certificates.settings'), '📜'],
                ['Forum', 'Categories, moderation, reports', route('admin.forum.index'), '💬'],
            ] as [$title, $desc, $link, $icon])
            <a href="{{ $link }}" class="glass-card overflow-hidden group hover:scale-[1.02] transition-transform" data-aos="fade-up">
                <div class="glass-card-body text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500/20 to-accent-500/20 flex items-center justify-center mx-auto mb-4 text-2xl group-hover:scale-110 transition-transform">{{ $icon }}</div>
                    <h3 class="text-lg font-bold mb-1" style="color: var(--color-text);">{{ $title }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $desc }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
