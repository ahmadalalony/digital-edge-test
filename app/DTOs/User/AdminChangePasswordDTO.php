<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class AdminChangePasswordDTO extends BaseDTO
{
    public function __construct(
        public int $userId,
        public string $newPassword,
        public int $adminId
    ) {
    }

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['user_id', 'new_password', 'admin_id']);

        return new static(
            self::getValue($data, 'user_id', type: 'int'),
            self::getValue($data, 'new_password', type: 'string'),
            self::getValue($data, 'admin_id', type: 'int')
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'new_password' => $this->newPassword,
            'admin_id' => $this->adminId,
        ];
    }
}
