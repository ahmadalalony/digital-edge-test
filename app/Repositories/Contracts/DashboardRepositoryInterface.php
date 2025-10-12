<?php

namespace App\Repositories\Contracts;

use Illuminate\Notifications\DatabaseNotification;

interface DashboardRepositoryInterface
{
    public function getTotalUsers(): int;
    public function getVerifiedUsers(): int;
    public function getTotalProducts(): int;
    public function getNewUsersLastMonth(): int;
    public function getProductsCountLastWeek(): array;
    public function getNotificationsPaginated(int $perPage = 15);
    public function getActivitiesPaginated(int $perPage = 15);
    public function getUserNotifications(int $userId, int $limit = 50);
    public function findUserNotification(int $userId, string $notificationId): ?DatabaseNotification;
    public function markAllUserNotificationsRead(int $userId): void;
    public function getUserUnreadCount(int $userId): int;
}


