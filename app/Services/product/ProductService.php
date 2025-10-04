<?php

namespace App\Services\Product;

use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function list(int $perPage = 10)
    {
        return $this->productRepository->getAllPaginated($perPage);
    }

    public function store(CreateProductDTO $dto)
    {
        try {
            $data = $dto->toArray();
            $data['created_by'] = Auth::id();

            $product = $this->productRepository->create($data);

            return ['success' => true, 'product' => $product];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update(UpdateProductDTO $dto)
    {
        try {
            $product = $this->productRepository->findById($dto->id);

            if (!$product) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            $updated = $this->productRepository->update($product, array_filter($dto->toArray()));

            return ['success' => true, 'product' => $updated];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $product = $this->productRepository->findById($id);

            if (!$product) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            $this->productRepository->delete($product);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}