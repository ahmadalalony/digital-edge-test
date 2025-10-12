<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ResetPasswordDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Illuminate\Support\Facades\RateLimiter;

class ResetPasswordService
{
    use LogsActivityCustom;

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function resetPassword(ResetPasswordDTO $dto): array
    {
        $key = "reset_attempts:{$dto->userId}";

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return [
                'success' => false,
                'error' => "Too many reset attempts. Try again in {$seconds} seconds.",
            ];
        }

        $user = $this->userRepository->findById($dto->userId);

        if (!$user) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));

            return ['success' => false, 'error' => 'User not found'];
        }

        if (!$this->userRepository->checkVerificationCode($user, $dto->verificationCode)) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));

            return ['success' => false, 'error' => 'Invalid verification code'];
        }

        $this->userRepository->updatePassword($user, $dto->newPassword);

        RateLimiter::clear($key);

        $this->logActivity('Password Reset', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return ['success' => true, 'user' => $user];
    }
}
