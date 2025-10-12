<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private DashboardService $dashboardService) {}

    public function overview()
    {
        $data = $this->dashboardService->getOverviewData();

        return $this->successResponse($data, 'Dashboard statistics retrieved successfully');
    }
}
