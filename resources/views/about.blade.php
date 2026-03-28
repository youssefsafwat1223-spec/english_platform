@extends('layouts.app')

@section('title', 'عن المنصة - ' . config('app.name'))

@section('content')
@php
    $publicPages = [
        ['title' => 'الصفحة الرئيسية', 'description' => 'تعرض نبذة واضحة عن المنصة، المزايا الأساسية، الكورسات المميزة، الفيديوهات التعريفية وآراء الطلاب.', 'url' => route('home'), 'label' => 'فتح الصفحة'],
        ['title' => 'الأسعار', 'description' => 'تشرح أسعار الكورسات وما الذي يحصل عليه الطالب بعد الاشتراك أو الشراء.', 'url' => route('pricing'), 'label' => 'عرض الأسعار'],
        ['title' => 'تواصل معنا', 'description' => 'لإرسال استفسار، شكوى، أو طلب دعم مباشر لفريق المنصة.', 'url' => route('contact'), 'label' => 'فتح الصفحة'],
        ['title' => 'تسجيل الدخول', 'description' => 'لدخول الطالب أو الأدمن إلى حسابه ومتابعة التعلّم أو الإدارة.', 'url' => route('login'), 'label' => 'فتح الصفحة'],
        ['title' => 'إنشاء حساب', 'description' => 'لتسجيل طالب جديد وبدء الرحلة داخل المنصة.', 'url' => route('register'), 'label' => 'فتح الصفحة'],
        ['title' => 'الخصوصية والشروط', 'description' => 'توضح السياسة القانونية وحقوق الاستخدام وحماية بيانات المستخدمين.', 'url' => route('privacy'), 'label' => 'فتح الصفحة'],
    ];

    $studentPages = [
        ['title' => 'لوحة التحكم', 'description' => 'أول صفحة بعد الدخول، وفيها الوصول السريع للكورسات الحالية، المدفوعات المعلقة، الترتيب وروابط أهم الأقسام.'],
        ['title' => 'كل الكورسات', 'description' => 'تعرض الكورسات المتاحة للطالب حتى يختار الكورس المناسب ويفتح صفحته.'],
        ['title' => 'كورساتي', 'description' => 'صفحة تجمع كل الكورسات التي اشترك فيها الطالب مع تقدم كل كورس.'],
        ['title' => 'صفحة الكورس', 'description' => 'تعرض تفاصيل الكورس، مستواه، محتواه، طريقة الدراسة، ومتطلبات الإكمال والشهادة.'],
        ['title' => 'صفحة التعلم', 'description' => 'تعرض وحدات ومحتوى الكورس داخل Accordion منظم، ويختار الطالب منه الدرس الذي يريد فتحه.'],
        ['title' => 'صفحة الدرس', 'description' => 'فيها الفيديو أو المحتوى، الملاحظات، التعليقات، التقدم، والتنقل بين الدروس.'],
        ['title' => 'الاختبارات والمحاولات', 'description' => 'الطالب يبدأ الاختبار، يرسل الإجابات، ثم يراجع النتيجة وتاريخ كل المحاولات السابقة.'],
        ['title' => 'التدريب على النطق', 'description' => 'لتقييم النطق ورفع التسجيلات أو إجراء التقييمات المتعلقة بمهارات التحدث.'],
        ['title' => 'الشهادات', 'description' => 'تعرض شهادات الطالب مع التحميل، الإرسال، والمشاركة والتحقق من صحة الشهادة.'],
        ['title' => 'الملاحظات', 'description' => 'تجمع ملاحظات الطالب من كل الدروس في مكان واحد مع العرض والتعديل والتصدير PDF.'],
        ['title' => 'الإشعارات', 'description' => 'تعرض كل التنبيهات المهمة مثل التقدم، النتائج، التنبيهات الإدارية أو حالات الدفع.'],
        ['title' => 'الملف الشخصي', 'description' => 'لتعديل البيانات، تغيير كلمة المرور، مراجعة الإنجازات وسجل النقاط.'],
    ];

    $communityPages = [
        ['title' => 'المنتدى', 'description' => 'مكان للأسئلة والنقاشات بين الطلاب داخل أقسام وتصنيفات متعددة.'],
        ['title' => 'لوحة الصدارة', 'description' => 'تعرض ترتيب الطلاب بالنقاط وتشجع على المنافسة والاستمرار.'],
        ['title' => 'الألعاب', 'description' => 'جلسات تفاعلية تعليمية مباشرة لزيادة التركيز والمتعة أثناء التعلم.'],
        ['title' => 'الباتل', 'description' => 'غرف منافسة بين الطلاب بأسئلة مرتبطة بالكورسات مع لوبي ونتائج نهائية.'],
        ['title' => 'الإحالات', 'description' => 'لمتابعة دعوات الأصدقاء وآلية الاستفادة من نظام الإحالة.'],
        ['title' => 'رأي الطالب', 'description' => 'صفحة يكتب منها الطالب تجربته أو تقييمه، ثم يراجع قبل ظهوره للجمهور.'],
    ];

    $adminFeatures = [
        'إدارة الكورسات والمستويات والدروس وترتيب المحتوى.',
        'إدارة الاختبارات والأسئلة والمراجعة وتتبع المحاولات.',
        'إدارة الطلاب والاشتراكات والتقدم والمدفوعات والاسترجاعات.',
        'إدارة الشهادات والمنتدى والبلاغات والألعاب والبوت والإعدادات العامة.',
        'إعدادات الأمان، الدفع، تيليجرام، النقاط، والمنافسات.',
    ];

    $botCommands = [
        ['/start', 'بدء المحادثة وربط حساب الطالب برقم الهاتف المسجل على المنصة.'],
        ['/today', 'جلب سؤال أو كويز اليوم مباشرة إذا كان متاحًا.'],
        ['/status', 'عرض الستريك، النقاط، وبعض بيانات التقدم.'],
        ['/courses', 'عرض الكورسات المسجل فيها الطالب مع التقدم العام.'],
        ['/leaderboard', 'عرض أفضل الطلاب وترتيب المستخدم الحالي.'],
        ['/streak', 'عرض عدد أيام الاستمرار الحالية وأطول streak.'],
        ['/certificate', 'عرض الشهادات المتاحة للطالب.'],
        ['/remind', 'تشغيل أو إيقاف التذكيرات الخاصة بالبوت.'],
        ['/unlink', 'فصل حساب تيليجرام من المنصة وإعادة ربطه لاحقًا عند الحاجة.'],
        ['/help', 'عرض كل الأوامر المتاحة داخل البوت.'],
    ];
@endphp

<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-20 dark:opacity-10"></div>
    <div class="absolute top-10 right-10 w-72 h-72 rounded-full bg-primary-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-accent-500/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">
        <div class="glass-card rounded-[2rem] p-8 md:p-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 font-bold text-sm mb-6">
                <span>📘</span>
                <span>عن المنصة</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 leading-tight">
                خريطة كاملة للمنصة
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500">وشرح كل صفحة ودور كل أداة</span>
            </h1>

            <p class="text-base md:text-lg leading-8 text-slate-600 dark:text-slate-300 max-w-4xl">
                الصفحة دي معمولة كمرجع واضح لأي شخص يريد يفهم المنصة من أول نظرة: الطالب يبدأ منين، يشتري إزاي، يتعلم إزاي، الاختبارات والشهادات والملاحظات والإشعارات بتشتغل إزاي، والبوت على تيليجرام دوره إيه بالضبط.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-8">
                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-2xl">🧭</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">رحلة الطالب داخل المنصة</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">من الحساب حتى الشهادة.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">1. إنشاء الحساب والدخول</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">الطالب يسجل حسابًا جديدًا أو يدخل بحسابه الحالي، ثم ينتقل إلى لوحة التحكم أو الكورسات.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">2. اختيار الكورس والدفع</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">الطالب يراجع صفحة الكورس، ثم يدخل صفحة التسجيل والدفع، وبعد نجاح العملية يفتح له الكورس.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">3. التعلم والاختبارات</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">من صفحة التعلم يفتح الدرس، يشاهد المحتوى، يحفظ الملاحظات، ويؤدي الاختبار المطلوب قبل إتمام الدرس إذا كان عليه Quiz.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">4. المتابعة والشهادة</h3>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">التقدم يتحدث تلقائيًا، والإشعارات تُرسل، وعند استيفاء المتطلبات تظهر الشهادة للتحميل والمشاركة.</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="150">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-accent-500/10 text-accent-500 flex items-center justify-center text-2xl">🌐</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">الصفحات العامة</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($publicPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <h3 class="font-bold text-slate-900 dark:text-white">{{ $page['title'] }}</h3>
                                    <a href="{{ $page['url'] }}" class="text-sm font-bold text-primary-500 hover:text-primary-400 whitespace-nowrap">
                                        {{ $page['label'] }}
                                    </a>
                                </div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-2xl">🎓</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">صفحات الطالب الأساسية</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($studentPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ $page['title'] }}</h3>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="250">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl">⚔️</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">التفاعل والمجتمع والمنافسة</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($communityPages as $page)
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ $page['title'] }}</h3>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 text-violet-500 flex items-center justify-center text-2xl">🤖</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">البوت على تيليجرام</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">جزء مكمل للمنصة وليس خدمة منفصلة.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-violet-500/20 bg-violet-500/5 p-5 mb-6">
                        <p class="text-sm leading-8 text-slate-700 dark:text-slate-200">
                            بعد ربط الحساب برقم الهاتف، البوت يقدر يساعد الطالب في المتابعة اليومية: يرسل سؤال اليوم، يعرض التقدم، الكورسات، الترتيب، الستريك، الشهادات، ويشغل التذكيرات أو يوقفها. اسم البوت الحالي:
                            <span class="font-bold text-violet-500">@{{ config('services.telegram.bot_username', 'SimpleEnglishBot') }}</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($botCommands as [$command, $description])
                            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-5">
                                <div class="inline-flex px-3 py-1 rounded-lg bg-violet-500/10 text-violet-500 font-mono font-bold text-sm mb-3">{{ $command }}</div>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('student.telegram.guide') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary-500 hover:text-primary-400">
                            <span>فتح دليل تيليجرام الكامل</span>
                            <span>←</span>
                        </a>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-up" data-aos-delay="350">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center text-2xl">🛠️</div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">لوحة الإدارة</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($adminFeatures as $feature)
                            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4">
                                <span class="mt-1 text-sky-500">•</span>
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="glass-card p-8 sticky-sidebar" data-aos="fade-left" data-aos-delay="180">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">روابط مهمة</h2>

                    <div class="space-y-3">
                        <a href="{{ route('pricing') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>الأسعار</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>الدعم والتواصل</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>إنشاء حساب</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>تسجيل الدخول</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('student.courses.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>الكورسات</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 text-sm font-bold text-slate-800 dark:text-white hover:border-primary-500/40 transition-colors">
                            <span>لوحة التحكم</span>
                            <span class="text-primary-500">↗</span>
                        </a>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-left" data-aos-delay="240">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">ما الذي يميز المنصة؟</h2>
                    <div class="space-y-4 text-sm leading-7 text-slate-600 dark:text-slate-300">
                        <p>المنصة ليست مجرد فيديوهات؛ هي نظام تعلّم متكامل يربط بين المحتوى، الاختبارات، الشهادات، الإشعارات، والمجتمع.</p>
                        <p>الطالب يقدر يتابع حالته من الموقع نفسه ومن تيليجرام، ويعرف أين وصل وماذا ينقصه بشكل واضح.</p>
                        <p>وفي الخلفية توجد لوحة إدارة قوية لإدارة المحتوى والطلاب والمدفوعات والإعدادات والأنشطة التفاعلية.</p>
                    </div>
                </div>

                <div class="glass-card p-8" data-aos="fade-left" data-aos-delay="300">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mb-5">لو أنت طالب جديد</h2>
                    <ol class="space-y-3 text-sm leading-7 text-slate-600 dark:text-slate-300 list-decimal pr-5">
                        <li>أنشئ حسابًا أو سجل دخولك.</li>
                        <li>راجع صفحة الأسعار أو افتح قائمة الكورسات.</li>
                        <li>اختر الكورس المناسب ثم أكمل عملية الشراء.</li>
                        <li>ابدأ من صفحة التعلم ثم افتح الدرس المطلوب.</li>
                        <li>أكمل الاختبارات المطلوبة حتى يتحدث التقدم بشكل صحيح.</li>
                        <li>اربط تيليجرام إذا كنت تريد متابعة يومية أسرع.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
