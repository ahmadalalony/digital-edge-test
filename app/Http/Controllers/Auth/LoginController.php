<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use App\DTOs\Auth\LoginDTO;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;

class LoginController extends Controller
{
    use ApiResponse;


    public function __construct(
        private LoginService $loginService
    ) {
    }

    public function login(LoginRequest $request)
    {
        $result = $this->loginService->login(
            LoginDTO::fromArray($request->validated())
        );

        if ($result['success']) {
            return $this->successResponse(
                [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                ],
                'Login successful'
            );
        }

        return $this->errorResponse('Login failed', 401, $result['error']);
    }

}