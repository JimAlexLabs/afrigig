<?php

namespace App\Models;

use CodeIgniter\Model;

class Milestone extends Model
{
    protected $table = 'milestones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'job_id',
        'title',
        'description',
        'amount',
        'due_date',
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
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'amount' => 'required|numeric|greater_than[0]',
        'due_date' => 'required|valid_date',
        'status' => 'required|in_list[pending,completed,paid]',
    ];

    protected $validationMessages = [
        'job_id' => [
            'required' => 'Job ID is required',
            'integer' => 'Job ID must be an integer',
            'is_not_unique' => 'Job does not exist',
        ],
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters',
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 10 characters long',
        ],
        'amount' => [
            'required' => 'Amount is required',
            'numeric' => 'Amount must be a number',
            'greater_than' => 'Amount must be greater than 0',
        ],
        'due_date' => [
            'required' => 'Due date is required',
            'valid_date' => 'Please enter a valid date',
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: pending, completed, paid',
        ],
    ];

    public function getByJob(array $jobId)
    {
        return $this->where('job_id', $jobId['id'])
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }

    public function updateStatus(array $milestoneId, string $status)
    {
        return $this->update($milestoneId['id'], ['status' => $status]);
    }

    public function getTotalAmount(array $jobId)
    {
        return $this->selectSum('amount')
            ->where('job_id', $jobId['id'])
            ->first()['amount'] ?? 0;
    }

    public function getPaidAmount(array $jobId)
    {
        return $this->selectSum('amount')
            ->where('job_id', $jobId['id'])
            ->where('status', 'paid')
            ->first()['amount'] ?? 0;
    }

    public function getUpcomingMilestones()
    {
        return $this->where('status', 'pending')
            ->where('due_date >=', date('Y-m-d'))
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }

    public function getOverdueMilestones()
    {
        return $this->where('status', 'pending')
            ->where('due_date <', date('Y-m-d'))
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }
} 