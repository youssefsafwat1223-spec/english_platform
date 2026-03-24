@extends('layouts.admin')
@section('title', __('Payment Details'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">Payment #{{ $payment->id }}</span></h1>
            <a href="{{ route('admin.payments.index') }}" class="text-primary-500 font-bold text-sm hover:underline mt-2 inline-block">{{ __('← Back to Payments') }}</a>
        </div>
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold" style="color: var(--color-text);">{{ __('Payment Information') }}</h3></div>
            <div class="glass-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Student') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->user->name }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Email') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->user->email }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Course') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->course->title }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Currency') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->currency }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Original Amount') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Discount') }}</div><div class="font-bold text-emerald-500">-{{ $payment->currency }} {{ number_format($payment->discount_amount, 2) }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Final Amount') }}</div><div class="font-extrabold text-xl text-primary-500">{{ $payment->currency }} {{ number_format($payment->final_amount, 2) }}</div></div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Status') }}</div>
                        @if($payment->payment_status == 'completed')<span class="badge-success">{{ __('Completed') }}</span>
                        @elseif($payment->payment_status == 'pending')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-500 text-xs font-bold">{{ __('Pending') }}</span>
                        @elseif($payment->payment_status == 'failed')<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Failed') }}</span>
                        @else<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-primary-500/10 text-primary-500 text-xs font-bold">{{ ucfirst($payment->payment_status) }}</span>@endif
                    </div>
                    <div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Date') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->created_at->format('M d, Y H:i') }}</div></div>
                    @if($payment->paid_at)<div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Paid At') }}</div><div class="font-bold" style="color: var(--color-text);">{{ $payment->paid_at->format('M d, Y H:i') }}</div></div>@endif
                    @if($payment->transaction_id)<div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Transaction ID') }}</div><div class="font-mono text-sm" style="color: var(--color-text);">{{ $payment->transaction_id }}</div></div>@endif
                    @if($payment->gateway_payment_id)<div><div class="text-xs" style="color: var(--color-text-muted);">{{ __('Gateway Payment ID') }}</div><div class="font-mono text-sm" style="color: var(--color-text);">{{ $payment->gateway_payment_id }}</div></div>@endif
                </div>
            </div>
        </div>
        @if($payment->is_completed && !$payment->refunded_at)
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up">
            <div class="glass-card-header"><h3 class="font-bold text-red-500">{{ __('Refund Payment') }}</h3></div>
            <div class="glass-card-body">
                <form action="{{ route('admin.payments.refund', $payment) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this payment?')">@csrf
                    <p class="text-sm mb-4" style="color: var(--color-text-muted);">{{ __('Refunding will return the money to the customer and revoke their access to the course.') }}</p>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-red-500/10 text-red-500 text-sm font-bold hover:bg-red-500/20 transition-colors">{{ __('Process Refund') }}</button>
                </form>
            </div>
        </div>
        @endif
        @if($payment->error_message)
        <div class="glass-card overflow-hidden mb-6" data-aos="fade-up"><div class="glass-card-body"><p class="text-red-500 font-bold text-sm">{{ $payment->error_message }}</p></div></div>
        @endif
        @if($payment->refunded_at)
        <div class="glass-card overflow-hidden" data-aos="fade-up"><div class="glass-card-body"><p class="text-amber-500 font-bold text-sm">{{ __('Payment was refunded on') }} {{ $payment->refunded_at->format('M d, Y H:i') }}</p></div></div>
        @endif
    </div>
</div>
@endsection
