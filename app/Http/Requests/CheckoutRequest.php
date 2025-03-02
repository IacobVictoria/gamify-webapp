<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'email' => 'required|email',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'apartament' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'code' => 'required|string|max:30',
            'phone' => 'required|string|max:25',
            'cartItems' => 'required|array',
            'cartItems.*.product.id' => 'required|string|exists:products,id',
            'cartItems.*.quantity' => 'required|integer|min:1',
            'discountAmount' => 'nullable|numeric|min:0|max:100',
            'promoCode' => 'nullable|string|max:255',
        ];
    }
}
