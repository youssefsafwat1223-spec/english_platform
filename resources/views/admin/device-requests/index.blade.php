@extends('layouts.admin')

@section('title', 'طلبات الأجهزة')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white">طلبات استبدال الأجهزة</h1>
            <p class="mt-2 text-sm text-slate-400">راجع الأجهزة الجديدة التي تجاوزت الحد الأقصى المسموح للحسابات الطلابية.</p>
        </div>

        <div class="glass-card mb-6">
            <div class="glass-card-body">
                <form method="GET" class="flex flex-wrap gap-3">
                    <select name="status" class="input-glass max-w-xs">
                        <option value="">كل الحالات</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>تمت الموافقة</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                    <button type="submit" class="btn-primary">تصفية</button>
                </form>
            </div>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>الطالب</th>
                            <th>الجهاز الجديد</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>تاريخ الطلب</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deviceRequests as $deviceRequest)
                            <tr>
                                <td>
                                    <div class="font-bold text-white">{{ $deviceRequest->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $deviceRequest->user->email }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-slate-200">{{ $deviceRequest->device_label ?: 'جهاز غير معروف' }}</div>
                                    <div class="text-xs text-slate-400">{{ $deviceRequest->browser ?: 'غير محدد' }} / {{ $deviceRequest->platform ?: 'غير محدد' }}</div>
                                </td>
                                <td class="capitalize">{{ $deviceRequest->device_type }}</td>
                                <td>
                                    @if ($deviceRequest->status === 'pending')
                                        <span class="inline-flex items-center rounded-lg bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-300">قيد المراجعة</span>
                                    @elseif ($deviceRequest->status === 'approved')
                                        <span class="inline-flex items-center rounded-lg bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">تمت الموافقة</span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-rose-500/10 px-3 py-1 text-xs font-bold text-rose-300">مرفوض</span>
                                    @endif
                                </td>
                                <td>{{ $deviceRequest->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.device-requests.show', $deviceRequest) }}" class="text-primary-400 hover:text-primary-300 font-semibold">
                                        عرض التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">لا توجد طلبات أجهزة مطابقة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="glass-card-footer">
                {{ $deviceRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
