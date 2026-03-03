<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'First Step',
                'slug' => 'first-step',
                'description' => 'Complete your first lesson',
                'icon' => '🚀',
                'points_reward' => 50,
                'criteria' => ['type' => 'lessons_completed', 'count' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'Knowledge Seeker',
                'slug' => 'knowledge-seeker',
                'description' => 'Complete 10 lessons',
                'icon' => '📚',
                'points_reward' => 100,
                'criteria' => ['type' => 'lessons_completed', 'count' => 10],
                'is_active' => true,
            ],
            [
                'name' => 'Quiz Master',
                'slug' => 'quiz-master',
                'description' => 'Pass 5 quizzes',
                'icon' => '🧠',
                'points_reward' => 150,
                'criteria' => ['type' => 'quizzes_passed', 'count' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Perfectionist',
                'slug' => 'perfectionist',
                'description' => 'Get 100% on a quiz',
                'icon' => '💯',
                'points_reward' => 200,
                'criteria' => ['type' => 'perfect_score', 'count' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'On Fire',
                'slug' => 'on-fire',
                'description' => 'Maintain a 7-day streak',
                'icon' => '🔥',
                'points_reward' => 300,
                'criteria' => ['type' => 'streak_days', 'count' => 7],
                'is_active' => true,
            ],
            [
                'name' => 'Finisher',
                'slug' => 'finisher',
                'description' => 'Complete a full course',
                'icon' => '🎓',
                'points_reward' => 500,
                'criteria' => ['type' => 'courses_completed', 'count' => 1],
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            \App\Models\Achievement::updateOrCreate(
                ['slug' => $achievement['slug']],
                $achievement
            );
        }
    }
}
