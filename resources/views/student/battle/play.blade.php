@extends('layouts.app')

@section('title', '⚔️ Battle — ' . $room->course->title)

@section('content')
<style>
    .option-btn { transition: all 0.3s; cursor: pointer; }
    .option-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
    .option-btn:disabled { cursor: not-allowed; opacity: 0.6; }
    .option-btn.correct { background: rgba(16, 185, 129, 0.2) !important; border-color: #10b981 !important; box-shadow: 0 0 20px rgba(16,185,129,0.3); }
    .option-btn.wrong   { background: rgba(239, 68, 68, 0.2) !important; border-color: #ef4444 !important; box-shadow: 0 0 20px rgba(239,68,68,0.3); }
    .score-bar { transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .pulse-score { animation: pulseScore 0.5s ease; }
    @keyframes pulseScore { 0%{transform:scale(1)} 50%{transform:scale(1.3)} 100%{transform:scale(1)} }
    .question-enter { animation: slideIn 0.5s ease; }
    @keyframes slideIn { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
</style>

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Top Bar: Scores --}}
        <div class="glass-card p-4 mb-6">
            <div class="flex items-center gap-4">
                {{-- Team A Score --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-red-500" id="team-a-name">🔴 {{ $room->team_a_name }}</span>
                        <span class="text-lg font-extrabold text-red-500" id="team-a-score">{{ $room->team_a_score }}</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-gray-700/30 overflow-hidden">
                        <div id="team-a-bar" class="h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full score-bar" style="width: 50%;"></div>
                    </div>
                </div>

                {{-- VS --}}
                <div class="text-2xl font-black" style="color: var(--color-text-muted);">VS</div>

                {{-- Team B Score --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-blue-500" id="team-b-name">🔵 {{ $room->team_b_name }}</span>
                        <span class="text-lg font-extrabold text-blue-500" id="team-b-score">{{ $room->team_b_score }}</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-gray-700/30 overflow-hidden">
                        <div id="team-b-bar" class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full score-bar" style="width: 50%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- My Team Badge --}}
        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white {{ $participant->team === 'a' ? 'bg-red-500' : 'bg-blue-500' }}">
                {{ $participant->team === 'a' ? '🔴 ' . $room->team_a_name : '🔵 ' . $room->team_b_name }}
                — {{ __('Your Team') }}
            </span>
        </div>

        {{-- Question Card --}}
        <div id="question-card" class="glass-card p-8 mb-6 question-enter">
            {{-- Question Header --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold px-3 py-1 rounded-full" style="background: var(--color-border); color: var(--color-text-muted);">
                        {{ __('Question') }} <span id="q-number">-</span>/<span id="q-total">-</span>
                    </span>
                    <span class="text-sm font-bold px-3 py-1 rounded-full bg-amber-500/20 text-amber-500">
                        🏆 <span id="q-points">-</span> {{ __('pts') }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm" style="color: var(--color-text-muted);">⏱️</span>
                    <span id="q-timer" class="text-2xl font-extrabold tabular-nums text-gradient">--</span>
                </div>
            </div>

            {{-- Timer Bar --}}
            <div class="w-full h-1.5 rounded-full bg-gray-700/30 mb-6 overflow-hidden">
                <div id="timer-bar" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-1000 ease-linear" style="width: 100%;"></div>
            </div>

            {{-- Question Text --}}
            <h2 id="q-text" class="text-xl sm:text-2xl font-bold mb-8 leading-relaxed" style="color: var(--color-text);">
                {{ __('Loading question...') }}
            </h2>

            {{-- Options --}}
            <div id="options-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button id="opt-a" disabled class="option-btn glass-card p-4 text-left flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-500/20 flex items-center justify-center text-primary-500 font-bold flex-shrink-0">A</span>
                    <span id="opt-a-text" class="font-medium" style="color: var(--color-text);">—</span>
                </button>
                <button id="opt-b" disabled class="option-btn glass-card p-4 text-left flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-accent-500/20 flex items-center justify-center text-accent-500 font-bold flex-shrink-0">B</span>
                    <span id="opt-b-text" class="font-medium" style="color: var(--color-text);">—</span>
                </button>
                <button id="opt-c" disabled class="option-btn glass-card p-4 text-left flex items-center gap-3" style="display: none;">
                    <span class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-500 font-bold flex-shrink-0">C</span>
                    <span id="opt-c-text" class="font-medium" style="color: var(--color-text);">—</span>
                </button>
                <button id="opt-d" disabled class="option-btn glass-card p-4 text-left flex items-center gap-3" style="display: none;">
                    <span class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-500 font-bold flex-shrink-0">D</span>
                    <span id="opt-d-text" class="font-medium" style="color: var(--color-text);">—</span>
                </button>
            </div>
        </div>

        {{-- Answer Result Feedback --}}
        <div id="answered-status" class="hidden glass-card p-4 mb-6 text-center">
            <span id="answer-result-icon" class="text-4xl block mb-2"></span>
            <span id="answer-result-text" class="text-lg font-bold" style="color: var(--color-text);"></span>
        </div>

        {{-- Progress --}}
        <div class="text-center text-sm font-medium" style="color: var(--color-text-muted);">
            {{ __('Team Answers') }}: <span id="answers-count">0</span>/<span id="answers-total">0</span>
        </div>

        <div id="player-indicators" class="flex justify-center gap-2 mt-4 flex-wrap"></div>
    </div>
</div>

<script>
(function() {
    // ==================== CONFIG ====================
    const pollUrl    = "{{ route('student.battle.poll', $room) }}";
    const answerUrl  = "{{ route('student.battle.answer', $room) }}";
    const resultsUrl = "{{ route('student.battle.results', $room) }}";
    const csrfToken  = "{{ csrf_token() }}";
    const questionTimerTotal = {{ $room->question_timer_seconds }};

    const trans = {
        correct: "{{ __('Correct!') }}",
        wrong:   "{{ __('Wrong answer!') }}",
        pts:     "{{ __('pts') }}",
        error:   "{{ __('Error occurred') }}",
        you:     "{{ __('(You)') }}"
    };

    // ==================== STATE ====================
    let currentRoundId = null;
    let hasAnswered    = false;
    let timerInterval  = null;
    let isSubmitting   = false;  // Prevents double-clicks during fetch

    // ==================== DOM REFERENCES ====================
    const els = {
        qNumber:    document.getElementById('q-number'),
        qTotal:     document.getElementById('q-total'),
        qPoints:    document.getElementById('q-points'),
        qTimer:     document.getElementById('q-timer'),
        qText:      document.getElementById('q-text'),
        timerBar:   document.getElementById('timer-bar'),
        teamAScore: document.getElementById('team-a-score'),
        teamBScore: document.getElementById('team-b-score'),
        teamABar:   document.getElementById('team-a-bar'),
        teamBBar:   document.getElementById('team-b-bar'),
        answeredStatus: document.getElementById('answered-status'),
        resultIcon: document.getElementById('answer-result-icon'),
        resultText: document.getElementById('answer-result-text'),
        answersCount: document.getElementById('answers-count'),
        answersTotal: document.getElementById('answers-total'),
        players:    document.getElementById('player-indicators'),
        questionCard: document.getElementById('question-card'),
    };

    const optionBtns = {
        a: document.getElementById('opt-a'),
        b: document.getElementById('opt-b'),
        c: document.getElementById('opt-c'),
        d: document.getElementById('opt-d'),
    };

    const optionTexts = {
        a: document.getElementById('opt-a-text'),
        b: document.getElementById('opt-b-text'),
        c: document.getElementById('opt-c-text'),
        d: document.getElementById('opt-d-text'),
    };

    // ==================== BUTTON CLICK HANDLERS ====================
    // Using JS event listeners instead of inline onclick for reliability
    ['a', 'b', 'c', 'd'].forEach(key => {
        optionBtns[key].addEventListener('click', function(e) {
            e.preventDefault();
            submitAnswer(key.toUpperCase());
        });
    });

    // ==================== SUBMIT ANSWER ====================
    function submitAnswer(option) {
        // Guard: prevent multiple submissions
        if (hasAnswered || isSubmitting) return;
        if (!currentRoundId) return; // No question loaded yet

        isSubmitting = true;
        hasAnswered = true;

        const selectedKey = option.toLowerCase(); // 'a', 'b', 'c', 'd'
        const btn = optionBtns[selectedKey];

        // 1. Immediate visual feedback: highlight selected
        if (btn) {
            btn.classList.add('ring-2', 'ring-primary-500');
            btn.style.opacity = '1';
        }

        // 2. Disable ALL buttons immediately
        disableAllOptions();

        // 3. Send to server
        // IMPORTANT: Controller expects 'selected_option' and 'round_id'
        fetch(answerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                selected_option: option,   // 'A', 'B', 'C', or 'D'
                round_id: currentRoundId
            })
        })
        .then(response => response.json())
        .then(data => {
            isSubmitting = false;

            if (data.success) {
                // Server confirmed — show result
                showAnswerResult(selectedKey, data.is_correct, data.points_awarded);
            } else if (data.error && data.error.includes(__('Already answered'))) {
                // Race condition: answer was saved but UI didn't update
                // Keep hasAnswered = true, poll will sync the UI
                console.warn('Already answered — waiting for poll sync.');
            } else {
                // Real error (timer expired, invalid round, etc.)
                console.error('Answer error:', data.error);
                hasAnswered = false;
                enableAllOptions();
                if (btn) btn.classList.remove('ring-2', 'ring-primary-500');
            }
        })
        .catch(err => {
            isSubmitting = false;
            console.error('Network error submitting answer:', err);
            // On network error, allow retry
            hasAnswered = false;
            enableAllOptions();
            if (btn) btn.classList.remove('ring-2', 'ring-primary-500');
        });
    }

    // ==================== SHOW ANSWER RESULT ====================
    function showAnswerResult(selectedKey, isCorrect, points) {
        const btn = optionBtns[selectedKey];
        if (btn) {
            btn.classList.remove('ring-2', 'ring-primary-500');
            if (isCorrect) {
                btn.classList.add('correct');
            } else {
                btn.classList.add('wrong');
            }
            btn.style.opacity = '1';
        }

        // Show feedback card
        els.answeredStatus.classList.remove('hidden');
        els.resultIcon.textContent = isCorrect ? '✅' : '❌';
        els.resultText.textContent = isCorrect
            ? `${trans.correct} +${points} ${trans.pts}`
            : trans.wrong;
    }

    // ==================== OPTION HELPERS ====================
    function disableAllOptions() {
        Object.values(optionBtns).forEach(btn => {
            btn.disabled = true;
        });
    }

    function enableAllOptions() {
        Object.values(optionBtns).forEach(btn => {
            if (btn.style.display !== 'none') {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.classList.remove('correct', 'wrong', 'ring-2', 'ring-primary-500');
            }
        });
    }

    // ==================== TIMER ====================
    function startClientTimer(seconds) {
        if (timerInterval) clearInterval(timerInterval);
        let timeLeft = Math.max(0, seconds);

        updateTimerDisplay(timeLeft);

        timerInterval = setInterval(() => {
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                return;
            }
            updateTimerDisplay(timeLeft);
        }, 1000);
    }

    function updateTimerDisplay(time) {
        els.qTimer.textContent = time;

        if (time <= 5) {
            els.qTimer.classList.add('text-red-500');
            els.qTimer.classList.remove('text-gradient');
        } else {
            els.qTimer.classList.remove('text-red-500');
            els.qTimer.classList.add('text-gradient');
        }

        const pct = (time / questionTimerTotal) * 100;
        els.timerBar.style.width = pct + '%';

        if (time <= 5) {
            els.timerBar.classList.remove('from-emerald-500', 'to-emerald-400');
            els.timerBar.classList.add('from-red-500', 'to-red-400');
        } else {
            els.timerBar.classList.remove('from-red-500', 'to-red-400');
            els.timerBar.classList.add('from-emerald-500', 'to-emerald-400');
        }
    }

    // ==================== POLL ====================
    function poll() {
        fetch(pollUrl)
            .then(r => r.json())
            .then(data => {
                // Game finished → go to results
                if (data.status === 'finished') {
                    window.location.href = resultsUrl;
                    return;
                }

                if (data.status === 'playing') {
                    handleRoundData(data);
                    updateScores(data);
                    updatePlayers(data);
                }
            })
            .catch(err => console.error('Poll error:', err))
            .finally(() => {
                setTimeout(poll, 2000);
            });
    }

    // Start polling immediately
    poll();

    // ==================== HANDLE ROUND DATA ====================
    function handleRoundData(data) {
        if (!data.current_round) return;

        const round = data.current_round;

        // --- NEW ROUND DETECTED ---
        if (currentRoundId !== round.round_id) {
            currentRoundId = round.round_id;
            hasAnswered = false;
            isSubmitting = false;

            // Reset UI for new question
            els.answeredStatus.classList.add('hidden');
            els.questionCard.classList.add('question-enter');
            setTimeout(() => els.questionCard.classList.remove('question-enter'), 600);

            // Update question text
            els.qNumber.textContent = round.round_number;
            els.qTotal.textContent  = round.total_rounds;
            els.qPoints.textContent = round.points;
            els.qText.textContent   = round.question_text;

            // Update options
            ['a', 'b', 'c', 'd'].forEach(key => {
                const text = round['option_' + key];
                const btn  = optionBtns[key];
                const span = optionTexts[key];

                // Reset all classes
                btn.classList.remove('correct', 'wrong', 'ring-2', 'ring-primary-500');
                btn.style.opacity = '1';

                if (text) {
                    btn.style.display = 'flex';
                    btn.disabled = false;
                    span.textContent = text;
                } else {
                    btn.style.display = 'none';
                    btn.disabled = true;
                }
            });
        }

        // --- CHECK IF ALREADY ANSWERED (from server state) ---
        if (round.my_answer && !hasAnswered) {
            hasAnswered = true;
            isSubmitting = false;
            const ans = round.my_answer;
            showAnswerResult(ans.selected.toLowerCase(), ans.is_correct, ans.points);
            disableAllOptions();
        }

        // --- UPDATE ANSWER COUNT ---
        if (round.answers_count !== undefined) {
            els.answersCount.textContent = round.answers_count;
            els.answersTotal.textContent = round.total_players || '?';
        }

        // --- SYNC TIMER (only if not answered, to avoid resetting display) ---
        if (!hasAnswered) {
            startClientTimer(round.time_remaining);
        } else {
            // If answered, just show current server time but don't restart interval
            els.qTimer.textContent = round.time_remaining;
            const pct = (round.time_remaining / questionTimerTotal) * 100;
            els.timerBar.style.width = pct + '%';
        }
    }

    // ==================== UPDATE SCORES ====================
    function updateScores(data) {
        const scoreA = parseInt(data.team_a_score) || 0;
        const scoreB = parseInt(data.team_b_score) || 0;

        els.teamAScore.textContent = scoreA;
        els.teamBScore.textContent = scoreB;

        const maxScore = Math.max(scoreA, scoreB, 10);
        els.teamABar.style.width = ((scoreA / maxScore) * 100) + '%';
        els.teamBBar.style.width = ((scoreB / maxScore) * 100) + '%';
    }

    // ==================== UPDATE PLAYERS ====================
    function updatePlayers(data) {
        if (!data.players) return;

        els.players.innerHTML = data.players.map(p => {
            const color = p.team === 'a' ? 'bg-red-500' : 'bg-blue-500';
            const name = p.name.split(' ')[0];
            const me = p.is_me ? ' ' + trans.you : '';
            return `<div class="flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold ${color} text-white">
                ${name}${me}
            </div>`;
        }).join('');
    }

})();
</script>
@endsection
