<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@afrigig.com',
            'password' => Hash::make('admin123456'),
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
            'rating' => 5.0,
            'registration_fee_paid' => true,
            'payment_verified' => true,
            'availability_status' => 'available',
            'completed_jobs_count' => 0,
            'success_rate' => 100.0
        ]);
    }
} 