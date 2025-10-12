<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class ResetPasswordDTO extends BaseDTO
{
    public function __construct(
        public int $userId,
        public string $verificationCode,
        public string $newPassword
    ) {
    }

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['user_id', 'verification_code', 'new_password']);

        return new static(
            self::getValue($data, 'user_id', type: 'int'),
            self::getValue($data, 'verification_code', type: 'string'),
            self::getValue($data, 'new_password', type: 'string')
        );
    }
}
