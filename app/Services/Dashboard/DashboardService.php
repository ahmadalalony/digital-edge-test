<?php

namespace App\Services\Dashboard;

use App\Repositories\DashboardRepository;
use Exception;

class DashboardService
{
    public function __construct(private DashboardRepository $dashboardRepository)
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
            throw new Exception("Error fetching dashboard data: " . $e->getMessage());
        }
    }
}