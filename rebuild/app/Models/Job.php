<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'freelance_jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'client_id',
        'freelancer_id',
        'category',
        'skills_required',
        'budget_min',
        'budget_max',
        'deadline',
        'status',
        'experience_level',
        'project_length',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'skills_required' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the client that posted the job.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the freelancer assigned to the job.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Get the bids for the job.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the milestones for the job.
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Get the messages for the job.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scope a query to only include active jobs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include jobs in a specific category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include jobs with a specific skill requirement.
     */
    public function scopeRequiresSkill($query, $skill)
    {
        return $query->where('skills_required', 'like', '%' . $skill . '%');
    }

    /**
     * Scope a query to only include jobs within a budget range.
     */
    public function scopeBudgetRange($query, $min, $max)
    {
        return $query->where('budget_min', '>=', $min)
                     ->where('budget_max', '<=', $max);
    }

    /**
     * Check if the job is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the job is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the job is assigned to a freelancer.
     */
    public function isAssigned()
    {
        return $this->freelancer_id !== null;
    }
} 