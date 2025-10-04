<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class UpdateUserDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $country = null,
        public ?string $city = null
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['id'],
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['country'] ?? null,
            $data['city'] ?? null
        );
    }
}