<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\User\UpdateUserDTO;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\SendEmailRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use App\Models\User as UserModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $filters = [
            'country' => $request->query('country'),
            'city' => $request->query('city'),
        ];

        $users = $this->userService->list($perPage, $search, $filters);

        return $this->successResponse(UserResource::collection($users), 'Users retrieved successfully');
    }

    public function show(int $id)
    {
        $result = $this->userService->show($id);

        return $result['success']
            ? $this->successResponse(new UserResource($result['user']), 'User retrieved successfully')
            : $this->errorResponse('User not found', 404);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $result = $this->userService->create($validated);

        if (!$result['success']) {
            return $this->errorResponse('User creation failed', 400, $result['error']);
        }

        return $this->successResponse(new UserResource($result['user']), 'User created successfully');
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

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->all()), 'users.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function sendEmail(SendEmailRequest $request, UserModel $user)
    {
        $dto = \App\DTOs\User\SendEmailDTO::fromArray([
            'user' => $user,
            'subject' => $request->validated()['subject'],
            'message' => $request->validated()['message'],
            'asJson' => true,
        ]);

        $this->userService->sendCustomMail($dto);

        return $this->successResponse([], __('users.email_sent_successfully'));
    }
}


