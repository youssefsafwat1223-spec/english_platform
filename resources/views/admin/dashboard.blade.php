@extends('layouts.admin')

@section('title', __('Admin Dashboard'))

@section('content')
<div class="py-2 relative overflow-hidden">
    {{-- Ambient Glow handled in layout --}}
    
    <div class="max-w-7xl mx-auto relative z-10">

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4" data-aos="fade-down">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 dark:text-white">
                    {{ __('Welcome back,') }}<span class="bg-gradient-to-r from-primary-500 to-cyan-400 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Here\'s what\'s happening on your platform today.') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.settings.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all hover:scale-105"
                   style="background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.2); color: #a78bfa;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Settings') }}
                </a>
            </div>
        </div>

        {{-- Main KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
                $kpis = [
                    [
                        'label' => 'Total Students',
                        'value' => number_format($stats['total_students']),
                        'sub' => '+' . $stats['new_students_this_month'] . ' this month',
                        'subPositive' => true,
                        'icon' => '👥',
                        'gradient' => 'from-violet-500/15 to-violet-600/5',
                        'border' => 'rgba(139, 92, 246, 0.15)',
                        'accent' => '#a78bfa',
                    ],
                    [
                        'label' => 'Active Students',
                        'value' => number_format($stats['active_students']),
                        'sub' => 'Last 7 days',
                        'subPositive' => false,
                        'icon' => '🟢',
                        'gradient' => 'from-emerald-500/15 to-emerald-600/5',
                        'border' => 'rgba(16, 185, 129, 0.15)',
                        'accent' => '#34d399',
                    ],
                    [
                        'label' => 'Total Revenue',
                        'value' => number_format($stats['total_revenue'], 2) . ' ' . __('ر.س'),
                        'sub' => '+' . number_format($stats['revenue_this_month'], 2) . ' ر.س this month',
                        'subPositive' => true,
                        'icon' => '💰',
                        'gradient' => 'from-cyan-500/15 to-cyan-600/5',
                        'border' => 'rgba(6, 182, 212, 0.15)',
                        'accent' => '#22d3ee',
                    ],
                    [
                        'label' => 'Enrollments',
                        'value' => number_format($stats['total_enrollments']),
                        'sub' => $stats['completed_enrollments'] . ' completed',
                        'subPositive' => false,
                        'icon' => '📋',
                        'gradient' => 'from-amber-500/15 to-amber-600/5',
                        'border' => 'rgba(245, 158, 11, 0.15)',
                        'accent' => '#fbbf24',
                    ],
                ];
            @endphp

            @foreach($kpis as $kpi)
                <div class="group rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}"
                     style="background: var(--glass-bg); border: 1px solid {{ $kpi['border'] }}; backdrop-filter: blur(20px);">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-2xl">{{ $kpi['icon'] }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-lg {{ $kpi['subPositive'] ? 'text-emerald-400' : '' }}"
                              style="{{ !$kpi['subPositive'] ? 'color: var(--color-text-muted);' : '' }} background: rgba(0,0,0,0.2);">
                            {{ $kpi['sub'] }}
                        </span>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold mb-1" style="color: {{ $kpi['accent'] }};">{{ $kpi['value'] }}</p>
                    <p class="text-xs font-medium" style="color: var(--color-text-muted);">{{ $kpi['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Quick Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            {{-- Daily Questions --}}
            <div class="rounded-2xl p-5" data-aos="fade-up" data-aos-delay="100"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">📝</span>
                    <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Daily Questions') }}</h3>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold" style="color: #a78bfa;">{{ $dailyQuestionsStats['sent_today'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Sent') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold text-emerald-400">{{ $dailyQuestionsStats['answered_today'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Answered') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold" style="color: #22d3ee;">{{ $dailyQuestionsStats['correct_today'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Correct') }}</div>
                    </div>
                </div>
            </div>

            {{-- Pronunciation --}}
            <div class="rounded-2xl p-5" data-aos="fade-up" data-aos-delay="200"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">🎤</span>
                    <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Pronunciation') }}</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold" style="color: #c084fc;">{{ $pronunciationStats['total_attempts'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Total Attempts') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold text-emerald-400">{{ round($pronunciationStats['average_score'] ?? 0) }}%</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Avg Score') }}</div>
                    </div>
                </div>
            </div>

            {{-- Forum --}}
            <div class="rounded-2xl p-5" data-aos="fade-up" data-aos-delay="300"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">💬</span>
                    <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Forum') }}</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold" style="color: #22d3ee;">{{ $forumStats['total_topics'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Topics') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background: rgba(0,0,0,0.15);">
                        <div class="text-xl font-extrabold {{ $forumStats['pending_reports'] > 0 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $forumStats['pending_reports'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-muted);">{{ __('Reports') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="rounded-2xl overflow-hidden" data-aos="fade-up"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid var(--glass-border);">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📈</span>
                        <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Revenue Overview') }}</h3>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-lg" style="background: rgba(0,0,0,0.2); color: var(--color-text-muted);">{{ __('Last 30 days') }}</span>
                </div>
                <div class="p-5">
                    <div class="h-[280px]"><canvas id="revenueChart"></canvas></div>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid var(--glass-border);">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📊</span>
                        <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('New Enrollments') }}</h3>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-lg" style="background: rgba(0,0,0,0.2); color: var(--color-text-muted);">{{ __('Last 30 days') }}</span>
                </div>
                <div class="p-5">
                    <div class="h-[280px]"><canvas id="enrollmentChart"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Recent Enrollments --}}
            <div class="rounded-2xl overflow-hidden" data-aos="fade-up"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid var(--glass-border);">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🎓</span>
                        <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Recent Enrollments') }}</h3>
                    </div>
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold transition-colors" style="color: #a78bfa;">{{ __('View All →') }}</a>
                </div>
                <div class="max-h-[400px] overflow-y-auto">
                    @forelse($recentEnrollments as $enrollment)
                        <div class="px-5 py-3.5 flex items-center justify-between transition-colors hover:bg-white/[0.02]" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border);' : '' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0" style="background: linear-gradient(135deg, #8b5cf6, #06b6d4);">
                                    {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold truncate" style="color: var(--color-text);">{{ $enrollment->user->name }}</p>
                                    <p class="text-xs truncate" style="color: var(--color-text-muted);">{{ $enrollment->course->title }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-medium px-2 py-1 rounded-lg shrink-0 ml-2" style="background: rgba(139, 92, 246, 0.1); color: #a78bfa;">{{ $enrollment->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm" style="color: var(--color-text-muted);">{{ __('No recent enrollments') }}</div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Payments --}}
            <div class="rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100"
                 style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
                <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid var(--glass-border);">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💳</span>
                        <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Recent Payments') }}</h3>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold transition-colors" style="color: #34d399;">{{ __('View All →') }}</a>
                </div>
                <div class="max-h-[400px] overflow-y-auto">
                    @forelse($recentPayments as $payment)
                        <div class="px-5 py-3.5 flex items-center justify-between transition-colors hover:bg-white/[0.02]" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border);' : '' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0" style="background: linear-gradient(135deg, #10b981, #059669);">💰</div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold truncate" style="color: var(--color-text);">{{ $payment->user->name }}</p>
                                    <p class="text-xs truncate" style="color: var(--color-text-muted);">{{ $payment->course->title }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <p class="text-sm font-bold text-emerald-400">+{{ number_format($payment->final_amount, 2) }} {{ __('ر.س') }}</p>
                                <p class="text-[10px]" style="color: var(--color-text-muted);">{{ $payment->paid_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm" style="color: var(--color-text-muted);">{{ __('No recent payments') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Popular Courses --}}
        <div class="rounded-2xl overflow-hidden mb-8" data-aos="fade-up"
             style="background: var(--glass-bg); border: 1px solid var(--glass-border); backdrop-filter: blur(20px);">
            <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid var(--glass-border);">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🏆</span>
                    <h3 class="text-sm font-bold" style="color: var(--color-text);">{{ __('Top Courses') }}</h3>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold" style="color: #fbbf24;">{{ __('Manage Courses →') }}</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <th class="text-left px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">#</th>
                            <th class="text-left px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">{{ __('Course') }}</th>
                            <th class="text-center px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">{{ __('Students') }}</th>
                            <th class="text-center px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">{{ __('Revenue') }}</th>
                            <th class="text-center px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">{{ __('Rating') }}</th>
                            <th class="text-center px-6 py-3 text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-muted);">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($popularCourses as $course)
                            <tr class="transition-colors hover:bg-white/[0.02]" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border);' : '' }}">
                                <td class="px-6 py-4">
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-bold" style="background: rgba(139, 92, 246, 0.1); color: #a78bfa;">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold" style="color: var(--color-text);">{{ $course->title }}</p>
                                    <p class="text-xs mt-0.5" style="color: var(--color-text-muted);">{{ number_format($course->price, 2) }} ر.س per student</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold" style="color: var(--color-text);">{{ number_format($course->total_students) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-emerald-400">{{ number_format($course->total_students * $course->price, 2) }} {{ __('ر.س') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($course->average_rating)
                                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-lg" style="background: rgba(245, 158, 11, 0.1);">
                                            <span class="text-xs font-bold text-amber-400">{{ number_format($course->average_rating, 1) }}</span>
                                            <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                    @else
                                        <span class="text-xs" style="color: var(--color-text-muted);">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($course->is_active)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg text-emerald-400" style="background: rgba(16, 185, 129, 0.1);">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>{{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg text-red-400" style="background: rgba(239, 68, 68, 0.1);">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>{{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3" data-aos="fade-up">
            @php
                $quickLinks = [
                    ['label' => 'Students', 'icon' => '👥', 'route' => route('admin.students.index'), 'color' => '#a78bfa'],
                    ['label' => 'Courses', 'icon' => '📚', 'route' => route('admin.courses.index'), 'color' => '#22d3ee'],
                    ['label' => 'Payments', 'icon' => '💳', 'route' => route('admin.payments.index'), 'color' => '#34d399'],
                    ['label' => 'Forum', 'icon' => '💬', 'route' => route('admin.forum.index'), 'color' => '#fbbf24'],
                ];
            @endphp
            @foreach($quickLinks as $link)
                <a href="{{ $link['route'] }}"
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                   style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                    <span class="text-xl">{{ $link['icon'] }}</span>
                    <span class="text-sm font-semibold" style="color: {{ $link['color'] }};">{{ $link['label'] }}</span>
                    <svg class="w-4 h-4 ml-auto" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridColor = 'rgba(255,255,255,0.04)';
    const tickColor = '#64748b';

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx && typeof Chart !== 'undefined') {
        const gradient = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.15)');
        gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($revenueChartData, 'date')) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode(array_column($revenueChartData, 'revenue')) !!},
                    borderColor: '#22d3ee',
                    backgroundColor: gradient,
                    tension: 0.4, fill: true, borderWidth: 2,
                    pointBackgroundColor: '#0f172a',
                    pointBorderColor: '#22d3ee', pointBorderWidth: 2, pointRadius: 0, pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.95)', titleColor: '#94a3b8', bodyColor: '#fff', padding: 12, cornerRadius: 10, borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1,
                        callbacks: { label: ctx => ctx.parsed.y.toFixed(2) + ' ' + __('ر.س') }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { callback: v => v + ' ' + __('ر.س'), color: tickColor, font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 10 }, maxRotation: 0, maxTicksLimit: 8 } }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        });
    }

    // Enrollment Chart
    const enrollCtx = document.getElementById('enrollmentChart');
    if (enrollCtx && typeof Chart !== 'undefined') {
        new Chart(enrollCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($enrollmentChartData, 'date')) !!},
                datasets: [{
                    label: 'Enrollments',
                    data: {!! json_encode(array_column($enrollmentChartData, 'count')) !!},
                    backgroundColor: 'rgba(139, 92, 246, 0.5)',
                    hoverBackgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderRadius: 6, borderSkipped: false,
                    barThickness: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.95)', titleColor: '#94a3b8', bodyColor: '#fff', padding: 12, cornerRadius: 10, borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { stepSize: 1, color: tickColor, font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 10 }, maxRotation: 0, maxTicksLimit: 8 } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
