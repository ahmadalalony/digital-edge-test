<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class ChangePasswordDTO extends BaseDTO
{
    public function __construct(
        public int $userId,
        public string $current_password,
        public string $new_password
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['user_id'],
            $data['current_password'],
            $data['new_password']
        );
    }
}