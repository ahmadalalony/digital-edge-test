<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10, ?string $search = null)
    {
        $query = Product::with('creator');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findByIdWithRelations(int $id, array $relations = []): ?Product
    {
        return Product::with($relations)->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function assignToUser(Product $product, User $user): bool
    {
        if ($product->assignedUsers()->where('user_id', $user->id)->exists()) {
            return false;
        }
        $product->assignedUsers()->attach($user->id);

        return true;
    }

    public function unassignFromUser(Product $product, User $user): bool
    {
        if (!$product->assignedUsers()->where('user_id', $user->id)->exists()) {
            return false;
        }
        $product->assignedUsers()->detach($user->id);

        return true;
    }

    public function getUserProducts(User $user)
    {
        return $user->assignedProducts()->latest()->paginate(10);
    }
}
