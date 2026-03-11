@extends('layouts.admin')
@section('title', __('آراء الطلاب'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('آراء الطلاب') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('إدارة آراء وتقييمات الطلاب المعروضة في الصفحة الرئيسية') }}</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="btn-primary ripple-btn">{{ __('+ إضافة رأي') }}</a>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('الصورة') }}</th>
                            <th>{{ __('الاسم') }}</th>
                            <th>{{ __('الصفة') }}</th>
                            <th>{{ __('التقييم') }}</th>
                            <th>{{ __('المحتوى') }}</th>
                            <th>{{ __('الحالة') }}</th>
                            <th>{{ __('الترتيب') }}</th>
                            <th>{{ __('إجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td>
                                @if($testimonial->avatar)
                                    <img src="{{ Storage::url($testimonial->avatar) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $testimonial->name }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ mb_substr($testimonial->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $testimonial->name }}</div>
                            </td>
                            <td style="color: var(--color-text-muted);">{{ $testimonial->role ?? '—' }}</td>
                            <td>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-slate-300 dark:text-slate-600' }}">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs truncate text-sm" style="color: var(--color-text-muted);">{{ $testimonial->content }}</div>
                            </td>
                            <td>
                                @if($testimonial->is_active)
                                    <span class="badge-success">{{ __('مفعّل') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('مخفي') }}</span>
                                @endif
                            </td>
                            <td style="color: var(--color-text);">{{ $testimonial->sort_order }}</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-primary-500 text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('تعديل') }}</a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('حذف') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12" style="color: var(--color-text-muted);">
                                <div class="text-4xl mb-4">💬</div>
                                <p class="mb-4">{{ __('لا توجد آراء حتى الآن') }}</p>
                                <a href="{{ route('admin.testimonials.create') }}" class="btn-primary ripple-btn">{{ __('أضف أول رأي') }}</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $testimonials->links() }}</div>
        </div>
    </div>
</div>
@endsection
