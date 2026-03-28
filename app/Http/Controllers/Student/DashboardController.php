<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\AchievementService;

class DashboardController extends Controller
{
    private const PENDING_PAYMENT_VISIBILITY_MINUTES = 30;

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

        // Only show the latest recent pending checkout per course.
        $pendingCutoff = now()->subMinutes(self::PENDING_PAYMENT_VISIBILITY_MINUTES);

        $latestPendingPaymentIds = Payment::query()
            ->selectRaw('MAX(id)')
            ->where('user_id', $user->id)
            ->pending()
            ->where('created_at', '>=', $pendingCutoff)
            ->groupBy('course_id');

        $pendingPayments = Payment::query()
            ->where('user_id', $user->id)
            ->pending()
            ->where('created_at', '>=', $pendingCutoff)
            ->whereIn('id', $latestPendingPaymentIds)
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->take(5)
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

        // Upcoming quiz/lesson
        $nextLesson = $this->getNextLesson($user);

        // Top Learners (Leaderboard)
        $topLearners = User::students()
            ->orderBy('total_points', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'user',
            'activeEnrollments',
            'stats',
            'nextLesson',
            'topLearners',
            'pendingPayments'
        ));
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
}
