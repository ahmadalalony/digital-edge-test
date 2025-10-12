<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class ChangePasswordDTO extends BaseDTO
{
    public function __construct(
        public int $userId,
        public string $currentPassword,
        public string $newPassword
    ) {
    }

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['user_id', 'current_password', 'new_password']);

        return new static(
            self::getValue($data, 'user_id', type: 'int'),
            self::getValue($data, 'current_password', type: 'string'),
            self::getValue($data, 'new_password', type: 'string')
        );
    }
}
