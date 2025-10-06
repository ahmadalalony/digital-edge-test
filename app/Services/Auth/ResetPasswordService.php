<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ResetPasswordDTO;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Traits\LogsActivityCustom;

class ResetPasswordService
{
    use LogsActivityCustom;
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function resetPassword(ResetPasswordDTO $dto): array
    {
        $key = "reset_attempts:{$dto->user_id}";

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return [
                'success' => false,
                'error' => "Too many reset attempts. Try again in {$seconds} seconds.",
            ];
        }

        $user = $this->userRepository->findById($dto->user_id);

        if (!$user) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));
            return ['success' => false, 'error' => 'User not found'];
        }

        if (!$this->userRepository->checkVerificationCode($user, $dto->verification_code)) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));
            return ['success' => false, 'error' => 'Invalid verification code'];
        }

        $this->userRepository->updatePassword($user, $dto->new_password);

        RateLimiter::clear($key);

        $this->logActivity('Password Reset', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return ['success' => true, 'user' => $user];
    }
}