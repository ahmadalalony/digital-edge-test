<?php

namespace App\Services\Auth;

use App\DTOs\Auth\VerifyUserDTO;
use App\Repositories\UserRepository;

class VerifyService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function verify(VerifyUserDTO $dto): array
    {
        $user = $this->userRepository->findByIdentifier($dto->identifier);

        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        if ($user->is_verified) {
            return ['success' => false, 'error' => 'User already verified'];
        }

        if ($user->verification_code !== $dto->verification_code) {
            return ['success' => false, 'error' => 'Invalid verification code'];
        }

        $this->userRepository->verifyUser($user);

        return ['success' => true, 'user' => $user];
    }

}