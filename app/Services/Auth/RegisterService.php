<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use App\Traits\ApiResponse;
use App\DTOs\Auth\RegisterUserDTO;

class RegisterService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function register(RegisterUserDTO $dto)
    {
        try {

            $userData = [
                'first_name' => $dto->first_name,
                'last_name' => $dto->last_name,
                'email' => $dto->email ?? null,
                'phone' => $dto->phone ?? null,
                'country' => $dto->country,
                'city' => $dto->city,
                'password' => Hash::make($dto->password),
                'is_verified' => false,
                'verification_code' => rand(1000, 9999),
            ];


            $user = $this->userRepository->create($userData);


            if ($user->email) {
                // Mail::to($user->email)->send(new VerificationMail($user->verification_code));
            } elseif ($user->phone) {
                // Service لإرسال SMS
            }

            return [
                'success' => true,
                'user' => $user,
                'verification_code' => $user->verification_code,
            ];



        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}