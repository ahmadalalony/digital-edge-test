<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\UpdateProductDTO;
use App\Services\Product\ProductService;
use App\Http\Resources\ProductResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $search = $request->input('search.value');
            $length = (int) $request->input('length', 10);
            $start = (int) $request->input('start', 0);
            $page = (int) floor($start / max($length, 1)) + 1;

            $request->merge(['page' => $page]);
            $products = $this->productService->list($length, $search);

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $products->total(),
                'recordsFiltered' => $products->total(),
                'data' => ProductResource::collection($products->items())->resolve()
            ]);
        }

        $products = $this->productService->list();
        return $this->successResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function store(CreateProductRequest $request)
    {
        $result = $this->productService->store(CreateProductDTO::fromArray($request->validated()));

        if ($result['success']) {
            return redirect()->route('admin_products_index')->with('success', __('dashboard.Product created successfully'));
        }

        return redirect()->back()->withErrors(['error' => $result['error']])->withInput();
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $validated = array_merge($request->validated(), ['id' => $id]);

        $result = $this->productService->update(UpdateProductDTO::fromArray($validated));

        if ($result['success']) {
            return redirect()->route('admin_products_edit', $id)->with('success', __('dashboard.Product updated successfully'));
        }

        return redirect()->back()->withErrors(['error' => $result['error']])->withInput();
    }

    public function destroy(int $id)
    {
        $result = $this->productService->destroy($id);

        if ($result['success']) {
            return $this->successResponse([], 'Product deleted successfully');
        }

        return $this->errorResponse('Product deletion failed', 500, $result['error']);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request->all()), 'products.csv', \Maatwebsite\Excel\Excel::CSV);
    }

}