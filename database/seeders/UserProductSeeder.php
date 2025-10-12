<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Create users first
            /** @var Collection<int, User> $users */
            $users = User::factory(100)->create();

            // Map of user IDs for fast random assignment
            $userIds = $users->pluck('id');

            // Create products with created_by set to a random user id
            /** @var Collection<int, Product> $products */
            $products = Product::factory()
                ->count(100)
                ->state(function () use ($userIds) {
                    return [
                        'created_by' => $userIds->random(),
                    ];
                })
                ->create();

            // Assign each product to a random user (pivot table product_user)
            // Efficiently using collection helpers and syncWithoutDetaching to avoid duplicate pairs
            $products->each(function (Product $product) use ($userIds) {
                $product->assignedUsers()->syncWithoutDetaching([$userIds->random()]);
            });
        });
    }
}
