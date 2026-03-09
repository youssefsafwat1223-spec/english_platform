@extends('layouts.admin')
@section('title', __('Payments'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Payment Transactions') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Monitor payments, refunds, and revenue') }}</p>
            </div>
            <a href="{{ route('admin.payments.reports') }}" class="btn-secondary">{{ __('Reports') }}</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php $payStats = [
                ['v' => number_format($stats['total_revenue'], 2).' ر.س', 'l' => 'Total Revenue', 'c' => 'text-emerald-500'],
                ['v' => number_format($stats['revenue_today'], 2).' ر.س', 'l' => 'Today', 'c' => 'text-primary-500'],
                ['v' => number_format($stats['revenue_this_month'], 2).' ر.س', 'l' => 'This Month', 'c' => 'text-blue-500'],
                ['v' => $stats['total_transactions'], 'l' => 'Total Transactions', 'c' => 'text-amber-500'],
            ]; @endphp
            @foreach($payStats as $i => $ps)
            <div class="glass-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="glass-card-body text-center">
                    <div class="text-3xl font-extrabold {{ $ps['c'] }}">{{ $ps['v'] }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ $ps['l'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="glass-card mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="text" name="search" placeholder="{{ __('Search transaction ID...') }}" class="input-glass" value="{{ request('search') }}">
                    <select name="status" class="input-glass">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                    </select>
                    <input type="date" name="from_date" class="input-glass" value="{{ request('from_date') }}">
                    <input type="date" name="to_date" class="input-glass" value="{{ request('to_date') }}">
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Student') }}</th><th>{{ __('Course') }}</th><th>{{ __('Amount') }}</th><th>{{ __('Status') }}</th><th>{{ __('Transaction') }}</th><th>{{ __('Date') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="font-bold" style="color: var(--color-text);">{{ $payment->user->name }}</td>
                            <td>{{ $payment->course->title }}</td>
                            <td class="font-bold">{{ $payment->currency }} {{ number_format($payment->final_amount, 2) }}</td>
                            <td>
                                @if($payment->payment_status == 'completed')<span class="badge-success">{{ __('Completed') }}</span>
                                @elseif($payment->payment_status == 'pending')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-bold">{{ __('Pending') }}</span>
                                @elseif($payment->payment_status == 'failed')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Failed') }}</span>
                                @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ ucfirst($payment->payment_status) }}</span>@endif
                            </td>
                            <td><span class="text-xs font-mono" style="color: var(--color-text-muted);">{{ $payment->transaction_id ?? '-' }}</span></td>
                            <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            <td><a href="{{ route('admin.payments.show', $payment) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No payments yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $payments->links() }}</div>
        </div>
    </div>
</div>
@endsection
