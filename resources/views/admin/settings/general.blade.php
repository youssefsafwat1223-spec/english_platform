@extends('layouts.admin')
@section('title', __('General Settings'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('General Settings') }}</span></h1>
            <a href="{{ route('admin.settings.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Settings') }}</a>
        </div>
        <form action="{{ route('admin.settings.general.update') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Site Name *') }}</label>
                        <input type="text" name="site_name" class="input-glass" value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Site URL *') }}</label>
                        <input type="url" name="site_url" class="input-glass" value="{{ old('site_url', $settings['site_url'] ?? config('app.url')) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Contact Email *') }}</label>
                        <input type="email" name="contact_email" class="input-glass" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Timezone *') }}</label>
                        @php($selectedTimezone = old('timezone', $settings['timezone'] ?? config('app.timezone', 'UTC')))
                        <select name="timezone" class="input-glass" required>
                            @foreach(['UTC' => 'UTC', 'America/New_York' => 'Eastern Time', 'America/Chicago' => 'Central Time', 'America/Denver' => 'Mountain Time', 'America/Los_Angeles' => 'Pacific Time', 'Asia/Dubai' => 'Dubai', 'Africa/Cairo' => 'Cairo'] as $tz => $label)
                            <option value="{{ $tz }}" {{ $selectedTimezone == $tz ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
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
