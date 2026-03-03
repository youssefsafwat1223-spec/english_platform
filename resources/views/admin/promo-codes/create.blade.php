@extends('layouts.admin')
@section('title', __('Create Promo Code'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Create Promo Code') }}</span></h1>
            <a href="{{ route('admin.promo-codes.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Promo Codes') }}</a>
        </div>

        <div class="glass-card" data-aos="fade-up">
            <div class="glass-card-body">
                <form action="{{ route('admin.promo-codes.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Coupon Code') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', strtoupper(Str::random(8))) }}" class="input-glass font-mono font-bold uppercase" required placeholder="SPRING50">
                            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Discount Type') }} <span class="text-red-500">*</span></label>
                            <select name="discount_type" class="input-glass" required>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>% {{ __('Percentage') }}</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>$ {{ __('Fixed Amount') }}</option>
                            </select>
                            @error('discount_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Discount Amount') }} <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="discount_amount" value="{{ old('discount_amount') }}" class="input-glass" required>
                            @error('discount_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Usage Limit (Optional)') }}</label>
                            <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit') }}" class="input-glass" placeholder="Leave empty for unlimited">
                            @error('usage_limit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Expiration Date (Optional)') }}</label>
                            <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="input-glass">
                            @error('expires_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-primary-500 rounded bg-slate-900 border-slate-700 focus:ring-primary-500" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="font-bold text-sm" style="color: var(--color-text);">{{ __('Active') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-white/10 mt-8">
                        <button type="submit" class="btn-primary ripple-btn px-8">{{ __('Create Promo Code') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
