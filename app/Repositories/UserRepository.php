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

    public function getAllPaginated(int $perPage = 10)
    {
        return User::paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
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
        $user->verification_code = null;
        $user->save();

        return $user;
    }

    public function updateVerificationCode(User $user, string $code): User
    {
        $user->verification_code = $code;
        $user->save();

        return $user;
    }


    public function checkVerificationCode(User $user, string $code): bool
    {
        return $user->verification_code === $code;
    }



}