<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ChangePasswordDTO;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\LogsActivityCustom;

class ChangePasswordService
{
    use LogsActivityCustom;
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

        $this->logActivity('Password Changed', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return ['success' => true, 'user' => $user];
    }
}