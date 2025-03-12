<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'job_id',
        'freelancer_id',
        'amount',
        'delivery_time',
        'proposal',
        'status',
        'attachments',
        'terms_accepted',
        'awarded_at',
        'completed_at'
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
        'freelancer_id' => 'required|integer',
        'amount' => 'required|numeric|greater_than[0]',
        'delivery_time' => 'required|integer|greater_than[0]',
        'proposal' => 'required|min_length[100]',
        'status' => 'required|in_list[pending,awarded,rejected,withdrawn,completed]',
        'terms_accepted' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'amount' => [
            'required' => 'Bid amount is required',
            'numeric' => 'Bid amount must be a number',
            'greater_than' => 'Bid amount must be greater than 0'
        ],
        'delivery_time' => [
            'required' => 'Delivery time is required',
            'integer' => 'Delivery time must be a number',
            'greater_than' => 'Delivery time must be greater than 0'
        ],
        'proposal' => [
            'required' => 'Proposal is required',
            'min_length' => 'Proposal must be at least 100 characters long'
        ],
        'terms_accepted' => [
            'required' => 'You must accept the terms and conditions',
            'in_list' => 'Invalid terms acceptance value'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    public function freelancer()
    {
        return $this->belongsTo('App\Models\User', 'freelancer_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    // Query Methods
    public function getFreelancerBids(int $freelancerId)
    {
        return $this->where('freelancer_id', $freelancerId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getJobBids(int $jobId)
    {
        return $this->where('job_id', $jobId)
                   ->orderBy('amount', 'ASC')
                   ->findAll();
    }

    public function getPendingBids()
    {
        return $this->where('status', 'pending')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getAwardedBids()
    {
        return $this->where('status', 'awarded')
                   ->orderBy('awarded_at', 'DESC')
                   ->findAll();
    }

    public function getCompletedBids()
    {
        return $this->where('status', 'completed')
                   ->orderBy('completed_at', 'DESC')
                   ->findAll();
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAwarded(): bool
    {
        return $this->status === 'awarded';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeAwarded(): bool
    {
        return $this->isPending() && $this->job()->where('status', 'open')->countAllResults() > 0;
    }

    public function award()
    {
        if (!$this->canBeAwarded()) {
            return false;
        }

        $this->update($this->id, [
            'status' => 'awarded',
            'awarded_at' => date('Y-m-d H:i:s')
        ]);

        $this->job()->update($this->job_id, [
            'status' => 'in_progress',
            'awarded_bid_id' => $this->id
        ]);

        return true;
    }

    public function complete()
    {
        if (!$this->isAwarded()) {
            return false;
        }

        $this->update($this->id, [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);

        $this->job()->update($this->job_id, [
            'status' => 'completed'
        ]);

        return true;
    }

    public function withdraw()
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update($this->id, [
            'status' => 'withdrawn'
        ]);
    }
} 