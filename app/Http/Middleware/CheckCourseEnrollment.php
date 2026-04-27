<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $courseModel = $course instanceof Course
            ? $course
            : Course::query()->where('slug', $course)->orWhere('id', $course)->first();
        $courseId = $courseModel?->id ?? $course;

        if (auth()->user()->isEnrolledIn($courseId)) {
            return $next($request);
        }

        // Allow access if the route targets a lesson that lives under a free level.
        $lessonParam = $request->route('lesson');
        if ($lessonParam) {
            $lesson = $lessonParam instanceof Lesson
                ? $lessonParam
                : Lesson::query()->where('slug', $lessonParam)->orWhere('id', $lessonParam)->first();

            if ($lesson?->level?->is_free) {
                return $next($request);
            }
        }

        return redirect()->route('student.courses.show', $courseModel ?? $course)
            ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
    }
}
