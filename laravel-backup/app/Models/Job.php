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

    protected $table = 'freelance_jobs';

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'freelancer_id',
        'category_id',
        'budget_min',
        'budget_max',
        'deadline',
        'required_skills',
        'experience_level', // entry, intermediate, expert
        'project_length', // short, medium, long
        'project_type', // fixed, hourly
        'status', // open, in_progress, completed, cancelled
        'attachments',
        'is_featured',
        'is_urgent',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'deadline' => 'datetime',
        'required_skills' => 'array',
        'attachments' => 'array',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    /**
     * Get the client that owns the job
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the freelancer assigned to the job
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
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
     * Get the milestones for the job
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
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
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include urgent jobs
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Check if the job is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if a user can bid on the job
     */
    public function canBid(User $user): bool
    {
        return $this->isOpen() && 
               $user->isFreelancer() && 
               $user->is_verified && 
               $this->client_id !== $user->id;
    }

    /**
     * Check if the user has already bid on the job
     */
    public function hasUserBid(User $user): bool
    {
        return $this->bids()->where('freelancer_id', $user->id)->exists();
    }

    /**
     * Award the job to a freelancer
     */
    public function awardBid(Bid $bid): bool
    {
        if (!$this->isOpen() || $bid->job_id !== $this->id) {
            return false;
        }

        $this->update([
            'freelancer_id' => $bid->freelancer_id,
            'status' => 'in_progress'
        ]);

        return true;
    }
} 