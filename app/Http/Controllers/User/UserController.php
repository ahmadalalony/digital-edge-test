<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\UserService;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService)
    {
        $this->middleware(['auth:sanctum', 'role:Admin']);
    }

    public function index()
    {
        $users = $this->userService->list();
        return $this->successResponse(UserResource::collection($users), 'Users retrieved successfully');
    }

    public function show(int $id)
    {
        $result = $this->userService->show($id);
        return $result['success']
            ? $this->successResponse(new UserResource($result['user']), 'User retrieved successfully')
            : $this->errorResponse('User not found', 404);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $dto = UpdateUserDTO::fromArray(array_merge($request->validated(), ['id' => $id]));
        $result = $this->userService->update($dto);

        return $result['success']
            ? $this->successResponse(new UserResource($result['user']), 'User updated successfully')
            : $this->errorResponse('User update failed', 400, $result['error']);
    }

    public function destroy(int $id)
    {
        $result = $this->userService->destroy($id);
        return $result['success']
            ? $this->successResponse([], 'User deleted successfully')
            : $this->errorResponse('User deletion failed', 400, $result['error']);
    }
}