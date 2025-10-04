<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => 'sometimes|required|string|max:255',
            'title_ar' => 'sometimes|required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'primary_image' => 'nullable|string',
            'other_images' => 'nullable|array',
        ];
    }
}