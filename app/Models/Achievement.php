<?php

namespace App\Models;

use App\Models\Concerns\RepairsMojibakeAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory, RepairsMojibakeAttributes;

    protected array $repairableTextAttributes = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'criteria',
        'points_reward',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'criteria' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ==================== METHODS ====================

    /**
     * Check if user has earned this achievement
     */
    public function isEarnedBy(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Award achievement to user
     */
    public function awardTo(User $user)
    {
        if (!$this->isEarnedBy($user)) {
            $this->users()->attach($user->id, ['earned_at' => now()]);

            // Award points
            if ($this->points_reward > 0) {
                $user->addPoints(
                    $this->points_reward,
                    'achievement',
                    $this->id,
                    "Earned achievement: {$this->name}"
                );
            }

            // Send notification
            Notification::create([
                'user_id' => $user->id,
                'notification_type' => 'achievement_earned',
                'title' => __('Achievement Unlocked!'),
                'message' => __("You earned the ':achievement' achievement!", [
                    'achievement' => $this->name,
                ]),
                'action_url' => route('student.profile.achievements'),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Check if user meets criteria
     */
    public function checkCriteria(User $user)
    {
        // Example criteria structure:
        // ['type' => 'lessons_completed', 'count' => 10]
        // ['type' => 'quizzes_passed', 'count' => 5]
        // ['type' => 'streak_days', 'count' => 7]

        $type = $this->criteria['type'] ?? null;
        $count = $this->criteria['count'] ?? 0;

        return match($type) {
            'lessons_completed' => $user->lessonProgress()->where('is_completed', true)->count() >= $count,
            'quizzes_passed' => $user->quizAttempts()->where('passed', true)->count() >= $count,
            'courses_completed' => $user->enrollments()->whereNotNull('completed_at')->count() >= $count,
            'streak_days' => $user->current_streak >= $count,
            'points_earned' => $user->total_points >= $count,
            'daily_questions' => $user->dailyQuestions()->where('is_correct', true)->count() >= $count,
            default => false,
        };
    }
}
