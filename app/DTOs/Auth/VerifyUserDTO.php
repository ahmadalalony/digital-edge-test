<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class VerifyUserDTO extends BaseDTO
{
    public function __construct(
        public string $identifier, // email or phone
        public string $verification_code
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['identifier'],
            $data['verification_code']
        );
    }
}