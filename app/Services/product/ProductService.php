<?php

namespace App\Services\Product;

use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductService
{
    use LogsActivityCustom;

    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function list(int $perPage = 10, ?string $search = null)
    {
        return $this->productRepository->getAllPaginated($perPage, $search);
    }

    public function store(CreateProductDTO $dto)
    {
        try {
            $data = $dto->toArray();
            $data['created_by'] = Auth::id();

            $product = $this->productRepository->create($data);

            $this->logActivity('Product Created', ['product_id' => $product->id, 'user_id' => $data['created_by']], $product);

            return ['success' => true, 'product' => $product];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $dto->toArray(),
                'user_id' => Auth::id(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update(UpdateProductDTO $dto)
    {
        try {
            $product = $this->productRepository->findById($dto->id);

            if (! $product) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            $updated = $this->productRepository->update($product, array_filter($dto->toArray()));

            $this->logActivity('Product Updated', ['product_id' => $product->id, 'user_id' => Auth::id()], $product);

            return ['success' => true, 'product' => $updated];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Product update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $dto->id,
                'data' => $dto->toArray(),
                'user_id' => Auth::id(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $product = $this->productRepository->findById($id);

            if (! $product) {
                return ['success' => false, 'error' => 'Product not found'];
            }

            $this->productRepository->delete($product);

            $this->logActivity('Product Deleted', ['product_id' => $product->id, 'user_id' => Auth::id()], $product);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function findWithRelations(int $id, array $relations = [])
    {
        $product = $this->productRepository->findByIdWithRelations($id, $relations);
        if (! $product) {
            return ['success' => false, 'error' => 'Product not found'];
        }

        return ['success' => true, 'product' => $product];
    }
}
