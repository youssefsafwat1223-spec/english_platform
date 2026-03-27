@extends('layouts.app')

@section('title', __('Notifications') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ─── HEADER SECTION ─── --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>🔔</span> {{ __('Notifications') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Your') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Updates') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium">
                        {{ __('Stay on top of your learning progress, achievements, and account activity.') }}
                    </p>
                </div>

                @if(isset($unreadCount) && $unreadCount > 0)
                    <form action="{{ route('student.notifications.mark-all-read') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-violet-500/25 flex items-center gap-2 font-bold bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-500 hover:to-violet-400 border-none text-white transition-all transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Mark All Read') }}
                            <span class="ml-2 px-2 py-0.5 rounded-md bg-white/20 text-xs shadow-inner">{{ $unreadCount }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- ─── NOTIFICATION LIST ─── --}}
        <div class="space-y-4">
            @forelse($notifications as $notification)
                @php
                    $isUnread = !$notification->is_read;
                    $typeIcons = [
                        'enrollment' => ['icon' => '🎓', 'color' => 'blue'],
                        'quiz' => ['icon' => '📝', 'color' => 'emerald'],
                        'certificate' => ['icon' => '🏆', 'color' => 'amber'],
                        'payment' => ['icon' => '💳', 'color' => 'emerald'],
                        'forum' => ['icon' => '💬', 'color' => 'primary'],
                        'course' => ['icon' => '📚', 'color' => 'indigo'],
                        'achievement' => ['icon' => '⭐', 'color' => 'amber'],
                        'default' => ['icon' => '🔔', 'color' => 'violet'],
                    ];
                    $typeData = $typeIcons[$notification->notification_type ?? 'default'] ?? $typeIcons['default'];
                    $icon = $typeData['icon'];
                    $color = $typeData['color'];
                @endphp
                
                <div class="glass-card relative overflow-hidden group transition-all duration-300 {{ $isUnread ? 'hover:-translate-y-1 shadow-lg shadow-'.$color.'-500/5 border-l-4 border-l-'.$color.'-500' : 'opacity-80 hover:opacity-100 hover:shadow-md' }}"
                     data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 400) }}">
                    
                    @if($isUnread)
                        <div class="absolute inset-0 bg-gradient-to-r from-{{ $color }}-500/5 to-transparent pointer-events-none"></div>
                    @endif

                    <div class="p-5 md:p-6 flex flex-col md:flex-row gap-5 items-start md:items-center relative z-10">
                        {{-- Icon --}}
                        <div class="shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner transition-transform group-hover:scale-110 group-hover:rotate-6
                                    {{ $isUnread ? 'bg-gradient-to-br from-'.$color.'-500/20 to-'.$color.'-500/5 border border-'.$color.'-500/30 text-'.$color.'-600 dark:text-'.$color.'-400' : 'bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/5 text-slate-500' }}">
                            {{ $icon }}
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-base font-bold truncate {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $notification->title ?? __('System Notification') }}
                                </h2>
                                @if($isUnread)
                                    <span class="shrink-0 flex h-2.5 w-2.5 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $color }}-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-{{ $color }}-500"></span>
                                    </span>
                                @endif
                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap ml-auto md:ml-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed {{ $isUnread ? 'text-slate-600 dark:text-slate-300 font-medium' : 'text-slate-500 dark:text-slate-400' }}">
                                {{ $notification->message }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="shrink-0 flex items-center gap-3 w-full md:w-auto justify-end mt-2 md:mt-0 pt-3 md:pt-0 border-t md:border-0 border-slate-200/50 dark:border-white/5">
                            @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" class="btn-ghost btn-sm font-bold {{ $isUnread ? 'text-'.$color.'-600 dark:text-'.$color.'-400 hover:bg-'.$color.'-500/10' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                                    {{ __('View') }} &rarr;
                                </a>
                            @endif

                            @if($isUnread)
                                <form action="{{ route('student.notifications.mark-as-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 hover:bg-{{ $color }}-500 hover:text-white transition-colors" title="{{ __('Mark as read') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="glass-card text-center py-20" data-aos="fade-up">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center mx-auto mb-6 text-5xl shadow-inner border border-white/50 dark:border-white/5">
                        �
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ __('All Caught Up!') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 mx-auto max-w-sm">{{ __('You have no active notifications at the moment. We\'ll let you know when something new happens.') }}</p>
                </div>
            @endforelse
        </div>

        {{-- ─── PAGINATION ─── --}}
        @if($notifications->hasPages())
        <div class="glass-card p-4 flex justify-center mt-8">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
