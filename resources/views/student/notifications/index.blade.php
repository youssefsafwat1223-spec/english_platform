@extends('layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
@endphp

@section('title', ($isArabic ? 'الإشعارات' : 'Notifications') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="student-container max-w-4xl space-y-8">

        {{-- ─── HEADER SECTION ─── --}}
        <x-student.page-header
            title="{{ $isArabic ? 'آخر' : 'Your' }} <span class='text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-accent-500'>{{ $isArabic ? 'التحديثات' : 'Updates' }}</span>"
            subtitle="{{ $isArabic ? 'تابع تقدمك الدراسي وإنجازاتك وأي نشاط جديد على حسابك من مكان واحد.' : 'Stay on top of your learning progress, achievements, and account activity.' }}"
            badge="{{ $isArabic ? 'الإشعارات' : 'Notifications' }}"
            badgeColor="primary"
            badgeIcon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24' aria-hidden='true'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 17h5l-1.41-1.41A2 2 0 0 1 18 14.17V11a6 6 0 1 0-12 0v3.17a2 2 0 0 1-.59 1.42L4 17h5'/><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 17a2 2 0 1 0 4 0'/></svg>"
        >
            <x-slot name="actions">
                @if(isset($unreadCount) && $unreadCount > 0)
                    <form action="{{ route('student.notifications.mark-all-read') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="btn-primary ripple-btn px-6 py-3 rounded-xl shadow-lg shadow-primary-500/25 flex items-center gap-2 font-bold bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 border-none text-white transition-all transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $isArabic ? 'تحديد الكل كمقروء' : 'Mark All Read' }}
                            <span class="ml-2 px-2 py-0.5 rounded-md bg-white/20 text-xs shadow-inner">{{ $unreadCount }}</span>
                        </button>
                    </form>
                @endif
            </x-slot>
        </x-student.page-header>

        {{-- ─── NOTIFICATION LIST ─── --}}
        <div class="space-y-4">
            @forelse($notifications as $notification)
                @php
                    $isUnread = !$notification->is_read;
                    $typeIcons = [
                        'enrollment' => ['icon' => 'academic-cap', 'color' => 'blue'],
                        'quiz' => ['icon' => 'document-text', 'color' => 'emerald'],
                        'certificate' => ['icon' => 'trophy', 'color' => 'amber'],
                        'payment' => ['icon' => 'credit-card', 'color' => 'emerald'],
                        'forum' => ['icon' => 'chat-bubble', 'color' => 'primary'],
                        'course' => ['icon' => 'book-open', 'color' => 'indigo'],
                        'achievement' => ['icon' => 'sparkles', 'color' => 'amber'],
                        'live_session_scheduled' => ['icon' => 'video-camera', 'color' => 'primary'],
                        'live_session_reminder' => ['icon' => 'video-camera', 'color' => 'amber'],
                        'promo_announcement' => ['icon' => 'sparkles', 'color' => 'amber'],
                        'default' => ['icon' => 'bell', 'color' => 'primary'],
                    ];
                    $typeData = $typeIcons[$notification->notification_type ?? 'default'] ?? $typeIcons['default'];
                    $icon = $typeData['icon'];
                    $color = $typeData['color'];
                @endphp
                
                <x-student.card padding="p-0" class="group transition-all duration-300 {{ $isUnread ? 'hover:-translate-y-1 shadow-lg shadow-'.$color.'-500/5 border-l-4 border-l-'.$color.'-500' : 'opacity-80 hover:opacity-100 hover:shadow-md' }}"
                     data-aos="fade-up" data-aos-delay="{{ min($loop->index * 50, 400) }}">
                    
                    @if($isUnread)
                        <div class="absolute inset-0 bg-gradient-to-r from-{{ $color }}-500/5 to-transparent pointer-events-none"></div>
                    @endif

                    <div class="p-5 md:p-6 flex flex-col md:flex-row gap-5 items-start md:items-center relative z-10">
                        {{-- Icon --}}
                        <div class="shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:scale-110 group-hover:rotate-6
                                    {{ $isUnread ? 'bg-gradient-to-br from-'.$color.'-500/20 to-'.$color.'-500/5 border border-'.$color.'-500/30 text-'.$color.'-600 dark:text-'.$color.'-400' : 'bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/5 text-slate-500' }}">
                            @switch($icon)
                                @case('academic-cap')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14 3 9l9-5 9 5-9 5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12v4.5c0 .8 2.2 2.5 5 2.5s5-1.7 5-2.5V12"/></svg>
                                    @break
                                @case('document-text')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6l4 4v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6M9 13h6M9 17h4"/></svg>
                                    @break
                                @case('trophy')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h8v3a4 4 0 0 1-8 0V4Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6H4a2 2 0 0 0 2 2h1m11-2h2a2 2 0 0 1-2 2h-1M12 11v4m-3 5h6"/></svg>
                                    @break
                                @case('credit-card')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><rect width="18" height="14" x="3" y="5" rx="2" ry="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h3"/></svg>
                                    @break
                                @case('chat-bubble')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h7m8 0a8 8 0 1 1-3.2-6.4L21 3v9Z"/></svg>
                                    @break
                                @case('book-open')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.25C10.83 5.48 9.25 5 7.5 5S4.17 5.48 3 6.25v11C4.17 16.48 5.75 16 7.5 16s3.33.48 4.5 1.25m0-11C13.17 5.48 14.75 5 16.5 5c1.75 0 3.33.48 4.5 1.25v11C19.83 16.48 18.25 16 16.5 16s-3.33.48-4.5 1.25"/></svg>
                                    @break
                                @case('sparkles')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8L12 3Zm7 12 1 2.5L22.5 18 20 19l-1 2.5L18 19l-2.5-1 2.5-.5 1-2.5ZM5 14l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8.8-2Z"/></svg>
                                    @break
                                @case('video-camera')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-2.28A1 1 0 0 1 21 8.62v6.76a1 1 0 0 1-1.45.9L15 14M5 6h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/></svg>
                                    @break
                                @default
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.41-1.41A2 2 0 0 1 18 14.17V11a6 6 0 1 0-12 0v3.17a2 2 0 0 1-.59 1.42L4 17h5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17a2 2 0 1 0 4 0"/></svg>
                            @endswitch
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-base font-bold truncate {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $notification->title ?? ($isArabic ? 'إشعار من النظام' : 'System Notification') }}
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
                                    <span>{{ $isArabic ? 'عرض' : 'View' }}</span>
                                    <svg class="w-3.5 h-3.5 {{ $isArabic ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif

                            @if($isUnread)
                                <form action="{{ route('student.notifications.mark-as-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 hover:bg-{{ $color }}-500 hover:text-white transition-colors" title="{{ $isArabic ? 'تحديد كمقروء' : 'Mark as read' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </x-student.card>
            @empty
                <x-student.empty-state
                    title="{{ $isArabic ? 'لا توجد إشعارات جديدة' : 'All Caught Up!' }}"
                    message="{{ $isArabic ? 'ليس لديك إشعارات نشطة حاليًا. سنخبرك فور حدوث أي شيء جديد.' : 'You have no active notifications at the moment. We\'ll let you know when something new happens.' }}"
                    data-aos="fade-up"
                >
                    <x-slot name="icon">
                        <svg class="w-10 h-10 text-slate-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.41-1.41A2 2 0 0 1 18 14.17V11a6 6 0 10-12 0v3.17a2 2 0 0 1-.59 1.42L4 17h5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 17a2 2 0 104 0"></path></svg>
                    </x-slot>
                </x-student.empty-state>
            @endforelse
        </div>

        {{-- ─── PAGINATION ─── --}}
        @if($notifications->hasPages())
        <div class="mt-8 flex justify-center" data-aos="fade-up">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection






