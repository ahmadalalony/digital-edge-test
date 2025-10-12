<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserMailService
{
    public function sendCustomMail(User $user, string $subject, string $message): void
    {
        Mail::to($user->email)->send(new \App\Mail\CustomUserMail($subject, $message, $user));
    }
}
