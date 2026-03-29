@extends('layouts.app')

@section('title', 'دليل بوت تيليجرام - ' . config('app.name'))

@section('content')
<div class="min-h-screen py-12 px-4">
    <div class="mx-auto max-w-5xl space-y-8">
        <div class="glass-card rounded-[2rem] p-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-500/20 bg-cyan-500/10 px-3 py-1 text-sm font-bold text-cyan-300">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                بوت تيليجرام
            </div>
            <h1 class="mt-5 text-4xl font-extrabold text-white">دليل استخدام بوت تيليجرام</h1>
            <p class="mt-3 max-w-3xl text-base leading-8 text-slate-300">
                اربط حسابك بالبوت لتصلك الأسئلة اليومية، وتراجع تقدمك، وتعرف ترتيبك داخل المنصة مباشرة من تيليجرام.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="glass-card p-8 lg:col-span-2">
                <h2 class="text-2xl font-bold text-white">طريقة الربط الصحيحة</h2>
                <div class="mt-6 space-y-5">
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                        <div class="text-sm font-bold text-cyan-300">1. افتح البوت</div>
                        <p class="mt-2 text-sm leading-7 text-slate-300">
                            افتح بوت تيليجرام الرسمي من الزر الموجود أسفل الصفحة أو من خلال اسم البوت:
                            <span class="font-semibold text-white">@{{ config('services.telegram.bot_username', 'SimpleEnglishBot') }}</span>
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                        <div class="text-sm font-bold text-cyan-300">2. اضغط Start</div>
                        <p class="mt-2 text-sm leading-7 text-slate-300">
                            بعد فتح البوت اضغط <span class="font-semibold text-white">Start</span> أو أرسل الأمر <span class="font-semibold text-white">/start</span>.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                        <div class="text-sm font-bold text-cyan-300">3. أرسل رقم هاتفك مع كود الدولة</div>
                        <p class="mt-2 text-sm leading-7 text-slate-300">
                            أرسل نفس الرقم المسجل في المنصة، ويجب أن يكون مكتوبًا مع كود الدولة.
                        </p>
                        <div class="mt-3 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-sm text-amber-100">
                            أمثلة صحيحة:
                            <div class="mt-2 font-mono text-white">+9665XXXXXXXX</div>
                            <div class="font-mono text-white">+2010XXXXXXX</div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                        <div class="text-sm font-bold text-cyan-300">4. ابدأ التفاعل</div>
                        <p class="mt-2 text-sm leading-7 text-slate-300">
                            بعد نجاح الربط سيبدأ البوت في إرسال التنبيهات والأسئلة اليومية، وستتمكن من استخدام الأوامر التعليمية مباشرة.
                        </p>
                    </div>
                </div>

                @if(!auth()->user()->is_telegram_linked)
                    <div class="mt-8">
                        <a href="https://t.me/{{ config('services.telegram.bot_username', 'YourBot') }}?start=1" target="_blank"
                           class="inline-flex items-center gap-3 rounded-xl bg-[#0088cc] px-6 py-3 text-sm font-bold text-white hover:bg-[#0077b5]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/></svg>
                            افتح بوت تيليجرام
                        </a>
                    </div>
                @endif
            </div>

            <div class="glass-card p-8">
                <h2 class="text-2xl font-bold text-white">أهم الأوامر</h2>
                <div class="mt-6 space-y-4 text-sm text-slate-300">
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/start</div>
                        <p class="mt-2">بدء المحادثة وربط الحساب.</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/today</div>
                        <p class="mt-2">بدء أسئلة اليوم أو إعادة استدعائها إذا كانت جاهزة.</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/status</div>
                        <p class="mt-2">عرض الدورات الحالية، التقدم، النقاط، والترتيب.</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/courses</div>
                        <p class="mt-2">عرض الدورات المسجلة ونسبة التقدم في كل دورة.</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/leaderboard</div>
                        <p class="mt-2">عرض المتصدرين ومعرفة ترتيبك الحالي.</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-slate-900/40 p-4">
                        <div class="font-mono font-bold text-cyan-300">/remind</div>
                        <p class="mt-2">تشغيل أو إيقاف تذكيرات تيليجرام اليومية.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
