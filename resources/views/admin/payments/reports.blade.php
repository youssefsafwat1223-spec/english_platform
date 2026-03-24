@extends('layouts.admin')
@section('title', __('Payment Reports'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Payment Reports') }}</span></h1>
            <a href="{{ route('admin.payments.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Payments') }}</a>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="glass-card-body">
                <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __('Generate payment reports for specific date ranges') }}</p>
                <form method="GET" action="{{ route('admin.payments.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold mb-1 ml-1" style="color: var(--color-text-muted);">{{ __('Start Date') }}</label>
                        <input type="date" name="start_date" class="input-glass w-full" value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1 ml-1" style="color: var(--color-text-muted);">{{ __('End Date') }}</label>
                        <input type="date" name="end_date" class="input-glass w-full" value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1 ml-1" style="color: var(--color-text-muted);">{{ __('Status') }}</label>
                        <select name="status" class="input-glass w-full">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary ripple-btn w-full justify-center h-[42px] flex items-center">{{ __('Generate Report') }}</button>
                </form>
            </div>
        </div>

        @if($hasFilters ?? false)
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--color-text-muted);">{{ __('Total Revenue') }}</div>
                    <div class="text-3xl font-black text-emerald-400">{{ number_format($stats['total_revenue'], 2) }} {{ __('ر.س') }}</div>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--color-text-muted);">{{ __('Transactions') }}</div>
                    <div class="text-3xl font-black text-blue-400">{{ number_format($stats['count']) }}</div>
                </div>
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--color-text-muted);">{{ __('Average Value') }}</div>
                    <div class="text-3xl font-black text-purple-400">{{ number_format($stats['average_value'], 2) }} {{ __('ر.س') }}</div>
                </div>
            </div>

            {{-- Results Table --}}
            <div class="glass-card overflow-hidden mt-8" data-aos="fade-up" data-aos-delay="400">
                <div class="glass-card-header flex justify-between items-center">
                    <h3 class="font-bold text-lg" style="color: var(--color-text);">{{ __('Transactions') }}</h3>
                    <form action="{{ route('admin.payments.export-report') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <button type="submit" class="btn-secondary btn-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            {{ __('Export CSV') }}
                        </button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b" style="border-color: var(--glass-border);">
                                <th class="p-4 font-bold text-sm" style="color: var(--color-text-muted);">{{ __('Date') }}</th>
                                <th class="p-4 font-bold text-sm" style="color: var(--color-text-muted);">{{ __('User') }}</th>
                                <th class="p-4 font-bold text-sm" style="color: var(--color-text-muted);">{{ __('Course') }}</th>
                                <th class="p-4 font-bold text-sm" style="color: var(--color-text-muted);">{{ __('Amount') }}</th>
                                <th class="p-4 font-bold text-sm" style="color: var(--color-text-muted);">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: var(--glass-border);">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 text-sm" style="color: var(--color-text);">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td class="p-4 text-sm font-bold" style="color: var(--color-text);">{{ $payment->user->name ?? 'Unknown' }}</td>
                                <td class="p-4 text-sm" style="color: var(--color-text);">{{ $payment->course->title ?? '-' }}</td>
                                <td class="p-4 text-sm font-mono font-bold" style="color: var(--color-text);">{{ number_format($payment->final_amount, 2) }} {{ __('ر.س') }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $payment->payment_status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : '' }}
                                        {{ $payment->payment_status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                        {{ $payment->payment_status === 'failed' ? 'bg-red-500/10 text-red-400' : '' }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-sm" style="color: var(--color-text-muted);">{{ __('No transactions found for the selected criteria.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->isNotEmpty())
                <div class="p-4 border-t" style="border-color: var(--glass-border);">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        @else
            <div class="mt-12 text-center" data-aos="fade-up">
                <div class="w-16 h-16 rounded-full bg-primary-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">{{ __('Select Filters to Generate Report') }}</h3>
                <p class="text-sm max-w-md mx-auto" style="color: var(--color-text-muted);">{{ __('Choose a date range and status above to see revenue statistics and transaction details.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
