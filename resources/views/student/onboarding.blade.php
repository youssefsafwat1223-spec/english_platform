@extends('layouts.app')

@php
    $telegramBotUsername = ltrim((string) config('services.telegram.bot_username', 'SimpleEnglishBot'), '@');
@endphp

@section('title', __('ui.onboarding.title') . ' - ' . config('app.name'))

@section('content')
<div class="min-h-screen py-12 relative overflow-hidden flex items-center justify-center" 
     x-data="onboardingFlow()" 
     dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Background Effects --}}
    <div class="absolute inset-0 bg-animated-gradient"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    
    {{-- Floating Orbs --}}
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-white/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 rounded-full bg-white/5 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="w-full student-container max-w-2xl relative z-10" x-cloak>

        {{-- Main Container Card --}}
        <x-student.card padding="p-0" class="shadow-2xl border-t-4 border-primary-500 overflow-hidden relative" data-aos="fade-up">

            {{-- Progress Bar --}}
            <div class="h-2 bg-white/10 w-full relative">
                <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-500 ease-out"
                     :style="{ width: ((step / 4) * 100) + '%' }"></div>
            </div>

            <div class="p-8 sm:p-12">
                {{-- Global Error --}}
                <div x-show="errorMessage" x-transition class="mb-6 p-4 rounded-xl bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 flex items-start gap-3 text-red-700 dark:text-red-300">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium" x-text="errorMessage"></span>
                </div>

                {{-- STEP 1: Personal Info (Age) --}}
                <div x-show="step === 1" x-transition.opacity.duration.300ms>
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-2xl bg-primary-100 dark:bg-primary-500/20 text-primary-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">{{ __('ui.onboarding.welcome', ['name' => explode(' ', $user->name)[0]]) }}</h2>
                        <p class="text-gray-600 dark:text-white/70">{{ __('ui.onboarding.welcome_text') }}</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white/80 mb-2">{{ __('ui.onboarding.full_name') }}</label>
                            <input type="text" x-model="form.name" required
                                   class="input-glass bg-white/50 dark:bg-white/5 border-gray-200 dark:border-white/10 focus:border-primary-500 text-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white/80 mb-2">{{ __('ui.onboarding.age') }}</label>
                            <input type="number" x-model="form.age" min="5" max="120" required placeholder="25"
                                   @keydown.enter.prevent="goNext(2)"
                                   class="input-glass text-center text-xl bg-white/50 dark:bg-white/5 border-gray-200 dark:border-white/10 focus:border-primary-500 text-gray-900 dark:text-white">
                        </div>

                        <button @click="goNext(2)" :class="{'opacity-50 cursor-not-allowed': !form.name || !form.age}" class="btn-primary w-full py-4 text-lg">
                            {{ __('ui.onboarding.next_step') }}
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Location & Contact --}}
                <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-2xl bg-accent-100 dark:bg-accent-500/20 text-accent-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">{{ __('ui.onboarding.where_from') }}</h2>
                        <p class="text-gray-600 dark:text-white/70">{{ __('ui.onboarding.where_from_text') }}</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white/80 mb-2">{{ __('ui.onboarding.address_or_city') }}</label>
                            <input type="text" x-model="form.address" placeholder="{{ __('ui.onboarding.address_placeholder') }}"
                                   class="input-glass bg-white/50 dark:bg-white/5 border-gray-200 dark:border-white/10 focus:border-primary-500 text-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white/80 mb-2">{{ __('ui.onboarding.backup_email') }}</label>
                            <input type="email" x-model="form.secondary_email" placeholder="backup@example.com"
                                   @keydown.enter.prevent="goNext(3)"
                                   class="input-glass bg-white/50 dark:bg-white/5 border-gray-200 dark:border-white/10 focus:border-primary-500 text-gray-900 dark:text-white">
                        </div>

                        <div class="flex gap-4">
                            <button @click="step = 1" class="btn-secondary px-6">{{ __('ui.onboarding.back') }}</button>
                            <button @click="goNext(3)" class="btn-primary flex-1 py-4 text-lg">
                                {{ __('ui.onboarding.next_step') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Phone & Telegram --}}
                <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-2xl bg-[#0088cc]/20 text-[#0088cc]">
                            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">{{ __('ui.onboarding.telegram_title') }}</h2>
                        <p class="text-gray-600 dark:text-white/70">{{ __('ui.onboarding.telegram_text') }}</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white/80 mb-2">{{ __('ui.onboarding.phone_number') }} <span class="text-red-500">*</span></label>
                            <div class="relative w-full text-left" dir="ltr">
                                <input type="tel" id="phone_input" x-model="form.phone" required placeholder="1012345678"
                                       @keydown.enter.prevent="saveAndConnect()"
                                       class="input-glass w-full !text-left text-xl tracking-wider font-mono bg-white/50 dark:bg-white/5 border-gray-200 dark:border-white/10 focus:border-[#0088cc] text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button @click="step = 2" class="btn-secondary px-6" :disabled="saving">{{ __('ui.onboarding.back') }}</button>
                            <button @click="saveAndConnect()" 
                                    :disabled="!form.phone || saving" 
                                    class="w-full flex-1 py-4 text-lg font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 text-white bg-[#0088cc] hover:bg-[#0077b5]">
                                <span x-show="!saving">{{ __('ui.onboarding.save_and_link') }}</span>
                                <span x-show="saving" x-cloak class="flex items-center gap-2">
                                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    {{ __('ui.onboarding.saving') }}
                                </span>
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button @click="skipTelegram()" class="text-sm text-gray-500 underline underline-offset-4 hover:text-gray-700 dark:hover:text-white">
                                {{ __('ui.onboarding.skip_telegram') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STEP 4: Telegram Instructions & Verification --}}
                <div x-show="step === 4" x-transition.opacity.duration.500ms style="display: none;">
                    
                    {{-- Default View: Waiting for connection --}}
                    <div x-show="!linked">
                        <div class="text-center mb-8">
                            <div class="relative inline-block mb-6">
                                <div class="w-20 h-20 rounded-full border-4 border-[#0088cc] border-t-transparent animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-[#0088cc]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                                </div>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('ui.onboarding.waiting_title') }}</h2>
                            <p class="text-gray-600 dark:text-white/70">{{ __('ui.onboarding.waiting_text') }}</p>
                        </div>

                        <div class="bg-slate-50 dark:bg-white/5 rounded-2xl p-6 border border-slate-200 dark:border-white/10 mb-8">
                            <ol class="space-y-4 text-slate-700 dark:text-white/90">
                                <li class="flex items-start gap-4">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0088cc]/20 text-[#0088cc] flex items-center justify-center font-bold">1</span>
                                    <div class="pt-1">
                                        <p class="font-medium">{{ __('ui.onboarding.waiting_step_1') }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0088cc]/20 text-[#0088cc] flex items-center justify-center font-bold">2</span>
                                    <div class="pt-1">
                                        <p class="font-medium">{{ __('ui.onboarding.waiting_step_2') }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-[#0088cc]/20 text-[#0088cc] flex items-center justify-center font-bold">3</span>
                                    <div class="pt-1">
                                        <p class="font-medium">{{ __('ui.onboarding.waiting_step_3') }}</p>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <a href="https://t.me/{{ $telegramBotUsername }}?start=1" target="_blank"
                           class="w-full py-4 text-lg font-bold rounded-xl shadow-[0_0_20px_rgba(0,136,204,0.3)] flex items-center justify-center gap-3 text-white bg-[#0088cc] hover:bg-[#0077b5] hover:scale-105 transition-transform mb-4">
                            <span>{{ __('ui.onboarding.open_bot') }}</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        
                        <div class="text-center mt-6">
                            <a href="{{ route('student.onboarding.complete') }}" class="text-sm text-gray-500 underline underline-offset-4 hover:text-gray-700 dark:hover:text-white">
                                {{ __('ui.onboarding.skip_to_dashboard') }}
                            </a>
                        </div>
                    </div>

                    {{-- Success View: Linked --}}
                    <div x-show="linked" x-cloak>
                        <div class="text-center mb-10">
                            <div class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-emerald-500/20 text-emerald-500 border-4 border-emerald-500/50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('ui.onboarding.success_title') }}</h2>
                            <p class="text-xl text-emerald-400 font-medium">{{ __('ui.onboarding.success_text') }}</p>
                            <p class="text-gray-600 dark:text-white/70 mt-4">{{ __('ui.onboarding.success_subtext') }}</p>
                        </div>

                        <a href="{{ route('student.onboarding.complete') }}" 
                           class="btn-primary w-full py-4 text-xl font-bold shadow-neon-cyan flex items-center justify-center gap-2 animate-bounce-soft">
                            {{ __('ui.onboarding.enter_dashboard') }}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                </div>
            </div>
        </x-student.card>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
    .iti { width: 100%; }
    .iti__flag {background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png");}
    @media (min-resolution: 2x) {
      .iti__flag {background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png");}
    }
    .iti__country-list { z-index: 50 !important; background-color: var(--tw-colors-slate-900) !important; color: white !important; border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; }
    .iti__country:hover { background-color: rgba(255,255,255,0.1) !important; }
    .iti__divider { border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
    
    html:not(.dark) .iti__country-list { background-color: white !important; color: #1e293b !important; border-color: #e2e8f0; }
    html:not(.dark) .iti__country:hover { background-color: #f1f5f9 !important; }
    html:not(.dark) .iti__divider { border-bottom-color: #e2e8f0 !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
    function onboardingFlow() {
        const texts = @js([
            'nameAgeRequired' => __('ui.onboarding.validation_name_age'),
            'phoneRequired' => __('ui.onboarding.validation_phone'),
            'genericError' => __('ui.onboarding.generic_error'),
            'connectionError' => __('ui.onboarding.connection_error'),
        ]);

        return {
            step: 1,
            linked: {{ $user->telegram_linked_at ? 'true' : 'false' }},
            saving: false,
            errorMessage: '',
            checkInterval: null,
            iti: null, // Store intl-tel-input instance
            form: {
                name: @json($user->name ?? ''),
                age: @json($user->age ?? ''),
                address: @json($user->address ?? ''),
                secondary_email: @json($user->secondary_email ?? ''),
                phone: @json($user->phone ?? '')
            },

            init() {
                // If user is already linked but somehow ended up here, they can just skip to end
                if (this.linked) {
                    this.step = 4;
                }

                this.$nextTick(() => {
                    const input = document.querySelector("#phone_input");
                    if (input) {
                        this.iti = window.intlTelInput(input, {
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                            initialCountry: "auto",
                            geoIpLookup: function(callback) {
                                fetch("https://ipapi.co/json")
                                    .then(function(res) { return res.json(); })
                                    .then(function(data) { callback(data.country_code); })
                                    .catch(function() { callback("eg"); });
                            },
                        });
                        
                        // Sync initial value if any
                        if (this.form.phone) {
                            this.iti.setNumber(this.form.phone);
                        }
                    }
                });
            },

            goNext(targetStep) {
                this.errorMessage = '';
                if (targetStep === 2 && (!this.form.name || !this.form.age)) {
                    this.errorMessage = texts.nameAgeRequired;
                    return;
                }
                this.step = targetStep;
            },

            async submitFormOnly() {
                this.errorMessage = '';
                this.saving = true;
                
                // Prefer full international number from plugin, then let backend normalize/validate.
                if (this.step === 3) {
                    const fullPhone = this.iti ? this.iti.getNumber() : this.form.phone;
                    this.form.phone = (fullPhone || this.form.phone || '').trim();

                    if (!this.form.phone) {
                        this.errorMessage = texts.phoneRequired;
                        this.saving = false;
                        return false;
                    }
                }

                try {
                    const response = await fetch('{{ route('student.onboarding.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();
                    
                    if (!response.ok) {
                        if (data.errors) {
                            this.errorMessage = Object.values(data.errors).flat().join(' | ');
                        } else {
                            this.errorMessage = data.message || texts.genericError;
                        }
                        this.saving = false;
                        return false;
                    }

                    return true;
                } catch (error) {
                    this.saving = false;
                    this.errorMessage = texts.connectionError;
                    return false;
                }
            },

            async saveAndConnect() {
                // Validation happens inside submitFormOnly now
                const success = await this.submitFormOnly();
                if (success) {
                    this.saving = false;
                    this.step = 4;
                    this.startPolling();
                }
            },

            async skipTelegram() {
                // Optionally save empty phone or whatever they typed, then just redirect
                if(this.iti) {
                   this.form.phone = this.iti.getNumber() || this.form.phone;
                }
                
                const success = await this.submitFormOnly();
                if (success) {
                    window.location.href = '{{ route('student.onboarding.complete') }}';
                }
            },

            startPolling() {
                if (!this.linked) {
                    this.checkInterval = setInterval(() => this.checkTelegram(), 3000);
                }
            },

            async checkTelegram() {
                try {
                    const res = await fetch('{{ route("student.onboarding.check-telegram") }}');
                    const data = await res.json();
                    if (data.linked && !this.linked) {
                        this.linked = true;
                        clearInterval(this.checkInterval);
                        
                        // Blast confetti
                        if (typeof confetti !== 'undefined') {
                            confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
                        }
                    }
                } catch (e) {
                    console.error('Polling error', e);
                }
            }
        }
    }
</script>
@endpush
@endsection





