@extends('layouts.app')

@section('title', __('Dashboard') . ' — ' . config('app.name'))

@section('content')
<div class="pt-8 pb-12 sm:py-12 relative min-h-screen z-10 px-3 sm:px-0">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4 sm:space-y-8">

        {{-- ─── HERO SECTION ─── --}}
        <div class="relative glass-card overflow-hidden rounded-2xl sm:rounded-[2rem] p-4 sm:p-8 md:p-12" data-aos="fade-down">
            {{-- Aesthetic Background Elements --}}
            <div class="absolute top-0 right-0 p-12 opacity-10 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <svg width="400" height="400" viewBox="0 0 24 24" fill="none" class="text-white">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 via-transparent to-accent-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-8">
                {{-- Left: Welcome Text --}}
                <div class="w-full md:w-3/5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 sm:py-1.5 rounded-full bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 text-amber-500 text-xs sm:text-sm font-bold mb-3 sm:mb-6 hover:scale-105 transition-transform backdrop-blur-md shadow-lg shadow-amber-500/10">
                        <span class="animate-pulse">🔥</span> {{ $stats['current_streak'] ?? 0 }} {{ __('Day Streak') }}
                    </div>
                    
                    <h1 class="text-xl sm:text-3xl md:text-5xl font-extrabold mb-2 sm:mb-4 leading-tight tracking-tight text-slate-900 dark:text-white drop-shadow-sm">
                        {{ __('Welcome back,') }}<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500 filter drop-shadow-md">
                            {{ explode(' ', $user->name)[0] }}!
                        </span>
                    </h1>
                    
                    <p class="text-xs sm:text-base md:text-lg text-slate-600 dark:text-slate-300 mb-4 sm:mb-8 max-w-xl leading-relaxed font-medium">
                        {{ __('Ready to crush your goals today? Pick up where you left off or challenge others in the arena.') }}
                    </p> 
                    
                    <div class="flex flex-row gap-2 sm:gap-4">
                        <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-3 sm:px-6 md:px-8 py-2.5 sm:py-3.5 rounded-xl shadow-xl shadow-primary-500/30 text-xs sm:text-sm md:text-base flex-1 md:flex-none text-center justify-center font-bold whitespace-nowrap">
                            {{ __('Resume Learning') }}
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-1 sm:ml-2 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('student.battle.index') }}" class="btn-secondary px-3 sm:px-6 md:px-8 py-2.5 sm:py-3.5 rounded-xl border-2 border-accent-500/30 text-accent-600 dark:text-accent-400 hover:bg-accent-500/10 hover:border-accent-500 font-bold flex-1 md:flex-none text-center justify-center transition-all bg-white/5 backdrop-blur-md text-xs sm:text-sm md:text-base whitespace-nowrap">
                            ⚔️ {{ __('Enter Battle') }}
                        </a>
                    </div>
                </div>
                
                {{-- Right: Rank Badge (3D Effect) --}}
                <div class="w-full md:w-2/5 hidden sm:flex justify-center md:justify-end perspective-1000">
                    <div class="relative w-40 h-40 sm:w-64 sm:h-64 group transform-gpu transition-transform duration-700 hover:rotate-y-12 hover:-rotate-x-12 cursor-pointer">
                        {{-- Glow --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-500 to-accent-500 rounded-full blur-3xl opacity-30 group-hover:opacity-50 animate-pulse transition-opacity"></div>
                        
                        {{-- Metal Circle --}}
                        <div class="absolute inset-2 rounded-full border border-white/20 dark:border-white/10 shadow-2xl overflow-hidden glass-card bg-gradient-to-br from-white/40 to-white/5 dark:from-white/10 dark:to-white/0 flex flex-col items-center justify-center p-8 text-center backdrop-blur-xl">
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer pointer-events-none"></div>
                            
                            <div class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mb-1 sm:mb-2">{{ __('Current Level') }}</div>
                            <div class="text-3xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-primary-600 to-accent-500 drop-shadow-lg mb-1 sm:mb-2">
                                {{ $stats['total_enrollments'] > 0 ? 'PRO' : 'ROOKIE' }}
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/10 dark:bg-black/30 text-sm font-mono text-primary-600 dark:text-primary-400 font-bold border border-primary-500/20">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 01.832.445l2.454 3.682 4.39.638a1 1 0 01.554 1.706l-3.176 3.097.75 4.373a1 1 0 01-1.451 1.054L10 14.73l-3.927 2.064a1 1 0 01-1.451-1.054l.75-4.373-3.176-3.097a1 1 0 01.554-1.706l4.39-.638 2.454-3.682A1 1 0 0110 2z" clip-rule="evenodd"/></svg>
                                {{ __('Rank') }} #{{ $stats['rank'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STATS GRID ─── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
            @php
                $statCards = [
                    ['label' => __('Total XP'), 'value' => $stats['total_points'] ?? 0, 'icon' => '⚡', 'color' => 'amber', 'desc' => __('Lifetime Pts'), 'bg' => 'from-amber-500/10 to-transparent'],
                    ['label' => __('Courses'), 'value' => $stats['active_courses'] ?? 0, 'icon' => '📚', 'color' => 'blue', 'desc' => __('in Progress'), 'bg' => 'from-blue-500/10 to-transparent'],
                    ['label' => __('Certificates'), 'value' => $stats['certificates_earned'] ?? 0, 'icon' => '🏅', 'color' => 'emerald', 'desc' => __('Earned'), 'bg' => 'from-emerald-500/10 to-transparent'],
                    ['label' => __('Accuracy'), 'value' => $dailyQuestionStats['accuracy'] ?? 0, 'suffix' => '%', 'icon' => '🎯', 'color' => 'purple', 'desc' => __('Overall'), 'bg' => 'from-purple-500/10 to-transparent'],
                ];
            @endphp

            @foreach($statCards as $card)
                <div class="glass-card relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border-t-2 border-t-{{ $card['color'] }}-500/50" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $card['bg'] }} opacity-50"></div>
                    <div class="relative p-3 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl bg-{{ $card['color'] }}-500/20 flex items-center justify-center text-lg sm:text-2xl shadow-inner border border-{{ $card['color'] }}-500/30 group-hover:scale-110 transition-transform duration-300">
                                {{ $card['icon'] }}
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded bg-slate-900/5 dark:bg-white/5 text-slate-500 dark:text-slate-400 group-hover:bg-{{ $card['color'] }}-500/20 group-hover:text-{{ $card['color'] }}-600 dark:group-hover:text-{{ $card['color'] }}-400 transition-colors uppercase tracking-wider">
                                {{ $card['desc'] }}
                            </span>
                        </div>
                        <div class="mt-1 sm:mt-2">
                            <div class="flex items-baseline gap-1">
                                <h3 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white leading-none counter tracking-tight" data-target="{{ $card['value'] }}">0</h3>
                                @if(isset($card['suffix']))
                                    <span class="text-xl font-bold text-slate-400 dark:text-slate-500">{{ $card['suffix'] }}</span>
                                @endif
                            </div>
                            <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $card['label'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ─── PENDING PAYMENTS ALERT ─── --}}
        @if(isset($pendingPayments) && $pendingPayments->count() > 0)
            <div class="space-y-4 mb-2" data-aos="fade-up" data-aos-delay="150">
                @foreach($pendingPayments as $payment)
                    <div class="glass-card bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 p-5 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-transparent pointer-events-none"></div>
                        <div class="flex items-center gap-4 relative z-10 w-full sm:w-auto">
                            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-2xl shrink-0 text-amber-600 dark:text-amber-500 shadow-inner">
                                ⏳
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900 dark:text-white text-lg">{{ __('Pending Payment') }}</h4>
                                <p class="text-sm text-slate-600 dark:text-amber-200/80 font-medium">
                                    {{ __('You have an incomplete checkout for') }} <span class="font-bold text-slate-800 dark:text-white">"{{ $payment->course->title }}"</span>.
                                </p>
                            </div>
                        </div>
                        <div class="relative z-10 w-full sm:w-auto flex-shrink-0 mt-4 sm:mt-0">
                            <a href="{{ route('student.courses.enroll', $payment->course) }}" class="btn-primary w-full sm:w-auto px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white border-0 shadow-lg shadow-amber-500/30 font-bold whitespace-nowrap inline-flex items-center justify-center gap-2">
                                💳 {{ __('Complete Purchase') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ─── QUICK ACTIONS ROW ─── --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('student.forum.index') }}" class="glass-card p-4 flex items-center justify-center gap-3 hover:bg-primary-500 hover:text-white group transition-colors shadow-sm">
                <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">💬</span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Community Forum') }}</span>
            </a>
            <a href="{{ route('student.games.index') }}" class="glass-card p-4 flex items-center justify-center gap-3 hover:bg-accent-500 hover:text-white group transition-colors shadow-sm">
                <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">🎮</span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Mini Games') }}</span>
            </a>
            <a href="{{ route('student.referrals.index') }}" class="glass-card p-4 flex items-center justify-center gap-3 hover:bg-emerald-500 hover:text-white group transition-colors shadow-sm">
                <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">🎁</span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Invite Friends') }}</span>
            </a>
            <a href="{{ route('student.telegram.guide') }}" class="glass-card p-4 flex items-center justify-center gap-3 hover:bg-[#0088cc] hover:text-white group transition-colors shadow-sm">
                <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform">🤖</span>
                <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('Telegram Bot') }}</span>
            </a>
            @if(auth()->user()->enrollments()->exists())
                <a href="{{ route('student.testimonial.edit') }}" class="glass-card p-4 flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white group transition-colors shadow-sm">
                    <span class="w-8 h-8 rounded-full bg-amber-500/10 group-hover:bg-white/15 text-amber-500 group-hover:text-white flex items-center justify-center transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.148 3.531a1 1 0 00.95.69h3.712c.969 0 1.371 1.24.588 1.81l-3.003 2.182a1 1 0 00-.364 1.118l1.147 3.531c.3.922-.755 1.688-1.539 1.118l-3.004-2.182a1 1 0 00-1.175 0l-3.004 2.182c-.784.57-1.838-.196-1.539-1.118l1.148-3.531a1 1 0 00-.364-1.118L2.65 8.958c-.783-.57-.38-1.81.588-1.81h3.712a1 1 0 00.95-.69l1.149-3.531z"></path>
                        </svg>
                    </span>
                    <span class="font-bold text-xs sm:text-sm text-slate-700 dark:text-slate-200 group-hover:text-white">{{ __('شارك رأيك') }}</span>
                </a>
            @endif
        </div>

        {{-- ─── MAIN CONTENT SPLIT ─── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column (2/3) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Continue Learning --}}
                <div class="glass-card relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 to-accent-500"></div>
                    <div class="p-6 md:p-8 flex items-center justify-between border-b border-slate-200 dark:border-white/5">
                        <h3 class="font-black text-2xl text-slate-900 dark:text-white flex items-center gap-3">
                            <span>📚</span> {{ __('Continue Learning') }}
                        </h3>
                        <a href="{{ route('student.courses.index') }}" class="btn-ghost btn-sm font-bold text-primary-500 bg-primary-500/10 hover:bg-primary-500 hover:text-white transition-colors rounded-lg">
                            {{ __('Browse Catalog') }}
                        </a>
                    </div>
                    
                    <div class="p-6 md:p-8 space-y-5">
                        @forelse($activeEnrollments->take(3) as $enrollment)
                            <div class="group relative rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 p-5 hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 flex flex-col sm:flex-row gap-6 items-center">
                                
                                {{-- Icon 3D --}}
                                <div class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-3xl shadow-lg border border-white/50 dark:border-white/10 group-hover:rotate-6 transition-transform">
                                    {{ substr($enrollment->course->title, 0, 1) }}
                                </div>
                                
                                <div class="flex-1 w-full text-center sm:text-left">
                                    <h4 class="font-bold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-primary-500 transition-colors">
                                        {{ $enrollment->course->title }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5">
                                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : __('Just started') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5">
                                            <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            {{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }} {{ __('Lessons') }}
                                        </span>
                                    </div>
                                    
                                    {{-- Progress Bar --}}
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-200/50 dark:border-white/5 shadow-inner relative">
                                            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary-500 to-accent-500 rounded-full w-0 transition-all duration-1000 ease-out" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-12 text-right">{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                </div>
                                
                                <div class="shrink-0 w-full sm:w-auto">
                                    <a href="{{ route('student.courses.learn', $enrollment->course) }}" class="flex sm:inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black font-bold shadow-lg shadow-slate-900/20 dark:shadow-white/20 hover:bg-primary-600 dark:hover:bg-primary-500 dark:hover:text-white transition-all transform group-hover:scale-105">
                                        {{ __('Play') }}
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 border-2 border-dashed border-slate-300 dark:border-white/20 rounded-2xl bg-white/50 dark:bg-black/20 backdrop-blur-sm">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">🧩</div>
                                <h3 class="text-2xl text-slate-900 dark:text-white font-extrabold mb-2">{{ __('No Active Courses') }}</h3>
                                <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">{{ __('You haven\'t started any courses yet. Browse our catalog and start learning today!') }}</p>
                                <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-8 shadow-lg shadow-primary-500/30">
                                    {{ __('Explore Courses') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Activity Chart --}}
                <div class="glass-card p-6 md:p-8 relative overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="mb-8 flex items-center justify-between relative z-10">
                        <h3 class="font-black text-2xl text-slate-900 dark:text-white flex items-center gap-3">
                            <span>📈</span> {{ __('Learning Activity') }}
                        </h3>
                    </div>
                    <div class="h-72 w-full relative z-10">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Right Column (1/3) --}}
            <div class="space-y-8">
                
                {{-- Next Up --}}
                @if($nextLesson)
                <div class="relative rounded-2xl p-1 shadow-2xl overflow-hidden group" data-aos="fade-left" data-aos-delay="500">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 via-accent-500 to-primary-500 background-size-200 animate-gradient-slow pointer-events-none"></div>
                    <div class="relative bg-white dark:bg-[#001c2e] rounded-[15px] p-6 h-full flex flex-col justify-between z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                                <span class="text-xs font-black uppercase tracking-widest text-primary-600 dark:text-primary-400">{{ __('Up Next') }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3 leading-tight">{{ $nextLesson->title }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 line-clamp-3 leading-relaxed">{{ Str::limit($nextLesson->description, 100) }}</p>
                        </div>
                        <a href="{{ route('student.lessons.show', [$nextLesson->course, $nextLesson]) }}" class="block w-full text-center py-4 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white font-bold hover:shadow-lg hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all">
                            {{ __('Start Lesson') }}
                        </a>
                    </div>
                </div>
                @endif

                {{-- Leaderboard Widget --}}
                <div class="glass-card" data-aos="fade-left" data-aos-delay="600">
                    <div class="p-6 border-b border-slate-200 dark:border-white/5 flex justify-between items-center">
                        <h3 class="font-black text-xl text-slate-900 dark:text-white flex items-center gap-2">
                            <span>🏆</span> {{ __('Top Rank') }}
                        </h3>
                        <a href="{{ route('student.leaderboard') }}" class="btn-ghost btn-sm text-xs font-bold text-slate-500">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="p-4 space-y-2">
                        @foreach($topLearners as $index => $learner)
                        @php
                            $isTop3 = $index < 3;
                            $bgClass = $isTop3 ? 'bg-gradient-to-r from-slate-50 to-white dark:from-white/10 dark:to-white/5 border border-slate-200 dark:border-white/10' : 'hover:bg-slate-50 dark:hover:bg-white/5';
                            $numberColor = $index == 0 ? 'text-amber-500' : ($index == 1 ? 'text-slate-400' : ($index == 2 ? 'text-amber-700 dark:text-amber-600' : 'text-slate-400 dark:text-slate-600'));
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl {{ $bgClass }} transition-colors">
                            <div class="w-6 font-black text-lg text-center {{ $numberColor }}">{{ $index + 1 }}</div>
                            <div class="relative w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-sm font-bold text-slate-700 dark:text-white shadow-inner">
                                {{ substr($learner->name, 0, 1) }}
                                @if($index == 0) <span class="absolute -top-2 -right-1 text-sm filter drop-shadow-sm">👑</span> @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ explode(' ', $learner->name)[0] }}</div>
                            </div>
                            <div class="text-sm font-black text-primary-600 dark:text-primary-400">{{ number_format($learner->total_points) }}</div>
                        </div>
                        @endforeach
                        
                        {{-- Current User Rank --}}
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-white/10">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-primary-500/10 border border-primary-500/30">
                                <div class="w-6 font-black text-lg text-center text-primary-500">{{ $stats['rank'] }}</div>
                                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center text-sm text-white font-bold shadow-md shadow-primary-500/30">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ __('You') }}</div>
                                </div>
                                <div class="text-sm font-black text-primary-600 dark:text-primary-400">{{ number_format($stats['total_points']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daily Tip / Recommendation --}}
                <div class="glass-card overflow-hidden bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 p-6" data-aos="fade-left" data-aos-delay="700">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-500/20 flex items-center justify-center text-2xl shrink-0 text-indigo-500">
                            💡
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-lg mb-2">{{ __('Pro Tip') }}</h4>
                            <p class="text-sm text-slate-600 dark:text-indigo-200/80 leading-relaxed font-medium">
                                {{ __('Consistency is key! Spending just 30 minutes a day practicing yields better results than 2 hours once a week.') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .background-size-200 { background-size: 200% 200%; }
    .animate-gradient-slow { animation: gradient 8s ease infinite; }
    
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate Counters (0 to Value)
        document.querySelectorAll('.counter').forEach(counter => {
            const target = +counter.getAttribute('data-target');
            if (window.animateCounter) {
                window.animateCounter(counter, target, 2500);
            } else {
                counter.innerText = target; 
            }
        });

        // Initialize Activity Chart
        const ctx = document.getElementById('activityChart');
        if (ctx && typeof Chart !== 'undefined') {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#475569';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const tooltipBg = isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)';
            const tooltipText = isDark ? '#fff' : '#0f172a';
            
            // Create rich gradient fill
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.5)'); // top
            gradient.addColorStop(1, 'rgba(14, 165, 233, 0.01)'); // bottom

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels'] ?? []) !!},
                    datasets: [{
                        label: 'XP Gained',
                        data: {!! json_encode($chartData['data'] ?? []) !!},
                        borderColor: '#0ea5e9',
                        backgroundColor: gradient,
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: isDark ? '#0f172a' : '#ffffff',
                        pointBorderColor: '#0ea5e9',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#0ea5e9',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' XP';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: gridColor, borderDash: [4,4], drawBorder: false }, 
                            ticks: { color: textColor, padding: 10, font: {family: "'Outfit', sans-serif"} } 
                        },
                        x: { 
                            grid: { display: false, drawBorder: false }, 
                            ticks: { color: textColor, padding: 10, font: {family: "'Outfit', sans-serif"} } 
                        }
                    },
                    interaction: { intersect: false, mode: 'index' },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
    });

    if (!window.animateCounter) {
        window.animateCounter = (obj, end, duration) => {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                // add easing out quad
                const easeOutQuad = progress * (2 - progress);
                obj.innerHTML = Math.floor(easeOutQuad * (end - 0) + 0);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    }
</script>
@endpush
@endsection
