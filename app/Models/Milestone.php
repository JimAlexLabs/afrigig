<?php

namespace App\Models;

use CodeIgniter\Model;

class Milestone extends Model
{
    protected $table = 'milestones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'job_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status',
        'completed_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'attachments'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'job_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'amount' => 'required|numeric|greater_than[0]',
        'due_date' => 'required|valid_date',
        'status' => 'required|in_list[pending,in_progress,completed,approved,rejected]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Milestone title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Milestone description is required',
            'min_length' => 'Description must be at least 10 characters long'
        ],
        'amount' => [
            'required' => 'Milestone amount is required',
            'numeric' => 'Amount must be a number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'due_date' => [
            'required' => 'Due date is required',
            'valid_date' => 'Please enter a valid date'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    // Query Methods
    public function getJobMilestones(int $jobId)
    {
        return $this->where('job_id', $jobId)
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }

    public function getPendingMilestones()
    {
        return $this->where('status', 'pending')
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }

    public function getCompletedMilestones()
    {
        return $this->where('status', 'completed')
                   ->orderBy('completed_at', 'DESC')
                   ->findAll();
    }

    public function getApprovedMilestones()
    {
        return $this->where('status', 'approved')
                   ->orderBy('approved_at', 'DESC')
                   ->findAll();
    }

    public function getRejectedMilestones()
    {
        return $this->where('status', 'rejected')
                   ->orderBy('rejected_at', 'DESC')
                   ->findAll();
    }

    public function getOverdueMilestones()
    {
        return $this->where('due_date <', date('Y-m-d'))
                   ->whereIn('status', ['pending', 'in_progress'])
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isOverdue(): bool
    {
        return strtotime($this->due_date) < time() && 
               in_array($this->status, ['pending', 'in_progress']);
    }

    public function start()
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update($this->id, [
            'status' => 'in_progress'
        ]);
    }

    public function complete()
    {
        if (!$this->isInProgress()) {
            return false;
        }

        return $this->update($this->id, [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function approve()
    {
        if (!$this->isCompleted()) {
            return false;
        }

        return $this->update($this->id, [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function reject(string $reason)
    {
        if (!$this->isCompleted()) {
            return false;
        }

        return $this->update($this->id, [
            'status' => 'rejected',
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ]);
    }

    public function getTotalAmount(int $jobId)
    {
        return $this->selectSum('amount')
                   ->where('job_id', $jobId)
                   ->get()
                   ->getRow()
                   ->amount ?? 0;
    }

    public function getCompletedAmount(int $jobId)
    {
        return $this->selectSum('amount')
                   ->where('job_id', $jobId)
                   ->where('status', 'approved')
                   ->get()
                   ->getRow()
                   ->amount ?? 0;
    }

    public function getRemainingAmount(int $jobId)
    {
        return $this->getTotalAmount($jobId) - $this->getCompletedAmount($jobId);
    }

    public function getProgress(int $jobId)
    {
        $total = $this->getTotalAmount($jobId);
        if ($total <= 0) {
            return 0;
        }
        return ($this->getCompletedAmount($jobId) / $total) * 100;
    }
} 