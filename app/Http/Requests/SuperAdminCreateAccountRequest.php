<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class SuperAdminCreateAccountRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $roleIds = $this->get('role_ids', []);
            $roles = Role::whereIn('id', $roleIds)->pluck('name')->map(fn($name) => strtolower($name))->toArray();

            if (count($roles) > 1) {
                $validCombination = collect($roles)->sort()->values()->toArray() === ['admin', 'super-admin'];

                if (!$validCombination) {
                    $validator->errors()->add('role_ids', 'Only the combination of admin and super-admin is allowed.');
                }
            }
        });
    }
}
