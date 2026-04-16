<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index(Request $request)
    {
        $query = User::students()
            ->withCount(['enrollments', 'certificates']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by telegram
        if ($request->filled('telegram')) {
            if ($request->telegram === 'linked') {
                $query->whereNotNull('telegram_chat_id');
            } elseif ($request->telegram === 'not_linked') {
                $query->whereNull('telegram_chat_id');
            }
        }

        $students = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        if (!$student->is_student) {
            abort(404);
        }

        $student->load([
            'enrollments.course',
            'certificates.course',
            'quizAttempts.quiz',
            'dailyQuestions',
            'pointsHistory',
            'achievements',
        ]);

        $stats = [
            'total_enrollments' => $student->enrollments()->count(),
            'completed_courses' => $student->enrollments()->completed()->count(),
            'active_courses' => $student->enrollments()->active()->count(),
            'total_points' => $student->total_points,
            'rank' => $student->getRank(),
            'current_streak' => $student->current_streak,
            'longest_streak' => $student->longest_streak,
            'certificates_earned' => $student->certificates()->count(),
            'daily_questions_answered' => $student->dailyQuestions()->answered()->count(),
            'daily_questions_correct' => $student->dailyQuestions()->correct()->count(),
            'quizzes_taken' => $student->quizAttempts()->count(),
            'quizzes_passed' => $student->quizAttempts()->passed()->count(),
        ];

        return view('admin.students.show', compact('student', 'stats'));
    }

    public function toggleStatus(User $student)
    {
        if (!$student->is_student) {
            abort(404);
        }

        $student->update([
            'is_active' => !$student->is_active,
        ]);

        $status = $student->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Student account {$status} successfully!");
    }

    public function sendMessage(Request $request, User $student)
    {
        $request->validate([
            'message' => 'required|string|min:5',
        ]);

        if (!$student->is_telegram_linked) {
            return back()->with('error', 'Student has not linked Telegram account.');
        }

        $result = $this->telegramService->sendMessage(
            $student->telegram_chat_id,
            $request->message
        );

        if ($result) {
            return back()->with('success', 'Message sent successfully!');
        }

        return back()->with('error', 'Failed to send message.');
    }

    public function enrollments(User $student)
    {
        $enrollments = $student->enrollments()
            ->with(['course', 'lessonProgress', 'quizAttempts'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.students.enrollments', compact('student', 'enrollments'));
    }

    public function grantAccess(Request $request, User $student)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($student->isEnrolledIn($course->id)) {
            return back()->with('error', "الطالب {$student->name} مسجل بالفعل في كورس {$course->title}");
        }

        $totalLessons = (int) $course->lessons()
            ->whereNotNull('title')
            ->whereRaw("TRIM(title) <> ''")
            ->reorder()
            ->selectRaw("COUNT(DISTINCT LOWER(TRIM(title))) as aggregate")
            ->value('aggregate');

        if ($totalLessons <= 0) {
            $totalLessons = (int) $course->lessons()->count();
        }

        Enrollment::create([
            'user_id'      => $student->id,
            'course_id'    => $course->id,
            'price_paid'   => 0,
            'total_lessons'=> $totalLessons,
            'started_at'   => now(),
        ]);

        $course->incrementStudents();

        return back()->with('success', "تم فتح كورس \"{$course->title}\" للطالب {$student->name} بنجاح ✓");
    }

    public function toggleEnrollmentAccess(User $student, Enrollment $enrollment)
    {
        if ($enrollment->user_id !== $student->id) {
            abort(404);
        }

        if ($enrollment->is_suspended) {
            $enrollment->update(['access_suspended_at' => null]);
            $message = "تم فتح وصول {$student->name} إلى كورس {$enrollment->course->title}";
        } else {
            $enrollment->update(['access_suspended_at' => now()]);
            $message = "تم قفل وصول {$student->name} إلى كورس {$enrollment->course->title}";
        }

        return back()->with('success', $message);
    }

    public function progress(User $student, $enrollmentId)
    {
        $enrollment = $student->enrollments()
            ->with(['course.lessons', 'lessonProgress'])
            ->findOrFail($enrollmentId);

        return view('admin.students.progress', compact('student', 'enrollment'));
    }

    public function destroy(User $student)
    {
        if (!$student->is_student) {
            abort(404);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student account deleted successfully!');
    }
}
