<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'title' => 'string|max:50',
            'rating' => 'numeric|min:0|max:5',
            'description' => 'string|max:500',
            // 'media_files' => 'array',
            // 'media_files.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv,wmv|max:20480',
        ];

    }
}
