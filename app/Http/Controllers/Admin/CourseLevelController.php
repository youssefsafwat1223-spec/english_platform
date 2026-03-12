<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseLevelController extends Controller
{
    public function index(Course $course)
    {
        $levels = $course->levels()->withCount('lessons')->get();

        return view('admin.course-levels.index', compact('course', 'levels'));
    }

    public function create(Course $course)
    {
        return view('admin.course-levels.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if (!isset($data['order_index'])) {
            $data['order_index'] = $course->levels()->max('order_index') + 1;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store("courses/{$course->id}/levels", 'public');
        }

        $course->levels()->create($data);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم إضافة المستوى بنجاح!'));
    }

    public function edit(Course $course, CourseLevel $level)
    {
        return view('admin.course-levels.edit', compact('course', 'level'));
    }

    public function update(Request $request, Course $course, CourseLevel $level)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if (isset($data['title']) && $data['title'] !== $level->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($level->thumbnail) {
                Storage::disk('public')->delete($level->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store("courses/{$course->id}/levels", 'public');
        }

        $level->update($data);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم تعديل المستوى بنجاح!'));
    }

    public function destroy(Course $course, CourseLevel $level)
    {
        if ($level->thumbnail) {
            Storage::disk('public')->delete($level->thumbnail);
        }

        $level->delete();

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم حذف المستوى بنجاح!'));
    }
}
