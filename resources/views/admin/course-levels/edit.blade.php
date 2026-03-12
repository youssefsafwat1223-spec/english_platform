@extends('layouts.admin')
@section('title', __('تعديل المستوى'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ route('admin.courses.levels.index', $course) }}" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">← {{ __('العودة لإدارة المستويات') }}</a>
            <h1 class="text-3xl font-extrabold mt-4"><span class="text-gradient">{{ __('تعديل المستوى') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <form action="{{ route('admin.courses.levels.update', [$course, $level]) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="glass-card-body space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('اسم المستوى') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $level->title) }}" required class="input-glass">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('وصف المستوى') }}</label>
                        <textarea name="description" rows="3" class="input-glass">{{ old('description', $level->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('صورة المستوى (اختياري)') }}</label>
                        @if($level->thumbnail)
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ Storage::url($level->thumbnail) }}" class="w-24 h-16 rounded-xl object-cover border-2 border-primary-500/30" alt="{{ $level->title }}">
                                <span class="text-xs font-medium" style="color: var(--color-text-muted);">{{ __('الصورة الحالية') }}</span>
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="input-glass">
                        @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text);">{{ __('الترتيب') }}</label>
                        <input type="number" name="order_index" value="{{ old('order_index', $level->order_index) }}" min="0" class="input-glass">
                        @error('order_index') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $level->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-500 focus:ring-primary-500 rounded" style="border-color: var(--color-border);">
                        <label for="is_active" class="text-sm font-semibold" style="color: var(--color-text);">{{ __('مفعّل (يظهر للطلاب)') }}</label>
                    </div>
                </div>

                <div class="glass-card-footer flex justify-between">
                    <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn-secondary">{{ __('إلغاء') }}</a>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('حفظ التعديلات') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
