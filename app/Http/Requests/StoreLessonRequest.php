<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $course = $this->route('course');
        if ($course && !$this->has('course_id')) {
            $this->merge([
                'course_id' => $course->id,
            ]);
        }

        $hasQuiz = $this->boolean('has_quiz');
        $hasPronunciation = $this->boolean('has_pronunciation_exercise');

        $this->merge([
            'is_free' => $this->boolean('is_free'),
            'has_quiz' => $hasQuiz,
            'has_pronunciation_exercise' => $hasPronunciation,
        ]);

        if (!$hasQuiz) {
            $this->merge([
                'quiz_mode' => null,
                'quiz_id' => null,
                'question_ids' => null,
            ]);
        }

        if (!$hasPronunciation) {
            $this->merge([
                'pronunciation_sentence_1' => null,
                'pronunciation_sentence_2' => null,
                'pronunciation_sentence_3' => null,
            ]);
        }
    }

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
            'course_level_id' => 'nullable|exists:course_levels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'text_content' => 'nullable|string',
            'order_index' => 'nullable|integer',
            'is_free' => 'boolean',
            'has_quiz' => 'boolean',
            'has_pronunciation_exercise' => 'boolean',
            'attachments.*' => 'nullable|file|max:102400', // 100MB
            'quiz_mode' => 'nullable|required_if:has_quiz,1|in:existing,questions',
            'quiz_id' => 'nullable|required_if:quiz_mode,existing|exists:quizzes,id',
            'question_ids' => 'nullable|required_if:quiz_mode,questions|array',
            'question_ids.*' => 'integer|exists:questions,id',
            'quiz_title' => 'nullable|string|max:255',
            'quiz_duration_minutes' => 'nullable|integer|min:1',
            'quiz_passing_score' => 'nullable|integer|min:0|max:100',
            'quiz_allow_retake' => 'boolean',
            'quiz_show_results' => 'boolean',
            'quiz_enable_audio' => 'boolean',
            'pronunciation_sentence_1' => 'nullable|required_if:has_pronunciation_exercise,1|string',
            'pronunciation_sentence_2' => 'nullable|string',
            'pronunciation_sentence_3' => 'nullable|string',
            'pronunciation_passing_score' => 'nullable|integer|min:0|max:100',
            'pronunciation_max_duration' => 'nullable|integer|min:1',
            'pronunciation_allow_retake' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Please select a course',
            'course_id.exists' => 'Selected course does not exist',
            'title.required' => 'Lesson title is required',
            'attachments.*.max' => 'Attachment size must not exceed 100MB',
        ];
    }
}
