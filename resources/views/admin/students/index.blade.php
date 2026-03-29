@extends('layouts.admin')
@section('title', __('Manage Students'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Manage Students') }}</span></h1>
            <p class="mt-2" style="color: var(--color-text-muted);">{{ __('View and manage all student accounts') }}</p>
            <div class="mt-4">
                <a href="{{ route('admin.device-requests.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-primary-500/30 bg-primary-500/10 px-4 py-2 text-sm font-bold text-primary-300 hover:bg-primary-500/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1v-9a1 1 0 011-1zm7 5h.01" />
                    </svg>
                    مراجعة طلبات الأجهزة
                </a>
            </div>
        </div>
        <div class="glass-card mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="{{ __('Search...') }}" class="input-glass" value="{{ request('search') }}">
                    <select name="status" class="input-glass">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                    <select name="telegram" class="input-glass">
                        <option value="">{{ __('Telegram Status') }}</option>
                        <option value="linked" {{ request('telegram') === 'linked' ? 'selected' : '' }}>{{ __('Linked') }}</option>
                        <option value="not_linked" {{ request('telegram') === 'not_linked' ? 'selected' : '' }}>{{ __('Not Linked') }}</option>
                    </select>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Student') }}</th><th>{{ __('Email') }}</th><th>{{ __('Enrollments') }}</th><th>{{ __('Points') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td><div class="font-bold" style="color: var(--color-text);">{{ $student->name }}</div><div class="text-xs" style="color: var(--color-text-muted);">Joined {{ $student->created_at->format('M d, Y') }}</div></td>
                            <td>{{ $student->email }}</td>
                            <td><span class="font-bold">{{ $student->enrollments_count }}</span></td>
                            <td><span class="font-bold text-primary-500">{{ $student->total_points }}</span></td>
                            <td>@if($student->is_active)<span class="badge-success">{{ __('Active') }}</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive') }}</span>@endif</td>
                            <td><div class="flex gap-2"><a href="{{ route('admin.students.show', $student) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a><form action="{{ route('admin.students.toggle-status', $student) }}" method="POST" class="inline">@csrf<button type="submit" class="text-amber-500 text-sm font-bold hover:underline">{{ $student->is_active ? 'Deactivate' : 'Activate' }}</button></form></div></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No students found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($students) && method_exists($students, 'links'))<div class="glass-card-footer">{{ $students->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
