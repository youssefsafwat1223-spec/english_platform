<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForumTopicRequest extends FormRequest
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
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|min:5|max:500',
            'content' => 'required|string|min:10',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category',
            'title.required' => 'Topic title is required',
            'title.min' => 'Topic title must be at least 5 characters',
            'content.required' => 'Topic content is required',
            'content.min' => 'Content must be at least 10 characters',
        ];
    }
}