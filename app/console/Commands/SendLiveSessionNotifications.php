<?php

namespace App\Console\Commands;

use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendLiveSessionNotifications extends Command
{
    protected $signature = 'live-sessions:send-notifications';
    protected $description = 'Send scheduled live session reminders to eligible students';

    public function handle()
    {
        $sessions = LiveSession::with('courses')
            ->where('notifications_enabled', true)
            ->whereIn('status', [LiveSession::STATUS_SCHEDULED, LiveSession::STATUS_LIVE])
            ->where('starts_at', '>', now())
            ->get();

        foreach ($sessions as $session) {
            $minutesUntilStart = now()->diffInMinutes($session->starts_at, false);

            if ($minutesUntilStart <= 1440 && $minutesUntilStart > 1380 && !$session->notified_24h_at) {
                $this->sendReminder($session, '24h');
                $session->forceFill(['notified_24h_at' => now()])->save();
            }

            if ($minutesUntilStart <= 60 && $minutesUntilStart >= 0 && !$session->notified_1h_at) {
                $this->sendReminder($session, '1h');
                $session->forceFill(['notified_1h_at' => now()])->save();
            }
        }

        $this->info('Live session reminders processed.');

        return self::SUCCESS;
    }

    private function sendReminder(LiveSession $session, string $type): void
    {
        $courseIds = $session->courses->pluck('id');

        $students = User::students()
            ->whereHas('enrollments', function ($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })
            ->get();

        if ($students->isEmpty()) {
            return;
        }

        $message = $type === '24h'
            ? __('live_sessions.reminder_24h', [
                'title' => $session->title,
                'date' => $session->starts_at->format('M d, Y h:i A'),
            ])
            : __('live_sessions.reminder_1h', [
                'title' => $session->title,
                'time' => $session->starts_at->format('h:i A'),
            ]);

        $payload = $students->map(fn (User $user) => [
            'user_id' => $user->id,
            'notification_type' => 'live_session_reminder',
            'title' => __('live_sessions.reminder_title'),
            'message' => $message,
            'action_url' => route('student.live-sessions.show', $session),
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        Notification::insert($payload);
    }
}
