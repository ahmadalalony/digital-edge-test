<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ChangePasswordDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService
{
    use LogsActivityCustom;

    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function changePassword(ChangePasswordDTO $dto): array
    {
        $user = $this->userRepository->findById($dto->userId);

        if (! $user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        if (! Hash::check($dto->currentPassword, $user->password)) {
            return ['success' => false, 'error' => 'Current password is incorrect'];
        }

        $this->userRepository->updatePassword($user, $dto->newPassword);

        $this->logActivity('Password Changed', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return ['success' => true, 'user' => $user];
    }
}
