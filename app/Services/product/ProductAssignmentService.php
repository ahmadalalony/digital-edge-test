<?php

namespace App\Services\Product;

use App\DTOs\Product\AssignProductDTO;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Traits\LogsActivityCustom;
class ProductAssignmentService
{
    use LogsActivityCustom;
    public function __construct(
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {
    }

    public function assign(AssignProductDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->product_id);
        $user = $this->userRepository->findById($dto->user_id);

        if (!$product || !$user) {
            return ['success' => false, 'error' => 'Invalid product or user'];
        }

        if (!$this->productRepository->assignToUser($product, $user)) {
            return ['success' => false, 'error' => 'Product already assigned to user'];
        }

        $this->logActivity('Product Assigned', ['product_id' => $product->id, 'user_id' => $user->id], $product);

        return ['success' => true, 'message' => 'Product assigned successfully'];
    }

    public function unassign(AssignProductDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->product_id);
        $user = $this->userRepository->findById($dto->user_id);

        if (!$product || !$user) {
            return ['success' => false, 'error' => 'Invalid product or user'];
        }

        if (!$this->productRepository->unassignFromUser($product, $user)) {
            return ['success' => false, 'error' => 'Product not assigned to user'];
        }

        $this->logActivity('Product Unassigned', ['product_id' => $product->id, 'user_id' => $user->id], $product);
        return ['success' => true, 'message' => 'Product unassigned successfully'];
    }

    public function getUserProducts(int $userId)
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        $products = $this->productRepository->getUserProducts($user);
        return ['success' => true, 'products' => $products];
    }
}