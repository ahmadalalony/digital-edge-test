<?php

namespace App\Services\User;

use App\DTOs\User\AdminChangePasswordDTO;
use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\SendEmailDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\LogsActivityCustom;
use Exception;
use Illuminate\Support\Facades\Log;

class UserService
{
    use LogsActivityCustom;

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function list(int $perPage = 10, ?string $search = null, array $filters = [])
    {
        return $this->userRepository->getAllPaginated($perPage, $search, $filters);
    }

    public function show(int $id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function update(UpdateUserDTO $dto)
    {
        try {
            $user = $this->userRepository->findById($dto->id);
            if (!$user) {
                return ['success' => false, 'error' => 'User not found'];
            }

            $updateData = array_filter($dto->toArray());

            // Log what data we're trying to update
            Log::info('Updating user', [
                'user_id' => $dto->id,
                'update_data' => $updateData,
                'dto_data' => get_object_vars($dto)
            ]);

            $updated = $this->userRepository->update($user, $updateData);
            $this->logActivity('User Updated', ['user_id' => $user->id, 'user_email' => $user->email], $user);

            return ['success' => true, 'user' => $updated];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('User update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dto_data' => get_object_vars($dto),
                'user_id' => $dto->id
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function destroy(int $id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return ['success' => false, 'error' => 'User not found'];
            }

            $this->userRepository->delete($user);
            $this->logActivity('User Deleted', ['user_id' => $user->id, 'user_email' => $user->email], $user);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function create(array $data)
    {
        try {
            $user = $this->userRepository->create($data);
            $this->logActivity('User Created', ['user_id' => $user->id, 'user_email' => $user->email], $user);
            return ['success' => true, 'user' => $user];
        } catch (Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage(), 'data' => $data]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function findWithRelations(int $id, array $relations = [])
    {
        $user = $this->userRepository->findByIdWithRelations($id, $relations);
        if (!$user) {
            return ['success' => false, 'error' => 'User not found'];
        }
        return ['success' => true, 'user' => $user];
    }

    public function sendCustomMail(SendEmailDTO $dto): void
    {
        app(UserMailService::class)->sendCustomMail($dto->user, $dto->subject, $dto->message);
    }

    public function adminChangePassword(AdminChangePasswordDTO $dto)
    {
        try {
            $user = $this->userRepository->findById($dto->userId);
            if (!$user) {
                return ['success' => false, 'error' => 'User not found'];
            }


            $this->userRepository->updatePassword($user, $dto->newPassword);

            $this->logActivity('Password Changed by Admin', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'admin_id' => $dto->adminId
            ], $user);

            Log::info('Admin password change successful', [
                'user_id' => $dto->userId,
                'admin_id' => $dto->adminId,
                'user_email' => $user->email
            ]);

            return ['success' => true, 'message' => 'Password changed successfully'];

        } catch (Exception $e) {

            Log::error('Admin password change failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $dto->userId,
                'admin_id' => $dto->adminId
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
