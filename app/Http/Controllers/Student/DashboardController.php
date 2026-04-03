<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LiveSession;
use App\Models\Payment;
use App\Models\SystemSetting;
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

        $primaryEnrollment = $activeEnrollments->first();
        $levelProgress = $primaryEnrollment?->progress_percentage ?? 0;
        $levelLabel = $levelProgress >= 50 ? 'PRO' : 'ROOKIE';

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
            'level_label' => $levelLabel,
            'level_progress' => $levelProgress,
        ];

        // Upcoming quiz/lesson
        $nextLesson = $this->getNextLesson($user);
        $featuredBanner = $this->getFeaturedBanner($user);

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
            'featuredBanner',
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

    private function getFeaturedBanner(User $user): ?array
    {
        $session = LiveSession::with('courses')
            ->visibleToStudent($user)
            ->where('banner_enabled', true)
            ->get()
            ->sortBy(function (LiveSession $liveSession) {
                return match ($liveSession->display_status) {
                    LiveSession::STATUS_LIVE => 0,
                    LiveSession::STATUS_SCHEDULED => 1,
                    default => 2,
                };
            })
            ->first(function (LiveSession $liveSession) {
                return $liveSession->display_status === LiveSession::STATUS_LIVE
                    || ($liveSession->display_status === LiveSession::STATUS_SCHEDULED && $liveSession->startsWithinHours(24));
            });

        if ($session) {
            $isLive = $session->display_status === LiveSession::STATUS_LIVE;

            return [
                'type' => 'live-session',
                'eyebrow' => $isLive ? __('live_sessions.live_now') : __('live_sessions.upcoming_live_session'),
                'title' => $session->title,
                'message' => $isLive
                    ? __('live_sessions.live_now_message')
                    : __('live_sessions.starts_on', ['date' => $session->starts_at->format('M d, Y h:i A')]),
                'action_label' => $isLive ? __('live_sessions.join_zoom_session') : __('live_sessions.view_session'),
                'action_url' => $isLive ? $session->zoom_join_url : route('student.live-sessions.show', $session),
                'course' => $session->primary_course?->title,
            ];
        }

        $promoTitle = SystemSetting::get('dashboard_promo_title');
        $promoMessage = SystemSetting::get('dashboard_promo_message');
        $promoUrl = SystemSetting::get('dashboard_promo_url');

        if ($promoTitle && $promoMessage) {
            return [
                'type' => 'promo',
                'eyebrow' => __('live_sessions.promo.special_offer'),
                'title' => $promoTitle,
                'message' => $promoMessage,
                'action_label' => $promoUrl ? __('live_sessions.promo.view_offer') : null,
                'action_url' => $promoUrl,
                'course' => null,
            ];
        }

        return null;
    }
}
