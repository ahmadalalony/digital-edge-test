<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\DTOs\User\UpdateUserDTO;
use Exception;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function list(int $perPage = 10)
    {
        return $this->userRepository->getAllPaginated($perPage);
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

            $updated = $this->userRepository->update($user, array_filter($dto->toArray()));
            return ['success' => true, 'user' => $updated];
        } catch (Exception $e) {
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
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}