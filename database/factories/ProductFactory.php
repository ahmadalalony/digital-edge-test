<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $titleEn = fake()->unique()->words(3, true);
        $titleAr = 'منتج '.fake()->unique()->numerify('###');

        return [
            'title_en' => ucfirst($titleEn),
            'title_ar' => $titleAr,
            'slug' => Str::slug($titleEn).'-'.uniqid(),
            'description_en' => fake()->optional()->paragraph(),
            'description_ar' => fake()->optional()->paragraph(),
            'price' => (float) fake()->randomFloat(2, 5, 9999),
            'primary_image' => fake()->optional()->imageUrl(800, 600, 'product', true),
            'other_images' => fake()->optional()->randomElements([
                fake()->imageUrl(800, 600, 'product', true),
                fake()->imageUrl(800, 600, 'product', true),
                fake()->imageUrl(800, 600, 'product', true),
            ], fake()->numberBetween(0, 3)),
            // creator will be assigned in seeder; provide fallback to valid user id to satisfy FK
            'created_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
        ];
    }
}
