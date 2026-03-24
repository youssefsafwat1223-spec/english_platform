@extends('layouts.admin')
@section('title', __('إدارة العناوين') . ' — ' . $course->title)
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('إدارة العناوين') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ $course->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.courses.show', $course) }}" class="btn-secondary">{{ __('← العودة للكورس') }}</a>
                <a href="{{ route('admin.courses.levels.create', $course) }}" class="btn-primary ripple-btn">{{ __('+ إضافة عنوان') }}</a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($levels as $index => $level)
            <div class="glass-card overflow-hidden group" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                <div class="flex items-center justify-between p-5">
                    <div class="flex items-center gap-4 flex-1">
                        {{-- Level Number --}}
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-black text-xl shadow-lg shrink-0">
                            {{ $level->order_index + 1 }}
                        </div>

                        {{-- Level Info --}}
                        <div class="flex-1">
                            <div class="font-bold text-lg flex items-center gap-2" style="color: var(--color-text);">
                                {{ $level->title }}
                                @if(!$level->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('مخفي') }}</span>
                                @endif
                            </div>
                            @if($level->description)
                                <p class="text-sm mt-1 line-clamp-1" style="color: var(--color-text-muted);">{{ $level->description }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs mt-2" style="color: var(--color-text-muted);">
                                <span class="flex items-center gap-1">
                                    📚 {{ $level->lessons_count }} {{ __('درس') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.courses.levels.edit', [$course, $level]) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('تعديل') }}</a>
                        <form action="{{ route('admin.courses.levels.destroy', [$course, $level]) }}" method="POST" class="inline" onsubmit="return confirm(__('هل أنت متأكد من حذف هذا العنوان وكل دروسه؟'))">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('حذف') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="text-center py-16" style="color: var(--color-text-muted);">
                    <div class="text-5xl mb-4">📊</div>
                    <p class="text-lg font-bold mb-2" style="color: var(--color-text);">{{ __('لا توجد عناوين حتى الآن') }}</p>
                    <p class="mb-6">{{ __('ابدأ بإضافة العنوان الأول للكورس') }}</p>
                    <a href="{{ route('admin.courses.levels.create', $course) }}" class="btn-primary ripple-btn">{{ __('+ إضافة أول عنوان') }}</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
