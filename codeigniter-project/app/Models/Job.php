<?php

namespace App\Models;

use CodeIgniter\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'title',
        'description',
        'requirements',
        'budget',
        'duration',
        'category',
        'status',
        'deadline'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|min_length[10]|max_length[255]',
        'description' => 'required|min_length[50]',
        'requirements' => 'required',
        'budget' => 'required|numeric|greater_than[0]',
        'duration' => 'required|numeric|greater_than[0]',
        'category' => 'required',
        'deadline' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Please provide a job title',
            'min_length' => 'Job title must be at least 10 characters long',
            'max_length' => 'Job title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Please provide a job description',
            'min_length' => 'Job description must be at least 50 characters long'
        ],
        'requirements' => [
            'required' => 'Please list the job requirements'
        ],
        'budget' => [
            'required' => 'Please specify the budget',
            'numeric' => 'Budget must be a number',
            'greater_than' => 'Budget must be greater than 0'
        ],
        'duration' => [
            'required' => 'Please specify the project duration in days',
            'numeric' => 'Duration must be a number',
            'greater_than' => 'Duration must be greater than 0'
        ],
        'category' => [
            'required' => 'Please select a job category'
        ],
        'deadline' => [
            'required' => 'Please specify the application deadline',
            'valid_date' => 'Please provide a valid date'
        ]
    ];

    public function getAvailableJobs()
    {
        return $this->where('status', 'open')
            ->where('deadline >=', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getJobWithDetails($jobId)
    {
        $job = $this->find($jobId);
        if (!$job) {
            return null;
        }

        // Get client details
        $userModel = new User();
        $client = $userModel->find($job['user_id']);
        $job['client'] = $client;

        // Get bid count and average bid
        $bidModel = new Bid();
        $job['bid_count'] = $bidModel->where('job_id', $jobId)->countAllResults();
        $job['average_bid'] = $bidModel->where('job_id', $jobId)->selectAvg('amount')->get()->getRow()->amount ?? 0;

        return $job;
    }

    public function searchJobs($keyword, $category = null, $minBudget = null, $maxBudget = null)
    {
        $builder = $this->where('status', 'open')
            ->where('deadline >=', date('Y-m-d'))
            ->groupStart()
                ->like('title', $keyword)
                ->orLike('description', $keyword)
                ->orLike('requirements', $keyword)
            ->groupEnd();

        if ($category) {
            $builder->where('category', $category);
        }

        if ($minBudget) {
            $builder->where('budget >=', $minBudget);
        }

        if ($maxBudget) {
            $builder->where('budget <=', $maxBudget);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
} 