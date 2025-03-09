<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'budget',
        'deadline',
        'skills',
        'status',
    ];

    protected $casts = [
        'skills' => 'array',
        'deadline' => 'datetime',
        'budget' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
} 