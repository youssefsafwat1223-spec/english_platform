@extends('layouts.app')

@section('title', __('About the Platform') . ' - ' . config('app.name'))

@section('content')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

<section class="relative py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">
        <div class="glass-card rounded-[2rem] p-8 md:p-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 font-bold text-sm mb-6">
                <span>{{ $isArabic ? 'البرنامج' : 'The Program' }}</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 leading-tight">
                {{ $isArabic ? 'تأسيس عملي للإنجليزية' : 'A practical English foundation program' }}
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500">{{ $isArabic ? 'بنتيجة واضحة' : 'with a clear outcome' }}</span>
            </h1>

            <p class="text-base md:text-lg leading-8 text-slate-600 dark:text-slate-300 max-w-4xl">
                {{ $isArabic ? 'هدفنا أن تنتقل من الفهم إلى الاستخدام: تبني الجملة، تقلل الترجمة الذهنية، وتتحدث بثقة عبر ممارسة منتظمة وتغذية راجعة.' : 'We help you move from understanding to use: build sentences, reduce mental translation, and speak with confidence through consistent practice and feedback.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'كيف تتعلم داخل المنصة' : 'How you learn inside the platform' }}</h2>
                <ol class="space-y-4 text-sm leading-7 text-slate-600 dark:text-slate-300 list-decimal pr-5">
                    <li>{{ $isArabic ? 'تشاهد الدرس بتركيز وتفهم القاعدة.' : 'Watch the lesson and understand the rule.' }}</li>
                    <li>{{ $isArabic ? 'تطبّق مباشرة على تمارين قصيرة.' : 'Apply immediately with short exercises.' }}</li>
                    <li>{{ $isArabic ? 'تختبر نفسك لتعرّف نقاط الضعف.' : 'Test yourself to reveal weak points.' }}</li>
                    <li>{{ $isArabic ? 'تتمرّن على المهارات مع نطق وتطبيق عملي.' : 'Practice the skills with pronunciation and real usage.' }}</li>
                </ol>
            </div>

            <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="150">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'لمن هذا البرنامج؟' : 'Who this program is for' }}</h2>
                <ul class="space-y-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                    <li>{{ $isArabic ? 'للمبتدئ الذي يريد أساسًا واضحًا.' : 'Beginners who want a clear foundation.' }}</li>
                    <li>{{ $isArabic ? 'لمن يفهم الإنجليزية لكنه لا يتحدث بثقة.' : 'Those who understand but do not speak confidently.' }}</li>
                    <li>{{ $isArabic ? 'لمن يريد خطة وممارسة منتظمة.' : 'Anyone seeking structure and consistent practice.' }}</li>
                    <li>{{ $isArabic ? 'غير مناسب لمن يريد نتيجة بلا التزام.' : 'Not for instant results without commitment.' }}</li>
                </ul>
            </div>
        </div>

        <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-4">{{ $isArabic ? 'ما الذي يشمله البرنامج؟' : 'What the program includes' }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-300">
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'منهج تأسيسي واضح.' : 'Clear foundation curriculum.' }}</div>
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'تدريب نطق وتطبيقات عملية.' : 'Pronunciation and real practice.' }}</div>
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'اختبارات قصيرة لقياس التقدم.' : 'Short quizzes to track progress.' }}</div>
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'جلسات مباشرة دورية للمراجعة.' : 'Periodic live sessions for review.' }}</div>
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'شهادة قابلة للتحقق بعد الإكمال.' : 'Verified certificate after completion.' }}</div>
                <div class="flex items-start gap-3"><span class="text-emerald-500">✓</span>{{ $isArabic ? 'مجتمع داعم وأسئلة ونقاش.' : 'Supportive community and Q&A.' }}</div>
            </div>
        </div>

        <div class="glass-card rounded-[2rem] p-8 md:p-12 text-center border border-white/10 shadow-2xl" data-aos="fade-up" data-aos-delay="250">
            <h2 class="text-2xl md:text-4xl font-black mb-4" style="color: var(--color-text);">
                {{ $isArabic ? 'جاهز تبدأ التأسيس الصحيح؟' : 'Ready to start with the right foundation?' }}
            </h2>
            <p class="text-base md:text-lg max-w-3xl mx-auto mb-6" style="color: var(--color-text-muted);">
                {{ $isArabic ? 'اطّلع على الأسعار وخيارات التقسيط إن كانت متاحة، ثم اختر المسار المناسب لك.' : 'See pricing and installment options (if available), then pick the right path for you.' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('pricing') }}" class="btn-primary ripple-btn px-8 py-4 rounded-2xl shadow-lg shadow-primary-500/25 inline-flex items-center gap-2 font-black">
                    {{ $isArabic ? 'اطّلع على الأسعار' : 'View pricing' }}
                </a>
                <a href="{{ route('home') }}#featured-courses" class="px-8 py-4 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors inline-flex items-center gap-2 font-black" style="color: var(--color-text);">
                    {{ $isArabic ? 'اختر الكورس المناسب' : 'Choose your course' }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
