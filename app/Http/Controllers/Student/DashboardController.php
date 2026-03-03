<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AchievementService;

class DashboardController extends Controller
{
    public function index(AchievementService $achievementService)
    {
        $user = auth()->user();

        // Check for daily login achievements (streaks)
        $achievementService->checkAchievements($user, 'daily_login');

        // Load relationships
        $user->load([
            'enrollments.course',
            'certificates',
            'achievements',
        ]);

        // Get active enrollments
        $activeEnrollments = $user->enrollments()
            ->active()
            ->with(['course', 'lessonProgress'])
            ->orderBy('last_accessed_at', 'desc')
            ->get();

        // Get pending payments
        $pendingPayments = \App\Models\Payment::where('user_id', $user->id)
            ->pending()
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recent activity
        $recentActivity = $user->pointsHistory()
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $stats = [
            'total_enrollments' => $user->enrollments()->count(),
            'active_courses' => $activeEnrollments->count(),
            'completed_courses' => $user->enrollments()->completed()->count(),
            'certificates_earned' => $user->certificates()->count(),
            'total_points' => $user->total_points,
            'rank' => $user->getRank(),
            'current_streak' => $user->current_streak,
            'longest_streak' => $user->longest_streak,
            'achievements_count' => $user->achievements()->count(),
        ];

        // Daily question stats
        $dailyQuestionStats = [
            'total_answered' => $user->dailyQuestions()->answered()->count(),
            'correct_answers' => $user->dailyQuestions()->correct()->count(),
            'accuracy' => $this->calculateAccuracy($user),
        ];

        // Upcoming quiz/lesson
        $nextLesson = $this->getNextLesson($user);

        // Top Learners (Leaderboard)
        $topLearners = User::students()
            ->orderBy('total_points', 'desc')
            ->take(5)
            ->get();

        // Available Courses (Not Enrolled)
        $availableCourses = \App\Models\Course::whereDoesntHave('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // 7-Day Activity Chart
        $chartData = $this->getPointsChartData($user);

        return view('student.dashboard', compact(
            'user',
            'activeEnrollments',
            'recentActivity',
            'stats',
            'dailyQuestionStats',
            'nextLesson',
            'topLearners',
            'chartData',
            'availableCourses',
            'pendingPayments'
        ));
    }

    private function calculateAccuracy(User $user)
    {
        $total = $user->dailyQuestions()->answered()->count();
        
        if ($total === 0) {
            return 0;
        }

        $correct = $user->dailyQuestions()->correct()->count();
        
        return round(($correct / $total) * 100);
    }

    private function getNextLesson(User $user)
    {
        $activeEnrollment = $user->enrollments()
            ->active()
            ->orderBy('last_accessed_at', 'desc')
            ->first();

        if (!$activeEnrollment) {
            return null;
        }

        return $activeEnrollment->next_lesson;
    }

    private function getPointsChartData(User $user)
    {
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $pointsPerDay = $user->pointsHistory()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(function ($record) {
                return $record->created_at->format('Y-m-d');
            })
            ->map(function ($records) {
                return $records->sum('points_earned');
            });

        return [
            'labels' => $days->map(fn($date) => \Carbon\Carbon::parse($date)->format('D'))->toArray(),
            'data' => $days->map(fn($date) => $pointsPerDay->get($date, 0))->toArray(),
        ];
    }
}