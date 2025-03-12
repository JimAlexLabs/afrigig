<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'title',
        'description',
        'client_id',
        'category_id',
        'budget_min',
        'budget_max',
        'duration',
        'skills_required',
        'experience_level',
        'project_type',
        'status',
        'attachments',
        'visibility',
        'featured_until',
        'awarded_bid_id',
        'completed_at',
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
        'client_id' => 'required|integer',
        'category_id' => 'required|integer',
        'budget_min' => 'required|numeric|greater_than[0]',
        'budget_max' => 'required|numeric|greater_than[budget_min]',
        'duration' => 'required|in_list[less_than_1_month,1_to_3_months,3_to_6_months,more_than_6_months]',
        'experience_level' => 'required|in_list[entry,intermediate,expert]',
        'project_type' => 'required|in_list[fixed,hourly]',
        'status' => 'required|in_list[draft,open,in_progress,completed,cancelled]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Job title is required',
            'min_length' => 'Job title must be at least 10 characters long',
            'max_length' => 'Job title cannot exceed 255 characters',
        ],
        'description' => [
            'required' => 'Job description is required',
            'min_length' => 'Job description must be at least 50 characters long',
        ],
        'budget_min' => [
            'required' => 'Minimum budget is required',
            'numeric' => 'Minimum budget must be a number',
            'greater_than' => 'Minimum budget must be greater than 0',
        ],
        'budget_max' => [
            'required' => 'Maximum budget is required',
            'numeric' => 'Maximum budget must be a number',
            'greater_than' => 'Maximum budget must be greater than minimum budget',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get the client that owns the job
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the category of the job
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the bids for the job
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the awarded bid for the job
     */
    public function awardedBid()
    {
        return $this->hasOne(Bid::class, 'id', 'awarded_bid_id');
    }

    /**
     * Scope a query to only include open jobs
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include featured jobs
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured_until >', date('Y-m-d H:i:s'));
    }

    /**
     * Check if the job is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if the job can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    /**
     * Check if the job can be awarded
     */
    public function canBeAwarded(): bool
    {
        return $this->status === 'open' && $this->bids()->countAllResults() > 0;
    }

    /**
     * Mark the job as completed
     */
    public function markAsCompleted()
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }
} 