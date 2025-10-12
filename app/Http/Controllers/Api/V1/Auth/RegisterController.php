<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\RegisterService;
use App\Traits\ApiResponse;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(private RegisterService $registerService) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->registerService->register(RegisterUserDTO::fromArray($request->validated()));

        if ($result['success']) {
            return $this->successResponse(
                new UserResource($result['user']),
                'User registered successfully. Verification code sent.'
            );
        }

        return $this->errorResponse('Registration failed', 500, $result['error']);
    }
}
