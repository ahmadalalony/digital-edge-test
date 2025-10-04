<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class ResetPasswordDTO extends BaseDTO
{
    public function __construct(
        public int $user_id,
        public string $verification_code,
        public string $new_password
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['user_id'],
            $data['verification_code'],
            $data['new_password']
        );
    }
}