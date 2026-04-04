@extends('layouts.app')

@section('title', __('Pricing') . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-animated-gradient opacity-5"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl animate-float pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 rounded-full bg-accent-500/10 blur-3xl animate-float-slow pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="badge-primary mb-4" data-aos="fade-up">{{ $isArabic ? 'الأسعار والخطط' : 'Pricing and plans' }}</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-6" style="color: var(--color-text);" data-aos="fade-up" data-aos-delay="100">
            {{ $isArabic ? 'سعر واضح' : 'Clear pricing' }}
            <span class="text-gradient">{{ $isArabic ? 'لتقدم واضح' : 'for clear progress' }}</span>
        </h1>
        <p class="text-lg max-w-3xl mx-auto mb-6" style="color: var(--color-text-muted);" data-aos="fade-up" data-aos-delay="200">
            {{ $isArabic ? 'اختر المسار المناسب لمستواك. كل كورس يشمل المنهج، التدريب العملي، الاختبارات، والشهادة القابلة للتحقق.' : 'Choose the course that matches your level. Each course includes curriculum, practical training, quizzes, and a verifiable certificate.' }}
        </p>
        <p class="text-sm max-w-3xl mx-auto mb-2" style="color: var(--color-text-muted);" data-aos="fade-up" data-aos-delay="250">
            {{ __('Any course duration shown on the site is a suggested study pace, not an access expiry.') }}
        </p>
        <p class="text-sm max-w-3xl mx-auto font-bold text-amber-600 dark:text-amber-400" data-aos="fade-up" data-aos-delay="300">
            {{ $isArabic ? 'سياسة الاسترداد: يوجد استرداد بشروط.' : 'Refund policy: Refunds are available under conditions.' }}
        </p>
    </div>
</section>

<section class="pb-24 relative -mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 rounded-2xl bg-primary-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'منهج تأسيسي واضح' : 'Clear foundation curriculum' }}</h3>
                <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'تعلم مرتب من البداية حتى الإتقان العملي.' : 'A structured path from basics to practical confidence.' }}</p>
            </div>

            <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 rounded-2xl bg-accent-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
                <h3 class="font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'تطبيق وتدريب فعلي' : 'Practical training' }}</h3>
                <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'تدريب نطق وتمارين واختبارات تثبت التعلم.' : 'Pronunciation practice, exercises, and quizzes that reinforce learning.' }}</p>
            </div>

            <div class="glass-card p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'شهادة قابلة للتحقق' : 'Verifiable certificate' }}</h3>
                <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'بعد الإكمال تحصل على شهادة يمكن التحقق منها.' : 'Earn a certificate that can be verified online after completion.' }}</p>
            </div>
        </div>

        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ $isArabic ? 'الكورسات المتاحة' : 'Available courses' }}</h2>
            <p class="text-sm mt-2" style="color: var(--color-text-muted);">{{ $isArabic ? 'اختر الكورس المناسب وابدأ بخطة واضحة.' : 'Pick a course and start with a clear plan.' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
                <div class="glass-card overflow-hidden group relative" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    @if($course->price == 0)
                        <div class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold shadow-lg">{{ $isArabic ? 'مجاني' : 'FREE' }}</div>
                    @endif

                    <div class="p-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xl font-bold mb-4 shadow-lg">
                            {{ substr($course->title, 0, 1) }}
                        </div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-primary-500 transition-colors" style="color: var(--color-text);">{{ $course->title }}</h3>
                        <p class="text-sm mb-6 line-clamp-2" style="color: var(--color-text-muted);">{{ $course->description }}</p>

                        <div class="flex items-end gap-2 mb-2">
                            @if($course->price > 0)
                                <span class="text-3xl font-extrabold" style="color: var(--color-text);">{{ number_format($course->price) }}</span>
                                <span class="text-sm mb-1" style="color: var(--color-text-muted);">{{ $isArabic ? 'ر.س' : 'SAR' }}</span>
                            @else
                                <span class="text-3xl font-extrabold text-emerald-500">{{ __('Free') }}</span>
                            @endif
                        </div>
                        @if($course->price > 0)
                            <p class="text-xs mb-5" style="color: var(--color-text-muted);">
                                {{ $isArabic ? 'قد تتوفر خيارات تقسيط حسب وسيلة الدفع.' : 'Installment options may be available depending on payment method.' }}
                            </p>
                        @endif

                        <ul class="space-y-2 mb-6">
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $isArabic ? 'منهج تأسيسي واضح' : 'Clear foundation curriculum' }}
                            </li>
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $isArabic ? 'اختبارات وتقييم مستمر' : 'Quizzes and ongoing assessment' }}
                            </li>
                            <li class="text-sm flex items-center gap-2" style="color: var(--color-text-muted);">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $isArabic ? 'شهادة قابلة للتحقق' : 'Verifiable completion certificate' }}
                            </li>
                        </ul>

                        <a href="{{ route('student.courses.show', $course) }}" class="block w-full text-center py-3 rounded-xl font-bold transition-all {{ $course->price == 0 ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $course->price == 0 ? ($isArabic ? 'ابدأ مجانًا' : 'Start Free') : ($isArabic ? 'عرض تفاصيل الكورس' : 'View Course Details') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">{{ $isArabic ? 'قريبًا' : 'Coming soon' }}</h3>
                    <p class="text-sm" style="color: var(--color-text-muted);">{{ $isArabic ? 'نعمل حاليًا على تجهيز كورسات جديدة.' : 'New courses are currently being prepared.' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-20 relative">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="badge-accent mb-4">{{ __('FAQ') }}</span>
            <h2 class="text-3xl font-extrabold" style="color: var(--color-text);">{{ $isArabic ? 'أسئلة شائعة' : 'Common questions' }}</h2>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            @php
                $faqs = [
                    [
                        'q' => $isArabic ? 'هل يمكنني التجربة قبل الشراء؟' : 'Can I try courses before buying?',
                        'a' => $isArabic ? 'نعم، لدينا كورسات مجانية يمكنك البدء بها مباشرة.' : 'Yes. We offer free courses that you can start immediately.'
                    ],
                    [
                        'q' => $isArabic ? 'هل يوجد استرداد؟' : 'Is there a refund policy?',
                        'a' => $isArabic ? 'يمكن تقديم طلب استرداد خلال أول 7 أيام إذا لم تتجاوز نسبة مشاهدة كبيرة من المحتوى.' : 'You can request a refund within the first 7 days if only a limited portion of content has been consumed.'
                    ],
                    [
                        'q' => $isArabic ? 'هل ينتهي الوصول بعد مدة الكورس؟' : 'Does access expire after course duration?',
                        'a' => $isArabic ? 'لا. مدة الكورس المعروضة هي مدة مقترحة للدراسة، وليست تاريخ انتهاء للوصول.' : 'No. Course duration is a suggested study timeline, not an access expiration date.'
                    ],
                    [
                        'q' => $isArabic ? 'هل الشهادة لها صلاحية انتهاء؟' : 'Do certificates expire?',
                        'a' => $isArabic ? 'لا، الشهادة تظل متاحة ويمكن التحقق منها إلكترونيًا.' : 'No. Certificates remain available and can be verified online.'
                    ],
                    [
                        'q' => $isArabic ? 'هل تعمل المنصة على الجوال؟' : 'Can I use the platform on mobile?',
                        'a' => $isArabic ? 'نعم، المنصة متوافقة مع الجوال والأجهزة المختلفة.' : 'Yes. The platform is responsive and works across devices.'
                    ],
                ];
            @endphp

            @foreach($faqs as $i => $faq)
                <div class="glass-card overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                    <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                        class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold text-sm transition-colors"
                        style="color: var(--color-text);">
                        {{ $faq['q'] }}
                        <svg class="w-4 h-4 shrink-0 transition-transform" :class="active === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === {{ $i }}" x-collapse>
                        <div class="px-6 pb-4 text-sm" style="color: var(--color-text-muted);">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
