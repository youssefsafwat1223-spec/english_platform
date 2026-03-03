@extends('layouts.admin')
@section('title', $game->title . ' - ' . __('Control Panel'))
@section('content')
<div class="py-12 relative overflow-hidden" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
     x-data="adminGameMonitor()" x-init="startPolling()">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header --}}
        <div class="mb-8" data-aos="fade-down">
            <a href="{{ route('admin.games.index') }}" class="text-sm font-medium hover:underline" style="color: var(--color-text-muted);">{{ __('Back to Competitions') }}</a>
            <div class="flex items-center justify-between mt-2">
                <div>
                    <h1 class="text-3xl font-extrabold"><span class="text-gradient">🎮 {{ $game->title }}</span></h1>
                    <p class="mt-1" style="color: var(--color-text-muted);">{{ $game->course->title }} | {{ $game->start_time->format('Y-m-d h:i A') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 rounded-lg text-sm font-bold"
                          :class="{
                              'bg-yellow-500/10 text-yellow-500': gameStatus === 'scheduled',
                              'bg-emerald-500/10 text-emerald-500 animate-pulse': gameStatus === 'active',
                              'bg-primary-500/10 text-primary-500': gameStatus === 'completed'
                          }"
                          x-text="statusLabel">
                    </span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Control Buttons (Forms are outside Alpine scope for simplicity, they reload page) --}}
        <div class="glass-card p-6 mb-6" data-aos="fade-up">
            <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">{{ __('Control Panel') }}</h2>
            <div class="flex flex-wrap gap-3">
                @if($game->status === 'scheduled')
                    <form action="{{ route('admin.games.notify', $game) }}" method="POST" class="inline">
                        @csrf
                        <button class="btn-secondary">{{ __('Send Invitations') }}</button>
                    </form>
                    <form action="{{ route('admin.games.start', $game) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Confirm Start') }}')">
                        @csrf
                        <button class="btn-primary ripple-btn">{{ __('Start Game') }}</button>
                    </form>
                @elseif($game->status === 'active')
                    <form action="{{ route('admin.games.next-question', $game) }}" method="POST" class="inline">
                        @csrf
                        <button class="btn-primary ripple-btn">{{ __('Next Question + Change Captain') }}</button>
                    </form>
                    <form action="{{ route('admin.games.end', $game) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Confirm End') }}')">
                        @csrf
                        <button class="px-4 py-2 rounded-xl text-red-500 border border-red-500/30 hover:bg-red-500/10 transition font-bold text-sm">{{ __('End Game') }}</button>
                    </form>
                @elseif($game->status === 'completed')
                    <p class="text-sm font-bold text-primary-500">{{ __('Game is completed.') }}</p>
                @endif
            </div>

            {{-- Live Current Question --}}
            <template x-if="gameStatus === 'active' && currentQuestion">
                <div class="mt-4 p-4 rounded-xl border" style="border-color: var(--color-border); background: var(--color-bg-elevated);">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-primary-500">
                            {{ __('Current Question') }} #<span x-text="currentQuestion.index + 1"></span>
                        </span>
                        <span class="text-xs px-2 py-1 rounded-lg bg-yellow-500/10 text-yellow-500 font-bold">
                            ⏱ <span x-text="timeRemaining"></span>{{ __('s | 🏆') }}<span x-text="currentQuestion.points"></span> {{ __('point') }}
                        </span>
                    </div>
                    <p class="font-bold text-lg mb-3" style="color: var(--color-text);" x-text="currentQuestion.question_text"></p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <template x-for="opt in currentQuestion.options" :key="opt">
                            <div class="px-3 py-2 rounded-lg text-sm font-medium flex justify-between items-center"
                                 :class="opt === currentQuestion.correct_answer ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/30' : 'bg-white/5 text-gray-400'">
                                <span x-text="opt"></span>
                                <span x-show="opt === currentQuestion.correct_answer">✅</span>
                            </div>
                        </template>
                    </div>

                    {{-- Live Answers --}}
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <h4 class="text-sm font-bold mb-2" style="color: var(--color-text-muted);">{{ __('Live Answers') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="ans in answers" :key="ans.team_id">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1"
                                      :class="ans.is_correct ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'">
                                    <span x-text="ans.team_name"></span>:
                                    <span x-text="ans.selected_option"></span>
                                    <span x-show="ans.is_correct" x-text="'(' + ans.points + ')'"></span>
                                </span>
                            </template>
                            <template x-if="answers.length === 0">
                                <span class="text-xs text-gray-500">{{ __('Waiting for answers...') }}</span>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Leaderboard --}}
            <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">{{ __('Team Ranking') }}</h2>
                <div class="space-y-3">
                    <template x-for="(team, index) in leaderboard" :key="team.id">
                        <div class="flex items-center gap-4 p-4 rounded-xl transition-all duration-500"
                             style="background: var(--color-bg-elevated);">
                            <div class="text-2xl font-extrabold" style="color: var(--color-text-muted);" x-text="index + 1"></div>
                            <div class="w-4 h-8 rounded" :style="'background-color: ' + team.color"></div>
                            <div class="flex-1">
                                <div class="font-bold" style="color: var(--color-text);" x-text="team.name"></div>
                                <div class="text-xs" style="color: var(--color-text-muted);">
                                    <span x-text="team.participants_count"></span> {{ __('players') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-extrabold text-primary-500" x-text="team.score"></div>
                                <div class="text-xs" style="color: var(--color-text-muted);">{{ __('point') }}</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Questions List --}}
            <div class="glass-card p-6 lg:col-span-2" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">{{ __('Questions') }}</h2>
                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                    @foreach($game->questions as $index => $question)
                        <div class="p-4 rounded-xl transition-colors"
                             :class="currentQuestion && currentQuestion.index === {{ $index }} ? 'ring-2 ring-primary-500 bg-primary-500/5' : ''"
                             style="background: var(--color-bg-elevated);">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-sm font-bold"
                                      :class="currentQuestion && currentQuestion.index === {{ $index }} ? 'text-primary-500' : 'text-gray-400'">
                                    Q{{ $index + 1 }}
                                    <span x-show="currentQuestion && currentQuestion.index === {{ $index }}">{{ __('◀ Current') }}</span>
                                </span>
                                <span class="text-xs" style="color: var(--color-text-muted);">⏱ {{ $question->time_limit_seconds }}s | 🏆 {{ $question->points }}pts</span>
                            </div>
                            <p class="text-sm font-medium" style="color: var(--color-text);">{{ $question->question_text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    @php
        // Prepare initial Question
        $initialQuestion = null;
        if ($game->status === 'active' && $game->current_question_index >= 0) {
            $q = $game->questions->skip($game->current_question_index)->first();
            if ($q) {
                $initialQuestion = $q->toArray();
                $initialQuestion['index'] = $game->current_question_index;
            }
        }

        // Prepare Leaderboard
        $initialLeaderboard = $leaderboard->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'color' => $t->color_hex,
            'score' => $t->score,
            'participants_count' => $t->participants->count()
        ])->values();
    @endphp

function adminGameMonitor() {
    return {
        gameStatus: '{{ $game->status }}',
        currentQuestion: @json($initialQuestion),
        answers: [],
        leaderboard: @json($initialLeaderboard),
        timeRemaining: 0,
        pollInterval: null,
        timerInterval: null,

        get statusLabel() {
            if (this.gameStatus === 'scheduled') return '{{ __('Scheduled') }}';
            if (this.gameStatus === 'active') return '{{ __('Active Now') }}';
            return '{{ __('Completed') }}';
        },

        startPolling() {
            this.poll();
            this.pollInterval = setInterval(() => this.poll(), 3000);
            this.timerInterval = setInterval(() => {
                if (this.timeRemaining > 0) this.timeRemaining--;
            }, 1000);
        },

        async poll() {
            try {
                const res = await fetch('{{ route("admin.games.poll", $game) }}');
                const data = await res.json();
                
                this.gameStatus = data.status;
                
                // Only update specific fields to avoid flicker
                if (JSON.stringify(this.leaderboard) !== JSON.stringify(data.leaderboard)) {
                    this.leaderboard = data.leaderboard;
                }
                
                this.answers = data.answers;
                
                if (data.current_question) {
                    // Update current question info
                    this.currentQuestion = {
                        ...data.current_question,
                        index: data.current_question_index
                    };
                    
                    // Sync time
                    if (Math.abs(this.timeRemaining - data.time_remaining) > 2) {
                        this.timeRemaining = data.time_remaining;
                    }
                } else {
                    this.currentQuestion = null;
                }

            } catch (e) {
                console.error('Poll error', e);
            }
        }
    }
}
</script>
@endsection
