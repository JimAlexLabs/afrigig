<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'bid_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status', // pending, in_progress, completed, approved
        'completion_date',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'completion_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
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
        return !is_null($this->approved_at);
    }

    public function start(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        return $this->update(['status' => 'in_progress']);
    }

    public function complete(): bool
    {
        if (!$this->isInProgress()) {
            return false;
        }

        return $this->update([
            'status' => 'completed',
            'completion_date' => now()
        ]);
    }

    public function approve(): bool
    {
        if (!$this->isCompleted() || $this->isApproved()) {
            return false;
        }

        return $this->update(['approved_at' => now()]);
    }
} 