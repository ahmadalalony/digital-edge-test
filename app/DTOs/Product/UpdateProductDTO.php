<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class UpdateProductDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public ?string $title_en = null,
        public ?string $title_ar = null,
        public ?string $description_en = null,
        public ?string $description_ar = null,
        public ?float $price = null,
        public ?string $primary_image = null,
        public ?array $other_images = null
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['id'],
            $data['title_en'] ?? null,
            $data['title_ar'] ?? null,
            $data['description_en'] ?? null,
            $data['description_ar'] ?? null,
            isset($data['price']) ? (float) $data['price'] : null,
            $data['primary_image'] ?? null,
            $data['other_images'] ?? null
        );
    }
}