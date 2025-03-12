<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'freelancer_id',
        'amount',
        'delivery_time',
        'proposal',
        'status', // pending, accepted, rejected, withdrawn
        'is_featured',
        'is_sealed',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'delivery_time' => 'integer', // in days
        'is_featured' => 'boolean',
        'is_sealed' => 'boolean',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function canWithdraw(): bool
    {
        return $this->isPending();
    }

    public function accept(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'accepted']);
        $this->job->awardBid($this);

        return true;
    }

    public function reject(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update(['status' => 'rejected']);
    }

    public function withdraw(): bool
    {
        if (!$this->canWithdraw()) {
            return false;
        }

        return $this->update(['status' => 'withdrawn']);
    }
} 