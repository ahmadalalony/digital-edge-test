<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class LoginDTO extends BaseDTO
{
    public function __construct(
        public string $identifier,
        public string $password
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['identifier'],
            $data['password']
        );
    }
}