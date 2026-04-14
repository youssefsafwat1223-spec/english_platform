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
                    <div class="pt-4 border-t" style="border-color: var(--color-border);">
                        <h2 class="text-lg font-extrabold mb-4" style="color: var(--color-text);">{{ __('Dashboard Promo Banner') }}</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Promo Title') }}</label>
                                <input type="text" name="dashboard_promo_title" class="input-glass" value="{{ old('dashboard_promo_title', $settings['dashboard_promo_title'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Promo Message') }}</label>
                                <textarea name="dashboard_promo_message" rows="3" class="input-glass">{{ old('dashboard_promo_message', $settings['dashboard_promo_message'] ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Promo URL') }}</label>
                                <input type="url" name="dashboard_promo_url" class="input-glass" value="{{ old('dashboard_promo_url', $settings['dashboard_promo_url'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t" style="border-color: var(--color-border);">
                        <h2 class="text-lg font-extrabold mb-4" style="color: var(--color-text);">{{ __('Social Media Links') }}</h2>
                        <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __('Add your social media links to display them in the footer.') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Facebook URL') }}</label>
                                <input type="url" name="social_facebook" class="input-glass" placeholder="https://facebook.com/..." value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Instagram URL') }}</label>
                                <input type="url" name="social_instagram" class="input-glass" placeholder="https://instagram.com/..." value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('X (Twitter) URL') }}</label>
                                <input type="url" name="social_twitter" class="input-glass" placeholder="https://x.com/..." value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('YouTube URL') }}</label>
                                <input type="url" name="social_youtube" class="input-glass" placeholder="https://youtube.com/..." value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('TikTok URL') }}</label>
                                <input type="url" name="social_tiktok" class="input-glass" placeholder="https://tiktok.com/@..." value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('WhatsApp URL') }}</label>
                                <input type="url" name="social_whatsapp" class="input-glass" placeholder="https://wa.me/..." value="{{ old('social_whatsapp', $settings['social_whatsapp'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t" style="border-color: var(--color-border);">
                        <h2 class="text-lg font-extrabold mb-4" style="color: var(--color-text);">{{ __('Live Sessions Visibility') }}</h2>
                        <div class="rounded-2xl border p-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between" style="border-color: var(--color-border); background: var(--color-surface);">
                            <div>
                                <p class="font-bold" style="color: var(--color-text);">{{ __('Show live sessions to students') }}</p>
                                <p class="text-sm mt-1" style="color: var(--color-text-muted);">
                                    {{ __('When disabled, live sessions disappear from the student navbar, dashboard banner, public mentions, and direct student links.') }}
                                </p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer gap-3">
                                <input type="hidden" name="live_sessions_enabled" value="0">
                                <input type="checkbox" name="live_sessions_enabled" value="1" class="sr-only peer"
                                       {{ old('live_sessions_enabled', $settings['live_sessions_enabled'] ?? true) ? 'checked' : '' }}>
                                <div class="relative w-14 h-8 rounded-full transition-colors bg-slate-300 peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-1 after:start-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-6 rtl:peer-checked:after:-translate-x-6"></div>
                                <span class="text-sm font-bold" style="color: var(--color-text);">
                                    {{ __('Enabled') }}
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="pt-4 border-t" style="border-color: var(--color-border);">
                        <h2 class="text-lg font-extrabold mb-4" style="color: var(--color-text);">{{ __('Course Student Count Visibility') }}</h2>
                        <div class="rounded-2xl border p-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between" style="border-color: var(--color-border); background: var(--color-surface);">
                            <div>
                                <p class="font-bold" style="color: var(--color-text);">{{ __('Show enrolled students count in course pages') }}</p>
                                <p class="text-sm mt-1" style="color: var(--color-text-muted);">
                                    {{ __('When disabled, student count will be hidden from public and student course listings/details.') }}
                                </p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer gap-3">
                                <input type="hidden" name="course_student_count_visible" value="0">
                                <input type="checkbox" name="course_student_count_visible" value="1" class="sr-only peer"
                                       {{ old('course_student_count_visible', $settings['course_student_count_visible'] ?? true) ? 'checked' : '' }}>
                                <div class="relative w-14 h-8 rounded-full transition-colors bg-slate-300 peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-1 after:start-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-6 rtl:peer-checked:after:-translate-x-6"></div>
                                <span class="text-sm font-bold" style="color: var(--color-text);">
                                    {{ __('Enabled') }}
                                </span>
                            </label>
                        </div>
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
