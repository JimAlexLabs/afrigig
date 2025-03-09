<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['web', 'mobile', 'design', 'writing']),
            'skills_required' => $this->faker->randomElements(['PHP', 'Laravel', 'Vue.js', 'React', 'Node.js', 'Python', 'Java', 'UI/UX'], 3),
            'budget_min' => $min = $this->faker->numberBetween(100, 1000),
            'budget_max' => $this->faker->numberBetween($min, $min + 1000),
            'deadline' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'open',
            'experience_level' => $this->faker->randomElement(['entry', 'intermediate', 'expert']),
            'project_length' => $this->faker->randomElement(['short', 'medium', 'long']),
            'attachments' => [],
        ];
    }

    public function inProgress(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
                'freelancer_id' => User::factory()->create(['role' => 'freelancer'])->id,
            ];
        });
    }

    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'freelancer_id' => User::factory()->create(['role' => 'freelancer'])->id,
            ];
        });
    }
} 