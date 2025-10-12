<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class AssignProductDTO extends BaseDTO
{
    public function __construct(
        public int $productId,
        public int $userId
    ) {}

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['product_id', 'user_id']);

        return new static(
            self::getValue($data, 'product_id', type: 'int'),
            self::getValue($data, 'user_id', type: 'int')
        );
    }
}
