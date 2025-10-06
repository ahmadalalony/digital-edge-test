<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
class UserResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'is_verified' => (bool) $this->is_verified,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];

    }
}
