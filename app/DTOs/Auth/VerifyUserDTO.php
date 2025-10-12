<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class VerifyUserDTO extends BaseDTO
{
    public function __construct(
        public string $identifier, // email or phone
        public string $verificationCode
    ) {}

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['identifier', 'verification_code']);

        return new static(
            self::getValue($data, 'identifier', type: 'string'),
            self::getValue($data, 'verification_code', type: 'string')
        );
    }
}
