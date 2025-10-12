<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $data = $this->dashboardService->getOverviewData();

        return view('dashboard.index', compact('data'));
    }

    public function overview(Request $request)
    {
        $data = $this->dashboardService->getOverviewData();

        return $this->successResponse($data, 'Dashboard statistics retrieved successfully');
    }
}
