<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Course;

class CheckCourseEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $course = $request->route('course');

        if ($course instanceof Course) {
            $courseId = $course->id;
        } else {
            $courseId = $course;
        }

        if (!auth()->user()->isEnrolledIn($courseId)) {
            return redirect()->route('student.courses.show', $courseId)
                ->with('error', 'You must enroll in this course first.');
        }

        return $next($request);
    }
}
