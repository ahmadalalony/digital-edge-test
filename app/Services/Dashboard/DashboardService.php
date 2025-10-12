<?php

namespace App\Services\Dashboard;

use App\Repositories\Contracts\DashboardRepositoryInterface;
use Exception;

class DashboardService
{
    public function __construct(private DashboardRepositoryInterface $dashboardRepository)
    {
    }

    public function getOverviewData(): array
    {
        try {
            return [
                'total_users' => $this->dashboardRepository->getTotalUsers(),
                'verified_users' => $this->dashboardRepository->getVerifiedUsers(),
                'total_products' => $this->dashboardRepository->getTotalProducts(),
                'new_users_last_month' => $this->dashboardRepository->getNewUsersLastMonth(),
                'products_last_week' => $this->dashboardRepository->getProductsCountLastWeek(),
            ];
        } catch (Exception $e) {
            throw new Exception('Error fetching dashboard data: ' . $e->getMessage());
        }
    }

    public function getNotificationsPaginated(int $perPage = 15)
    {
        return $this->dashboardRepository->getNotificationsPaginated($perPage);
    }

    public function getActivitiesPaginated(int $perPage = 15)
    {
        return $this->dashboardRepository->getActivitiesPaginated($perPage);
    }

    public function getUserNotifications(int $userId, int $limit = 50)
    {
        return $this->dashboardRepository->getUserNotifications($userId, $limit);
    }

    public function findUserNotification(int $userId, string $notificationId)
    {
        return $this->dashboardRepository->findUserNotification($userId, $notificationId);
    }

    public function markAllUserNotificationsRead(int $userId): void
    {
        $this->dashboardRepository->markAllUserNotificationsRead($userId);
    }

    public function getUserUnreadCount(int $userId): int
    {
        return $this->dashboardRepository->getUserUnreadCount($userId);
    }
}
