<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\ChangePasswordDTO;
use App\DTOs\Auth\ForgotPasswordDTO;
use App\DTOs\Auth\ResetPasswordDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\ChangePasswordService;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\ResetPasswordService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class PasswordManagementController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ChangePasswordService $changePasswordService,
        private ForgotPasswordService $forgotPasswordService,
        private ResetPasswordService $resetPasswordService
    ) {}

    public function changePassword(ChangePasswordRequest $request)
    {
        $dto = new ChangePasswordDTO(Auth::id(), $request->current_password, $request->new_password);

        $result = $this->changePasswordService->changePassword($dto);

        if ($result['success']) {
            return $this->successResponse(new UserResource($result['user']), 'Password changed successfully');
        }

        return $this->errorResponse('Password change failed', 400, $result['error']);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = $this->forgotPasswordService->sendResetCode(ForgotPasswordDTO::fromArray($request->validated()));

        if ($result['success']) {
            return $this->successResponse($result['user'], 'Forgot password request processed successfully');
        }

        return $this->errorResponse('Forgot password failed', 400, $result['error']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = new ResetPasswordDTO(Auth::id(), $request->verification_code, $request->new_password);

        $result = $this->resetPasswordService->resetPassword($dto);

        if ($result['success']) {
            return $this->successResponse(new UserResource($result['user']), 'Password reset successfully');
        }

        return $this->errorResponse('Password reset failed', 400, $result['error']);
    }
}
