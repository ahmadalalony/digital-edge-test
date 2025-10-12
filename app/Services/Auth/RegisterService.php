<?php

namespace App\Services\Auth;

use App\DTOs\Auth\RegisterUserDTO;
use App\Mail\VerificationMail;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterService
{
    use LogsActivityCustom;

    public function __construct(private UserRepositoryInterface $userRepository)
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

            $this->logActivity(
                'User registered',
                ['user_id' => $user->id, 'user_email' => $user->email],
                $user
            );

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
