<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            // 'email' => "sometimes|nullable|email|unique:users,email,{$this->id}",
            // 'phone' => "sometimes|nullable|string|max:20|unique:users,phone,{$this->id}",
            'email' => 'sometimes|nullable|email|unique:users,email,'.$this->route('id'),
            'phone' => 'sometimes|nullable|string|max:20|unique:users,phone,'.$this->route('id'),
            'country' => 'sometimes|nullable|string|max:100',
            'city' => 'sometimes|nullable|string|max:100',
        ];
    }
}
