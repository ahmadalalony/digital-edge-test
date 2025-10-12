<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AdminChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization will be handled by middleware (Admin role)
    }

    public function rules(): array
    {
        return [
            'new_password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.required' => __('validation.required', ['attribute' => __('dashboard.New Password')]),
            'new_password.min' => __('validation.min.string', ['attribute' => __('dashboard.New Password'), 'min' => 8]),
            'new_password.confirmed' => __('validation.confirmed', ['attribute' => __('dashboard.New Password')]),
        ];
    }
}
