<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'freelancer_id',
        'amount',
        'proposal',
        'timeline',
        'status',
        'is_hidden',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'timeline' => 'integer',
        'is_hidden' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the job that the bid is for.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the freelancer that made the bid.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Scope a query to only include accepted bids.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope a query to only include pending bids.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include rejected bids.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include hidden bids.
     */
    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }

    /**
     * Scope a query to only include featured bids.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Check if the bid is accepted.
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the bid is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the bid is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Calculate the premium features cost.
     */
    public function getPremiumCost()
    {
        $cost = 0;
        
        if ($this->is_hidden) {
            $cost += $this->job->budget_max > 500 ? 8 : 3;
        }
        
        if ($this->is_featured) {
            $cost += $this->job->budget_max > 500 ? 8 : 3;
        }
        
        return $cost;
    }
} 