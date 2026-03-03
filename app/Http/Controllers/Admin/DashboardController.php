<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\DailyQuestion;
use App\Models\PronunciationAttempt;
use App\Models\ForumTopic;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Overview statistics
        $stats = [
            'total_students' => User::students()->count(),
            'active_students' => User::students()
                ->where('last_activity_at', '>=', now()->subDays(7))
                ->count(),
            'new_students_this_month' => User::students()
                ->whereMonth('created_at', now()->month)
                ->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::active()->count(),
            'total_enrollments' => Enrollment::count(),
            'completed_enrollments' => Enrollment::completed()->count(),
            'revenue_today' => Payment::completed()
                ->whereDate('paid_at', today())
                ->sum('final_amount'),
            'revenue_this_month' => Payment::completed()
                ->whereMonth('paid_at', now()->month)
                ->sum('final_amount'),
            'total_revenue' => Payment::completed()->sum('final_amount'),
            'pending_payments' => Payment::pending()->count(),
        ];

        // Recent activities
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with(['user', 'course'])
            ->completed()
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Daily questions stats
        $dailyQuestionsStats = [
            'sent_today' => DailyQuestion::whereDate('sent_at', today())->count(),
            'answered_today' => DailyQuestion::whereDate('answered_at', today())->count(),
            'correct_today' => DailyQuestion::whereDate('answered_at', today())
                ->where('is_correct', true)
                ->count(),
        ];

        // Pronunciation practice stats
        $pronunciationStats = [
            'total_attempts' => PronunciationAttempt::count(),
            'attempts_today' => PronunciationAttempt::whereDate('created_at', today())->count(),
            'average_score' => PronunciationAttempt::avg('overall_score'),
        ];

        // Forum stats
        $forumStats = [
            'total_topics' => ForumTopic::count(),
            'topics_today' => ForumTopic::whereDate('created_at', today())->count(),
            'pending_reports' => \App\Models\ForumReport::pending()->count(),
        ];

        // Popular courses
        $popularCourses = Course::orderBy('total_students', 'desc')
            ->take(5)
            ->get();

        // Revenue chart data (last 30 days)
        $revenueChartData = $this->getRevenueChartData();

        // Enrollment chart data (last 30 days)
        $enrollmentChartData = $this->getEnrollmentChartData();

        return view('admin.dashboard', compact(
            'stats',
            'recentEnrollments',
            'recentPayments',
            'dailyQuestionsStats',
            'pronunciationStats',
            'forumStats',
            'popularCourses',
            'revenueChartData',
            'enrollmentChartData'
        ));
    }

    private function getRevenueChartData()
    {
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Payment::completed()
                ->whereDate('paid_at', $date)
                ->sum('final_amount');
            
            $data[] = [
                'date' => $date->format('M d'),
                'revenue' => (float) $revenue,
            ];
        }

        return $data;
    }

    private function getEnrollmentChartData()
    {
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Enrollment::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('M d'),
                'count' => $count,
            ];
        }

        return $data;
    }
}