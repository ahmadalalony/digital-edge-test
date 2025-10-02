<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use App\DTOs\Auth\RegisterUserDTO;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(private RegisterService $registerService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->registerService->register(RegisterUserDTO::fromArray($request->validated()));

        if ($result['success']) {
            return $this->successResponse(
                [
                    new UserResource($result['user']),
                    'verification_code' => $result['verification_code']
                ],
                'User registered successfully. Verification code sent.'
            );
        }

        return $this->errorResponse('Registration failed', 500, $result['error']);
    }
}