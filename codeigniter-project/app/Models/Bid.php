<?php

namespace App\Models;

use CodeIgniter\Model;

class Bid extends Model
{
    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'job_id',
        'amount',
        'proposal',
        'delivery_time',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'amount' => 'required|numeric|greater_than[0]',
        'proposal' => 'required|min_length[50]|max_length[2000]',
        'delivery_time' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'amount' => [
            'required' => 'Please enter your bid amount',
            'numeric' => 'Bid amount must be a number',
            'greater_than' => 'Bid amount must be greater than 0'
        ],
        'proposal' => [
            'required' => 'Please provide a proposal',
            'min_length' => 'Your proposal must be at least 50 characters long',
            'max_length' => 'Your proposal cannot exceed 2000 characters'
        ],
        'delivery_time' => [
            'required' => 'Please specify the delivery time in days',
            'numeric' => 'Delivery time must be a number',
            'greater_than' => 'Delivery time must be greater than 0'
        ]
    ];

    public function getRecentBidsForClientJobs($clientId)
    {
        return $this->select('bids.*, jobs.title as job_title, users.name as freelancer_name')
            ->join('jobs', 'jobs.id = bids.job_id')
            ->join('users', 'users.id = bids.user_id')
            ->where('jobs.user_id', $clientId)
            ->orderBy('bids.created_at', 'DESC')
            ->limit(10)
            ->find();
    }

    public function getBidWithDetails($id)
    {
        return $this->select('bids.*, users.name as freelancer_name, users.email as freelancer_email, jobs.title as job_title')
            ->join('users', 'users.id = bids.user_id')
            ->join('jobs', 'jobs.id = bids.job_id')
            ->where('bids.id', $id)
            ->where('bids.deleted_at IS NULL')
            ->first();
    }

    public function getBidsForJob($jobId)
    {
        return $this->select('bids.*, users.name as freelancer_name')
            ->join('users', 'users.id = bids.user_id')
            ->where('job_id', $jobId)
            ->where('bids.deleted_at IS NULL')
            ->findAll();
    }

    public function hasUserBidOnJob($userId, $jobId)
    {
        return $this->where('user_id', $userId)
            ->where('job_id', $jobId)
            ->where('deleted_at IS NULL')
            ->countAllResults() > 0;
    }

    public function getFreelancerBids($userId)
    {
        return $this->select('bids.*, jobs.title as job_title, jobs.status as job_status, users.name as client_name')
            ->join('jobs', 'jobs.id = bids.job_id')
            ->join('users', 'users.id = jobs.user_id')
            ->where('bids.user_id', $userId)
            ->where('bids.deleted_at IS NULL')
            ->findAll();
    }
} 