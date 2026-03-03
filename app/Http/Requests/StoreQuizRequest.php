<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
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
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'quiz_type' => 'required|in:lesson,final_exam',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'allow_retake' => 'boolean',
            'show_results_immediately' => 'boolean',
            'enable_audio' => 'boolean',
            'audio_auto_play' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Quiz title is required',
            'duration_minutes.required' => 'Quiz duration is required',
            'passing_score.required' => 'Passing score is required',
            'questions.required' => 'Please select at least one question',
            'questions.*.exists' => 'One or more selected questions do not exist',
        ];
    }
}