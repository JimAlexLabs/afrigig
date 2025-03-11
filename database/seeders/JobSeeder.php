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
            // Finance Category
            [
                'title' => 'Investment Management Analysis',
                'description' => 'Write a comprehensive analysis of investment management strategies and market trends.',
                'requirements' => 'Strong background in finance and investment analysis',
                'benefits' => 'Flexible working hours, competitive pay',
                'category' => 'Finance',
                'skills_required' => ['Financial Analysis', 'Investment Research', 'Report Writing'],
                'budget_min' => 41.50,
                'budget_max' => 50.00,
                'deadline' => Carbon::now()->addDays(8),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Finance Department',
            ],
            [
                'title' => 'Financial Risk Assessment',
                'description' => 'Conduct a thorough risk assessment for a multinational corporation.',
                'requirements' => 'Experience in risk management and financial modeling',
                'benefits' => 'Performance bonus, flexible schedule',
                'category' => 'Finance',
                'skills_required' => ['Risk Management', 'Financial Modeling', 'Data Analysis'],
                'budget_min' => 55.00,
                'budget_max' => 70.00,
                'deadline' => Carbon::now()->addDays(5),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'long',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Risk Management Team',
            ],

            // Writing Category
            [
                'title' => 'Technical Documentation',
                'description' => 'Create comprehensive technical documentation for a software product.',
                'requirements' => 'Experience in technical writing and software documentation',
                'benefits' => 'Ongoing work opportunities',
                'category' => 'Writing',
                'skills_required' => ['Technical Writing', 'Documentation', 'Software Knowledge'],
                'budget_min' => 35.00,
                'budget_max' => 45.00,
                'deadline' => Carbon::now()->addDays(7),
                'status' => 'open',
                'experience_level' => 'intermediate',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Tech Documentation Team',
            ],
            [
                'title' => 'Content Strategy Development',
                'description' => 'Develop a comprehensive content strategy for a digital marketing campaign.',
                'requirements' => 'Proven experience in content strategy and SEO',
                'benefits' => 'Long-term collaboration opportunity',
                'category' => 'Writing',
                'skills_required' => ['Content Strategy', 'SEO', 'Digital Marketing'],
                'budget_min' => 40.00,
                'budget_max' => 50.00,
                'deadline' => Carbon::now()->addDays(10),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'long',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Marketing Department',
            ],

            // Legal Category
            [
                'title' => 'Legal Research Project',
                'description' => 'Conduct legal research on international business regulations.',
                'requirements' => 'Law degree and research experience required',
                'benefits' => 'Premium compensation for expertise',
                'category' => 'Legal',
                'skills_required' => ['Legal Research', 'International Law', 'Business Law'],
                'budget_min' => 60.00,
                'budget_max' => 80.00,
                'deadline' => Carbon::now()->addDays(14),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'long',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Legal Department',
            ],

            // Academic Category
            [
                'title' => 'Research Methodology Review',
                'description' => 'Review and analyze research methodologies for academic studies.',
                'requirements' => 'PhD or equivalent research experience',
                'benefits' => 'Academic publication opportunity',
                'category' => 'Academic',
                'skills_required' => ['Research Methods', 'Data Analysis', 'Academic Writing'],
                'budget_min' => 45.00,
                'budget_max' => 55.00,
                'deadline' => Carbon::now()->addDays(9),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Research Institute',
            ],

            // Business Category
            [
                'title' => 'Market Analysis Report',
                'description' => 'Create a comprehensive market analysis report for an emerging industry.',
                'requirements' => 'Experience in market research and analysis',
                'benefits' => 'Future project opportunities',
                'category' => 'Business',
                'skills_required' => ['Market Research', 'Data Analysis', 'Report Writing'],
                'budget_min' => 50.00,
                'budget_max' => 65.00,
                'deadline' => Carbon::now()->addDays(6),
                'status' => 'open',
                'experience_level' => 'intermediate',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Business Strategy Team',
            ],

            // Technology Category
            [
                'title' => 'Software Documentation',
                'description' => 'Create user documentation for a new software product.',
                'requirements' => 'Technical writing experience in software industry',
                'benefits' => 'Long-term contract potential',
                'category' => 'Technology',
                'skills_required' => ['Technical Writing', 'Software Knowledge', 'Documentation'],
                'budget_min' => 45.00,
                'budget_max' => 60.00,
                'deadline' => Carbon::now()->addDays(12),
                'status' => 'open',
                'experience_level' => 'intermediate',
                'project_length' => 'medium',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Tech Documentation Team',
            ],

            // Healthcare Category
            [
                'title' => 'Medical Content Writing',
                'description' => 'Create accurate medical content for healthcare professionals.',
                'requirements' => 'Medical writing experience required',
                'benefits' => 'Ongoing work availability',
                'category' => 'Healthcare',
                'skills_required' => ['Medical Writing', 'Research', 'Healthcare Knowledge'],
                'budget_min' => 55.00,
                'budget_max' => 70.00,
                'deadline' => Carbon::now()->addDays(7),
                'status' => 'open',
                'experience_level' => 'expert',
                'project_length' => 'long',
                'location' => 'Remote',
                'job_type' => 'contract',
                'posted_by' => 'Healthcare Communications',
            ],
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
} 