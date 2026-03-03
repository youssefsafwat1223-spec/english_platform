<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment_text' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:lesson_comments,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'comment_text.required' => 'Comment text is required',
            'comment_text.min' => 'Comment must be at least 3 characters',
            'comment_text.max' => 'Comment must not exceed 1000 characters',
        ];
    }
}