<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\PromoVideo;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::active()
            ->orderBy('total_students', 'desc')
            ->take(6)
            ->get();

        $stats = [
            'total_students' => \App\Models\User::students()->count(),
            'total_courses' => Course::active()->count(),
            'total_enrollments' => \App\Models\Enrollment::count(),
            'certificates_issued' => \App\Models\Certificate::count(),
        ];

        $testimonials = Testimonial::active()->ordered()->take(6)->get();
        $promoVideos = PromoVideo::active()->ordered()->take(4)->get();

        return view('home', compact('featuredCourses', 'stats', 'testimonials', 'promoVideos'));
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
        $courses = Course::active()->orderBy('price')->get();
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