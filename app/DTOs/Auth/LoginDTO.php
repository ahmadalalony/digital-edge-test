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
        self::validateRequiredFields($data, ['identifier', 'password']);

        return new static(
            self::getValue($data, 'identifier', type: 'string'),
            self::getValue($data, 'password', type: 'string')
        );
    }
}
