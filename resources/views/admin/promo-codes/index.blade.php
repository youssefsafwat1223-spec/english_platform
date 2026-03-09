@extends('layouts.admin')
@section('title', __('Promo Codes'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-center mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Promo Codes') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Manage discount coupons for courses') }}</p>
            </div>
            <a href="{{ route('admin.promo-codes.create') }}" class="btn-primary ripple-btn">{{ __('+ Create Code') }}</a>
        </div>

        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Usage') }}</th>
                            <th>{{ __('Expires At') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promoCodes as $code)
                        <tr>
                            <td>
                                <div class="font-mono font-bold text-lg" style="color: var(--color-text);">{{ $code->code }}</div>
                            </td>
                            <td>
                                <span class="badge-accent text-sm">
                                    {{ $code->discount_type === 'percentage' ? $code->discount_amount . '%' : $code->discount_amount . ' ر.س' }}
                                </span>
                            </td>
                            <td>
                                <div style="color: var(--color-text);">
                                    {{ $code->used_count }} / {{ $code->usage_limit ?: '∞' }}
                                </div>
                                <div class="w-24 bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full mt-1 overflow-hidden">
                                    @php
                                        $percent = $code->usage_limit ? min(100, ($code->used_count / $code->usage_limit) * 100) : 0;
                                        $color = $percent >= 100 ? 'bg-red-500' : 'bg-primary-500';
                                    @endphp
                                    <div class="h-full {{ $color }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                            <td class="text-sm" style="color: var(--color-text-muted);">
                                {{ $code->expires_at ? $code->expires_at->format('M d, Y') : __('Never') }}
                            </td>
                            <td>
                                @if($code->isValid())
                                    <span class="badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-red-500/10 text-red-500 text-xs font-bold">{{ __('Inactive/Expired') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.promo-codes.edit', $code) }}" class="text-primary-500 text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.promo-codes.destroy', $code) }}" method="POST" class="inline" onsubmit="return confirm('Delete this promo code?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm font-bold hover:underline">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12" style="color: var(--color-text-muted);">
                                <div class="text-4xl mb-4">🎟️</div>
                                <p class="mb-4">{{ __('No promo codes created yet') }}</p>
                                <a href="{{ route('admin.promo-codes.create') }}" class="btn-primary ripple-btn">{{ __('Create First Code') }}</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $promoCodes->links() }}</div>
        </div>
    </div>
</div>
@endsection
