@extends('layouts.app')

@section('title', __('How Referrals Work') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 lg:py-16 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-primary-600/10 via-accent-500/5 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="mb-12 text-center" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('How It Works') }}
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-4">
                {{ __('The Referral Program') }}
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 font-medium max-w-2xl mx-auto">
                {{ __('Invite your friends to learn with us and both of you can benefit from referral discounts on purchases. Once 5 people register successfully through your link, you unlock one free course.') }}
            </p>
        </div>

        {{-- Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-16 relative">
            {{-- Connecting Line (Desktop Only) --}}
            <div class="hidden md:block absolute top-12 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-transparent via-primary-500/30 to-transparent -z-10 mt-4"></div>
            
            @php
                $steps = [
                    ['icon' => '📤', 'title' => 'Share Your Link', 'desc' => 'Send your unique referral code or link to friends, classmates, or on social media.', 'color' => 'blue'],
                    ['icon' => '🛒', 'title' => 'They Purchase', 'desc' => 'Your friend enters your referral code during their first course checkout to get ' . $discountPercentage . '% off.', 'color' => 'purple'],
                    ['icon' => '🎉', 'title' => 'You Earn', 'desc' => 'Once their purchase is complete, you automatically earn a ' . $discountPercentage . '% discount for your next course.', 'color' => 'emerald'],
                ];
            @endphp
            
            @foreach($steps as $step)
                <div class="glass-card p-8 md:p-10 text-center group rounded-[2.5rem] border border-slate-200/50 dark:border-white/5 bg-white/60 dark:bg-slate-900/60 shadow-xl relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-{{ $step['color'] }}-500/10" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $step['color'] }}-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative w-24 h-24 mx-auto mb-6">
                        <div class="absolute inset-0 rounded-full bg-{{ $step['color'] }}-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="w-24 h-24 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 shadow-lg flex items-center justify-center text-4xl relative z-10 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                            {{ $step['icon'] }}
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-{{ $step['color'] }}-500 text-white flex items-center justify-center font-black text-sm shadow-md border-2 border-white dark:border-slate-900 z-20">
                            {{ $loop->iteration }}
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-3 tracking-tight">{{ __($step['title']) }}</h3>
                    <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 font-medium leading-relaxed">{{ __($step['desc']) }}</p>
                </div>
            @endforeach
        </div>

        {{-- Rules & Guidelines --}}
        <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/50 dark:border-white/5 shadow-xl bg-white/80 dark:bg-slate-900/80 mb-12 max-w-3xl mx-auto" data-aos="fade-up">
            <div class="px-8 py-6 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    📜
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Program Rules & Guidelines') }}</h2>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Please keep these simple rules in mind, thank you!') }}</p>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <ul class="space-y-4">
                    @php
                        $rules = [
                            'Discount is applied automatically at checkout when using a valid code.',
                            'When 5 people register successfully through your link, you unlock one free course.',
                            'Each referral code can be used by up to 5 successful purchases for referral discounts.',
                            'Referral discounts can be stacked/used once per user purchase.',
                            'Referral discounts expire after 30 days if left unused.',
                            'Self-referrals (using your own code with a different email) are not allowed and will be voided.',
                        ];
                    @endphp
                    @foreach($rules as $rule)
                        <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:border-primary-500/30 transition-colors group">
                            <div class="w-8 h-8 rounded-full bg-primary-500/10 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0 text-sm font-black shadow-sm group-hover:bg-primary-500 group-hover:text-white transition-colors">
                                {{ $loop->iteration }}
                            </div>
                            <span class="text-slate-700 dark:text-slate-300 font-medium leading-relaxed mt-0.5">{{ __($rule) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Action Area --}}
        <div class="text-center pb-8" data-aos="fade-up">
            <a href="{{ route('student.referrals.index') }}" class="btn-primary ripple-btn inline-flex items-center gap-2 px-8 py-4 rounded-xl shadow-lg shadow-primary-500/25 font-bold text-lg">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Go to My Referrals') }}
            </a>
        </div>
    </div>
</div>
@endsection
