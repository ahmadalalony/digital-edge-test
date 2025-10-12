<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected static ?string $password;


    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $isVerified = fake()->boolean(70);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => $isVerified ? now() : null,
            'verification_code' => $isVerified ? null : (string) fake()->unique()->numerify('######'),
            'phone' => fake()->unique()->numerify('+1##########'),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
