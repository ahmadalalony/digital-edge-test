<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginService
{
    use LogsActivityCustom;

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function login(LoginDTO $dto): array
    {
        $identifier = $dto->identifier;
        $key = 'login_attempts:'.Str::lower($identifier);

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return [
                'success' => false,
                'error' => "Too many attempts. Try again in {$seconds} seconds.",
            ];
        }

        $user = $this->userRepository->findByIdentifier($identifier);

        if (! $user) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));

            return ['success' => false, 'error' => 'User not found'];
        }

        if (! $user->email_verified_at) {
            return ['success' => false, 'error' => 'User not verified'];
        }

        if (! Hash::check($dto->password, $user->password)) {
            RateLimiter::hit($key, now()->addMinutes(rand(15, 30)));

            return ['success' => false, 'error' => 'Invalid credentials'];
        }

        RateLimiter::clear($key);

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->logActivity('User Logged In', ['identifier' => $identifier], $user);

        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }
}
