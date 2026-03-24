@extends('layouts.admin')
@section('title', __('عينة من الشروحات'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('عينة من الشروحات') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('إدارة فيديوهات العينة المعروضة في الصفحة الرئيسية') }}</p>
            </div>
            <a href="{{ route('admin.promo-videos.create') }}" class="btn-primary ripple-btn">{{ __('+ إضافة فيديو') }}</a>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('الصورة المصغرة') }}</th>
                            <th>{{ __('العنوان') }}</th>
                            <th>{{ __('الرابط') }}</th>
                            <th>{{ __('الحالة') }}</th>
                            <th>{{ __('الترتيب') }}</th>
                            <th>{{ __('إجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promoVideos as $video)
                        <tr>
                            <td>
                                @if($video->thumbnail)
                                    <img src="{{ Storage::url($video->thumbnail) }}" class="w-20 h-12 rounded-lg object-cover" alt="{{ $video->title }}">
                                @else
                                    <div class="w-20 h-12 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xl">▶</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold" style="color: var(--color-text);">{{ $video->title }}</div>
                                @if($video->description)
                                    <div class="text-xs max-w-xs truncate mt-1" style="color: var(--color-text-muted);">{{ $video->description }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ $video->video_url }}" target="_blank" class="text-primary-500 text-xs font-bold hover:underline truncate block max-w-[200px]">{{ $video->video_url }}</a>
                            </td>
                            <td>
                                @if($video->is_active)
                                    <span class="badge-success">{{ __('مفعّل') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('مخفي') }}</span>
                                @endif
                            </td>
                            <td style="color: var(--color-text);">{{ $video->sort_order }}</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.promo-videos.edit', $video) }}" class="text-primary-500 text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('تعديل') }}</a>
                                    <form action="{{ route('admin.promo-videos.destroy', $video) }}" method="POST" class="inline" onsubmit="return confirm(__('هل أنت متأكد من الحذف؟'))">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('حذف') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">
                                <div class="text-4xl mb-4">🎬</div>
                                <p class="mb-4">{{ __('لا توجد فيديوهات حتى الآن') }}</p>
                                <a href="{{ route('admin.promo-videos.create') }}" class="btn-primary ripple-btn">{{ __('أضف أول فيديو') }}</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $promoVideos->links() }}</div>
        </div>
    </div>
</div>
@endsection
