<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ProductResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'slug' => $this->slug,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'price' => $this->price,
            'primary_image' => $this->primary_image,
            'other_images' => $this->other_images,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'full_name' => $this->creator->full_name,
                    'email' => $this->creator->email,
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];

    }
}
