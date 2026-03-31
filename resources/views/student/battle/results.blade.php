@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $isClosedByPlayerLeaving = $room->winner_team === 'player_left';
    $isCancelled = is_null($room->winner_team);
    $isDraw = $room->winner_team === 'draw';
    $isAbandoned = $isCancelled && !is_null($room->started_at);
    $isWinner = !$isCancelled && !$isDraw && !$isClosedByPlayerLeaving && $participant->team === $room->winner_team;
@endphp

@section('title', $isArabic ? 'نتيجة الباتل' : 'Battle Results')

@section('content')
<style>
    .confetti { position: fixed; top: -10px; z-index: 100; pointer-events: none; animation: confettiFall linear forwards; }
    @keyframes confettiFall { to { top: 110vh; transform: rotate(720deg); } }
    .winner-glow { animation: winnerGlow 2s ease-in-out infinite; }
    @keyframes winnerGlow { 0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); } 50% { box-shadow: 0 0 40px rgba(245, 158, 11, 0.6); } }
</style>

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10" data-aos="zoom-in">
            @if($isCancelled && !$isAbandoned)
                <h1 class="text-4xl md:text-5xl font-extrabold mb-3 text-slate-900 dark:text-white">
                    {{ $isArabic ? 'تم إلغاء الباتل' : 'Battle cancelled' }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic
                        ? 'انتهى وقت اللوبي قبل اكتمال الحد الأدنى من اللاعبين.'
                        : 'The lobby expired before the minimum number of players joined.' }}
                </p>
            @elseif($isAbandoned)
                <h1 class="text-4xl md:text-5xl font-extrabold mb-3 text-slate-900 dark:text-white">
                    {{ $isArabic ? 'تم إغلاق الروم لعدم النشاط' : 'Room closed due to inactivity' }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic
                        ? 'توقفت المباراة لأن اللاعبين لم يكملوا اللعب داخل الوقت المحدد.'
                        : 'The match was closed because the players stopped answering in time.' }}
                </p>
            @elseif($isClosedByPlayerLeaving)
                <h1 class="text-4xl md:text-5xl font-extrabold mb-3 text-slate-900 dark:text-white">
                    {{ $isArabic ? 'تم إنهاء الباتل بخروج لاعب' : 'Battle closed because a player left' }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic
                        ? 'انتهت الجولة الحالية لأن أحد اللاعبين غادر الروم أثناء اللعب.'
                        : 'The current match was closed because one of the players left the room during play.' }}
                </p>
            @elseif($isDraw)
                <h1 class="text-5xl font-extrabold mb-3 text-slate-900 dark:text-white">
                    {{ $isArabic ? 'تعادل' : "It's a draw" }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic ? 'الفريقان أنهيا المباراة بنفس النتيجة.' : 'Both teams finished with the same score.' }}
                </p>
            @elseif($isWinner)
                <h1 class="text-5xl font-extrabold mb-3 text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-500">
                    {{ $isArabic ? 'فريقك فاز' : 'You won' }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic ? 'أداء قوي من فريقك حتى آخر جولة.' : 'Your team stayed ahead until the final round.' }}
                </p>
            @else
                <h1 class="text-5xl font-extrabold mb-3 text-slate-900 dark:text-white">
                    {{ $isArabic ? 'انتهت المباراة' : 'Match finished' }}
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 font-medium">
                    {{ $isArabic ? 'يمكنك المحاولة من جديد في جولة أخرى.' : 'You can try again in another battle.' }}
                </p>
            @endif
        </div>

        <x-student.card padding="p-8" class="mb-8 {{ ($isWinner && !$isDraw) ? 'winner-glow' : '' }}" data-aos="fade-up">
            <div class="flex items-center justify-center gap-8">
                <div class="text-center flex-1">
                    <div class="text-sm font-bold uppercase tracking-widest mb-2 text-red-500">{{ $room->team_a_name }}</div>
                    <div class="text-5xl font-extrabold text-red-500">{{ $room->team_a_score }}</div>
                    @if($room->winner_team === 'a')
                        <span class="inline-block mt-2 text-sm font-bold text-amber-500">
                            {{ $isArabic ? 'الفائز' : 'Winner' }}
                        </span>
                    @endif
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-red-500 to-blue-500 flex items-center justify-center text-white font-black text-lg shadow-inner shadow-white/20">
                        VS
                    </div>
                </div>

                <div class="text-center flex-1">
                    <div class="text-sm font-bold uppercase tracking-widest mb-2 text-blue-500">{{ $room->team_b_name }}</div>
                    <div class="text-5xl font-extrabold text-blue-500">{{ $room->team_b_score }}</div>
                    @if($room->winner_team === 'b')
                        <span class="inline-block mt-2 text-sm font-bold text-amber-500">
                            {{ $isArabic ? 'الفائز' : 'Winner' }}
                        </span>
                    @endif
                </div>
            </div>
        </x-student.card>

        <x-student.card padding="p-6" class="mb-8" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-4 text-slate-900 dark:text-white">
                {{ $isArabic ? 'أداؤك في الباتل' : 'Your performance' }}
            </h2>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-3xl font-extrabold text-primary-500">{{ $participant->individual_score }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                        {{ $isArabic ? 'نقاطك' : 'Your points' }}
                    </div>
                </div>
                <div>
                    @php
                        $myCorrect = \App\Models\BattleAnswer::where('battle_participant_id', $participant->id)->where('is_correct', true)->count();
                        $myTotal = \App\Models\BattleAnswer::where('battle_participant_id', $participant->id)->count();
                    @endphp
                    <div class="text-3xl font-extrabold text-emerald-500">{{ $myCorrect }}/{{ $myTotal }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                        {{ $isArabic ? 'إجابات صحيحة' : 'Correct answers' }}
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-amber-500">+{{ $participant->individual_score }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                        {{ $isArabic ? 'النقاط المضافة' : 'Points earned' }}
                    </div>
                </div>
            </div>
        </x-student.card>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <x-student.card padding="p-6" class="{{ $room->winner_team === 'a' ? 'ring-2 ring-amber-500 shadow-md shadow-amber-500/20' : '' }}" data-aos="fade-right">
                <div class="flex items-center gap-2 mb-4">
                    <h3 class="text-lg font-bold text-red-500">{{ $room->team_a_name }}</h3>
                    @if($room->winner_team === 'a')
                        <span class="ml-auto text-amber-500 text-sm font-bold">{{ $isArabic ? 'الفائز' : 'Winner' }}</span>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($teamAPlayers as $p)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-slate-200/50 dark:border-white/5' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 font-bold text-sm">
                                    {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-sm text-slate-900 dark:text-white">
                                    {{ $p->user->name }}
                                    @if($p->user_id === auth()->id())
                                        <span class="text-primary-500">({{ $isArabic ? 'أنت' : 'You' }})</span>
                                    @endif
                                </span>
                            </div>
                            <span class="font-bold text-sm text-red-500">{{ $p->individual_score }} {{ $isArabic ? 'نقطة' : 'pts' }}</span>
                        </div>
                    @endforeach
                </div>
            </x-student.card>

            <x-student.card padding="p-6" class="{{ $room->winner_team === 'b' ? 'ring-2 ring-amber-500 shadow-md shadow-amber-500/20' : '' }}" data-aos="fade-left">
                <div class="flex items-center gap-2 mb-4">
                    <h3 class="text-lg font-bold text-blue-500">{{ $room->team_b_name }}</h3>
                    @if($room->winner_team === 'b')
                        <span class="ml-auto text-amber-500 text-sm font-bold">{{ $isArabic ? 'الفائز' : 'Winner' }}</span>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($teamBPlayers as $p)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-slate-200/50 dark:border-white/5' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-500 font-bold text-sm">
                                    {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-sm text-slate-900 dark:text-white">
                                    {{ $p->user->name }}
                                    @if($p->user_id === auth()->id())
                                        <span class="text-primary-500">({{ $isArabic ? 'أنت' : 'You' }})</span>
                                    @endif
                                </span>
                            </div>
                            <span class="font-bold text-sm text-blue-500">{{ $p->individual_score }} {{ $isArabic ? 'نقطة' : 'pts' }}</span>
                        </div>
                    @endforeach
                </div>
            </x-student.card>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="fade-up">
            <a href="{{ route('student.battle.index') }}" class="btn-primary btn-lg">
                {{ $isArabic ? 'العودة إلى الباتل' : 'Back to battles' }}
            </a>
            <a href="{{ route('student.dashboard') }}" class="btn-secondary">
                {{ $isArabic ? 'العودة إلى لوحة التحكم' : 'Back to dashboard' }}
            </a>
        </div>
    </div>
</div>

@if($isWinner && !$isDraw)
<script>
function createConfetti() {
    const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
    for (let i = 0; i < 60; i += 1) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.width = (Math.random() * 10 + 5) + 'px';
            confetti.style.height = (Math.random() * 10 + 5) + 'px';
            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
            document.body.appendChild(confetti);
            setTimeout(() => confetti.remove(), 5000);
        }, i * 50);
    }
}

createConfetti();
</script>
@endif
@endsection
