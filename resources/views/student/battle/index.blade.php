@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', $isArabic ? 'ساحة الباتل' : 'Battle Arena')

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-12" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>{{ $isArabic ? 'الوضع الجماعي' : 'Team mode' }}</span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ $isArabic ? 'ساحة الباتل' : 'Battle Arena' }}
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ $isArabic
                            ? 'ادخل تحديًا مباشرًا مع طلاب من نفس الكورس، جاوب بسرعة، واجمع النقاط لفريقك.'
                            : 'Join a live battle with students from the same course, answer quickly, and score points for your team.' }}
                    </p>
                </div>
            </div>
        </div>

        @if($activeRoom)
            <div class="glass-card p-6 mb-8 border-l-4 border-amber-500" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg" style="color: var(--color-text);">
                            {{ $isArabic ? 'لديك باتل نشط الآن' : 'You already have an active battle' }}
                        </h3>
                        <p class="text-sm" style="color: var(--color-text-muted);">
                            {{ $activeRoom->course->title }}
                            -
                            {{ $activeRoom->status === 'playing'
                                ? ($isArabic ? 'جارٍ اللعب' : 'Playing')
                                : ($isArabic ? 'في اللوبي' : 'In lobby') }}
                        </p>
                    </div>

                    <a href="{{ $activeRoom->status === 'playing' ? route('student.battle.play', $activeRoom) : route('student.battle.lobby', $activeRoom) }}"
                       class="btn-primary btn-lg">
                        {{ $activeRoom->status === 'playing'
                            ? ($isArabic ? 'العودة إلى الباتل' : 'Return to battle')
                            : ($isArabic ? 'العودة إلى اللوبي' : 'Return to lobby') }}
                    </a>
                </div>
            </div>
        @endif

        @if($enrolledCourses->isEmpty())
            <div class="glass-card p-12 text-center" data-aos="fade-up">
                <h3 class="text-xl font-bold mb-2" style="color: var(--color-text);">
                    {{ $isArabic ? 'لا توجد كورسات جاهزة للباتل الآن' : 'No battle-ready courses yet' }}
                </h3>
                <p class="mb-6" style="color: var(--color-text-muted);">
                    {{ $isArabic
                        ? 'يجب أن تكون مشتركًا في كورس يحتوي على عدد كافٍ من الأسئلة حتى يبدأ الباتل.'
                        : 'You must be enrolled in a course with enough questions before a battle can start.' }}
                </p>
                <a href="{{ route('student.courses.index') }}" class="btn-primary">
                    {{ $isArabic ? 'عرض كورساتي' : 'Browse my courses' }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrolledCourses as $course)
                    <div class="glass-card overflow-hidden group hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="relative h-40 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-500/30 to-blue-500/30"></div>
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-black text-white" style="background: linear-gradient(135deg, #ef4444, #3b82f6);">
                                    VS
                                </div>
                            @endif

                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h3 class="text-white font-bold text-lg">{{ $course->title }}</h3>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4 text-sm" style="color: var(--color-text-muted);">
                                <div>{{ $course->questions_count }} {{ $isArabic ? 'سؤال' : 'questions' }}</div>
                                <div>{{ $isArabic ? 'نظام فرق' : 'Team battle' }}</div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4 text-xs" style="color: var(--color-text-muted);">
                                <span class="px-2 py-1 rounded-full" style="background: var(--color-border);">
                                    {{ $isArabic ? 'زمن محدود لكل سؤال' : 'Timed questions' }}
                                </span>
                                <span class="px-2 py-1 rounded-full" style="background: var(--color-border);">
                                    {{ $isArabic ? 'أسئلة عشوائية' : 'Random rounds' }}
                                </span>
                            </div>

                            <form action="{{ route('student.battle.join', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full btn-primary py-3 text-center font-bold group-hover:shadow-lg transition-shadow"
                                        @if($activeRoom) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                    {{ $isArabic ? 'ابدأ الباتل' : 'Join battle' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-16 glass-card p-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-6 text-center" style="color: var(--color-text);">
                {{ $isArabic ? 'كيف يعمل الباتل' : 'How battle works' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">1</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ $isArabic ? 'ادخل الغرفة' : 'Join the room' }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'اختر الكورس وانتظر اكتمال اللوبي.' : 'Pick your course and wait for the lobby to fill.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">2</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ $isArabic ? 'تقسيم الفرق' : 'Split teams' }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'السيستم يوزع اللاعبين على فريقين تلقائيًا.' : 'The system splits players into two teams automatically.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">3</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ $isArabic ? 'جاوب بسرعة' : 'Answer fast' }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'كل إجابة صحيحة تضيف نقاطًا لك ولفريقك.' : 'Each correct answer gives points to you and your team.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">4</div>
                    <h4 class="font-bold mb-1" style="color: var(--color-text);">{{ $isArabic ? 'احسم النتيجة' : 'Win the match' }}</h4>
                    <p class="text-sm" style="color: var(--color-text-muted);">
                        {{ $isArabic ? 'الفريق الأعلى نقاطًا يفوز في نهاية الجولات.' : 'The team with the most points wins at the end of the rounds.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
