@extends('layouts.admin')
@section('title', __('Points and Rewards'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Points and Rewards Settings') }}</span></h1>
            <a href="{{ route('admin.settings.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Settings') }}</a>
        </div>
        <form action="{{ route('admin.settings.points.update') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    <h3 class="font-bold pb-2" style="color: var(--color-text); border-bottom: 1px solid var(--color-border);">{{ __('Points Configuration') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([['Points per Lesson Completion', 'points_per_lesson', $settings['points_per_lesson'] ?? 10], ['Points per Quiz Pass', 'points_per_quiz', $settings['points_per_quiz'] ?? 30], ['Points per Daily Question', 'points_per_daily_question', $settings['points_per_daily_question'] ?? 5], ['Points per Pronunciation', 'points_per_pronunciation', $settings['points_per_pronunciation'] ?? 10]] as [$label, $name, $val])
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ $label }}</label>
                            <input type="number" name="{{ $name }}" class="input-glass" value="{{ old($name, $val) }}">
                        </div>
                        @endforeach
                    </div>
                    <h3 class="font-bold pb-2 pt-4" style="color: var(--color-text); border-bottom: 1px solid var(--color-border);">{{ __('Referral Settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Referral Discount (%)') }}</label>
                            <input type="number" name="referral_discount_percentage" step="1" class="input-glass" value="{{ old('referral_discount_percentage', $settings['referral_discount_percentage'] ?? 10) }}">
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
