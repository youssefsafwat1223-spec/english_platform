<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_student;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.user_answer' => 'required|string',
            'answers.*.time_taken' => 'nullable|integer',
            'answers.*.audio_played' => 'nullable|boolean',
            'answers.*.audio_replay_count' => 'nullable|integer',
            'started_at' => 'required|date',
            'completed_at' => 'required|date|after:started_at',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'answers.required' => 'Please answer at least one question',
            'answers.*.user_answer.required' => 'Please provide an answer for each question',
            'answers.*.user_answer.in' => 'Answer must be A, B, C, or D',
            'started_at.required' => 'Quiz start time is required',
            'completed_at.after' => 'Completion time must be after start time',
        ];
    }
}