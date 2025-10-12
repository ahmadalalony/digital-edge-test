<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function getAllPaginated(int $perPage = 10, ?string $search = null, array $filters = []);

    public function findById(int $id): ?User;

    public function findByIdWithRelations(int $id, array $relations = []): ?User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function findByIdentifier(string $identifier): ?User;

    public function verifyUser(User $user): User;

    public function updatePassword(User $user, string $newPassword): User;

    public function updateVerificationCode(User $user, string $code): User;

    public function checkVerificationCode(User $user, string $code): bool;
}
