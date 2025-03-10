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
        'budget_min',
        'budget_max',
        'deadline',
        'skills_required',
        'status',
        'location',
        'job_type',
        'experience_level',
        'project_length',
        'attachments',
    ];

    protected $casts = [
        'skills_required' => 'array',
        'deadline' => 'datetime',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
} 