<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Product\AssignProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\AssignProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\Product\ProductAssignmentService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class ProductAssignmentController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductAssignmentService $service)
    {
        $this->middleware('role:Admin')->only(['assign', 'unassign']);
        $this->middleware('role:User|Admin')->only(['userProducts']);
    }

    public function assign(AssignProductRequest $request)
    {

        $result = $this->service->assign(AssignProductDTO::fromArray($request->validated()));

        return $result['success']
            ? $this->successResponse([], $result['message'])
            : $this->errorResponse('Assignment failed', 400, $result['error']);
    }

    public function unassign(AssignProductRequest $request)
    {

        $result = $this->service->unassign(AssignProductDTO::fromArray($request->validated()));

        return $result['success']
            ? $this->successResponse([], $result['message'])
            : $this->errorResponse('Unassignment failed', 400, $result['error']);
    }

    public function userProducts()
    {
        $result = $this->service->getUserProducts(Auth::id());

        return $result['success']
            ? $this->successResponse(ProductResource::collection($result['products']), 'User products retrieved successfully')
            : $this->errorResponse('Failed to get user products', 400, $result['error']);
    }
}
