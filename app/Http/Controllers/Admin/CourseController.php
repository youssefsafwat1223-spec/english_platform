<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['lessons', 'students', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        // Generate slug
        $data['slug'] = Str::slug($data['title']);
        $data['created_by'] = auth()->id();

        $course = Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('success', __('تم إنشاء الكورس بنجاح!'));
    }

    public function show(Course $course)
    {
        $course->load(['lessons', 'enrollments.user', 'questions', 'quizzes', 'creator']);
        
        $stats = [
            'total_students' => $course->total_students,
            'active_enrollments' => $course->enrollments()->active()->count(),
            'completed_enrollments' => $course->enrollments()->completed()->count(),
            'average_progress' => $course->enrollments()->avg('progress_percentage'),
            'total_lessons' => $course->lessons()->count(),
            'total_questions' => $course->questions()->count(),
            'total_quizzes' => $course->quizzes()->count(),
            'revenue' => $course->payments()->completed()->sum('final_amount'),
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $course->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')
            ->with('success', __('تم تعديل الكورس بنجاح!'));
    }

    public function destroy(Course $course)
    {
        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', __('تم حذف الكورس بنجاح!'));
    }

    public function toggleStatus(Course $course)
    {
        $course->update([
            'is_active' => !$course->is_active,
        ]);

        $status = $course->is_active ? 'activated' : 'deactivated';

        $statusAr = $course->is_active ? __('مفعل') : __('غير مفعل');
        return back()->with('success', __("تم تغيير حالة الكورس إلى :status بنجاح!", ['status' => $statusAr]));
    }
}
