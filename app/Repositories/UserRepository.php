<?php

namespace App\Repositories;

use App\Models\User;

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
        $user->is_verified = true;
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return $user;
    }

}