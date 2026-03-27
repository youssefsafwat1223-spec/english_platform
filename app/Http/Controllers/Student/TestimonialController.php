<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        if (!$this->canSubmit($user)) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', __('تقدر تضيف رأيك بعد الاشتراك في كورس واحد على الأقل.'));
        }

        $testimonial = $user->testimonial;

        return view('student.testimonials.edit', compact('testimonial'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $existingTestimonial = $user->testimonial;

        if (!$this->canSubmit($user)) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', __('تقدر تضيف رأيك بعد الاشتراك في كورس واحد على الأقل.'));
        }

        $validated = $request->validate([
            'role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:20|max:1000',
        ]);

        $testimonial = Testimonial::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'role' => filled($validated['role'] ?? null)
                    ? $validated['role']
                    : ($existingTestimonial?->role ?: __('طالب')),
                'content' => trim($validated['content']),
                'avatar' => $user->avatar ?: $existingTestimonial?->avatar,
                'rating' => (int) $validated['rating'],
                'is_active' => false,
                'sort_order' => $existingTestimonial?->sort_order ?? 0,
            ]
        );

        $message = $testimonial->wasRecentlyCreated
            ? __('تم استلام رأيك بنجاح، وسيظهر بعد مراجعته من الإدارة.')
            : __('تم تحديث رأيك وإرساله مرة أخرى للمراجعة.');

        return redirect()
            ->route('student.testimonial.edit')
            ->with('success', $message);
    }

    protected function canSubmit($user): bool
    {
        return $user->enrollments()->exists();
    }
}
