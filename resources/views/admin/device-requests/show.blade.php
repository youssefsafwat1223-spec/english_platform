@extends('layouts.admin')

@section('title', 'تفاصيل طلب الجهاز')

@section('content')
<div class="py-10">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white">تفاصيل طلب استبدال الجهاز</h1>
                <p class="mt-2 text-sm text-slate-400">راجع الجهاز الجديد واختر الجهاز الحالي الذي سيتم استبداله عند الموافقة.</p>
            </div>
            <a href="{{ route('admin.device-requests.index') }}" class="text-sm font-semibold text-primary-400 hover:text-primary-300">العودة إلى الطلبات</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="glass-card lg:col-span-2">
                <div class="glass-card-body space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-white">بيانات الطالب</h2>
                        <div class="mt-3 rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                            <div class="font-bold text-white">{{ $deviceReplacementRequest->user->name }}</div>
                            <div class="text-sm text-slate-400">{{ $deviceReplacementRequest->user->email }}</div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-white">الجهاز المطلوب اعتماده</h2>
                        <div class="mt-3 rounded-2xl border border-white/10 bg-slate-900/40 p-4 space-y-2">
                            <div class="text-slate-200"><span class="font-semibold text-white">الاسم:</span> {{ $deviceReplacementRequest->device_label ?: 'جهاز غير معروف' }}</div>
                            <div class="text-slate-200"><span class="font-semibold text-white">النوع:</span> {{ $deviceReplacementRequest->device_type }}</div>
                            <div class="text-slate-200"><span class="font-semibold text-white">النظام:</span> {{ $deviceReplacementRequest->platform ?: 'غير محدد' }}</div>
                            <div class="text-slate-200"><span class="font-semibold text-white">المتصفح:</span> {{ $deviceReplacementRequest->browser ?: 'غير محدد' }}</div>
                            <div class="text-slate-200"><span class="font-semibold text-white">IP:</span> {{ $deviceReplacementRequest->ip_address ?: 'غير متوفر' }}</div>
                            <div class="text-slate-200"><span class="font-semibold text-white">وقت الطلب:</span> {{ $deviceReplacementRequest->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-white">الأجهزة المعتمدة حاليًا</h2>
                        <div class="mt-3 space-y-3">
                            @forelse ($activeDevices as $device)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                    <div class="font-semibold text-white">{{ $device->device_label ?: 'جهاز غير معروف' }}</div>
                                    <div class="mt-1 text-sm text-slate-400">{{ $device->browser ?: 'غير محدد' }} / {{ $device->platform ?: 'غير محدد' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">آخر ظهور: {{ optional($device->last_seen_at)->format('Y-m-d H:i') ?: 'غير متوفر' }}</div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-white/10 p-4 text-sm text-slate-400">لا توجد أجهزة معتمدة حاليًا لهذا الطالب.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card h-fit">
                <div class="glass-card-body space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-white">الحالة</h2>
                        <div class="mt-3">
                            @if ($deviceReplacementRequest->status === 'pending')
                                <span class="inline-flex items-center rounded-lg bg-amber-500/10 px-3 py-1 text-sm font-bold text-amber-300">قيد المراجعة</span>
                            @elseif ($deviceReplacementRequest->status === 'approved')
                                <span class="inline-flex items-center rounded-lg bg-emerald-500/10 px-3 py-1 text-sm font-bold text-emerald-300">تمت الموافقة</span>
                            @else
                                <span class="inline-flex items-center rounded-lg bg-rose-500/10 px-3 py-1 text-sm font-bold text-rose-300">مرفوض</span>
                            @endif
                        </div>
                    </div>

                    @if ($deviceReplacementRequest->status === 'pending')
                        <form method="POST" action="{{ route('admin.device-requests.approve', $deviceReplacementRequest) }}" class="space-y-4">
                            @csrf

                            @if ($activeDevices->count() >= 3)
                                <div>
                                    <label for="replacement_device_id" class="mb-2 block text-sm font-semibold text-slate-200">اختر الجهاز الذي سيتم استبداله</label>
                                    <select id="replacement_device_id" name="replacement_device_id" class="input-glass w-full" required>
                                        <option value="">اختر جهازًا</option>
                                        @foreach ($activeDevices as $device)
                                            <option value="{{ $device->id }}">{{ $device->device_label ?: 'جهاز غير معروف' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <button type="submit" class="btn-primary w-full">موافقة واستبدال الجهاز</button>
                        </form>

                        <form method="POST" action="{{ route('admin.device-requests.reject', $deviceReplacementRequest) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-bold text-rose-300 hover:bg-rose-500/20">
                                رفض الطلب
                            </button>
                        </form>
                    @else
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-sm text-slate-300">
                            تم اتخاذ القرار على هذا الطلب في {{ optional($deviceReplacementRequest->reviewed_at)->format('Y-m-d H:i') ?: '-' }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
