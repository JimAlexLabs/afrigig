<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Investment Management',
                'description' => 'Write a comprehensive analysis of investment management strategies.',
                'requirements' => 'Strong background in finance and investment analysis',
                'benefits' => 'Flexible working hours, competitive pay',
                'category' => 'Finance',
                'skills_required' => ['Financial Analysis', 'Investment Research', 'Report Writing'],
                'budget_min' => 41.50,
                'budget_max' => 50.00,
                'deadline' => Carbon::now()->addDays(8)->addHours(18),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Finance Department',
            ],
            [
                'title' => 'Draft',
                'description' => 'Create a draft document for review and analysis.',
                'requirements' => 'Excellent writing and editing skills',
                'benefits' => 'Performance bonuses available',
                'category' => 'Writing',
                'skills_required' => ['Content Writing', 'Editing', 'Proofreading'],
                'budget_min' => 39.00,
                'budget_max' => 45.00,
                'deadline' => Carbon::now()->addDays(2)->addHours(8),
                'status' => 'open',
                'experience_level' => 'intermediate',
                'project_length' => 'short',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Content Team',
            ],
            [
                'title' => 'Legal Issues in Psychology',
                'description' => 'Research and write about legal considerations in psychological practice.',
                'requirements' => 'Knowledge of both legal and psychological principles',
                'benefits' => 'Access to premium research databases',
                'category' => 'Legal',
                'skills_required' => ['Legal Research', 'Psychology', 'Academic Writing'],
                'budget_min' => 15.00,
                'budget_max' => 20.00,
                'deadline' => Carbon::now()->addDays(11)->addHours(2),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Legal Department',
            ],
            [
                'title' => 'Discussion #2 Volcy',
                'description' => 'Write a discussion post about specified topics.',
                'requirements' => 'Strong academic writing skills',
                'benefits' => 'Regular work opportunities',
                'category' => 'Academic',
                'skills_required' => ['Academic Writing', 'Research', 'Discussion'],
                'budget_min' => 11.00,
                'budget_max' => 15.00,
                'deadline' => Carbon::now()->addDays(1)->addHours(12),
                'status' => 'open',
                'experience_level' => 'entry',
                'project_length' => 'short',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Academic Team',
            ],
            [
                'title' => 'Restaurant Review Analysis',
                'description' => 'Analyze and summarize restaurant reviews for market research.',
                'requirements' => 'Experience in data analysis and market research',
                'benefits' => 'Future project opportunities',
                'category' => 'Business',
                'skills_required' => ['Data Analysis', 'Report Writing', 'Market Research'],
                'budget_min' => 11.00,
                'budget_max' => 15.00,
                'deadline' => Carbon::now()->addDays(3),
                'status' => 'open',
                'experience_level' => 'intermediate',
                'project_length' => 'short',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Research Team',
            ],
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
} 