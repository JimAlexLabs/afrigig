<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'category',
        'skills_required',
        'budget_min',
        'budget_max',
        'deadline',
        'status', // 'open', 'in_progress', 'completed', 'cancelled'
        'experience_level', // 'entry', 'intermediate', 'expert'
        'project_length', // 'short', 'medium', 'long'
        'attachments',
    ];

    protected $casts = [
        'skills_required' => 'array',
        'deadline' => 'datetime',
        'budget_min' => 'float',
        'budget_max' => 'float',
        'attachments' => 'array',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id')->withDefault();
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
} 