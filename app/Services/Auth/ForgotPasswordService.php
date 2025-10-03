<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ForgotPasswordDTO;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;


class ForgotPasswordService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function sendResetCode(ForgotPasswordDTO $dto): array
    {

        $user = $this->userRepository->findByIdentifier($dto->identifier);

        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        $code = rand(1000, 9999);
        $this->userRepository->updateVerificationCode($user, $code);

        if ($user->email) {
            Mail::to($user->email)->send(new ResetPasswordMail($code));
        } elseif ($user->phone) {
            //send sms
        }

        return [
            'success' => true,
            'message' => 'Reset code sent successfully',
            'user' => $user,
            'code' => $code, // don't return the code in the response after finish development
        ];
    }
}