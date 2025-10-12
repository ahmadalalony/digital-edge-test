<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;

class ProductViewController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index()
    {
        return view('dashboard.products.index');
    }

    public function create()
    {
        return view('dashboard.products.create');
    }

    public function edit(int $id)
    {
        $result = $this->productService->findWithRelations($id, ['assignedUsers']);
        if (! $result['success']) {
            abort(404);
        }
        $product = $result['product'];

        return view('dashboard.products.edit', compact('product'));
    }
}
