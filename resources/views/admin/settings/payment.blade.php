@extends('layouts.admin')
@section('title', __('Payment Settings'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Payment Settings') }}</span></h1>
            <a href="{{ route('admin.settings.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Settings') }}</a>
        </div>
        <form action="{{ route('admin.settings.payment.update') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('StreamPay API Key') }}</label>
                        <input type="text" class="input-glass font-mono" value="{{ $settings['streampay_api_key'] ?? '' }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Set this in your .env file as STREAMPAY_API_KEY.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('StreamPay Secret Key') }}</label>
                        <input type="text" class="input-glass font-mono" value="{{ $settings['streampay_secret_key'] ?? '' }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">{{ __('Set this in your .env file as STREAMPAY_SECRET_KEY.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Currency *') }}</label>
                        @php($selectedCurrency = old('currency', $settings['currency'] ?? 'USD'))
                        <select name="currency" class="input-glass" required>
                            @foreach(['USD' => 'USD - US Dollar', 'EUR' => 'EUR - Euro', 'GBP' => 'GBP - British Pound', 'AED' => 'AED - UAE Dirham', 'SAR' => 'SAR - Saudi Riyal', 'EGP' => 'EGP - Egyptian Pound'] as $code => $label)
                            <option value="{{ $code }}" {{ $selectedCurrency == $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" step="0.01" class="input-glass" value="{{ old('tax_rate', $settings['tax_rate'] ?? 0) }}">
                    </div>
                </div>
                <div class="glass-card-footer">
                    <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                        <span x-show="!loading">{{ __('Save Settings') }}</span><span x-show="loading">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
