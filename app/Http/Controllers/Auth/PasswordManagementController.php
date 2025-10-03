<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\Auth\ChangePasswordService;
use App\DTOs\Auth\ChangePasswordDTO;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use App\DTOs\Auth\ForgotPasswordDTO;

class PasswordManagementController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ChangePasswordService $changePasswordService,
        private ForgotPasswordService $forgotPasswordService
    ) {
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $dto = new ChangePasswordDTO(
            Auth::id(),
            $request->current_password,
            $request->new_password
        );

        $result = $this->changePasswordService->changePassword($dto);

        if ($result['success']) {
            return $this->successResponse(
                new UserResource($result['user']),
                'Password changed successfully'
            );
        }

        return $this->errorResponse('Password change failed', 400, $result['error']);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = $this->forgotPasswordService->sendResetCode(
            ForgotPasswordDTO::fromArray($request->validated())
        );

        if ($result['success']) {
            return $this->successResponse(
                $result['code'],
                // $result['message'],
                'Forgot password request processed successfully'
            );
        }

        return $this->errorResponse('Forgot password failed', 400, $result['error']);
    }
}