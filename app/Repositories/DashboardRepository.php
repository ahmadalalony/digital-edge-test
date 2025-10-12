<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalUsers(): int
    {
        return User::count();
    }

    public function getVerifiedUsers(): int
    {
        return User::whereNotNull('email_verified_at')->count();
    }

    public function getTotalProducts(): int
    {
        return Product::count();
    }

    public function getNewUsersLastMonth(): int
    {
        return User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
    }

    public function getProductsCountLastWeek(): array
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $data = Product::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $dates = collect(range(0, 6))
            ->mapWithKeys(fn($i) => [
                Carbon::now()->subDays(6 - $i)->toDateString() => $data->get(Carbon::now()->subDays(6 - $i)->toDateString(), 0),
            ]);

        return $dates->toArray();
    }

    public function getNotificationsPaginated(int $perPage = 15)
    {
        return DatabaseNotification::query()->latest()->paginate($perPage);
    }

    public function getActivitiesPaginated(int $perPage = 15)
    {
        return \Spatie\Activitylog\Models\Activity::query()->with(['causer'])->latest()->paginate($perPage);
    }

    public function getUserNotifications(int $userId, int $limit = 50)
    {
        return DatabaseNotification::query()
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function findUserNotification(int $userId, string $notificationId): ?DatabaseNotification
    {
        return DatabaseNotification::query()
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->where('id', $notificationId)
            ->first();
    }

    public function markAllUserNotificationsRead(int $userId): void
    {
        DatabaseNotification::query()
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function getUserUnreadCount(int $userId): int
    {
        return DatabaseNotification::query()
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->count();
    }
}
