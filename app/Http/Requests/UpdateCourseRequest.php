<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price'        => 'sometimes|required|numeric|min:0',
            'payment_type' => 'sometimes|in:full,installment',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'intro_video_url' => 'nullable|url',
            'estimated_duration_weeks' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_exam' => 'boolean',
            'prerequisite_course_id' => 'nullable|exists:courses,id',
        ];
    }
}