@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $playTranslations = [
        'correct' => $isArabic ? 'إجابة صحيحة' : 'Correct!',
        'wrong' => $isArabic ? 'إجابة غير صحيحة' : 'Wrong answer!',
        'pts' => $isArabic ? 'نقطة' : 'pts',
        'you' => $isArabic ? '(أنت)' : '(You)',
    ];
@endphp

@section('title', $isArabic ? 'الباتل' : 'Battle')

@section('content')
<style>
    .option-btn { transition: all 0.3s; cursor: pointer; }
    .option-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
    .option-btn:disabled { cursor: not-allowed; opacity: 0.6; }
    .option-btn.correct { background: rgba(16, 185, 129, 0.2) !important; border-color: #10b981 !important; box-shadow: 0 0 20px rgba(16,185,129,0.3); }
    .option-btn.wrong { background: rgba(239, 68, 68, 0.2) !important; border-color: #ef4444 !important; box-shadow: 0 0 20px rgba(239,68,68,0.3); }
    .score-bar { transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .question-enter { animation: slideIn 0.5s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <x-student.card padding="p-4" class="mb-6">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-red-500" id="team-a-name">{{ $room->team_a_name }}</span>
                        <span class="text-lg font-extrabold text-red-500" id="team-a-score">{{ $room->team_a_score }}</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-200 dark:bg-slate-700/30 overflow-hidden">
                        <div id="team-a-bar" class="h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full score-bar" style="width: 50%;"></div>
                    </div>
                </div>

                <div class="text-2xl font-black text-slate-500 dark:text-slate-400">VS</div>

                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-bold text-blue-500" id="team-b-name">{{ $room->team_b_name }}</span>
                        <span class="text-lg font-extrabold text-blue-500" id="team-b-score">{{ $room->team_b_score }}</span>
                    </div>
                    <div class="w-full h-3 rounded-full bg-slate-200 dark:bg-slate-700/30 overflow-hidden">
                        <div id="team-b-bar" class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full score-bar" style="width: 50%;"></div>
                    </div>
                </div>
            </div>
        </x-student.card>

        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white {{ $participant->team === 'a' ? 'bg-red-500' : 'bg-blue-500' }}">
                {{ $participant->team === 'a' ? $room->team_a_name : $room->team_b_name }}
                -
                {{ $isArabic ? 'فريقك' : 'Your team' }}
            </span>
        </div>

        <div class="text-center mb-4">
            <form action="{{ route('student.battle.leave', $room) }}" method="POST" onsubmit="return confirm(@js($isArabic ? 'هل تريد الخروج من الباتل الآن؟ سيتم إنهاء الجولة الحالية.' : 'Do you want to leave the battle now? The current match will be closed.'));">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-bold text-red-500 border border-red-500/30 bg-red-500/5 hover:bg-red-500/10 transition-colors">
                    {{ $isArabic ? 'الخروج من الباتل' : 'Leave battle' }}
                </button>
            </form>
        </div>

        <x-student.card padding="p-8" class="mb-6 question-enter" id="question-card">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                        {{ $isArabic ? 'السؤال' : 'Question' }} <span id="q-number">-</span>/<span id="q-total">-</span>
                    </span>
                    <span class="text-sm font-bold px-3 py-1 rounded-full bg-amber-500/20 text-amber-500">
                        <span id="q-points">-</span> {{ $isArabic ? 'نقطة' : 'pts' }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ $isArabic ? 'الوقت' : 'Time' }}</span>
                    <span id="q-timer" class="text-2xl font-extrabold tabular-nums text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-500">--</span>
                </div>
            </div>

            <div class="w-full h-1.5 rounded-full bg-slate-200 dark:bg-slate-700/30 mb-6 overflow-hidden">
                <div id="timer-bar" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-1000 ease-linear" style="width: 100%;"></div>
            </div>

            <h2 id="q-text" class="text-xl sm:text-2xl font-bold mb-8 leading-relaxed text-slate-900 dark:text-white">
                {{ $isArabic ? 'جارٍ تحميل السؤال...' : 'Loading question...' }}
            </h2>

            <div id="options-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <button id="opt-a" disabled class="option-btn glass-card rounded-2xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-white/5 p-4 text-left flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800">
                    <span class="w-10 h-10 rounded-xl bg-primary-500/20 flex items-center justify-center text-primary-500 font-bold flex-shrink-0">A</span>
                    <span id="opt-a-text" class="font-medium text-slate-900 dark:text-white">-</span>
                </button>
                <button id="opt-b" disabled class="option-btn glass-card rounded-2xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-white/5 p-4 text-left flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800">
                    <span class="w-10 h-10 rounded-xl bg-accent-500/20 flex items-center justify-center text-accent-500 font-bold flex-shrink-0">B</span>
                    <span id="opt-b-text" class="font-medium text-slate-900 dark:text-white">-</span>
                </button>
                <button id="opt-c" disabled class="option-btn glass-card rounded-2xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-white/5 p-4 text-left flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800" style="display: none;">
                    <span class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-500 font-bold flex-shrink-0">C</span>
                    <span id="opt-c-text" class="font-medium text-slate-900 dark:text-white">-</span>
                </button>
                <button id="opt-d" disabled class="option-btn glass-card rounded-2xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-white/5 p-4 text-left flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800" style="display: none;">
                    <span class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-500 font-bold flex-shrink-0">D</span>
                    <span id="opt-d-text" class="font-medium text-slate-900 dark:text-white">-</span>
                </button>
            </div>
        </x-student.card>

        <x-student.card id="answered-status" padding="p-4" class="hidden mb-6 text-center">
            <span id="answer-result-icon" class="text-4xl block mb-2"></span>
            <span id="answer-result-text" class="text-lg font-bold text-slate-900 dark:text-white"></span>
        </x-student.card>

        <div class="text-center text-sm font-medium text-slate-500 dark:text-slate-400">
            {{ $isArabic ? 'إجابات اللاعبين' : 'Team answers' }}: <span id="answers-count">0</span>/<span id="answers-total">0</span>
        </div>

        <div id="player-indicators" class="flex justify-center gap-2 mt-4 flex-wrap"></div>
    </div>
</div>

<script>
(function () {
    const pollUrl = "{{ route('student.battle.poll', $room) }}";
    const answerUrl = "{{ route('student.battle.answer', $room) }}";
    const resultsUrl = "{{ route('student.battle.results', $room) }}";
    const csrfToken = "{{ csrf_token() }}";
    const questionTimerTotal = {{ $room->question_timer_seconds }};

    const trans = @json($playTranslations);

    let currentRoundId = null;
    let hasAnswered = false;
    let timerInterval = null;
    let isSubmitting = false;

    const els = {
        qNumber: document.getElementById('q-number'),
        qTotal: document.getElementById('q-total'),
        qPoints: document.getElementById('q-points'),
        qTimer: document.getElementById('q-timer'),
        qText: document.getElementById('q-text'),
        timerBar: document.getElementById('timer-bar'),
        teamAScore: document.getElementById('team-a-score'),
        teamBScore: document.getElementById('team-b-score'),
        teamABar: document.getElementById('team-a-bar'),
        teamBBar: document.getElementById('team-b-bar'),
        answeredStatus: document.getElementById('answered-status'),
        resultIcon: document.getElementById('answer-result-icon'),
        resultText: document.getElementById('answer-result-text'),
        answersCount: document.getElementById('answers-count'),
        answersTotal: document.getElementById('answers-total'),
        players: document.getElementById('player-indicators'),
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

    ['a', 'b', 'c', 'd'].forEach((key) => {
        optionBtns[key].addEventListener('click', function (event) {
            event.preventDefault();
            submitAnswer(key.toUpperCase());
        });
    });

    function disableAllOptions() {
        Object.values(optionBtns).forEach((button) => {
            button.disabled = true;
        });
    }

    function enableAllOptions() {
        Object.values(optionBtns).forEach((button) => {
            if (button.style.display !== 'none') {
                button.disabled = false;
                button.style.opacity = '1';
                button.classList.remove('correct', 'wrong', 'ring-2', 'ring-primary-500');
            }
        });
    }

    function showAnswerResult(selectedKey, isCorrect, points) {
        const button = optionBtns[selectedKey];
        if (button) {
            button.classList.remove('ring-2', 'ring-primary-500');
            button.classList.add(isCorrect ? 'correct' : 'wrong');
            button.style.opacity = '1';
        }

        els.answeredStatus.classList.remove('hidden');
        els.resultIcon.innerHTML = isCorrect ? '&#10003;' : '&#10005;';
        els.resultText.textContent = isCorrect
            ? `${trans.correct} +${points} ${trans.pts}`
            : trans.wrong;
    }

    function submitAnswer(option) {
        if (hasAnswered || isSubmitting || !currentRoundId) {
            return;
        }

        isSubmitting = true;
        hasAnswered = true;

        const selectedKey = option.toLowerCase();
        const button = optionBtns[selectedKey];

        if (button) {
            button.classList.add('ring-2', 'ring-primary-500');
            button.style.opacity = '1';
        }

        disableAllOptions();

        fetch(answerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                selected_option: option,
                round_id: currentRoundId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                isSubmitting = false;

                if (data.success) {
                    showAnswerResult(selectedKey, data.is_correct, data.points_awarded);
                    return;
                }

                if (data.error === 'Already answered this question') {
                    return;
                }

                hasAnswered = false;
                enableAllOptions();
                if (button) {
                    button.classList.remove('ring-2', 'ring-primary-500');
                }
            })
            .catch(() => {
                isSubmitting = false;
                hasAnswered = false;
                enableAllOptions();
                if (button) {
                    button.classList.remove('ring-2', 'ring-primary-500');
                }
            });
    }

    function updateTimerDisplay(time) {
        els.qTimer.textContent = time;

        if (time <= 5) {
            els.qTimer.classList.add('text-red-500');
            els.qTimer.classList.remove('text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-primary-600', 'to-accent-500');
        } else {
            els.qTimer.classList.remove('text-red-500');
            els.qTimer.classList.add('text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-primary-600', 'to-accent-500');
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

    function startClientTimer(seconds) {
        if (timerInterval) {
            clearInterval(timerInterval);
        }

        let timeLeft = Math.max(0, seconds);
        updateTimerDisplay(timeLeft);

        timerInterval = setInterval(() => {
            timeLeft -= 1;
            if (timeLeft < 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                return;
            }

            updateTimerDisplay(timeLeft);
        }, 1000);
    }

    function updateScores(data) {
        const scoreA = parseInt(data.team_a_score, 10) || 0;
        const scoreB = parseInt(data.team_b_score, 10) || 0;

        els.teamAScore.textContent = scoreA;
        els.teamBScore.textContent = scoreB;

        const maxScore = Math.max(scoreA, scoreB, 10);
        els.teamABar.style.width = ((scoreA / maxScore) * 100) + '%';
        els.teamBBar.style.width = ((scoreB / maxScore) * 100) + '%';
    }

    function updatePlayers(data) {
        if (!data.players) {
            return;
        }

        els.players.innerHTML = data.players.map((player) => {
            const color = player.team === 'a' ? 'bg-red-500' : 'bg-blue-500';
            const name = player.name.split(' ')[0];
            const me = player.is_me ? ` ${trans.you}` : '';

            return `<div class="flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold ${color} text-white">${name}${me}</div>`;
        }).join('');
    }

    function handleRoundData(data) {
        if (!data.current_round) {
            return;
        }

        const round = data.current_round;

        if (currentRoundId !== round.round_id) {
            currentRoundId = round.round_id;
            hasAnswered = false;
            isSubmitting = false;
            els.answeredStatus.classList.add('hidden');
            els.questionCard.classList.add('question-enter');
            setTimeout(() => els.questionCard.classList.remove('question-enter'), 600);

            els.qNumber.textContent = round.round_number;
            els.qTotal.textContent = round.total_rounds;
            els.qPoints.textContent = round.points;
            els.qText.textContent = round.question_text;

            ['a', 'b', 'c', 'd'].forEach((key) => {
                const text = round['option_' + key];
                const button = optionBtns[key];
                const span = optionTexts[key];

                button.classList.remove('correct', 'wrong', 'ring-2', 'ring-primary-500');
                button.style.opacity = '1';

                if (text) {
                    button.style.display = 'flex';
                    button.disabled = false;
                    span.textContent = text;
                } else {
                    button.style.display = 'none';
                    button.disabled = true;
                    span.textContent = '-';
                }
            });
        }

        if (round.my_answer && !hasAnswered) {
            hasAnswered = true;
            isSubmitting = false;
            showAnswerResult(round.my_answer.selected.toLowerCase(), round.my_answer.is_correct, round.my_answer.points);
            disableAllOptions();
        }

        els.answersCount.textContent = round.answers_count ?? 0;
        els.answersTotal.textContent = round.total_players ?? 0;

        if (!hasAnswered) {
            startClientTimer(round.time_remaining);
        } else {
            updateTimerDisplay(round.time_remaining);
        }
    }

    function poll() {
        fetch(pollUrl)
            .then((response) => response.json())
            .then((data) => {
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
            .catch((error) => console.error('Poll error:', error))
            .finally(() => {
                setTimeout(poll, 2000);
            });
    }

    poll();
})();
</script>
@endsection
