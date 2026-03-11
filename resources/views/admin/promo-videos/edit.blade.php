@extends('layouts.admin')
@section('title', __('تعديل فيديو شرح'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ route('admin.promo-videos.index') }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">← {{ __('العودة لقائمة الفيديوهات') }}</a>
            <h1 class="text-3xl font-extrabold mt-4"><span class="text-gradient">{{ __('تعديل فيديو شرح') }}</span></h1>
        </div>

        <div class="glass-card p-8" data-aos="fade-up">
            <form action="{{ route('admin.promo-videos.update', $promoVideo) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('عنوان الفيديو') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $promoVideo->title) }}" required
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('وصف الفيديو') }}</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);">{{ old('description', $promoVideo->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('رابط الفيديو (YouTube أو رابط مباشر)') }} <span class="text-red-500">*</span></label>
                    <input type="url" name="video_url" value="{{ old('video_url', $promoVideo->video_url) }}" required
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);" dir="ltr">
                    @error('video_url') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('صورة مصغرة (اختياري)') }}</label>
                    @if($promoVideo->thumbnail)
                        <div class="flex items-center gap-4 mb-3">
                            <img src="{{ Storage::url($promoVideo->thumbnail) }}" class="w-24 h-14 rounded-lg object-cover border-2 border-primary-500/30" alt="{{ $promoVideo->title }}">
                            <span class="text-xs font-medium" style="color: var(--color-text-muted);">{{ __('الصورة الحالية') }}</span>
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold transition-colors" style="color: var(--color-text);">
                    @error('thumbnail') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('الترتيب') }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $promoVideo->sort_order) }}" min="0"
                        class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" style="color: var(--color-text);">
                    @error('sort_order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promoVideo->is_active) ? 'checked' : '' }}
                        class="w-5 h-5 rounded-lg border-slate-300 dark:border-white/20 text-primary-500 focus:ring-primary-500">
                    <label for="is_active" class="text-sm font-bold" style="color: var(--color-text);">{{ __('مفعّل (يظهر في الصفحة الرئيسية)') }}</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/10">
                    <a href="{{ route('admin.promo-videos.index') }}" class="btn-secondary">{{ __('إلغاء') }}</a>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('حفظ التعديلات') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
