<?php

namespace Database\Factories;

use App\Models\Milestone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $milestone = Milestone::factory()->create();
        
        return [
            'user_id' => $milestone->job->client_id,
            'milestone_id' => $milestone->id,
            'amount' => $milestone->amount,
            'payment_method' => $this->faker->randomElement(['mpesa', 'paypal']),
            'transaction_id' => Str::random(20),
            'status' => 'pending',
            'payment_details' => [],
        ];
    }

    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'payment_details' => [
                    'transaction_date' => now()->toDateTimeString(),
                    'receipt_number' => Str::random(10),
                ],
            ];
        });
    }

    public function failed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
                'payment_details' => [
                    'error_code' => $this->faker->randomNumber(4),
                    'error_message' => $this->faker->sentence(),
                ],
            ];
        });
    }

    public function mpesa(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'mpesa',
                'payment_details' => [
                    'CheckoutRequestID' => 'ws_CO_' . now()->format('YmdHis') . Str::random(4),
                ],
            ];
        });
    }

    public function paypal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'paypal',
                'payment_details' => [
                    'order_id' => 'ORDER_' . now()->format('YmdHis') . Str::random(4),
                ],
            ];
        });
    }
} 