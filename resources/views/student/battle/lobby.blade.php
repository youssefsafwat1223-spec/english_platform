@extends('layouts.app')

@section('title', '⏳ Battle Lobby — ' . $room->course->title)

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-8" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 glass-card px-4 py-2 mb-4 !rounded-full">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <span class="text-sm font-bold uppercase tracking-widest" style="color: var(--color-text-muted);">{{ __('Waiting for Players') }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2" style="color: var(--color-text);">
                {{ $room->course->title }}
            </h1>
            <p class="text-lg" style="color: var(--color-text-muted);">{{ __('Battle Room') }} #{{ $room->id }}</p>
        </div>

        {{-- Lobby Timer --}}
        <div class="glass-card p-8 mb-8 text-center" data-aos="fade-up">
            <div class="text-sm uppercase tracking-widest mb-2 font-bold" style="color: var(--color-text-muted);">{{ __('Game starts in') }}</div>
            <div id="lobby-timer" class="text-6xl font-extrabold text-gradient tabular-nums">
                <span id="timer-minutes">02</span>:<span id="timer-seconds">00</span>
            </div>
            <p class="text-sm mt-3" style="color: var(--color-text-muted);">
                {{ __('Lobby Waiting Message') }}
            </p>
        </div>

        {{-- Players List --}}
        <div class="glass-card p-6 mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold" style="color: var(--color-text);">
                    👥 {{ __('Players') }} (<span id="player-count">{{ $room->playerCount() }}</span>/{{ $room->max_players }})
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
                            <span class="text-primary-500">({{ __('You') }})</span>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Empty slots --}}
                @for($i = $room->playerCount(); $i < $room->max_players; $i++)
                <div class="glass-card p-3 text-center opacity-30">
                    <div class="w-10 h-10 rounded-full bg-gray-500/20 flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg">?</span>
                    </div>
                    <div class="text-xs" style="color: var(--color-text-muted);">{{ __('Waiting...') }}</div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Instructions --}}
        <div class="glass-card p-6 mb-8" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-4" style="color: var(--color-text);">📋 {{ __('Battle Rules') }}</h2>
            <div class="space-y-3 text-sm" style="color: var(--color-text-muted);">
                <div class="flex items-start gap-3">
                    <span class="text-lg">👥</span>
                    <p>{!! __('Rule 1') !!}</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">❓</span>
                    <p>{{ __('Rule 2') }}</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">⏱️</span>
                    <p>{{ __('Rule 3') }}</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">✅</span>
                    <p>{{ __('Rule 4') }}</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">🏆</span>
                    <p>{{ __('Rule 5') }}</p>
                </div>
            </div>
        </div>

        {{-- Leave Button --}}
        <div class="text-center" data-aos="fade-up">
            <form action="{{ route('student.battle.leave', $room) }}" method="POST" onsubmit="return confirm(__('Leave Lobby Confirm'))">
                @csrf
                <button type="submit" class="btn-secondary">
                    {{ __('Leave Lobby') }}
                </button>
            </form>
        </div>

        {{-- Sorry Modal (shown if cancelled) --}}
        <div id="sorry-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background: rgba(0,0,0,0.7);">
            <div class="glass-card p-10 text-center max-w-md mx-4">
                <span class="text-6xl block mb-4">😔</span>
                <h2 class="text-2xl font-bold mb-3" style="color: var(--color-text);">{{ __('Not Enough Players Title') }}</h2>
                <p class="mb-6" style="color: var(--color-text-muted);">{{ __('Not Enough Players Message') }}</p>
                <a href="{{ route('student.battle.index') }}" class="btn-primary">{{ __('Back to Battle Arena') }}</a>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    const roomId = {{ $room->id }};
    let lobbyTimeRemaining = {{ $lobbyTimeRemaining }};
    const pollUrl = "{{ route('student.battle.poll', $room) }}";
    const playUrl = "{{ route('student.battle.play', $room) }}";
    const timerMinEl = document.getElementById('timer-minutes');
    const timerSecEl = document.getElementById('timer-seconds');
    const playerCountEl = document.getElementById('player-count');

    // Countdown timer
    function updateTimer() {
        if (lobbyTimeRemaining <= 0) {
            timerMinEl.textContent = '00';
            timerSecEl.textContent = '00';
            return;
        }
        lobbyTimeRemaining--; // Decrement abstractly
        
        // Calculate minutes and seconds
        const m = Math.floor(lobbyTimeRemaining / 60);
        const s = Math.floor(lobbyTimeRemaining % 60);
        
        timerMinEl.textContent = String(m).padStart(2, '0');
        timerSecEl.textContent = String(s).padStart(2, '0');
    }

    setInterval(updateTimer, 1000);

    // Poll every 2 seconds
    function pollRoom() {
        fetch(pollUrl)
            .then(r => r.json())
            .then(data => {
                playerCountEl.textContent = data.player_count;

                if (data.status === 'playing') {
                    window.location.href = playUrl;
                    return;
                }

                if (data.status === 'finished' && !data.winner_team) {
                    // Game cancelled — not enough players
                    document.getElementById('sorry-modal').classList.remove('hidden');
                    document.getElementById('sorry-modal').classList.add('flex');
                    return;
                }

                lobbyTimeRemaining = data.lobby_time_remaining;

                // Update players grid
                updatePlayersGrid(data.players, data.max_players);
            })
            .catch(console.error);
    }

    function updatePlayersGrid(players, maxPlayers) {
        const grid = document.getElementById('players-grid');
        grid.innerHTML = ''; // Clear current grid

        // Render joined players
        players.forEach(p => {
            const isMe = p.is_me;
            const initial = p.name.charAt(0).toUpperCase();
            
            const card = document.createElement('div');
            card.className = `glass-card p-3 text-center ${isMe ? 'ring-2 ring-primary-500' : ''}`;
            
            card.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold mx-auto mb-2">
                    ${initial}
                </div>
                <div class="text-xs font-bold truncate" style="color: var(--color-text);">
                    ${p.name}
                    ${isMe ? '<span class="text-primary-500">(You)</span>' : ''}
                </div>
            `;
            grid.appendChild(card);
        });

        // Render empty slots
        for (let i = players.length; i < maxPlayers; i++) {
            const slot = document.createElement('div');
            slot.className = 'glass-card p-3 text-center opacity-30';
            slot.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-gray-500/20 flex items-center justify-center mx-auto mb-2">
                    <span class="text-lg">?</span>
                </div>
                <div class="text-xs" style="color: var(--color-text-muted);">Waiting...</div>
            `;
            grid.appendChild(slot);
        }
        
        // Update dots
        const dotsContainer = document.getElementById('player-dots');
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < maxPlayers; i++) {
                const dot = document.createElement('div');
                dot.className = `w-3 h-3 rounded-full transition-all duration-300 ${i < players.length ? 'bg-emerald-500' : 'bg-gray-600/30'}`;
                dotsContainer.appendChild(dot);
            }
        }
    }

    setInterval(pollRoom, 2000);
})();
</script>
@endsection
