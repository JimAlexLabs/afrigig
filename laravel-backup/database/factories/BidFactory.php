<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
{
    public function definition(): array
    {
        $job = Job::factory()->create();
        
        return [
            'job_id' => $job->id,
            'freelancer_id' => User::factory()->create(['role' => 'freelancer'])->id,
            'amount' => $this->faker->numberBetween($job->budget_min, $job->budget_max),
            'proposal' => $this->faker->paragraphs(2, true),
            'delivery_time' => $this->faker->numberBetween(1, 30),
            'status' => 'pending',
        ];
    }

    public function accepted(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'accepted',
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
            ];
        });
    }
} 