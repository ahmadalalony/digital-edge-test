<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class CreateProductDTO extends BaseDTO
{
    public function __construct(
        public string $titleEn,
        public string $titleAr,
        public ?string $descriptionEn,
        public ?string $descriptionAr,
        public float $price,
        public ?string $primaryImage,
        public ?array $otherImages,
        public int $createdBy
    ) {}

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['title_en', 'title_ar', 'price']);

        return new static(
            self::getValue($data, 'title_en', type: 'string'),
            self::getValue($data, 'title_ar', type: 'string'),
            self::getValue($data, 'description_en'),
            self::getValue($data, 'description_ar'),
            self::getValue($data, 'price', type: 'float'),
            self::getValue($data, 'primary_image'),
            self::getValue($data, 'other_images', [], 'array'),
            self::getValue($data, 'created_by', 0, 'int')
        );
    }

    public function toArray(): array
    {
        return [
            'title_en' => $this->titleEn,
            'title_ar' => $this->titleAr,
            'description_en' => $this->descriptionEn,
            'description_ar' => $this->descriptionAr,
            'price' => $this->price,
            'primary_image' => $this->primaryImage,
            'other_images' => $this->otherImages,
            'created_by' => $this->createdBy,
        ];
    }
}
