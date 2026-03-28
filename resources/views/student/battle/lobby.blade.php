@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', $isArabic ? 'لوبي الباتل' : 'Battle Lobby')

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-8" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 glass-card px-4 py-2 mb-4 !rounded-full">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <span class="text-sm font-bold uppercase tracking-widest" style="color: var(--color-text-muted);">
                    {{ $isArabic ? 'انتظار اللاعبين' : 'Waiting for players' }}
                </span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2" style="color: var(--color-text);">
                {{ $room->course->title }}
            </h1>
            <p class="text-lg" style="color: var(--color-text-muted);">
                {{ $isArabic ? 'غرفة الباتل' : 'Battle room' }} #{{ $room->id }}
            </p>
        </div>

        <div class="glass-card p-8 mb-8 text-center" data-aos="fade-up">
            <div class="text-sm uppercase tracking-widest mb-2 font-bold" style="color: var(--color-text-muted);">
                {{ $isArabic ? 'بداية المباراة خلال' : 'Match starts in' }}
            </div>
            <div id="lobby-timer" class="text-6xl font-extrabold text-gradient tabular-nums">
                <span id="timer-minutes">02</span>:<span id="timer-seconds">00</span>
            </div>
            <p class="text-sm mt-3" style="color: var(--color-text-muted);">
                {{ $isArabic
                    ? 'إذا اكتمل عدد اللاعبين قبل انتهاء المؤقت سيبدأ الباتل مباشرة.'
                    : 'If the room fills before the timer ends, the battle will start immediately.' }}
            </p>
        </div>

        <div class="glass-card p-6 mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold" style="color: var(--color-text);">
                    {{ $isArabic ? 'اللاعبون' : 'Players' }} (<span id="player-count">{{ $room->playerCount() }}</span>/{{ $room->max_players }})
                </h2>
                <div class="flex gap-1" id="player-dots">
                    @for($i = 0; $i < $room->max_players; $i++)
                        <div class="w-3 h-3 rounded-full transition-all duration-300 {{ $i < $room->playerCount() ? 'bg-emerald-500' : 'bg-gray-600/30' }}"></div>
                    @endfor
                </div>
            </div>

            <div id="players-grid" class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach($room->participants as $p)
                    <div class="glass-card p-3 text-center {{ $p->user_id === auth()->id() ? 'ring-2 ring-primary-500' : '' }}">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold mx-auto mb-2">
                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                        </div>
                        <div class="text-xs font-bold truncate" style="color: var(--color-text);">
                            {{ $p->user->name }}
                            @if($p->user_id === auth()->id())
                                <span class="text-primary-500">({{ $isArabic ? 'أنت' : 'You' }})</span>
                            @endif
                        </div>
                    </div>
                @endforeach

                @for($i = $room->playerCount(); $i < $room->max_players; $i++)
                    <div class="glass-card p-3 text-center opacity-30">
                        <div class="w-10 h-10 rounded-full bg-gray-500/20 flex items-center justify-center mx-auto mb-2">
                            <span class="text-lg">?</span>
                        </div>
                        <div class="text-xs" style="color: var(--color-text-muted);">
                            {{ $isArabic ? 'بانتظار لاعب' : 'Waiting...' }}
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="glass-card p-6 mb-8" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-4" style="color: var(--color-text);">
                {{ $isArabic ? 'قواعد الباتل' : 'Battle rules' }}
            </h2>
            <div class="space-y-3 text-sm" style="color: var(--color-text-muted);">
                <p>{{ $isArabic ? 'يبدأ الباتل تلقائيًا عند اكتمال الغرفة أو انتهاء مؤقت اللوبي.' : 'The battle starts automatically when the room fills or the lobby timer ends.' }}</p>
                <p>{{ $isArabic ? 'كل لاعب يجاوب بنفسه، لكن النقاط تذهب لك وللفريق معًا.' : 'Each player answers individually, but points are added to both the player and the team.' }}</p>
                <p>{{ $isArabic ? 'إذا لم يصل الحد الأدنى من اللاعبين، تُلغى الغرفة تلقائيًا.' : 'If the minimum number of players is not reached, the room is cancelled automatically.' }}</p>
                <p>{{ $isArabic ? 'إذا توقف اللاعبون عن اللعب بعد بدء الروم، تُغلق الغرفة تلقائيًا بسبب عدم النشاط.' : 'If players stop playing after the match starts, the room closes automatically because of inactivity.' }}</p>
            </div>
        </div>

        <div class="text-center" data-aos="fade-up">
            <form action="{{ route('student.battle.leave', $room) }}" method="POST" onsubmit="return confirm(@js($isArabic ? 'هل تريد مغادرة اللوبي؟' : 'Do you want to leave the lobby?'));">
                @csrf
                <button type="submit" class="btn-secondary">
                    {{ $isArabic ? 'مغادرة اللوبي' : 'Leave lobby' }}
                </button>
            </form>
        </div>

        <div id="sorry-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background: rgba(0,0,0,0.7);">
            <div class="glass-card p-10 text-center max-w-md mx-4">
                <h2 class="text-2xl font-bold mb-3" style="color: var(--color-text);">
                    {{ $isArabic ? 'تم إلغاء الباتل' : 'Battle cancelled' }}
                </h2>
                <p id="sorry-modal-message" class="mb-6" style="color: var(--color-text-muted);">
                    {{ $isArabic
                        ? 'لم يكتمل الحد الأدنى من اللاعبين قبل انتهاء وقت اللوبي.'
                        : 'The minimum number of players was not reached before the lobby expired.' }}
                </p>
                <a href="{{ route('student.battle.index') }}" class="btn-primary">
                    {{ $isArabic ? 'العودة إلى ساحة الباتل' : 'Back to battle arena' }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    let lobbyTimeRemaining = {{ $lobbyTimeRemaining }};
    const pollUrl = "{{ route('student.battle.poll', $room) }}";
    const playUrl = "{{ route('student.battle.play', $room) }}";

    const translations = @json([
        'you' => $isArabic ? 'أنت' : 'You',
        'waiting' => $isArabic ? 'بانتظار لاعب' : 'Waiting...',
        'cancelled' => $isArabic
            ? 'لم يكتمل الحد الأدنى من اللاعبين قبل انتهاء وقت اللوبي.'
            : 'The minimum number of players was not reached before the lobby expired.',
    ]);

    const timerMinEl = document.getElementById('timer-minutes');
    const timerSecEl = document.getElementById('timer-seconds');
    const playerCountEl = document.getElementById('player-count');

    function updateTimer() {
        if (lobbyTimeRemaining <= 0) {
            timerMinEl.textContent = '00';
            timerSecEl.textContent = '00';
            return;
        }

        lobbyTimeRemaining -= 1;
        const minutes = Math.floor(lobbyTimeRemaining / 60);
        const seconds = Math.floor(lobbyTimeRemaining % 60);
        timerMinEl.textContent = String(minutes).padStart(2, '0');
        timerSecEl.textContent = String(seconds).padStart(2, '0');
    }

    function showCancellationModal(message) {
        document.getElementById('sorry-modal-message').textContent = message || translations.cancelled;
        document.getElementById('sorry-modal').classList.remove('hidden');
        document.getElementById('sorry-modal').classList.add('flex');
    }

    function updatePlayersGrid(players, maxPlayers) {
        const grid = document.getElementById('players-grid');
        grid.innerHTML = '';

        players.forEach(player => {
            const card = document.createElement('div');
            card.className = `glass-card p-3 text-center ${player.is_me ? 'ring-2 ring-primary-500' : ''}`;
            card.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold mx-auto mb-2">
                    ${player.name.charAt(0).toUpperCase()}
                </div>
                <div class="text-xs font-bold truncate" style="color: var(--color-text);">
                    ${player.name}
                    ${player.is_me ? `<span class="text-primary-500">(${translations.you})</span>` : ''}
                </div>
            `;
            grid.appendChild(card);
        });

        for (let i = players.length; i < maxPlayers; i += 1) {
            const slot = document.createElement('div');
            slot.className = 'glass-card p-3 text-center opacity-30';
            slot.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-gray-500/20 flex items-center justify-center mx-auto mb-2">
                    <span class="text-lg">?</span>
                </div>
                <div class="text-xs" style="color: var(--color-text-muted);">${translations.waiting}</div>
            `;
            grid.appendChild(slot);
        }

        const dotsContainer = document.getElementById('player-dots');
        dotsContainer.innerHTML = '';

        for (let i = 0; i < maxPlayers; i += 1) {
            const dot = document.createElement('div');
            dot.className = `w-3 h-3 rounded-full transition-all duration-300 ${i < players.length ? 'bg-emerald-500' : 'bg-gray-600/30'}`;
            dotsContainer.appendChild(dot);
        }
    }

    function pollRoom() {
        fetch(pollUrl)
            .then(response => response.json())
            .then(data => {
                playerCountEl.textContent = data.player_count;

                if (data.status === 'playing') {
                    window.location.href = playUrl;
                    return;
                }

                if (data.status === 'finished' && !data.winner_team) {
                    showCancellationModal(translations.cancelled);
                    return;
                }

                lobbyTimeRemaining = data.lobby_time_remaining;
                updatePlayersGrid(data.players, data.max_players);
            })
            .catch(error => console.error(error));
    }

    updateTimer();
    setInterval(updateTimer, 1000);
    setInterval(pollRoom, 2000);
})();
</script>
@endsection
