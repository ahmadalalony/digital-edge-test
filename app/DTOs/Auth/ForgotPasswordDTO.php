<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class ForgotPasswordDTO extends BaseDTO
{
    public function __construct(
        public string $identifier
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['identifier']
        );
    }
}