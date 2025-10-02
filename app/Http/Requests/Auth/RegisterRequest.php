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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|regex:/^[0-9]{8,15}$/|unique:users,phone',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Password must contain upper, lower, number and special character.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'Email already exists.',
            'email.email' => 'Email is not valid.',
            'phone.unique' => 'Phone already exists.',
            'country.required' => 'Country is required.',
            'city.required' => 'City is required.'
        ];
    }
}