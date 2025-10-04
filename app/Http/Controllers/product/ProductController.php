<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Services\Product\ProductService;
use App\Http\Resources\ProductResource;
use App\Traits\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $productService)
    {
    }

    public function index()
    {
        $products = $this->productService->list();
        return $this->successResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function store(CreateProductRequest $request)
    {
        $result = $this->productService->store(CreateProductDTO::fromArray($request->validated()));

        if ($result['success']) {
            return $this->successResponse(
                new ProductResource($result['product']),
                'Product created successfully'
            );
        }

        return $this->errorResponse('Product creation failed', 500, $result['error']);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $validated = array_merge($request->validated(), ['id' => $id]);

        $result = $this->productService->update(UpdateProductDTO::fromArray($validated));

        if ($result['success']) {
            return $this->successResponse(
                new ProductResource($result['product']),
                'Product updated successfully'
            );
        }

        return $this->errorResponse('Product update failed', 500, $result['error']);
    }

    public function destroy(int $id)
    {
        $result = $this->productService->destroy($id);

        if ($result['success']) {
            return $this->successResponse([], 'Product deleted successfully');
        }

        return $this->errorResponse('Product deletion failed', 500, $result['error']);
    }
}