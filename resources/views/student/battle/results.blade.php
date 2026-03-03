@extends('layouts.app')

@section('title', '🏆 Battle Results — ' . $room->course->title)

@section('content')
<style>
    .confetti { position: fixed; top: -10px; z-index: 100; pointer-events: none; animation: confettiFall linear forwards; }
    @keyframes confettiFall { to { top: 110vh; transform: rotate(720deg); } }
    .winner-glow { animation: winnerGlow 2s ease-in-out infinite; }
    @keyframes winnerGlow { 0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); } 50% { box-shadow: 0 0 40px rgba(245, 158, 11, 0.6); } }
    .score-count { animation: countUp 1s ease-out; }
    @keyframes countUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Result Banner --}}
        <div class="text-center mb-10" data-aos="zoom-in">
            @php
                $isWinner = $participant->team === $room->winner_team;
                $isDraw = $room->winner_team === 'draw';
            @endphp

            @if($isDraw)
                <span class="text-8xl block mb-4">🤝</span>
                <h1 class="text-5xl font-extrabold mb-3" style="color: var(--color-text);">{{ __("It's a Draw!") }}</h1>
                <p class="text-xl" style="color: var(--color-text-muted);">{{ __('Both teams fought equally hard!') }}</p>
            @elseif($isWinner)
                <span class="text-8xl block mb-4">🏆</span>
                <h1 class="text-5xl font-extrabold text-gradient mb-3">{{ __('You Won!') }}</h1>
                <p class="text-xl" style="color: var(--color-text-muted);">{{ __('Your team dominated the battlefield!') }} 🎉</p>
            @else
                <span class="text-8xl block mb-4">😔</span>
                <h1 class="text-5xl font-extrabold mb-3" style="color: var(--color-text);">{{ __('You Lost') }}</h1>
                <p class="text-xl" style="color: var(--color-text-muted);">{{ __('Better luck next time!') }}</p>
            @endif
        </div>

        {{-- Final Score --}}
        <div class="glass-card p-8 mb-8 {{ ($isWinner && !$isDraw) ? 'winner-glow' : '' }}" data-aos="fade-up">
            <div class="flex items-center justify-center gap-8">
                {{-- Team A --}}
                <div class="text-center flex-1">
                    <div class="text-sm font-bold uppercase tracking-widest mb-2 text-red-500">{{ $room->team_a_name }}</div>
                    <div class="text-5xl font-extrabold text-red-500 score-count">{{ $room->team_a_score }}</div>
                    @if($room->winner_team === 'a')
                        <span class="inline-block mt-2 text-2xl">🏆</span>
                    @endif
                </div>

                {{-- VS --}}
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-red-500 to-blue-500 flex items-center justify-center text-white font-black text-lg">
                        VS
                    </div>
                </div>

                {{-- Team B --}}
                <div class="text-center flex-1">
                    <div class="text-sm font-bold uppercase tracking-widest mb-2 text-blue-500">{{ $room->team_b_name }}</div>
                    <div class="text-5xl font-extrabold text-blue-500 score-count">{{ $room->team_b_score }}</div>
                    @if($room->winner_team === 'b')
                        <span class="inline-block mt-2 text-2xl">🏆</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Your Stats --}}
        <div class="glass-card p-6 mb-8" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-4" style="color: var(--color-text);">📊 {{ __('Your Performance') }}</h2>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-3xl font-extrabold text-primary-500">{{ $participant->individual_score }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ __('Points Scored') }}</div>
                </div>
                <div>
                    @php
                        $myCorrect = \App\Models\BattleAnswer::where('battle_participant_id', $participant->id)->where('is_correct', true)->count();
                        $myTotal   = \App\Models\BattleAnswer::where('battle_participant_id', $participant->id)->count();
                    @endphp
                    <div class="text-3xl font-extrabold text-emerald-500">{{ $myCorrect }}/{{ $myTotal }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ __('Correct Answers') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold text-amber-500">+{{ $participant->individual_score }}</div>
                    <div class="text-sm" style="color: var(--color-text-muted);">{{ __('Points Earned') }}</div>
                </div>
            </div>
        </div>

        {{-- Team Breakdown --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Team A --}}
            <div class="glass-card p-6 {{ $room->winner_team === 'a' ? 'ring-2 ring-amber-500' : '' }}" data-aos="fade-right">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🔴</span>
                    <h3 class="text-lg font-bold text-red-500">{{ $room->team_a_name }}</h3>
                    @if($room->winner_team === 'a')
                        <span class="ml-auto text-amber-500">🏆 {{ __('Winner') }}</span>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($teamAPlayers as $p)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b' : '' }}" style="border-color: var(--color-border);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 font-bold text-sm">
                                {{ strtoupper(substr($p->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-sm" style="color: var(--color-text);">
                                {{ $p->user->name }}
                                @if($p->user_id === auth()->id())
                                    <span class="text-primary-500">{{ __('(You)') }}</span>
                                @endif
                            </span>
                        </div>
                        <span class="font-bold text-sm text-red-500">{{ $p->individual_score }} {{ __('pts') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Team B --}}
            <div class="glass-card p-6 {{ $room->winner_team === 'b' ? 'ring-2 ring-amber-500' : '' }}" data-aos="fade-left">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🔵</span>
                    <h3 class="text-lg font-bold text-blue-500">{{ $room->team_b_name }}</h3>
                    @if($room->winner_team === 'b')
                        <span class="ml-auto text-amber-500">🏆 {{ __('Winner') }}</span>
                    @endif
                </div>
                <div class="space-y-3">
                    @foreach($teamBPlayers as $p)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b' : '' }}" style="border-color: var(--color-border);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-500 font-bold text-sm">
                                {{ strtoupper(substr($p->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-sm" style="color: var(--color-text);">
                                {{ $p->user->name }}
                                @if($p->user_id === auth()->id())
                                    <span class="text-primary-500">{{ __('(You)') }}</span>
                                @endif
                            </span>
                        </div>
                        <span class="font-bold text-sm text-blue-500">{{ $p->individual_score }} {{ __('pts') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="fade-up">
            <a href="{{ route('student.battle.index') }}" class="btn-primary btn-lg">
                ⚔️ {{ __('Play Again') }}
            </a>
            <a href="{{ route('student.dashboard') }}" class="btn-secondary">
                🏠 {{ __('Back to Dashboard') }}
            </a>
        </div>

    </div>
</div>

@if($isWinner && !$isDraw)
<script>
// Confetti celebration 🎉
function createConfetti() {
    const colors = ['#ef4444', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
    for (let i = 0; i < 60; i++) {
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
