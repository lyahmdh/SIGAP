<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',

            'email' => 'required|email|unique:users,email',

            'password' => [
                'required',
                'confirmed',
                'min:8'
            ]
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('_auth_mode', 'register');
        parent::failedValidation($validator);
    }
}