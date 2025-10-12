<?php

namespace App\Services\Product;

use App\DTOs\Product\AssignProductDTO;
use App\Notifications\ProductAssignedNotification;
use App\Notifications\ProductUnassignedNotification;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;

class ProductAssignmentService
{
    use LogsActivityCustom;

    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function assign(AssignProductDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);
        $user = $this->userRepository->findById($dto->userId);

        if (! $product || ! $user) {
            return ['success' => false, 'error' => 'Invalid product or user'];
        }

        if (! $this->productRepository->assignToUser($product, $user)) {
            return ['success' => false, 'error' => 'Product already assigned to user'];
        }

        $this->logActivity('Product Assigned', ['product_id' => $product->id, 'user_id' => $user->id], $product);

        $user->notify(new ProductAssignedNotification(
            productName: $product->title ?? 'Unknown Product',
            assignedBy: auth()->user()->first_name ?? 'Admin'
        ));

        return ['success' => true, 'message' => 'Product assigned successfully'];
    }

    public function unassign(AssignProductDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);
        $user = $this->userRepository->findById($dto->userId);

        if (! $product || ! $user) {
            return ['success' => false, 'error' => 'Invalid product or user'];
        }

        if (! $this->productRepository->unassignFromUser($product, $user)) {
            return ['success' => false, 'error' => 'Product not assigned to user'];
        }

        $this->logActivity('Product Unassigned', ['product_id' => $product->id, 'user_id' => $user->id], $product);

        $user->notify(new ProductUnassignedNotification(
            productName: $product->title ?? 'Unknown Product',
            unassignedBy: auth()->user()->first_name ?? 'Admin'
        ));

        return ['success' => true, 'message' => 'Product unassigned successfully'];
    }

    public function getUserProducts(int $userId)
    {
        $user = $this->userRepository->findById($userId);
        if (! $user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        $products = $this->productRepository->getUserProducts($user);

        return ['success' => true, 'products' => $products];
    }
}
