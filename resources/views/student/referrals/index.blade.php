@extends('layouts.app')

@section('title', __('Referral Program') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-10" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>🤝</span> {{ __('Invite & Earn') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Referral') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Program') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Invite friends to join the platform and earn exclusive discounts together on your next course purchases.') }}
                    </p>
                </div>
                
                <div class="shrink-0">
                    <a href="{{ route('student.referrals.how-it-works') }}" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-violet-500/25 flex items-center gap-2 font-bold bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 border-none text-white transition-all transform hover:scale-105">
                        <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-sm shadow-inner shrink-0">?</span>
                        {{ __('How It Works') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Hero Referral Code Card --}}
        <div class="glass-card overflow-hidden rounded-[2.5rem] mb-12 border-t-8 border-t-primary-500 shadow-2xl relative" data-aos="zoom-in">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-accent-500/10 pointer-events-none"></div>
            
            {{-- Decorative Blobs --}}
            <div class="absolute -top-32 -right-32 w-64 h-64 rounded-full bg-primary-500/20 mix-blend-multiply filter blur-[64px] pointer-events-none"></div>
            <div class="absolute -bottom-32 -left-32 w-64 h-64 rounded-full bg-accent-500/20 mix-blend-multiply filter blur-[64px] pointer-events-none"></div>
            
            <div class="p-8 md:p-14 relative z-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-3xl bg-white dark:bg-slate-800 shadow-xl text-primary-500 rotate-3 transform group-hover:rotate-6 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                </div>
                
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white mb-8">{{ __('Your Unique Referral Code') }}</h2>
                
                <div class="rounded-3xl p-6 md:p-8 max-w-xl mx-auto mb-8 bg-white/60 dark:bg-slate-900/60 border border-slate-200/50 dark:border-white/10 shadow-inner backdrop-blur-md relative group cursor-pointer" onclick="copyReferralLink()">
                    <div class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">{{ __('Share this code to get started') }}</div>
                    <div class="font-mono text-4xl md:text-6xl font-black bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-accent-500 tracking-wider mb-4">{{ $user->referral_code }}</div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-2xl bg-primary-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg scale-90 group-hover:scale-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4 max-w-xl mx-auto">
                    <button onclick="copyReferralLink()" class="btn-primary ripple-btn flex-1 py-4 text-lg font-bold shadow-lg shadow-primary-500/25 justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        {{ __('Copy Link') }}
                    </button>
                </div>

                @if($hasDiscount)
                    <div class="mt-8 rounded-2xl p-6 bg-emerald-500/10 border border-emerald-500/20 max-w-xl mx-auto flex items-center gap-4 text-left animate-pulse-soft">
                        <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/30">
                            <svg class="w-7 h-7 transform rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        </div>
                        <div>
                            <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mb-1">{{ __('🎉 You have an available discount!') }}</div>
                            <div class="text-emerald-700/80 dark:text-emerald-300/80 font-medium">{{ __('Use it on your next course purchase at checkout.') }}</div>
                        </div>
                    </div>
                @endif

                {{-- Free Enrollment Progress --}}
                @if($hasFreeEnrollment)
                    <div class="mt-8 rounded-2xl p-6 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 max-w-xl mx-auto flex items-center gap-4 text-left">
                        <div class="w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-green-500/30">
                            <span class="text-2xl">🎁</span>
                        </div>
                        <div>
                            <div class="text-xl font-black text-green-600 dark:text-green-400 mb-1">🎉 {{ __('لديك اشتراك مجاني!') }}</div>
                            <div class="text-green-700/80 dark:text-green-300/80 font-medium">{{ __('يمكنك الاشتراك في أي كورس مجاناً. اختر الكورس من صفحة الكورسات.') }}</div>
                        </div>
                    </div>
                @else
                    <div class="mt-8 max-w-xl mx-auto">
                        <div class="rounded-2xl p-6 bg-white/60 dark:bg-slate-900/60 border border-slate-200/50 dark:border-white/10 backdrop-blur-md">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">🎁 {{ __('اشتراك مجاني عند إحالة 5 أشخاص') }}</span>
                                <span class="text-sm font-black text-primary-500">{{ min($referralProgress, 5) }}/5</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-500" style="width: {{ min(($referralProgress / 5) * 100, 100) }}%"></div>
                            </div>
                            @if($referralProgress < 5)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">{{ __('باقي') }} {{ 5 - $referralProgress }} {{ __('إحالات للحصول على اشتراك مجاني!') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Statistics Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-12">
            @php
                $refStats = [
                    ['icon' => '👆', 'value' => $stats['total_clicks'], 'label' => 'Total Clicks', 'color' => 'primary'],
                    ['icon' => '👤', 'value' => $stats['total_registrations'], 'label' => 'Registrations', 'color' => 'emerald'],
                    ['icon' => '💰', 'value' => $stats['total_purchases'], 'label' => 'Purchases', 'color' => 'blue'],
                    ['icon' => '🏷️', 'value' => $stats['available_discounts'], 'label' => 'Discounts', 'color' => 'amber'],
                ];
            @endphp
            @foreach($refStats as $s)
                <div class="glass-card p-6 md:p-8 text-center group rounded-[2rem] border border-slate-200/50 dark:border-white/5 bg-white/50 dark:bg-slate-900/50 relative overflow-hidden" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $s['color'] }}-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="text-4xl mb-4 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-500 inline-block">{{ $s['icon'] }}</div>
                    <div class="text-4xl md:text-5xl font-black bg-clip-text text-transparent bg-gradient-to-r from-{{ $s['color'] }}-600 to-{{ $s['color'] }}-400 mb-2">{{ $s['value'] }}</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __($s['label']) }}</div>
                </div>
            @endforeach
        </div>

        {{-- Referrals Table --}}
        <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/50 dark:border-white/5 shadow-xl bg-white/80 dark:bg-slate-900/80" data-aos="fade-up">
            <div class="px-6 py-5 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl shrink-0 shadow-inner">
                        👥
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ __('Your Referrals') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Track the status of friends you\'ve invited') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/50 border-y border-slate-200/50 dark:border-white/5 text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">{{ __('Friend') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Date') }}</th>
                            <th class="px-6 py-4">{{ __('Discount Earned') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5">
                        @forelse($referrals as $referral)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-sm shrink-0 uppercase">
                                            {{ substr($referral->referee->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors">{{ $referral->referee->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $referral->referee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($referral->status === 'purchased')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider border border-emerald-500/20 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            {{ __('Purchased') }}
                                        </span>
                                    @elseif($referral->status === 'registered')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-500/10 text-primary-600 dark:text-primary-400 text-xs font-black uppercase tracking-wider border border-primary-500/20 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ __('Registered') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-wider border border-blue-500/20 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ __('Clicked') }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $referral->created_at->format('M d, Y') }}</div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($referral->referrer_earned_discount)
                                        @if($referral->referrer_discount_used)
                                            <span class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                {{ __('Used') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 text-sm font-bold border border-amber-500/20 shadow-sm">
                                                <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                                {{ __('Available') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-medium text-sm">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 mb-6 border border-slate-200 dark:border-white/5 shadow-inner">
                                        <span class="text-4xl text-slate-400">👥</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No referrals yet') }}</h3>
                                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-sm mx-auto">
                                        {{ __('Share your referral code with friends and start earning discounts on your next courses.') }}
                                    </p>
                                    <button onclick="copyReferralLink()" class="btn-primary ripple-btn inline-flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        {{ __('Copy Your Link') }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($referrals) && method_exists($referrals, 'links') && $referrals->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20">
                    {{ $referrals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyReferralLink() {
    const link = '{{ route('referral.track', $user->referral_code) }}';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(() => {
            if (window.showNotification) window.showNotification(__('Referral link copied to clipboard! 🚀'), 'success');
        }).catch(err => {
            prompt(__('Copy this link:'), link);
        });
    } else {
        prompt(__('Copy this link:'), link);
    }
}
</script>
@endpush
@endsection
