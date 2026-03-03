<?php

namespace App\Console\Commands;

use App\Mail\InactivityReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInactivityReminders extends Command
{
    protected $signature = 'email:send-inactivity-reminders {--days=7 : Number of inactive days threshold}';
    protected $description = 'Send reminder emails to students who have been inactive';

    public function handle()
    {
        $days = (int) $this->option('days');

        $this->info("Looking for students inactive for {$days}+ days...");

        $inactiveStudents = User::where('role', 'student')
            ->where('is_active', true)
            ->where(function ($query) use ($days) {
                $query->where('last_activity_at', '<=', now()->subDays($days))
                      ->orWhereNull('last_activity_at');
            })
            ->get();

        $this->info("Found {$inactiveStudents->count()} inactive students.");

        $sent = 0;
        $failed = 0;

        foreach ($inactiveStudents as $user) {
            try {
                $daysSinceActive = $user->last_activity_at
                    ? $user->last_activity_at->diffInDays(now())
                    : $days;

                // Get enrolled courses with progress
                $enrolledCourses = $user->enrollments()
                    ->with('course')
                    ->where('status', '!=', 'completed')
                    ->get();

                Mail::to($user)->send(new InactivityReminder($user, $daysSinceActive, $enrolledCourses));

                $sent++;
                $this->line("  ✓ Sent to {$user->email}");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed for {$user->email}: {$e->getMessage()}");
                Log::error("Inactivity reminder failed for user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Done! Sent: {$sent}, Failed: {$failed}");

        return Command::SUCCESS;
    }
}
