<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\User;

interface ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10, ?string $search = null);

    public function findById(int $id): ?Product;

    public function findByIdWithRelations(int $id, array $relations = []): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): bool;

    public function assignToUser(Product $product, User $user): bool;

    public function unassignFromUser(Product $product, User $user): bool;

    public function getUserProducts(User $user);
}
