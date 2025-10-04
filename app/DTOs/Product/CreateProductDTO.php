<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class CreateProductDTO extends BaseDTO
{
    public function __construct(
        public string $title_en,
        public string $title_ar,
        public ?string $description_en,
        public ?string $description_ar,
        public float $price,
        public ?string $primary_image,
        public ?array $other_images,
        public int $created_by
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['title_en'],
            $data['title_ar'],
            $data['description_en'] ?? null,
            $data['description_ar'] ?? null,
            (float) $data['price'],
            $data['primary_image'] ?? null,
            $data['other_images'] ?? [],
            $data['created_by'] ?? 0
        );
    }
}