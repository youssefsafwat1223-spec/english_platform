<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\PromoVideo;
use App\Mail\ContactMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::active()
            ->orderBy('total_students', 'desc')
            ->take(3)
            ->get();

        $testimonials = Testimonial::active()->ordered()->take(6)->get();
        $promoVideos = PromoVideo::active()->ordered()->take(4)->get();
        $canSubmitTestimonial = false;
        $studentTestimonial = null;

        if (auth()->check() && auth()->user()->is_student) {
            $studentTestimonial = auth()->user()->testimonial;
            $canSubmitTestimonial = auth()->user()->enrollments()->exists();
        }

        return view('home', compact(
            'featuredCourses',
            'testimonials',
            'promoVideos',
            'canSubmitTestimonial',
            'studentTestimonial'
        ));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function pricing()
    {
        $courses = Course::active()
            ->withHeadingsCount()
            ->orderBy('total_students', 'desc')
            ->take(3)
            ->get();
        return view('pricing', compact('courses'));
    }

    public function blog()
    {
        return view('blog');
    }

    public function careers()
    {
        return view('careers');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function courses(Request $request)
    {
        $query = Course::active()
            ->withCount(['students'])
            ->withHeadingsCount();

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function (Builder $subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        switch ($request->input('sort')) {
            case 'popular':
                $query->orderBy('total_students', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('order_index');
                break;
        }

        $courses = $query->paginate(12)->appends($request->query());

        return view('courses.index', compact('courses'));
    }

    public function courseShow(Course $course)
    {
        if (!$course->is_active) {
            abort(404);
        }

        $course->loadCount(['lessons', 'students']);
        $headingsCount = $course->levels()
            ->where('is_active', true)
            ->count();

        $totalLessonsCount = (int) $course->lessons()->count();
        $distinctLessonTitlesCount = $course->lessons()
            ->whereNotNull('title')
            ->whereRaw("TRIM(title) <> ''")
            ->distinct()
            ->count('title');

        $previewLessons = $course->lessons()
            ->orderBy('order_index')
            ->select([
                'id',
                'course_id',
                'title',
                'is_free',
                'has_quiz',
                'has_pronunciation_exercise',
                'has_writing_exercise',
            ])
            ->take(12)
            ->get();

        $hasQuizFeature = $course->lessons()
            ->where(function (Builder $query) {
                $query->where('has_quiz', true)
                    ->orWhereHas('quiz', function (Builder $quizQuery) {
                        $quizQuery->where('is_active', true);
                    });
            })
            ->exists();

        $hasWritingFeature = $course->lessons()
            ->where(function (Builder $query) {
                $query->where('has_writing_exercise', true)
                    ->orWhereHas('writingExercise');
            })
            ->exists();

        $hasPronunciationFeature = $course->lessons()
            ->where(function (Builder $query) {
                $query->where('has_pronunciation_exercise', true)
                    ->orWhereHas('pronunciationExercise');
            })
            ->exists();

        return view('courses.show', compact(
            'course',
            'headingsCount',
            'totalLessonsCount',
            'distinctLessonTitlesCount',
            'previewLessons',
            'hasQuizFeature',
            'hasWritingFeature',
            'hasPronunciationFeature'
        ));
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Send email to admin
        $admins = User::where('role', 'admin')->get();
        
        if ($admins->isNotEmpty()) {
            Mail::to($admins)->send(new ContactMessage($request->all()));
        } else {
            // Fallback if no admin user is found
            $adminEmail = config('mail.from.address', 'admin@example.com');
            Mail::to($adminEmail)->send(new ContactMessage($request->all()));
        }

        return back()->with('success', __('Thank you for contacting us! We will get back to you soon.'));
    }
}
