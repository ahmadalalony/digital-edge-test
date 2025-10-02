<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class RegisterUserDTO extends BaseDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public ?string $email,
        public ?string $phone,
        public string $country,
        public string $city,
        public string $password
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['first_name'],
            $data['last_name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['country'],
            $data['city'],
            $data['password']
        );
    }
}