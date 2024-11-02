<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.score' => 'nullable|integer',
            'questions.*.answers' => 'required|array',
            'questions.*.answers.*.text' => 'required|string',
            'questions.*.answers.*.isCorrect' => 'required|boolean',
        ];
    }
}
