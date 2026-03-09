@extends('layouts.app')

@section('title', __('Checkout') . ' — ' . config('app.name'))

@section('content')
<div class="relative min-h-screen pb-20">
    {{-- Cinematic Hero Background --}}
    <div class="absolute top-0 left-0 w-full h-[40vh] z-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-200 to-slate-100 dark:from-[#020617] dark:to-slate-900 flex items-center justify-center">
            <div class="w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary-500/20 via-transparent to-transparent"></div>
        </div>
        {{-- Deep Gradient Overlays for SaaS look --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50/50 via-slate-50/95 to-slate-50 dark:from-[#020617]/50 dark:via-[#020617]/95 dark:to-[#020617] transition-colors duration-500"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-24 lg:pt-32">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10" data-aos="fade-down">
            <div>
                <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white transition-colors mb-4 group">
                    <span class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center mr-3 group-hover:bg-slate-300 dark:group-hover:bg-white/10 transition-colors border border-slate-300 dark:border-white/10 text-slate-600 dark:text-slate-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </span>
                    {{ __('العودة للكورس') }}
                </a>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight drop-shadow-sm dark:drop-shadow-lg">{{ __('إتمام الدفع بأمان') }}</h1>
            </div>
            <div class="hidden sm:flex items-center gap-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 px-4 py-2 rounded-full font-bold shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                SSL Encrypted
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <div class="lg:col-span-7 xl:col-span-7 space-y-8">
                
                {{-- Payment Details Card --}}
                <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200 dark:border-white/5 bg-white/50 dark:bg-[#0f172a]/50 backdrop-blur-xl shadow-lg group" data-aos="fade-up">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-lg shadow-lg">💳</span>
                            {{ __('تفاصيل الدفع') }}
                        </h3>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <form action="{{ route('student.courses.payment', $course) }}" method="POST" id="paymentForm" x-data="{ loading: false }" @submit="loading = true">
                            @csrf
                            
                            <div class="space-y-4 mb-4">
                                @if(isset($promoCode))
                                    {{-- If a valid promo code is applied, show it locked in --}}
                                    <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ __('تم تطبيق كوبون الخصم!') }}</p>
                                            <p class="text-xs text-emerald-600 dark:text-emerald-500 font-mono mt-1">{{ $promoCode->code }}</p>
                                        </div>
                                        <a href="{{ route('student.courses.remove-discount', $course) }}" class="text-xs font-bold text-rose-500 hover:text-rose-600 underline px-2">{{ __('حذف') }}</a>
                                    </div>
                                    <input type="hidden" name="promo_code_id" value="{{ $promoCode->id }}">
                                @elseif(auth()->user()->has_referral_discount)
                                    <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ __('تم تطبيق خصم الدعوة!') }}</p>
                                            <p class="text-xs text-emerald-600 dark:text-emerald-500 font-medium mt-1">{{ session('referral_code', __('مطبق على حسابك الحالي')) }}</p>
                                        </div>
                                        <a href="{{ route('student.courses.remove-discount', $course) }}" class="text-xs font-bold text-rose-500 hover:text-rose-600 underline px-2">{{ __('حذف') }}</a>
                                    </div>
                                @else
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('هل معاك كود دعوة؟ (من صديق)') }}</label>
                                    <div class="relative flex gap-2">
                                        <div class="relative flex-grow">
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </div>
                                            <input id="referral_code" name="referral_code" type="text" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-4 pr-12 pl-4 text-slate-900 dark:text-white font-semibold focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-inner" placeholder="{{ __('أدخل كود الدعوة (اختياري)') }}" value="{{ old('referral_code', session('referral_code')) }}">
                                        </div>
                                    </div>
                                    @error('referral_code')
                                        <p class="text-rose-500 text-sm font-semibold mt-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endif
                            </div>
                            
                            @if(!isset($promoCode))
                            <div class="mb-8 p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('أو معاك كوبون خصم؟ (من المنصة)') }}</label>
                                <div class="flex gap-2">
                                    <input type="text" id="promo_code_input" class="w-full bg-white dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 text-sm font-mono uppercase text-slate-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors" placeholder="مثال: SUMMER50">
                                    <button type="button" onclick="applyPromoCode()" class="bg-slate-800 dark:bg-white text-white dark:text-slate-900 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-700 dark:hover:bg-slate-200 whitespace-nowrap">{{ __('تطبيق') }}</button>
                                </div>
                                <script>
                                    function applyPromoCode() {
                                        const code = document.getElementById('promo_code_input').value;
                                        if(code.trim() === '') return;
                                        window.location.href = "{{ route('student.courses.enroll', $course) }}?promo_code=" + encodeURIComponent(code);
                                    }
                                </script>
                            </div>
                            @endif
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-500 hover:to-accent-500 text-white font-black text-lg py-4 rounded-xl shadow-lg shadow-primary-500/30 transform hover:scale-[1.02] transition-all flex items-center justify-center border-0" :disabled="loading" :class="loading ? 'opacity-70 cursor-not-allowed transform-none' : ''">
                                <span x-show="!loading" class="flex items-center justify-center gap-2">
                                    {{ __('الانتقال للدفع') }}
                                    <svg class="w-5 h-5 ml-1 mr-2 scale-x-[-1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </span>
                                <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    {{ __('جاري تحويلك للدفع بأمان...') }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- What Happens Next --}}
                <div class="glass-card overflow-hidden rounded-[2rem] border border-slate-200 dark:border-white/5 bg-white/30 dark:bg-black/10" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-6 sm:p-8">
                        <h3 class="font-bold text-lg mb-6 text-slate-900 dark:text-white">{{ __('إيه اللي هيحصل بعدين؟') }}</h3>
                        <div class="space-y-6">
                            @php $steps = ['هتكمل عملية الدفع بأمان على بوابة الدفع.', 'هيتم تفعيل الكورس في حسابك تلقائياً.', 'هتقدر تبدأ مذاكرة فوراً من لوحة تحكمك.']; @endphp
                            @foreach($steps as $step)
                                <div class="flex items-start gap-4">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-white/10 flex items-center justify-center text-sm font-bold shrink-0 shadow-inner mt-0.5">{{ $loop->iteration }}</div>
                                    <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $step }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT COLUMN: ORDER SUMMARY ─── --}}
            <div class="lg:col-span-5 xl:col-span-5 relative mt-8 lg:mt-0">
                <div class="sticky top-28" data-aos="fade-left" data-aos-delay="100">
                    
                    <div class="relative bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-1 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-accent-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <div class="bg-slate-50 dark:bg-[#020617] rounded-[1.8rem] p-8 relative z-10 border border-slate-100 dark:border-white/5">
                            
                            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-wider">{{ __('ملخص الطلب') }}</h3>
                            
                            {{-- Course Details inside summary --}}
                            <div class="flex gap-4 items-center mb-8 bg-white dark:bg-white/5 rounded-xl p-3 border border-slate-200 dark:border-white/5 shadow-sm">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-20 h-20 rounded-lg object-cover shadow-sm shrink-0" alt="{{ $course->title }}">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-slate-200 dark:border-white/5">🎓</div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">{{ $course->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 font-medium">{{ $course->level ?? 'Beginner' }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-slate-600 dark:text-slate-400 font-medium">{{ __('السعر الأصلي للكورس') }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($course->price, 2) }} ر.س</span>
                                </div>
                                
                                @if($discount > 0)
                                    <div class="flex justify-between items-center text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-500/10 p-2 rounded-lg -mx-2 px-2 border border-emerald-100 dark:border-emerald-500/20">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                            @if(isset($promoCode))
                                                {{ __('خصم الكوبون') }}
                                            @else
                                                {{ __('خصم الدعوة') }}
                                            @endif
                                        </div>
                                        <span>-{{ number_format($discount, 2) }} ر.س</span>
                                    </div>
                                @endif
                                
                                <div class="pt-6 mt-4 border-t border-slate-200 dark:border-white/10 border-dashed">
                                    <div class="flex justify-between items-end">
                                        <span class="font-black text-slate-900 dark:text-white text-lg">{{ __('الإجمالي المطلوب') }}</span>
                                        <div class="text-left">
                                            <span class="text-4xl font-black text-primary-600 dark:text-primary-400 leading-none">{{ number_format($finalAmount, 2) }} ر.س</span>
                                            <p class="text-xs text-slate-500 mt-1 font-medium">{{ __('السعر معروض بالريال السعودي.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
