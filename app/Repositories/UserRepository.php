<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getAllPaginated(int $perPage = 10, ?string $search = null, array $filters = [])
    {
        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['country'])) {
            $query->where('country', 'like', "%{$filters['country']}%");
        }
        if (! empty($filters['city'])) {
            $query->where('city', 'like', "%{$filters['city']}%");
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByIdWithRelations(int $id, array $relations = []): ?User
    {
        return User::with($relations)->find($id);
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

    public function createPlain(array $data): User
    {
        return User::create($data);
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
