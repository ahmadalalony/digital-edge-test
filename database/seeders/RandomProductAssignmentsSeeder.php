<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RandomProductAssignmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $productIds = Product::query()->pluck('id')->all();
            if (empty($productIds)) {
                return;
            }

            $maxAttachPerUser = min(10, count($productIds));

            User::query()->chunkById(200, function ($users) use ($productIds, $maxAttachPerUser) {
                foreach ($users as $user) {
                    $count = random_int(0, $maxAttachPerUser);
                    if ($count === 0) {
                        continue;
                    }

                    $selection = $count === 1
                        ? [Arr::random($productIds)]
                        : Arr::random($productIds, $count);

                    // Ensure we always pass a flat array of IDs
                    if (! is_array($selection)) {
                        $selection = [$selection];
                    }

                    $user->assignedProducts()->syncWithoutDetaching($selection);
                }
            });
        });
    }
}
