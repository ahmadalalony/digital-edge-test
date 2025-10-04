<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ChangePasswordDTO;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class ChangePasswordService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function changePassword(ChangePasswordDTO $dto): array
    {
        $user = $this->userRepository->findById($dto->userId);

        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        if (!Hash::check($dto->current_password, $user->password)) {
            return ['success' => false, 'error' => 'Current password is incorrect'];
        }

        $this->userRepository->updatePassword($user, $dto->new_password);

        return ['success' => true, 'user' => $user];
    }
}