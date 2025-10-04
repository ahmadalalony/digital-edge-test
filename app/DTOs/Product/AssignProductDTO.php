<?php

namespace App\DTOs\Product;

use App\DTOs\BaseDTO;

class AssignProductDTO extends BaseDTO
{
    public function __construct(
        public int $product_id,
        public int $user_id
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['product_id'],
            $data['user_id']
        );
    }
}