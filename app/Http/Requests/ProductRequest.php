<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'score' => 'required|integer|min:0|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp',
            'is_published' => 'boolean'
        ];
    }
}
