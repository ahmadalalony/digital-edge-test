<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\Product\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $products = $this->productService->list($perPage, $search);

        return $this->successResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function store(CreateProductRequest $request)
    {
        $result = $this->productService->store(CreateProductDTO::fromArray($request->validated()));

        return $result['success']
            ? $this->successResponse(new ProductResource($result['product']), 'Product created successfully')
            : $this->errorResponse('Product creation failed', 500, $result['error']);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $validated = array_merge($request->validated(), ['id' => $id]);
        $result = $this->productService->update(UpdateProductDTO::fromArray($validated));

        return $result['success']
            ? $this->successResponse(new ProductResource($result['product']), 'Product updated successfully')
            : $this->errorResponse('Product update failed', 500, $result['error']);
    }

    public function destroy(int $id)
    {
        $result = $this->productService->destroy($id);

        return $result['success']
            ? $this->successResponse([], 'Product deleted successfully')
            : $this->errorResponse('Product deletion failed', 500, $result['error']);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request->all()), 'products.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
