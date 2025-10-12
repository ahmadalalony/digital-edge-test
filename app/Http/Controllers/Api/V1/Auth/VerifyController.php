<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\VerifyUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\VerifyService;
use App\Traits\ApiResponse;

class VerifyController extends Controller
{
    use ApiResponse;

    public function __construct(private VerifyService $verifyService)
    {
    }

    public function verify(VerifyRequest $request)
    {
        $result = $this->verifyService->verify(VerifyUserDTO::fromArray($request->validated()));

        if ($result['success']) {
            return $this->successResponse(new UserResource($result['user']), 'User verified successfully.');
        }

        return $this->errorResponse('Verification failed', 400, $result['error']);
    }
}


