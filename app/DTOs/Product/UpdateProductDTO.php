<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class UpdateProductDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public ?string $titleEn = null,
        public ?string $titleAr = null,
        public ?string $descriptionEn = null,
        public ?string $descriptionAr = null,
        public ?float $price = null,
        public ?string $primaryImage = null,
        public ?array $otherImages = null
    ) {
    }

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['id']);

        return new static(
            self::getValue($data, 'id', type: 'int'),
            self::getValue($data, 'title_en'),
            self::getValue($data, 'title_ar'),
            self::getValue($data, 'description_en'),
            self::getValue($data, 'description_ar'),
            self::getValue($data, 'price') ? self::getValue($data, 'price', type: 'float') : null,
            self::getValue($data, 'primary_image'),
            self::getValue($data, 'other_images')
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->titleEn !== null)
            $data['title_en'] = $this->titleEn;
        if ($this->titleAr !== null)
            $data['title_ar'] = $this->titleAr;
        if ($this->descriptionEn !== null)
            $data['description_en'] = $this->descriptionEn;
        if ($this->descriptionAr !== null)
            $data['description_ar'] = $this->descriptionAr;
        if ($this->price !== null)
            $data['price'] = $this->price;
        if ($this->primaryImage !== null)
            $data['primary_image'] = $this->primaryImage;
        if ($this->otherImages !== null)
            $data['other_images'] = $this->otherImages;

        return $data;
    }
}
