<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use App\Traits\ApiResponse;
use App\DTOs\Auth\RegisterUserDTO;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;

class RegisterService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function register(RegisterUserDTO $dto)
    {
        try {

            $userData = $dto->toArray();

            $userData['password'] = Hash::make($dto->password);
            $userData['verification_code'] = rand(1000, 9999);

            $user = $this->userRepository->create($userData);

            $user->assignRole('User');

            if ($user->email) {
                Mail::to($user->email)->send(new VerificationMail($user->verification_code));
            } elseif ($user->phone) {
                // Service to send SMS
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