<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['freelancer', 'client']),
            'phone' => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
            'bio' => $this->faker->paragraph(),
            'skills' => $this->faker->randomElements(['Writing', 'Editing', 'Research', 'Analysis'], 2),
            'portfolio_url' => $this->faker->url(),
            'avatar' => null,
            'is_verified' => false,
            'rating' => $this->faker->randomFloat(1, 0, 5),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
} 