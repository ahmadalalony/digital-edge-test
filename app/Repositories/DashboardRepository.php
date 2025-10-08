<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getTotalUsers(): int
    {
        return User::count();
    }

    public function getVerifiedUsers(): int
    {
        return User::where('is_verified', true)->count();
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
                Carbon::now()->subDays(6 - $i)->toDateString() => $data->get(Carbon::now()->subDays(6 - $i)->toDateString(), 0)
            ]);

        return $dates->toArray();
    }
}