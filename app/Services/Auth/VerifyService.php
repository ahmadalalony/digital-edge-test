<?php

namespace App\Services\Auth;

use App\DTOs\Auth\VerifyUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;

class VerifyService
{
    use LogsActivityCustom;

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function verify(VerifyUserDTO $dto): array
    {
        $user = $this->userRepository->findByIdentifier($dto->identifier);

        if (! $user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        if ($user->verification_code !== $dto->verificationCode) {
            return ['success' => false, 'error' => 'Invalid verification code'];
        }

        $this->userRepository->verifyUser($user);

        $this->logActivity('User Verified', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return ['success' => true, 'user' => $user];
    }
}
