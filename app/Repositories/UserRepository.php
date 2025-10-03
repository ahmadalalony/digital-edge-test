<?php

namespace App\Repositories;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByIdentifier(string $identifier): ?User
    {
        return User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();
    }

    public function verifyUser(User $user): User
    {
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return $user;
    }

    public function updatePassword(User $user, string $newPassword): User
    {
        $user->password = Hash::make($newPassword);
        $user->save();

        return $user;
    }

    public function updateVerificationCode(User $user, string $code): User
    {
        $user->verification_code = $code;
        $user->save();

        return $user;
    }
}