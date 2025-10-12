<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ForgotPasswordDTO;
use App\Mail\ResetPasswordMail;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordService
{
    use LogsActivityCustom;

    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function sendResetCode(ForgotPasswordDTO $dto): array
    {

        $user = $this->userRepository->findByIdentifier($dto->identifier);

        if (! $user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        $code = rand(1000, 9999);
        $this->userRepository->updateVerificationCode($user, $code);

        if ($user->email) {
            Mail::to($user->email)->send(new ResetPasswordMail($code));
        } elseif ($user->phone) {
            // send sms
        }

        $this->logActivity('Reset Code Sent', ['user_id' => $user->id, 'user_email' => $user->email], $user);

        return [
            'success' => true,
            'message' => 'Reset code sent successfully',
            'user' => $user,
        ];
    }
}
