<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'freelancer_id',
        'amount',
        'proposal',
        'delivery_time',
        'status', // 'pending', 'accepted', 'rejected'
    ];

    protected $casts = [
        'amount' => 'float',
        'delivery_time' => 'integer', // in days
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
} 