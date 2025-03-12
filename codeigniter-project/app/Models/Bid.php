<?php

namespace App\Models;

use CodeIgniter\Model;

class Bid extends Model
{
    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'job_id',
        'user_id',
        'amount',
        'proposal',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'job_id' => 'required|integer|is_not_unique[jobs.id]',
        'user_id' => 'required|integer|is_not_unique[users.id]',
        'amount' => 'required|numeric|greater_than[0]',
        'proposal' => 'required|min_length[50]',
        'status' => 'required|in_list[pending,accepted,rejected]',
    ];

    protected $validationMessages = [
        'job_id' => [
            'required' => 'Job ID is required',
            'integer' => 'Job ID must be an integer',
            'is_not_unique' => 'Job does not exist',
        ],
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer',
            'is_not_unique' => 'User does not exist',
        ],
        'amount' => [
            'required' => 'Amount is required',
            'numeric' => 'Amount must be a number',
            'greater_than' => 'Amount must be greater than 0',
        ],
        'proposal' => [
            'required' => 'Proposal is required',
            'min_length' => 'Proposal must be at least 50 characters long',
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: pending, accepted, rejected',
        ],
    ];

    public function getWithDetails()
    {
        return $this->select('bids.*, users.name as freelancer_name, jobs.title as job_title')
            ->join('users', 'users.id = bids.user_id')
            ->join('jobs', 'jobs.id = bids.job_id')
            ->findAll();
    }

    public function getByJob(array $jobId)
    {
        return $this->where('job_id', $jobId['id'])->findAll();
    }

    public function getByUser(array $userId)
    {
        return $this->where('user_id', $userId['id'])->findAll();
    }

    public function updateStatus(array $bidId, string $status)
    {
        return $this->update($bidId['id'], ['status' => $status]);
    }

    public function getAcceptedBid(array $jobId)
    {
        return $this->where('job_id', $jobId['id'])
            ->where('status', 'accepted')
            ->first();
    }

    public function hasBidForJob(array $userId, array $jobId)
    {
        return $this->where('user_id', $userId['id'])
            ->where('job_id', $jobId['id'])
            ->countAllResults() > 0;
    }
} 