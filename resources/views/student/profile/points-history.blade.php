@extends('layouts.app')

@section('title', __('Points History') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-8" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-bold mb-4 shadow-sm">
                        <svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('Activity') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Points') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-primary-500">{{ __('History') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl">
                        {{ __('Track points earned from your activities.') }}
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('student.profile.show') }}" class="inline-flex justify-center items-center px-6 py-3 bg-white/10 hover:bg-white/20 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-white font-bold rounded-xl transition-all duration-300 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 group">
                        <svg class="w-5 h-5 mr-2 rtl:ml-2 rtl:mr-0 group-hover:-translate-x-1 rtl:group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ __('Back to Profile') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $pointStats = [
                    ['icon' => '⚡', 'value' => $stats['total_points'], 'label' => 'Total Points', 'color' => 'primary'],
                    ['icon' => '📅', 'value' => $stats['this_week'], 'label' => 'This Week', 'color' => 'emerald'],
                    ['icon' => '📆', 'value' => $stats['this_month'], 'label' => 'This Month', 'color' => 'blue'],
                ];
            @endphp
            @foreach($pointStats as $s)
                <div class="glass-card p-6 text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">{{ $s['icon'] }}</div>
                    <div class="text-3xl font-extrabold text-{{ $s['color'] }}-500">{{ $s['value'] }}</div>
                    <div class="text-sm font-medium" style="color: var(--color-text-muted);">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- History Table --}}
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Points</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                            <tr>
                                <td>
                                    <div class="font-semibold" style="color: var(--color-text);">{{ $item->description ?? ucfirst(str_replace('_', ' ', $item->activity_type)) }}</div>
                                </td>
                                <td><span class="font-bold text-emerald-500">+{{ $item->points_earned }}</span></td>
                                <td style="color: var(--color-text-muted);">{{ $item->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8" style="color: var(--color-text-muted);">No points history yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $history->links() }}</div>
        </div>
    </div>
</div>
@endsection
