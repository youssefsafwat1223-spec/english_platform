<?php

use Illuminate\Support\Facades\Schedule;
use App\Services\DailyQuestionService;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Schedule::call(function () {
    app(DailyQuestionService::class)->scheduleQuestionsForToday();
})->dailyAt('18:00')->name('send-daily-questions');

Schedule::call(function () {
    app(DailyQuestionService::class)->sendQuizReminders();
})->dailyAt('19:00')->name('send-quiz-reminders');

Schedule::call(function () {
    // Send study reminders to inactive users
    $inactiveUsers = \App\Models\User::students()
        ->active()
        ->telegramLinked()
        ->where(function ($q) {
            $q->whereNull('telegram_reminders')->orWhere('telegram_reminders', true);
        })
        ->where('last_activity_at', '<=', now()->subDays(3))
        ->get();
    
    $telegramService = app(\App\Services\TelegramService::class);
    
    foreach ($inactiveUsers as $user) {
        $daysSince = now()->diffInDays($user->last_activity_at);
        $telegramService->sendStudyReminder($user, $daysSince);
    }
})->dailyAt('18:00')->name('send-study-reminders');

Schedule::call(function () {
    // Clean old pronunciation recordings (older than 90 days)
    $oldRecordings = \App\Models\PronunciationAttempt::where('created_at', '<=', now()->subDays(90))
        ->get();
    
    foreach ($oldRecordings as $attempt) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($attempt->audio_recording_path);
    }
})->weekly()->name('clean-old-recordings');

Schedule::call(function () {
    // Update leaderboard cache
    $topUsers = \App\Models\User::students()
        ->orderBy('total_points', 'desc')
        ->take(100)
        ->get();
    
    \Illuminate\Support\Facades\Cache::put('leaderboard', $topUsers, now()->addHour());
})->hourly()->name('update-leaderboard');

Schedule::call(function () {
    // Expire referral discounts
    \App\Models\User::where('referral_discount_expires_at', '<=', now())
        ->where('referral_discount_used', false)
        ->update([
            'referral_discount_used' => true,
        ]);
})->daily()->name('expire-referral-discounts');

// Email inactivity reminders (7-day inactive students)
Schedule::command('email:send-inactivity-reminders --days=7')
    ->weekly()
    ->mondays()
    ->at('10:00')
    ->name('email-inactivity-reminders');

Schedule::command('battle:cleanup-stale-rooms')
    ->everyMinute()
    ->name('battle-cleanup-stale-rooms');
