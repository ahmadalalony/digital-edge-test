<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => 'required|string', // email or phone
            'verification_code' => 'required|string|min:4|max:6',
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'Identifier is required.',
            'verification_code.required' => 'Verification code is required.',
            'verification_code.min' => 'Verification code must be at least 4 characters long.',
            'verification_code.max' => 'Verification code must be at most 6 characters long.',
        ];

    }
}
