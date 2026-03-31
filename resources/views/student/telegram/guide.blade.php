@extends('layouts.app')

@php
    $telegramBotUsername = ltrim((string) config('services.telegram.bot_username', 'SimpleEnglishBot'), '@');
@endphp

@section('title', __('ui.telegram.guide_title') . ' - ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-cyan-500/10 via-cyan-500/5 to-transparent pointer-events-none z-0"></div>
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
        <x-student.page-header
            title="{{ __('ui.telegram.guide_title') }}"
            subtitle="{{ __('ui.telegram.guide_intro') }}"
            badge="{{ __('ui.telegram.guide_badge') }}"
            badgeColor="cyan"
            badgeIcon="<svg class='h-4 w-4' viewBox='0 0 24 24' fill='currentColor'>
                <path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z'/>
            </svg>"
        />

        <div class="grid gap-8 lg:grid-cols-3">
            <x-student.card padding="p-8" class="shadow-xl lg:col-span-2">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('ui.telegram.guide_steps_title') }}</h2>

                <div class="mt-6 space-y-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-slate-900/40">
                        <div class="text-sm font-bold text-cyan-600 dark:text-cyan-300">1. {{ __('ui.telegram.step_1_title') }}</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                            {{ __('ui.telegram.step_1_text') }}
                            <span class="font-semibold text-slate-900 dark:text-white">{{ '@' . $telegramBotUsername }}</span>
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-slate-900/40">
                        <div class="text-sm font-bold text-cyan-600 dark:text-cyan-300">2. {{ __('ui.telegram.step_2_title') }}</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                            {{ __('ui.telegram.step_2_text') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-slate-900/40">
                        <div class="text-sm font-bold text-cyan-600 dark:text-cyan-300">3. {{ __('ui.telegram.step_3_title') }}</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                            {{ __('ui.telegram.step_3_text') }}
                        </p>
                        <div class="mt-3 rounded-xl border border-amber-500/20 bg-amber-500/10 p-4 text-sm text-amber-700 dark:text-amber-100">
                            {{ __('ui.telegram.valid_examples') }}
                            <div class="mt-2 font-mono text-slate-900 dark:text-white">+9665XXXXXXXX</div>
                            <div class="font-mono text-slate-900 dark:text-white">+2010XXXXXXX</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-slate-900/40">
                        <div class="text-sm font-bold text-cyan-600 dark:text-cyan-300">4. {{ __('ui.telegram.step_4_title') }}</div>
                        <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                            {{ __('ui.telegram.step_4_text') }}
                        </p>
                    </div>
                </div>

                @if(!auth()->user()->is_telegram_linked)
                    <div class="mt-8">
                        <a href="https://t.me/{{ $telegramBotUsername }}?start=1" target="_blank"
                           class="inline-flex items-center gap-3 rounded-xl bg-[#0088cc] px-6 py-3 text-sm font-bold text-white hover:bg-[#0077b5]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .24z"/>
                            </svg>
                            {{ __('ui.telegram.open_bot') }}
                        </a>
                    </div>
                @endif
            </x-student.card>

            <x-student.card padding="p-8" class="shadow-xl h-fit">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('ui.telegram.commands_title') }}</h2>

                <div class="mt-6 space-y-4 text-sm">
                    @php
                        $commands = [
                            ['/start', __('ui.telegram.command_start')],
                            ['/today', __('ui.telegram.command_today')],
                            ['/status', __('ui.telegram.command_status')],
                            ['/courses', __('ui.telegram.command_courses')],
                            ['/leaderboard', __('ui.telegram.command_leaderboard')],
                            ['/remind', __('ui.telegram.command_remind')],
                        ];
                    @endphp

                    @foreach($commands as [$command, $description])
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/40">
                            <div class="font-mono font-bold text-cyan-600 dark:text-cyan-300">{{ $command }}</div>
                            <p class="mt-2 text-slate-600 dark:text-slate-300">{{ $description }}</p>
                        </div>
                    @endforeach
                </div>
            </x-student.card>
        </div>
    </div>
</div>
@endsection
