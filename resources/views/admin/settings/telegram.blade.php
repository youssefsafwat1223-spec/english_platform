@extends('layouts.admin')
@section('title', __('Telegram Bot Settings'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Telegram Bot Settings') }}</span></h1>
            <a href="{{ route('admin.settings.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Settings') }}</a>
        </div>
        <form action="{{ route('admin.settings.telegram.update') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Bot Token') }}</label>
                        <input type="text" class="input-glass font-mono" value="{{ $settings['bot_token'] ?? '' }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Set this in your .env file as TELEGRAM_BOT_TOKEN.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Webhook URL') }}</label>
                        <input type="url" class="input-glass font-mono" value="{{ $settings['webhook_url'] ?? '' }}" readonly>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="send_alternate_days" value="1" {{ old('send_alternate_days', $settings['send_alternate_days'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                        <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Send daily questions on alternate days') }}</label>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Daily Question Time') }}</label>
                        <input type="time" name="question_time" class="input-glass" value="{{ old('question_time', $settings['question_time'] ?? '09:00') }}">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="enable_notifications" value="1" {{ old('enable_notifications', $settings['enable_notifications'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                        <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Enable Telegram notifications') }}</label>
                    </div>
                </div>
                <div class="glass-card-footer">
                    <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                        <span x-show="!loading">{{ __('Save Settings') }}</span><span x-show="loading">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </div>
        </form>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Webhook Management') }}</h3></div>
            <div class="glass-card-body space-y-3">
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.settings.telegram.webhook.set') }}" method="POST">@csrf
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-emerald-500 rounded-xl border border-emerald-500/20 bg-emerald-500/10 hover:bg-emerald-500/20 transition-all">{{ __('Set Webhook') }}</button>
                    </form>
                    <form action="{{ route('admin.settings.telegram.webhook.delete') }}" method="POST" onsubmit="return confirm('Delete webhook?')">@csrf
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-red-500 rounded-xl border border-red-500/20 bg-red-500/10 hover:bg-red-500/20 transition-all">{{ __('Delete Webhook') }}</button>
                    </form>
                </div>
                @if($botInfo)
                <div class="mt-4">
                    <div class="text-xs font-bold mb-2" style="color: var(--color-text-muted);">{{ __('Bot Info') }}</div>
                    <pre class="p-4 rounded-xl text-xs font-mono" style="background: var(--color-surface-hover); color: var(--color-text);">{{ json_encode($botInfo, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
