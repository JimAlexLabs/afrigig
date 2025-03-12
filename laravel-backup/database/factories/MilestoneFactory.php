<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilestoneFactory extends Factory
{
    public function definition(): array
    {
        $job = Job::factory()->create();
        $amount = $this->faker->numberBetween(100, $job->budget_max / 2);
        
        return [
            'job_id' => $job->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'amount' => $amount,
            'due_date' => $this->faker->dateTimeBetween('now', $job->deadline),
            'status' => 'pending',
            'deliverables' => $this->faker->words(3),
        ];
    }

    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }

    public function paid(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
            ];
        });
    }
} 