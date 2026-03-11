@extends('layouts.admin')
@section('title', __('إضافة رأي طالب'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">← {{ __('العودة لقائمة الآراء') }}</a>
            <h1 class="text-3xl font-extrabold mt-4"><span class="text-gradient">{{ __('إضافة رأي طالب') }}</span></h1>
        </div>

        <div class="glass-card p-8" data-aos="fade-up">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('اسم الطالب') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);"
                        placeholder="{{ __('مثل: أحمد محمد') }}">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('الصفة / الدور') }}</label>
                    <input type="text" name="role" value="{{ old('role') }}"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);"
                        placeholder="{{ __('مثل: طالب جامعي، موظف') }}">
                    @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('محتوى الرأي') }} <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="4" required
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);"
                        placeholder="{{ __('ماذا قال الطالب عن المنصة...') }}">{{ old('content') }}</textarea>
                    @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('صورة الطالب') }}</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold transition-colors" style="color: var(--color-text);">
                    @error('avatar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('التقييم') }} <span class="text-red-500">*</span></label>
                    <select name="rating" required
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);">
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ $i }} {{ __('نجوم') }} {{ str_repeat('★', $i) }}</option>
                        @endfor
                    </select>
                    @error('rating') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('الترتيب') }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);">
                    @error('sort_order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-slate-300 dark:border-white/20 text-primary-500 focus:ring-primary-500">
                    <label for="is_active" class="text-sm font-bold" style="color: var(--color-text);">{{ __('مفعّل (يظهر في الصفحة الرئيسية)') }}</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/10">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">{{ __('إلغاء') }}</a>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('حفظ') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
