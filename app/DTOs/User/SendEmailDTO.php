<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;
use App\Models\User;

class SendEmailDTO extends BaseDTO
{
    public function __construct(
        public User $user,
        public string $subject,
        public string $message,
        public bool $asJson = false
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['user'],
            $data['subject'],
            $data['message'],
            $data['asJson'] ?? false
        );
    }
}
