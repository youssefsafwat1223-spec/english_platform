@extends('layouts.app')
@section('title', $game->title . ' - Game Room')
@section('content')
<div class="py-8 lg:py-12 relative min-h-screen overflow-hidden" x-data="gameRoom()" x-init="startPolling()" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    <div class="student-container relative z-10">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="text-4xl animate-bounce">🎮</span>
                    {{ $game->title }}
                </h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">{{ $game->course->title }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <template x-if="gameStatus === 'active'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-black uppercase tracking-wider shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('Active Now') }}
                    </span>
                </template>
                <template x-if="gameStatus === 'scheduled'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-sm font-black uppercase tracking-wider shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Waiting to start') }}
                    </span>
                </template>
                <template x-if="gameStatus === 'completed'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-200/50 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-black uppercase tracking-wider shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Completed') }}
                    </span>
                </template>
                <a href="{{ route('student.games.index') }}" class="btn-ghost flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    {{ __('Leave Room') }}
                </a>
            </div>
        </div>

        {{-- Game Instructions (before start) --}}
        <template x-if="gameStatus === 'scheduled'">
            <x-student.card padding="p-8 md:p-12" class="border-t-8 border-t-primary-500 shadow-2xl max-w-2xl relative group mx-auto" data-aos="fade-up">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="text-center relative z-10">
                    <div class="w-24 h-24 mx-auto rounded-3xl bg-primary-500/10 text-primary-500 flex items-center justify-center text-5xl mb-6 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                        📖
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">{{ __('Game Instructions') }}</h2>
                    <div class="text-[15px] font-medium text-slate-600 dark:text-slate-400 space-y-4 mb-8 text-left bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <p class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</span> {{ __('Discuss the question in the Team Chat with your teammates.') }}</p>
                        <p class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</span> {{ __('The Captain is the only one who can select and submit the final answer.') }}</p>
                        <p class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</span> {{ __('Answer quickly! Faster correct answers might earn more points.') }}</p>
                        <p class="flex items-start gap-3"><span class="w-6 h-6 rounded-full bg-primary-500/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</span> {{ __('Keep an eye on the clock and the leaderboard.') }}</p>
                    </div>
                    
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-sm flex flex-col items-center justify-center">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">{{ __('You are playing for') }}</p>
                        <div class="flex items-center justify-center gap-3">
                            <div class="w-6 h-6 rounded-xl shadow-md border-2 border-white dark:border-slate-800" style="background-color: {{ $team->color_hex }};"></div>
                            <span class="font-black text-2xl text-slate-900 dark:text-white">{{ $team->name }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 animate-pulse-soft">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Waiting for the host to start the game...') }}</span>
                        </div>
                    </div>
                </div>
            </x-student.card>
        </template>

        {{-- Main Game Layout (Active / Completed) --}}
        <template x-if="gameStatus === 'active' || gameStatus === 'completed'">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- Left Column: Question + Answer --}}
                <div class="xl:col-span-2 space-y-6 flex flex-col h-[calc(100vh-150px)] min-h-[600px] max-h-[800px]">

                    {{-- Status Bar (Banner) --}}
                    <x-student.card padding="p-4" class="flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0" x-show="gameStatus === 'active'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl shadow-inner">👑</div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Team Captain') }}</div>
                                <div class="text-sm font-black text-slate-900 dark:text-white" x-text="captainName"></div>
                            </div>
                        </div>
                        <template x-if="isCaptain">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-xs font-black uppercase tracking-wider shadow-sm animate-pulse-soft">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('You are the Captain! Submit answers.') }}
                            </span>
                        </template>
                        <template x-if="!isCaptain">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold">
                                {{ __('Only the Captain can submit the final answer.') }}
                            </span>
                        </template>
                    </x-student.card>

                    {{-- Question Area --}}
                    <x-student.card padding="p-0" class="shadow-xl flex-1 flex flex-col relative" x-show="gameStatus === 'active' && question">
                        
                        {{-- Decorative background --}}
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none transform translate-x-1/2 -translate-y-1/2 z-0"></div>

                        {{-- Timer & Progress Header --}}
                        <div class="p-6 md:p-8 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between gap-4 relative z-10 shrink-0">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ __('Question Progress') }}</div>
                                <div class="text-lg font-black text-slate-900 dark:text-white flex items-baseline gap-1">
                                    <span x-text="question?.index + 1" class="text-2xl text-primary-500"></span>
                                    <span class="text-slate-400">/</span>
                                    <span x-text="question?.total"></span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 text-right">
                                <div class="hidden sm:block">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ __('Time Remaining') }}</div>
                                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400 text-right"><span x-text="timeRemaining"></span> {{ __('seconds') }}</div>
                                </div>
                                <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="32" cy="32" r="28" stroke-width="6" fill="transparent" class="stroke-slate-200 dark:stroke-slate-700"/>
                                        <circle cx="32" cy="32" r="28" stroke-width="6" fill="transparent"
                                            stroke-dasharray="175.93"
                                            :stroke-dashoffset="175.93 - (175.93 * (question ? (timeRemaining / question.time_limit) : 0))"
                                            class="transition-all duration-1000 ease-linear"
                                            :class="timeRemaining <= 5 ? 'stroke-rose-500 animate-pulse' : 'stroke-primary-500'"
                                            stroke-linecap="round"/>
                                    </svg>
                                    <span class="absolute text-xl font-black tabular-nums" :class="timeRemaining <= 5 ? 'text-rose-500' : 'text-slate-900 dark:text-white'" x-text="timeRemaining"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Question Content --}}
                        <div class="p-6 md:p-8 flex-1 flex flex-col relative z-10 overflow-y-auto">
                            <h3 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white leading-snug mb-8" x-text="question?.text"></h3>

                            <div class="grid grid-cols-1 gap-3 sm:gap-4 mt-auto">
                                <template x-for="(opt, optIndex) in (question?.options || [])" :key="optIndex">
                                    <div class="relative">
                                        <button type="button"
                                                @click="submitAnswer(opt)"
                                                :disabled="!isCaptain || teamAnswered || timeRemaining <= 0"
                                                :class="{
                                                    'ring-2 ring-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-500/30 text-emerald-900 dark:text-emerald-100 shadow-md transform scale-[1.02]': teamAnswered && question?.team_answer?.selected === opt && question?.team_answer?.is_correct,
                                                    'ring-2 ring-rose-500 bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-500/30 text-rose-900 dark:text-rose-100 shadow-md': teamAnswered && question?.team_answer?.selected === opt && !question?.team_answer?.is_correct,
                                                    'opacity-60 cursor-not-allowed bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700': !isCaptain || teamAnswered,
                                                    'hover:-translate-y-1 hover:shadow-lg hover:border-primary-500/50 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 active:scale-95 cursor-pointer': isCaptain && !teamAnswered && timeRemaining > 0,
                                                }"
                                                class="w-full flex items-center p-4 md:p-5 rounded-2xl border-2 text-left font-bold transition-all duration-200 group relative overflow-hidden">
                                            
                                            <span class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-xl text-sm md:text-base font-black shrink-0 mr-4 md:mr-5 text-white shadow-sm transition-transform group-hover:scale-110"
                                                  :style="'background-color: ' + ['#8b5cf6','#10b981','#f59e0b','#ef4444','#0ea5e9','#ec4899'][optIndex]">
                                                <span x-text="['A','B','C','D','E','F'][optIndex]"></span>
                                            </span>
                                            <span class="text-base md:text-lg flex-1" x-text="opt"></span>
                                            
                                            <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none" x-show="isCaptain && !teamAnswered && timeRemaining > 0"></div>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <template x-if="teamAnswered">
                                <div class="mt-6 p-4 rounded-2xl border text-center font-black animate-zoom-in"
                                     :class="question?.team_answer?.is_correct ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-600 dark:text-rose-400'">
                                    <div class="flex items-center justify-center gap-2 text-lg">
                                        <svg x-show="question?.team_answer?.is_correct" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        <svg x-show="!question?.team_answer?.is_correct" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        <span x-text="question?.team_answer?.is_correct ? '{{ __("Correct Answer!") }} +' + question?.team_answer?.points + ' {{ __("points") }}' : '{{ __("Wrong Answer!") }}'"></span>
                                    </div>
                                    <div x-show="!question?.team_answer?.is_correct" class="text-xs font-bold uppercase tracking-wider mt-2 opacity-80">{{ __('Wait for the next question') }}</div>
                                </div>
                            </template>
                        </div>
                    </x-student.card>

                    {{-- Game Over Screen --}}
                    <x-student.card padding="p-8 md:p-12" class="text-center shadow-2xl flex-1 flex flex-col justify-center border-t-8 border-t-primary-500" x-show="gameStatus === 'completed'" x-data="{
                            maxScore: Math.max(...leaderboard.map(t => t.score)),
                            isWinner() { return this.teamScore === this.maxScore }
                        }">
                        
                        {{-- Winner Confetti/Background --}}
                        <div x-show="isWinner()" class="absolute inset-0 bg-gradient-to-b from-amber-500/10 to-transparent pointer-events-none z-0"></div>
                        <div x-show="!isWinner()" class="absolute inset-0 bg-gradient-to-b from-slate-500/5 to-transparent pointer-events-none z-0"></div>

                        <div class="relative z-10 w-full">
                            <template x-if="isWinner()">
                                <div class="mb-8">
                                    <div class="inline-flex w-32 h-32 rounded-full bg-amber-500/20 text-amber-500 items-center justify-center text-6xl mb-6 shadow-inner animate-bounce">
                                        🏆
                                    </div>
                                    <h2 class="text-4xl md:text-5xl font-black mb-3 text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-400">{{ __('Victory!') }}</h2>
                                    <p class="text-xl md:text-2xl font-bold text-slate-700 dark:text-slate-300">{{ __('Your team won the match!') }}</p>
                                </div>
                            </template>
                            <template x-if="!isWinner()">
                                <div class="mb-8">
                                    <div class="inline-flex w-32 h-32 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 items-center justify-center text-6xl mb-6 shadow-inner">
                                        👏
                                    </div>
                                    <h2 class="text-3xl md:text-4xl font-black mb-3 text-slate-900 dark:text-white">{{ __('Game Over') }}</h2>
                                    <p class="text-lg md:text-xl font-medium text-slate-600 dark:text-slate-400">{{ __('Great effort, good luck next time!') }}</p>
                                </div>
                            </template>

                            <div class="inline-flex flex-col items-center justify-center px-10 py-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl relative mt-4 transform hover:scale-105 transition-transform">
                                <div class="absolute -top-3 px-3 py-1 rounded-full bg-primary-500 text-white text-[10px] font-black uppercase tracking-widest shadow-md">
                                    {{ __('Final Score') }}
                                </div>
                                <div class="text-6xl md:text-7xl font-black bg-clip-text text-transparent bg-gradient-to-br from-primary-500 to-accent-500 tracking-tighter" x-text="teamScore"></div>
                            </div>
                            
                            <div class="mt-10">
                                <a href="{{ route('student.games.index') }}" class="btn-primary ripple-btn inline-flex px-8 py-3.5 rounded-xl font-bold text-lg">
                                    {{ __('Return to Competitions') }}
                                </a>
                            </div>
                        </div>
                    </x-student.card>
                </div>

                {{-- Right Column: Leaderboard + Chat --}}
                <div class="xl:col-span-1 space-y-6 flex flex-col h-[calc(100vh-150px)] min-h-[600px] max-h-[800px]">

                    {{-- Leaderboard --}}
                    <x-student.card padding="p-0" class="shadow-xl shrink-0">
                        <div class="px-5 py-4 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="text-xl">📊</span> {{ __('Live Ranking') }}
                            </h3>
                            <span class="px-2 py-1 rounded bg-primary-500/10 text-primary-600 dark:text-primary-400 text-[10px] font-bold uppercase tracking-wider">{{ __('Top Teams') }}</span>
                        </div>
                        <div class="p-5 flex flex-col gap-2 max-h-[250px] overflow-y-auto">
                            <template x-for="(t, idx) in leaderboard" :key="idx">
                                <div class="flex items-center gap-3 p-3 rounded-xl border transition-colors relative overflow-hidden group"
                                     :class="t.is_mine ? 'bg-primary-50/50 dark:bg-primary-900/10 border-primary-500/30 ring-1 ring-primary-500/50' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50'">
                                    
                                    {{-- Medals for top 3 --}}
                                    <div class="w-6 justify-center flex shrink-0">
                                        <span x-show="idx === 0" class="text-xl drop-shadow-sm">🥇</span>
                                        <span x-show="idx === 1" class="text-xl drop-shadow-sm">🥈</span>
                                        <span x-show="idx === 2" class="text-xl drop-shadow-sm">🥉</span>
                                        <span x-show="idx > 2" class="text-sm font-black text-slate-400 dark:text-slate-500" x-text="idx + 1"></span>
                                    </div>
                                    
                                    <div class="w-1.5 h-8 rounded-full shrink-0" :style="'background-color:' + t.color"></div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-sm truncate" :class="t.is_mine ? 'text-primary-700 dark:text-primary-300' : 'text-slate-900 dark:text-white'" x-text="t.name"></div>
                                        <div x-show="t.is_mine" class="text-[10px] font-bold uppercase text-primary-500 tracking-wider">{{ __('Your Team') }}</div>
                                    </div>
                                    
                                    <div class="text-lg font-black text-right shrink-0 min-w-[3rem]" :class="t.is_mine ? 'text-primary-600 dark:text-primary-400' : 'text-slate-700 dark:text-slate-300'" x-text="t.score"></div>
                                </div>
                            </template>
                        </div>
                    </x-student.card>

                    {{-- Team Chat --}}
                    <x-student.card padding="p-0" class="flex-1 shadow-xl flex flex-col min-h-0">
                        <div class="px-5 py-4 border-b border-slate-200/50 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-2 shrink-0">
                            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="text-xl">💬</span> {{ __('Team Chat') }}
                            </h3>
                            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-white/50 dark:bg-slate-800 px-2 py-1 rounded-md border border-slate-200 dark:border-slate-700">
                                <div class="w-3 h-3 rounded-md" style="background-color: {{ $team->color_hex }};"></div>
                                {{ $team->name }}
                            </div>
                        </div>

                        {{-- Chat Messages Area --}}
                        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-box">
                            <template x-for="msg in chats" :key="msg.id">
                                <div class="flex flex-col max-w-[90%]" :class="msg.is_mine ? 'ml-auto items-end' : 'mr-auto items-start'">
                                    <div class="flex items-baseline gap-2 mb-1 px-1">
                                        <span class="text-[11px] font-bold" :class="msg.is_mine ? 'text-primary-500' : 'text-slate-600 dark:text-slate-400'" x-text="msg.is_mine ? __('You') : msg.user_name"></span>
                                        <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider" x-text="msg.created_at"></span>
                                    </div>
                                    <div class="px-4 py-2.5 rounded-2xl text-[14px] font-medium leading-snug shadow-sm border"
                                         :class="msg.is_mine ? 'bg-primary-500 text-white rounded-tr-sm border-primary-600' : 'bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-tl-sm border-slate-200 dark:border-slate-700'">
                                        <p x-text="msg.message" class="break-words"></p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="chats.length === 0">
                                <div class="h-full flex flex-col items-center justify-center text-center p-6 opacity-60">
                                    <div class="text-4xl mb-3">🤐</div>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('No messages yet') }}</p>
                                    <p class="text-xs font-medium text-slate-400 mt-1">{{ __('Say hi and discuss the strategy with your team!') }}</p>
                                </div>
                            </template>
                        </div>

                        {{-- Chat Input --}}
                        <div class="p-4 border-t border-slate-200/50 dark:border-white/5 bg-slate-50/80 dark:bg-slate-900/80 shrink-0">
                            <form @submit.prevent="sendMessage()" class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" x-model="newMessage" placeholder="{{ __('Type your message...') }}"
                                           class="w-full bg-white dark:bg-black/20 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all font-medium" maxlength="500">
                                </div>
                                <button type="submit" class="btn-primary ripple-btn shrink-0 w-12 h-[42px] rounded-xl flex items-center justify-center shadow-md shadow-primary-500/20 p-0" :disabled="!newMessage.trim()">
                                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        </div>
                    </x-student.card>

                    {{-- Team Members --}}
                    {{-- Skipped team members list as the chat usually provides enough context and screen real estate is limited. If needed, can be re-added as a dropdown --}}
                    
                </div>
            </div>
        </template>
    </div>
</div>

<script>
@php
    $questionData = null;
    if ($currentQuestion) {
        $questionData = [
            'id' => $currentQuestion->id,
            'text' => $currentQuestion->question_text,
            'options' => $currentQuestion->options,
            'time_limit' => $currentQuestion->time_limit_seconds,
            'points' => $currentQuestion->points,
            'index' => $game->current_question_index,
            'total' => $game->questions->count(),
            'team_answered' => $teamAnswered,
            'team_answer' => $teamAnswer ? [
                'selected' => $teamAnswer->selected_option,
                'is_correct' => $teamAnswer->is_correct,
                'points' => $teamAnswer->points_awarded,
            ] : null,
        ];
    }

    $leaderboardData = $leaderboard->map(fn($t) => [
        'name' => $t->name,
        'color' => $t->color_hex,
        'score' => $t->score,
        'is_mine' => $t->id === $team->id,
    ])->values();

    $chatsData = $chats->map(fn($c) => [
        'id' => $c->id,
        'user_name' => $c->user->name,
        'message' => $c->message,
        'created_at' => $c->created_at->diffForHumans(null, true, true), // Short format e.g. "1m ago"
        'is_mine' => $c->user_id === auth()->id(),
    ])->values();
@endphp

// Include alpine transitions/zoom
tailwind.config = {
  theme: {
    extend: {
      animation: {
        'zoom-in': 'zoomIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
      },
      keyframes: {
        zoomIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        }
      }
    }
  }
}

function gameRoom() {
    return {
        gameStatus: '{{ $game->status }}',
        question: @json($questionData),
        timeRemaining: {{ $timeRemaining }},
        isCaptain: {{ $participant->is_captain ? 'true' : 'false' }},
        captainName: '{{ $team->captain?->user?->name ?? "..." }}',
        teamScore: {{ $team->score }},
        teamAnswered: {{ $teamAnswered ? 'true' : 'false' }},
        leaderboard: @json($leaderboardData),
        chats: @json($chatsData),
        newMessage: '',
        pollInterval: null,
        timerInterval: null,
        lastChatId: 0,

        startPolling() {
            this.pollInterval = setInterval(() => this.poll(), 3000);
            this.timerInterval = setInterval(() => {
                if (this.timeRemaining > 0 && this.gameStatus === 'active') {
                    this.timeRemaining--;
                }
            }, 1000);
            if (this.chats.length > 0) {
                this.lastChatId = this.chats[this.chats.length - 1].id;
            }
            // initial scroll
            this.$nextTick(() => {
                const box = document.getElementById('chat-box');
                if (box) box.scrollTop = box.scrollHeight;
            });
        },

        async poll() {
            try {
                const res = await fetch('{{ route("student.games.poll", $game) }}');
                const data = await res.json();
                this.gameStatus = data.status;
                this.isCaptain = data.is_captain;
                this.captainName = data.captain_name || '...';
                this.teamScore = data.team_score;
                this.leaderboard = data.leaderboard;
                if (data.question) {
                    if (!this.question || this.question.index !== data.question.index) {
                        this.teamAnswered = false;
                    }
                    this.question = data.question;
                    this.timeRemaining = data.time_remaining;
                    this.teamAnswered = data.question.team_answered;
                } else {
                    this.question = null;
                }
                if (data.chats && data.chats.length > 0) {
                    const newChats = data.chats.filter(c => c.id > this.lastChatId);
                    if (newChats.length > 0) {
                        this.chats.push(...newChats);
                        this.lastChatId = newChats[newChats.length - 1].id;
                        this.$nextTick(() => {
                            const box = document.getElementById('chat-box');
                            if (box) box.scrollTop = box.scrollHeight;
                        });
                    }
                }
            } catch (e) {
                console.error('Poll error:', e);
            }
        },

        async submitAnswer(option) {
            if (!this.isCaptain || this.teamAnswered) return;
            try {
                const res = await fetch('{{ route("student.games.submit-answer", $game) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ question_id: this.question.id, selected_option: option }),
                });
                const data = await res.json();
                if (data.success) {
                    this.teamAnswered = true;
                    this.question.team_answered = true;
                    this.question.team_answer = { selected: option, is_correct: data.is_correct, points: data.points_awarded };
                    this.teamScore = data.team_score;
                } else {
                    if (window.showNotification) window.showNotification(data.error || '{{ __("Error occurred") }}', 'error');
                }
            } catch (e) {
                console.error('Submit error:', e);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;
            const msg = this.newMessage;
            this.newMessage = '';
            try {
                const res = await fetch('{{ route("student.games.send-chat", $game) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: msg }),
                });
                const data = await res.json();
                if (data.success) {
                    data.chat.is_mine = true;
                    // format time roughly as "now"
                    data.chat.created_at = '{{ __("Just now") }}';
                    this.chats.push(data.chat);
                    this.lastChatId = data.chat.id;
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-box');
                        if (box) box.scrollTop = box.scrollHeight;
                    });
                }
            } catch (e) {
                console.error('Chat error:', e);
            }
        },

        destroy() {
            if (this.pollInterval) clearInterval(this.pollInterval);
            if (this.timerInterval) clearInterval(this.timerInterval);
        },
    };
}
</script>
<style>
/* Custom Scrollbar for chat area */
#chat-box::-webkit-scrollbar {
  width: 6px;
}
#chat-box::-webkit-scrollbar-track {
  background: transparent;
}
#chat-box::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
  border-radius: 10px;
}
.dark #chat-box::-webkit-scrollbar-thumb {
  background-color: rgba(75, 85, 99, 0.5);
}
</style>
@endsection





