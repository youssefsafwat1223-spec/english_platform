@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', $isArabic ? 'ساحة الباتل' : 'Battle Arena')

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="student-container max-w-6xl relative z-10">
        <x-student.page-header
            title="{{ $isArabic ? 'ساحة الباتل' : 'Battle Arena' }}"
            subtitle="{{ $isArabic ? 'ادخل تحديًا مباشرًا مع طلاب من نفس الكورس، جاوب بسرعة، واجمع النقاط لفريقك.' : 'Join a live battle with students from the same course, answer quickly, and score points for your team.' }}"
            badge="{{ $isArabic ? 'الوضع الجماعي' : 'Team mode' }}"
            badgeColor="primary"
            mb="mb-12"
        />

        @if($activeRoom)
            <x-student.card padding="p-6" class="mb-8 border-l-4 border-l-amber-500" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white">
                            {{ $isArabic ? 'لديك باتل نشط الآن' : 'You already have an active battle' }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
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
            </x-student.card>
        @endif

        @if($enrolledCourses->isEmpty())
            <x-student.empty-state
                title="{{ $isArabic ? 'لا توجد كورسات جاهزة للباتل الآن' : 'No battle-ready courses yet' }}"
                message="{{ $isArabic
                    ? 'يجب أن تكون مشتركًا في كورس يحتوي على عدد كافٍ من الأسئلة حتى يبدأ الباتل.'
                    : 'You must be enrolled in a course with enough questions before a battle can start.' }}"
                data-aos="fade-up"
            >
                <x-slot name="icon">
                    <svg class="h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0 0 10 9.868v4.264a1 1 0 0 0 1.555.832l3.197-2.132a1 1 0 0 0 0-1.664Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h.01M10 12h.01M14 12h.01M18 12h.01"/>
                    </svg>
                </x-slot>
                <x-slot name="actions">
                    <a href="{{ route('student.courses.index') }}" class="btn-primary">
                        {{ $isArabic ? 'عرض كورساتي' : 'Browse my courses' }}
                    </a>
                </x-slot>
            </x-student.empty-state>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrolledCourses as $course)
                    <x-student.card padding="p-0" class="overflow-hidden group hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="relative h-40 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-amber-500/20"></div>
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
                            <div class="flex items-center justify-between mb-4 text-sm text-slate-500 dark:text-slate-400">
                                <div>{{ $isArabic ? 'نظام فرق' : 'Team battle' }}</div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4 text-xs text-slate-600 dark:text-slate-400">
                                <span class="px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-800">
                                    {{ $isArabic ? 'زمن محدود لكل سؤال' : 'Timed questions' }}
                                </span>
                                <span class="px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-800">
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
                    </x-student.card>
                @endforeach
            </div>
        @endif

        <x-student.card padding="p-8" class="mt-16" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-6 text-center text-slate-900 dark:text-white">
                {{ $isArabic ? 'كيف يعمل الباتل' : 'How battle works' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-inner shadow-white/20">1</div>
                    <h4 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $isArabic ? 'ادخل الغرفة' : 'Join the room' }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $isArabic ? 'اختر الكورس وانتظر اكتمال اللوبي.' : 'Pick your course and wait for the lobby to fill.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-inner shadow-white/20">2</div>
                    <h4 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $isArabic ? 'تقسيم الفرق' : 'Split teams' }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $isArabic ? 'السيستم يوزع اللاعبين على فريقين تلقائيًا.' : 'The system splits players into two teams automatically.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-inner shadow-white/20">3</div>
                    <h4 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $isArabic ? 'جاوب بسرعة' : 'Answer fast' }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $isArabic ? 'كل إجابة صحيحة تضيف نقاطًا لك ولفريقك.' : 'Each correct answer gives points to you and your team.' }}
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-inner shadow-white/20">4</div>
                    <h4 class="font-bold mb-1 text-slate-900 dark:text-white">{{ $isArabic ? 'احسم النتيجة' : 'Win the match' }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $isArabic ? 'الفريق الأعلى نقاطًا يفوز في نهاية الجولات.' : 'The team with the most points wins at the end of the rounds.' }}
                    </p>
                </div>
            </div>
        </x-student.card>
    </div>
</div>
@endsection






