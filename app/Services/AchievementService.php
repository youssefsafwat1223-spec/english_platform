<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class AchievementService
{
    /**
     * Check for any achievements that might have been triggered by recent activity.
     *
     * @param User $user
     * @param string $triggerType The type of action that occurred ('lesson_completed', 'quiz_completed', 'daily_login')
     */
    public function checkAchievements(User $user, string $triggerType = null)
    {
        // Get all active achievements the user hasn't earned yet
        $unearnedAchievements = Achievement::active()
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($unearnedAchievements as $achievement) {
            // Optimization: Only check relevant achievements based on trigger
            if ($this->shouldCheck($achievement, $triggerType)) {
                if ($achievement->checkCriteria($user)) {
                    $this->award($user, $achievement);
                }
            }
        }
    }

    /**
     * Determine if we should check this achievement based on the trigger.
     */
    private function shouldCheck(Achievement $achievement, ?string $triggerType): bool
    {
        if (!$triggerType) return true; // Check all if no specific trigger

        $criteriaType = $achievement->criteria['type'] ?? null;

        return match ($triggerType) {
            'lesson_completed' => in_array($criteriaType, ['lessons_completed', 'courses_completed', 'first_step']),
            'quiz_completed' => in_array($criteriaType, ['quizzes_passed', 'perfect_score']),
            'daily_login' => in_array($criteriaType, ['streak_days']),
            'points_earned' => in_array($criteriaType, ['points_earned']),
            default => true,
        };
    }

    /**
     * Award the achievement to the user.
     */
    private function award(User $user, Achievement $achievement)
    {
        // Double check to prevent duplicates
        if ($achievement->isEarnedBy($user)) {
            return;
        }

        try {
            // 1. Attach to user
            $achievement->users()->attach($user->id, ['earned_at' => now()]);

            // 2. Award points
            if ($achievement->points_reward > 0) {
                $user->addPoints(
                    $achievement->points_reward,
                    'achievement',
                    $achievement->id,
                    "Earned achievement: {$achievement->name}"
                );
            }

            // 3. Create Notification
            Notification::create([
                'user_id' => $user->id,
                'notification_type' => 'achievement_earned',
                'title' => '🏆 Achievement Unlocked!',
                'message' => "You earned '{$achievement->name}': {$achievement->description}",
                'action_url' => route('student.profile.achievements'),
            ]);

            Log::info("Awarded achievement {$achievement->name} to user {$user->id}");

        } catch (\Exception $e) {
            Log::error("Failed to award achievement {$achievement->id} to user {$user->id}: " . $e->getMessage());
        }
    }
}
