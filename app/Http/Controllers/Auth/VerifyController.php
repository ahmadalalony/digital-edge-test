<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\VerifyService;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use App\DTOs\Auth\VerifyUserDTO;
use App\Http\Requests\Auth\VerifyRequest;

class VerifyController extends Controller
{
    use ApiResponse;

    public function __construct(private VerifyService $verifyService)
    {
    }

    public function verify(VerifyRequest $request)
    {
        $result = $this->verifyService->verify(
            VerifyUserDTO::fromArray($request->validated())
        );

        if ($result['success']) {
            return $this->successResponse(
                new UserResource($result['user']),
                'User verified successfully.'
            );
        }

        return $this->errorResponse('Verification failed', 400, $result['error']);
    }
}