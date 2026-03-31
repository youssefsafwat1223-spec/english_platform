@extends('layouts.app')

@section('title', __('Points History') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative overflow-hidden">

    <div class="student-container relative z-10">
        {{-- Header Section --}}
        <x-student.page-header
            title="{{ __('Points') }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500'>{{ __('History') }}</span>"
            subtitle="{{ __('Track points earned from your activities.') }}"
            badge="{{ __('Activity') }}"
            badgeIcon='<svg class="w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
            badgeColor="primary"
            mb="mb-8"
        >
            <x-slot name="actions">
                <a href="{{ route('student.profile.show') }}" class="btn-ghost flex items-center justify-center gap-2 px-6 py-3 font-bold rounded-xl w-full sm:w-auto transition-colors shadow-sm bg-white/10 hover:bg-white/20 dark:bg-slate-800/50 dark:hover:bg-slate-700/50 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 group">
                    <svg class="w-5 h-5 mr-0 rtl:ml-2 rtl:-mr-2 group-hover:-translate-x-1 rtl:group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Profile') }}
                </a>
            </x-slot>
        </x-student.page-header>

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
                <x-student.card padding="p-6" class="text-center group" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">{{ $s['icon'] }}</div>
                    <div class="text-3xl font-extrabold text-{{ $s['color'] }}-500">{{ $s['value'] }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $s['label'] }}</div>
                </x-student.card>
            @endforeach
        </div>

        {{-- History Table --}}
        <x-student.card padding="p-0" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>{{ __('Activity') }}</th>
                            <th>{{ __('Points') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $item->description ?? ucfirst(str_replace('_', ' ', $item->activity_type)) }}</div>
                                </td>
                                <td><span class="font-bold text-emerald-500">+{{ $item->points_earned }}</span></td>
                                <td class="text-slate-500 dark:text-slate-400">{{ $item->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12">
                                    <x-student.empty-state
                                        title="{{ __('No points history yet.') }}"
                                        message="{{ __('Complete lessons and activities to start earning points.') }}"
                                    >
                                        <x-slot name="icon">
                                            <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </x-slot>
                                    </x-student.empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">{{ $history->links() }}</div>
        </x-student.card>
    </div>
</div>
@endsection






