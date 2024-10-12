<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCheckoutRequest extends FormRequest
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
            'supplierId' => 'required|string|exists:suppliers,id',
            'cartItems' => 'required|array',
            'companyName' => 'required|string',
            'email' => 'required|email',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'adress' => 'required|string',
            'apartament' => 'nullable|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'code' => 'required|string',
            'phone' => 'required|string',
            'products' => 'required|array',
            'products.*.product.id' => 'required|string|exists:supplier_products,id',
            'products.*.product.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.product.price' => 'required|numeric',
        ];
    }
}
