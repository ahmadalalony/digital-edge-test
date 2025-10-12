<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\LogService;

class ActivityLogController extends Controller
{
    public function __construct(private DashboardService $dashboardService, private LogService $logService)
    {
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'activity'); // activity | notifications | errors
        $perPage = (int) $request->query('per_page', 15);

        $data = [
            'tab' => $type,
            'perPage' => $perPage,
        ];

        if ($type === 'notifications') {
            $data['notifications'] = $this->dashboardService->getNotificationsPaginated($perPage)
                ->appends($request->query());
        } elseif ($type === 'errors') {
            $rawErrorLines = $this->logService->tailLog(storage_path('logs/laravel.log'), 200);
            $data['parsedErrors'] = $this->logService->parseLogErrors($rawErrorLines);
        } else {
            // Simplified activity query without filtering, via service
            $activities = $this->dashboardService->getActivitiesPaginated($perPage)
                ->appends($request->query());

            $data['activities'] = $activities;
        }

        return view('dashboard.activity_logs.index', $data);
    }

}
