<?php

namespace App\Http\Requests;

use App\Models\Lesson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
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
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,fill_blank,drag_drop',
            'option_a' => 'required_unless:question_type,drag_drop|nullable|string|max:500',
            'option_b' => 'required_if:question_type,multiple_choice,true_false|nullable|string|max:500',
            'option_c' => 'nullable|string|max:500',
            'option_d' => 'nullable|string|max:500',
            'matching_pairs' => 'required_if:question_type,drag_drop|nullable|array|min:2',
            'matching_pairs.*.left' => 'required_with:matching_pairs|string',
            'matching_pairs.*.right' => 'required_with:matching_pairs|string',
            'correct_answer' => 'required_unless:question_type,drag_drop|nullable|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'nullable|integer|min:1',
            'audio_file' => 'nullable|file|mimes:mp3,wav|max:5120', // 5MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'question_text.required' => 'Question text is required',
            'option_a.required' => 'Option A is required',
            'option_b.required' => 'Option B is required',
            'correct_answer.required' => 'Please select the correct answer',
            'correct_answer.in' => 'Correct answer must be A, B, C, or D',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $courseId = (int) $this->input('course_id');
            $lessonId = $this->input('lesson_id');

            if (!$lessonId) {
                return;
            }

            $lesson = Lesson::query()->select(['id', 'course_id'])->find($lessonId);

            if (!$lesson || (int) $lesson->course_id !== $courseId) {
                $validator->errors()->add('lesson_id', 'Selected lesson does not belong to the selected course.');
            }
        });
    }
}
